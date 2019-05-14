<?php
/**
 * Bitcoin Converter - Converter
 *
 * @package Bitcoin Converter
 * @since   1.0
 */

class BTCC_Converter {

    public function __construct() {
        add_action( 'wp_head', array( $this, 'add_converter' ) );
    }

    function add_converter() {
        if ( is_home() || is_front_page() ) { ?>
            <div class="btcc">

                <div>
                    <span>BTC</span>
                    <input class="btcc-input" name="btcc_input" type="number" min="0" value="1">
                </div>

                <span>=</span>

                <div>
                    <select class="btcc-select">
                        <option value="1">BTC</option>
                    </select>
                    <span class="btcc-result">1</span>
                </div>

                <div class="btcc-history"></div>
            </div>
        <? }
    }

    /**
     * Create instance.
     *
     * @return BTCC_Converter instance.
     */
    public static function get_instance() {
        static $instance;

        if ( ! isset( $instance ) ) {
            $instance = new BTCC_Converter;
        }

        return $instance;
    }
}