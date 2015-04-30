<?php
/**
 * Plugin Name: wp-squirrelmail.
 * Plugin URI: https://wordpress.org/plugins/wp-squirrelmail/
 * Description: Connect to SquirrelMail from withiin WordPress.
 * Version: 1.0
 * Author: Edgar Hernandez
 * Author URI: http://edgarjhernandez.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpsquirrelmail
 * Domain Path: /languages/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * 
 * WP-SquirrelMail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * WP-SquirrelMail is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with WP-SquirrelMail. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

defined( 'ABSPATH' ) or die( "I'm sorry, Dave, I'm afraid I can't do that" );

define( 'WPSQUIRRELMAIL__VERSION',            '1.0' );
define( 'WPSQUIRRELMAIL_MASTER_USER',         true );
define( 'WPSQUIRRELMAIL__PLUGIN_DIR',         plugin_dir_path( __FILE__ ) );
define( 'WPSQUIRRELMAIL__PLUGIN_FILE',        __FILE__ );

if ( is_admin() ) {
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'require-lib.php'              );
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class.wpsquirrelmail.php'     );
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class.wpsquirrelmail-admin.php' );
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class.wpsquirrelmail-encrypt.php' );
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class.wpsquirrelmail-login-form.php' );
    
    add_action( 'init', array( 'WPSquirrelMail', 'init' ) );
}
