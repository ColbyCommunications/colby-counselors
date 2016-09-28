<?php
/**
 * Location picker form
 *
 * @package Colby/Counselors
 */

global $colby_counselors;

$us_territories = array_map( function( $territory ) {
	return trim( $territory );
}, explode( '<br />', get_field( 'us_territories', 'option' ) ) );
$international_territories = array_map( function( $territory ) {
	return trim( $territory );
}, explode( '<br />', get_field( 'international_territories', 'option' ) ) );

?>

<form id="location-filter" action="" method="POST">
	<h1>Filter by Territory:</h1>

	<select name="location-pulldown" id="location-pulldown">
		<option value="">-- U.S. --</option>
		<?php foreach ( $us_territories as $territory ) : ?>

		<option value="<?php echo esc_html( $territory ); ?>"<?php
		if ( get_query_var( 'location-pulldown' ) === $territory ) : ?> selected<?php
endif; ?>>
			<?php echo esc_html( $territory ); ?>

		</option>
		<?php endforeach; ?>

	</select>

	<select id="international" name="international">
		<option value="">-- International --</option>
		<?php foreach ( $international_territories as $territory ) : ?>

		<option value="<?php echo esc_html( $territory ); ?>"<?php
		if ( get_query_var( 'international' ) === $territory ) : ?> selected<?php
endif; ?>>
			<?php echo esc_html( $territory ); ?>

		</option>
		<?php endforeach; ?>

	</select>

	<?php if ( get_query_var( 'international' ) || get_query_var( 'location-pulldown' ) ) : ?>

	<a href="">Reset</a>
	<?php endif; ?>

	<?php wp_nonce_field( 'territory_picker', 'territory_form' ); ?>

</form>