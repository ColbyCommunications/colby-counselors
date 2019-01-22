<?php
/**
 * Location picker form
 *
 * @package Colby/Counselors
 */

$us_territories            = colby_counselors_get_us_territories();
$international_territories = colby_counselors_get_global_territories();

?>
<style>
.location-filter h1 {
	margin-top: 0;
	margin-bottom: 0;
}
.location-filter__forms {
	display: flex;
	flex-wrap: wrap;
}

@media screen and (min-width: 592px) {
	.location-filter__forms {
		flex-wrap: nowrap;
	}
}
#location-filter form {
	margin-bottom: 0;
}
</style>
<div id="location-filter" class="location-filter">
	<h1>Filter by Territory:</h1>
	<div class="location-filter__forms">

		<form id="location-filter" action="" method="GET">

			<select name="territories" id="location-pulldown" onchange="this.form.submit()">
				<option value="">-- U.S. --</option>
				<?php foreach ( $us_territories as $territory ) : ?>

					<option value="<?php echo esc_attr( $territory->slug ); ?>"
						<?php if ( get_query_var( 'territories' ) === $territory->slug ) : ?>
							selected
						<?php endif; ?>
						>
						<?php echo esc_html( $territory->name ); ?>

					</option>
				<?php endforeach; ?>

			</select>
		</form>
		<form id="location-filter" action="" method="GET">
			<select id="international" name="territories" onchange="this.form.submit()">
				<option value="">-- International --</option>
				<?php foreach ( $international_territories as $territory ) : ?>
					<option value="<?php echo esc_attr( $territory->slug ); ?>"
						<?php if ( get_query_var( 'territories' ) === $territory->slug ) : ?>
							selected
						<?php endif; ?>
				>
					<?php echo esc_html( $territory->name ); ?>
				</option>
				<?php endforeach; ?>

			</select>

			<?php if ( get_query_var( 'territory' ) ) : ?>
				<a href="">Reset</a>
			<?php endif; ?>
		</form>
	</div>
</div>
