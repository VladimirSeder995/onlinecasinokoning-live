
<?php 
$casino = $args['casino'];
?>
<li>
    <a class='casino-widget-item' href="<?php echo $casino->getAffiliateLink(); ?>" rel="nofollow">
        <?php echo wp_get_attachment_image( $casino->getSmallCoverId(), 'thumbnail', false, array('class' => 'casino-widget-item-img no-lazy') ); ?>
        <div>
            <p class="casino-widget-item-inner"><strong><?php echo $casino->getTitle(); ?></strong></p>
            <p class="casino-widget-item-inner"><?php echo $casino->getBonusAdditionalText(); ?></p>
        </div>
    </a>
</li>