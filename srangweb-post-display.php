<?php
/*
Plugin Name: Srangweb Post Display
Description: Lightweight post display with cards, views counter, shortcode and GitHub auto update.
Version: 1.1.1
Author: Srangweb
*/

if (!defined('ABSPATH')) exit;

define('SPD_VERSION','1.1.1');
define('SPD_FILE',__FILE__);
define('SPD_PATH',plugin_dir_path(__FILE__));
define('SPD_URL',plugin_dir_url(__FILE__));
define('SPD_BASENAME',plugin_basename(__FILE__));

/* CHANGE THIS AFTER CREATING YOUR REPO */
define('SPD_GITHUB_REPO','https://github.com/tsig689/srangweb-post-display');

require_once SPD_PATH.'includes/views.php';
require_once SPD_PATH.'includes/query.php';
require_once SPD_PATH.'includes/render.php';
require_once SPD_PATH.'includes/shortcodes.php';
require_once SPD_PATH.'includes/updater.php';

add_action('wp',function(){
    if(is_admin() || !is_singular('post')) return;
    $post_id=get_queried_object_id();
    SPD_Views::increment($post_id);
});

add_action('wp_enqueue_scripts',function(){
    wp_enqueue_style('spd-style',SPD_URL.'assets/css/style.css',[],SPD_VERSION);
});

new SPD_Github_Updater(SPD_FILE,SPD_BASENAME,'srangweb-post-display',SPD_GITHUB_REPO);
