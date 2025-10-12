<?php
// Get current progress
$steps = ['quotes', 'cover', 'smoked', 'duration', 'date-of-birth', 'first-and-last-name', 'postcode', 'contact'];
$current_index = array_search($step, $steps);
$progress = $current_index !== false ? round(($current_index + 1) / count($steps) * 100) : 0;
?>

<div class="wizard-container">
    <form class="wizard-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-step="<?php echo esc_attr($step); ?>">
        <!-- Error Message Container -->
        <div class="error-message" style="display: none;"></div>
        
        <!-- Progress Bar -->
        <div class="wizard-progress">
            <div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr($progress); ?>%;" 
                 aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100">
                <?php echo round($progress); ?>%
            </div>
        </div>
        
        <!-- Current Step Indicator (for debugging) -->
        <input type="hidden" name="current_step" value="<?php echo esc_attr($step); ?>">
        
        <!-- Error Message -->
        <div class="error-message"></div>
        
        <!-- Step Content -->
        <div class="form-step">
            <?php
            // Include the appropriate step template
            $template_file = dirname(__FILE__) . '/steps/step-' . $step . '.php';
            if (file_exists($template_file)) {
                include $template_file;
            } else {
                echo '<p>Step not found. Please try again.</p>';
            }
            ?>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="form-navigation">
            <?php if (isset($current_step_data['prev'])) : ?>
                <a href="#" class="button back-button" data-prev-step="<?php echo esc_attr($current_step_data['prev']); ?>">
                    &larr; Back
                </a>
            <?php endif; ?>
            
            <?php if (isset($current_step_data['next'])) : ?>
                <?php if ($current_step_data['next'] === 'complete') : ?>
                <span>&nbsp;</span>
                <?php else : ?>
                <button type="submit" class="button next-button" name="submit" data-next-step="<?php echo esc_attr($current_step_data['next']); ?>">
                    Continue &rarr;
                </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- Hidden Fields -->
        <input type="hidden" name="action" value="wizard_form_submit">
        <input type="hidden" name="current_step" value="<?php echo esc_attr($step); ?>">
        <?php wp_nonce_field('wizard_form_action', 'wizard_form_nonce'); ?>
    </form>
</div>

<script type="text/javascript">
// Ensure the wizard_ajax object is available
var wizard_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('wizard_form_action'); ?>'
};
</script>

<style>
.wizard-container {
    width: 80%;
    margin: 0 10%;
    padding: 20px;
    box-sizing: border-box;
}

.wizard-form {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding: 20px 0;
    border-top: 1px solid #eee;
    position: relative;
    min-height: 60px; /* Ensure consistent height */
}

.button {
    padding: 12px 25px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.back-button {
    background: #f5f5f5;
    color: #333;
    position: absolute;
    left: 0;
}

.back-button:hover {
    background: #e0e0e0;
    text-decoration: none;
}

.next-button {
    background: #0073aa;
    color: white;
    position: absolute;
    right: 0;
}

.next-button:hover {
    background: #005177;
    color: white;
    text-decoration: none;
}

.next-button:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

/* Make sure the buttons are visible on all steps */
.form-step {
    min-height: 200px; /* Ensure there's enough space for the content */
    position: relative;
    padding-bottom: 20px;
}

/* Loading state */
.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.loading:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 50 50"><path fill="%230073aa" d="M25,5A20,20,0,1,0,45,25,20,20,0,0,0,25,5Zm0,35A15,15,0,1,1,40,25,15,15,0,0,1,25,40Z"><animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="1s" repeatCount="indefinite"/><animate attributeName="stroke-dasharray" values="0, 100; 50, 50; 0, 100" dur="1s" repeatCount="indefinite"/></path></svg>') no-repeat center center;
    background-size: 40px 40px;
    z-index: 10;
    border-radius: 4px;
}

/* Error message styling */
.error-message {
    color: #dc3232;
    margin: 10px 0;
    padding: 10px;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    display: none;
}

.wizard-progress {
    height: 20px;
    background: #f0f0f0;
    border-radius: 10px;
    margin-bottom: 30px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: #0073aa;
    color: white;
    text-align: center;
    line-height: 20px;
    font-size: 12px;
    transition: width 0.3s ease;
}

.form-step {
    margin-bottom: 30px;
}

h2 {
    margin-top: 0;
    color: #333;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="date"],
select {
    width: 100%;
    padding: 10px;
    margin: 5px 0 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.radio-option {
    display: block;
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    margin-right: 10px;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.button {
    padding: 10px 20px;
    border: 1px solid #ddd;
    background: #f7f7f7;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
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

/* Loading state */
.wizard-form.loading .form-navigation {
    opacity: 0.7;
    pointer-events: none;
}

.wizard-form.loading::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.wizard-form.loading::before {
    content: 'Loading...';
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1001;
    background: #333;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
}

/* Mobile Optimization */
@media screen and (max-width: 768px) {
    .wizard-container {
        width: 95%;
        margin: 0 2.5%;
        padding: 10px;
    }
    
    .wizard-form {
        padding: 20px 15px;
    }
    
    .form-navigation {
        flex-direction: column;
        gap: 10px;
        margin-top: 30px;
        padding: 15px 0;
        min-height: auto;
    }
    
    .back-button,
    .next-button {
        position: static;
        width: 100%;
        margin: 0;
    }
    
    .button {
        padding: 14px 20px;
        font-size: 16px;
        width: 100%;
    }
    
    .next-button {
        order: -1; /* Show next button first on mobile */
    }
    
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    select {
        font-size: 16px; /* Prevents zoom on iOS */
        padding: 12px;
    }
    
    h2 {
        font-size: 1.5em;
    }
    
    .radio-option {
        padding: 12px;
        margin: 8px 0;
    }
}

@media screen and (max-width: 480px) {
    .wizard-container {
        width: 100%;
        margin: 0;
        padding: 5px;
    }
    
    .wizard-form {
        padding: 15px 10px;
        border-radius: 0;
    }
    
    .progress-bar {
        font-size: 11px;
    }
}
</style>
