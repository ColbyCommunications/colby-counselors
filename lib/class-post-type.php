<?php
/**
 * Handles the counselors post type.
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

use WP_Query, WP_Post;

/**
 * Post_Type class
 */
abstract class Post_Type {

	/**
	 * The post type name.
	 *
	 * @var string
	 */
	const NAME = '';

	/**
	 * Whether the post type has JS-rendered meta fields.
	 *
	 * @var boolean
	 */
	const HAS_JS_FOR_META_FIELDS = true;

	/**
	 * A nonce action name for saving data.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const NONCE_ACTION = 'colby_counselors__metadata';
	/**
	 * A nonce name for saving data.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const NONCE_NAME = 'colby_counselors__metadata_nonce';

	/**
	 * Fields that have string defaults.
	 *
	 * @var array
	 */
	const STRING_META_FIELDS = [];

	/**
	 * Sets up hooks.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'pre_get_posts', [ $this, 'modify_archive_query' ] );
		add_filter( 'template_include', [ $this, 'include_template' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'set_up_assets' ] );
		add_action( 'edit_form_after_editor', [ $this, 'add_meta_box' ] );
		add_filter(
			'wp_insert_post_data',
			function( $post_array, $form_input ) {
				return $this->save_data( $post_array, $form_input );
			},
			10,
			2
		);
		add_filter( 'body_class', [ $this, 'filter_body_class' ] );
		add_filter('document_title_parts', [ $this, 'counselor_archives_title' ]);
	}

	/**
	 * Provides args for the class's post type.
	 *
	 * @return array
	 */
	abstract public function get_post_type_args() : array;

	/**
	 * Registers the post type.
	 *
	 * @return void
	 */
	public function register_post_type() : void {
		if ( ! empty( static::NAME ) ) {
			register_post_type(
				static::NAME,
				$this->get_post_type_args()
			);
		}
	}
	
	public function counselor_archives_title( $title_parts_array )
	{
		if (get_query_var( 'post_type' ) === 'counselors' && is_post_type_archive( 'counselors' )) {
			$title_parts_array['title'] = "Meet Your Counselor";
		}
		return  $title_parts_array;
	}
	

	/**
	 * Filters the archive query.
	 *
	 * @param WP_Query $query The query.
	 * @return void
	 */
	abstract public function modify_archive_query( WP_Query $query ) : void;

	/**
	 * Filters the template four the counselor archive page.
	 *
	 * @param string $template The template path.
	 * @return string The filtered template path.
	 */
	public function include_template( string $template ) : string {
		if ( is_post_type_archive( static::NAME ) || is_singular( static::NAME ) ) {
			return sprintf(
				'%stemplates/template.php',
				colby_counselors_path(),
				static::NAME
			);
		}

		return $template;
	}

	/**
	 * Enqueues the script.
	 *
	 * @return void
	 */
	public function set_up_assets() : void {
		if ( true !== static::HAS_JS_FOR_META_FIELDS ) {
			return;
		}

		wp_enqueue_script(
			static::NAME,
			sprintf( '%sdist/admin-%s.js', colby_counselors_url(), static::NAME ),
			[ 'react', 'react-dom', 'lodash' ],
			filemtime( sprintf( '%sdist/admin-%s.js', colby_counselors_path(), static::NAME ) ),
			true
		);

		wp_enqueue_style( 'wp-components' );

		wp_localize_script(
			static::NAME,
			'colbyCounselorsBackend',
			static::get_localized_data()
		);
	}

	/**
	 * Adds the metabox.
	 *
	 * @param WP_Post $post The current post.
	 */
	public function add_meta_box( WP_Post $post ) : void {
		if ( static::NAME === $post->post_type ) {
			$this->render_meta_box( $post );
		}
	}

	/**
	 * The callback for rendering the meta box.
	 *
	 * @param WP_Post $post A post object.
	 */
	public function render_meta_box( WP_Post $post ) : void {
		wp_nonce_field( static::NONCE_ACTION, static::NONCE_NAME );
		?>
		<div class="inside">
			<div data-<?php echo esc_attr( static::NAME ); ?>-root></div>
		</div>
		<?php
	}

	/**
	 * Gets data to localize for the CPT editor scripts.
	 *
	 * @return array
	 */
	protected function get_localized_data() : array {
		return [
			'FIELDS' => static::get_string_meta_values(),
		];
	}

	/**
	 * Builds an returns an associative array of meta values expected to be strings.
	 *
	 * @return array
	 */
	protected function get_string_meta_values() : array {
		return array_reduce(
			static::STRING_META_FIELDS,
			function( $array, $meta_key ) {
				$array[ $meta_key ] = get_post_meta( get_the_ID(), $meta_key, true );
				return $array;
			},
			[]
		);
	}

	/**
	 * Saves the meta data
	 *
	 * @param array $post_array An array of data for the current post.
	 * @param array $form_input $_POST data.
	 * @param bool  $verify Whether to check nonce and that the post type matches.
	 * @return array Filtered array of post data.
	 */
	public function save_data( array $post_array, array $form_input, bool $verify = true ) {
		if ( static::NAME !== $post_array['post_type'] ) {
			return $post_array;
		}

		if ( true === $verify ) {
			if ( ! wp_verify_nonce( $form_input[ static::NONCE_NAME ] ?? '', static::NONCE_ACTION ) ) {
				return $post_array;
			}
		}

		foreach ( static::STRING_META_FIELDS as $meta_key ) {
			if ( isset( $form_input[ $meta_key ] ) ) {
				$value = strval( $form_input[ $meta_key ] );
			}

			update_post_meta( get_the_ID(), $meta_key, $value );
		}

		return $post_array;
	}

	/**
	 * Filters the body classes for this post type.
	 *
	 * @param array $classes Unfiltered classes array.
	 * @return array
	 */
	public function filter_body_class( array $classes ) : array {
		if ( static::NAME === get_query_var( 'post_type' ) ) {
			if ( ! in_array( 'post-type-archive-counselors', $classes, true ) ) {
				$classes[] = 'post-type-archive-counselors';
			}
		}

		return $classes;
	}
}
