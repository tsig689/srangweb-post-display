<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_GitHub_Updater {
	private $plugin_file;
	private $plugin_basename;
	private $current_version;
	private $github_user;
	private $github_repo;
	private $cache_key;
	private $release_data = null;

	public function __construct( $plugin_file, $current_version, $github_user, $github_repo ) {
		$this->plugin_file     = $plugin_file;
		$this->plugin_basename = plugin_basename( $plugin_file );
		$this->current_version = $current_version;
		$this->github_user     = $github_user;
		$this->github_repo     = $github_repo;
		$this->cache_key       = 'spd_github_release_' . md5( $github_user . '/' . $github_repo );
	}

	public function init() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'inject_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugins_api' ), 20, 3 );
		add_filter( 'upgrader_package_options', array( $this, 'fix_destination_name' ) );
		add_action( 'upgrader_process_complete', array( $this, 'purge_cache_after_upgrade' ), 10, 2 );
	}

	private function request_release_data() {
		if ( null !== $this->release_data ) {
			return $this->release_data;
		}

		$cached = get_site_transient( $this->cache_key );
		if ( is_array( $cached ) && ! empty( $cached['tag_name'] ) ) {
			$this->release_data = $cached;
			return $this->release_data;
		}

		$url = sprintf( 'https://api.github.com/repos/%1$s/%2$s/releases/latest', $this->github_user, $this->github_repo );
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 15,
				'headers' => array(
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url( '/' ),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['tag_name'] ) ) {
			return false;
		}

		$data = array(
			'tag_name'     => (string) $body['tag_name'],
			'name'         => isset( $body['name'] ) ? (string) $body['name'] : '',
			'body'         => isset( $body['body'] ) ? (string) $body['body'] : '',
			'html_url'     => isset( $body['html_url'] ) ? (string) $body['html_url'] : '',
			'published_at' => isset( $body['published_at'] ) ? (string) $body['published_at'] : '',
			'zipball_url'  => isset( $body['zipball_url'] ) ? (string) $body['zipball_url'] : '',
			'package'      => $this->resolve_package_url( $body ),
		);

		set_site_transient( $this->cache_key, $data, 6 * HOUR_IN_SECONDS );
		$this->release_data = $data;
		return $this->release_data;
	}

	private function resolve_package_url( $release ) {
		$expected = sprintf( '%s-v%s.zip', $this->github_repo, ltrim( (string) $release['tag_name'], 'v' ) );

		if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
			foreach ( $release['assets'] as $asset ) {
				if ( empty( $asset['browser_download_url'] ) ) {
					continue;
				}
				if ( ! empty( $asset['name'] ) && $asset['name'] === $expected ) {
					return (string) $asset['browser_download_url'];
				}
			}

			foreach ( $release['assets'] as $asset ) {
				if ( empty( $asset['browser_download_url'] ) ) {
					continue;
				}
				if ( ! empty( $asset['name'] ) && preg_match( '/\.zip$/i', $asset['name'] ) ) {
					return (string) $asset['browser_download_url'];
				}
			}
		}

		return ! empty( $release['zipball_url'] ) ? (string) $release['zipball_url'] : '';
	}

	public function inject_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			$transient = new stdClass();
		}

		if ( empty( $transient->checked ) || ! is_array( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->request_release_data();
		if ( empty( $release['tag_name'] ) || empty( $release['package'] ) ) {
			return $transient;
		}

		$new_version = ltrim( $release['tag_name'], 'v' );
		if ( ! version_compare( $new_version, $this->current_version, '>' ) ) {
			return $transient;
		}

		$transient->response[ $this->plugin_basename ] = (object) array(
			'slug'         => dirname( $this->plugin_basename ),
			'plugin'       => $this->plugin_basename,
			'new_version'  => $new_version,
			'url'          => $release['html_url'],
			'package'      => $release['package'],
			'requires_php' => '7.4',
		);

		return $transient;
	}

	public function plugins_api( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || empty( $args->slug ) || $args->slug !== dirname( $this->plugin_basename ) ) {
			return $result;
		}

		$release = $this->request_release_data();
		if ( empty( $release['tag_name'] ) ) {
			return $result;
		}

		$new_version = ltrim( $release['tag_name'], 'v' );
		return (object) array(
			'name'          => 'Srangweb Post Display',
			'slug'          => dirname( $this->plugin_basename ),
			'version'       => $new_version,
			'author'        => '<a href="https://www.srangweb.com/">Srangweb</a>',
			'homepage'      => 'https://github.com/' . $this->github_user . '/' . $this->github_repo,
			'requires'      => '6.0',
			'requires_php'  => '7.4',
			'download_link' => $release['package'],
			'trunk'         => $release['package'],
			'last_updated'  => ! empty( $release['published_at'] ) ? gmdate( 'Y-m-d', strtotime( $release['published_at'] ) ) : '',
			'sections'      => array(
				'description' => 'Display WordPress posts with category filtering, pagination, view counts, and title-only mode.',
				'changelog'   => wp_kses_post( nl2br( $release['body'] ) ),
			),
		);
	}

	public function fix_destination_name( $options ) {
		if ( empty( $options['hook_extra']['plugin'] ) || $options['hook_extra']['plugin'] !== $this->plugin_basename ) {
			return $options;
		}

		$options['destination'] = WP_PLUGIN_DIR . '/' . dirname( $this->plugin_basename );
		$options['clear_destination'] = true;
		return $options;
	}

	public function purge_cache_after_upgrade( $upgrader, $hook_extra ) {
		if ( empty( $hook_extra['action'] ) || empty( $hook_extra['type'] ) ) {
			return;
		}
		if ( 'update' !== $hook_extra['action'] || 'plugin' !== $hook_extra['type'] ) {
			return;
		}
		if ( empty( $hook_extra['plugins'] ) || ! in_array( $this->plugin_basename, (array) $hook_extra['plugins'], true ) ) {
			return;
		}
		delete_site_transient( $this->cache_key );
	}
}
