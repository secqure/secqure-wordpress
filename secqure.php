<?php

/**
 * Plugin Name: Secqure Passwordless Authentication
 * Description: Secqure provides a secure and delightful user experience 
 * Version: 1.0
 * Author: Secqure Team
 * Author URI: https://secqure.io
 * License: GPLv2+
 */
if (!defined('ABSPATH')) {
    exit();
}

define('SECQURE_ROOT_DIR', plugin_dir_path(__FILE__));
define('SECQURE_ROOT_URL', plugin_dir_url(__FILE__));
define('SECQURE_PLUGIN_VERSION', '1.0');
define('SECQURE_ROOT_SETTING_LINK', plugin_basename(__FILE__));


if (!class_exists('secqurePlugin')) {

    /**
     * Main class
     */
    class secqurePlugin
    {
        public function __construct()
        {
            require_once(SECQURE_ROOT_DIR."admin/admin.php");
            require_once(SECQURE_ROOT_DIR."frontend/pages/auth-flow.php");
        }

        public static function reset_share_options()
        {
            update_option('secqure_option', '');
        }
        
        public static function data_validation($key, $post){
            return isset($post[$key]) && !empty($post[$key]) ? sanitize_text_field(esc_html(trim($post[$key]))) : false;
        }
        
    }
    new secqurePlugin();
}
