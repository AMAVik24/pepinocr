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
		// Enqueue your script, last parameter set to "true" so it loads the script in the footer, which is generally recommended for better performance.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, true );
	
		// Localize the script to provide the ajax_url variable to our JS file
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		//wp_enqueue_script( 'wc-cart-fragments' );
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

	// Display the cart icon
	function add_cart_icon_to_menu( $items, $args ) {
		// Check if the current menu is the main menu
		if ( isset( $args->theme_location ) && $args->theme_location === 'primary' && !is_cart()) {
			// Get cart contents count
			$cart_count = WC()->cart->get_cart_contents_count();
			$cart_total = WC()->cart->get_cart_total();
		
			// Cart icon markup with count
			$cart_icon_markup = '<li class="menu-item cart-icon">';
			$cart_icon_markup .= '<a href="#" id="cart-icon">';
			$cart_icon_markup .= $this->generate_svg_cart_icon();

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

	// The SVG code for the cart icon
	function generate_svg_cart_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="shopping-cart-icon">
		<path d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"/>
		</svg>';

		return $svg;
	}

	// Function to update cart count
	public function update_cart_count_callback() {
		// Check for a valid request
		if ( !isset($_POST['action']) || $_POST['action'] !== 'update_cart_count' ) {
			wp_send_json_error('Invalid request');
		}
	
		// Perform the necessary actions to update the cart count
		$cart_count = WC() -> cart -> get_cart_contents_count();
		$cart_total = WC() -> cart -> get_cart_total();
	
		// Return an array with the cart count as the response
		wp_send_json_success(array('cart_count' => $cart_count, 'cart_total' => $cart_total));
	}


	public function add_side_panel_to_footer() {
        // Include the side panel HTML
        include plugin_dir_path(__FILE__) . 'partials/side-panel-cart.php';
    }


	// Function to get the cart contents to show in the side panel cart
	public function get_cart_contents_callback() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WC_Cart' ) ) {
            wp_send_json_error('WooCommerce is not active.');
            return;
        }

        $cart_items = array();

        // Loop through the cart items and get their data
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $cart_items[] = array(
                'name' => $product->get_name(),
                'quantity' => $cart_item['quantity'],
                'price' => wc_price( $product->get_price() )
            );
        }

        // Send the cart items as the response
        wp_send_json_success( array( 'cart_items' => $cart_items ) );
    }


	function add_mini_cart_menu_item( $items, $args ) {
		// Change 'primary' to the location ID of your menu
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