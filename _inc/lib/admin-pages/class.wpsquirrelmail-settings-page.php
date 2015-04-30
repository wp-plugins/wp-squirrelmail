<?php
include_once( 'class.wpsquirrelmail-admin-page.php' );

// Builds the settings page and its menu
class WPSquirrelMail_Settings_Page extends WPSquirrelMail_Admin_Page {
    // Show the settings page only when WPSquirrelMail is connected or in dev mode
    protected $dont_show_if_not_active = true;
    
    // Register page actions
    function add_page_actions( $hook ) {
        add_action( "admin_init", array( $this, 'admin_register_settings' ) );
        //remove_action( 'admin_notices', array( $this, 'wpsquirrelmail_admin_notices' ) );
    }
    
    // Adds the Settings sub menu
    function get_page_hook() {
        return add_submenu_page(
                'options-general.php',                             // admin page slug
                __( 'WP SquirrelMail Options', 'wpsquirrelmail' ), // page title
                __( 'WP SquirrelMail', 'wpsquirrelmail' ),         // menu title
                'manage_options',                                  // capability required to see the page
                'wpsquirrelmail',                                  // admin page slug, e.g. options-general.php?page=wpsquirrelmail_options
                array( $this, 'render' )                           // callback function to display the options page
        );
    }
    
    public function page_render() {
        filter_input(INPUT_POST, $settingsUpdated);
        if ( ! isset($settingsUpdated) ) {
            $settingsUpdated = false;
        }
        
        $roles = $this->allowed_roles();
        ?>
<div class="wrap">
    <?php if ( false !== $settingsUpdated ) : ?>
    <div class="updated fade"><p><strong><?php _e( 'WP SquirrelMail Options saved!', 'wpsquirrelmail' ); ?></strong></p></div>
    <?php endif; ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'wpsquirrelmail_options' ); ?>
        <table class="form-table">
            <tr valign="top"><th scope="row"><label class="description" for="wpsquirrelmail_settings_action"><?php _e( 'Form action: ', 'wpsquirrelmail' ); ?></label></th>
                <td>
                    <?php $action = get_option( 'wpsquirrelmail_settings_action' ); ?>
                    <input type='text' name='wpsquirrelmail_settings_action' value='<?php echo $action; ?>' placeholder="Form action attribute" required="required"/>
                    <p class="description">The url to summit the SquirrelMail login form, e.g. "webmail/src/redirect.php"</p>
                </td>
            </tr>
            
            <tr valign="top"><th scope="row"><p class="description"><?php _e( 'Capability: ', 'wpsquirrelmail' ); ?></p></th>
                <td>
                    <?php $allowed_roles = get_option( 'wpsquirrelmail_settings_capability' ); ?>
                    <?php for($i=0;$i<count($roles);$i++): ?>
                    <input type="checkbox" name="wpsquirrelmail_settings_capability[<?php echo $roles[$i]; ?>]" value="1" <?php echo checked( 1, $allowed_roles[$roles[$i]], false ); ?> />
                    <label><?php echo $roles[$i]; ?></label><br />
                    <?php endfor; ?>
                    <p class="description">Select roles that need access to WP SquirrelMail.</p>
                </td>
            </tr>
            <tr valign="top"><th scope="row"></th>
                <td>
                    <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
                </td>
            </tr>
        </table>
    </form>
</div>
        <?php
    }
    
    // Javascript logic specific to the list table
    function page_admin_scripts() {}
    
    function admin_register_settings() {
        // ('Settings section', 'Seting Name')
        register_setting( 'wpsquirrelmail_options', 'wpsquirrelmail_settings_action' );
        register_setting( 'wpsquirrelmail_options', 'wpsquirrelmail_settings_capability' );
    }
}
