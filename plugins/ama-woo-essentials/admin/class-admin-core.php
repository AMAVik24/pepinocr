<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Everything that is focused on the admin side of the plugin.

 */

namespace AmaWooEssentials\Admin;

class Admin_Core {

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
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name .'-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name .'-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
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
		// Define days of the week for use in checkboxes
		$days_of_week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		$days_of_week_translated = array(__('Monday', 'ama-woo-essentials'), __('Tuesday', 'ama-woo-essentials'), __('Wednesday', 'ama-woo-essentials'), __('Thursday', 'ama-woo-essentials'), __('Friday', 'ama-woo-essentials'), __('Saturday', 'ama-woo-essentials'), __('Sunday', 'ama-woo-essentials'));
	
		// Handle form submission and update all settings
		if (isset($_POST['ama_woo_essentials_submit'])) {
			
			// Update schema markup options
			update_option( 'ama_woo_essentials_business_type', $_POST['ama_woo_essentials_business_type'] );
			update_option( 'ama_woo_essentials_business_country', sanitize_text_field( $_POST['ama_woo_essentials_business_country'] ) );
			update_option( 'ama_woo_essentials_business_region', sanitize_text_field( $_POST['ama_woo_essentials_business_region'] ) );
			update_option( 'ama_woo_essentials_business_postal_code', sanitize_text_field( $_POST['ama_woo_essentials_business_postal_code'] ) );
			update_option( 'ama_woo_essentials_business_physical_address', sanitize_text_field( $_POST['ama_woo_essentials_business_physical_address'] ) );
			update_option( 'ama_woo_essentials_business_geolocation', sanitize_text_field( $_POST['ama_woo_essentials_business_geolocation'] ) );
			update_option( 'ama_woo_essentials_business_phone_number', sanitize_text_field( $_POST['ama_woo_essentials_business_phone_number'] ) );
			update_option( 'ama_woo_essentials_business_opening_hour', $_POST['ama_woo_essentials_business_opening_hour'] );
			update_option( 'ama_woo_essentials_business_closing_hour', $_POST['ama_woo_essentials_business_closing_hour'] );
	
			// Create an array of selected open days from the checkboxes to store them as a single string in the option
			$new_selected_days = array();
			foreach ($days_of_week as $day) {
				// Check if the checkbox for this day is checked
				if (isset($_POST['ama_woo_essentials_business_open_day_' . $day])) {
					// Add the day to the selected_days array
					$new_selected_days[] = $_POST['ama_woo_essentials_business_open_day_' . $day];
				}
			}
			update_option( 'ama_woo_essentials_business_open_days', implode(', ', $new_selected_days) );
		}

