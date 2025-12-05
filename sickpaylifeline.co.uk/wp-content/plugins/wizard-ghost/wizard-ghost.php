<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Wizard_Ghost
 *
 * @wordpress-plugin
 * Plugin Name:       Wizard Ghost
 * Plugin URI:        http://example.com/wizard-ghost-uri/
 * Description:       A powerful wizard plugin for WordPress.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wizard-ghost
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WIZARD_GHOST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wizard-ghost-activator.php
 */
function activate_wizard_ghost() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wizard-ghost-activator.php';
	Wizard_Ghost_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wizard-ghost-deactivator.php
 */
function deactivate_wizard_ghost() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wizard-ghost-deactivator.php';
	Wizard_Ghost_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wizard_ghost' );
register_deactivation_hook( __FILE__, 'deactivate_wizard_ghost' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wizard-ghost.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wizard_ghost() {

	$plugin = new Wizard_Ghost();
	$plugin->run();

}
run_wizard_ghost();
