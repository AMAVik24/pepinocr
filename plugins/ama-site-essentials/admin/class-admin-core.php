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

	/* Create an Admin Page for settings*/
	public function add_admin_menu() {
		add_menu_page(
			'AMA Site Essentials',
			'AMA Site Essentials',
			'manage_options',
			'ama-site-essentials-settings',
			array( $this, 'settings_page' )
		);
	}

	public function add_admin_menu2() {
		add_menu_page(
			'AMA Site Essentials2',
			'AMA Site Essentials2',
			'manage_options',
			'ama-site-essentials-settings2',
			array( $this, 'settings_page' )
		);
	}


	/**
	 * ADMIN PAGE RELATED FUNCTIONS
	 */

	
	// Creates the html form for the admin settings page
	public function settings_page() {

		// Handle form submission and update all settings
		if (isset($_POST['ama_site_essentials_submit'])) {

			update_option('ama_site_essentials_custom_home_meta_description', sanitize_text_field($_POST['ama_site_essentials_custom_home_meta_description']));

			update_option('ama_site_essentials_gtm_header_tag', $_POST['ama_site_essentials_gtm_header_tag']);

			update_option('ama_site_essentials_gtm_body_tag', $_POST['ama_site_essentials_gtm_body_tag']);

			update_option('ama_site_essentials_smtp_username', sanitize_text_field($_POST['ama_site_essentials_smtp_username']));

			update_option('ama_site_essentials_smtp_password', $this -> my_encrypt(sanitize_text_field($_POST['ama_site_essentials_smtp_password'])));

			update_option('ama_site_essentials_smtp_sender', sanitize_text_field($_POST['ama_site_essentials_smtp_sender']));

			update_option('ama_site_essentials_smtp_name', sanitize_text_field($_POST['ama_site_essentials_smtp_name']));

			update_option('ama_site_essentials_smtp_server', sanitize_text_field($_POST['ama_site_essentials_smtp_server']));

			update_option('ama_site_essentials_smtp_port', sanitize_text_field($_POST['ama_site_essentials_smtp_port']));

			update_option('ama_site_essentials_smtp_secure', sanitize_text_field($_POST['ama_site_essentials_smtp_secure']));

		}
		?>
	
		<div class="wrap">
			<h2>AMA Site Essentials Settings</h2>
	
			<!-- Custom Home Meta Description Section -->
			<h3>Custom Home Meta Description</h3>
			<form method="post" action="">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ama_site_essentials_custom_home_meta_description">Custom Home Meta Description</label></th>
						<td>
							<textarea id="ama_site_essentials_custom_home_meta_description" name="ama_site_essentials_custom_home_meta_description" class="regular-text" rows="4" placeholder="Esta es la página web de XXXXXX, un pequeño negocio enfocado en XXXXX."><?php echo esc_attr(get_option('ama_site_essentials_custom_home_meta_description')); ?></textarea>
							<p class="description">Enter the custom meta description text for your home page.</p>
						</td>
					</tr>
				</table>

			<!-- Google Tag Manager Section -->
			<h3>Google Tag Manager</h3>
			<form method="post" action="">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="ama_site_essentials_gtm_header_tag">Google Tag Manager: Header</label></th>
						<td>
							<textarea id="ama_site_essentials_gtm_header_tag" name="ama_site_essentials_gtm_header_tag" class="regular-text" rows="7" placeholder="&lt;!-- Google Tag Manager --&gt;&#10;&lt;script&gt;XXX&lt;/script&gt;&#10;&lt;!-- End Google Tag Manager --&gt;"><?php echo esc_attr(get_option('ama_site_essentials_gtm_header_tag')); ?></textarea>
							<p class="description">Enter the GTM code for the header.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="ama_site_essentials_gtm_body_tag">Google Tag Manager: Body</label></th>
						<td>
							<textarea id="ama_site_essentials_gtm_body_tag" name="ama_site_essentials_gtm_body_tag" class="regular-text" rows="7" placeholder="&lt;!-- Google Tag Manager (noscript) --&gt;&#10;&lt;noscript&gt;XXX&lt;/noscript&gt;&#10;&lt;!-- End Google Tag Manager (noscript) --&gt;"><?php echo esc_attr(get_option('ama_site_essentials_gtm_body_tag')); ?></textarea>
							<p class="description">Enter the GTM code for the body.</p>
						</td>
					</tr>
				</table>
	
				<!-- Email Section -->
				<h3>Email Settings</h3>
				<table class="form-table">

					<?php 
					echo $this->generate_smtp_setting_row("smtp_username", "SMTP Username", "Enter the username of your SMTP server.");

					// Different field for the password. 
					// Defines the placeholder of the field based on wether the option has been set or not.

					if(get_option('ama_site_essentials_smtp_password') === false) {
						$password_placeholder = "";
					} else {
						$password_placeholder = "Hidden for security";
					}

					?>
					<tr>
					<th scope="row"><label for="ama_site_essentials_smtp_password">SMTP Password</label></th>
					<td>
						<input type="password" id="ama_site_essentials_smtp_password" name="ama_site_essentials_smtp_password" class="regular-text" placeholder= "<?php echo $password_placeholder ?>">
						<p class="description">Enter the password of your SMTP server.</p>
					</td>
					</tr>
					<?php

					echo $this->generate_smtp_setting_row("smtp_sender", "SMTP Sender", "Enter the email address from where the emails should be sent.");

					echo $this->generate_smtp_setting_row("smtp_name", "SMTP Name", "Enter the name that people will see from these emails.");

					echo $this->generate_smtp_setting_row("smtp_server", "SMTP Server", "Enter the address (hostname) of your SMTP server.");

					echo $this->generate_smtp_setting_row("smtp_port", "SMTP Port", "Enter the server port number.");

					echo $this->generate_smtp_setting_row("smtp_secure", "SMTP Encryption", "Enter the encryption protocol (tls is recommended).");
					?>

				</table>
	
				<?php submit_button('Save Settings', 'primary', 'ama_site_essentials_submit'); ?>
			</form>
		</div>
	
		<?php
	}

	// Generate a table row for a setting field, to be used in the SMTP settings table.
	function generate_smtp_setting_row($field_name_no_prefix, $setting_title, $description) {
		
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

	/**
	* EMAIL RELATED FUNCTIONS
	*/

	// Adds the SMTP authentication details when sending emails
	function my_phpmailer_smtp( $phpmailer ) {
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

	function add_address_to_bcc($args) {
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
	function my_encrypt($data) {
		$cipher = 'AES-256-CBC'; // You can choose a different encryption method if needed
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
		$encrypted = openssl_encrypt($data, $cipher, '3Sct2cJJS5yOrfQpgwqNYm/De+zwIW0ciTfb+8dVfXALvdsm/vZaEZAU1uhuIQ5x', 0, $iv);
		return base64_encode($iv . $encrypted);
	}
	
	// Decryption function
	function my_decrypt($data) {
		$cipher = 'AES-256-CBC'; // Make sure it matches the encryption method used
		$data = base64_decode($data);
		$iv_size = openssl_cipher_iv_length($cipher);
		$iv = substr($data, 0, $iv_size);
		$data = substr($data, $iv_size);
		return openssl_decrypt($data, $cipher, '3Sct2cJJS5yOrfQpgwqNYm/De+zwIW0ciTfb+8dVfXALvdsm/vZaEZAU1uhuIQ5x', 0, $iv);
	}

	// Checks how many login attempts have been made. If 3 or more attempts were made, it returns a WP_Error indicating that the user has reached the authentication limit.

	function check_attempted_login( $user, $username, $password ) {
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
	
		// converting the mysql timestamp to php time
		$periods = array(
			"second",
			"minute",
			"hour",
			"day",
			"week",
			"month",
			"year"
		);
		$lengths = array(
			"60",
			"60",
			"24",
			"7",
			"4.35",
			"12"
		);

		// Calculates the time left in human measurements
		$current_timestamp = time();
		$difference = abs($current_timestamp - $timestamp);
		for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i ++) {
			$difference /= $lengths[$i];
		}

		// Prepares the time as text. If it's more than 1, converts the time measurement to plural.
		$difference = round($difference);
		if (isset($difference)) {
			if ($difference != 1)
				$periods[$i] .= "s";
				$output = "$difference $periods[$i]";
				return $output;
		}
	}
}