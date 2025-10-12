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
// 2. DATABASE TABLE ON ACTIVATE
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
// 3. REWRITE RULES FOR STEPS
// --------------------------
add_action('init', function() {
    // Route /compare-life-insurance-wizard/step-x/ to the real page
    add_rewrite_rule(
        '^compare-life-insurance-wizard/([^/]*)/?$',
        'index.php?pagename=compare-life-insurance-wizard&wizard_step=$matches[1]',
        'top'
    );
});

add_filter('query_vars', function($vars) {
    $vars[] = 'wizard_step';
    return $vars;
});

// --------------------------
// 4. SHORTCODE TO DISPLAY WIZARD
// --------------------------
add_shortcode('wizard_form', function() {

    $step = get_query_var('wizard_step');
    if (empty($step)) $step = 'quotes';

    $progress = [
        'quotes' => 12,
        'cover' => 25,
        'smoked' => 37,
        'duration' => 50,
        'date-of-birth' => 62,
        'first-and-last-name' => 75,
        'postcode' => 87,
        'contact' => 100,
    ];
    $current_progress = $progress[$step] ?? 0;

    // Handle POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['compare-life-insurance-wizard'][$step] = $_POST;

        // Get the current step index and find the next step
        $steps = array_keys($progress);
        $current_index = array_search($step, $steps);
        $next_step = $steps[$current_index + 1] ?? '';

        if (empty($next_step)) {
            global $wpdb;
            $table = $wpdb->prefix . 'wizard_entries';
            $all_data = $_SESSION['compare-life-insurance-wizard'];

            $wpdb->insert($table, [
                'data' => maybe_serialize($all_data),
                'created_at' => current_time('mysql')
            ]);

            // Send Email
            $to = get_option('admin_email');
            $subject = 'New Wizard Submission';
            $message = print_r($all_data, true);
            wp_mail($to, $subject, $message);

            unset($_SESSION['compare-life-insurance-wizard']);
            wp_redirect(site_url('/thank-you/'));
            exit;
        } else {
            wp_redirect(site_url('/compare-life-insurance-wizard/' . sanitize_title($next_step) . '/'));
            exit;
        }
    }

    ob_start();
    ?>
    <style>
       
        
        .form-step {
            margin-bottom: 25px;
        }
        
        .form-step h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .form-step h2 i {
            font-size: 1.5em;
            color: #0073aa;
            width: 30px;
            text-align: center;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 8px 0;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .radio-option:hover {
            background: #e9ecef;
            border-color: #ced4da;
        }
        
        .radio-option input[type="radio"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }
        
        .info-text {
            font-size: 0.9em;
            color: #6c757d;
            margin: 5px 0 15px 30px;
            line-height: 1.5;
        }
        .wizard-container {
            width: 80%;
            margin: 0 10%;
            padding: 20px;
            box-sizing: border-box;
        }
        .wizard-progress {
            background: #eee;
            height: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: #0073aa;
            border-radius: 10px;
            text-align: center;
            color: white;
            line-height: 20px;
            font-size: 14px;
            transition: width 0.3s ease;
        }
        .wizard-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .wizard-form h2 {
            margin-top: 0;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .wizard-form label {
            display: block;
            margin: 10px 0;
            cursor: pointer;
        }
        .wizard-form input[type="text"],
        .wizard-form input[type="email"],
        .wizard-form input[type="tel"],
        .wizard-form input[type="date"],
        .wizard-form select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .wizard-form button[type="submit"] {
            background: #0073aa;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin: 40px 0 20px;
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .button {
            min-width: 120px;
            padding: 12px 25px;
            border: 2px solid transparent;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.2);
        }
        
        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .button:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .back-button {
            background: #f8f9fa;
            color: #495057;
            border-color: #dee2e6;
            padding: 10px 20px;
        }
        
        .back-button:hover {
            background: #e9ecef;
            border-color: #ced4da;
            color: #212529;
        }
        
        .next-button {
            background: #0073aa;
            color: white;
            border-color: #0056b3;
            margin-left: auto;
            padding: 12px 30px;
        }
        
        .next-button:hover {
            background: #005f8b;
            border-color: #004a8a;
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .form-navigation {
                flex-direction: column;
                gap: 10px;
            }
            
            .button {
                width: 100%;
                margin: 0;
            }
            
            .next-button {
                margin-top: 5px;
                order: -1;
            }
        }
    </style>
    
    <div class="wizard-container">
      <div class="wizard-progress">
        <div class="progress-bar" style="width:<?= esc_attr($current_progress) ?>%">
          <?= esc_html($current_progress) ?>%
        </div>
      </div>
      <div class="wizard-form">

      <form method="post" class="wizard-form">
        <?php
        switch ($step) {
            case 'quotes':
                echo '<div class="form-step">';
                echo '<h2>I\'d like quotes for:</h2>';
                echo '<label class="radio-option"><input type="radio" name="quotes_option" value="Just me" required><span>Just me</span></label>';
                echo '<label class="radio-option"><input type="radio" name="quotes_option" value="Me and my partner"><span>Me and my partner</span></label>';
                echo '</div>';
                break;

            case 'cover':
                echo '<div class="form-step">';
                echo '<h2>What would you like to cover?</h2>';
                echo '<label class="radio-option"><input type="radio" name="coverage" value="My Family" required><span>My Family</span></label>';
                echo '<label class="radio-option"><input type="radio" name="coverage" value="My Mortgage"><span>My Mortgage</span></label>';
                echo '<label class="radio-option"><input type="radio" name="coverage" value="Both"><span>Both</span></label>';
                echo '</div>';
                break;

            case 'smoked':
                echo '<div class="form-step">';
                echo '<h2>Have you smoked or used nicotine in the last 12 months?</h2>';
                echo '<p class="info-text">This includes vaping, using tobacco, e-cigarettes, cigars, pipes or any nicotine replacement products.</p>';
                echo '<div style="display: flex; gap: 15px;">';
                echo '<label class="radio-option" style="flex: 1; justify-content: center;"><input type="radio" name="smoking_status" value="yes" required><span>Yes</span></label>';
                echo '<label class="radio-option" style="flex: 1; justify-content: center;"><input type="radio" name="smoking_status" value="no"><span>No</span></label>';
                echo '</div>';
                echo '</div>';
                break;

            case 'duration':
                echo '<div class="form-group">';
                echo '<h2>How much cover would you like?</h2>';
                echo '<select name="cover_amount" class="ff-el-form-control" required>';
                echo '<option value="">- Select -</option>';
                echo '<option value="I\'m not sure" selected>I\'m not sure</option>';
                $amounts = [
                    '£10,000', '£20,000', '£30,000', '£40,000', '£50,000', '£60,000', '£70,000', '£80,000', '£90,000', 
                    '£100,000', '£110,000', '£120,000', '£130,000', '£140,000', '£150,000', '£160,000', '£170,000', 
                    '£180,000', '£190,000', '£200,000', '£225,000', '£250,000', '£275,000', '£300,000', '£325,000', 
                    '£350,000', '£375,000', '£400,000', '£425,000', '£450,000', '£475,000', '£500,000', '£550,000', 
                    '£600,000', '£650,000', '£700,000', '£750,000', '£800,000', '£850,000', '£900,000', '£950,000', 
                    '£1,000,000', 'More than £1,000,000'
                ];
                foreach ($amounts as $amount) {
                    echo '<option value="' . esc_attr($amount) . '">' . esc_html($amount) . '</option>';
                }
                echo '</select>';
                
                echo '<h2 style="margin-top: 20px;">How long would you like the cover for?</h2>';
                echo '<select name="cover_duration" class="ff-el-form-control" required>';
                echo '<option value="">- Select -</option>';
                echo '<option value="I\'m not sure" selected>I\'m not sure</option>';
                
                for ($i = 5; $i <= 40; $i++) {
                    $years = $i . ' year' . ($i > 1 ? 's' : '');
                    echo '<option value="' . esc_attr($years) . '">' . esc_html($years) . '</option>';
                }
                echo '<option value="More than 40 Years">More than 40 Years</option>';
                echo '</select>';
                echo '</div>';
                break;

            case 'date-of-birth':
                echo '<h2>Date of Birth</h2>';
                echo '<input type="date" name="date_of_birth" required max="' . date('Y-m-d') . '">';
                break;

            case 'first-and-last-name':
                echo '<h2>Your Name</h2>';
                echo '<input type="text" name="first_name" placeholder="First Name" required>';
                echo '<input type="text" name="last_name" placeholder="Last Name" required>';
                break;

            case 'postcode':
                echo '<h2>Postcode</h2>';
                echo '<input type="text" name="postcode" placeholder="Enter your postcode" pattern="[A-Za-z]{1,2}[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}" title="Please enter a valid UK postcode" required>';
                break;

            case 'contact':
                echo '<h2>Contact Information</h2>';
                echo '<input type="tel" name="mobile" placeholder="Mobile Number" required pattern="[0-9]{10,15}" title="Please enter a valid phone number">';
                echo '<input type="email" name="email" placeholder="Email Address" required>';
        }
        ?>
        <div class="form-navigation">
            <?php if ($step !== 'quotes'): ?>
                <?php 
                $steps = array_keys($progress);
                $current_index = array_search($step, $steps);
                $prev_step = $current_index > 0 ? $steps[$current_index - 1] : '';
                ?>
                <a href="<?= esc_url(site_url('/compare-life-insurance-wizard/' . $prev_step . '/')) ?>" class="button back-button">← Back</a>
            <?php endif; ?>
            <button type="submit" class="button next-button">Next →</button>
        </div>
      </form>
      </div>
    </div>
    <?php
    return ob_get_clean();
});

// --------------------------
// 5. OPTIONAL STYLES
// --------------------------
add_action('wp_enqueue_scripts', function() {
    wp_add_inline_style('wp-block-library', '
        .progress {background:#ddd;height:20px;border-radius:10px;}
        .progress div {height:100%;background:linear-gradient(90deg,#0073aa,#00a0d2);border-radius:10px;color:#fff;text-align:center;line-height:20px;transition:width .5s ease;}
    ');
});
