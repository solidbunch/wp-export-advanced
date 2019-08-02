<?php

/**
 * The plugin bootstrap file
 *
 * @category   Wordpress
 * @package    Wp Export Advanced Plugin
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      1.0.0
 *
 * @starter-kit-plugin
 * Plugin Name: Wp Export Advanced
 * Plugin URI: https://github.com/SolidBunch/wp-export-advanced
 * Description: Simple advanced WordPress Export plugin. Provides to export content by selecting a date range for a custom post types.
 * Version: 1.0.0
 * Author: SolidBunch
 * Author URI: https://solidbunch.com
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-export-advanced
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/SolidBunch/wp-export-advanced
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WP_EXPORT_ADVANCED_VERSION', '1.0.0' );
define( 'WP_EXPORT_ADVANCED_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_EXPORT_ADVANCED_BASE', plugin_basename( __FILE__ ) );
define( 'WP_EXPORT_ADVANCED_URL', plugin_dir_url( __FILE__ ) );


// helper functions for developers
require_once __DIR__ . '/app/dev.php';

/**
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 *
 * @return void
 */
spl_autoload_register( function ( $class ) {
	
	// project-specific namespace prefix
	$prefix = 'WpExportAdvanced\\';
	
	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/app/';
	
	// does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		// no, move to the next registered autoloader
		return;
	}
	
	// get the relative class name
	$relative_class = substr( $class, $len );
	
	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
	
	// if the file exists, require it
	if ( file_exists( $file ) ) {
		require $file;
	}
} );

// Global point of enter
if ( ! function_exists( 'Wp_Export_Advanced' ) ) {
	
	function Wp_Export_Advanced() {
		return \WpExportAdvanced\App::getInstance();
	}
	
}

// Run the theme
Wp_Export_Advanced()->run();