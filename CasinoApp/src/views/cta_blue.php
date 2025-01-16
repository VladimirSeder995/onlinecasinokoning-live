<div class="blue-cta">
    <p><?php echo $args['content']; ?></p>
    <?php if( $args['cta_link'] ) : ?>
        <a href="<?php echo $args['cta_link']; ?>" class="casino-list-btn"><?php echo $args['cta_text'] != '' ? $args['cta_text'] : 'Speel nu'; ?></a>
    <?php endif; ?>
</div>