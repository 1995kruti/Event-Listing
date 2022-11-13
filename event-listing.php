<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/1995kruti
 * @since             1.0.0
 * @package           Event_Listing
 *
 * @wordpress-plugin
 * Plugin Name:       Event Listing
 * Plugin URI:        https://https://wordpress.org/
 * Description:       Event Listing with easy shortcode([event_listing]) integration
 * Version:           1.0.0
 * Author:            Kruti Modi
 * Author URI:        https://https://github.com/1995kruti
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       event-listing
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
define( 'EVENT_LISTING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-event-listing-activator.php
 */
function activate_event_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-listing-activator.php';
	Event_Listing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-event-listing-deactivator.php
 */
function deactivate_event_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-listing-deactivator.php';
	Event_Listing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_event_listing' );
register_deactivation_hook( __FILE__, 'deactivate_event_listing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-event-listing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_event_listing() {

	$plugin = new Event_Listing();
	$plugin->run();

}
run_event_listing();
