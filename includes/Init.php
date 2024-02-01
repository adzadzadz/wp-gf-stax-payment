<?php 

namespace adz\Stax;

class Init {

    public function __construct() {
        add_action('gform_loaded', [$this, 'init'], 5);
    }

    public function init() {
        if (!class_exists('GFForms')) {
            return;
        }

        add_action( 'wp_enqueue_scripts', function() {
            wp_enqueue_script( 'adz-stax-api', 'https://staxjs.staxpayments.com/stax.js', array(), null, false );
            wp_enqueue_script( 'adz-stax-payment-field-js', ADZ_STAX_PLUGIN_URL . 'assets/js/adz_stax_payment_field.js', array('adz-stax-api'), random_int(000, 999), false );
            // enqueue styles
            wp_enqueue_style( 'adz-stax-css', ADZ_STAX_PLUGIN_URL . 'assets/css/adz_stax_payment_field.css', array(), random_int(000, 999), 'all' );
        });
        
        require_once __DIR__ . '/GF_Payment_Field.php';
        require_once __DIR__ . '/GF_Payment.php';

        \GF_Fields::register(new GF_Payment_Field());
        $payment = new GF_Payment();
    }


}