<?php
class WPSquirrelMail {
    /**
     * Holds the singleton instance of this class
     * @since 1.0.0
     * @var WPSquirrelMail
     */
    static $instance = false;
    
    /**
     * Singleton
     * @static
     */
    public static function init() {
        if ( ! self::$instance ) {
            if ( did_action( 'plugins_loaded' ) ) {
                self::plugin_textdomain();
            }
            else {
                add_action( 'plugins_loaded', array( __CLASS__, 'plugin_textdomain' ), 99 );
            }
            self::$instance = new WPSquirrelMail;
        }
        return self::$instance;
    }
    
    /**
     * Constructor.  Initializes WordPress hooks
     */
    private function __construct() {
        /*
         * Do things that should run even in the network admin
	 * here, before we potentially fail out.
	 */
	add_filter( 'wpsquirrelmail_require_lib_dir', array( $this, 'require_lib_dir' ) );
        add_filter("plugin_action_links_" . plugin_basename(WPSQUIRRELMAIL__PLUGIN_FILE), 
                array( $this, 'wpsquirrelmail_settings_link' ) );
        
        add_action( "wp_ajax_wpsquirrelmail_login", array( $this, 'my_ajax_handler_email' ) );
        add_action( "wp_ajax_wpsquirrelmail_user", array( $this, 'my_ajax_handler_user' ) );
    }
    
    /**
     * Place a link in the plugins page
     * @param string $links
     * @return string
     */
    public function wpsquirrelmail_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=wpsquirrelmail">Settings</a>';
        array_unshift($links, $settings_link);
        
        return $links; 
    }
    
    /**
     * Load language files
     */
    public static function plugin_textdomain() {
        // Note to self, the third argument must not be hardcoded, to account for relocated folders.
        load_plugin_textdomain( 'wpsquirrelmail', false, dirname( plugin_basename( WPSQUIRRELMAIL__PLUGIN_FILE ) ) . '/languages/' );
    }
        
        
    /**
     * Is WPSquirrelMail active?
     */
    public static function is_active() {
        return (bool) WPSquirrelMail_Data::get_access_token( WPSQUIRRELMAIL_MASTER_USER );
    }
    
    /*
     * Returns the location of WPSquirrelMail's lib directory. This filter is applied
     * in require_lib().
     * @filter require_lib_dir
     */
    function require_lib_dir( $lib_dir ) {
        return WPSQUIRRELMAIL__PLUGIN_DIR . '_inc/lib';
    }
    
    /**
     * Is WPSquirrelMail in development (offline) mode?
     */
    public static function is_development_mode() {
        $development_mode = false;
        
        if ( defined( 'WPSQUIRRELMAIL_DEV_DEBUG' ) ) {
            $development_mode = WPSQUIRRELMAIL_DEV_DEBUG;
        }
        
        elseif ( site_url() && false === strpos( site_url(), '.' ) ) {
            $development_mode = true;
        }
        return apply_filters( 'wpsquirrelmail_development_mode', $development_mode );
    }
    
    function my_ajax_handler_email() {
        // Check for nonce
        check_ajax_referer( 'wpsquerrilmail_login_form' );
        
        try {
            $user_id = get_current_user_id();
            $profile = get_the_author_meta('wpsquirrelmail_user_settings', $user_id );
            $username = esc_attr( $profile['username'] );
            $password = esc_attr( $profile['password'] );
            $autologin = (int) esc_attr( $profile['autologin'] );
            
            $decrypt = new WPSquirrelMail_Encrypt();
            
            $this->loginForm = new WPSquirrelMail_Login_Form();
            $this->loginForm->setAction( get_option( 'wpsquirrelmail_settings_action' ) );
            $this->loginForm->setUsername( $decrypt->getDecrypt( $username ) );
            $this->loginForm->setAutoLogin($autologin);
            $loginForm = $this->loginForm->getForm();
            
            $data['response'] = true;
            $data['content'] = $loginForm;
            $data['password'] = $decrypt->getDecrypt( $password );
            $data['autologin'] = $autologin;
            $data['css'] = plugins_url( "wp-squirrelmail/css/bootstrap.min.css" );
            
            wp_send_json( $data );
            
        } catch (Exception $e) {
            $data['response'] = false;
            $data['message'] = "Unable to load the login form. " . $e->getMessage() . "\n";
            
            wp_send_json( $data );
        }
    }
    
    function my_ajax_handler_user() {
        // Check for nonce
        check_ajax_referer( 'wpsquerrilmail_user_settings' );
        
        try {
            $user_id = get_current_user_id();
            $profile = get_the_author_meta('wpsquirrelmail_user_settings', $user_id );
            $password = esc_attr( $profile['password'] );
            
            $decrypt = new WPSquirrelMail_Encrypt();
            
            $data['response'] = true;
            $data['password'] = $decrypt->getDecrypt( $password );
            
            wp_send_json( $data );
        } catch (Exception $e) {
            $data['response'] = false;
            $data['message'] = "Unable to retrieve the password. " . $e->getMessage() . "\n";
            
            wp_send_json( $data );
        }
    }
}
