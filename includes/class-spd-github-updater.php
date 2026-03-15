<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_GitHub_Updater {

	private $plugin_file;
	private $plugin_basename;
	private $slug;
	private $repo;
	private $cache_key;
	private $cache_ttl = 3600;

	public function __construct( $plugin_file, $plugin_basename, $slug, $repo ) {
		$this->plugin_file     = $plugin_file;
		$this->plugin_basename = $plugin_basename;
		$this->slug            = $slug;
		$this->repo            = trim( (string) $repo );
		$this->cache_key       = 'spd_github_release_' . md5( $this->repo );
	}

	public function init() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'inject_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
	}

	public function inject_update( $transient ) {
		if ( empty( $transient ) || empty( $transient->checked ) || empty( $transient->checked[ $this->plugin_basename ] ) ) {
			return $transient;
		}

		$release = $this->get_release();

		if ( empty( $release['version'] ) || empty( $release['package'] ) ) {
			return $transient;
		}

		$current_version = $transient->checked[ $this->plugin_basename ];

		if ( version_compare( $release['version'], $current_version, '>' ) ) {
			$transient->response[ $this->plugin_basename ] = (object) array(
				'slug'        => $this->slug,
				'plugin'      => $this->plugin_basename,
				'new_version' => $release['version'],
				'package'     => $release['package'],
				'url'         => $release['url'],
			);
		}

		return $transient;
	}

	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || empty( $args->slug ) || $args->slug !== $this->slug ) {
			return $result;
		}

		$release = $this->get_release();

		if ( empty( $release['version'] ) ) {
			return $result;
		}

		return (object) array(
			'name'          => 'Srangweb Post Display',
			'slug'          => $this->slug,
			'version'       => $release['version'],
			'author'        => '<a href="' . esc_url( $release['url'] ) . '">Srangweb</a>',
			'homepage'      => $release['url'],
			'download_link' => $release['package'],
			'sections'      => array(
				'description' => '<p>Lightweight post display plugin with cards, views, category filter, pagination, and GitHub release auto-update.</p>',
				'changelog'   => ! empty( $release['notes'] ) ? wp_kses_post( wpautop( $release['notes'] ) ) : '<p>No changelog provided.</p>',
			),
		);
	}

	private function get_release() {
		$cached = get_site_transient( $this->cache_key );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		if ( empty( $this->repo ) || false !== strpos( $this->repo, 'CHANGE-ME' ) ) {
			return array();
		}

		$request = wp_remote_get(
			'https://api.github.com/repos/' . $this->repo . '/releases/latest',
			array(
				'headers' => array(
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url( '/' ),
				),
				'timeout' => 20,
			)
		);

		if ( is_wp_error( $request ) ) {
			return array();
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $request ) ) {
			return array();
		}

		$body = json_decode( wp_remote_retrieve_body( $request ), true );

		if ( empty( $body ) || ! is_array( $body ) ) {
			return array();
		}

		$package = $this->find_package_url( $body );

		if ( empty( $package ) ) {
			return array();
		}

		$version = '';
		if ( ! empty( $body['tag_name'] ) ) {
			$version = ltrim( (string) $body['tag_name'], 'vV' );
		}

		$data = array(
			'version' => $version,
			'package' => $package,
			'url'     => ! empty( $body['html_url'] ) ? $body['html_url'] : 'https://github.com/' . $this->repo,
			'notes'   => ! empty( $body['body'] ) ? $body['body'] : '',
		);

		set_site_transient( $this->cache_key, $data, $this->cache_ttl );

		return $data;
	}

	private function find_package_url( $release ) {
		if ( empty( $release['assets'] ) || ! is_array( $release['assets'] ) ) {
			return '';
		}

		foreach ( $release['assets'] as $asset ) {
			if ( ! empty( $asset['name'] ) && preg_match( '/\.zip$/i', $asset['name'] ) && ! empty( $asset['browser_download_url'] ) ) {
				return $asset['browser_download_url'];
			}
		}

		return '';
	}
}
