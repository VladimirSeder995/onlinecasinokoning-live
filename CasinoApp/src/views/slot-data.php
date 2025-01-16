<div class="features-grey-table">
    <?php 
    foreach ($args['content'] as $key => $block) { // Iterate through all games are created
        if( trim($block['value']) ) {
            CasinoApp\Base::load_template_part( 'slot-data-item', null, [
                'block'    => $block,
            ], false);
        }
    }
    ?>
</div>