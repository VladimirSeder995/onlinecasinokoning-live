<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package zakra
 */

get_header();
?>

<div id="primary" class="content-area">
    <?php echo apply_filters('zakra_after_primary_start_filter', false); // WPCS: XSS OK. 
    ?>

    <?php 
    
    if( function_exists('yoast_breadcrumb') ):
		yoast_breadcrumb( '<p class="header-breadcrumbs" id="breadcrumbs">','</p>' );
	endif;
    
    ?>

    <?php zakra_entry_title(); ?>

    <div class="posts-wrapper">
        <div class="hentry">
            <?php get_template_part('template-parts/content', 'none'); ?>

        </div>
    </div>

    <?php echo apply_filters('zakra_after_primary_end_filter', false); // WPCS: XSS OK. 
    ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
