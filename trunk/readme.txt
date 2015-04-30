=== WP-SquirrelMail ===
Contributors: edgarr41
Donate link: http://example.com/
Tags: squirrelmail, email, mail
Requires at least: 3.0.1
Tested up to: 4.2.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect to your SquirrelMail installation from within WordPress.

== Description ==

WP-SquirrelMail creates an iframe inside WordPress to display emails from your SquirrelMail installation.
The admin tab allows the administrator to select witch user's roles can access the plugin.  It also requires you to specify the URL for the SquirrelMail installation.
Users granted rights to check email via WP-SquirrelMail can enter their user ID and password in the user settings and select whether they want to be login automatically or manually to SquirrelMail.

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wpsquirrelmail` unzip folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. A user with admin rights need to set the url for the SquirrelMail installation
   in the admin settings section.

== Frequently Asked Questions ==

= Can I access a SquirrelMail installation on a different server? =

No.  WPSquirrelMail only allows you to access SquirrelMail installations on the same server
as your WordPress installation.
e.g. http://your.site.com/webmail/src/redirect.php


= Is my password store in plain text in the database? =

No.  The username and password are encrypted using php mcrypt.

== Screenshots ==

1. Log_Form
2. Admin_Area
3. User_Settings

== Changelog ==

= 1.0 =
Release Date: April 27, 2015

* Initial release

== Upgrade Notice ==

No upgrades availabe at the moment.