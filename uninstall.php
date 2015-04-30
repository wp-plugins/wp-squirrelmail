<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

delete_option( 'wpsquirrelmail_user_settings' );
delete_option( 'wpsquirrelmail_settings_action' );
delete_option( 'wpsquirrelmail_settings_capability' );
