<?php

namespace WpExportAdvanced\View;

/**
 * View Class
 *
 * Anything to do with templates
 * and outputting client code
 *
 * @category   Wordpress
 * @package    Wp Export Advanced Plugin
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class View {
	
	/**
	 * Load view. Used on back-end side
	 *
	 * @param string $path
	 * @param array $data
	 * @param bool $return
	 * @param null $base
	 *
	 * @return false|string
	 */
	public function load( $path = '', array $data = array(), $return = false, $base = null ) {
		
		if ( $base === null ) {
			$base = get_stylesheet_directory();
		}
		
		if ( is_child_theme() ) {
			$full_path = $base . $path;
			if ( ! file_exists( $full_path ) ) {
				$base      = get_template_directory();
				$full_path = $base . $path . '.php';
			}
		} else {
			$full_path = $base . $path . '.php';
		}
		
		if ( $return ) {
			ob_start();
		}
		
		try {
			if ( file_exists( $full_path ) ) {
				
				require $full_path;
				
			} else {
				throw new \RuntimeException( 'The view path ' . $full_path . ' can not be found.' );
			}
		} catch ( \Exception $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
		
		
		if ( $return ) {
			return ob_get_clean();
		}
		
	}
	
}
