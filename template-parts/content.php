<?php

/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zakra
 */

$meta_style = get_theme_mod('zakra_blog_archive_meta_style', 'tg-meta-style-one');
?>

<article id="post-<?php the_ID(); ?>" class="tg-meta-style-one post-<?php the_ID(); ?> post type-post status-publish format-standard has-post-thumbnail hentry category-nieuws zakra-article">
    <div class="news__single__article">
        <div class="news__single__article__image">
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="false">
                <?php if( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail('full'); ?>
                <?php else : ?>
                    <img src='<?php echo get_stylesheet_directory_uri() . '/assets/img/images/default-image.png' ?>' alt='default-post-image' />
                <?php endif; ?>
            </a>
        </div>
        <div class="news__single__article__content">
            <header class="entry-header">
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                </h2>
            </header>
            <div class="entry-content">
                <p><?php echo get_the_excerpt(); ?></p>
                <div class="tg-read-more-wrapper clearfix tg-text-align--left">
                    <a href="<?php the_permalink(); ?>" class="tg-read-more">
                    <?php _e('Lees meer', 'ock'); ?>
                </a>
                    <p><?php echo get_the_date('d F Y', get_the_ID()); ?></p>
                </div>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->