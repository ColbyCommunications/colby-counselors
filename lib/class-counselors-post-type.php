<?php
/**
 * Handles the counselors post type.
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

use WP_Query;
use JohnWatkins0\WPSingleton\Singleton;

/**
 * Counselors_Post_Typeclass
 */
final class Counselors_Post_Type extends Post_Type {
	use Singleton;

	/**
	 * The post type name.
	 *
	 * @var string
	 */
	const NAME = 'counselors';

	/**
	 * First name meta key.
	 *
	 * @var string
	 */
	const FIRST_NAME_META_KEY = 'first_name';

	/**
	 * Last name meta key.
	 *
	 * @var string
	 */
	const LAST_NAME_META_KEY = 'last_name';

		/**
	 * Last name meta key.
	 *
	 * @var string
	 */
	const PRONOUNS_META_KEY = 'pronouns';

	/**
	 * Phone meta key.
	 *
	 * @var string
	 */
	const PHONE_META_KEY = 'phone';

	/**
	 * Email meta key.
	 *
	 * @var string
	 */
	const EMAIL_META_KEY = 'email';

	/**
	 * Job title meta key.
	 *
	 * @var string
	 */
	const JOB_TITLE_META_KEY = 'job_title';

	/**
	 * Regional representative meta key.
	 *
	 * @var string
	 */
	const REGIONAL_REPRESENTATIVE_META_KEY = 'regional_representative';

	/**
	 * Fields that have string defaults.
	 *
	 * @var array
	 */
	const STRING_META_FIELDS = [
		self::FIRST_NAME_META_KEY,
		self::LAST_NAME_META_KEY,
		self::PRONOUNS_META_KEY,
		self::JOB_TITLE_META_KEY,
		self::PHONE_META_KEY,
		self::EMAIL_META_KEY,
	];

	/**
	 * Provides the post type args.
	 *
	 * @return array
	 */
	public function get_post_type_args(): array {
		return [
			'labels'       => [
				'name'          => 'Counselors',
				'singular_name' => 'Counselor',
			],
			'public'       => true,
			'show_ui'      => true,
			'has_archive'  => true,
			'show_in_menu' => true,
			'supports'     => [ 'thumbnail' ],
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

		$query->set( 'orderby', 'name' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', 99 );
	}

	/**
	 * Gets data to localize for the CPT editor scripts.
	 *
	 * @return array
	 */
	protected function get_localized_data() : array {
		return [
			'FIELDS' => array_merge(
				self::get_string_meta_values(),
				[
					self::REGIONAL_REPRESENTATIVE_META_KEY => boolval( get_post_meta( get_the_ID(), self::REGIONAL_REPRESENTATIVE_META_KEY, true ) ) ?: false,
				]
			),
		];
	}

	/**
	 * Saves the meta data
	 *
	 * @param array $post_array An array of data for the current post.
	 * @param array $form_input $_POST data.
	 * @param bool  $verify Whether to verify before saving.
	 * @return array Filtered array of post data.
	 */
	public function save_data( array $post_array, array $form_input, bool $verify = true ) : array {
		if ( self::NAME !== $post_array['post_type'] ) {
			return $post_array;
		}

		if ( $verify && ! wp_verify_nonce( $form_input[ self::NONCE_NAME ] ?? '', self::NONCE_ACTION ) ) {
			return $post_array;
		}

		parent::save_data( $post_array, $form_input, false );

		if ( isset( $form_input[ self::REGIONAL_REPRESENTATIVE_META_KEY ] ) ) {
			update_post_meta(
				get_the_ID(),
				self::REGIONAL_REPRESENTATIVE_META_KEY,
				boolval( $form_input[ self::REGIONAL_REPRESENTATIVE_META_KEY ] )
			);
		}

		// Build the post title from Lastname, Firstname.
		if ( ! empty( $form_input[ self::LAST_NAME_META_KEY ] ) ) {
			$title = sprintf(
				'%s%s%s',
				$form_input[ self::LAST_NAME_META_KEY ] ?? '',
				empty( $form_input[ self::FIRST_NAME_META_KEY ] ) ? '' : ', ',
				$form_input[ self::FIRST_NAME_META_KEY ] ?? ''
			);

			$post_array['post_title'] = $title;
			$post_array['post_name']  = sanitize_title( $title );
		}

		return $post_array;
	}

}
