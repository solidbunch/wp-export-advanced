<?php

namespace WpExportAdvanced;

use WpExportAdvanced\Helper\Utils;
use WpExportAdvanced\View\View;

/**
 * Application Singleton
 *
 * Primary application controller
 *
 * @category   Wordpress
 * @package    Wp Export Advanced Plugin
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class App {
	
	/** @var  $instance - self */
	private static $instance;
	
	/** @var array */
	public $config;
	
	/** @var \stdClass */
	public $Controller;

	/** @var view */
	public $View;

	private function __construct() {

	}
	
	/**
	 * @return App Singleton
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Run the theme
	 **/
	public function run() {
		
		// Load default config
		$this->config = require WP_EXPORT_ADVANCED_DIR . 'app/config.php';
		
		// Translation support
		load_theme_textdomain( 'wp-export-advanced', WP_EXPORT_ADVANCED_DIR . 'languages' );
		
		// Load core classes
		$this->_dispatch();
		
	}
	
	/**
	 * Load and instantiate all application
	 * classes necessary for this theme
	 **/
	private function _dispatch() {
		
		$this->Controller = new \stdClass();

		// load dependency classes first
		// View
		$this->View = new View();

		// Autoload controllers
		$this->_load_modules( 'Controller', '/' );
		
	}
	
	/**
	 * Autoload core modules in a specific directory
	 *
	 * @param string
	 * @param string
	 * @param bool
	 **/
	private function _load_modules( $layer, $dir = '/' ) {
		
		$directory = WP_EXPORT_ADVANCED_DIR . 'app/' . $layer . $dir;
		$handle    = opendir( $directory );
		
		while ( false !== ( $file = readdir( $handle ) ) ) {
			
			if ( is_file( $directory . $file ) ) {
				// Figure out class name from file name
				$class = str_replace( '.php', '', $file );
				
				// Avoid recursion
				if ( $class !== get_class( $this ) ) {
					$classPath            = "\\WpExportAdvanced\\{$layer}\\{$class}";
					$this->$layer->$class = new $classPath();
				}
				
			}
		}
		
	}
	
	private function __clone() {
	}
	
}
