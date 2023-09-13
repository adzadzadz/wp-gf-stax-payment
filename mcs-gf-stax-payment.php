<?php
/**
 * Plugin Name: GF Stax Payment
 * Plugin URI: http://mycustomsoftware.com/
 * Description: Gravity Forms Stax Payment extension
 * Version: 1.0.0
 * Author: MCS
 * Author URI: http://mycustomsoftware.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 3.0.0
 * WC tested up to: 5.9.0
 * Text Domain: mcs-gf-stax-payment
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Simple php autoloader
spl_autoload_register(function ($class) {
    $prefix = 'MCS\\Stax\\';
    $base_dir = __DIR__ . '/includes/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

define('MCS_STAX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MCS_STAX_PLUGIN_URL', plugin_dir_url(__FILE__));

// check if GRAVITY_FORMS_PLUGIN_URL is defined
if (!defined('GRAVITY_FORMS_PLUGIN_URL')) {
    define('GRAVITY_FORMS_PLUGIN_URL', WP_PLUGIN_URL . '/gravityforms');
}

// include the gravity forms library
// require_once GRAVITY_FORMS_PLUGIN_URL . '/forms_model.php';
// require_once GRAVITY_FORMS_PLUGIN_URL . '/form_display.php';
// require_once GRAVITY_FORMS_PLUGIN_URL . '/entry_list.php';

// require_once './includes/Payment.php';

$init = new \MCS\Stax\Init();