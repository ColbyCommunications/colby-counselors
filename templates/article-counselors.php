<?php
/**
 * Template for a single counselor.
 *
 * @package colbycomms/counselors
 */

?>

<article class="px-container py-8 counselor-grid md:counselor-grid-cols-1 lg:counselor-grid-cols-2">

<div class="counselor-mb-2 lg:counselor-mb-0">
    <?php if ( has_post_thumbnail() ) : ?>
        <img>
            <?php the_post_thumbnail( 'medium' ); ?>
        </img>
    <?php else : ?>
        <div class="lg:counselor-h-full counselor-h-72 counselor-w-72 md:counselor-w-auto counselor-block  counselor-flex counselor-justify-center counselor-items-center" style="background-color: #e4e8ef;">
            <img class="counselor-h-full counselor-w-3/5" src="<?php echo plugins_url( '../src/assets/placeholder.svg', __FILE__ ); ?>" alt="Placeholder Image" class="max-w-full max-h-full">
        </div>
    <?php endif; ?>
</div>



	<div class="lg:counselor-pl-2 md:text-md">
		<h4 class="counselor-font-bold counselor-text-lg">
			<?php colby_counselors_the_meta_field( 'first_name' ); ?>
			<?php colby_counselors_the_meta_field( 'last_name' ); ?>
		</h4>
		<p class="mb-4 font-normal"><?php colby_counselors_the_meta_field( 'pronouns' ); ?></p>
		<p class="" style="color: #273057">
			<?php colby_counselors_the_meta_field( 'job_title' ); ?>
		</p>
		<div>
		<p class=""><a style="color: #062da1" href="mailto:<?php colby_counselors_the_meta_field( 'email' ); ?>"><?php colby_counselors_the_meta_field( 'email' ); ?></a></p>
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
