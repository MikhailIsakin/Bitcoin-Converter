<?php
/**
 * Bitcoin Converter - Currencies
 *
 * @package Bitcoin Converter
 * @since   1.0
 */

class BTCC_Currencies {

    public function __construct() {
        add_filter( 'cron_schedules', array( $this, 'add_recurrence' ) );

        if( ! wp_next_scheduled( 'update_schedule' ) ) {
            wp_schedule_event( time(), 'five_minutes', 'update_schedule' );
        }
        add_action( 'update_schedule', array( $this,'update_currencies' ) );

        add_action( 'wp_save_currencies', array( $this, 'save_currencies' ) );

        add_action( 'wp_ajax_get_currencies', array( $this, 'get_currencies' ) );
        add_action( 'wp_ajax_nopriv_get_currencies', array( $this, 'get_currencies' ) );
    }

    function add_recurrence( $schedules ) {
        $schedules['five_minutes'] = array(
            'interval' => 300,
            'display' => '5 minutes'
        );
        return $schedules;
    }

    function update_currencies() {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = array(
            'start' => '1',
            'convert' => 'BTC'
        );

        $headers = array(
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: a325ccbb-9ce9-470a-97b6-5f078024e50a'
        );
        $qs = http_build_query( $parameters ); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        // Set cURL options

        curl_setopt_array( $curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec( $curl ); // Send the request, save the response
        $data = json_decode( $response ) -> data;

        curl_close( $curl ); // Close request

        do_action( 'wp_save_currencies', $data );
    }

    function save_currencies( $data ) {
        $currencies = array();

        foreach ( $data as &$currency ) {
            $currency_symbol = $currency -> symbol;

            if ($currency_symbol !== 'BTC') {
                $currencies[$currency_symbol] = $currency -> quote -> BTC -> price;
            }
        }

        update_option( 'btcc_currencies', $currencies );
    }

    function get_currencies() {
        $currencies = get_option( 'btcc_currencies' );

        echo json_encode( $currencies );

        die;
    }

    /**
     * Create instance.
     *
     * @return BTCC_Currencies instance.
     */
    public static function get_instance() {
        static $instance;

        if ( ! isset( $instance ) ) {
            $instance = new BTCC_Currencies;
        }

        return $instance;
    }
}