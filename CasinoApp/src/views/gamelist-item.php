
<?php 
$game = $args['game'];
?>

<div class="online-casino-card">
    <div class="card-text-wrap">
         <div class="online-casino-card-img">
        <?php echo wp_get_attachment_image( $game['image'], 'full') ?>
    </div>
     <h2 class="entry-title">
         <?php $title = trim($game['title_override']) != '' ? trim($game['title_override']) : $game['page_relation']->post_title; ?>
        <a href="<?php echo get_the_permalink( $game['page_relation']->ID ) ?>"><?php echo $title; ?></a>
    </h2>
    <p><?php echo $game['excerpt']; ?></p>
    </div>
    <a href="<?php echo get_the_permalink( $game['page_relation']->ID ) ?>" class="tg-read-more"><?php _e('Lees meer') ?></a>
</div>