<?php 
/**
 * Override default theme functionalities and extend it.
 */
namespace CasinoApp;

class Application {

    public function __construct() {
        new Shortcode();
        new Options();
    }

}

new Application();

?>