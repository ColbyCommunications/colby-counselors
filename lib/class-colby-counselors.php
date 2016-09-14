<?php
/**
 * Configuration class
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

/**
 * Set variables used throughout the plugin.
 */
class Colby_Counselors {
    public $us_territories = [
    	'Alabama',
    	'Alaska',
    	'Arizona',
    	'Arkansas',
    	'California (Northern)',
    	'California (Southern)',
    	'Colorado',
    	'Connecticut',
    	'Delaware',
    	'District of Columbia',
    	'Florida',
    	'Georgia',
    	'Hawaii',
    	'Idaho',
    	'Illinois',
    	'Indiana',
    	'Iowa',
    	'Kansas',
    	'Kentucky',
    	'Louisiana',
    	'Maine (Central)',
    	'Maine (Northern)',
    	'Maine (Southern)',
    	'Maryland',
    	'Massachusetts (Boston, Cambridge)',
    	'Massachusetts (Central)',
    	'Massachusetts (Central, Cape Cod, and Islands)',
    	'Massachusetts (Essex, Norfolk, Bristol, and Plymouth Counties)',
    	'Massachusetts (Lowell, Concord, Wellesley, Milton, Lexington, Waltham)',
    	'Massachusetts (Western)',
    	'Michigan',
    	'Minnesota',
    	'Mississippi',
    	'Missouri',
    	'Montana',
    	'Nebraska',
    	'Nevada',
    	'New Hampshire',
    	'New Jersey',
    	'New Mexico',
        'New York',
        'New York (Manhattan)',
        'New York (except Westchester and Manhattan)',
        'New York (Westchester)',
    	'North Carolina',
    	'North Dakota',
    	'Ohio',
    	'Oklahoma',
    	'Oregon',
    	'Pennsylvania',
    	'Rhode Island',
    	'South Carolina',
    	'South Dakota',
    	'Tennessee',
    	'Texas',
    	'Vermont',
    	'Virginia',
    	'Washington',
    	'West Virginia',
    	'Wisconsin',
    	'Wyoming',
    ];

    public $intl_territories = [
        'Africa',
    	'Bermuda',
    	'Canada',
    	'Caribbean',
    	'East Asia',
        'East and Southeast Asia',
    	'Eurasia',
    	'Europe',
    	'Greater Indian Subcontinent',
    	'Latin America',
    	'Mexico',
    	'Middle East',
    	'Oceania',
    ];

	/**
	 * Stylesheets for the admin backend.
	 *
	 * @var $admin_stylesheets array
	 */
	public $admin_stylesheets = [];

	/**
	 * The protocol-free URL.
	 *
	 * @var $assets_url string
	 */
	public $assets_url = '';

	/**
	 * Run the plugin in debug mode.
	 *
	 * @var $debug bool
	 */
	public $debug = false;

	/**
	 * Post types that are not publicly used.
	 *
	 * @var $hidden_post_types array
	 */
	public $hidden_post_types = [];

	/**
	 * Associative array of data to localize.
	 *
	 * @var $localized_Data array
	 */
	public $localized_data = [];

	/**
	 * The system path to the main directory file.
	 *
	 * @var $main_file string
	 */
	public $main_file = '';

	/**
	 * The string to add to minified asset URLs when not debugging.
	 *
	 * @var $min string
	 */
	public $min = '';

	/**
	 * The system path to the plugin root.
	 *
	 * @var $path string
	 */
	public $path = '';

	/**
	 * The array of post types registered by this plugin.
	 *
	 * @var $post_types array
	 */
	public $post_types = [];

	/**
	 * The array of post type slugs registered by this plugin.
	 *
	 * @var $post_type_names array
	 */
	public $post_type_names = [];

	/**
	 * The array of scripts enqueued by this plugin.
	 *
	 * @var $scripts array
	 */
	public $scripts = [];

	/**
	 * The array of shortcodes added by this plugin.
	 *
	 * @var $shortcodes array.
	 */
	public $shortcodes = [];

	/**
	 * The array of stylesheets enqueued by this plugin.
	 *
	 * @var $stylesheets array
	 */
	public $stylesheets = [];

	/**
	 * The array of taxonomies added by this plugin.
	 *
	 * @var $taxonomies array
	 */
	public $taxonomies = [];

	/**
	 * The array of taxonomies names added by this plugin.
	 *
	 * @var $taxonomy_names array
	 */
	public $taxonomy_names = [];

	/**
	 * The plugin's text domain.
	 *
	 * @var $text_domain string
	 */
	public $text_domain = '';

	/**
	 * The text domain with an underscore instead of a hyphen.
	 *
	 * @var $text_domain_underscore string
	 */
	public $text_domain_underscore = '';

	/**
	 * The plugin directory's root URL.
	 *
	 * @var $url string
	 */
	public $url = '';

	/**
	 * The plugin's version number.
	 *
	 * @var $version string
	 */
	public $version = '0.1';

