<?php

// Shared logic between WPSquirrelMail admin pages
abstract class WPSquirrelMail_Admin_Page {
    /**
     * Add page specific actions given the page hook
     */
    abstract function add_page_actions( $hook );
    
    /**
     * Create a menu item for the page and returns the hook
     */
    abstract function get_page_hook();
    
    /**
     * Enqueue and localize page specific scripts
     */
    abstract function page_admin_scripts();
    
    /**
     * Render page specific HTML
     */
    abstract function page_render();
    
    /**
     * Instanciate class WPSquirrelMail init function
     */
    function __construct() {
        $this->wpsquirrelmail = WPSquirrelMail::init();
    }
    
    /**
     * Add actions common to all admin pages
     */
    public function add_actions() {
        /**
	 * Don't add in the modules page unless modules are available!
	 */
	if ( $this->dont_show_if_not_active && ! WPSquirrelMail::is_development_mode() ) {
	//	return;
	}
        
	// Initialize menu item for the page in the admin
	$hook = $this->get_page_hook();
        
	// Attach page specific actions in addition to the above
	$this->add_page_actions( $hook );
    }
    
    /**
     * Render the page with a common top and bottom part, and page specific
     * content
     */
    public function render() {
        $this->page_render();
    }
    
    /**
     * Prints Admin Note asking the Administrator to complete the plugin setup
     */
    public function wpsquirrelmail_admin_notices() {
        echo "<div id='notice' class='updated fade'><p><a href='options-general.php?page=wpsquirrelmail'>WP SquirrelMail</a> is not configured yet. Please do it now.</p></div>\n";
    }
    
    /**
     * Array of roles authorized by the Administrator to use WPSquirrelMail
     * @return array
     */
    public function allowed_roles() {
        $roles = get_editable_roles();
        
        $roleName = array();
        foreach($roles as $role) {
            $roleName[] = $role['name'];
        }
        
        return $roleName;
    }
}