<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Plugin {
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		$shortcode = new SPD_Shortcode();
		$shortcode->register();

		SPD_Views::register_hooks();
	}

	public function enqueue_assets() {
		wp_enqueue_style(
			'spd-style',
			SPD_PLUGIN_URL . 'assets/css/spd.css',
			array(),
			SPD_VERSION
		);
	}
}
