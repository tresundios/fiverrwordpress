<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/admin/partials
 */

// Check user capabilities
if (!current_user_can('manage_options')) {
	return;
}

?>
<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

	<div id="wizard-ghost-admin" class="wizard-ghost-admin-container">
		<div class="wizard-ghost-admin-header">
			<p><?php esc_html_e('Welcome to Wizard Ghost! Configure your wizard forms below.', 'wizard-ghost'); ?></p>
		</div>

		<div class="wizard-ghost-admin-content">
			<div class="wizard-ghost-admin-section">
				<h2><?php esc_html_e('Settings', 'wizard-ghost'); ?></h2>
				<form method="post" action="">
					<?php wp_nonce_field('wizard_ghost_admin_action', 'wizard_ghost_admin_nonce'); ?>

					<div class="form-group">
						<label for="wizard_ghost_email">
							<strong><?php esc_html_e('Email Address for Submissions', 'wizard-ghost'); ?></strong>
						</label>
						<p class="description">
							<?php esc_html_e('Form submissions will be sent to this email address.', 'wizard-ghost'); ?>
						</p>
						<input type="email" id="wizard_ghost_email" name="wizard_ghost_email"
							value="<?php echo esc_attr(get_option('wizard_ghost_email')); ?>" class="regular-text"
							required>
					</div>

					<div class="form-group">
						<label for="wizard_ghost_cc_email">
							<strong><?php esc_html_e('CC Email Address', 'wizard-ghost'); ?></strong>
						</label>
						<p class="description">
							<?php esc_html_e('Optional: Add an email address to CC on submissions.', 'wizard-ghost'); ?>
						</p>
						<input type="email" id="wizard_ghost_cc_email" name="wizard_ghost_cc_email"
							value="<?php echo esc_attr(get_option('wizard_ghost_cc_email')); ?>"
							class="regular-text">
					</div>

					<div class="form-group">
						<label for="wizard_ghost_bcc_email">
							<strong><?php esc_html_e('BCC Email Address', 'wizard-ghost'); ?></strong>
						</label>
						<p class="description">
							<?php esc_html_e('Optional: Add an email address to BCC on submissions.', 'wizard-ghost'); ?>
						</p>
						<input type="email" id="wizard_ghost_bcc_email" name="wizard_ghost_bcc_email"
							value="<?php echo esc_attr(get_option('wizard_ghost_bcc_email')); ?>"
							class="regular-text">
					</div>

					<div class="form-group">
						<label for="wizard_ghost_redirect_url">
							<strong><?php esc_html_e('Redirect URL After Submission', 'wizard-ghost'); ?></strong>
						</label>
						<p class="description">
							<?php esc_html_e('Users will be redirected to this URL after successful form submission.', 'wizard-ghost'); ?>
						</p>
						<input type="url" id="wizard_ghost_redirect_url" name="wizard_ghost_redirect_url"
							value="<?php echo esc_attr(get_option('wizard_ghost_redirect_url')); ?>"
							class="regular-text" placeholder="https://example.com/thank-you/">
					</div>

					<p class="submit">
						<button type="submit"
							class="button button-primary"><?php esc_html_e('Save Settings', 'wizard-ghost'); ?></button>
						<button type="submit" name="wizard_ghost_test_email" class="button button-secondary"
							style="margin-left: 10px;"><?php esc_html_e('Send Test Email', 'wizard-ghost'); ?></button>
					</p>
				</form>
			</div>

			<div class="wizard-ghost-admin-section">
				<h2><?php esc_html_e('Wizard Form Submissions', 'wizard-ghost'); ?></h2>
				<?php
				global $wpdb;
				$table_name = $wpdb->prefix . 'wizard_ghost_submissions';

				// Check if table exists
				if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
					$submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submitted_at DESC");

					if (!empty($submissions)) {
						?>
						<table class="widefat striped">
							<thead>
								<tr>
									<th><?php esc_html_e('ID', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Occupation', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('First Name', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Last Name', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Phone', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Email', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Date of Birth', 'wizard-ghost'); ?></th>
									<th><?php esc_html_e('Submitted', 'wizard-ghost'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($submissions as $submission): ?>
									<tr>
										<td><?php echo esc_html($submission->id); ?></td>
										<td><?php echo esc_html($submission->occupation); ?></td>
										<td><?php echo esc_html($submission->first_name); ?></td>
										<td><?php echo esc_html($submission->last_name); ?></td>
										<td><?php echo esc_html($submission->phone); ?></td>
										<td><?php echo esc_html($submission->email); ?></td>
										<td><?php echo esc_html($submission->dob); ?></td>
										<td><?php echo esc_html($submission->submitted_at); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php
					} else {
						?>
						<p><?php esc_html_e('No submissions yet.', 'wizard-ghost'); ?></p>
						<?php
					}
				} else {
					?>
					<p><?php esc_html_e('No submissions yet.', 'wizard-ghost'); ?></p>
					<?php
				}
				?>
			</div>

			<div class="wizard-ghost-admin-section">
				<h2><?php esc_html_e('Plugin Information', 'wizard-ghost'); ?></h2>
				<table class="widefat">
					<tbody>
						<tr>
							<td><strong><?php esc_html_e('Plugin Name:', 'wizard-ghost'); ?></strong></td>
							<td><?php esc_html_e('Wizard Ghost', 'wizard-ghost'); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e('Version:', 'wizard-ghost'); ?></strong></td>
							<td><?php echo esc_html(WIZARD_GHOST_VERSION); ?></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e('Author:', 'wizard-ghost'); ?></strong></td>
							<td><?php esc_html_e('Your Name or Your Company', 'wizard-ghost'); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>