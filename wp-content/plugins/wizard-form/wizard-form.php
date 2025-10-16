<?php
/*
Plugin Name: Custom Wizard Form
Description: Multi-step SEO-friendly wizard form with progress and email sending.
Version: 1.0
Author: Navis Michael Bearly J
*/

if (!defined('ABSPATH')) exit; // Prevent direct access

// --------------------------
// 1. SESSION START
// --------------------------
add_action('init', function() {
    if (!session_id()) session_start();
});

// --------------------------
// 2. ENQUEUE SCRIPTS AND STYLES
// --------------------------
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('wizard-form-js', plugins_url('wizard-form.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('wizard-form-js', 'wizard_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wizard_form_nonce')
    ));
});

// --------------------------
// 3. DATABASE TABLE ON ACTIVATE
// --------------------------
register_activation_hook(__FILE__, function() {
    global $wpdb;
    $table = $wpdb->prefix . 'wizard_entries';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        data longtext NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY (id)
    ) $charset;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// --------------------------
// 4. REWRITE RULES FOR STEPS
// --------------------------
add_action('init', function() {
    add_rewrite_rule(
        'compare-life-insurance/([^/]+)/?$',
        'index.php?wizard_step=$matches[1]',
        'top'
    );
    
    // Add query var
    add_rewrite_tag('%wizard_step%', '([^&]+)');
});

// Add custom query vars
add_filter('query_vars', function($vars) {
    $vars[] = 'wizard_step';
    return $vars;
});

// --------------------------
// 5. AJAX HANDLERS
// Handle form submission via AJAX
add_action('wp_ajax_wizard_form_submit', 'handle_wizard_form_submit');
add_action('wp_ajax_nopriv_wizard_form_submit', 'handle_wizard_form_submit');

function handle_wizard_form_submit() {
    // Verify nonce for security
    if (!isset($_POST['wizard_form_nonce']) || !wp_verify_nonce($_POST['wizard_form_nonce'], 'wizard_form_action')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
        wp_die();
    }

    // Process form data
    $current_step = isset($_POST['current_step']) ? sanitize_text_field($_POST['current_step']) : 'quotes';
    $next_step = isset($_POST['next_step']) ? sanitize_text_field($_POST['next_step']) : 'quotes';
    
    // Initialize session data if not set
    if (!isset($_SESSION['wizard_data'])) {
        $_SESSION['wizard_data'] = [];
    }
    
    // Process form data
    $form_data = [];
    
    // Handle regular POST data
    foreach ($_POST as $key => $value) {
        // Skip system fields
        if (in_array($key, ['wizard_form_nonce', 'action', 'current_step', 'next_step'])) {
            continue;
        }
        
        if (is_array($value)) {
            $form_data[$key] = array_map('sanitize_text_field', $value);
        } else {
            $form_data[$key] = sanitize_text_field($value);
        }
    }
    
    // Handle file uploads if any
    if (!empty($_FILES)) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        
        foreach ($_FILES as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $upload = wp_handle_upload($file, array('test_form' => false));
                if ($upload && !isset($upload['error'])) {
                    $form_data[$key] = $upload['url'];
                }
            }
        }
    }
    
    // Merge with existing session data
    $_SESSION['wizard_data'] = array_merge($_SESSION['wizard_data'], $form_data);
    
    // Debug: Log the session data
    error_log('Wizard Form Data: ' . print_r($_SESSION['wizard_data'], true));
    
    // If this is the final step, process the complete form
    if ($next_step === 'complete') {
        $result = process_complete_form();
        if ($result['success']) {
            // Clear the session after successful submission
            unset($_SESSION['wizard_data']);
            
            wp_send_json_success([
                'redirect' => home_url('/thank-you/') // Change this to your thank you page
            ]);
            wp_die();
        } else {
            wp_send_json_error([
                'message' => $result['message'] ?? 'An error occurred while processing your submission.'
            ]);
            wp_die();
        }
    }
    
    // Get the next step HTML
    $html = get_wizard_step_html($next_step);
    
    if ($html === false) {
        wp_send_json_error([
            'message' => 'Failed to load the next step. Please try again.'
        ]);
        wp_die();
    }
    
    // Calculate progress
    $progress = calculate_progress($next_step);
    
    // Return the response
    wp_send_json_success([
        'html' => $html,
        'progress' => $progress
    ]);
    
    // Always die in functions echoing AJAX content
    wp_die();
}

