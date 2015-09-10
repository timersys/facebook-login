=== Facebook Login ===
Contributors: timersys
Donate link: http://wp.timersys.com
Tags: facebook, facebook login, woocommerce, easy digital downloads, facebook ajax, facebook registration, registration form, login form, login widget, registration widget
Requires at least: 3.6
Tested up to: 4.3
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Facebook Login. Simple adds a facebook login button into wp-login.php and let you use fb avatars, period.

== Description ==

If you just need a facebook login button in your wp-login.php to login/register users, this is your plugin.
If you need to add a facebook login in your template use the following code:

`<?php do_action('<?php do_action('facebook_login_button');?>`

> <strong>Premium Version</strong><br>
>
> Check the **new premium version** available in ([https://timersys.com/plugins/facebook-login-pro/](https://timersys.com/plugins/facebook-login-pro/?utm_source=readme%20file&utm_medium=readme%20links&utm_campaign=facebook-login))
>
> * Powerful Login / Registration AJAX sidebar widget,
> * Also available with a shortcode and php template function
> * Compatible with WooCommerce and Easy Digital Downloads checkout pages
> * Compatible with BuddyPress
> * Login widget in Popups
> * Premium support
>

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

= 1.0.4 =

* Now user are checked by email, so existing users can still log with facebook
* Now errors are showing instead of showing eternal spin wheel

= 1.0.3 =

* Fixed undefined error when notices are on
* Added button to registration screen

= 1.0.2 =

* Fixed bug with avatars
* Added scopes in case api version of fb > 2.3 - thanks to sdether
* redirect fix
* Added some more filters

= 1.0.1 =

* Added facebook_login_button hook

= 1.0.0 =
* First version
