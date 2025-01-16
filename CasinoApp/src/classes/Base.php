<?php 
namespace CasinoApp;

class Base extends Application{

    public $namespace = 'onlinecasinoconing_';

    public function getNamespace()
    {
        return $this->namespace;
    }

    public static function load_template_part( $template_name, $part_name = null, $arguments = [], $start_ob = true ) {
        if( $start_ob ) {
            ob_start();
        }
        
        get_template_part( 'CasinoApp/src/views/' . $template_name, $part_name, $arguments );
        
        if( $start_ob ) {
            $var = ob_get_contents();
            ob_end_clean();
            return $var;
        }
    }

    public function includeCustom( $path ) {
        include get_stylesheet_directory() . '/CasinoApp/src' . $path;

        return $fields;
    }

}
?>