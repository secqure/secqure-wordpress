<?php
// Exit if called directly
if (!defined('ABSPATH')) {
    exit();
}
?>
<div id="secqure_admin">
    <div class="secqure_logo">
        <img src="<?php echo SECQURE_ROOT_URL . 'admin/assets/images/Secqure.png'?>" alt="Secqure" title="Secqure">
    </div>
    <br/>
    <?php
    settings_errors();
    ?><br/>
    <div class="secqure_config">
        <form method="post" action="options.php"> 
            <?php
            $secqure_option = get_option('secqure_option');
            settings_fields('secqure_option');?>
            <div class="secqure_field">
                <label for="secqure_apikey">
                <?php _e('APIkey:','secqure');?>
                </label>
                <input type="text" id="secqure_apikey" name="secqure_option[apikey]" value="<?php echo isset($secqure_option['apikey'])?esc_attr($secqure_option['apikey']):"";?>">
				<div class="secqure_help_text"><?php _e('Visit <a href="https://secqure.io" target="_blank">SecQure</a> to get your API key.','secqure');?></div>

                <label for="secqure_redirect_uri">
                <?php _e('Redirect URI:','secqure');?>
                </label>
                <input type="text" id="secqure_redirect_uri" name="secqure_option[redirect_uri]" value="<?php echo isset($secqure_option['redirect_uri'])?esc_attr($secqure_option['redirect_uri']):"";?>">
                <div class="secqure_help_text"><?php _e('User will be redirected to this page after successful login.','secqure');?></div>
                
                <div class="secqure_verification_message" style="display:none;"></div>
            </div>
            
            <hr>
            <div class="secqure_field">
                <?php submit_button(); ?>
            </div>
        </form>
    </div>
</div>