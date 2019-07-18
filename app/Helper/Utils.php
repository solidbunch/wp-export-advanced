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

	/**
	 * Create the date options fields for exporting a given post type.
	 *
	 * @global wpdb      $wpdb      WordPress database abstraction object.
	 * @global WP_Locale $wp_locale Date and Time Locale object.
	 *
	 * @since 3.1.0
	 *
	 * @param string $post_type The post type. Default 'post'.
	 */
	public static function export_date_options( $post_type = 'post' ) {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results( $wpdb->prepare( "
		SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
		FROM $wpdb->posts
		WHERE post_type = %s AND post_status != 'auto-draft'
		ORDER BY post_date DESC
	", $post_type ) );

		$month_count = count( $months );
		if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
			return;

		foreach ( $months as $date ) {
			if ( 0 == $date->year )
				continue;

			$month = zeroise( $date->month, 2 );
			echo '<option value="' . $date->year . '-' . $month . '">' . $wp_locale->get_month( $month ) . ' ' . $date->year . '</option>';
		}
	}

	public static function export_autor_options( $post_type = 'post' ) {
		global $wpdb;
		$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = '$post_type' " );

		return $authors;
	}

	
}
