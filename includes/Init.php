<?php 

namespace MCS\Stax;

class Init {

    public function __construct() {
        add_action('gform_loaded', [$this, 'init'], 5);
    }

    public function init() {
        if (!class_exists('GFForms')) {
            return;
        }

        add_action( 'wp_enqueue_scripts', function() {
            wp_enqueue_script( 'mcs-stax-api', 'https://staxjs.staxpayments.com/stax.js?nocache=2', array(), null, false );
            wp_enqueue_script( 'mcs-stax-payment-field-js', MCS_STAX_PLUGIN_URL . 'assets/js/mcs_stax_payment_field.js', array(), null, false );
            // enqueue styles
            wp_enqueue_style( 'mcs-stax-css', MCS_STAX_PLUGIN_URL . 'assets/css/mcs_stax_payment_field.css', array(), null, 'all' );
        });
        
        require_once __DIR__ . '/GF_Payment_Field.php';
        require_once __DIR__ . '/GF_Payment.php';

        \GF_Fields::register(new GF_Payment_Field());
        $payment = new GF_Payment();
    }


}