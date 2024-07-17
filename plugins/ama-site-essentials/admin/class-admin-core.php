<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Everything that is focused on the admin side of the plugin.

 */

namespace AmaSiteEssentials\Admin;

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
			'AMA Site Essentials',
			'AMA Site Essentials',
			'manage_options',
			'ama-site-essentials-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Callback function to display settings page.
	 */
	public function settings_page() {
		// Define days of the week for use in checkboxes
		$days_of_week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		$days_of_week_translated = array(__('Monday', 'ama-site-essentials'), __('Tuesday', 'ama-site-essentials'), __('Wednesday', 'ama-site-essentials'), __('Thursday', 'ama-site-essentials'), __('Friday', 'ama-site-essentials'), __('Saturday', 'ama-site-essentials'), __('Sunday', 'ama-site-essentials'));
	
		// Handle form submission and update all settings
		if (isset($_POST['ama_site_essentials_submit'])) {
			// Update custom home meta description
			update_option( 'ama_site_essentials_custom_home_meta_description', sanitize_text_field( $_POST['ama_site_essentials_custom_home_meta_description'] ) );
	
			// Update schema markup options
			update_option( 'ama_site_essentials_business_type', $_POST['ama_site_essentials_business_type'] );
			update_option( 'ama_site_essentials_business_country', sanitize_text_field( $_POST['ama_site_essentials_business_country'] ) );
			update_option( 'ama_site_essentials_business_region', sanitize_text_field( $_POST['ama_site_essentials_business_region'] ) );
			update_option( 'ama_site_essentials_business_postal_code', sanitize_text_field( $_POST['ama_site_essentials_business_postal_code'] ) );
			update_option( 'ama_site_essentials_business_physical_address', sanitize_text_field( $_POST['ama_site_essentials_business_physical_address'] ) );
			update_option( 'ama_site_essentials_business_geolocation', sanitize_text_field( $_POST['ama_site_essentials_business_geolocation'] ) );
			update_option( 'ama_site_essentials_business_phone_number', sanitize_text_field( $_POST['ama_site_essentials_business_phone_number'] ) );
			update_option( 'ama_site_essentials_business_opening_hour', $_POST['ama_site_essentials_business_opening_hour'] );
			update_option( 'ama_site_essentials_business_closing_hour', $_POST['ama_site_essentials_business_closing_hour'] );
	
			// Create an array of selected open days from the checkboxes to store them as a single string in the option
			$new_selected_days = array();
			foreach ($days_of_week as $day) {
				// Check if the checkbox for this day is checked
				if (isset($_POST['ama_site_essentials_business_open_day_' . $day])) {
					// Add the day to the selected_days array
					$new_selected_days[] = $_POST['ama_site_essentials_business_open_day_' . $day];
				}
			}
			update_option( 'ama_site_essentials_business_open_days', implode(', ', $new_selected_days) );
	
			// Update child theme options
			update_option( 'ama_site_essentials_parent_theme_handle', sanitize_text_field( $_POST['ama_site_essentials_parent_theme_handle'] ) );
			update_option( 'ama_site_essentials_parent_theme_loading_method', sanitize_text_field( $_POST['ama_site_essentials_parent_theme_loading_method'] ) );
	
			// Update email options
			update_option( 'ama_site_essentials_smtp_username', sanitize_text_field( $_POST['ama_site_essentials_smtp_username'] ) );
			update_option( 'ama_site_essentials_smtp_password', $this->my_encrypt( $_POST['ama_site_essentials_smtp_password'] ) ); // Encrypt password for security
			update_option( 'ama_site_essentials_smtp_sender', sanitize_text_field( $_POST['ama_site_essentials_smtp_sender'] ) );
			update_option( 'ama_site_essentials_smtp_name', sanitize_text_field( $_POST['ama_site_essentials_smtp_name'] ) );
			update_option( 'ama_site_essentials_smtp_server', sanitize_text_field( $_POST['ama_site_essentials_smtp_server'] ) );
			update_option( 'ama_site_essentials_smtp_port', sanitize_text_field( $_POST['ama_site_essentials_smtp_port'] ) );
			update_option( 'ama_site_essentials_smtp_secure', sanitize_text_field( $_POST['ama_site_essentials_smtp_secure'] ) );
	
			// Update Google Tag Manager options
			update_option( 'ama_site_essentials_gtm_header_tag', sanitize_text_field( $_POST['ama_site_essentials_gtm_header_tag'] ) );
			update_option( 'ama_site_essentials_gtm_body_tag', sanitize_text_field( $_POST['ama_site_essentials_gtm_body_tag'] ) );
		}
		?>
	
		<div class="wrap">
			<h2><?php _e( 'AMA Site Essentials Settings', 'ama-site-essentials' ); ?></h2>

			<!-- Custom Home Meta Description Section -->
			<h3><?php _e( 'Custom Home Meta Description', 'ama-site-essentials' ); ?></h3>
			<form method="post" action="">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ama_site_essentials_custom_home_meta_description"><?php _e( 'Custom Home Meta Description', 'ama-site-essentials' ); ?></label></th>
						<td>
							<textarea id="ama_site_essentials_custom_home_meta_description" name="ama_site_essentials_custom_home_meta_description" class="regular-text" rows="4" maxlength="160" placeholder="<?php _e( 'This is the website of XXXXXX, a small business focused on XXXXX.', 'ama-site-essentials' ); ?>"><?php echo esc_attr( get_option( 'ama_site_essentials_custom_home_meta_description' ) ); ?></textarea>
							<p class="description"><?php _e( 'Write a short, 160 character summary of your website/business.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
				</table>

				<!-- Schema Markup Section -->
				<h3><?php _e( 'Schema Markup Configuration', 'ama-site-essentials' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Business Type', 'ama-site-essentials' ); ?></th>
						<td>
							<?php
							$selected_type = esc_attr( get_option( 'ama_site_essentials_business_type' ) );
							?>
							<input type="radio" id="ama_site_essentials_business_type1" name="ama_site_essentials_business_type" value="online" <?php checked( 'online', $selected_type ); ?>>
							<label for="ama_site_essentials_business_type1"><?php _e( 'Online Store', 'ama-site-essentials' ); ?></label><br>

							<input type="radio" id="ama_site_essentials_business_type2" name="ama_site_essentials_business_type" value="physical" <?php checked( 'physical', $selected_type ); ?>>
							<label for="ama_site_essentials_business_type2"><?php _e( 'Physical Store', 'ama-site-essentials' ); ?></label><br>

							<input type="radio" id="ama_site_essentials_business_type3" name="ama_site_essentials_business_type" value="online_and_physical" <?php checked( 'online_and_physical', $selected_type ); ?>>
							<label for="ama_site_essentials_business_type3"><?php _e( 'Online and Physical Store', 'ama-site-essentials' ); ?></label>
							<p class="description"><?php _e( 'Select the type of business that better describes yours.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
				</table>
				<div id="business_information">
					<table class="form-table">
						<tr>
							<th scope="row"><label for="ama_site_essentials_business_country"><?php _e( 'Business Country', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_site_essentials_business_country" name="ama_site_essentials_business_country" class="regular-text" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_country' ) ); ?>">
								<p class="description"><?php _e( 'Enter the country where your company is based', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_region"><?php _e( 'Business Region', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_site_essentials_business_region" name="ama_site_essentials_business_region" class="regular-text" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_region' ) ); ?>">
								<p class="description"><?php _e( 'Enter the region where your company is based (i.e. San José, Guadalajara, California, etc.)', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_postal_code"><?php _e( 'Business Postal Code', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="tel" id="ama_site_essentials_business_postal_code" name="ama_site_essentials_business_postal_code" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_postal_code' ) ); ?>">
								<p class="description"><?php _e( 'Set the business postal code', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_physical_address"><?php _e( 'Physical Address', 'ama-site-essentials' ); ?></label></th>
							<td>
								<textarea id="ama_site_essentials_business_physical_address" name="ama_site_essentials_business_physical_address" class="regular-text" rows="4" placeholder="<?php _e( 'San José, Curridabat, 300 meters from ...', 'ama-site-essentials' ); ?>"><?php echo esc_textarea( get_option( 'ama_site_essentials_business_physical_address' ) ); ?></textarea>
								<p class="description"><?php _e( 'Write the physical address of your business.', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_geolocation"><?php _e( 'Geolocation', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="text" id="ama_site_essentials_business_geolocation" name="ama_site_essentials_business_geolocation" class="regular-text" placeholder="00.00000000000000, 00.00000000000000" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_geolocation' ) ); ?>">
								<p class="description"><?php _e( 'Type the latitude and longitude of your business location (as given by Google Maps).', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_phone_number"><?php _e( 'Business Phone Number', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="tel" id="ama_site_essentials_business_phone_number" name="ama_site_essentials_business_phone_number" pattern="[0-9]+" placeholder="88888888" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_phone_number' ) ); ?>">
								<p class="description"><?php _e( 'Set the business main phone number.', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_opening_hour"><?php _e( 'Opening Hour', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="time" id="ama_site_essentials_business_opening_hour" name="ama_site_essentials_business_opening_hour" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_opening_hour' ) ); ?>">
								<p class="description"><?php _e( 'Specify the opening hour of your business.', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="ama_site_essentials_business_closing_hour"><?php _e( 'Closing Hour', 'ama-site-essentials' ); ?></label></th>
							<td>
								<input type="time" id="ama_site_essentials_business_closing_hour" name="ama_site_essentials_business_closing_hour" value="<?php echo esc_attr( get_option( 'ama_site_essentials_business_closing_hour' ) ); ?>">
								<p class="description"><?php _e( 'Specify the closing hour of your business.', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<fieldset>
									<legend><?php _e( 'Open Days', 'ama-site-essentials' ); ?></legend>
								</fieldset>
							</th>
							<td>
								<?php
								// Converts the string retrieved by the get_option function and converts it to an array.
								$past_selected_days = explode( ', ', get_option( 'ama_site_essentials_business_open_days' ) );

								// Generates checkboxes for each day of the week, using the checked() function to mark days selected based on the stored options. A counter is used to keep track of what day is being worked, so it can call the translated weekday as the label.
								$i = 0;
								foreach ( $days_of_week as $day ) {
								?>
									<input type="checkbox" id="ama_site_essentials_business_open_day_<?php echo $day; ?>" name="ama_site_essentials_business_open_day_<?php echo $day; ?>" value="<?php echo $day; ?>" <?php checked( in_array( $day, $past_selected_days ) ); ?>>
									<label for="ama_site_essentials_business_open_day_<?php echo $day; ?>"><?php echo $days_of_week_translated[$i] ?></label><br>
								<?php
								$i++;
								}
								?>
								<p class="description"><?php _e( 'Select the days when your business is open.', 'ama-site-essentials' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<!-- Child Theme Section -->
				<h3><?php _e( 'Child Theme Configuration', 'ama-site-essentials' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ama_site_essentials_parent_theme_handle"><?php _e( 'Parent Theme Stylesheet Handle', 'ama-site-essentials' ); ?></label></th>
						<td>
							<input type="text" id="ama_site_essentials_parent_theme_handle" name="ama_site_essentials_parent_theme_handle" class="regular-text" value="<?php echo esc_attr( get_option( 'ama_site_essentials_parent_theme_handle' ) ); ?>">
							<p class="description"><?php _e( 'Enter the parent\'s theme stylesheet handle. It\'s the first parameter of the wp_enqueue_style function in the functions.php file of the parent theme. If you can\'t find that function, you can leave this field empty.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ama_site_essentials_parent_theme_loading_method"><?php _e( 'Parent Theme Loading Method', 'ama-site-essentials' ); ?></label></th>
						<td>
							<?php
							$selected_method = esc_attr( get_option( 'ama_site_essentials_parent_theme_loading_method' ) );
							?>
							<input type="radio" id="method1" name="ama_site_essentials_parent_theme_loading_method" value="get_template" <?php checked( 'get_template', $selected_method ); ?>>
							<label for="method1">'get_template'</label><br>

							<input type="radio" id="method2" name="ama_site_essentials_parent_theme_loading_method" value="get_stylesheet" <?php checked( 'get_stylesheet', $selected_method ); ?>>
							<label for="method2">'get_stylesheet'</label><br>

							<input type="radio" id="method3" name="ama_site_essentials_parent_theme_loading_method" value="both" <?php checked( 'both', $selected_method ); ?>>
							<label for="method3"><?php _e( 'The parent theme loads both stylesheets', 'ama-site-essentials' ); ?></label>

							<input type="radio" id="method4" name="ama_site_essentials_parent_theme_loading_method" value="none" <?php checked( 'none', $selected_method ); ?>>
							<label for="method4"><?php _e( 'The parent theme doesn\'t load any stylesheets', 'ama-site-essentials' ); ?></label>

							<p class="description"><?php _e( 'Select the stylesheet loading method used by the parent theme in its functions.php file.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
				</table>
			

				<!-- Email Section -->
				<h3><?php _e( 'Email Settings', 'ama-site-essentials' ); ?></h3>
				<table class="form-table">
					<?php 
					echo $this->generate_smtp_setting_row( "smtp_username", __( 'SMTP Username', 'ama-site-essentials' ), __( 'Enter the username of your SMTP server.', 'ama-site-essentials' ) );
					
					// Different field for the password. 
					// Defines the placeholder of the field based on whether the option has been set or not.
					$password_placeholder = ( get_option( 'ama_site_essentials_smtp_password' ) === false ) ? "" : __( 'Hidden for security', 'ama-site-essentials' );
					?>
					<tr>
						<th scope="row"><label for="ama_site_essentials_smtp_password"><?php _e( 'SMTP Password', 'ama-site-essentials' ); ?></label></th>
						<td>
							<input type="password" id="ama_site_essentials_smtp_password" name="ama_site_essentials_smtp_password" class="regular-text" placeholder="<?php $password_placeholder ?>">
							<p class="description"><?php _e( 'Enter the password of your SMTP server.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
					<?php

					echo $this->generate_smtp_setting_row( "smtp_sender", __( 'SMTP Sender', 'ama-site-essentials' ), __( 'Enter the email address from where the emails should be sent.', 'ama-site-essentials' ) );

					echo $this->generate_smtp_setting_row( "smtp_name", __( 'SMTP Name', 'ama-site-essentials' ), __( 'Enter the name that people will see from these emails.', 'ama-site-essentials' ) );

					echo $this->generate_smtp_setting_row( "smtp_server", __( 'SMTP Server', 'ama-site-essentials' ), __( 'Enter the address (hostname) of your SMTP server.', 'ama-site-essentials' ) );

					echo $this->generate_smtp_setting_row( "smtp_port", __( 'SMTP Port', 'ama-site-essentials' ), __( 'Enter the server port number.', 'ama-site-essentials' ) );

					echo $this->generate_smtp_setting_row( "smtp_secure", __( 'SMTP Encryption', 'ama-site-essentials' ), __( 'Enter the encryption protocol (tls is recommended).', 'ama-site-essentials' ) );
					?>
				</table>

				<!-- Google Tag Manager Section -->
				<h3><?php _e( 'Google Tag Manager', 'ama-site-essentials' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ama_site_essentials_gtm_header_tag"><?php _e( 'Google Tag Manager: Header', 'ama-site-essentials' ); ?></label></th>
						<td>
							<textarea id="ama_site_essentials_gtm_header_tag" name="ama_site_essentials_gtm_header_tag" class="regular-text" rows="7" placeholder="<!-- Google Tag Manager -->\n<script>XXX</script>\n<!-- End Google Tag Manager -->"><?php echo esc_attr( get_option( 'ama_site_essentials_gtm_header_tag' ) ); ?></textarea>
							<p class="description"><?php _e( 'Enter the GTM code for the header.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ama_site_essentials_gtm_body_tag"><?php _e( 'Google Tag Manager: Body', 'ama-site-essentials' ); ?></label></th>
						<td>
							<textarea id="ama_site_essentials_gtm_body_tag" name="ama_site_essentials_gtm_body_tag" class="regular-text" rows="7" placeholder="<!-- Google Tag Manager (noscript) -->\n<noscript>XXX</noscript>\n<!-- End Google Tag Manager (noscript) -->"><?php echo esc_attr( get_option( 'ama_site_essentials_gtm_body_tag' ) ); ?></textarea>
							<p class="description"><?php _e( 'Enter the GTM code for the body.', 'ama-site-essentials' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Save Settings', 'ama-site-essentials' ), 'primary', 'ama_site_essentials_submit' ); ?>
			</form>
		</div>
	<?php
	}

	// Generate a table row for a setting field, to be used in the SMTP settings table.
	public function generate_smtp_setting_row($field_name_no_prefix, $setting_title, $description) {

	// Adds the plugin prefix to the field name, which will be used for the "for, "id", and "name parameters.
	$field_name_no_prefix = "ama_site_essentials_" . $field_name_no_prefix;
	// Starts the buffer to store all the following HTML
	ob_start(); ?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr($field_name_no_prefix); ?>"><?php echo esc_html($setting_title); ?></label></th>
		<td>
			<input type="text" id="<?php echo esc_attr($field_name_no_prefix); ?>" name="<?php echo esc_attr($field_name_no_prefix); ?>" class="regular-text" value="<?php echo esc_attr(get_option($field_name_no_prefix)); ?>">
			<p class="description"><?php echo esc_html($description); ?></p>
		</td>
	</tr>
	<?php
	// Returns the buffer contents and cleans the buffer.
	return ob_get_clean();
	}
	
	// Add a custom meta description field to the post edit area
	public function add_custom_meta_description_field() {

		$screens = array( 'post', 'page' );

		// Loop through each screen type and add the meta box
		foreach ( $screens as $screen ) {
			add_meta_box(
				'ama_site_essentials_meta_description',
				__( 'AMA Site Essentials - Meta Description', 'ama-site-essentials' ),
				array( $this, 'render_custom_meta_description_field' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Render the custom meta description field
	public function render_custom_meta_description_field($post) {
		// Get the saved meta description if it exists
		$meta_description = get_post_meta( $post->ID, '_ama_site_essentials_meta_description', true );

		// Output the field HTML
		?>
		<label for="ama_site_essentials_meta_description"><?php _e( 'Meta Description:', 'ama-site-essentials' ); ?></label> 
		<textarea id="ama_site_essentials_meta_description" name="ama_site_essentials_meta_description" rows="3" maxlength="160" placeholder="<?php esc_attr_e( 'Write a short, 160 character summary of your post/page.', 'ama-site-essentials' ); ?>"><?php echo esc_textarea( $meta_description ); ?></textarea>
		<?php
	}

	// Save the custom meta description field data
	public function save_custom_meta_description_field($post_id) {

		// Exits the function if it's just autouploading or if the user doesn't have authorization.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!current_user_can('edit_post', $post_id)) return;

		// Save the meta description field value
		if (isset($_POST['ama_site_essentials_meta_description'])) {
			$meta_description = sanitize_text_field($_POST['ama_site_essentials_meta_description']);
			update_post_meta($post_id, '_ama_site_essentials_meta_description', $meta_description);
		}
	}

	/**
	* EMAIL RELATED FUNCTIONS
	*/

	// Adds the SMTP authentication details when sending emails
	public function my_phpmailer_smtp( $phpmailer ) {
		$phpmailer->isSMTP();     
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = get_option('ama_site_essentials_smtp_username'); 
		$phpmailer->Password = $this -> my_decrypt(get_option('ama_site_essentials_smtp_password')); 
		$phpmailer->From = get_option('ama_site_essentials_smtp_sender'); 
		$phpmailer->FromName = get_option('ama_site_essentials_smtp_name'); 
		$phpmailer->Host = get_option('ama_site_essentials_smtp_server');  
		$phpmailer->Port = get_option('ama_site_essentials_smtp_port'); 
		$phpmailer->SMTPSecure = get_option('ama_site_essentials_smtp_secure'); 
	}


	// Copies the sender email as BCC to see the sent emails. It checks if the 'headers' element of the $args array is an array itself. If it's an array, it appends a BCC header to the array. The 'headers' array can have multiple headers, and this code just adds another one. If it's not an array (which typically means there was no existing header), it treats the 'headers' element as a string and appends the BCC header.

	public function add_address_to_bcc($args) {
		$bcc_email = sanitize_email(get_option('ama_site_essentials_smtp_sender'));
		if (is_array($args['headers'])) {
			$args['headers'][] = 'Bcc: '.$bcc_email ;
		}
		else {
			$args['headers'] .= 'Bcc: '.$bcc_email."\r\n";
		}
		return $args;
	}

	/**
	 * SECURITY RELATED FUNCTIONS
	 */

	// Encryption function
	public function my_encrypt($data) {
		$cipher = 'AES-256-CBC'; // You can choose a different encryption method if needed
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
		$encrypted = openssl_encrypt($data, $cipher, '3Sct2cJJS5yOrfQpgwqNYm/De+zwIW0ciTfb+8dVfXALvdsm/vZaEZAU1uhuIQ5x', 0, $iv);
		return base64_encode($iv . $encrypted);
	}
	
	// Decryption function
	public function my_decrypt($data) {
		$cipher = 'AES-256-CBC'; // Make sure it matches the encryption method used
		$data = base64_decode($data);
		$iv_size = openssl_cipher_iv_length($cipher);
		$iv = substr($data, 0, $iv_size);
		$data = substr($data, $iv_size);
		return openssl_decrypt($data, $cipher, '3Sct2cJJS5yOrfQpgwqNYm/De+zwIW0ciTfb+8dVfXALvdsm/vZaEZAU1uhuIQ5x', 0, $iv);
	}

	// Checks how many login attempts have been made. If 3 or more attempts were made, it returns a WP_Error indicating that the user has reached the authentication limit.

	public function check_attempted_login( $user ) {
		if ( get_transient( 'ama_site_essentials_attempted_login' ) ) {
			$datas = get_transient( 'ama_site_essentials_attempted_login' );
			
			// Check the expiration timestamp of the transient stored in the automatically generated option. Then calculate how much time it's left for the expiration.
			if ( $datas['tried'] >= 3 ) {
				$until = get_option( '_transient_timeout_' . 'ama_site_essentials_attempted_login' );
				$time = $this->time_to_go( $until );
			
				// Show the error message
				
				return new \WP_Error( 'too_many_tried',  sprintf( __( '<strong>ERROR</strong>: You have reached authentication limit, you will be able to try again in %1$s.' ) , $time ) );
			}
		}
	
		return $user;
	}

	public function login_failed( $username ) {

		// Adds a login attempt to the counter
		if ( get_transient( 'ama_site_essentials_attempted_login' ) ) {
			$datas = get_transient( 'ama_site_essentials_attempted_login' );
			$datas['tried']++;

			// If there have been less or equal to 3 failed attempts, resets the expiration time of 300 seconds (5 minutes) for the transient.
			if ( $datas['tried'] <= 3 )
				set_transient( 'ama_site_essentials_attempted_login', $datas , 300 );

		} else {
			// sets tried to 1, indicating the first failed login attempt and sets an expiration time of 300 seconds (5 minutes) for the transient.
			$datas = array(
				'tried'     => 1
			);
			set_transient( 'ama_site_essentials_attempted_login', $datas , 300 );
		}
	}

	public function time_to_go($timestamp) {
		// Defining periods and their lengths in seconds
		$periods = array(
			__("second", "ama-site-essentials"),
			__("minute", "ama-site-essentials"),
			__("hour", "ama-site-essentials"),
			__("day", "ama-site-essentials"),
			__("week", "ama-site-essentials"),
			__("month", "ama-site-essentials"),
			__("year", "ama-site-essentials")
		);
		$lengths = array(60, 60, 24, 7, 4.35, 12);

		// Calculate the time difference
		$current_timestamp = time();
		$difference = abs($current_timestamp - $timestamp);
		for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i++) {
			$difference /= $lengths[$i];
		}

		// Prepare the time as text. If it's more than 1, convert the time measurement to plural.
		$difference = round($difference);
		if (isset($difference)) {
			if ($difference != 1)
				$periods[$i] .= __("s", "ama-site-essentials");
			$output = "$difference $periods[$i]";
			return $output;
		}
	}
}