<?php
// Template for showing highlighted counselors at the top of the page

$query_args = array(
    'post_type'  => 'counselors',
    'meta_query' => array(
        array(
            'key'     => 'highlight',
            'value'   => '1',
            'compare' => '=',
        ),
    ),
);

$highlight_query = new WP_Query( $query_args );

if ( $highlight_query->have_posts() ) : ?>

    <div class="counselor-grid counselor-grid-cols-1 md:counselor-grid-cols-2">
        <?php while ( $highlight_query->have_posts() ) : $highlight_query->the_post(); ?>

            <?php include sprintf( 'article-%s.php', get_post_type() ); ?>

        <?php endwhile; ?>
    </div>

<?php
    wp_reset_postdata();
endif;
?>
