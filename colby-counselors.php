<?php
/**
 * Plugin Name: Colby Counselors
 * Description: Plugin for displaying information about Colby College Admissions counselors
 * Author: John Watkins, Colby Communications Department
 * Version 2.0
 *
 * @package colby-counselors
 */

if ( ! function_exists( 'register_wp_autoload' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

JohnWatkins0\WPAutoload\register_wp_autoload( 'Colby_Counselors\\', __DIR__ . '/lib' );

Colby_Counselors\Counselors_Post_Type::get_instance();
// Colby_Counselors\Counselor_Events_Post_Type::get_instance();
Colby_Counselors\Territories_Taxonomy::get_instance();

/**
 * Returns the trailing-slashed path to the plugin home directory.
 *
 * @return string
 */
function colby_counselors_path() : string {
	/**
	 * Filters the plugin home path.
	 *
	 * @param string Unfiltered path.
	 */
	return apply_filters( 'colby_counselors_path', plugin_dir_path( __FILE__ ) );
}

/**
 * Returns the trailing-slashed URL path to the root of theh plugin.
 *
 * @return string
 */
function colby_counselors_url() : string {
	/**
	 * Filters the plugin URL path.
	 *
	 * @param string Unfiltered path.
	 */
	return apply_filters( 'colby_counselors_url', plugin_dir_url( __FILE__ ) );
}

/**
 * Returns category terms by their parent, either U.S. or International.
 *
 * @param string $parent The parent term name.
 * @return array
 */
function colby_counselors_get_territories( $parent ) {
	$parent_term = get_term_by( 'name', $parent, Colby_Counselors\Territories_Taxonomy::NAME );

	if ( empty( $parent_term ) ) {
		return;
	}

	$terms = get_term_children( $parent_term->term_id, Colby_Counselors\Territories_Taxonomy::NAME );
	$terms = array_map( 'get_term', $terms );

	if ( is_archive() ) {
		$post_type = get_query_var( 'post_type' );

		$terms = array_filter(
			$terms,
			function( WP_Term $term ) use ( $post_type ) {
				$query = new WP_Query(
					[
						'post_type' => $post_type,
						'tax_query' => [
							[
								'taxonomy' => Colby_Counselors\Territories_Taxonomy::NAME,
								'terms'    => $term->term_id,
							],
						],
					]
				);

				if ( is_wp_error( $query ) ) {
					return false;
				}

				return $query->have_posts();
			}
		);

	}

	usort(
		$terms,
		function( $a, $b ) {
			if ( ! $a->name === $b->name ) {
				return 0;
			}

			return $a->name < $b->name ? -1 : 1;
		}
	);

	return $terms;
}

function colby_counselors_get_regions( $parent ) {
    // Get the parent term by name
    $parent_term = get_term_by( 'name', $parent, Colby_Counselors\Territories_Taxonomy::NAME );

    // If the parent term doesn't exist, return an empty array
    if ( empty( $parent_term ) || is_wp_error( $parent_term ) ) {
        return [];
    }

    // Get the child terms of the parent term
    $terms = get_terms(
        array(
            'taxonomy'   => Colby_Counselors\Territories_Taxonomy::NAME,
            'parent'     => $parent_term->term_id,
            'hide_empty' => false, // Include terms even if they have no posts
        )
    );

    // Filter terms if on an archive page
    if ( is_archive() ) {
        $post_type = get_query_var( 'post_type' );

        $terms = array_filter(
            $terms,
            function( WP_Term $term ) use ( $post_type ) {
                $query = new WP_Query(
                    [
                        'post_type' => $post_type,
                        'tax_query' => [
                            [
                                'taxonomy' => Colby_Counselors\Territories_Taxonomy::NAME,
                                'terms'    => $term->term_id,
                            ],
                        ],
                    ]
                );

                return ! is_wp_error( $query ) && $query->have_posts();
            }
        );
    }

    // Sort the terms alphabetically by name
    usort(
        $terms,
        function( $a, $b ) {
            return strcmp( $a->name, $b->name );
        }
    );

    return $terms;
}


/**
 * Returns global category terms sorted by name.
 *
 * @return array
 */
function colby_counselors_get_global_territories() {
	return colby_counselors_get_territories( 'International' );
}

/**
 * Returns U.S. category terms sorted by name.
 *
 * @return array
 */
function colby_counselors_get_us_territories() {
	return colby_counselors_get_territories( 'U.S.' );
}

function colby_counselors_get_us_regions() {
	return colby_counselors_get_regions( 'U.S.' );
}


/**
 * Echoes a meta field.
 *
 * @param string $key The meta key.
 * @return string The value.
 */
function colby_counselors_get_meta_field( string $key ) : string {
	$value = get_post_meta( get_the_ID(), $key, true );

	if ( empty( $value ) ) {
		return '';
	}

	switch ( $key ) {
		case Colby_Counselors\Counselors_Post_Type::FIRST_NAME_META_KEY:
		case Colby_Counselors\Counselors_Post_Type::LAST_NAME_META_KEY:
		case Colby_Counselors\Counselors_Post_Type::PRONOUNS_META_KEY:
		case Colby_Counselors\Counselors_Post_Type::JOB_TITLE_META_KEY:
		case Colby_Counselors\Counselors_Post_Type::EMAIL_META_KEY:
		case Colby_Counselors\Counselors_Post_Type::PHONE_META_KEY:
		// case Colby_Counselors\Counselor_Events_Post_Type::LOCATION_META_KEY:
			return $value;

		// case Colby_Counselors\Counselor_Events_Post_Type::START_TIME_META_KEY:
		// case Colby_Counselors\Counselor_Events_Post_Type::END_TIME_META_KEY:
		// 	$time = strtotime( $value );
		// 	return esc_html(
		// 		str_replace(
		// 			[ 'am', 'pm' ],
		// 			[ 'a.m.', 'p.m.' ],
		// 			date( get_option( 'date_format' ), $time ) . date( ' g:i a', $time )
		// 		)
		// 	);
	}

	return '';
}

/**
 * Echoes a meta field.
 *
 * @param string $key The meta key.
 * @return void
 */
function colby_counselors_the_meta_field( string $key ) : void {
	static $cache = [];

	$id = get_the_ID();

	if ( ! isset( $cache[ $id ] ) ) {
		$cache[ $id ] = [];
	}

	if ( ! isset( $cache[ $id ][ $key ] ) ) {
		$cache[ $id ][ $key ] = colby_counselors_get_meta_field( $key );
	}

	echo esc_html( $cache[ $id ][ $key ] );
}

/**
 * Echoes the territory list for a post.
 *
 * @return array
 */
function colby_counselors_the_territory_list() {
	$terms = get_the_terms( get_the_ID(), Colby_Counselors\Territories_Taxonomy::NAME );

	if ( is_wp_error( $terms ) ) {
		return;
	}

	$international = [];
	$domestic = [];

	for ($i = 0; $i < count($terms); $i++) {
		$ancestor_cat_ids = get_ancestors($terms[$i]->term_id, "territories");
		$highest_ancestor = $ancestor_cat_ids[count($ancestor_cat_ids) - 1];
		// hardcoded values here!
		if ($highest_ancestor === 46) {
			$international[] = $terms[$i];
		} else {
			$domestic[] = $terms[$i];
		}
	}

	// 41 - US
	// 46 - International

	$term_names_d = array_map(
		function( WP_Term $term ) {
			return $term->name;
		},
		$domestic
	);

	$term_names_i = array_map(
		function( WP_Term $term ) {
			return $term->name;
		},
		$international
	);

	return ["international" => esc_html( implode( ', ', $term_names_i )), "domestic" => esc_html( implode( ', ', $term_names_d ))];
}

/**
 * Echoes the title of an archive page.
 *
 * @return void
 */
function colby_counselors_archive_title() : void {
	if ( Colby_Counselors\Counselors_Post_Type::NAME === get_query_var( 'post_type' ) ) {
		$value = __( 'Meet Our Team', 'colby-counselors' );
	} 
	// else {
	// 	$value = __( 'Colby Counselor Events', 'colby-counselors' );
	// }

	echo esc_html( $value );
}

