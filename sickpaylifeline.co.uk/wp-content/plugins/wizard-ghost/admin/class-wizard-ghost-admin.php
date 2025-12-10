<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/admin
 * @author     Your Name <email@example.com>
 */
class Wizard_Ghost_Admin
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
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wizard-ghost-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wizard-ghost-admin.js', array('jquery'), $this->version, false);

	}

	/**
	 * Add admin menu page for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu()
	{

		add_menu_page(
			esc_html__('Wizard Ghost', 'wizard-ghost'),
			esc_html__('Wizard Ghost', 'wizard-ghost'),
			'manage_options',
			'wizard-ghost',
			array($this, 'display_admin_page'),
			'dashicons-wizard',
			25
		);

	}

	/**
	 * Display the admin page for this plugin
	 *
	 * @since    1.0.0
	 */
	public function display_admin_page()
	{

		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wizard-ghost'));
		}

		include_once plugin_dir_path(__FILE__) . 'partials/wizard-ghost-admin-display.php';

	}

	/**
	 * Handle admin settings form submission
	 *
	 * @since    1.0.0
	 */
	public function save_admin_settings()
	{

		if (!isset($_POST['wizard_ghost_admin_nonce']) || !wp_verify_nonce($_POST['wizard_ghost_admin_nonce'], 'wizard_ghost_admin_action')) {
			return;
		}

		if (!current_user_can('manage_options')) {
			return;
		}

		$email = isset($_POST['wizard_ghost_email']) ? sanitize_email($_POST['wizard_ghost_email']) : '';
		$cc_email = isset($_POST['wizard_ghost_cc_email']) ? sanitize_email($_POST['wizard_ghost_cc_email']) : '';
		$bcc_email = isset($_POST['wizard_ghost_bcc_email']) ? sanitize_email($_POST['wizard_ghost_bcc_email']) : '';
		$redirect_url = isset($_POST['wizard_ghost_redirect_url']) ? esc_url_raw($_POST['wizard_ghost_redirect_url']) : '';

		if (!empty($email) && is_email($email)) {
			update_option('wizard_ghost_email', $email);
		}

		if (!empty($cc_email) && is_email($cc_email)) {
			update_option('wizard_ghost_cc_email', $cc_email);
		} else {
			delete_option('wizard_ghost_cc_email');
		}

		if (!empty($bcc_email) && is_email($bcc_email)) {
			update_option('wizard_ghost_bcc_email', $bcc_email);
		} else {
			delete_option('wizard_ghost_bcc_email');
		}

		if (!empty($redirect_url)) {
			update_option('wizard_ghost_redirect_url', $redirect_url);
		}

		// Send test email if requested
		if (isset($_POST['wizard_ghost_test_email']) && !empty($email)) {
			$this->send_test_email($email);
		}

		wp_safe_remote_post(admin_url('admin.php?page=wizard-ghost'), array(
			'blocking' => false,
		));

	}

	/**
	 * Send test email
	 *
	 * @since    1.0.0
	 * @param    string $email Email address to send test to.
	 */
	private function send_test_email($email)
	{

		$subject = 'Wizard Ghost - Test Email';
		$message = '<html><body>';
		$message .= '<h2>Test Email from Wizard Ghost</h2>';
		$message .= '<p>This is a test email to verify that email notifications are working correctly.</p>';
		$message .= '<p><strong>Site:</strong> ' . get_bloginfo('name') . '</p>';
		$message .= '<p><strong>Time:</strong> ' . current_time('mysql') . '</p>';
		$message .= '</body></html>';

		$headers = array('Content-Type: text/html; charset=UTF-8');

		$mail_sent = wp_mail($email, $subject, $message, $headers);

		if ($mail_sent) {
			add_action('admin_notices', function () {
				echo '<div class="notice notice-success is-dismissible"><p>Test email sent successfully!</p></div>';
			});
		} else {
			add_action('admin_notices', function () {
				echo '<div class="notice notice-error is-dismissible"><p>Failed to send test email. Check your server mail configuration.</p></div>';
			});
		}

	}

}
