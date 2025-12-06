(function ($) {
	'use strict';

	/**
	 * Wizard Ghost Public JavaScript
	 * Handles wizard form navigation and data collection
	 */

	$(function () {
		// Only initialize if wizard-ghost container exists
		if ($('.wizard-ghost-container').length === 0) {
			return;
		}

		var WizardGhost = {
			currentStep: 1,
			formData: {},

			init: function () {
				this.bindEvents();
			},

			bindEvents: function () {
				var self = this;

				// Handle form submissions - only for wizard-ghost forms
				$('.wizard-ghost-container .wizard-form').on('submit', function (e) {
					e.preventDefault();
					var step = parseInt($(this).data('step'), 10);
					console.log('Wizard Ghost - Form submitted for step:', step);
					self.handleStepSubmission(step, $(this));
				});

				// Handle phone number input formatting and validation
				$('.wizard-ghost-container #phone').on('input', function () {
					self.formatPhoneNumber($(this));
				});

				// Handle phone number blur for validation
				$('.wizard-ghost-container #phone').on('blur', function () {
					self.validatePhoneNumber($(this));
				});

				// Handle country code change
				$('.wizard-ghost-container #phone_country_code').on('change', function () {
					var flag = $(this).find('option:selected').data('flag');
					$('.wizard-ghost-container .phone-flag').text(flag);
				});
			},

			/**
			 * Format phone number as user types
			 */
			formatPhoneNumber: function ($input) {
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
			validatePhoneNumber: function ($input) {
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

			handleStepSubmission: function (step, $form) {
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
					setTimeout(function () {
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
					var helpWith = $form.find('input[name="help_with"]:checked').val();
					console.log('Personal details collected:', { firstName, lastName, phone, email, dob, helpWith });

					// Validate required fields
					if (!firstName || !lastName || !phone || !email || !dob || !helpWith) {
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
					this.formData.help_with = helpWith;

					// Send data to server
					this.submitFormData($form);
				}
			},

			submitFormData: function ($form) {
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
					dob: self.formData.dob,
					help_with: self.formData.help_with
				};

				console.log('AJAX data being sent:', ajaxData);

				$.ajax({
					url: wizardGhostData.ajaxUrl,
					type: 'POST',
					data: ajaxData,
					success: function (response) {
						console.log('AJAX success response:', response);
						// Log email sent status
						if (typeof response.data.email_sent !== 'undefined') {
							console.log('Email sent status:', response.data.email_sent ? '✅ Email was sent successfully' : '❌ Failed to send email');
							console.log('Debug - Recipient:', response.data.debug_email_recipient);
							console.log('Debug - Configured:', response.data.debug_email_configured);
							console.log('Debug - wp_mail result:', response.data.debug_wp_mail_result);
						} else {
							console.log('Email sent status: Not provided in response');
						}

						if (response.success) {
							// Show thank you message
							$('.wizard-step').removeClass('active');
							$('.wizard-ghost-container').html(`
								<div class="wizard-thank-you">
									<div class="thank-you-icon">✓</div>
									<h2>Thank You!</h2>
									<p>${response.data.message}</p>
									${response.data.email_sent === false ? '<p class="email-warning">Note: We had trouble sending the confirmation email. Your information has been saved.</p>' : ''}
									<button class="btn btn-primary start-over-btn">Start Over</button>
								</div>
							`).addClass('thank-you-active');

							// Add click handler for start over button
							setTimeout(function () {
								$('.start-over-btn').on('click', function () {
									location.reload();
								});
							}, 100);
						} else {
							alert('Error: ' + (response.data.message || 'An unknown error occurred'));
						}
					},
					error: function (xhr, status, error) {
						console.log('AJAX error:', status, error);
						console.log('AJAX response:', xhr.responseText);
						alert('An error occurred while submitting the form.');
					}
				});
			},

			showStep: function (step) {
				console.log('Showing step:', step);
				// Hide all steps
				$('.wizard-step').removeClass('active');

				// Show the requested step
				var $stepElement = $('.wizard-step-' + step);
				console.log('Step element found:', $stepElement.length);
				$stepElement.addClass('active');

				this.currentStep = step;

				// Trigger confetti for step 2 (Congratulations)
				if (step === 2) {
					this.triggerConfetti();
				}

				// Scroll to top
				var containerOffset = $('.wizard-ghost-container').offset();
				if (containerOffset) {
					$('html, body').animate({
						scrollTop: containerOffset.top - 100
					}, 300);
				}
			},

			triggerConfetti: function () {
				console.log('Triggering confetti...');
				var colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];

				for (var i = 0; i < 100; i++) {
					var $confetti = $('<div class="confetti"></div>');
					var color = colors[Math.floor(Math.random() * colors.length)];
					var left = Math.random() * 100 + 'vw';
					var animationDuration = (Math.random() * 3 + 2) + 's';
					var animationDelay = (Math.random() * 2) + 's';

					$confetti.css({
						'background-color': color,
						'left': left,
						'animation-duration': animationDuration,
						'animation-delay': animationDelay
					});

					$('body').append($confetti);

					// Remove confetti after animation
					setTimeout(function ($c) {
						$c.remove();
					}, 5000, $confetti);
				}
				console.log('Confetti triggered!');
			},

			resetWizard: function () {
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

})(jQuery);
