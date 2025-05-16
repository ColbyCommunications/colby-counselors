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

function enqueue_custom_scripts() {
    // Get the dimensions of the 'medium' size thumbnail
    $medium_size = get_option('thumbnail_size_w'); // Default width
    $medium_height = get_option('thumbnail_size_h'); // Default height

    // Pass the dimensions to JavaScript
    wp_localize_script('your-script-handle', 'thumbnailData', array(
        'width' => $medium_size,
        'height' => $medium_height,
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


function enqueue_map_scripts() {

	    wp_enqueue_script(
        'usmap-js',
        plugins_url('map-data/us-map/usmap.js', __FILE__),
        array('mapdata-js'), // Load after mapdata.js
        null, // Version number
        false // Load in the footer
    );

    wp_enqueue_script(
        'mapdata-js',
        plugins_url('map-data/us-map/mapdata1.js', __FILE__),
        array(), // No dependencies
        null, // Version number
        false // Load in the footer
    );

		  wp_enqueue_script(
        'worldmap-js',
        plugins_url('map-data/world-map/worldmap.js', __FILE__),
        array('mapdata2-js'), // Load after mapdata.js
        null, // Version number
        false // Load in the footer
    );

		wp_enqueue_script(
        'mapdata2-js',
        plugins_url('map-data/world-map/mapdata2.js', __FILE__),
        array(), // No dependencies
        null, // Version number
        false // Load in the footer
    );


	wp_register_script('maps-script', plugins_url('map-data/maps.js', __FILE__), array(), null, true);
    wp_enqueue_script('maps-script');

	wp_register_script('alpine', '//cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array('maps-script'), null, true);
    wp_enqueue_script('alpine');
}

add_action('wp_enqueue_scripts', 'enqueue_map_scripts');

function register_counselors_rest_route() {
    register_rest_route('counselors/v1', '/counselors', [
        'methods' => 'GET',
        'callback' => 'get_counselors_data',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'register_counselors_rest_route');

function get_counselors_data(WP_REST_Request $request) {
    $args = [
        'post_type' => 'counselors',
        'posts_per_page' => -1,
    ];

    $query = new WP_Query($args);
    $counselors = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $taxonomies = ['territories'];
            $terms = [];

            foreach ($taxonomies as $taxonomy) {
                $taxonomy_terms = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'all']);
                if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
                    $terms[$taxonomy] = array_map(function ($term) {

                        // Get the immediate parent term
                        $parent = ($term->parent) ? get_term($term->parent) : null;
                        
                        // Get the grandparent term if the parent exists
                        $grandparent = ($parent && $parent->parent) ? get_term($parent->parent) : null;

                        // Return the term data with both parent and grandparent
                        return [
                            'id' => $term->term_id,
                            'name' => $term->name,
                            'slug' => $term->slug,
                            'taxonomy' => $term->taxonomy,
                            'description' => $term->description,
                            'parent' => $parent ? [
                                'id' => $parent->term_id,
                                'name' => $parent->name,
                                'slug' => $parent->slug,
                                'taxonomy' => $parent->taxonomy,
                                'description' => $parent->description,
                            ] : null,
                            'grandparent' => $grandparent ? [
                                'id' => $grandparent->term_id,
                                'name' => $grandparent->name,
                                'slug' => $grandparent->slug,
                                'taxonomy' => $grandparent->taxonomy,
                                'description' => $grandparent->description,
                            ] : null,
                        ];
                    }, $taxonomy_terms);
                }
            }

            $counselors[] = [
                'id' => $post_id,
                'title' => get_the_title(),
                'content' => apply_filters('the_content', get_the_content()),
                'excerpt' => get_the_excerpt(),
                'date' => get_the_date(),
                'modified' => get_the_modified_date(),
                'author' => get_the_author(),
                'slug' => get_post_field('post_name', $post_id),
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'full'),
                'permalink' => get_permalink(),
                'meta' => get_post_meta($post_id),
                'terms' => $terms,
            ];
        }
        wp_reset_postdata();
    }

    // Sort counselors by last name (assuming 'last_name' is stored in 'meta')
    usort($counselors, function ($a, $b) {
        // Extract last names from meta fields
        $last_name_a = isset($a['meta']['last_name']) ? $a['meta']['last_name'][0] : '';
        $last_name_b = isset($b['meta']['last_name']) ? $b['meta']['last_name'][0] : '';

        // Compare last names alphabetically
        return strcmp($last_name_a, $last_name_b);
    });

    return rest_ensure_response($counselors);
}





