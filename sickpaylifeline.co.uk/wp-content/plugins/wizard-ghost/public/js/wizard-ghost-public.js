(function( $ ) {
	'use strict';

	/**
	 * Wizard Ghost Public JavaScript
	 * Handles wizard form navigation and data collection
	 */

	$(function() {
		// Only initialize if wizard-ghost container exists
		if ($('.wizard-ghost-container').length === 0) {
			return;
		}

		var WizardGhost = {
			currentStep: 1,
			formData: {},

			init: function() {
				this.bindEvents();
			},

			bindEvents: function() {
				var self = this;

				// Handle form submissions - only for wizard-ghost forms
				$('.wizard-ghost-container .wizard-form').on('submit', function(e) {
					e.preventDefault();
					var step = parseInt($(this).data('step'), 10);
					console.log('Wizard Ghost - Form submitted for step:', step);
					self.handleStepSubmission(step, $(this));
				});

				// Handle phone number input formatting and validation
				$('.wizard-ghost-container #phone').on('input', function() {
					self.formatPhoneNumber($(this));
				});

				// Handle phone number blur for validation
				$('.wizard-ghost-container #phone').on('blur', function() {
					self.validatePhoneNumber($(this));
				});

				// Handle country code change
				$('.wizard-ghost-container #phone_country_code').on('change', function() {
					var flag = $(this).find('option:selected').data('flag');
					$('.wizard-ghost-container .phone-flag').text(flag);
				});
			},

			/**
			 * Format phone number as user types
			 */
			formatPhoneNumber: function($input) {
				var value = $input.val().replace(/\D/g, '');
				var formatted = '';

				if (value.length > 0) {
					// UK format: +44 XXXX XXX XXXX
					if (value.length <= 4) {
						formatted = value;
					} else if (value.length <= 7) {
						formatted = value.slice(0, 4) + ' ' + value.slice(4);
					} else {
						formatted = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7, 11);
					}
				}

				$input.val(formatted);
			},

			/**
			 * Validate UK phone number
			 */
			validatePhoneNumber: function($input) {
				var phone = $input.val().replace(/\D/g, '');
				var $errorMsg = $input.closest('.form-group').find('.phone-error');

				// UK phone numbers should be 10-11 digits
				if (phone.length < 10 || phone.length > 11) {
					$errorMsg.show();
					$input.addClass('error');
					return false;
				}

				// Check if it's a valid UK number (starts with 0 or 7 for mobile)
				var ukPhoneRegex = /^(0|7)\d{9,10}$/;
				if (!ukPhoneRegex.test(phone)) {
					$errorMsg.show();
					$input.addClass('error');
					return false;
				}

				$errorMsg.hide();
				$input.removeClass('error');
				return true;
			},

			handleStepSubmission: function(step, $form) {
				var self = this;
				console.log('Handling step submission for step:', step);

				if (step === 1) {
					// Collect occupation data
					var occupation = $form.find('input[name="occupation"]').val();
					console.log('Occupation entered:', occupation);
					
					if (!occupation) {
						alert('Please enter your occupation');
						return;
					}

					this.formData.occupation = occupation;
					console.log('Moving to step 2 (loading)...');

					// Move to loading step
					this.showStep(2);

					// Simulate loading for 3 seconds
					setTimeout(function() {
						console.log('Moving to step 3 (personal details)...');
						self.showStep(3);
					}, 3000);
				} else if (step === 3) {
					console.log('Step 3 form submitted');
					// Collect personal details
					var firstName = $form.find('input[name="first_name"]').val();
					var lastName = $form.find('input[name="last_name"]').val();
					var phone = $form.find('input[name="phone"]').val();
					var phoneCountryCode = $form.find('select[name="phone_country_code"]').val();
					var email = $form.find('input[name="email"]').val();
					var dob = $form.find('input[name="dob"]').val();
					console.log('Personal details collected:', {firstName, lastName, phone, email, dob});

					// Validate required fields
					if (!firstName || !lastName || !phone || !email || !dob) {
						alert('Please fill in all required fields');
						return;
					}

					// Validate phone number using the validation method
					var $phoneInput = $form.find('input[name="phone"]');
					if (!self.validatePhoneNumber($phoneInput)) {
						return;
					}

					// Collect all data
					this.formData.first_name = firstName;
					this.formData.last_name = lastName;
					this.formData.phone = phoneCountryCode + ' ' + phone;
					this.formData.email = email;
					this.formData.dob = dob;

					// Send data to server
					this.submitFormData($form);
				}
			},

			submitFormData: function($form) {
				var self = this;
				var nonce = $form.find('input[name="wizard_ghost_nonce"]').val();

				console.log('Submitting form with data:', self.formData);
				console.log('Nonce:', nonce);
				console.log('AJAX URL:', wizardGhostData.ajaxUrl);

				var ajaxData = {
					action: 'wizard_ghost_submit_form',
					nonce: nonce,
					occupation: self.formData.occupation,
					first_name: self.formData.first_name,
					last_name: self.formData.last_name,
					phone: self.formData.phone,
					email: self.formData.email,
					dob: self.formData.dob
				};

				console.log('AJAX data being sent:', ajaxData);

				$.ajax({
					url: wizardGhostData.ajaxUrl,
					type: 'POST',
					data: ajaxData,
					success: function(response) {
						console.log('AJAX success response:', response);
						if (response.success) {
							// Show success message
							// alert('Thank you! Your information has been submitted successfully.');
							
							// Redirect to thank you page if URL is provided
							if (response.data.redirect) {
								window.location.href = response.data.redirect;
							} else {
								// Reset form if no redirect
								self.resetWizard();
							}
						} else {
							alert('Error: ' + response.data.message);
						}
					},
					error: function(xhr, status, error) {
						console.log('AJAX error:', status, error);
						console.log('AJAX response:', xhr.responseText);
						alert('An error occurred while submitting the form.');
					}
				});
			},

			showStep: function(step) {
				console.log('Showing step:', step);
				// Hide all steps
				$('.wizard-step').removeClass('active');

				// Show the requested step
				var $stepElement = $('.wizard-step-' + step);
				console.log('Step element found:', $stepElement.length);
				$stepElement.addClass('active');

				this.currentStep = step;

				// Scroll to top
				var containerOffset = $('.wizard-ghost-container').offset();
				if (containerOffset) {
					$('html, body').animate({
						scrollTop: containerOffset.top - 100
					}, 300);
				}
			},

			resetWizard: function() {
				this.currentStep = 1;
				this.formData = {};
				$('.wizard-ghost-container .wizard-form').trigger('reset');
				this.showStep(1);
			}
		};

		// Initialize wizard
		console.log('Initializing Wizard Ghost...');
		WizardGhost.init();
		console.log('Wizard Ghost initialized successfully');
	});

})( jQuery );
