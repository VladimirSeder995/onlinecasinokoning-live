<?php
namespace CasinoApp;

use WP_Query;

class Shortcode extends Base {

    public function __construct()
    {
        $this->register();
        $this->actions();
    }

    private function register() {

        add_shortcode( 'section', [ $this, 'shortcode_section_function' ] );
        add_shortcode( 'casinolist', [ $this, 'shortcode_casinolist_function' ] );
        add_shortcode( 'casino', [ $this, 'shortcode_casino_function' ] );
        add_shortcode( 'section_summary', [ $this, 'shortcode_section_summary_function' ] );
        add_shortcode( 'section_content', [ $this, 'shortcode_section_content_function' ] );
        add_shortcode( 'gamecards', [ $this, 'shortcode_gamecard_function' ] );
        add_shortcode( 'popularcasinos', [ $this, 'shortcode_popularcasinos_function' ] );
        add_shortcode( 'slotdata', [ $this, 'shortcode_slotdata_function' ] );
        add_shortcode( 'cta_blue', [ $this, 'shortcode_cta_blue_function' ] );
        do_action( $this->namespace . 'register_shortcodes', $this );
    }

    private function actions() {
        add_action( 'wp_footer', [ $this, 'footer_html_function'] );
    }

    public function footer_html_function() {
        echo Base::load_template_part( 'stars-base', null);
    }

    public function shortcode_section_summary_function( $args, $content ) {
        return $content;
    }

    public function shortcode_slotdata_function( $args, $content ) {
        $id = isset( $args['id'] ) ? intval($args['id']) : get_the_ID();

        $content = [];

        $group = get_field_object('slot_data', $id);

        $fields = $group['sub_fields'];
        $values = $group['value'];
        $fieldsCount = count($fields) - 1;

        foreach ($fields as $key => $field) {

            $valueArray = $values[$field['name']];
            $buttonClass = $key == $fieldsCount ? 'casino-list-btn' : '';
            
            if( $key == $fieldsCount ) {

                // disabled casinos

                // if( $valueArray['relation'] ) {
                //     $casino = new Casino( $valueArray['relation'] );
                //     $field['label'] = '';
                //     $value = "<a target='_blank' class='$buttonClass' href='". $casino->getAffiliateLink() ."'>Speel ". get_the_title() ."</a>";
                // } else {
                //     $field['label'] = '';
                //     $value = "N/A";
                // }
            } else {
                if( $valueArray['field_type'] == 1 ) {
                    $value = $valueArray['text'];
                } else {
                    if( $valueArray['relation'] ) { // Relation field has been set
                        $value = "<a class='$buttonClass' href='". get_the_permalink( $valueArray['relation'] ) ."'>". get_the_title($valueArray['relation']) ."</a>";
                    } else { // No relation field has been set, use no1 casino
                        $casinoQuery = new WP_Query([
                            'posts_per_page'    => 1, 
                            'post_type'         => 'fcrp_casino',
                            'order_by'          => 'menu_order',
                            'order'             => 'asc',
                        ]);


                        $casinos = $casinoQuery->posts;

                        if( isset($casinos[0]) ) {
                            $casino = new Casino( $casinos[0] );

                            $value = "<a class='$buttonClass' href='". $casino->getAffiliateLink() ."'>". $casino->getTitle() ."</a>";
                        } else {
                            $value = "N/A";
                        }
                    }
                }
            }
  
            $content[] = [
                'label' => $field['label'],
                'value' => $value,
            ];
        }


        return Base::load_template_part( 'slot-data', null, [ 
            'content'   => $content,
        ]);
    }
    public function shortcode_popularcasinos_function( $args, $content ) {

        // disabled casinos
        return false;

        $postsPerPage = isset( $args['per_page'] ) ? intval($args['per_page']) : 4;

        $casinoQuery = new WP_Query([
            'posts_per_page'    => $postsPerPage, 
            'post_type'         => 'fcrp_casino',
            'order_by'          => 'menu_order',
            'order'             => 'asc',
        ]);


        $casinos = $casinoQuery->posts;

        return Base::load_template_part( 'casinolist-widget', null, [ 
            'content'   => $content,
            'casinos'   => $casinos,
        ]);
    }

