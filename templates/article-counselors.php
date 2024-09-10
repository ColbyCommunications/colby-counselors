<?php
/**
 * Template for a single counselor.
 *
 * @package colbycomms/counselors
 */

?>

<article class="px-container py-8 counselor-border-b grid grid-cols-1 lg:grid-cols-2">

<div>
	<?php if ( has_post_thumbnail() ) : ?>
		
	<img>
			<?php the_post_thumbnail( 'medium'); ?>
	</img>
	<?php endif; ?>
</div>

	<div class="pl-2 counselor-text-sm lg:counselor-text-large">
		<h2 class="counselor-font-bold">
			<?php colby_counselors_the_meta_field( 'first_name' ); ?>
			<?php colby_counselors_the_meta_field( 'last_name' ); ?>
		</h2>
		<p class="mb-4 font-normal"><?php colby_counselors_the_meta_field( 'pronouns' ); ?></p>
		<h3 class="" style="color: #273057">
			<?php colby_counselors_the_meta_field( 'job_title' ); ?>
		</h3>
		<div>
		<p class=""><?php colby_counselors_the_meta_field( 'email' ); ?></p>
		<p class=""><?php colby_counselors_the_meta_field( 'phone' ); ?></p>
		</div>
		<?php if ( has_term( '', Colby_Counselors\Territories_Taxonomy::NAME ) ) : ?>
			<div class="">
				<p class="counselor-font-bold">Primary Contact:
					<span class="font-normal"><?php echo colby_counselors_the_territory_list()["domestic"]; ?></span>
				</p>
			</div>
		<?php endif; ?>
	</div>

</article>
