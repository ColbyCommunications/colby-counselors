<?php
/**
 * Plugin control code tied to action hooks.
 *
 * @package Colby/Counselors
 */

add_action( 'init', function() {
	global $FIELD_GROUP;
	global $INTL_TERRITORIES;
	global $US_TERRITORIES;

	$labels = array(
		'name' => 'Counselors',
		'singular_name' => 'Counselor',
		);

	$args = array(
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'has_archive' => true,
		'show_in_menu' => true,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'counselors', 'with_front' => true ),
		'query_var' => true,
		'supports' => array( 'title', 'editor', 'thumbnail' ),
		);
	register_post_type( 'counselors', $args );

	$territories = [];
	foreach ( [ $US_TERRITORIES, $INTL_TERRITORIES ] as $territory_group ) {
		foreach ( $territory_group as $territory ) {
			$territories[ $territory ] = $territory;
		}
	}

	register_field_group( $FIELD_GROUP );
} );


add_action( 'pre_get_posts', function( $query ) {
	if ( ! is_post_type_archive( 'counselors' ) ) {
		return;
	}

	$query->set( 'orderby', 'name' );
	$query->set( 'order', 'ASC' );
	$query->set( 'posts_per_page', 99 );
	$query->set( 'post_status', 'publish' );

	if ( ! isset( $_POST['territory_form'] ) ||
			! wp_verify_nonce( $_POST['territory_form'], 'territory_picker' ) ) {
		return;
	}

	if ( isset( $_POST['location-pulldown'] ) &&
			in_array( $_POST['location-pulldown'], $US_TERRITORIES ) ) {
		$query->set( 'location-pulldown', $_POST['location-pulldown'] );
	}

	if ( isset( $_POST['international'] ) &&
			in_array( $_POST['international'], $INTL_TERRITORIES ) ) {
		$query->set( 'international', $_POST['international'] );
	}
} );


add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'admissions-counselors', plugins_url( 'js/scripts.js', __FILE__ ), [], false, true );
}, 99);
