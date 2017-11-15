<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.2d12.com
 * @since             1.0.0
 * @package           Wrestleefedmanager
 *
 * @wordpress-plugin
 * Plugin Name:       WrestleEFed Manager
 * Plugin URI:        http://www.2d12.com
 * Description:       Establishes a usable history for a wrestling e-fed, including the ability to store multiple federations,
 *                    workers, titles (with histories), events, and match history.
 * Version:           1.0.0
 * Author:            E. Steev Ramsdell
 * Author URI:        http://www.2d12.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wrestleefedmanager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wrestleefedmanager-activator.php
 */
function activate_wrestleefedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager-activator.php';
	Wrestleefedmanager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wrestleefedmanager-deactivator.php
 */
function deactivate_wrestleefedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager-deactivator.php';
	Wrestleefedmanager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wrestleefedmanager' );
register_deactivation_hook( __FILE__, 'deactivate_wrestleefedmanager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wrestleefedmanager() {

	$plugin = new Wrestleefedmanager();
	$plugin->run();

}

run_wrestleefedmanager();
