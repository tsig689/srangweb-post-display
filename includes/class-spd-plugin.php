<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Plugin {

	public function init() {
		$this->register_assets();

		$shortcode = new SPD_Shortcode();
		$shortcode->register();

		add_action( 'wp', array( 'SPD_Views', 'maybe_track_view' ) );
		add_action( 'init', array( $this, 'maybe_setup_updater' ) );
	}

	public function register_assets() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				wp_enqueue_style(
					'spd-style',
					SPD_PLUGIN_URL . 'assets/css/spd.css',
					array(),
					SPD_VERSION
				);
			}
		);
	}

	public function maybe_setup_updater() {
		if ( class_exists( 'SPD_GitHub_Updater' ) ) {
			$updater = new SPD_GitHub_Updater();
			$updater->init();
		}
	}
}
