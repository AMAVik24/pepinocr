<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */

namespace AmaWooEssentials\Includes;

class Main {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'AMA_WOO_ESSENTIALS_VERSION' ) ) {
			$this->version = AMA_WOO_ESSENTIALS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ama-woo-essentials';

		$this->loader = new Loader();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function set_locale() {
		$plugin_i18n = new i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$plugin_admin = new \AmaWooEssentials\Admin\Admin_Core( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
	}
	
	private function define_public_hooks() {
		$plugin_public = new \AmaWooEssentials\Public\Public_Core( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'output_custom_meta_description');
		$this->loader->add_action( 'wp_nav_menu_items', $plugin_public, 'add_cart_icon_to_menu', 10 , 2);

	}


	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}

$core = new Main();