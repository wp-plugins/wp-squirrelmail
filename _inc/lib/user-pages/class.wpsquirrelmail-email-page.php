<?php
include_once( 'class.wpsquirrelmail-user-page.php' );

// Builds the settings page and its menu
class WPSquirrelMail_User_Email_Page extends WPSquirrelMail_User_Page {
    // Show the settings page only when WPSquirrelMail is connected or in dev mode
    protected $dont_show_if_not_active = true;
    
    // Register page actions
    function add_page_actions( $hook ) {}
    
    // Adds the Settings sub menu
    function get_page_hook() {
        //$page_hook_suffix = add_users_page(
        return add_submenu_page(
            'users.php',
            __( 'WP SquirrelMail User Settings', 'wpsquirrelmail' ),
            __( 'Mail', 'wpsquirrelmail' ),
            'read',
            'wpsquirrelmail-email',
            array( $this, 'render' )
            );
    }
    
    public function page_render() {
        ?>
<div class="container-fluid">
    <div class='row'>
        <header>
            <h3 class='text-center'><?php _e('WP SquirrelMail', 'wpsquirrelmail'); ?></h3>
        </header>
    </div>
    <div class="row wpsquirrelmail" id="wpsquirrelmail-div">
        <p>Loading...</p>
    </div>
</div>

<?php
    }
    
// Javascript logic specific to the list table
    function page_user_scripts() {
        wp_enqueue_script(
                'wpsquirrelmail-email-js',
                plugins_url( '_inc/wpsquirrelmail-email.js', WPSQUIRRELMAIL__PLUGIN_FILE ), array( 'jquery' ),
                WPSQUIRRELMAIL__VERSION . '-1'
        );
        
        wp_localize_script( 'wpsquirrelmail-email-js', 'my_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wpsquerrilmail_login_form' ),
        ));        
    }
}
