<?php

namespace WpExportAdvanced\Helper;

/**
 * Utilities
 *
 * Helper functions
 *
 * @category   Wordpress
 * @package    Starter Kit Backend
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Utils {

	
	/**
	 * Autoload PHP files in directory
	 *
	 * @param $dir
	 * @param int $max_scan_depth
	 * @param string $load_file
	 * @param int $current_depth
	 */
	public static function autoload_dir( $dir, $max_scan_depth = 0, $load_file = '', $current_depth = 0 ) {
		if ( $current_depth > $max_scan_depth ) {
			return;
		}
		
		// require all php files
		$scan = glob( trailingslashit( $dir ) . '*' );
		
		foreach ( $scan as $path ) {
			
			if ( preg_match( '/\.php$/', $path ) ) {
				
				if ( is_string( $load_file ) && $load_file !== '' ) {
					
					// load specific file
					
					$dir  = dirname( $path );
					$file = $dir . '/' . $load_file;
					
					if ( is_file( $file ) ) {
						require_once $file;
					}
					
				} else {
					
					// load all PHP files in folder
					require_once $path;
					
				}
				
			} elseif ( is_dir( $path ) ) {
				
				self::autoload_dir( $path, $max_scan_depth, $load_file, $current_depth + 1 );
				
			}
		}
	}

	/**
	 * Sanitize text params from array
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public static function sanitize_array_text_params( $params ) {
		
		$sanitized_params = array();
		
		foreach ( $params as $k => $v ) {
			
			if ( is_string( $v ) || is_numeric( $v ) ) {
				$sanitized_params[ $k ] = \sanitize_text_field( $v );
			}
			
		}
		
		return $sanitized_params;
		
	}

	
}
