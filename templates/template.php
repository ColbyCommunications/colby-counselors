<?php
/**
 * Counselors_Post_Type post type archive
 *
 * @package Colby/Counselors
 */

get_header();

if ( have_posts() ) :

    // Retrieve the territory from the URL, default to 'All' if not present or if empty
    $territory = (!empty($_GET['territories'])) ? sanitize_text_field($_GET['territories']) : 'all counselors';

    // Define custom formatting for specific territories
    $custom_territories = [
        'mid-atlantic' => 'Mid-Atlantic',
        'midwest-west' => 'Mid-West and West',
    ];

    // Format the territory name: first check custom mappings, then apply default formatting
    $territory_key = strtolower(str_replace(' ', '-', $territory)); // Convert to lowercase and replace spaces with hyphens
    if (array_key_exists($territory_key, $custom_territories)) {
        $territory_display = $custom_territories[$territory_key];
    } else {
        $territory_display = ucwords(str_replace('-', ' ', $territory));
    }

    ?>
    <div style="background-color: #022168;"><h1 class="mx-auto px-container container mb-4 py-2 text-3xl counselor-font-bold" style=" color: #FFFF"><?php colby_counselors_archive_title(); ?></h1></div>

    <main class="colby-counselors-main main container mx-auto mt-8 md:mt-8 lg:mt-16" id="main">

        <p class="px-container">For general inquiries, please reach out to (207) 859-4800 or <a href="mailto:admissions@colby.edu" style="color: #062da1">admissions@colby.edu.</a> For financial aid inquiries, please reach out
            to (207) 859-4830 or <a href="mailto:finaid@colby.edu" style="color: #062da1">finaid@colby.edu</a>. </p>
        <div id="colby-counselors-territory-picker"></div>

        <?php include_once 'highlight.php'; ?>

        <?php if ( is_archive() ) : ?>
            <?php include_once 'territory-picker.php'; ?>
        <?php endif; ?>

        <div class="px-container text-left">
            <h3 class="inline-block px-2 counselor-font-bold py-2 text-white text-2xl" style="background-color: #022168;">
                <?php echo $territory_display; ?>
            </h3>
        </div>

        <div class="counselor-grid counselor-grid-cols-1 md:counselor-grid-cols-2">
            <?php while ( have_posts() ) : ?>
                <?php the_post(); ?>
                
                <?php
                $highlight_meta = get_post_meta( get_the_ID(), 'highlight', true );
                if ( empty( $highlight_meta ) ) :
                    include sprintf( 'article-%s.php', get_post_type() );
                endif;
                ?>
            <?php endwhile; ?>
        </div>

    </main>
    <?php
endif;
get_footer();
