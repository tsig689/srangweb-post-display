<?php
/**
 * Plugin Name: Srangweb Post Display
 * Description: Lightweight post display plugin with cards, views, category filter, pagination, and GitHub release auto-update.
 * Version: 1.1.9
 * Author: Srangweb
 * Text Domain: srangweb-post-display
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SPD_VERSION', '1.1.9' );
define( 'SPD_FILE', __FILE__ );
define( 'SPD_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPD_URL', plugin_dir_url( __FILE__ ) );
define( 'SPD_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPD_GITHUB_REPO', 'tsig689/srangweb-post-display' );

require_once SPD_PATH . 'includes/class-spd-helpers.php';
require_once SPD_PATH . 'includes/class-spd-pagination.php';
require_once SPD_PATH . 'includes/class-spd-views.php';
require_once SPD_PATH . 'includes/class-spd-filter.php';
require_once SPD_PATH . 'includes/class-spd-query.php';
require_once SPD_PATH . 'includes/class-spd-render.php';
require_once SPD_PATH . 'includes/class-spd-shortcode.php';
require_once SPD_PATH . 'includes/class-spd-github-updater.php';
require_once SPD_PATH . 'includes/class-spd-plugin.php';

function spd_boot_plugin() {
	$plugin = new SPD_Plugin();
	$plugin->init();
}
add_action( 'plugins_loaded', 'spd_boot_plugin' );
