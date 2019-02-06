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
			<?php the_title(); ?>
		</h1>
		<h2 class="counselor__job-title">
			<?php colby_counselors_the_meta_field( 'location' ); ?>
		</h2>
		<p>
			<?php colby_counselors_the_meta_field( 'start_time' ); ?>
		</p>

	</section>
</article>
