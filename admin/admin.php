<?php
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('secqure_Admin')) {

    class secqure_Admin
    {
        public function __construct()
        {
            if (is_admin()) {
                add_action('admin_init', array($this, 'configure_plugin_settings'));
            }
            add_action('admin_menu', array($this, 'create_settings_menu'));
            add_filter('plugin_action_links', array($this, 'secqure_setting_link'), 10, 2);
            add_action('secqure_reset_admin_action', array($this, 'reset_settings'), 10, 2);
            add_action('admin_enqueue_scripts', array($this, 'add_styles_to_admin'));
            add_action('personal_options_update', array($this, 'disable_users_email_change_BACKEND'), 5);
            add_action('show_user_profile', array($this, 'disable_users_email_change_HTML'));
        }
        
        // Configure plugin settings
        public function configure_plugin_settings()
        {
            register_setting('secqure_option', 'secqure_option', array($this,'validate_plugin_settings'));
        }
        
        // Validate settings
        public function validate_plugin_settings($input)
        {
            $message = null;
            $type = null;
            if (!$input) {
                $input = array();
            }
            if (null != $input) {
                if (!isset($input['apikey']) || empty($input['apikey'])) {
                    $message = __('API Key is mandatory');
                    $type = 'error';
                } elseif (!isset($input['redirect_uri']) || empty($input['redirect_uri'])) {
                    $message = __('Redirect URL is mandatory');
                    $type = 'error';
                } elseif (get_option('secqure_option')) {
                    $message = __('Settings updated!');
                    $type = 'updated';
                } else {
                    $message = __('Settings added!');
                    $type = 'updated';
                }
            } else {
                $message = __('Something went wrong');
                $type = 'error';
            }
            
            add_settings_error('secqure_option_notice', 'secqure_option', $message, $type);
            return $input;
        }

        public static function reset_settings($option, $settings)
        {
            if (current_user_can('manage_options')) {
                update_option($option, $settings);
            }
        }

        public function create_settings_menu()
        {
            add_menu_page('secqure', 'SecQure', 'manage_options', 'secqure', array('secqure_Admin', 'settings_page'), SECQURE_ROOT_URL . 'admin/assets/images/SecqureIcon.png');
        }

        //  Add settings link in the Plugins page,
        public function secqure_setting_link($links, $file)
        {
            static $thisPlugin = '';
            if (empty($thisPlugin)) {
                $thisPlugin = SECQURE_ROOT_SETTING_LINK;
            }
            if ($file == $thisPlugin) {
                $settingsLink = '<a href="admin.php?page=secqure">' . __('Settings', 'secqure') . '</a>';

                array_unshift($links, $settingsLink);
            }
            return $links;
        }
        
        //  Add Styling on plguin Admin Page
        public function add_styles_to_admin()
        {
            wp_enqueue_style('secqure-admin-style', SECQURE_ROOT_URL . 'admin/assets/css/style.css', false, SECQURE_PLUGIN_VERSION);
        }

        public static function settings_page()
        {
            require_once(SECQURE_ROOT_DIR . "admin/views/settings.php");
        }
        
        //  Configure email field to be disabled during update
        public function disable_users_email_change_BACKEND($user_id)
        {
            if (!current_user_can('manage_options')) {
                $user = get_user_by('id', $user_id);
                $_POST['email']=$user->user_email;
            }
        }
        //  Configure email field to be disabled on profile page
        public function disable_users_email_change_HTML($user)
        {
            if (!current_user_can('manage_options')) {
                echo '<script>document.getElementById("email").setAttribute("disabled","disabled");</script>';
            }
        }
    }
    new secqure_Admin();
}
