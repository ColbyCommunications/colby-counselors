<?php
/**
 * Registers an autoloader to dynamically load classes, traits, and interfaces using WordPress file naming conventions.
 *
 * @package johnwatkins0/wp_autoload
 */

namespace JohnWatkins0\WPAutoload;

/**
 * Registers an autoloader.
 *
 * @param string $namespace The namespace.
 * @param string $dir The root directory to which the namespace maps.
 * @return void
 */
function register_wp_autoload( $namespace, $dir ) {
	spl_autoload_register(
		function( $class ) use ( $namespace, $dir ) {
			if ( 0 === strpos( $class, '\\' ) ) {
				$class = substr( $class, 1 );
			}

			if ( 0 !== strpos( $class, $namespace ) ) {
				return;
			}

			if ( '\\' !== substr( $namespace, strlen( $namespace ) - 1 ) ) {
				$namespace .= '\\';
			}

			if ( '/' !== substr( $dir, strlen( $dir ) - 1 ) ) {
				$dir .= '/';
			}

			$class = strtolower( str_replace( $namespace, '', $class ) );

			$filename = array_reduce(
				explode( '\\', $class ),
				function( $name, $part ) {
					return sprintf(
						'%s%s%s',
						$name,
						$name ? DIRECTORY_SEPARATOR : '',
						str_replace( '_', '-', $part )
					);
				},
				''
			);

			foreach ( [ 'class', 'trait', 'interface' ] as $prefix ) {
				$file           = sprintf( '%s%s.php', $dir, $filename );
				$file_parts     = explode( DIRECTORY_SEPARATOR, $file );
				$last_file_part = array_pop( $file_parts );
				$file_parts[]   = sprintf( '%s-%s', $prefix, $last_file_part );
				$file           = implode( DIRECTORY_SEPARATOR, $file_parts );

				if ( file_exists( $file ) ) {
					require_once $file;
					return;
				}
			}
		}
	);
}
