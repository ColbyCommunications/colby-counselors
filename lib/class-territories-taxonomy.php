<?php
/**
 * Handles the counselors post type.
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

use JohnWatkins0\WPSingleton\Singleton;

/**
 * Counselors_Post_Typeclass
 */
final class Territories_Taxonomy {
	use Singleton;

	/**
	 * The post type name.
	 *
	 * @var string
	 */
	const NAME = 'territories';

	/**
	 * Sets up hooks.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
	}

	/**
	 * Registers the post type.
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		register_taxonomy(
			self::NAME,
			[ Counselors_Post_Type::NAME, Counselor_Events_Post_Type::NAME ],
			[
				'label'              => esc_html__( 'Territories', 'colby-counselors' ),
				'labels'             => [
					'singular_name' => esc_html__( 'Territory', 'colby-counselors' ),
					'menu_name'     => esc_html__( 'Territories', 'colby-counselors' ),
					'all_items'     => esc_html__( 'All Territories', 'colby-counselors' ),
					'edit_item'     => esc_html__( 'Edit Territories', 'colby-counselors' ),
					'view_item'     => esc_html__( 'View Territory', 'colby-counselors' ),
					'update_item'   => esc_html__( 'Update Territory', 'colby-counselors' ),
					'add_new_item'  => esc_html__( 'Add New Territory', 'colby-counselors' ),
				],
				'hierarchical'       => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => true,
			]
		);
	}
}
