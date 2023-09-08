<?php
/**
 * Template for a single counselor.
 *
 * @package colbycomms/counselors
 */

?>

<article class="counselor-grid counselor-grid-cols-12 counselor-gap-4 py-8 counselor-border-b">

	<?php if ( has_post_thumbnail() ) : ?>
		
		<div class="counselor-col-span-12 laptop:counselor-col-span-2 counselor-mb-4 laptop:counselor-mb-0" >
			<?php the_post_thumbnail( 'medium'); ?>
		</div>
	<?php endif; ?>

	<div class="counselor-col-span-12 laptop:counselor-col-span-8">
		<h2 class="counselor-font-bold counselor-text-2xl">
			<?php colby_counselors_the_meta_field( 'first_name' ); ?>
			<?php colby_counselors_the_meta_field( 'last_name' ); ?>
			<span class="counselor-text-lg font-normal"><?php colby_counselors_the_meta_field( 'pronouns' ); ?></span>
		</h2>
		<h3 class="counselor-text-lg counselor-mb-2" style="color: #273057">
			<?php colby_counselors_the_meta_field( 'job_title' ); ?>
		</h3>
		<div class="counselor-mb-4">
			<p>
				<a style="color: #273057" href="mailto:<?php colby_counselors_the_meta_field( 'email' ); ?>">
					<?php colby_counselors_the_meta_field( 'email' ); ?>
				</a>
			</p>
			<p>
				<?php colby_counselors_the_meta_field( 'phone' ); ?>
			</p>
		</div>
		<?php if ( has_term( '', Colby_Counselors\Territories_Taxonomy::NAME ) ) : ?>
			<div class="">
				<span class="counselor-font-bold">Domestic: </span>
				<?php echo colby_counselors_the_territory_list()["domestic"]; ?>
			</div>
			<div class="">
				<span class="counselor-font-bold">International: </span>
				<?php echo colby_counselors_the_territory_list()["international"]; ?>
			</div>
		<?php endif; ?>
	</div>

</article>
