<?php
/**
 * Modify WP_Query objects
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

class Query_Handler {
	public function __construct( &$plugin ) {
		$this->plugin = $plugin;

        add_action( 'pre_get_posts', [ $this, 'handle_counselor_query' ] );
	}

    public function handle_counselor_query( $query ) {
    	if ( ! is_post_type_archive( 'counselors' ) ) {
    		return;
    	}

		$us_territories = array_map( function( $territory ) {
		   return trim( $territory );
		}, explode( '<br />', get_field( 'us_territories', 'option' ) ) );
		$international_territories = array_map( function( $territory ) {
		   return trim( $territory );
		}, explode( '<br />', get_field( 'international_territories', 'option' ) ) );

		$all_territories = array_merge( $us_territories, $international_territories );

    	$query->set( 'orderby', 'name' );
    	$query->set( 'order', 'ASC' );
    	$query->set( 'posts_per_page', 99 );
    	$query->set( 'post_status', 'publish' );

    	if ( ! isset( $_POST['territory_form'] ) ||
    			! wp_verify_nonce( $_POST['territory_form'], 'territory_picker' ) ) {
    		return;
    	}

    	if ( isset( $_POST['location-pulldown'] ) &&
    			in_array( $_POST['location-pulldown'], $all_territories ) ) {
    		$query->set( 'location-pulldown', $_POST['location-pulldown'] );
    	}

    	if ( isset( $_POST['international'] ) &&
    			in_array( $_POST['international'], $all_territories ) ) {
    		$query->set( 'international', $_POST['international'] );
    	}
    }
}
