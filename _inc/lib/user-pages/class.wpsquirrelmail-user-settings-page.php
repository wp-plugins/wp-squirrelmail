<?php
include_once( 'class.wpsquirrelmail-user-page.php' );

// Builds the settings page and its menu
class WPSquirrelMail_User_Settings_Page extends WPSquirrelMail_User_Page {
    // Show the settings page only when WPSquirrelMail is connected or in dev mode
    protected $dont_show_if_not_active = true;
    
    // Register page actions
    function add_page_actions( $hook ) {
        // Only add_action when the form is submitted
        if ( $this->settingsUpdated() ) {
            add_action( "admin_init", array( $this, 'user_register_settings' ) );
        }
    }
    
    // Adds the Settings sub menu
    function get_page_hook() {
        return add_submenu_page(
            'users.php',
            __( 'WP SquirrelMail User Settings', 'wpsquirrelmail' ),
            __( 'WPSquirrelMail Settings', 'wpsquirrelmail' ),
            'read',
            'wpsquirrelmail',
            array( $this, 'render' )
        );
    }
    
    public function page_render() {
        $user_id = wp_get_current_user()->id;
        $profile = get_the_author_meta('wpsquirrelmail_user_settings', $user_id );
        $decrypt = new WPSquirrelMail_Encrypt();
        ?>
<div class="wrap">
    <?php if ( false !== $this->settingsUpdated() ) : ?>
    <div class="updated fade"><p><strong><?php _e( 'WP SquirrelMail User Settings saved!', 'wpsquirrelmail' ); ?></strong></p></div>
    <?php endif; ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form method="post" action="users.php?page=wpsquirrelmail">
        <?php settings_fields( 'wpsquirrelmail_user_options' ); ?>
        <table class="form-table">
            <tr valign="top"><th scope="row"><label class="description" for="username"><?php _e('User Name', 'wpsquirrelmail'); ?></label></th>
                <td>
                    <?php $username = esc_attr( $profile['username'] ); ?>
                    <input type="text" name="username" id="username" value="<?php echo $decrypt->getDecrypt($username); ?>" placeholder="Username" /><br />
                    <p class="description"><?php _e('Please enter your username.', 'wpsquirrelmail'); ?></p>
                </td>
            </tr>
            <tr valign="top"><th scope="row"><label class="description" for="password"><?php _e('Password', 'wpsquirrelmail'); ?></label></th>
                <td>
                    <?php $password = esc_attr( $profile['password'] ); ?>
                    <input type="password" name="password" id="password" value="<?php //echo $decrypt->getDecrypt($password); ?>" placeholder="Password" /><br />
                    <p class="description"><?php _e('Please enter your password.', 'wpsquirrelmail'); ?></p>
                </td>
            </tr>
            <tr valign="top"><th scope="row"><label class="description" for="autologin"><?php _e('Autologin', 'wpsquirrelmail'); ?></label></th>
                <td class="description">
                    <?php $autologin = esc_attr( $profile['autologin'] ); ?>
                    <input type="checkbox" name="autologin" id="autologin" value="1" <?php echo checked( 1, $autologin, false ); ?> />
                    <?php _e('WPSquirrelMail automatic Login.', 'wpsquirrelmail'); ?>
                    <p><?php _e('Username and Password required to select this option'); ?>.</p>
                </td>
            </tr>
            <tr valign="top"><th scope="row"></th>
                <td>
                    <p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php
    }
    
    // Javascript logic specific to the list table
    function page_user_scripts() {
        wp_enqueue_script(
                'wpsquirrelmail-user-settings-js', 
                plugins_url( '_inc/wpsquirrelmail-user-settings.js', WPSQUIRRELMAIL__PLUGIN_FILE ), 
                array( 'jquery' ), WPSQUIRRELMAIL__VERSION . '-1'
        );
        
        wp_localize_script( 'wpsquirrelmail-user-settings-js', 'my_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wpsquerrilmail_user_settings' ),
        ));
    }
    
    function user_register_settings() {
        $user_id = wp_get_current_user()->id;
        $post = $_POST;
        
        $encrypt = new WPSquirrelMail_Encrypt();
        $profile_options = array(
            'username'  => $encrypt->getEncrypt( $post['username'] ),
            'password'  => $encrypt->getEncrypt( $post['password'] ),
            'autologin' => $post['autologin']
        );
        update_usermeta( $user_id, 'wpsquirrelmail_user_settings', $profile_options);
    }
    
    function settingsUpdated() {
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }
        return true;
    }
}
