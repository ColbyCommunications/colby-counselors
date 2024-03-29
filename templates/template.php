<?php
/**
 * Counselors_Post_Typepost type archive
 *
 * @package Colby/Counselors
 */

get_header();
if ( have_posts() ) : ?>

<main class="colby-counselors-main main container mx-auto px-container mt-8 md:mt-8 lg:mt-16" id="main">

	<h1 class="text-3xl font-bold" style="color: #273057"><?php colby_counselors_archive_title(); ?></h1>
	<p class="mt-6">For general inquiries, please reach out to (207) 859-4800 or <a href="mailto:admissions@colby.edu" class="underline" style="color: #002878;">admissions@colby.edu</a>. For financial aid inquiries, please reach out to (207) 859-4830 or <a href="mailto:finaid@colby.edu" class="underline" style="color: #002878;">finaid@colby.edu</a>. To connect with your admissions counselor, use the search bar available on this page.</p>
	<div id="colby-counselors-territory-picker"></div>

	<?php if ( is_archive() ) : ?>
		<?php include_once 'territory-picker.php'; ?>
	<?php endif; ?>

	<?php while ( have_posts() ) : ?>

		<?php the_post(); ?>

		<?php include sprintf( 'article-%s.php', get_post_type() ); ?>

	<?php endwhile; ?>

</main>
	<?php
endif;
get_footer();
