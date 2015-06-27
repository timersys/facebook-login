=== Facebook Login ===
Contributors: timersys
Donate link: http://wp.timersys.com
Tags: facebook, facebook login
Requires at least: 3.6
Tested up to: 4.2.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Facebook Login for developers... and everyone else . Simple adds a facebook login button into wp-login.php and let you use fb avatars, period.

== Description ==

If you just need a facebook login button in your wp-login.php to login/register users, this is your plugin.
There won't be any settings other than facebook app id and enable avatars. Any other customizations needed will be in the code.

= GitHub =

Please contribute on [https://github.com/timersys/facebook-login](https://github.com/timersys/facebook-login)


== Installation ==


1. Install plugin zip using `/wp-admin/plugin-install.php` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php do_action('<?php do_action('facebook_login_button');?>` in your templates if you need it somewhere else than wp-login.php


== Frequently Asked Questions ==

= Are you planning to add more features ?=

Nope really. The plugin is intended as a base for anyone needing facebook login


== Screenshots ==

1. button

== Changelog ==

= 1.0.1 =

* Added facebook_login_button hook

= 1.0.0 =
* First version
