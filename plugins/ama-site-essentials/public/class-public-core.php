<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */

namespace AmaSiteEssentials\Public;

class Public_Core {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * SEO RELATED FUNCTIONS
	 */

	// Generates a custom meta description to be added to each page, based on if it's the home page or any post.

	public function add_custom_meta_description() {

		$content_to_be_added = '';

		if ( is_home() || is_front_page() ) {
			$meta_description = get_option( 'ama_site_essentials_custom_home_meta_description' );
			$content_to_be_added = '<meta name="description" content="' . esc_attr( $meta_description ) . '" />';
		}

		if ( is_single() || is_page() ) {
			$description = get_post_meta( get_the_id(), 'description', true );
			if ( ! empty( $description ) ) {
				$meta_description = esc_html( $description );
				$content_to_be_added = '<meta name="description" content="' . esc_attr( $meta_description ) . '" />';
			}
		}
		return $content_to_be_added;
	}

	// Outputs the meta description to be used in the wp_head hook

	public function output_custom_meta_description() {
		echo $this->add_custom_meta_description();
	}

	// Adds the GTM header tag to all the headers

	function add_analytics_head_js() {
		echo get_option( 'ama_site_essentials_gtm_header_tag' );
	}
		
	// Adds the GTM body tag to all the bodies
	
	function add_analytics_body_js() {
		echo get_option( 'ama_site_essentials_gtm_body_tag' );
	}


	//Add noindex to low value pages so that they don't show up in the search engine results (No one wants to look at page 37 of a a year's post)
	function add_noindex_tags(){
		# Get page number for paginated archives.
		$paged = intval( get_query_var( 'paged' ) );
		
		# Add noindex tag to all archive, search and 404 pages.
		if( is_archive() || is_search() || is_404() )
		echo '<meta name="robots" content="noindex,follow">';
			
		# Add noindex tag to homepage paginated pages.  
		if(( is_home() || is_front_page() ) && $paged >= 2 )
		echo '<meta name="robots" content="noindex,follow">';
	}

}
