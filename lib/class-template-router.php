<?php
/**
 * Template router
 *
 * @package colby-counselors
 */

namespace Colby_Counselors;

class Template_Router {
	public function __construct( &$plugin ) {
		$this->plugin = $plugin;

        add_filter( 'template_include', [ $this, 'include_counselor_template' ] );
	}

    public function include_counselor_template( $template ) {
    	if ( is_post_type_archive( 'counselors' ) ) {
    		return "{$this->plugin->path}templates/archive-counselors.php";
    	}

    	return $template;
    }
}
