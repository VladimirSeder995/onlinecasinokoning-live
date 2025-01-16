<?php 

namespace CasinoApp;

class Options extends Base {

    private $group_id = 'group_5644bb5babab9';
    protected static $field_key_prefix = 'fcrp_fields_addon_';

    public $fields;

    public function __construct() {
        $this->fields = $this->includeCustom( "/config/fields.php" );
        apply_filters('fcrp_casinokoning_fields', $this->fields);

        $this->register_options_page();
        $this->filters();
        $this->actions();

        $this->register_fields();
    }

    public function filters() {
        add_filter('acf/settings/show_admin', '__return_true');
    }

    public function actions() {
        add_filter('init', [ $this, 'register_fields' ]);
    }

    public function register_options_page() {
        if( function_exists('acf_add_options_page') ) {
            
            acf_add_options_page(array(
                'page_title' 	=> 'Theme General Settings',
                'menu_title'	=> 'Theme Settings',
                'menu_slug' 	=> 'theme-general-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ));

            acf_add_options_page(array(
                'page_title' 	=> 'Author Settings',
                'menu_title'	=> 'Author Settings',
                'menu_slug' 	=> 'author-custom-settings',
                'capability'	=> 'manage_options',
                'redirect'		=> false
            ));

            acf_add_options_page(array(
                'page_title' 	=> 'Compare',
                'menu_title'	=> 'Compare',
                'menu_slug' 	=> 'theme-compare-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ));

            acf_add_options_page(array(
                'page_title' 	=> 'OL Settings',
                'menu_title'	=> 'OL Settings',
                'menu_slug' 	=> 'ol-custom-settings',
                'capability'	=> 'manage_options',
                'redirect'		=> false
            ));
        
            acf_add_options_page(array(
                'page_title' 	=> 'UL Settings',
                'menu_title'	=> 'UL Settings',
                'menu_slug' 	=> 'ul-custom-settings',
                'capability'	=> 'manage_options',
                'redirect'		=> false,
                'parent_slug'	=> 'ol-custom-settings',
            ));
        
            acf_add_options_page(array(
                'page_title' 	=> 'Table Settings',
                'menu_title'	=> 'Table Settings',
                'menu_slug' 	=> 'table-custom-settings',
                'capability'	=> 'manage_options',
                'redirect'		=> false,
                'parent_slug'	=> 'ol-custom-settings',
            ));
        }
    }

    public function register_fields() {

        // If $this->fields is not array, exit;
        if( !is_array($this->fields) ) return;

        $defaults = [
            'instructions'      => '',
            'required'          => 0,
            'conditional_logic' => 0,
            'wrapper'           => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value'     => '',
            'placeholder'       => '',
            'prepend'           => '',
            'append'            => '',
            'maxlength'         => '',
            'readonly'          => 0,
            'disabled'          => 0,
        ];

        foreach ($this->fields as $key => $new_field) {
            $field = [];

            $fieldOptions = array_merge( $defaults, isset($new_field['options']) ? $new_field['options'] : [] );

            $field = array_merge( $fieldOptions, $new_field );

            unset( $field['options'] );

            $field['parent'] = $this->group_id;

            $field['key'] = self::$field_key_prefix . $field['key'];

            if( function_exists('acf_add_local_field'))
                acf_add_local_field($field);
        }
    }

    public static function get_field( $name, $post_id ) {
        return get_field( self::$field_key_prefix . $name, $post_id );
    }
}