		function check_for_dependencies() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			// Check if WooCommerce is active
			if ( ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				// WooCommerce is not active, display admin notice
				add_action( 'admin_notices', array( __CLASS__, 'dependencies_not_installed_notice' ) );
				// Deactivate the plugin
				/* deactivate_plugins( plugin_basename( dirname( dirname( __FILE__ ) ) . '/ama-woo-essentials.php' ) ); */
				return;
			}
		}
		
		function dependencies_not_installed_notice() {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'AMA - Woo Essentials requires WooCommerce to be installed and activated. Please install and activate WooCommerce to use this plugin.', 'ama-woo-essentials' ); ?></p>
			</div>
			<?php
		}
		
		?>
	
		<div class="wrap">
			<h2><?php _e( 'AMA Woo Essentials Settings', 'ama-woo-essentials' ); ?></h2>

				<!-- Schema Markup Section -->
				<h3><?php _e( 'Schema Markup Configuration', 'ama-woo-essentials' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Business Type', 'ama-woo-essentials' ); ?></th>
						<td>
							<?php
							$selected_type = esc_attr( get_option( 'ama_woo_essentials_business_type' ) );
							?>
							<input type="radio" id="ama_woo_essentials_business_type1" name="ama_woo_essentials_business_type" value="online" <?php checked( 'online', $selected_type ); ?>>
							<label for="ama_woo_essentials_business_type1"><?php _e( 'Online Store', 'ama-woo-essentials' ); ?></label><br>

							<input type="radio" id="ama_woo_essentials_business_type2" name="ama_woo_essentials_business_type" value="physical" <?php checked( 'physical', $selected_type ); ?>>
							<label for="ama_woo_essentials_business_type2"><?php _e( 'Physical Store', 'ama-woo-essentials' ); ?></label><br>

							<input type="radio" id="ama_woo_essentials_business_type3" name="ama_woo_essentials_business_type" value="online_and_physical" <?php checked( 'online_and_physical', $selected_type ); ?>>
							<label for="ama_woo_essentials_business_type3"><?php _e( 'Online and Physical Store', 'ama-woo-essentials' ); ?></label>
							<p class="description"><?php _e( 'Select the type of business that better describes yours.', 'ama-woo-essentials' ); ?></p>
						</td>
					</tr>
				</table>
				<div id="business_information">
					<table class="form-table">
						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_country"><?php _e( 'Business Country', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_woo_essentials_business_country" name="ama_woo_essentials_business_country" class="regular-text" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_country' ) ); ?>">
								<p class="description"><?php _e( 'Enter the country where your company is based', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_region"><?php _e( 'Business Region', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_woo_essentials_business_region" name="ama_woo_essentials_business_region" class="regular-text" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_region' ) ); ?>">
								<p class="description"><?php _e( 'Enter the region where your company is based (i.e. San José, Guadalajara, California, etc.)', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_postal_code"><?php _e( 'Business Postal Code', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="tel" id="ama_woo_essentials_business_postal_code" name="ama_woo_essentials_business_postal_code" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_postal_code' ) ); ?>">
								<p class="description"><?php _e( 'Set the business postal code', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_physical_address"><?php _e( 'Physical Address', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<textarea id="ama_woo_essentials_business_physical_address" name="ama_woo_essentials_business_physical_address" class="regular-text" rows="4" placeholder="<?php _e( 'San José, Curridabat, 300 meters from ...', 'ama-woo-essentials' ); ?>"><?php echo esc_textarea( get_option( 'ama_woo_essentials_business_physical_address' ) ); ?></textarea>
								<p class="description"><?php _e( 'Write the physical address of your business.', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_geolocation"><?php _e( 'Geolocation', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_woo_essentials_business_geolocation" name="ama_woo_essentials_business_geolocation" class="regular-text" placeholder="00.00000000000000, 00.00000000000000" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_geolocation' ) ); ?>">
								<p class="description"><?php _e( 'Type the latitude and longitude of your business location (as given by Google Maps).', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_phone_number"><?php _e( 'Business Phone Number', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="tel" id="ama_woo_essentials_business_phone_number" name="ama_woo_essentials_business_phone_number" pattern="[0-9]+" placeholder="88888888" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_phone_number' ) ); ?>">
								<p class="description"><?php _e( 'Set the business main phone number.', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_opening_hour"><?php _e( 'Opening Hour', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="time" id="ama_woo_essentials_business_opening_hour" name="ama_woo_essentials_business_opening_hour" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_opening_hour' ) ); ?>">
								<p class="description"><?php _e( 'Specify the opening hour of your business.', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_woo_essentials_business_closing_hour"><?php _e( 'Closing Hour', 'ama-woo-essentials' ); ?></label></th>
							<td>
								<input type="time" id="ama_woo_essentials_business_closing_hour" name="ama_woo_essentials_business_closing_hour" value="<?php echo esc_attr( get_option( 'ama_woo_essentials_business_closing_hour' ) ); ?>">
								<p class="description"><?php _e( 'Specify the closing hour of your business.', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<fieldset>
									<legend><?php _e( 'Open Days', 'ama-woo-essentials' ); ?></legend>
								</fieldset>
							</th>
							<td>
								<?php
								// Converts the string retrieved by the get_option function and converts it to an array.
								$past_selected_days = explode( ', ', get_option( 'ama_woo_essentials_business_open_days' ) );

								// Generates checkboxes for each day of the week, using the checked() function to mark days selected based on the stored options. A counter is used to keep track of what day is being worked, so it can call the translated weekday as the label.
								$i = 0;
								foreach ( $days_of_week as $day ) {
								?>
									<input type="checkbox" id="ama_woo_essentials_business_open_day_<?php echo $day; ?>" name="ama_woo_essentials_business_open_day_<?php echo $day; ?>" value="<?php echo $day; ?>" <?php checked( in_array( $day, $past_selected_days ) ); ?>>
									<label for="ama_woo_essentials_business_open_day_<?php echo $day; ?>"><?php echo $days_of_week_translated[$i] ?></label><br>
								<?php
								$i++;
								}
								?>
								<p class="description"><?php _e( 'Select the days when your business is open.', 'ama-woo-essentials' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<?php submit_button( __( 'Save Settings', 'ama-woo-essentials' ), 'primary', 'ama_woo_essentials_submit' ); ?>
			</form>
		</div>

	<?php
	}
}