<?php
/*
Plugin Name: Fast Forward Api Builder
Plugin URI:  github.com/ff
Description: Foundation for api content and collections
Version:     1
Author:      www.fastforward.sh
Author URI:  https://www.fastforward.sh
Text Domain: wporg
Domain Path: /languages
License:     GPL2

{Plugin Name} is free software: you can redistribute it and/or modify
*/

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die;

include_once( plugin_dir_path(__FILE__) . 'controllers/status.php');
include_once( plugin_dir_path(__FILE__) . 'controllers/collections.php');
include_once( plugin_dir_path(__FILE__) . 'controllers/content.php');
include_once( plugin_dir_path(__FILE__) . 'hooks/admin.php');


/**
 * Register the custom routes for the tables
 *
 * @since 1.4
 */
function register_endpoints() {

  $status = new Status_API();
  $status->register_routes();

  $collections_route = new Collections_API();
	$collections_route->register_routes();
  
  $content_route = new Content_API();
  $content_route->register_routes();
  
}

add_action('rest_api_init', 'register_endpoints');