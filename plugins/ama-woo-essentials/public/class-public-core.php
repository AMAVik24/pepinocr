<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */

namespace AmaWooEssentials\Public;

class Public_Core {

	private $default_settings;
	private $selected_settings;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->default_settings = $this->load_default_settings();
		$this->selected_settings = $this->load_selected_settings();
	}

	/**
	 * Load default settings from options.
	 */
	private function load_default_settings() {
		$settings = [
			'text_color',
			'hover_text_color',
			'enable_hover_background_color',
			'hover_background_color',
			'hover_text_decoration',
			'hover_opacity',
			'hover_text_decoration_style',
			'padding_top',
			'hover_border_style',
			'hover_border_color',
			'hover_border_width'
		];

		$defaults = [];
		foreach ( $settings as $setting ) {
			$defaults[ $setting ] = get_option( "ama_woo_essentials_default_$setting" );
		}

		return $defaults;
	}

	/**
	 * Load selected settings with defaults.
	 */
	private function load_selected_settings() {
		$settings = [];
		foreach ( $this->default_settings as $key => $default ) {
			$settings[ $key ] = $this->get_option_with_default( "ama_woo_essentials_selected_$key", $default );
		}

		return $settings;
	}

	/**
	 * Get option with default value.
	 */
	private function get_option_with_default( $option_name, $default_value ) {
		$value = get_option( $option_name, $default_value );
		return ( $value === '' ) ? $default_value : $value;
	}

	/**
	 * Register the styles for the public area.
	 */

	 public function enqueue_dinamic_styles() {

		// Dynamic styles based on option
		echo '  <style> .wc-block-mini-cart__button{
				color: ' . $this->selected_settings['text_color'] . '!important;
				background-color: transparent !important;
				padding-top: ' . $this->selected_settings['padding_top'] . 'px !important;
			}
		
			.wc-block-mini-cart__button:hover {
				color:' . $this->selected_settings['hover_text_color'] . '!important;
				background-color:' . ($this->selected_settings['enable_hover_background_color'] === '1' ? $this->selected_settings['hover_background_color'] : 'transparent') . '!important;
				text-decoration:' . $this->selected_settings['hover_text_decoration'] . '!important;
				opacity:' . $this->selected_settings['hover_opacity'] . '!important;
				text-decoration-style:' . $this->selected_settings['hover_text_decoration_style'] . '!important;
				border-style:' . $this->selected_settings['hover_border_style'] . '!important;
				border-color:' . $this->selected_settings['hover_border_color'] . '!important;
				border-width:' . $this->selected_settings['hover_border_width'] . 'px !important;
			} </style>';
	}	

	/**
	 * SEO RELATED FUNCTIONS
	 */

	// Generates a meta description to the viewed product, based on its short description.

	public function add_custom_meta_description() {

		if ( is_product() ) {
			
			// Get the post object for the product
			$product = wc_get_product( get_the_ID() );

			// Get the short description for the product
			$meta_description = $product->get_short_description();
		}
		// Check if a meta description exists
		if ( !empty( $meta_description ) ) {
			return '<meta name="description" content="' . esc_attr( $meta_description ) . '">';
		}
		return '';
	}

	// Outputs the meta description and JSON-LD schema to be used in the wp_head hook
	public function output_custom_meta_description() {
    	echo $this->add_custom_meta_description();
	}

	/**
	 * CART RELATED FUNCTIONS
	 */


	// Adds the mini cart block to the main menu
	function add_mini_cart_menu_item( $items, $args ) {
		if ( $args->theme_location === 'primary' ) {
			ob_start();
			?>
			<!-- wp:woocommerce/mini-cart /-->
			<?php
			$mini_cart_block = ob_get_clean();
			$items .= '<li class="menu-item mini-cart-block">' . do_blocks( $mini_cart_block ) . '</li>';
		}
		return $items;
	}
}