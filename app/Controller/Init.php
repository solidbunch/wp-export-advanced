<?php

namespace WpExportAdvanced\Controller;

/**
 * Init controller
 *
 * Controller which setup theme
 *
 * @category   Wordpress
 * @package    Wp Export Advanced Plugin
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Init {
	
	/**
	 * Constructor
	 **/
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'register_submenu_page' ] );

		add_action( 'admin_init', [ $this, 'export_check' ] );

		add_filter( 'plugin_action_links', [ $this, 'add_export_link' ], 10, 2 );

	}

	function register_submenu_page() {
		add_submenu_page(
			'tools.php',
			__( 'WP Export Advanced', 'wp-export-advanced' ),
			__( 'Export Advanced', 'wp-export-advanced' ),
			'manage_options',
			'wp-export-advanced',
			[ $this, 'export_page_content' ]
		);
	}

	function export_page_content() {

		if ( !current_user_can('export') )
			wp_die(__('Sorry, you are not allowed to export the content of this site.'));


		wp_enqueue_script( 'wp-export-advanced', WP_EXPORT_ADVANCED_URL . 'dist/js/app.min.js', [ 'jquery' ] );
		wp_enqueue_style( 'wp-export-advanced', WP_EXPORT_ADVANCED_URL . 'dist/css/admin.css' );

		Wp_Export_Advanced()->View->load( '/template-parts/export-page' );
	}

	function export_check() {

		// If the 'download' URL parameter is set, a WXR export file is baked and returned.
		if ( isset( $_GET['download'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'wp-export-advanced' ) {

			if ( !current_user_can('export') )
				wp_die(__('Sorry, you are not allowed to export the content of this site.'));

			$args = $this->generate_args();

			Wp_Export_Advanced()->Model->Export->export_wp( $args );
			die();
		}
	}

	function generate_args() {
		$args = array();

		if ( ! isset( $_GET['content'] ) || 'all' == $_GET['content'] ) {
			$args['content'] = 'all';
		} elseif ( 'posts' == $_GET['content'] ) {
			$args['content'] = 'post';

			if ( $_GET['cat'] )
				$args['category'] = (int) $_GET['cat'];

			if ( $_GET['post_author'] )
				$args['author'] = (int) $_GET['post_author'];

			if ( $_GET['post_start_date'] || $_GET['post_end_date'] ) {
				$args['start_date'] = $_GET['post_start_date'];
				$args['end_date'] = $_GET['post_end_date'];
			}

			if ( $_GET['post_status'] )
				$args['status'] = $_GET['post_status'];
		} elseif ( 'pages' == $_GET['content'] ) {
			$args['content'] = 'page';

			if ( $_GET['page_author'] )
				$args['author'] = (int) $_GET['page_author'];

			if ( $_GET['page_start_date'] || $_GET['page_end_date'] ) {
				$args['start_date'] = $_GET['page_start_date'];
				$args['end_date'] = $_GET['page_end_date'];
			}

			if ( $_GET['page_status'] )
				$args['status'] = $_GET['page_status'];
		} elseif ( 'attachment' == $_GET['content'] ) {
			$args['content'] = 'attachment';

			if ( $_GET['attachment_start_date'] || $_GET['attachment_end_date'] ) {
				$args['start_date'] = $_GET['attachment_start_date'];
				$args['end_date'] = $_GET['attachment_end_date'];
			}
		} else {
			$args['content'] = $_GET['content'];
			if ( $args['content'] . '_start_date' || $args['content'] . '_end_date' ) {
				$args['start_date'] = $_GET[ $args['content'] . '_start_date' ];
				$args['end_date']   = $_GET[ $args['content'] . '_end_date' ];
			}
		}

		/**
		 * Filters the export args.
		 *
		 * @since 3.5.0
		 *
		 * @param array $args The arguments to send to the exporter.
		 */
		$args = apply_filters( 'export_args', $args );

		return $args;
	}

	/**
	 * Add a link to the export on the Plugins screen.
	 */
	public function add_export_link( $links ) {

		$url = admin_url( 'tools.php?page=wp-export-advanced' );

		$links = (array) $links;
		$links[] = sprintf( '<a href="%s">%s</a>', $url, __( 'Export', 'wp-export-advanced' ) );

		return $links;
	}

}
