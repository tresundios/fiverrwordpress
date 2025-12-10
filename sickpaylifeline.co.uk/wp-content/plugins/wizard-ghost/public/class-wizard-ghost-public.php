<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/public
 * @author     Your Name <email@example.com>
 */
class Wizard_Ghost_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wizard_Ghost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wizard_Ghost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wizard-ghost-public.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wizard_Ghost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wizard_Ghost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wizard-ghost-public.js', array('jquery'), $this->version, false);

		// Localize script with AJAX URL
		wp_localize_script($this->plugin_name, 'wizardGhostData', array(
			'ajaxUrl' => admin_url('admin-ajax.php')
		));

	}

	/**
	 * Dequeue conflicting wizard-form scripts globally.
	 *
	 * @since    1.0.0
	 */
	public function dequeue_conflicting_scripts()
	{

		// Dequeue conflicting wizard-form scripts to prevent interference
		wp_dequeue_script('wizard-form');
		wp_dequeue_script('wizard-form-js');

	}

	/**
	 * Register shortcode for wizard form.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode()
	{

		add_shortcode('wizard_ghost', array($this, 'display_wizard_form'));

	}

	/**
	 * Display wizard form via shortcode.
	 *
	 * @since    1.0.0
	 * @return   string HTML output of the wizard form.
	 */
	public function display_wizard_form()
	{

		ob_start();
		include_once plugin_dir_path(__FILE__) . 'partials/wizard-ghost-public-display.php';
		return ob_get_clean();

	}

	/**
	 * Handle wizard form submission via AJAX.
	 *
	 * @since    1.0.0
	 */
	public function handle_form_submission()
	{

		// Log to custom file
		$log_file = WP_CONTENT_DIR . '/wizard-ghost-debug.log';
		$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: AJAX handler called!' . PHP_EOL;
		file_put_contents($log_file, $log_message, FILE_APPEND);

		$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: POST data: ' . json_encode($_POST) . PHP_EOL;
		file_put_contents($log_file, $log_message, FILE_APPEND);

		error_log('Wizard Ghost: AJAX handler called!');
		error_log('Wizard Ghost: POST data: ' . json_encode($_POST));

		// Verify nonce
		$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
		error_log('Wizard Ghost: Nonce received: ' . $nonce);

		if (!empty($nonce)) {
			$nonce_verified = wp_verify_nonce($nonce, 'wizard_ghost_step_3');
			error_log('Wizard Ghost: Nonce verification result: ' . ($nonce_verified ? 'VALID' : 'INVALID'));

			if (!$nonce_verified) {
				error_log('Wizard Ghost: Nonce verification failed.');
				wp_send_json_error(array('message' => 'Security check failed - invalid nonce.'));
				wp_die();
			}
		} else {
			error_log('Wizard Ghost: No nonce provided in form submission');
			wp_send_json_error(array('message' => 'Security check failed - no nonce.'));
			wp_die();
		}

		// Get form data - now flattened from JavaScript
		$sanitized_data = array(
			'occupation' => isset($_POST['occupation']) ? sanitize_text_field($_POST['occupation']) : '',
			'first_name' => isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '',
			'last_name' => isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '',
			'phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '',
			'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
			'dob' => isset($_POST['dob']) ? sanitize_text_field($_POST['dob']) : '',
			'help_with' => isset($_POST['help_with']) ? sanitize_text_field($_POST['help_with']) : '',
		);

		error_log('Wizard Ghost: Form data received: ' . json_encode($sanitized_data));

		// Send email notification and wait for result
		$email_sent = $this->send_submission_email($sanitized_data);

		// Store in database
		$this->store_form_submission($sanitized_data);

		// Return success with thank you message
		wp_send_json_success(array(
			'message' => 'Thank you! Your information has been submitted successfully. We\'ll be in touch with you shortly.',
			'email_sent' => $email_sent,
		));

	}

	/**
	 * Send submission email notification.
	 *
	 * @since    1.0.0
	 * @param    array $data Form data to send.
	 */
	private function send_submission_email($data)
	{

		$log_file = WP_CONTENT_DIR . '/wizard-ghost-debug.log';

		$email = get_option('wizard_ghost_email');
		$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: send_submission_email called. Email configured: ' . $email . PHP_EOL;
		file_put_contents($log_file, $log_message, FILE_APPEND);

		$cc_email = get_option('wizard_ghost_cc_email');
		$bcc_email = get_option('wizard_ghost_bcc_email');

		if (empty($email) || !is_email($email)) {
			$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: No valid email configured. Email: ' . $email . PHP_EOL;
			file_put_contents($log_file, $log_message, FILE_APPEND);
			error_log('Wizard Ghost: No valid email configured. Email: ' . $email);
			return false;
		}

		$subject = 'New_Short_Term_Form_Submission';

		$message = '<html><body>';
		$message .= '<h2>' . esc_html__('New Short Term Form Submission', 'wizard-ghost') . '</h2>';
		$message .= '<table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Occupation', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['occupation']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('First Name', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['first_name']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Last Name', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['last_name']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Phone', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['phone']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Email', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['email']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Date of Birth', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['dob']) . '</td>';
		$message .= '</tr>';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__('Help With', 'wizard-ghost') . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($data['help_with']) . '</td>';
		$message .= '</tr>';

		$message .= '</table>';
		$message .= '</body></html>';

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
			'Reply-To: ' . get_option('admin_email'),
		);

		if (!empty($cc_email) && is_email($cc_email)) {
			$headers[] = 'Cc: ' . $cc_email;
		}

		if (!empty($bcc_email) && is_email($bcc_email)) {
			$headers[] = 'Bcc: ' . $bcc_email;
		}

		$log_file = WP_CONTENT_DIR . '/plugins/wizard-ghost-debug.log';

		// Send email notification
		$mail_sent = wp_mail($email, $subject, $message, $headers);

		$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: Calling wp_mail to: ' . $email . PHP_EOL;
		$log_message .= '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: Subject: ' . $subject . PHP_EOL;
		$log_message .= '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: From: ' . get_option('admin_email') . PHP_EOL;
		if (!empty($cc_email)) {
			$log_message .= '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: CC: ' . $cc_email . PHP_EOL;
		}
		if (!empty($bcc_email)) {
			$log_message .= '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: BCC: ' . $bcc_email . PHP_EOL;
		}
		file_put_contents($log_file, $log_message, FILE_APPEND);

		if ($mail_sent) {
			$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: wp_mail returned TRUE - Email queued successfully to ' . $email . PHP_EOL;
			file_put_contents($log_file, $log_message, FILE_APPEND);
			error_log('Wizard Ghost: Email queued successfully to ' . $email);
		} else {
			$log_message = '[' . date('Y-m-d H:i:s') . '] Wizard Ghost: wp_mail returned FALSE - Failed to queue email to ' . $email . PHP_EOL;
			file_put_contents($log_file, $log_message, FILE_APPEND);
			error_log('Wizard Ghost: Failed to queue email to ' . $email);
		}

		return $mail_sent;

	}

	/**
	 * Store form submission in database.
	 *
	 * @since    1.0.0
	 * @param    array $data Form data to store.
	 */
	private function store_form_submission($data)
	{

		global $wpdb;

		// Create table if it doesn't exist
		$table_name = $wpdb->prefix . 'wizard_ghost_submissions';

		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				occupation varchar(255) NOT NULL,
				first_name varchar(255) NOT NULL,
				last_name varchar(255) NOT NULL,
				phone varchar(20) NOT NULL,
				email varchar(255) NOT NULL,
				dob date NOT NULL,
				help_with varchar(255) NOT NULL,
				submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta($sql);
		} else {
			// Check if column exists, if not add it
			$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table_name' AND column_name = 'help_with'");
			if (empty($row)) {
				$wpdb->query("ALTER TABLE $table_name ADD help_with varchar(255) NOT NULL");
			}
		}

		// Insert data
		$wpdb->insert(
			$table_name,
			array(
				'occupation' => $data['occupation'],
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'phone' => $data['phone'],
				'email' => $data['email'],
				'dob' => $data['dob'],
				'help_with' => $data['help_with'],
			),
			array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
		);

	}

	/**
	 * Mask email address for debug output.
	 *
	 * @since    1.0.0
	 * @param    string $email Email address to mask.
	 * @return   string Masked email address.
	 */
	private function mask_email($email)
	{
		if (!is_email($email)) {
			return 'Invalid Email';
		}
		$parts = explode('@', $email);
		$name = $parts[0];
		$domain = $parts[1];
		$masked_name = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));
		return $masked_name . '@' . $domain;
	}

}