	/**
	 * Populate the object's variables with real values.
	 *
	 * @param string $main_file The system path of the main plugin file.
	 * @param array  $plugin_data The plugin data set in the main file.
	 */
	public function __construct( $main_file, $plugin_data ) {
		$this->main_file = $main_file;
		$this->debug = isset( $_GET['debug'] ) ? true : false;
		$this->path = trailingslashit( dirname( $main_file ) );
		$this->url = trailingslashit( plugin_dir_url( $main_file ) );
		$this->assets_url = substr( $this->url, ( strpos( $this->url, '//' ) ) ) . 'assets/';
		$this->text_domain = $plugin_data['Text Domain'];
		$this->text_domain_underscore = str_replace( '-', '_', $this->text_domain );
		$this->version = $plugin_data['Version'];
		$this->min = true === $this->debug ? '' : '.min';
		$this->plugin = $plugin_data['Email'];

		add_action( 'admin_init', function() {
			$this->set_admin_styles();
		} );

		add_action( 'init', function() {
            $this->set_taxonomies();
			$this->set_post_types();
			$this->set_hidden_post_types();
			$this->set_shortcodes();
			$this->set_styles();
			$this->set_scripts();
			$this->set_up_localization();
		} );
	}

	/**
	 * Set an array of admin styles.
	 */
	public function set_admin_styles() {
		$this->admin_stylesheets = [];

        foreach ( $this->admin_stylesheets as $style ) {
            if ( ! wp_style_is( $style[0]) && file_exists( $style[1]) ) {
    			call_user_func_array( 'wp_enqueue_style', $style );
            }
		}
	}

	/**
	 * Set an associative array of this plugin's post types -- name => settings.
	 * Example:
	 * $this->post_types = [
	 *        'type' => [
	 *            'label' => 'Types',
	 *            'labels' => [
	 *                'singular_name' => 'Type',
	 *            ],
	 *            'public' => true,
	 *            'supports' => [ 'title', 'editor' ],
	 *            'hierarchical' => false,
	 *            'taxonomies' => [ 'type-categories' ],
	 *        ],
	 *    ];
	 */
	public function set_post_types() {
		$this->post_types = [
            'counselors' => array(
        		'labels' => [
        			'name' => 'Counselors',
        			'singular_name' => 'Counselor',
        			],
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
            ),
        ];

        foreach ( $this->post_types as $name => $settings ) {
			if ( ! post_type_exists( $name ) ) {
				register_post_type( $name, $settings );
				$this->post_type_names[] = $name;
			}
		}
	}

	public function set_hidden_post_types() {
		$this->hidden_post_types = [];

        foreach ( $this->hidden_post_types as $name => $settings ) {
			if ( ! post_type_exists( $name ) ) {
				register_post_type( $name, $settings );
			}
		}
	}

	/**
	 * Set an array of arrays corresponding to wp_enqueue_script parameters.
	 */
	public function set_scripts() {
		$this->scripts = [
            [ $this->text_domain, "{$this->assets_url}{$this->text_domain}.js", [ 'jquery' ], $this->version, true ]
        ];

        foreach ( $this->scripts as $script ) {
            if ( ! wp_script_is( $script[0] ) ) {
    			call_user_func_array( 'wp_enqueue_script', $script );
            }
		}
	}

	/**
	 * Create an array of data to send to scripts.
	 * Example:
	 * [handle => [key => value],
	 * handle2 => [key=> value]]]
	 */
	public function set_up_localization() {
		$this->localized_data = [];

		foreach ( $this->localized_data as $parameter_array ) {
			call_user_func_array( 'wp_localize_script', $parameter_array );
		}
	}

	/**
	 * Set an array of namespaces corresponding to this plugin's shortcode classes.
	 */
	public function set_shortcodes() {
		$this->shortcodes = [];

        foreach ( $this->shortcodes as $class ) {
			$shortcode = new $class( $this->plugin );
			add_shortcode( $shortcode->shortcode, [ $shortcode, 'run' ] );
		}
	}

	/**
	 * Set an array of arrays corresponding to wp_enqueue_style parameters.
	 */
	public function set_styles() {
		$this->stylesheets = [

        ];

        foreach ( $this->stylesheets as $style ) {
            if ( ! wp_style_is( $style[0] ) ) {
    			call_user_func_array( 'wp_enqueue_style', $style );
            }
		}
	}

	/** Set an associative array of this plugin's taxonomies -- name => settings
	 * Example:
	 * $this->taxonomies = [
	 *        'type-categories' => [
	 *            'object_type' => 'type',
	 *            'args' => [
	 *                'label' => 'Type Categories',
	 *                'labels' => [
	 *                    'singular_name' => 'Type Category',
	 *                ],
	 *                'hierarchical' => true,
	 *            ],
	 *        ],
	 *    ];
	 */
	public function set_taxonomies() {
		$this->taxonomies = [];

        foreach ( $this->taxonomies as $name => $settings ) {
			register_taxonomy( $name, $settings['type'], $settings['args'] );
			$this->taxonomy_names[] = $name;
		}
	}
}
