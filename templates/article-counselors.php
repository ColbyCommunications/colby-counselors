<?php
/**
 * Template for a single counselor.
 *
 * @package colbycomms/counselors
 */

?>

<article <?php post_class( 'counselor' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>

		<section class="counselor__image-container">
			<?php the_post_thumbnail( 'thumbnail', [ 'class' => 'counselor__image' ] ); ?>
		</section>
	<?php endif; ?>

	<section class="counselor__content">
		<h1 class="counselor__title">
			<?php colby_counselors_the_meta_field( 'first_name' ); ?>
			<?php colby_counselors_the_meta_field( 'last_name' ); ?>
		</h1>
		<h2 class="counselor__job-title">
			<?php colby_counselors_the_meta_field( 'job_title' ); ?>
		</h2>
		<div class="counselor__contact">
			<p>
				<a href="mailto:<?php colby_counselors_the_meta_field( 'email' ); ?>">
					<?php colby_counselors_the_meta_field( 'email' ); ?>
				</a>
			</p>
			<p>
				<?php colby_counselors_the_meta_field( 'phone' ); ?>
			</p>
		</div>
		<?php if ( has_term( '', Colby_Counselors\Territories_Taxonomy::NAME ) ) : ?>
			<div class="counselor__territories">
				<span>Territories: </span>
				<?php colby_counselors_the_territory_list(); ?>
			</div>
		<?php endif; ?>
	</section>
</article>
