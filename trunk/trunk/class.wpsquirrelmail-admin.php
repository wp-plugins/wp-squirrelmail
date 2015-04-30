<?php
// Build the WPSquirrelMail admin menu
class WPSquirrelmail_Admin {
    /**
     * @var WPSquirrelMail_Admin
     */
    private static $instance = null;
    
    /**
     * @var WPSquirrelMail
     */
    private $wpsquirrelmail;
    
    private $admin_settings_page;
    private $user_settings_page;
    private $email_page;

    static function init() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new WPSquirrelmail_Admin();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->wpsquirrelmail = WPSquirrelMail::init();
        wpsquirrelmail_require_lib( 'admin-pages/class.wpsquirrelmail-settings-page' );
        wpsquirrelmail_require_lib( 'user-pages/class.wpsquirrelmail-user-settings-page' );
        wpsquirrelmail_require_lib( 'user-pages/class.wpsquirrelmail-email-page' );
        
        // Initialize objects building settings page
        $this->admin_settings_page = new WPSquirrelMail_Settings_Page();
        $this->user_settings_page = new WPSquirrelMail_User_Settings_Page();
        $this->email_page = new WPSquirrelMail_User_Email_Page();
        
        // Add hooks for admin menus
        if( ! $this->plugin_configured() ) {
            add_action( 'admin_notices', array( $this->admin_settings_page, 'wpsquirrelmail_admin_notices' ) );
        }
        add_action( 'admin_menu', array( $this->admin_settings_page, 'add_actions' ) );
        add_action( 'admin_menu', array( $this->user_settings_page, 'add_actions' ) );
        add_action( 'admin_menu', array( $this->email_page, 'add_actions' ) );
    }
    
    public function plugin_configured() {
        $action = get_option( 'wpsquirrelmail_settings_action' );
        if( ! $action ) {
            return false;
        }
        return true;
    }
}

WPSquirrelmail_Admin::init();