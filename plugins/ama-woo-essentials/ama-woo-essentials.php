<?php
/**
 * Plugin Name: AMA - Woo Essentials
 * Plugin URI: https://andreasmasis.com/plugins/pdev
 * Description: Completes WooCommercer with some basic SEO and functionalities. WooCommerce is required to use this plugin.
 * Version: 1.0.0
 * Requires at least: 5.3
 * Requires PHP: 5.6
 * Author: Andreas Masis
 * Author URI: https://andreasmasis.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ama-woo-essentials
 * Domain Path: /languages
 */
/*
Copyright (C) <2023> <Andreas Masis> 

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 */

namespace AmaWooEssentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Define the current plugin version
define( 'AMA_WOO_ESSENTIALS_VERSION', '1.0.0' );

// Define a constant for the plugin directory path
define('AMA_WOO_ESSENTIALS_DIR', plugin_dir_path(__FILE__));

require_once AMA_WOO_ESSENTIALS_DIR . 'includes/class-autoloader.php';
new \AmaWooEssentials\Includes\Autoloader();

//Includes the core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.

//Begins execution of the plugin.
function run_main() {
	//Include the plugin.php file so that the "is_plugin_active()" function is available.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		return; // WooCommerce is not active, don't proceed further
    }
	$plugin = new \AmaWooEssentials\Includes\Main();
	$plugin->run();
}
run_main();
