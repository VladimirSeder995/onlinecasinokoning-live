<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zakra
 */

$content_orders = get_theme_mod(
	'zakra_page_content_structure',
	array(
		'title',
		'featured_image',
		'content',
	)
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php

	if( !is_front_page() && function_exists('yoast_breadcrumb') ):
		yoast_breadcrumb( '<p class="header-breadcrumbs" id="breadcrumbs">','</p>' );
	endif;

	foreach ( $content_orders as $key => $content_order ) :

		if ( 'title' === $content_order ) :
			?>
			<div class="entry-header">
				<?php zakra_entry_title(); ?>
			</div><!-- .entry-header -->
			<?php
		elseif ( 'featured_image' === $content_order ) :
			zakra_post_thumbnail();
		elseif ( 'content' === $content_order ) :
			?>
			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zakra' ),
						'after'  => '</div>',
					)
				);
				?>
			</div><!-- .entry-content -->
			<?php
		endif;
	endforeach;

	// related slots section
	global $post;
	$gokkasten_parent = get_field('gokkasten_parent','option');
	$live_casino_parent = get_field('live_casino_parent','option');
	$post_type_slug = '';

	if( $post->post_parent == $gokkasten_parent ):

		$post_type_slug = 'gokkasten';

	elseif( $post->post_parent == $live_casino_parent ):

		$post_type_slug = 'live_casino';

	endif;

	if( $post_type_slug ):
		
		$position_helper = intval(get_post_meta( get_the_ID(), $post_type_slug.'_position_helper', true ));

		if( $position_helper < 3 ):
			$position_helper = $position_helper + ( 10 - $position_helper );
		elseif( $position_helper <= 8 ):
			$position_helper = $position_helper + 7;
		else:
			$position_helper = $position_helper + 4;
		endif;

		$relatedPostsArgs = array(
			'post_type'      => 'page',
			'posts_per_page' => 8,
			'post__not_in' => array (get_the_ID()),
			'post_parent'    => get_field($post_type_slug.'_parent','option'),
			'meta_query'       => array(
				'relation' => 'AND',
				array(
					'key'     => $post_type_slug.'_position_helper',
					'value'   => $position_helper,
					'compare' => '<',
					'type'    => 'numeric'
				),
			),
			'order' => 'DESC',
		);
		$relatedPosts = get_posts($relatedPostsArgs);

		if( $relatedPosts ): ?>

			<?php 
			
			$gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';

			?>
		
			<h2><?php _e('Meer zoals','ock'); ?> <?php the_title(); ?></h2>
			<div class="card-wrapper withflexwrap">
		
				<?php foreach ($relatedPosts as $key => $relatedPost):

					$cardId = $relatedPost->ID;

					$cardTitle = $relatedPost->post_title;
					$cardThumbUrl = get_the_post_thumbnail_url($cardId, 'medium_large');

					?>
					<div class="gamecard-container">
						<div class="gamecard-image 1 eager">
							<a href="<?php the_permalink($cardId) ?>">

								<?php if( $cardThumbUrl ): ?>
									<img class="z-depth-1 <?php echo $lazyload_class ?>" alt="<?php echo $cardTitle; ?>" src="<?php echo $cardThumbUrl ?>" />
								<?php else: ?>
									<img class="z-depth-1 <?php echo $lazyload_class ?>" src="<?php echo get_template_directory_uri(); ?>/assets/img/images/default-image.png" alt="default" />
								<?php endif; ?>
							</a>
						</div>
						<div class="gamecard-title">
							<a class="truncate" href="<?php the_permalink($cardId) ?>"><?php echo $cardTitle ?></a>
						</div>

						<?php if( $post_type_slug == 'live_casino' ):
							
							$gokkastenSupplier = get_post_meta($cardId, 'slot_data_game_provider_text', true);

							if( !empty($gokkastenSupplier) ): ?>	

								<div class="gamecard-tag">
									<a class="truncate" href="<?php the_permalink($cardId) ?>"><?php echo $gokkastenSupplier; ?></a>
								</div>

							<?php endif;
						endif; ?>
					</div>

				<?php endforeach; ?>

			</div>

		<?php endif;

	endif;
	// related slots section

	if ( get_edit_post_link() ) :
		?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'zakra' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
		<?php
	endif;
	?>
</article><!-- #post-<?php the_ID(); ?> -->
