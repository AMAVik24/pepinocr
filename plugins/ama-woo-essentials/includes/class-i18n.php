<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */

namespace AmaWooEssentials\Includes;

class i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$plugin_rel_path = dirname( plugin_basename( dirname( __FILE__ ) ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
		load_plugin_textdomain( 'ama-woo-essentials', false, $plugin_rel_path );
	}
}
