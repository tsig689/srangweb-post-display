<?php
/**
 * Plugin Name: Srangweb Post Display
 * Description: Lightweight post display plugin with shortcode, pagination, post views, and GitHub release auto-update support.
 * Version: 1.1.0
 * Author: Srangweb
 * Text Domain: srangweb-post-display
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SPD_VERSION', '1.1.0' );
define( 'SPD_FILE', __FILE__ );
define( 'SPD_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPD_URL', plugin_dir_url( __FILE__ ) );
define( 'SPD_BASENAME', plugin_basename( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| GitHub updater configuration
|--------------------------------------------------------------------------
| Change these 3 values after you create your GitHub repository.
| Example:
| SPD_GITHUB_REPO  => 'yourname/srangweb-post-display'
| SPD_GITHUB_TOKEN => '' for public repo
| SPD_GITHUB_ASSET => 'srangweb-post-display-v1.1.0.zip'
|
| Important:
| 1) Create a GitHub Release.
| 2) Attach the plugin ZIP file as a release asset.
| 3) The updater will use that ZIP asset for one-click updates in WordPress.
*/
define( 'SPD_GITHUB_REPO', 'tsig689/srangweb-post-display' );
define( 'SPD_GITHUB_TOKEN', '' ); // Optional. Usually leave blank for public repos.
define( 'SPD_GITHUB_ASSET', 'srangweb-post-display-v1.1.0.zip' );

require_once SPD_PATH . 'includes/class-spd-helpers.php';
require_once SPD_PATH . 'includes/class-spd-pagination.php';
require_once SPD_PATH . 'includes/class-spd-views.php';
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
