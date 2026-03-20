<?php
/**
 * Plugin Name: Srangweb Post Display
 * Plugin URI: https://github.com/tsig689/srangweb-post-display
 * Description: Display WordPress posts with category filtering, pagination, view counts, and title-only mode.
 * Version: 2.1.6
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Srangweb
 * Author URI: https://www.srangweb.com/
 * Text Domain: srangweb-post-display
 * Update URI: https://github.com/tsig689/srangweb-post-display
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SPD_VERSION', '2.1.6' );
define( 'SPD_PLUGIN_FILE', __FILE__ );
define( 'SPD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SPD_PLUGIN_DIR . 'includes/class-spd-helpers.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-filter.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-pagination.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-query.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-render.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-shortcode.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-views.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-github-updater.php';
require_once SPD_PLUGIN_DIR . 'includes/class-spd-plugin.php';

function spd_boot_plugin() {
	$plugin = new SPD_Plugin();
	$plugin->init();

	$updater = new SPD_GitHub_Updater(
		SPD_PLUGIN_FILE,
		SPD_VERSION,
		'tsig689',
		'srangweb-post-display'
	);
	$updater->init();
}
add_action( 'plugins_loaded', 'spd_boot_plugin' );
