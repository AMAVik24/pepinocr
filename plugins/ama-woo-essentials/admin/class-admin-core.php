<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Everything that is focused on the admin side of the plugin.

 */

namespace AmaWooEssentials\Admin;

class Admin_Core {

	private $plugin_name;
	private $version;
	private $default_settings;
	private $selected_settings;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->default_settings = $this->load_default_settings();
		$this->selected_settings = $this->load_selected_settings();
	}

	/**
	 * Load default settings from options.
	 */
	private function load_default_settings() {
		$settings = [
			'enable_mini_cart_on_menu',
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
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script($this->plugin_name . '-admin', 'miniCartOptions', array(
			'default_settings' => $this->default_settings,
			'selected_settings' => $this->selected_settings,
		));
	}
	
	/**
	 * Add an admin menu for settings.
	 */
	public function add_admin_menu() {
		add_menu_page(
			'AMA Woo Essentials',
			'AMA Woo Essentials',
			'manage_options',
			'ama-woo-essentials-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Callback function to display settings page.
	 */
	public function settings_page() {

		?>
		<div class="wrap">
			<h1><?php _e('Mini Cart Settings', 'ama-woo-essentials')?></h1>

			<h2><?php _e('Preview', 'ama-woo-essentials')?></h2>
			<div class="wc-block-mini-cart">
				<div class="menu-item mini-cart-block">
					<div data-block-name="woocommerce/mini-cart" class="wc-block-mini-cart wp-block-woocommerce-mini-cart">
						<button id="mini-cart-preview" class="wc-block-mini-cart__button" aria-label="1 item in cart, total price of ₡10000" style="border: none;">
							<span class="wc-block-mini-cart__amount">₡10000</span>
							<span class="wc-block-mini-cart__quantity-badge">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" width="20" height="20" class="wc-block-mini-cart__icon" aria-hidden="true" focusable="false">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M7.84614 18.2769C7.89712 18.2769 7.93845 18.2356 7.93845 18.1846C7.93845 18.1336 7.89712 18.0923 7.84614 18.0923C7.79516 18.0923 7.75384 18.1336 7.75384 18.1846C7.75384 18.2356 7.79516 18.2769 7.84614 18.2769ZM6.03076 18.1846C6.03076 17.182 6.84353 16.3692 7.84614 16.3692C8.84875 16.3692 9.66152 17.182 9.66152 18.1846C9.66152 19.1872 8.84875 20 7.84614 20C6.84353 20 6.03076 19.1872 6.03076 18.1846Z" fill="currentColor"></path>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17.3231 18.2769C17.3741 18.2769 17.4154 18.2356 17.4154 18.1846C17.4154 18.1336 17.3741 18.0923 17.3231 18.0923C17.2721 18.0923 17.2308 18.1336 17.2308 18.1846C17.2308 18.2356 17.2721 18.2769 17.3231 18.2769ZM15.5077 18.1846C15.5077 17.182 16.3205 16.3692 17.3231 16.3692C18.3257 16.3692 19.1385 17.182 19.1385 18.1846C19.1385 19.1872 18.3257 20 17.3231 20C16.3205 20 15.5077 19.1872 15.5077 18.1846Z" fill="currentColor"></path>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M20.0631 9.53835L19.4662 12.6685L19.4648 12.6757L19.4648 12.6757C19.3424 13.2919 19.0072 13.8454 18.5178 14.2394C18.031 14.6312 17.4226 14.8404 16.798 14.8308H8.44017C7.81556 14.8404 7.20714 14.6312 6.72038 14.2394C6.2312 13.8456 5.89605 13.2924 5.77352 12.6765L5.77335 12.6757L4.33477 5.48814C4.3286 5.46282 4.32345 5.43711 4.31934 5.41104L3.61815 1.90768H0.953842C0.42705 1.90768 0 1.48063 0 0.953842C0 0.42705 0.42705 0 0.953842 0H4.4C5.01794 0 5.54321 0.421984 5.68772 1.0263L5.68831 1.02889L6.3895 4.53225H18.0122C19.1316 4.53225 20.0001 5.40275 20.0001 6.52216C20.0001 7.27429 19.6302 7.97089 19.0631 8.42015L20.0631 9.53835ZM17.8628 10.1557L18.7754 7.76818L16.798 7.76817H7.67778L8.66871 12.4038C8.6969 12.5422 8.76943 12.6683 8.87506 12.7623C8.98123 12.8565 9.11459 12.9135 9.25816 12.9219H16.798C16.9416 12.9135 17.0749 12.8565 17.1809 12.7623C17.2865 12.6683 17.3591 12.5422 17.3872 12.4038L17.8628 10.1557Z" fill="currentColor"></path>
								</svg>
							</span>
						</button>
					</div>
				</div>
			</div>

			<h2><?php _e('Settings', 'ama-woo-essentials')?></h2>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'ama-woo-essentials-settings-group' );
				do_settings_sections( 'ama-woo-essentials-settings-group' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="enable_mini_cart_on_menu"><?php _e('Enable the Mini Cart on the Main Menu', 'ama-woo-essentials'); ?></label></th>
						<td>
							<input type="checkbox" id="enable_mini_cart_on_menu" name="ama_woo_essentials_selected_enable_mini_cart_on_menu" value="1" <?php checked(get_option('ama_woo_essentials_selected_enable_mini_cart_on_menu', '0'), '1'); ?> />
							<p class="description"><?php _e('Check to enable the Mini Cart on the Main Menu.', 'ama-woo-essentials'); ?></p>
						</td>
					</tr>
				</table>
				<div id="mini_cart_container">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="text_color"><?php _e('Main Color', 'ama-woo-essentials'); ?></label></th>
							<td>
								<input type="color" id="text_color" name="ama_woo_essentials_selected_text_color" value="<?php echo $this->selected_settings['text_color']; ?>" />
								<p class="description"><?php _e('Select the main color of the cart.', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="hover_text_color"><?php _e('Hover Color', 'ama-woo-essentials'); ?></label></th>
							<td>
								<input type="color" id="hover_text_color" name="ama_woo_essentials_selected_hover_text_color" value="<?php echo $this->selected_settings['hover_text_color']; ?>" />
								<p class="description"><?php _e('Select the hover color.', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="enable_hover_background_color"><?php _e('Enable Hover Background Color', 'ama-woo-essentials'); ?></label></th>
							<td>
								<input type="checkbox" id="enable_hover_background_color" name="ama_woo_essentials_selected_enable_hover_background_color" value="1" <?php checked(get_option('ama_woo_essentials_selected_enable_hover_background_color', '0'), '1'); ?> />
								<p class="description"><?php _e('Check to enable hover background color.', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
					</table>
					<div id="hover_background_color_container">
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="hover_background_color"><?php _e('Hover Background Color', 'ama-woo-essentials'); ?></label></th>
								<td>
									<input type="color" id="hover_background_color" name="ama_woo_essentials_selected_hover_background_color" value="<?php echo $this->selected_settings['hover_background_color']; ?>" />
									<p class="description"><?php _e('Select the hover background color.', 'ama-woo-essentials'); ?></p>
								</td>
							</tr>
						</table>
					</div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="hover_text_decoration"><?php _e('Hover Decoration', 'ama-woo-essentials'); ?></label></th>
							<td>
								<select id="hover_text_decoration" name="ama_woo_essentials_selected_hover_text_decoration">
									<option value="none" <?php selected($this->selected_settings['hover_text_decoration'], 'none'); ?>><?php _e('None', 'ama-woo-essentials'); ?></option>
									<option value="underline" <?php selected($this->selected_settings['hover_text_decoration'], 'underline'); ?>><?php _e('Underline', 'ama-woo-essentials'); ?></option>
									<option value="overline" <?php selected($this->selected_settings['hover_text_decoration'], 'overline'); ?>><?php _e('Overline', 'ama-woo-essentials'); ?></option>
									<option value="line-through" <?php selected($this->selected_settings['hover_text_decoration'], 'line-through'); ?>><?php _e('Line Through', 'ama-woo-essentials'); ?></option>
									<option value="inherit" <?php selected($this->selected_settings['hover_text_decoration'], 'inherit'); ?>><?php _e('Inherit', 'ama-woo-essentials'); ?></option>
								</select>
								<p class="description"><?php _e('Select the hover decoration style.', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
					</table>
					<div id="hover_text_decoration_style_container">
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="hover_text_decoration_style"><?php _e('Hover Decoration Style', 'ama-woo-essentials'); ?></label></th>
								<td>
									<select id="hover_text_decoration_style" name="ama_woo_essentials_selected_hover_text_decoration_style">
										<option value="solid" <?php selected($this->selected_settings['hover_text_decoration_style'], 'solid'); ?>><?php _e('Solid', 'ama-woo-essentials'); ?></option>
										<option value="double" <?php selected($this->selected_settings['hover_text_decoration_style'], 'double'); ?>><?php _e('Double', 'ama-woo-essentials'); ?></option>
										<option value="dotted" <?php selected($this->selected_settings['hover_text_decoration_style'], 'dotted'); ?>><?php _e('Dotted', 'ama-woo-essentials'); ?></option>
										<option value="dashed" <?php selected($this->selected_settings['hover_text_decoration_style'], 'dashed'); ?>><?php _e('Dashed', 'ama-woo-essentials'); ?></option>
										<option value="wavy" <?php selected($this->selected_settings['hover_text_decoration_style'], 'wavy'); ?>><?php _e('Wavy', 'ama-woo-essentials'); ?></option>
									</select>
									<p class="description"><?php _e('Select the hover decoration style.', 'ama-woo-essentials'); ?></p>
								</td>
							</tr>
						</table>
					</div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="hover_opacity"><?php _e('Hover Opacity', 'ama-woo-essentials'); ?></label></th>
							<td>
								<input type="number" id="hover_opacity" name="ama_woo_essentials_selected_hover_opacity" value="<?php echo $this->selected_settings['hover_opacity']; ?>" step="0.1" min="0" max="1" />
								<p class="description"><?php _e('Set the hover opacity (0 to 1).', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="padding_top"><?php _e('Padding Top', 'ama-woo-essentials'); ?></label></th>
							<td>
								<input type="number" id="padding_top" name="ama_woo_essentials_selected_padding_top" value="<?php echo $this->selected_settings['padding_top']; ?>" step="1" min="0" max="100" />
								<p class="description"><?php _e('Set the top padding value to adjust the height of the cart (0 to 100).', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="hover_border_style"><?php _e('Hover Border Style', 'ama-woo-essentials'); ?></label></th>
							<td>
								<select id="hover_border_style" name="ama_woo_essentials_selected_hover_border_style">
									<option value="none" <?php selected($this->selected_settings['hover_border_style'], 'none'); ?>><?php _e('None', 'ama-woo-essentials'); ?></option>
									<option value="solid" <?php selected($this->selected_settings['hover_border_style'], 'solid'); ?>><?php _e('Solid', 'ama-woo-essentials'); ?></option>
									<option value="dashed" <?php selected($this->selected_settings['hover_border_style'], 'dashed'); ?>><?php _e('Dashed', 'ama-woo-essentials'); ?></option>
									<option value="dotted" <?php selected($this->selected_settings['hover_border_style'], 'dotted'); ?>><?php _e('Dotted', 'ama-woo-essentials'); ?></option>
								</select>
								<p class="description"><?php _e('Select the hover border style.', 'ama-woo-essentials'); ?></p>
							</td>
						</tr>
					</table>
					<div id="hover_border_container">
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="hover_border_color"><?php _e('Hover Border Color', 'ama-woo-essentials'); ?></label></th>
								<td>
									<input type="color" id="hover_border_color" name="ama_woo_essentials_selected_hover_border_color" value="<?php echo $this->selected_settings['hover_border_color']; ?>" />
									<p class="description"><?php _e('Select the hover border color.', 'ama-woo-essentials'); ?></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="hover_border_width"><?php _e('Hover Border Width', 'ama-woo-essentials'); ?></label></th>
								<td>
									<input type="number" id="hover_border_width" name="ama_woo_essentials_selected_hover_border_width" value="<?php echo $this->selected_settings['hover_border_width']; ?>" step="1" min="0" max="10" />
									<p class="description"><?php _e('Set the hover border width (0 to 10).', 'ama-woo-essentials'); ?></p>
								</td>
							</tr>
						</table>
					</div>
				</div>
			<?php submit_button(); ?>
			</form>

        <button id="restore_defaults" class="button">Restore Defaults</button>
    	</div>
    <?php
	}

	public function register_settings() {
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_enable_mini_cart_on_menu');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_text_color');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_text_color');
		register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_enable_hover_background_color');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_background_color');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_text_decoration');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_opacity');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_text_decoration_style');
        register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_padding_top');
		register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_border_style');
		register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_border_color');
		register_setting('ama-woo-essentials-settings-group', 'ama_woo_essentials_selected_hover_border_width');
    }
}