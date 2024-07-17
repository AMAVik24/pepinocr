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
	 * SEO RELATED FUNCTIONS
	 */

	// Generates a custom meta description to be added to each page, based on if it's the home page or any post.

	public function add_custom_meta_description_and_schema() {

		$content_to_be_added = '';

		if ( is_home() || is_front_page() ) {
			// Generate JSON-LD schema for the homepage
			$content_to_be_added = $this->generate_json_ld_schema();
			
			$meta_description = get_option( 'ama_site_essentials_custom_home_meta_description' );
		}

		else {
			// Get the saved meta description for the current post
			$meta_description = get_post_meta( get_the_ID(), '_ama_site_essentials_meta_description', true );
		}
		// Check if a meta description exists
		if (!empty( $meta_description )) {
			$content_to_be_added .= '<meta name="description" content="' . esc_attr( $meta_description ) . '">';
		}
		return $content_to_be_added;
	}

	// Outputs the meta description and JSON-LD schema to be used in the wp_head hook
	public function output_custom_meta_description_and_schema() {
    echo $this->add_custom_meta_description_and_schema();
	}

	// Generates the schema markup based on the options stored in the plugin settings page

	function generate_json_ld_schema() {

		$prepared_data = $this -> prepare_data_for_json();

		// Define your schema data using the provided fields
		$schema = array(
			'@context' => 'http://schema.org',
			'@type' => 'LocalBusiness',
			'address' => array(
				'@type' => 'PostalAddress',
				'streetAddress' => get_option('ama_site_essentials_business_physical_address'),
				'addressRegion' => get_option('ama_site_essentials_business_region'),
				'postalCode' => get_option('ama_site_essentials_business_postal_code'),
				'addressCountry' => get_option('ama_site_essentials_business_country')
			),
			'geo' => array(
				'@type' => 'GeoCoordinates',
				'latitude' => trim($prepared_data['latitude']),
				'longitude' => trim($prepared_data['longitude'])
			),
			'openingHoursSpecification' => array(
				array(
					'@type' => 'OpeningHoursSpecification',
					'dayOfWeek' => get_option('ama_site_essentials_business_open_days'),
					'opens' => get_option('ama_site_essentials_business_opening_hour'),
					'closes' => get_option('ama_site_essentials_business_closing_hour')
				),
			),
			'name' => get_option('blogname'),
			'url' => get_option('siteurl'),
			'telephone' => get_option('ama_site_essentials_business_phone_number'),
		);
	
		// Convert schema array to JSON format
		$json_ld = json_encode($schema);
	
		// Output the JSON-LD script tag in the head of the document
		return '<script type="application/ld+json">' . $json_ld . '</script>';
	}

	function prepare_data_for_json() {
		// Get the geolocation data from the option
		$geolocation_data = get_option('ama_site_essentials_business_geolocation');
		// Check if geolocation data exists and is not empty
		if ($geolocation_data) {
			// Split the geolocation string into latitude and longitude values
			$geolocation_values = explode(',', $geolocation_data);
			$latitude = trim($geolocation_values[0]);
            $longitude = trim($geolocation_values[1]);
		}
		// Return the data as an array
		return array('latitude' => $latitude, 'longitude' => $longitude);
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

	/**
	 * AESTHETICS RELATED FUNCTIONS
	 */


	// Defines what method of stylesheet enqueuing should be used based on the loading method used by the parent theme.

	function child_theme_enqueue_styles() {

		if (get_option( 'ama_site_essentials_parent_theme_loading_method' ) == "get_template") {


		wp_enqueue_style( 'child-style',
			get_stylesheet_uri(),
			array( get_option( 'ama_site_essentials_parent_theme_handle' ) ),
			wp_get_theme()->get( 'Version' ) // This only works if you have Version defined in the style header.
		);

		} elseif (get_option( 'ama_site_essentials_parent_theme_loading_method' ) == "get_stylesheet" || get_option( 'ama_site_essentials_parent_theme_loading_method' ) == "none") {

			
				$parenthandle = get_option( 'ama_site_essentials_parent_theme_handle' ); 
				$theme        = wp_get_theme();
				wp_enqueue_style( $parenthandle,
					get_template_directory_uri() . '/style.css',
					array(),  // If the parent theme code has a dependency, copy it to here.
					$theme->parent()->get( 'Version' )
				);
				wp_enqueue_style( 'child-style',
					get_stylesheet_uri(),
					array( $parenthandle ),
					$theme->get( 'Version' ) // This only works if you have Version defined in the style header.
				);
		} else {
			// Left blank intentionally since no enqueuing method for the child stylesheet is required.
		}

	}

	
	
}