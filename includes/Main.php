<?php
/**
 * Bitcoin Converter - Main
 *
 * @package Bitcoin Converter
 * @since   1.0
 */

// Include the BTCC_Currencies class.
if ( ! class_exists( 'BTCC_Currencies' ) ) {
    include_once dirname( __FILE__ ) . '/Currencies.php';
}

// Include the BTCC_Converter class.
if ( ! class_exists( 'BTCC_Converter' ) ) {
    include_once dirname( __FILE__ ) . '/Converter.php';
}

class BTCC_Main {

    public function __construct() {
        add_action( 'wp_enqueue_scripts',  array( $this, 'btcc_enqueue_assets' ) );
    }

    function btcc_enqueue_assets() {
        wp_enqueue_style( 'wp-btcc-css', plugin_dir_url( __FILE__ ) . '../assets/css/wp-btcc.css' );
        wp_enqueue_script( 'wp-btcc', plugin_dir_url( __FILE__ ) . '../assets/js/wp-btcc.js', array( 'jquery' ) );
    }

    /**
     * Create instance.
     *
     * @return BTCC_Main instance.
     */
    public static function get_instance() {
        static $instance;

        if ( ! isset( $instance ) ) {
            $instance = new BTCC_Main;
        }

        return $instance;
    }
}

/**
 * Currencies instance.
 *
 * Returns the Currencies instance of plugin to prevent the need to use globals.
 *
 * @return BTCC_Currencies
 */
if( ! function_exists( 'wp_btcc_currencies' ) ) {
    function wp_btcc_currencies() {
        return BTCC_Currencies::get_instance();
    }

    wp_btcc_currencies();
}

/**
 * Converter instance.
 *
 * Returns the Converter instance of plugin to prevent the need to use globals.
 *
 * @return BTCC_Converter
 */
if( ! function_exists( 'wp_btcc_converter' ) ) {
    function wp_btcc_converter() {
        return BTCC_Converter::get_instance();
    }

    wp_btcc_converter();
}