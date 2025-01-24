<?php

if( isset($args['post_data']) && $args['post_data'] ):
    
    $cardId = $args['post_data']->ID;

    $cardTitle = $args['post_data']->post_title;
    $cardThumbUrl = get_the_post_thumbnail_url($cardId, 'medium_large');

    $lazyload_class = ( isset($args['lazyload_class']) && $args['lazyload_class'] )? $args['lazyload_class']:'';

    ?>
    <div class="gamecard-container">
        <div class="gamecard-image">
            <a href="<?php the_permalink($cardId) ?>">

                <?php if( $cardThumbUrl ): ?>
                    <img class="z-depth-1 <?php echo $lazyload_class ?>" alt="<?php echo $cardTitle; ?>" src="<?php echo $cardThumbUrl ?>" loading="lazy" />
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

<?php endif; ?>