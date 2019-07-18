<?php

use WpExportAdvanced\Helper\Utils;


?>


<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<p><?php _e('When you click the button below WordPress will create an XML file for you to save to your computer.'); ?></p>
	<p><?php _e('This format, which we call WordPress eXtended RSS or WXR, will contain your posts, pages, comments, custom fields, categories, and tags.'); ?></p>
	<p><?php _e('Once you&#8217;ve saved the download file, you can use the Import function in another WordPress installation to import the content from this site.'); ?></p>

	<h2><?php _e( 'Choose what to export' ); ?></h2>
	<form method="get" id="export-filters">
		<input type="hidden" name="page" value="wp-export-advanced">
		<fieldset>
			<legend class="screen-reader-text"><?php _e( 'Content to export' ); ?></legend>
			<input type="hidden" name="download" value="true" />
			<p><label><input type="radio" name="content" value="all" checked="checked" aria-describedby="all-content-desc" /> <?php _e( 'All content' ); ?></label></p>
			<p class="description" id="all-content-desc"><?php _e( 'This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts.' ); ?></p>

			<p><label><input type="radio" name="content" value="post" /> <?php _e( 'Posts' ); ?></label></p>
			<ul id="post-filters" class="export-filters">
				<li>
					<label><span class="label-responsive"><?php _e( 'Categories:' ); ?></span>
						<?php wp_dropdown_categories( array( 'show_option_all' => __('All') ) ); ?>
					</label>
				</li>
				<li>
					<label><span class="label-responsive"><?php _e( 'Authors:' ); ?></span>
						<?php
						wp_dropdown_users( array(
							'include' => Utils::export_autor_options(),
							'name' => 'post_author',
							'multi' => true,
							'show_option_all' => __( 'All' ),
							'show' => 'display_name_with_login',
						) );
						 ?>
					</label>
				</li>
				<li>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Date range:' ); ?></legend>
						<label for="post-start-date" class="label-responsive"><?php _e( 'Start date:' ); ?></label>
						<select name="post_start_date" id="post-start-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options(); ?>
						</select>
						<label for="post-end-date" class="label-responsive"><?php _e( 'End date:' ); ?></label>
						<select name="post_end_date" id="post-end-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options(); ?>
						</select>
					</fieldset>
				</li>
				<li>
					<label for="post-status" class="label-responsive"><?php _e( 'Status:' ); ?></label>
					<select name="post_status" id="post-status">
						<option value="0"><?php _e( 'All' ); ?></option>
						<?php $post_stati = get_post_stati( array( 'internal' => false ), 'objects' );
						foreach ( $post_stati as $status ) : ?>
							<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			</ul>

			<p><label><input type="radio" name="content" value="page" /> <?php _e( 'Pages' ); ?></label></p>
			<ul id="page-filters" class="export-filters">
				<li>
					<label><span class="label-responsive"><?php _e( 'Authors:' ); ?></span>
						<?php
						wp_dropdown_users( array(
							'include' => Utils::export_autor_options('page'),
							'name' => 'page_author',
							'multi' => true,
							'show_option_all' => __( 'All' ),
							'show' => 'display_name_with_login',
						) ); ?>
					</label>
				</li>
				<li>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Date range:' ); ?></legend>
						<label for="page-start-date" class="label-responsive"><?php _e( 'Start date:' ); ?></label>
						<select name="page_start_date" id="page-start-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options( 'page' ); ?>
						</select>
						<label for="page-end-date" class="label-responsive"><?php _e( 'End date:' ); ?></label>
						<select name="page_end_date" id="page-end-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options( 'page' ); ?>
						</select>
					</fieldset>
				</li>
				<li>
					<label for="page-status" class="label-responsive"><?php _e( 'Status:' ); ?></label>
					<select name="page_status" id="page-status">
						<option value="0"><?php _e( 'All' ); ?></option>
						<?php foreach ( $post_stati as $status ) : ?>
							<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
						<?php endforeach; ?>
					</select>
				</li>
			</ul>

			<?php foreach ( get_post_types( array( '_builtin' => false, 'can_export' => true ), 'objects' ) as $post_type ) : ?>
				<p><label><input type="radio" name="content" value="<?php echo esc_attr( $post_type->name ); ?>" /> <?php echo esc_html( $post_type->label ); ?></label></p>
				<ul id="<?php echo esc_html( $post_type->name ); ?>-filters" class="export-filters">
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php _e( 'Date range:' ); ?></legend>
							<label for="<?php echo esc_attr( $post_type->name ); ?>-start-date" class="label-responsive"><?php _e( 'Start date:' ); ?></label>
							<select name="<?php echo esc_html( $post_type->name ); ?>_start_date" id="<?php echo esc_attr( $post_type->name ); ?>-start-date">
								<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
								<?php Utils::export_date_options($post_type->name); ?>
							</select>
							<label for="<?php echo esc_attr( $post_type->name ); ?>-end-date" class="label-responsive"><?php _e( 'End date:' ); ?></label>
							<select name="<?php echo esc_html( $post_type->name ); ?>_end_date" id="<?php echo esc_attr( $post_type->name ); ?>-end-date">
								<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
								<?php Utils::export_date_options($post_type->name); ?>
							</select>
						</fieldset>
					</li>
				</ul>
			<?php endforeach; ?>

			<p><label><input type="radio" name="content" value="attachment" /> <?php _e( 'Media' ); ?></label></p>
			<ul id="attachment-filters" class="export-filters">
				<li>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Date range:' ); ?></legend>
						<label for="attachment-start-date" class="label-responsive"><?php _e( 'Start date:' ); ?></label>
						<select name="attachment_start_date" id="attachment-start-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options( 'attachment' ); ?>
						</select>
						<label for="attachment-end-date" class="label-responsive"><?php _e( 'End date:' ); ?></label>
						<select name="attachment_end_date" id="attachment-end-date">
							<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
							<?php Utils::export_date_options( 'attachment' ); ?>
						</select>
					</fieldset>
				</li>
			</ul>

		</fieldset>
		<?php
		/**
		 * Fires at the end of the export filters form.
		 *
		 * @since 3.5.0
		 */
		do_action( 'export_filters' );
		?>

		<?php submit_button( __('Download Export File') ); ?>
	</form>
</div>