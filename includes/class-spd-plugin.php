<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Plugin {

	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp', array( $this, 'track_post_views' ) );

		$shortcode = new SPD_Shortcode();
		$shortcode->register();

		$updater = new SPD_GitHub_Updater(
			SPD_FILE,
			SPD_BASENAME,
			'srangweb-post-display',
			SPD_GITHUB_REPO
		);
		$updater->init();
	}

	public function enqueue_assets() {
		wp_enqueue_style(
			'spd-style',
			SPD_URL . 'assets/css/spd-style.css',
			array(),
			SPD_VERSION
		);
	}

	public function track_post_views() {
		if ( is_admin() || ! is_singular( 'post' ) ) {
			return;
		}

		$post_id = get_queried_object_id();

		if ( $post_id ) {
			SPD_Views::increment( $post_id );
		}
	}
}
