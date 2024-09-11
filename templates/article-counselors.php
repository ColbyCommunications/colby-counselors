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
        <div class="block w-auto flex justify-center items-center" style="background-color: #e4e8ef; max-width: 300px;">
            <img class="h-full max-width: 300px;" src="<?php echo plugins_url( '../src/assets/placeholder.svg', __FILE__ ); ?>" alt="Placeholder Image" class="max-w-full max-h-full">
        </div>
    <?php endif; ?>
</div>



	<div class="lg:counselor-pl-2 md:text-md">
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
