<?php

// Shared logic between WPSquirrelMail user pages
abstract class WPSquirrelMail_User_Page {
    // Add page specific actions given the page hook
    abstract function add_page_actions( $hook );
    
    // Create a menu item for the page and returns the hook
    abstract function get_page_hook();
    
    // Enqueue and localize page specific scripts
    abstract function page_user_scripts();
    
    // Render page specific HTML
    abstract function page_render();
    
    function __construct() {
        $this->wpsquirrelmail = WPSquirrelMail::init();
    }
        
function add_actions() {
    // Check if user belongs to an authorized role
    if( ! $this->is_allowed() ) {
        return false;
    }
    
    // Initialize menu item for the page in the user
    $hook = $this->get_page_hook();
    
    // Attach hooks common to all WPSquirrelMail admin pages based on the created
    add_action( "admin_print_styles-$hook",  array( $this, 'admin_styles'    ) );
    add_action( "admin_print_scripts-$hook", array( $this, 'admin_scripts'   ) );
    
    // Attach page specific actions in addition to the above
    $this->add_page_actions( $hook );
}
    
    // Render the page with a common top and bottom part, and page specific
    // content
    function render() {
        //$this->user_page_top();
        $this->page_render();
        //$this->user_page_bottom();
    }
    
    function user_page_top() {
        include_once( WPSQUIRRELMAIL__PLUGIN_DIR . '_inc/header.php' );
    }
    
    function user_page_bottom() {
        include_once( WPSQUIRRELMAIL__PLUGIN_DIR . '_inc/footer.php' );
    }
    
    // Add page specific scripts and jetpack stats for all menu pages
    function admin_scripts() {
        $this->page_user_scripts(); // Delegate to inheriting class
    }
    
    // Enqueue the Jetpack admin stylesheet
    function admin_styles() {
        wp_enqueue_style( 'wpsquirrelmail-admin', plugins_url( "css/bootstrap.min.css", WPSQUIRRELMAIL__PLUGIN_FILE ), false, WPSQUIRRELMAIL__VERSION . '-3.3.4', 'all' );
    }
    
    private function is_allowed() {
        $roles = array_keys( get_option( 'wpsquirrelmail_settings_capability') );
        $user = wp_get_current_user();
        $user_role = $user->roles;
        
        foreach($roles as $key) {
            $allowed_roles[] = strtolower($key);
        }
        
        if( !array_intersect($allowed_roles, $user_role ) ) {
            return false;
        }
        return true;
    }
}