<?php
/**
 * File that's included each time we call get_search_form function
 *
 * @package ATM
 */

?>

<div class="desktop-search-wrap">
	<div class="desktop-search-inner">
		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label>
				<span class="screen-reader-text">Zoeken naar:</span>
				<input autocomplete="off" type="search" class="search-field" placeholder="Zoeken …" value="<?php echo get_search_query(); ?>" name="s">
				<span class="mobile-search-submit"></span>
			</label>
			<input type="submit" class="search-submit" value="Zoeken">
		</form>
		<div class="desktop-search-results">
		</div><!-- .desktop-search-results -->
	</div><!-- .desktop-search-inner -->
</div><!-- .desktop-search-wrap -->