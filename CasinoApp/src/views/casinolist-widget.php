
<ul class="casino-sidebar-list">
    <?php 
    foreach( $args['casinos'] as $position => $casino ) {
        CasinoApp\Base::load_template_part( 'casinolist-widget-item', null, [
            'casino'    => new CasinoApp\Casino( $casino, $position ),
        ], false);
    }
    ?>
</ul>