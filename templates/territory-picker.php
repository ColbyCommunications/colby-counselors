<?php
/**
 * Location picker form
 *
 * @package Colby/Counselors
 */

$us_regions = colby_counselors_get_us_regions();
$us_territories            = colby_counselors_get_us_territories();
$international_territories = colby_counselors_get_global_territories();

?>

<div id="location-filter" class="px-container location-filter mt-8 pb-4 flex flex-col items-start"><div>
	<h2 class="mb-4 text-2xl font-bold" style="color: #052168;">Contacts by Region</h2>
	<p class="mb-4">Primary contacts are assigned based on the geographic region of a student’s current school, or the most recent school attended. </p>
	<div class="flex flex-col">

		<form id="location-filter" action="" method="GET">
			<select name="territories" id="location-pulldown" onchange="this.form.submit()" class="mb-2 w-full tablet:w-28">
				<option value="">-- All --</option>
				<?php foreach ( $us_regions as $region ) : ?>

					<option value="<?php echo esc_attr( $region->slug ); ?>"
						<?php if ( get_query_var( 'territories' ) === $region->slug ) : ?>
							selected
						<?php endif; ?>
						>
						<?php echo esc_html( $region->name ); ?>

					</option>
				<?php endforeach; ?>

			</select>
		</form>
	</div>
			</div>
</div>
