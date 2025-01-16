<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zakra
 */

get_header();
?>

<div id="primary" class="content-area">
    <?php echo apply_filters('zakra_after_primary_start_filter', false); // WPCS: XSS OK.

    if (is_home() && !is_front_page()) :

        if( function_exists('yoast_breadcrumb') ):
            yoast_breadcrumb( '<p class="header-breadcrumbs" id="breadcrumbs">','</p>' );
        endif;

        if ('page-header' !== zakra_is_page_title_enabled()) : ?>
            <div class="page-header">
                <h1 class="page-title tg-page-content__title"><?php single_post_title(); ?></h1>
            </div><!-- .page-header -->
        <?php
        endif;
    endif;

    if (have_posts()) : ?>

        <div class="posts-wrapper">
            <?php
            do_action('zakra_before_posts_the_loop');

            /* Start the Loop */
            while (have_posts()) :
                the_post();

                /*
                    * Include the Post-Type-specific template for the content.
                    * If you want to override this in a child theme, then include a file
                    * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                    */
                get_template_part('template-parts/content', get_post_type());

            endwhile;

            ?>
        </div>
        <hr>

        <div class="nav-links">
            <?php
            global $wp_query;

            $big = 999999999; // need an unlikely integer

            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages,
                'prev_text'          => __('« Vorige'),
                'next_text'          => __('Volgende »')
            ));
            ?>
        </div>
    <?php

    else :

        get_template_part('template-parts/content', 'none');

    endif;

    echo apply_filters('zakra_after_primary_end_filter', false); // // WPCS: XSS OK. 
    ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
