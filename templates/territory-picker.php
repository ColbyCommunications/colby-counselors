<?php
/**
 * Location picker form
 *
 * @package Colby/Counselors
 */

$us_territories            = colby_counselors_get_us_territories();
$international_territories = colby_counselors_get_global_territories();

?>

<div id="location-filter" class="location-filter mt-8 pb-4 flex flex-col items-end" style="border-bottom: 1px solid black;"><div>
	<div><h2 class="font-bold">Filter by Territory:</h2></div>
	<div class="flex flex-col">

		<form id="location-filter" action="" method="GET">

			<select name="territories" id="location-pulldown" onchange="this.form.submit()" class="mb-2 w-full tablet:w-28">
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
			<select id="international" name="territories" onchange="this.form.submit()" class="w-full tablet:w-28">
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
</div>
