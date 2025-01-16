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


$get_queried = get_queried_object();

$author = get_user_by( 'ID', $get_queried->data->ID );
$author_id = $get_queried->data->ID;

?>

<div id="primary" class="content-area">

    <?php 
    
    if( function_exists('yoast_breadcrumb') ):
		yoast_breadcrumb( '<p class="header-breadcrumbs" id="breadcrumbs">','</p>' );
	endif;
    
    ?>

    <div class="page-header">
        <h1 class="page-title tg-page-content__title"><?php echo $author->data->display_name; ?></h1>
    </div><!-- .page-header -->

        <div class="posts-wrapper">

            <div class="section ock-author-archive-top archive author global-author">
                <div class="container">
                    <div class="row">
                        <div class="col s12">

                            <div class="ock-author-archive-top-inner">

                                <div class="ock-author-archive-side">

                                    <div class="author__name__date__wrap">
                                        <?php 			
                                        // Vars
                                        $birthdate   = get_field('birthdate', 'user_'.$author_id);
                                        $location    = get_field('location', 'user_'.$author_id);
                                        $description = get_field('description_2', 'user_'.$author_id);
                                        $facebook    = get_the_author_meta( 'facebook', $author_id );
                                        $twitter     = get_the_author_meta( 'twitter', $author_id );
                                        $linkedin    = get_the_author_meta( 'linkedin', $author_id );
                                        

                                        if( $birthdate || $location ): ?>
                                        <?php endif; ?>
                                    </div>

                                </div><!-- .ock-author-archive-side -->

                                <div class="ock-author-archive-main">
                                    <div class="ock-author-archive-main-inner">
                                        <?php if( $description ): ?>
                                            <div class="author-archive-desc" style="margin-left: 0; border-radius: 0;">
                                                <div class="author-archive-profile-pic" style="float: right;">
                                                    <?php $image = get_field('profile_picture', 'user_'.$author_id);
                                                    if( !empty($image) ): ?>
                                                        <?php echo wp_get_attachment_image( $image, 'medium' ); ?>
                                                    <?php else: ?>
                                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/icon-author.svg" alt="icon-author" />
                                                    <?php endif; ?>
                                                </div><!-- .author-archive-profile-pic -->
                                                <p><a style="color: #fff; font-size: 18px; font-weight: 800;" href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_object->data->display_name; ?></a></p>
                                                <?php echo $description; ?>
                                            </div><!-- .author-archive-desc -->
                                        <?php endif; ?>
                                    </div><!-- .ock-author-archive-main-inner -->
                                </div><!-- .ock-author-archive-main -->

                            </div><!-- .ock-author-archive-top-inner -->

                        </div><!-- .col s12 -->
                    </div><!-- .row -->
                </div>

            </div><!-- .ock-author-archive-top -->

            <?php

            $author_display_pages = get_field('author_display_pages','option');
            $author_posts_prepared = array();

            if( $author_display_pages ):

                foreach( $author_display_pages as $key => $author_display_page ):

                    if( $author_display_page['page_slug'] && $author_display_page['author_object']->ID == $author_id ):

                        $this_cat = get_category_by_slug( $author_display_page['page_slug'] );

                        if( $this_cat ):

                            $args = array(
                                'post_type'   => array('post', 'page', 'casino_post', 'gokkasten_post', 'live_cas_spel_post', 'cas_spel_post'),
                                'posts_per_page' => 1,
                                'cat' => $this_cat->ID,
                            );
                            $this_cat_post = get_posts($args);

                            $x = new stdClass();
                            $x->type = 'term';
                            $x->permalink = get_term_link($this_cat);
                            $x->title = $this_cat->name;
                            $x->excerpt = term_description($this_cat);

                            if( $this_cat_post ):
                                $x->post_date = $this_cat_post[0]->post_date;
                            endif;

                            $author_posts_prepared[] = $x;

                        else:

                            $args = array(
                                'name'        => $author_display_page['page_slug'],
                                'post_type'   => array('post', 'page', 'casino_post', 'gokkasten_post', 'live_cas_spel_post', 'cas_spel_post'),
                                'post_status' => 'publish',
                                'numberposts' => 1
                            );
                            $this_posts = get_posts($args);

                            if( $this_posts ):
                                $author_posts_prepared[] = $this_posts[0];
                            endif;
                            
                        endif;
                        
                    endif;
                    
                endforeach;
                
            endif;

            // $paged = ( isset($_GET['page']) )? $_GET['page']:1;
            // $paged_prev = $paged-1;

            // if( $author_posts_prepared ):

            // 	$author_posts_prepared_count = count($author_posts_prepared);
            // 	$author_posts_prepared_pages = ceil($author_posts_prepared_count/10);

            // 	if( $author_posts_prepared_count > 10 * $paged_prev ):

            // 		$per_page = 10 - ($author_posts_prepared_count - 10 * $paged_prev);
            // 		$per_page = ( $per_page < 0 )? 0:$per_page;

            // 	else:
            // 		$per_page = 10;
            // 	endif;

            // 	$author_posts_prepared = array_slice($author_posts_prepared, 10 * $paged_prev); 
            // 	$author_posts_prepared = array_slice($author_posts_prepared, 0, 10);

            // endif;


            $per_page = 5 - count($author_posts_prepared);
            $per_page = ( $per_page < 0 )? 0:$per_page;

            $author_posts = array();

            $single_gokkasten_author = get_field('single_gokkasten_author','option');
            $single_news_author = get_field('single_news_author','option');
            $single_casino_author = get_field('single_casino_author','option');

            if( $per_page && isset($single_gokkasten_author->ID) && $single_gokkasten_author->ID == $author_id ):
                $args = array(
                    'post_type' 		=> 'page',
                    'post_parent' => 261,
                    'posts_per_page'	=> $per_page,
                    //'paged' => $paged,
                    //'author' =>  $author_id,
                    'orderby' => 'date',
                    'order'   => 'DESC',
                );

                $author_posts = get_posts( $args );
            elseif( $per_page && isset($single_casino_author->ID) && $single_casino_author->ID == $author_id ):
                $args = array(
                    'post_type' 		=> 'page',
                    'post_parent' => 12,
                    'posts_per_page'	=> $per_page,
                    //'paged' => $paged,
                    //'author' =>  $author_id,
                    'orderby' => 'date',
                    'order'   => 'DESC',
                );

                $author_posts = get_posts( $args );
            elseif( $per_page && isset($single_news_author->ID) && $single_news_author->ID == $author_id ):
                $args = array(
                    'post_type' 		=> 'post',
                    'posts_per_page'	=> $per_page,
                    //'paged' => $paged,
                    //'author' =>  $author_id,
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'custom_author',
                            'value'   => $author_id,
                            'compare' => '=',
                        ),
                        array(
                            'key'     => 'custom_author',
                            'value'   => '',
                            'compare' => '=',
                        ),
                        array(
                            'key'     => 'custom_author',
                            'compare' => 'NOT EXISTS'
                        ),
                    ),
                );

                $author_posts = get_posts( $args );
            elseif( $per_page ):
                $args = array(
                    'post_type' 		=> 'post',
                    'posts_per_page'	=> $per_page,
                    //'paged' => $paged,
                    'author' =>  $author_id,
                    'orderby' => 'date',
                    'order'   => 'DESC',
                );

                $author_posts = get_posts( $args );

                $args = array(
                    'post_type' 		=> 'post',
                    'posts_per_page'	=> $per_page,
                    //'paged' => $paged,
                    'meta_query' => array(
                        array(
                            'key'     => 'custom_author',
                            'value'   => $author_id,
                            'compare' => '=',
                        ),
                    ),
                    'orderby' => 'date',
                    'order'   => 'DESC',
                );

                $author_posts2 = get_posts( $args );

                $author_posts = array_merge( $author_posts, $author_posts2 ); 

            endif;

            // $args = array(
            // 	'post_type' 		=> 'post',
            // 	'posts_per_page'	=> 10,
            // 	'author' =>  $author_id,
            // );

            // $author_posts_query = new WP_Query( $args );

            //$max_pages = $author_posts_query->max_num_pages+$author_posts_prepared_pages;
            $author_posts_prepared = array_merge($author_posts_prepared,$author_posts);
            $author_posts_prepared_h = array();

            if( $author_posts_prepared ):
                foreach ($author_posts_prepared as $key => $author_post_prepared):
                    
                    $date = str_replace('-','',$author_post_prepared->post_date);
                    $date = str_replace(' ','',$date);
                    $date = str_replace(':','',$date);
                    $author_posts_prepared_h[$date] = $author_post_prepared;

                endforeach;
            endif;

            ksort($author_posts_prepared_h);
            $author_posts_prepared = array_reverse($author_posts_prepared_h);
            $author_posts_prepared = array_slice($author_posts_prepared, 0, 5);

            wp_reset_query(); 

            if ( $author_posts_prepared ) :

                do_action('zakra_before_posts_the_loop');

				foreach( $author_posts_prepared as $key => $author_posts_prepared ):

					if( isset($author_posts_prepared->type) && $author_posts_prepared->type == 'term' ):

						get_template_part('template-parts/blog-news-card-v2', '', array(
							'type' => '',
							'permalink' => $author_posts_prepared->permalink,
							'title' => $author_posts_prepared->title,
							'excerpt' => $author_posts_prepared->excerpt,
							'post_date' => $author_posts_prepared->post_date,
						));
						
					else:

						$post = $author_posts_prepared;
                        

						setup_postdata($post);
						get_template_part('template-parts/content');
						
					endif;
					
				endforeach;

				wp_reset_postdata();
        
            endif;

            ?>
        </div>
        <hr>

    <?php

    echo apply_filters('zakra_after_primary_end_filter', false); // // WPCS: XSS OK. 
    ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
