<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */

namespace AmaSiteEssentials\Includes;

class Main {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'AMA_SITE_ESSENTIALS_VERSION' ) ) {
			$this->version = AMA_SITE_ESSENTIALS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ama-site-essentials';

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
		$plugin_admin = new \AmaSiteEssentials\Admin\Admin_Core( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_filter( 'authenticate', $plugin_admin, 'check_attempted_login', 30 );
		$this->loader->add_action( 'wp_login_failed', $plugin_admin, 'login_failed' ); 
		$this->loader->add_action( 'phpmailer_init', $plugin_admin, 'my_phpmailer_smtp' );
		$this->loader->add_filter( 'wp_mail', $plugin_admin,'add_address_to_bcc' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_meta_description_field' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_custom_meta_description_field' );
	}
	
	private function define_public_hooks() {
		$plugin_public = new \AmaSiteEssentials\Public\Public_Core( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'output_custom_meta_description_and_schema');
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_analytics_head_js');
		$this->loader->add_action( 'wp_body_open', $plugin_public, 'add_analytics_body_js');
		$this->loader->add_action( 'wp_head', $plugin_public,'add_noindex_tags');
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'child_theme_enqueue_styles' );
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