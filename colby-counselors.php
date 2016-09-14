<?php
/**
 * Plugin Name: Colby Counselors
 * Description: Plugin for displaying information about Colby College Admissions counselors
 * Author: John Watkins, Colby Communications Department
 *
 * @package colby-counselors
 */

global $colby_counselors;

 $plugin_data = [ // For easy use throughout the plugin.
 	'Plugin Name' => 'Colby Counselors',
 	'Description' => 'Plugin for displaying information about Colby College Admissions counselors.',
 	'Version' => '0.01',
 	'Author' => 'John Watkins',
 	'Text Domain' => 'colby-counselors',
 	'Namespace' => 'Colby_Counselors',
 	'Email' => 'jwatkins@colby.edu',
 	];

 if ( ! function_exists( 'register_wp_autoload' ) ) {
 	 require_once( 'vendor/autoload.php' );
 }

 register_wp_autoload( 'Colby_Counselors\\', __DIR__ . '/lib' );

 $colby_counselors = new Colby_Counselors\Colby_Counselors( __FILE__, $plugin_data );

 // Admin (admin_init doesn't work with ACF options pages).
 add_action( 'init', function() use ( $colby_counselors ) {
 	$plugin_classes = [
 		'Colby_Counselors\\Query_Handler',
 		'Colby_Counselors\\Template_Router',
 	];

 	foreach ( $plugin_classes as $class ) {
 		new $class( $colby_counselors );
 	}
 }, 1 );

 /**
  * For development -- pretty-print data in the browser; optionally var_dump; optionally wp_die.
  *
  * @param  mixed   $data Any variable.
  * @param  integer $die  Zero for false, one for true.
  * @param  integer $dump Zero for false, one for true.
  */
 if ( ! function_exists( 'pp' ) ) :
 function pp( $data, $die = 0, $dump = 0 ) {
 	echo '<pre>';
 	if ( 1 === $dump ) {
 		var_dump( $data );
 	} else {
 		print_r( $data );
 	}

 	echo '</pre>';
 	if ( 1 === $die ) {
 		wp_die();
 	}

 }
 endif;
