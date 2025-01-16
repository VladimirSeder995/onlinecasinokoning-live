
<?php 
$casino = $args['casino'];
?>
<div class="casino-list-item">
    <div class="casino-list-item-inner">
        <div class="casino-list-item-cell">
            <?php echo $casino->getPositionHTML(); ?>
        </div>
        <div class="casino-list-item-cell">
            <div class="casino-list-img">
                <a href="<?php echo $casino->getAffiliateLink(); ?>">
                    <?php echo wp_get_attachment_image( $casino->getCoverId(), 'fcrp-casino-logo', false, array('class' => 'casino-list-img-img') ); ?>
                </a>
            </div>
            <div class="mobile-rating">
                 <?php echo $casino->getStarRating(); ?>
            </div>
        </div>
        <div class="casino-list-item-cell">
            <?php if( $casino->getBonusText() ) : ?>
                <p class="casino-list-price"><?php echo $casino->getBonusText(); ?></p>
            <?php endif; ?>

            <?php if( $casino->getBonusAdditionalText() ) : ?>
                <p class="casino-list-price"><?php echo $casino->getBonusAdditionalText(); ?></p>
            <?php endif; ?>
        </div>
        <div class="casino-list-item-cell">
            <div class="casino-review">
                <a href="<?php echo $casino->getReviewPageUrl(); ?>"><?php echo $casino->getTitle(); ?> Review</a>
                <?php echo $casino->getStarRating(); ?>
            </div>
        </div>
        <div class="casino-list-item-cell">
            <a target="_blank" class="casino-list-btn" href="<?php echo $casino->getAffiliateLink(); ?>">Speel nu</a>
        </div>
    </div>
</div>