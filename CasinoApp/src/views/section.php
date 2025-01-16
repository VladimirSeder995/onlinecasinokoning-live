
<div class="<?php echo $args['sectionClass']; ?>">
    <div class="section-background-inner">
        <?php if( $args['hasReadMore'] ) : ?>
            <div class="section-summary">
                <?php echo $args['summary']; ?>
            </div>
            <div class="section-content" style='display: none'>
                <?php echo $args['content']; ?>
            </div>
            <div class="section-summary-read-more">
                <span class="section-summary-read-more-close"><?php _e('Lees meer') ?></span>
                <span class="section-summary-read-more-open" style='display: none'><?php _e('Toon minder') ?></span>
            </div>

        <?php else : ?>
            <div class="section-content">
                <?php echo $args['content']; ?>
            </div>
        <?php endif; ?>
    </div>
</div>