// Handle loading steps via AJAX
add_action('wp_ajax_wizard_load_step', 'handle_wizard_load_step');
add_action('wp_ajax_nopriv_wizard_load_step', 'handle_wizard_load_step');

function handle_wizard_load_step() {
    // Verify nonce for security
    if (!isset($_GET['wizard_form_nonce']) || !wp_verify_nonce($_GET['wizard_form_nonce'], 'wizard_form_action')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
        wp_die();
    }
    
    $step = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : 'quotes';
    $html = get_wizard_step_html($step);
    $progress = calculate_progress($step);
    
    if ($html === false) {
        wp_send_json_error('Failed to load step');
        wp_die();
    }
    
    // Get saved data from session to pass to JavaScript
    $saved_data = isset($_SESSION['wizard_data']) ? $_SESSION['wizard_data'] : [];
    
    wp_send_json_success([
        'html' => $html,
        'progress' => $progress,
        'saved_data' => $saved_data
    ]);
    
    wp_die(); // This is required to terminate immediately and return a proper response
}

// Helper function to get HTML for a specific step
function get_wizard_step_html($step) {
    // Define steps data
    $steps = array(
        'quotes' => array(
            'name' => 'Quotes',
            'next' => 'cover'
        ),
        'cover' => array(
            'name' => 'Cover Type',
            'next' => 'smoked',
            'prev' => 'quotes'
        ),
        'smoked' => array(
            'name' => 'Smoking Status',
            'next' => 'duration',
            'prev' => 'cover'
        ),
        'duration' => array(
            'name' => 'Cover Duration',
            'next' => 'date-of-birth',
            'prev' => 'smoked'
        ),
        'date-of-birth' => array(
            'name' => 'Date of Birth',
            'next' => 'first-and-last-name',
            'prev' => 'duration'
        ),
        'first-and-last-name' => array(
            'name' => 'Your Name',
            'next' => 'postcode',
            'prev' => 'date-of-birth'
        ),
        'postcode' => array(
            'name' => 'Postcode',
            'next' => 'contact',
            'prev' => 'first-and-last-name'
        ),
        'contact' => array(
            'name' => 'Contact Info',
            'next' => 'note',
            'prev' => 'postcode'
        ),
        'note' => array(
            'name' => 'Quick Note',
            'next' => 'complete',
            'prev' => 'contact'
        ),
        'complete' => array(
            'name' => 'Get Quotes',
            'prev' => 'note'
        )
    );

    // Get current step data
    $current_step_data = isset($steps[$step]) ? $steps[$step] : [];
    
    // Get saved form data from session
    $saved_data = isset($_SESSION['wizard_data']) ? $_SESSION['wizard_data'] : [];

    ob_start();
    
    // Include the step template
    $template_file = plugin_dir_path(__FILE__) . 'templates/steps/step-' . $step . '.php';
    if (file_exists($template_file)) {
        include $template_file;
    } else {
        echo '<p>Step not found. Please try again.</p>';
    }
    
    // Add navigation buttons
    ?>
    <div class="form-navigation">
        <?php if (isset($current_step_data['prev'])) : ?>
            <a href="#" class="button back-button" data-prev-step="<?php echo esc_attr($current_step_data['prev']); ?>">
                &larr; Back
            </a>
        <?php endif; ?>
        
        <?php if (isset($current_step_data['next']) && $current_step_data['next'] !== 'complete' && $current_step_data['next'] !== 'note') : ?>
            <button type="submit" class="button next-button" name="submit" data-next-step="<?php echo esc_attr($current_step_data['next']); ?>">
                <?php echo $current_step_data['next'] === 'complete' ? 'Compare Quotes' : 'Continue &rarr;abc'; ?>
            </button>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean();
}

// Helper function to calculate progress
function calculate_progress($step) {
    $steps = ['quotes', 'cover', 'smoked', 'duration', 'date-of-birth', 'first-and-last-name', 'postcode', 'contact', 'note', 'complete'];
    $current_index = array_search($step, $steps);
    
    if ($current_index === false) {
        return 0;
    }
    
    return round(($current_index + 1) / count($steps) * 100);
}

