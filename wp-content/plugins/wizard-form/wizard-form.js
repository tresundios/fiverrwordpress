// Initialize wizard_ajax if not already defined
var wizard_ajax = window.wizard_ajax || {};

jQuery(document).ready(function($) {
    'use strict';
    
    // Helper function to update URL with clean rewrite structure
    function updateUrlParameter(key, value) {
        // Get the base URL without query strings
        let baseUrl = window.location.origin + window.location.pathname;
        
        // Remove any existing step from the URL
        baseUrl = baseUrl.replace(/\/[^\/]+\/?$/, '');
        
        // If we're not at the base wizard URL, ensure we have the correct base
        if (!baseUrl.includes('compare-life-insurance')) {
            // Find the wizard base URL
            const pathParts = window.location.pathname.split('/').filter(part => part);
            const wizardIndex = pathParts.indexOf('compare-life-insurance');
            if (wizardIndex !== -1) {
                baseUrl = window.location.origin + '/' + pathParts.slice(0, wizardIndex + 1).join('/');
            }
        }
        
        // Construct the new URL with the step
        const newUrl = baseUrl + '/' + value + '/';
        
        // Update the browser URL without reloading
        window.history.pushState({step: value}, '', newUrl);
    }
    
    // Function to show error messages
    function showError(message) {
        // Create or update error message element
        let $error = $('.error-message');
        if (!$error.length) {
            $error = $('<div class="error-message"></div>');
            $('.wizard-form').prepend($error);
        }
        
        $error.text(message).fadeIn();
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            $error.fadeOut();
        }, 5000);
    }
    
    // Function to update navigation buttons based on current step
    function updateNavigationButtons(step) {
        // Show/hide back button
        const $backButton = $('.back-button');
        if ($backButton.length) {
            $backButton.toggle(step !== 'quotes');
        }
        
        // Update next button text and alignment
        const $nextButton = $('.next-button');
        if ($nextButton.length) {
            const isLastStep = $nextButton.data('next-step') === 'complete';
            $nextButton.html(isLastStep ? 'Get Your Quotes Now' : 'Continue &rarr;');
            
            // Center align the button if it's the last step
            if (isLastStep === 'Get Your Quotes Now') {
                $('.submit-button-wrapper .next-button').removeClass('next-button');
                // $nextButton.removeClass('next-button');
                // $nextButton.parent().css({
                //     'text-align': 'center',
                //     'display': 'flex',
                //     'justify-content': 'center'
                // });
            }
        }
    }
    
    // Function to restore visual state of form elements
    function restoreFormState() {
        // Restore visual state for radio buttons with images
        $('.auto-submit-radio:checked').each(function() {
            $(this).closest('.ff-el-image-holder').addClass('ff_item_selected');
        });
    }
    
    // Function to load a specific step
    function loadStep(step) {
        const $container = $('.wizard-container');
        const $form = $('.wizard-form');
        
        // Show loading state
        $container.addClass('loading');
        
        // Get the nonce
        const nonce = $form.find('input[name="wizard_form_nonce"]').val() || wizard_ajax.nonce;
        
        // Load step via AJAX
        $.ajax({
            url: wizard_ajax.ajax_url,
            type: 'GET',
            data: {
                action: 'wizard_load_step',
                step: step,
                wizard_form_nonce: nonce
            },
            dataType: 'json',
            success: function(response) {
                $container.removeClass('loading');
                
                if (response && response.success) {
                    if (response.data.html) {
                        // Update the URL
                        updateUrlParameter('wizard_step', step);
                        
                        // Create the new form HTML with proper structure
                        const newFormHtml = '<div class="wizard-container">' +
                            '<form class="wizard-form" method="post" action="' + wizard_ajax.ajax_url + '" data-step="' + step + '">' +
                            '<input type="hidden" name="action" value="wizard_form_submit">' +
                            '<input type="hidden" name="current_step" value="' + step + '">' +
                            '<input type="hidden" name="wizard_form_nonce" value="' + nonce + '">' +
                            '<div class="wizard-progress">' +
                            '<div class="progress-bar" role="progressbar" style="width: ' + response.data.progress + '%;" aria-valuenow="' + response.data.progress + '" aria-valuemin="0" aria-valuemax="100">' +
                            Math.round(response.data.progress) + '%' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-step">' +
                            response.data.html +
                            '</div>' +
                            '</form>' +
                            '</div>';
                        
                        // Replace the container content
                        $container.replaceWith(newFormHtml);
                        
                        // Restore visual state for form elements
                        restoreFormState();
                        
                        // Reinitialize the form
                        initWizardForm();
                        
                        // Scroll to top of form
                        $('html, body').animate({
                            scrollTop: $('.wizard-container').offset().top - 20
                        }, 300);
                    }
                } else {
                    showError('Failed to load step. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                $container.removeClass('loading');
                console.error('AJAX Error:', status, error);
                showError('Failed to load the step. Please try again.');
            }
        });
    }
    
    // Initialize the form
    function initWizardForm() {
        const $form = $('.wizard-form');
        if (!$form.length) return;
        
        // Get current step from URL path or form data attribute
        let currentStep = $form.data('step');
        
        // If not in form data, try to extract from URL
        if (!currentStep) {
            const pathParts = window.location.pathname.split('/').filter(part => part);
            const wizardIndex = pathParts.indexOf('compare-life-insurance');
            
            if (wizardIndex !== -1 && pathParts[wizardIndex + 1]) {
                currentStep = pathParts[wizardIndex + 1];
            } else {
                // Fallback to query string if clean URL not found
                const urlParams = new URLSearchParams(window.location.search);
                currentStep = urlParams.get('wizard_step') || 'quotes';
            }
            
            $form.data('step', currentStep);
        }
        
        // Ensure navigation buttons are visible
        updateNavigationButtons(currentStep);
        
        // Initialize form submission
        initFormSubmission();
    }
    
    // Initialize form submission handler
    function initFormSubmission() {
        const $form = $('.wizard-form');
        
        $form.off('submit').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $container = $('.wizard-container');
            const currentStep = $form.data('step') || 'quotes';
            
            // Show loading state
            $form.addClass('loading');
            
            // Get the next step from the clicked button
            const $nextButton = $('button[type="submit"].next-button', this);
            const nextStep = $nextButton.data('next-step');
            
            if (!nextStep) {
                console.error('Next step not defined');
                $form.removeClass('loading');
                return false;
            }
            
            // Get the nonce from the form
            const nonce = $form.find('input[name="wizard_form_nonce"]').val() || wizard_ajax.nonce;
            
            // Create form data object
            const formData = new FormData($form[0]);
            formData.append('action', 'wizard_form_submit');
            formData.append('next_step', nextStep);
            formData.append('wizard_form_nonce', nonce);
            
            // Submit form via AJAX
            $.ajax({
                url: wizard_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $form.removeClass('loading');
                    
                    if (response && response.success) {
                        if (response.data.redirect) {
                            window.location.href = response.data.redirect;
                            return;
                        }
                        
                        // Update the URL to reflect the current step
                        updateUrlParameter('wizard_step', nextStep);
                        
                        // Update the form content
                        if (response.data.html) {
                            // Get the nonce value before replacing content
                            const nonce = $form.find('input[name="wizard_form_nonce"]').val() || wizard_ajax.nonce;
                            
                            // Create the new form HTML with proper structure
                            const newFormHtml = '<div class="wizard-container">' +
                                '<form class="wizard-form" method="post" action="' + wizard_ajax.ajax_url + '" data-step="' + nextStep + '">' +
                                '<input type="hidden" name="action" value="wizard_form_submit">' +
                                '<input type="hidden" name="current_step" value="' + nextStep + '">' +
                                '<input type="hidden" name="wizard_form_nonce" value="' + nonce + '">' +
                                '<div class="wizard-progress">' +
                                '<div class="progress-bar" role="progressbar" style="width: ' + response.data.progress + '%;" aria-valuenow="' + response.data.progress + '" aria-valuemin="0" aria-valuemax="100">' +
                                Math.round(response.data.progress) + '%' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-step">' +
                                response.data.html +
                                '</div>' +
                                '</form>' +
                                '</div>';
                            
                            // Replace the container content
                            $container.replaceWith(newFormHtml);
                            
                            // Restore visual state for form elements
                            restoreFormState();
                            
                            // Reinitialize the form with the new step
                            initWizardForm();
                            
                            // Scroll to top of form
                            $('html, body').animate({
                                scrollTop: $('.wizard-container').offset().top - 20
                            }, 300);
                        }
                    } else {
                        const errorMsg = (response && response.data && response.data.message) 
                            ? response.data.message 
                            : 'An error occurred. Please try again.';
                        showError(errorMsg);
                    }
                },
                error: function(xhr, status, error) {
                    $form.removeClass('loading');
                    console.error('AJAX Error:', status, error);
                    showError('An error occurred while processing your request. Please try again.');
                }
            });
            
            return false;
        });
    }
    
    // Initialize the form when the document is ready
    initWizardForm();
    
    // Restore visual state on initial load
    restoreFormState();
    
    // Handle browser back/forward buttons
    $(window).on('popstate', function() {
        // Extract step from clean URL
        const pathParts = window.location.pathname.split('/').filter(part => part);
        const wizardIndex = pathParts.indexOf('compare-life-insurance');
        
        let step = 'quotes';
        if (wizardIndex !== -1 && pathParts[wizardIndex + 1]) {
            step = pathParts[wizardIndex + 1];
        } else {
            // Fallback to query string
            const urlParams = new URLSearchParams(window.location.search);
            step = urlParams.get('wizard_step') || 'quotes';
        }
        
        loadStep(step);
    });
    
    // Handle back button click
    $(document).on('click', '.back-button', function(e) {
        e.preventDefault();
        const prevStep = $(this).data('prev-step');
        if (prevStep) {
            loadStep(prevStep);
        }
    });
});
