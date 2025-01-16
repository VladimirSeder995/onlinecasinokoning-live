<div class="online-casino-gallery">
    <div class="online-casino-gallery-inner">
        <?php 
        foreach ($args['games'] as $key => $game) { // Iterate through all games are created
            CasinoApp\Base::load_template_part( 'gamelist-item', null, [
                'game'    => $game,
            ], false);
        }
        ?>
    </div>
</div>