    public function shortcode_casinolist_function( $args, $content ) {

        // disabled casinos
        return false;

        $postsPerPage = isset( $args['per_page'] ) ? intval($args['per_page']) : 10;
        $orderBy = isset( $args['order_by'] ) ? $args['order_by'] : 'menu_order';
        $order = isset( $args['order'] ) ? $args['order'] : 'ASC';

        $casinoQuery = new WP_Query([
            'posts_per_page'    => $postsPerPage, 
            'post_type'         => 'fcrp_casino',
            'orderby'          => $orderBy,
            'order'             => $order,
        ]);


        $casinos = $casinoQuery->posts;

        return Base::load_template_part( 'casinolist', null, [ 
            'content'   => $content,
            'casinos'   => $casinos,
        ]);
    }

    public function shortcode_casino_function( $args, $content ) {

        $id = isset( $args['id'] ) ? $args['id'] : false;

        $id = explode(',',$id);

        $casinoQuery = new WP_Query([
            'post_type'         => 'fcrp_casino',
            'post__in'          => $id
        ]);

        $casinos = $casinoQuery->posts;

        return Base::load_template_part( 'casinolist', null, [ 
            'content'   => $content,
            'casinos'   => $casinos,
        ]);
    }
    
    public function shortcode_gamecard_function( $args, $content ) {

        $key = isset( $args['key'] ) ? $args['key'] : null;

        if( $key == null ) {
            return 'A key options is required';
        }

        $cards = get_field('game_cards', 'option');
        $games = get_field('games', 'option');

        foreach ($games as $index => $game) { // Check all existing shortcodes
            if( strpos( $game['unique_id'], $key ) != false ) { // Match the currently searched shortcode

                return Base::load_template_part( 'gameslist', null, [ 
                    'games'      => [$game],
                ]);

            }
        }

        foreach ($cards as $index => $card) { // Check all existing shortcodes

            if( strpos( $card['shortcode'], $key ) != false ) { // Match the currently searched shortcode

                if( !empty( $card['cards'] ) ) { // If we have cards selected

                    $selectedCards = $card['cards'];

                    $render_cards = array_filter( $games, function( $game ) use ($selectedCards) {
                        return in_array( $game['unique_id'], $selectedCards );
                    });

                    
                    return Base::load_template_part( 'gameslist', null, [ 
                        'games'      => $render_cards,
                    ]);

                }
            }
        }

        // return Base::load_template_part( 'casinolist', null, [ 
        //     'content'   => $content,
        //     'casinos'   => $casinos,
        // ]);
    }
    
    public function shortcode_section_content_function( $args, $content ) {
        return $content;
    }

    public function shortcode_section_function( $args, $content ) {
        $hasReadMore = has_shortcode($content, 'section_summary') && has_shortcode($content, 'section_content');
        $summary = '';

        if( $hasReadMore ) {
            preg_match("#\[section_summary\](.*?)\[/section_summary\]#s", $content, $summaryMatch);
            // echo '<pre>aaaaaa'; print_r($summaryMatch); echo '</pre>'; 
            preg_match("#\[section_content\](.*?)\[/section_content\]#s", $content, $contentMatch);
            
            $summary = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', force_balance_tags(do_shortcode($summaryMatch[0])));
            $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', force_balance_tags(do_shortcode($contentMatch[0])));
        }

        $sectionClass = ['section-background']; 
        
        if( isset($args['items']) ) {
            if( trim($args['items']) == 'full' ) {
                $sectionClass[] = 'list-items-full'; 
            } elseif( trim($args['items']) == 'half' ) {
                $sectionClass[] = 'list-items-half'; 
            }
        } else {
            $sectionClass[] = 'list-items-full'; 
        }

        if( isset($args['color']) ) {
            if( trim($args['color']) == 'blue' ) {
                $sectionClass[] = 'section-background-blue'; 
            } elseif( trim($args['color']) == 'gray' ) {
                $sectionClass[] = 'section-background-gray'; 
            }
        }

        return Base::load_template_part( 'section', null, [ 
            'content'           => $content,
            'summary'           => $summary,
            'hasReadMore'       => $hasReadMore,
            'sectionClass'      => join( " ", $sectionClass ),
        ]);
    }

    public function shortcode_cta_blue_function( $args, $content ) {

        $cta_link = trim($args['cta_link']);
        $cta_text = trim($args['cta_text']);

        return Base::load_template_part( 'cta_blue', null, [ 
            'content'   => $content,
            'cta_link'  => $cta_link,
            'cta_text'  => $cta_text,
        ]);
    }

}

?>