// Process complete form (for the final submission)
function process_complete_form() {
    global $wpdb;
    
    // Get all form data from session
    $form_data = isset($_SESSION['wizard_data']) ? $_SESSION['wizard_data'] : [];
    
    if (empty($form_data)) {
        return ['success' => false, 'message' => 'No form data found.'];
    }
    
    // Save to database
    $table_name = $wpdb->prefix . 'wizard_entries';
    $result = $wpdb->insert(
        $table_name,
        [
            'data' => json_encode($form_data),
            'created_at' => current_time('mysql')
        ],
        ['%s', '%s']
    );
    
    if ($result === false) {
        return ['success' => false, 'message' => 'Failed to save form data.'];
    }
    
    // Send email notification
    $to = array(
        'noleen@lifeinsurancenorthernireland.co.uk',
        'mcurran6911@gmail.com',
        'navis.programmer@gmail.com'
    );
    $subject = 'Quote Request | Life Insurance Under 30';
    //$message = 'New Quote Request received for Life Insurance Under 30:\n\n';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $message ="<html>";
    $message .="<head><title>Quote Request | Life Insurance Under 30</title></head>";
    $message .="<body>";
    $message .="<img src='https://lifeinsuranceunder30.co.uk/wp-content/uploads/2025/05/cropped-cropped-Life-Insurance-Under-30.png' alt='Life Insurance Under 30'>";
    $message .="<h1>Quote Request | Life Insurance Under 30</h1>";
    $message .="<table>";
    $message .="<tr><th>Item</th><th>Value</th></tr>";
    foreach ($form_data as $key => $value) {
        //$message .= ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . "\n";
        $message .="<tr><td>".ucfirst(str_replace('_', ' ', $key))."</td><td>".$value."</td></tr>";
    }
    $message .="</table>";
    $message .="</body>";
    $message .="</html>";
    
    // Send to all recipients
    foreach ($to as $email) {
        wp_mail($email, $subject, $message, $headers);
    }
    
    // Clear session data
    unset($_SESSION['wizard_data']);
    
    return ['success' => true];
}

// --------------------------
// 6. SHORTCODE
// --------------------------
function wizard_form_shortcode() {
    // Start output buffering
    ob_start();
    
    // Get the current step from URL parameter or default to 'quotes'
    $step = isset($_GET['wizard_step']) ? sanitize_text_field($_GET['wizard_step']) : 'quotes';
    
    // Define valid steps with their display names and next steps
    $steps = array(
        'quotes' => array(
            'name' => 'Quotes',
            'next' => 'cover'
        ),
        'cover' => array(
            'name' => 'Cover Type',
            'next' => 'smoked',
            'prev' => 'quotes'
        ),
        'smoked' => array(
            'name' => 'Smoking Status',
            'next' => 'duration',
            'prev' => 'cover'
        ),
        'duration' => array(
            'name' => 'Cover Duration',
            'next' => 'date-of-birth',
            'prev' => 'smoked'
        ),
        'date-of-birth' => array(
            'name' => 'Date of Birth',
            'next' => 'first-and-last-name',
            'prev' => 'duration'
        ),
        'first-and-last-name' => array(
            'name' => 'Your Name',
            'next' => 'postcode',
            'prev' => 'date-of-birth'
        ),
        'postcode' => array(
            'name' => 'Postcode',
            'next' => 'contact',
            'prev' => 'first-and-last-name'
        ),
        'contact' => array(
            'name' => 'Contact Info',
            'next' => 'note',
            'prev' => 'postcode'
        ),
        'note' => array(
            'name' => 'Quick Note',
            'next' => 'complete',
            'prev' => 'contact'
        ),
        'complete' => array(
            'name' => 'Get Quotes',
            'prev' => 'note'
        )
    );
    
    // Validate step
    if (!array_key_exists($step, $steps)) {
        $step = 'quotes';
    }
    
    // Calculate progress percentage
    $step_keys = array_keys($steps);
    $current_step_index = array_search($step, $step_keys);
    $progress = ($current_step_index / (count($steps) - 1)) * 100;
    
    // Get current step data
    $current_step_data = $steps[$step];
    
    // Get saved form data from session
    $saved_data = isset($_SESSION['wizard_data']) ? $_SESSION['wizard_data'] : [];
    
    // Include the form template
    include plugin_dir_path(__FILE__) . 'templates/wizard-form.php';
    
    // Return the output
    return ob_get_clean();
}

add_shortcode('wizard_form', 'wizard_form_shortcode');

// Flush rewrite rules on plugin activation
register_activation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Flush rewrite rules on plugin deactivation
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
