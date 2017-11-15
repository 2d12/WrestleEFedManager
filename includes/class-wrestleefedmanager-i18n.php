<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.2d12.com
 * @since      1.0.0
 *
 * @package    Wrestleefedmanager
 * @subpackage Wrestleefedmanager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wrestleefedmanager
 * @subpackage Wrestleefedmanager/includes
 * @author     E. Steev Ramsdell <steev@2d12.com>
 */
class Wrestleefedmanager_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wrestleefedmanager',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
