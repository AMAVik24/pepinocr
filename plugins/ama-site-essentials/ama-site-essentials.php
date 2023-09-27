<?php
/**
 * Plugin Name: AMA - Site Essentials
 * Plugin URI: https://andreasmasis.com/plugins/pdev
 * Description: All the basics needed for a solid and secure website.
 * Version: 1.0.0
 * Requires at least: 5.3
 * Requires PHP: 5.6
 * Author: Andreas Masis
 * Author URI: https://andreasmasis.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pdev
 * Domain Path: /public/lang
 */
/*
Copyright (C) <2023> <Andreas Masis> 

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 */

namespace AmaSiteEssentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Define the current plugin version
define( 'AMA_SITE_ESSENTIALS_VERSION', '1.0.0' );

// Define a constant for the plugin directory path
define('AMA_SITE_ESSENTIALS_DIR', plugin_dir_path(__FILE__));

require_once AMA_SITE_ESSENTIALS_DIR . 'includes/class-autoloader.php';
new \AmaSiteEssentials\Includes\Autoloader();

// Register activation and deactivation hooks invoking the methods statically, so creating an instance of the classes is not needed
function main_activate() {
	\AmaSiteEssentials\Includes\Activator::activate();
}
	
register_activation_hook( __FILE__, __NAMESPACE__ . '\main_activate');
	
function main_deactivate() {
	\AmaSiteEssentials\Includes\Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\main_deactivate');

//Includes the core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.

//Begins execution of the plugin.
function run_main() {
	$plugin = new \AmaSiteEssentials\Includes\Main();
	$plugin->run();
}
run_main();
