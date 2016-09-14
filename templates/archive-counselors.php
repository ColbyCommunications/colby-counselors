<?php
/**
 * Counselors post type archive
 *
 * @package Colby/Counselors
 */

get_header();
if ( have_posts() ) : ?>

<main class="main">
	<h1>Meet Your Counselor</h1>
<?php
include( 'territory-picker.php' );
while ( have_posts() ) : the_post();
	if ( get_query_var( 'international' ) && ! in_array( get_query_var( 'international' ), $post->locations ) ) :
		continue;
	endif;

	if ( get_query_var( 'location-pulldown' ) &&
			'International' !== get_query_var( 'location-pulldown' ) &&
			! in_array( get_query_var( 'location-pulldown' ), $post->locations ) ) :
		continue;
	endif;

?>

	<article <?php post_class( 'counselor' ); ?>><?php
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'small' );
	if ( $thumbnail ) :  ?>

		<section class="counselor__image-container">
			<img class="counselor__image" src="<?php echo esc_url( $thumbnail[0] ); ?>">
		</section>
		<?php endif; ?>

		<section class="counselor__content">
			<h1 class="counselor__title"><?php the_title(); ?></h1>
			<h2 class="counselor__job-title">
				<?php echo esc_html( $post->jobtitle ); ?>
			</h2>
			<div class="counselor__contact">
				<p>
					<a href="mailto:<?php echo esc_html( $post->email ); ?>">
						<?php echo esc_html( $post->email ); ?>
					</a>
				<p>
					<?php echo esc_html( $post->phone ); ?>
			</div>
			<?php if ( isset( $post->locations ) && $post->locations ): ?>
			<div class="counselor__territories">
				<span>Territories: </span><?php
				foreach ( $post->locations as $key => $location ) :
					if ( 0 < $key ) : ?>, <?php
		endif;
					echo esc_html( $location );
	endforeach;
?>
			</div>
		<?php endif; ?>
		</section>
	</article>
<?php endwhile; ?>

</main><?php
endif;
get_footer();
