<?php 
namespace CasinoApp;

class Casino {

    private $casino;
    private $position;

    public function __construct( $casino, $position = 0 ) {
        $this->casino = $casino;
        $this->position = $position;
        $this->stars_html = $this->getStarRating();
        $this->position_html = $this->getPositionHTML();
        $this->cover_id = $this->getCoverId();
        $this->casino_bonus_text = $this->getBonusText();
        $this->casino_affiliate_link = $this->getAffiliateLink();
        $this->review_page_url = $this->getReviewPageUrl();

        return $this;

    }

    public function getPositionHTML() {
        
            /**
             * Badge field
             */
            if( $this->position == 0 ){
                if( $first_place_image = get_field( 'first_place_image', 'option' ) ) {
                    $position_html = wp_get_attachment_image( $first_place_image, 'thumbnail' );
                } else {
                    $position_html = "<img src='". get_stylesheet_directory_uri() . '/assets/img/images/gold-badge.png' ."' class='get-position-img' alt='first_place_image'/>";
                }
            }
            elseif( $this->position == 1 ) {
                if( $second_place_image = get_field( 'second_place_image', 'option' ) ) {
                    $position_html = wp_get_attachment_image( $second_place_image, 'thumbnail' );
                } else {
                    $position_html = "<img src='". get_stylesheet_directory_uri() . '/assets/img/images/silver-badge.png' ."' class='get-position-img' alt='second_place_image'/>";
                }
            }
            elseif( $this->position == 2 ) {
                if( $third_place_image = get_field( 'third_place_image', 'option' ) ) {
                    $position_html = wp_get_attachment_image( $third_place_image, 'thumbnail' );
                } else {
                    $position_html = "<img src='". get_stylesheet_directory_uri() . '/assets/img/images/bronze-badge.png' ."' class='get-position-img' alt='third_place_image'/>";
                }
            }
            else
                $position_html = "#" . ($this->position + 1);

        return $position_html;
            
    }

    public function getCoverId() {

        $casino_main_cover = Options::get_field('cover_image', $this->casino->ID);
        $casino_cover = get_field( 'fcrp_main_screenshot', $this->casino->ID );
        $cover_id = get_field( 'default_casino_image', 'option' );

        if( $casino_main_cover ) {
            $cover_id = $casino_main_cover['ID'];
        } elseif( $casino_cover ) {
            $cover_id = $casino_cover['ID'];
        }

        return $cover_id;
    }

    public function getSmallCoverId() {

        $casino_main_cover = Options::get_field('cover_image_small', $this->casino->ID);

        $cover_id = $casino_main_cover['ID'];

        return $cover_id;
    }

    public function getBonusText() {
        $casino_bonus_text = get_field( 'fcrp_bonus_display_text', $this->casino->ID ) ?: "";
        return $casino_bonus_text;
    }

    public function getBonusAdditionalText() {
        $casino_bonus_text = get_field( 'bonus_display_text', $this->casino->ID ) ?: "";
        return $casino_bonus_text;
    }

    public function getAffiliateLink() {
        $casino_affiliate_link = get_field( 'fcrp_affiliate_referral_url', $this->casino->ID );
        return $casino_affiliate_link;
    }

    public function getReviewPageUrl() {
        $getReviewPageUrl = get_field( 'fcrp_review_page_url', $this->casino->ID );
        return $getReviewPageUrl;
    }

    public function getTitle() {
        return $this->casino->post_title;
    }

    public function getStarRating() {
        $rate = get_field( 'fcrp_editor_rating', $this->casino->ID );

        $rate = floor($rate * 2) / 2;

        $content = "";

        $content .= "<p class='star-rating' aria-label='$rate stars out of 5'>";
            for( $i = 1; $i <= 5; $i++ ) : 
                $fill = "";

                if( $rate == ( $i - 0.5) ) {
                    $fill = 'fill="url(#half)"';
                } elseif( $rate < $i ) {
                    $fill = 'fill="url(#empty)"';
                }
                
                $content .= "<svg class='c-star active' width='32' height='32' viewBox='0 0 32 32'>";
                    $content .= "<use xlink:href='#star' $fill></use>";
                $content .= "</svg>";
            endfor;
        $content .= "</p>";
        
        return $content;
    }

}

?>