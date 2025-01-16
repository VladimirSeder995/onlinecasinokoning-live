
<div class="casino-list-wrapper">
    <div class="casino-list-wrapper-inner">
        <?php 
        foreach( $args['casinos'] as $position => $casino ) {
            CasinoApp\Base::load_template_part( 'casinolist-item', null, [
                'casino'    => new CasinoApp\Casino( $casino, $position ),
            ], false);
        }
        ?>
    </div>
</div>