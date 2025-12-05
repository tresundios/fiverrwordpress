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
class Wizard_Ghost_Public {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wizard-ghost-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wizard-ghost-public.js', array( 'jquery' ), $this->version, false );

		// Localize script with AJAX URL
		wp_localize_script( $this->plugin_name, 'wizardGhostData', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' )
		) );

	}

	/**
	 * Dequeue conflicting wizard-form scripts globally.
	 *
	 * @since    1.0.0
	 */
	public function dequeue_conflicting_scripts() {

		// Dequeue conflicting wizard-form scripts to prevent interference
		wp_dequeue_script( 'wizard-form' );
		wp_dequeue_script( 'wizard-form-js' );

	}

	/**
	 * Register shortcode for wizard form.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode() {

		add_shortcode( 'wizard_ghost', array( $this, 'display_wizard_form' ) );

	}

	/**
	 * Display wizard form via shortcode.
	 *
	 * @since    1.0.0
	 * @return   string HTML output of the wizard form.
	 */
	public function display_wizard_form() {

		ob_start();
		include_once plugin_dir_path( __FILE__ ) . 'partials/wizard-ghost-public-display.php';
		return ob_get_clean();

	}

	/**
	 * Handle wizard form submission via AJAX.
	 *
	 * @since    1.0.0
	 */
	public function handle_form_submission() {

		// Verify nonce
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( empty( $nonce ) ) {
			error_log( 'Wizard Ghost: No nonce provided in form submission' );
			wp_send_json_error( array( 'message' => 'Security check failed - no nonce.' ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'wizard_ghost_step_3' ) ) {
			error_log( 'Wizard Ghost: Nonce verification failed. Nonce: ' . $nonce );
			wp_send_json_error( array( 'message' => 'Security check failed - invalid nonce.' ) );
		}

		// Get form data
		$form_data = isset( $_POST['formData'] ) ? $_POST['formData'] : array();

		error_log( 'Wizard Ghost: Form data received: ' . json_encode( $form_data ) );

		// Sanitize and validate data
		$sanitized_data = array(
			'occupation'  => isset( $form_data['occupation'] ) ? sanitize_text_field( $form_data['occupation'] ) : '',
			'first_name'  => isset( $form_data['first_name'] ) ? sanitize_text_field( $form_data['first_name'] ) : '',
			'last_name'   => isset( $form_data['last_name'] ) ? sanitize_text_field( $form_data['last_name'] ) : '',
			'phone'       => isset( $form_data['phone'] ) ? sanitize_text_field( $form_data['phone'] ) : '',
			'email'       => isset( $form_data['email'] ) ? sanitize_email( $form_data['email'] ) : '',
			'dob'         => isset( $form_data['dob'] ) ? sanitize_text_field( $form_data['dob'] ) : '',
		);

		error_log( 'Wizard Ghost: Sanitized data: ' . json_encode( $sanitized_data ) );

		// Store in database
		$this->store_form_submission( $sanitized_data );

		// Send email notification
		$this->send_submission_email( $sanitized_data );

		// Get redirect URL
		$redirect_url = get_option( 'wizard_ghost_redirect_url' );
		if ( empty( $redirect_url ) ) {
			$redirect_url = home_url( '/thank-you/' );
		}

		wp_send_json_success( array(
			'message' => 'Form submitted successfully.',
			'redirect' => $redirect_url,
		) );

	}

	/**
	 * Send submission email notification.
	 *
	 * @since    1.0.0
	 * @param    array $data Form data to send.
	 */
	private function send_submission_email( $data ) {

		$email = get_option( 'wizard_ghost_email' );
		if ( empty( $email ) || ! is_email( $email ) ) {
			error_log( 'Wizard Ghost: No valid email configured. Email: ' . $email );
			return;
		}

		$subject = esc_html__( 'New Wizard Ghost Form Submission', 'wizard-ghost' );

		$message = '<html><body>';
		$message .= '<h2>' . esc_html__( 'New Form Submission', 'wizard-ghost' ) . '</h2>';
		$message .= '<table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'Occupation', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['occupation'] ) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'First Name', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['first_name'] ) . '</td>';
		$message .= '</tr>';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'Last Name', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['last_name'] ) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'Phone', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['phone'] ) . '</td>';
		$message .= '</tr>';

		$message .= '<tr style="background-color: #f9f9f9;">';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'Email', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['email'] ) . '</td>';
		$message .= '</tr>';

		$message .= '<tr>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;"><strong>' . esc_html__( 'Date of Birth', 'wizard-ghost' ) . '</strong></td>';
		$message .= '<td style="padding: 10px; border: 1px solid #ddd;">' . esc_html( $data['dob'] ) . '</td>';
		$message .= '</tr>';

		$message .= '</table>';
		$message .= '</body></html>';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$mail_sent = wp_mail( $email, $subject, $message, $headers );

		if ( $mail_sent ) {
			error_log( 'Wizard Ghost: Email sent successfully to ' . $email );
		} else {
			error_log( 'Wizard Ghost: Failed to send email to ' . $email );
		}

	}

	/**
	 * Store form submission in database.
	 *
	 * @since    1.0.0
	 * @param    array $data Form data to store.
	 */
	private function store_form_submission( $data ) {

		global $wpdb;

		// Create table if it doesn't exist
		$table_name = $wpdb->prefix . 'wizard_ghost_submissions';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				occupation varchar(255) NOT NULL,
				first_name varchar(255) NOT NULL,
				last_name varchar(255) NOT NULL,
				phone varchar(20) NOT NULL,
				email varchar(255) NOT NULL,
				dob date NOT NULL,
				submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		// Insert data
		$wpdb->insert(
			$table_name,
			array(
				'occupation'  => $data['occupation'],
				'first_name'  => $data['first_name'],
				'last_name'   => $data['last_name'],
				'phone'       => $data['phone'],
				'email'       => $data['email'],
				'dob'         => $data['dob'],
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s' )
		);

	}

}
