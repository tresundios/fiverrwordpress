<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following:
 *
 * - This file is called when the plugin is uninstalled by a user
 * - This file will NOT be executed automatically when updating the plugin
 * - This file will NOT be executed automatically when a user re-activates the plugin
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 */

// If uninstall is not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
