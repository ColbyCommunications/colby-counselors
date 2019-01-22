<?php
/**
 * Handles the counselor-events post type.
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

use WP_Query;
use JohnWatkins0\WPSingleton\Singleton;

/**
 * Counselors_Post_Typeclass
 */
final class Counselor_Events_Post_Type extends Post_Type {
	use Singleton;

	/**
	 * The post type name.
	 *
	 * @var string
	 */
	const NAME = 'counselor-events';

	/**
	 * Location meta key.
	 *
	 * @var string
	 */
	const LOCATION_META_KEY = 'location';

	/**
	 * Location meta key.
	 *
	 * @var string
	 */
	const START_TIME_META_KEY = 'start_time';

	/**
	 * Link meta key.
	 *
	 * @var string
	 */
	const LINK_META_KEY = 'link';

	const STRING_META_FIELDS = [
		self::LOCATION_META_KEY,
		self::START_TIME_META_KEY,
	];

	/**
	 * Sets up hooks.
	 *
	 * @return void
	 */
	protected function init() {
		parent::init();

		add_filter( 'body_class', [ $this, 'filter_body_class' ] );
	}

	/**
	 * Provides the post type args.
	 *
	 * @return array
	 */
	public function get_post_type_args(): array {
		return [
			'labels'       => [
				'name'          => 'Counselor Events',
				'singular_name' => 'Counselor Event',
			],
			'description'  => '',
			'public'       => true,
			'show_ui'      => true,
			'has_archive'  => true,
			'show_in_menu' => true,
			'hierarchical' => false,
			'query_var'    => true,
			'supports'     => [ 'title' ],
		];
	}

	/**
	 * Filters the counselors archive query.
	 *
	 * @param WP_Query $query The query.
	 * @return void
	 */
	public function modify_archive_query( WP_Query $query ) : void {
		if ( is_admin() || ! is_post_type_archive( self::NAME ) ) {
			return;
		}

		$query->set(
			'meta_query',
			[
				'time_clause' => [
					'meta_key' => self::START_TIME_META_KEY,
					'value'    => date( 'c' ),
					'compare'  => '>=',
					'type'     => 'DATETIME',
				],
			]
		);

		$query->set( 'orderby', [ 'time_clause' => 'asc' ] );
	}

	/**
	 * Filters the body classes for this post type.
	 *
	 * @param array $classes Unfiltered classes array.
	 * @return array
	 */
	public function filter_body_class( array $classes ) : array {
		if ( self::NAME === get_query_var( 'post_type' ) ) {

			if ( ! in_array( 'post-type-archive-counselors', $classes, true ) ) {
				$classes[] = 'post-type-archive-counselors';
			}
		}

		return $classes;
	}
}
