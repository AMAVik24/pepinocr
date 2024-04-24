<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */

namespace AmaWooEssentials\Public;

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

	// Generates a meta description to the viewed product, based on its short description.

	public function add_custom_meta_description() {

		$content_to_be_added = '';

		if ( is_product() ) {
			
			// Get the post object for the product
			$product = wc_get_product( get_the_ID() );

			// Get the short description for the product
			$meta_description = $product->get_short_description();
		}
		// Check if a meta description exists
		if (!empty( $meta_description )) {
			$content_to_be_added .= '<meta name="description" content="' . esc_attr( $meta_description ) . '">';
		}
		return $content_to_be_added;
	}

	// Outputs the meta description and JSON-LD schema to be used in the wp_head hook
	public function output_custom_meta_description() {
    echo $this->add_custom_meta_description();
	}

	// Display the cart
	function add_cart_icon_to_menu( $items, $args ) {
		// Check if the current menu is the main menu
		if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
			// Get cart contents count
			$cart_count = WC()->cart->get_cart_contents_count();
			$cart_total = WC()->cart->get_cart_total();
		
			// Cart page URL
			$cart_page_url = wc_get_cart_url();
		
			// Cart icon markup with count
			$cart_icon_markup = '<li class="menu-item cart-icon">';
			$cart_icon_markup .= '<a href="' . esc_url( $cart_page_url ) . '" id="cart-icon">';
			$cart_icon_markup .= $this -> generate_svg_cart_icon();

			// If the cart has something, display the number of items and the total amount
			if ($cart_count > 0) {
				$cart_icon_markup .= '<span class="cart-count">' . $cart_count . '</span>';
				$cart_icon_markup .= '<span class="cart-amount">' . $cart_total . '</span>';
			}
			$cart_icon_markup .= '</a>';
			$cart_icon_markup .= '</li>';
		
			// Add cart icon to the menu
			$items .= $cart_icon_markup;
		}
	
		return $items;
	}


	function generate_svg_cart_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="shopping-cart-icon">
		<path d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"/>
		</svg>';

		return $svg;
	}
}