<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/public/partials
 */

?>

<div class="wizard-ghost-container">
	<div class="wizard-ghost-form-wrapper">
		<!-- Step 1: Occupation -->
		<div class="wizard-step wizard-step-1 active">
			<div class="wizard-header">
				<h1>Find out if you are eligible for Private Sick Pay™</h1>
			</div>

			<div class="wizard-content">
				<div class="wizard-question">
					<h2>What is your occupation?</h2>
					<form class="wizard-form" method="post" data-step="1">
						<div class="form-group">
							<input type="text" name="occupation" class="form-control" placeholder="Occupation" required>
						</div>
						<button type="submit" class="btn btn-primary btn-continue">
							Continue
							<span class="arrow">→</span>
						</button>
						<?php wp_nonce_field( 'wizard_ghost_step_1', 'wizard_ghost_nonce' ); ?>
					</form>
				</div>

				<div class="wizard-benefits">
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>100% of Eligible Claims Paid – 95% of All Claims Approved</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>Simple Process – Check Your Eligibility in Minutes</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>£810 Million Paid in 2023 – Trusted by Thousands</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>Non-Profit Providers - More Payouts, Less Hassle</strong></p>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 2: Loading -->
		<div class="wizard-step wizard-step-2">
			<div class="wizard-header">
				<h1>We're now checking your occupations eligibility...</h1>
			</div>

			<div class="wizard-content wizard-loading">
				<div class="loading-spinner">
					<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
						<circle cx="50" cy="50" r="45" fill="none" stroke="#e0e0e0" stroke-width="3"></circle>
						<circle cx="50" cy="50" r="45" fill="none" stroke="#0073aa" stroke-width="3" stroke-dasharray="141.3" stroke-dashoffset="0" class="spinner-circle"></circle>
						<path d="M 50 20 L 50 50 L 70 70" stroke="#0073aa" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round" class="checkmark"></path>
					</svg>
				</div>

				<div class="loading-contact">
					<div class="contact-item">
						<span class="contact-icon">☎</span>
						<p>0300 303 5758</p>
					</div>
					<div class="contact-item">
						<span class="contact-icon">✉</span>
						<p>info@privatesickpay.co.uk</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 3: Personal Details -->
		<div class="wizard-step wizard-step-3">
			<div class="wizard-header">
				<h1>Congratulations 🎉</h1>
				<p>Your Occupation is Eligible For Private Sick Pay.</p>
				<p class="subtitle">We now need to check if you personally qualify.</p>
			</div>

			<div class="wizard-content">
				<form class="wizard-form" method="post" data-step="3">
					<div class="form-row">
						<div class="form-group">
							<label for="first_name">First Name</label>
							<input type="text" id="first_name" name="first_name" class="form-control" placeholder="First name" required>
						</div>
						<div class="form-group">
							<label for="last_name">Last Name</label>
							<input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last name" required>
						</div>
					</div>

					<div class="form-group">
						<label for="phone">Phone number</label>
						<p class="help-text">So we can contact you about your cover and support you when making a claim</p>
						<div class="phone-input-wrapper">
							<div class="phone-country-select">
								<span class="phone-flag">🇬🇧</span>
								<select class="phone-country-code" name="phone_country_code" id="phone_country_code">
									<option value="+44" data-flag="🇬🇧">+44</option>
									<option value="+1" data-flag="🇺🇸">+1</option>
									<option value="+33" data-flag="🇫🇷">+33</option>
								</select>
							</div>
							<input type="tel" id="phone" name="phone" class="form-control" placeholder="7911 123456" data-format="uk" required>
						</div>
						<div class="error-message phone-error" style="display: none;">
							<span class="error-icon">✕</span>
							<span class="error-text">Please enter a valid phone number</span>
						</div>
					</div>

					<div class="form-group">
						<label for="email">Email address</label>
						<p class="help-text">This is where we'll send your your quote.</p>
						<div class="email-input-wrapper">
							<span class="email-icon">✉</span>
							<input type="email" id="email" name="email" class="form-control" placeholder="address@mail.com" required>
						</div>
					</div>

					<div class="form-group">
						<label for="dob">Date Of Birth</label>
						<div class="date-input-wrapper">
							<span class="date-icon">📅</span>
							<input type="date" id="dob" name="dob" class="form-control" placeholder="Click to select" required>
						</div>
					</div>

					<button type="submit" class="btn btn-primary btn-submit">
						Submit
						<span class="arrow">→</span>
					</button>

					<p class="disclaimer">By submitting this form and based on your requirements you agree that we can contact you by phone, email or electronic messaging in accordance with our Privacy Policy.</p>

					<?php wp_nonce_field( 'wizard_ghost_step_3', 'wizard_ghost_nonce' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>
