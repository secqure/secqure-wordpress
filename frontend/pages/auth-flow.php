<?php
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('secqure_Front')) {

    class secqure_Front
    {

        public function __construct()
        {
            add_action('login_enqueue_scripts', array($this,'secqure_enqueue_script'), 10);
            add_action('wp_ajax_secqure_login', array($this,'secqure_login'));
            add_action('wp_ajax_nopriv_secqure_login', array($this,'secqure_login'));

        }

        /**
         * create and generate login form
         */
        public function secqure_enqueue_script()
        {
            ?>
            <style type="text/css">#login{display:none;}#secqure-passwordless-form {margin: 6% auto;width: 405px;}</style>
            <style type="text/css">#secuuthForm#secuuthForm {margin: 6% auto;width: 300px;}</style>
            <?php
            $secqure_option = get_option('secqure_option');
            $apikey = isset($secqure_option["apikey"]) && !empty($secqure_option["apikey"])?trim($secqure_option["apikey"]):"";
            $redirect_uri = isset($secqure_option["redirect_uri"]) && !empty($secqure_option["redirect_uri"])?trim($secqure_option["redirect_uri"]):"";
            
            wp_enqueue_script('secqure-js', 'https://dev.secuuth.io/JS/prod/Secuuth.bundle.js', false, SECQURE_PLUGIN_VERSION);
            // wp_enqueue_script('secqureajax-script');
            // wp_register_script('secqureajax-script', SECQURE_ROOT_URL . 'frontend/assets/js/loginpage.js', array('secqure-js', 'jquery'));
            wp_enqueue_style('secqure-js', SECQURE_ROOT_URL . 'admin/assets/css/style.css', false, SECQURE_PLUGIN_VERSION);
            wp_enqueue_script('secqureajax-script', SECQURE_ROOT_URL . 'frontend/assets/js/loginpage.js', array('secqure-js', 'jquery'), SECQURE_PLUGIN_VERSION);
            // wp_enqueue_style('secqure-js', SECQURE_ROOT_URL . 'admin/assets/css/style.css', false, SECQURE_PLUGIN_VERSION);
            wp_localize_script('secqureajax-script', 'secqureajax', 
                array(
                    'ajax_url' => admin_url('admin-ajax.php'), 
			        'apikey' => $apikey, 
                    'redirect' => $redirect_uri,
                    'csslink' =>  SECQURE_ROOT_URL
                    )
                );
        }

        public function secqure_login()
        { 
            $accessToken = secqurePlugin::data_validation('accessToken', $_POST);
            $userId = secqurePlugin::data_validation('userId', $_POST);
            if (!empty($accessToken) && !empty($userId)) {
                require_once(__DIR__ . '/../../vendor/autoload.php');
                $jwtUtils = new ValidateToken();
                $decodedToken = $jwtUtils->decodeToken($accessToken);
                if($decodedToken->userId)
                    $this->wp_login($userId);
            }
            wp_die();
        }
        
		private function wp_login($userId){
			$user = get_user_by('login', $userId);
			if (!$user) {
				//create user in wp database
				$result = wp_create_user($userId, $userId, $userId);
				if (is_wp_error($result)) {
					$error = $result->get_error_message();
				} else {
					$user = get_user_by('id', $result);
				}
			}
			//login user
			wp_clear_auth_cookie();
			wp_set_auth_cookie($user->ID, true);
			wp_set_current_user($user->ID);
			do_action('wp_login', $user->user_login, $user);
		}
        
    }
    
    new secqure_Front();
}
