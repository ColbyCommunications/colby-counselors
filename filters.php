<?php
/**
 * Plugin control code tied to filters.
 *
 * @package Colby/Counselors
 */

add_filter( 'template_include', function( $template ) {
	if ( is_post_type_archive( 'counselors' ) ) {
		return plugin_dir_path( __FILE__ ) . 'templates/archive-counselors.php';
	}
	return $template;
} );
