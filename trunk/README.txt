=== Login for WordPress ===
Contributors: timersys
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4T6L69EV9G2Q
Tags: facebook, facebook login, woocommerce, easy digital downloads, facebook ajax, facebook registration, buddypress, registration form, login form, login widget, registration widget, ajax login, facebook ajax login, popup, popups, facebook popup, facebook avatars, buddypress
Requires at least: 3.6
Tested up to: 5.4.1
Stable tag: 1.2.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Facebook Login for WordPress simple adds a facebook login button into wp-login.php and let you use facebook avatars. BuddyPress compatible

== Description ==

If you just need a facebook login button in your wp-login.php to login/register users, this is your plugin. Lightweight plugin that won't bloat your site with unnecessary functions. Developer friendly and easy to expand.

To add facebook button on a page use shortcode `[fbl_login_button redirect="" hide_if_logged="" size="large" type="continue_with" show_face="true"]`

More info about the button on https://developers.facebook.com/docs/facebook-login/web/login-button

If you need to add a facebook login in your template or link a Facebook account to an existing profile use the following code:

```<?php do_action('facebook_login_button');?>```

If you want to show a disconnect button to remove facebook connection from a user profile and avatar use this:

```<?php do_action('facebook_disconnect_button');?>```

If you want to change the redirect url after used is logged in wp-login.php you can pass ?redirect_to= in the url or use the following filter:

```add_filter('flp/redirect_url', function($url){ return site_url('another-url')});``

= Configuration =
Once you install the plugin, you need to configure it. Please follow [this guide](https://timersys.com/facebook-login/docs/configuration/) . That guide is for the premium version so be aware that the only shortcode available is `[fbl_login_button redirect="" hide_if_logged=""]`

> <strong>Premium Version</strong><br>
>
> Check the **new premium version** available in ([https://timersys.com/plugins/facebook-login-pro/](https://timersys.com/plugins/facebook-login-pro/?utm_source=readme%20file&utm_medium=readme%20links&utm_campaign=facebook-login))
>
> * Powerful Login / Registration AJAX sidebar widget,
> * Also available with a shortcode and php template function
> * Compatible with WooCommerce
> * Easy Digital Downloads checkout pages
> * Compatible with BuddyPress
> * Login widget in Popups
> * Premium support
>

= Available Languages =

* Spanish
* English
* Bulgarian
* Vietnamese
* Italian
* Czech
* Portuguese
* Danish
* Russian
* Ukrainian

Collaborate with translations on Transifex https://www.transifex.com/timersys/facebook-login/dashboard/

= GitHub =

Please contribute on [https://github.com/timersys/facebook-login](https://github.com/timersys/facebook-login)


== Installation ==


1. Install plugin zip using `/wp-admin/plugin-install.php` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> Facebook Login and enter your facebook app ID
4. Place `<?php do_action('facebook_login_button');?>` in your templates if you need it somewhere else than wp-login.php


== Frequently Asked Questions ==

= Are you planning to add more features ?=

Nope really. The plugin is intended as a base for anyone needing facebook login


== Screenshots ==

1. button

== Changelog ==

= 1.2.3.5 =
* Updated api version

= 1.2.3.4 =
* Fixed missing loading gif

= 1.2.3.3 =
* Fix issue with new FB update that broke user registration

= 1.2.3.2 =
* tagging new release for svn

= 1.2.3.1 =
* Branding changes to comply with WP guideliness

= 1.2.3 =
* New languages
* Better error handling
* data-size filter for facebook button

= 1.2.2 =
* More shortcodes attributes and filters to change button style

= 1.2.1 =
* Added ability to change scopes
* Added request for new button, so if email is not provided app will ass permission again

= 1.2 =
* New facebook login button
* Fixed issues with hooks on disable registration
* New facebook api used
* Better compatibility with jetpack
* New languages
* Filters to disable email notifications
* Fixed locale of button

= 1.1.6 =
* Fixed user notifications not sending

= 1.1.5 =
* Fixed bug with redirection
* Added new languages
* Better error handling on fb response

= 1.1.4 =
* Fixed permissions to view facebook settings page
* Fixed typo preventing app_secret to work fine
* Added new filter to let users change response based on login/registration

= 1.1.3 =
* Added secret key field in settings to make queries more secure and fix issue that some users were having
* Fixed textdomain to make plugin translatable with other plugins
* Updated language files

= 1.1.2 =
* Replaced deprecated function in WP 4.5

= 1.1.1 =
* Fully support for russian usernames and Bp valid usernames
* Now scripts are added with button ( fb init bug fix )
* Added shortcode for button

= 1.1 =
* Added Chrome iOS workaround
* Improved username generation
* Added connect / disconnect button on user profile
* Fixed bug with avatars in groups
* Code improvement and minor bugfixes


= 1.0.10 =
* Custom Avatars in bp are working now along with facebook ones
* Notifications when a new user register
* If FB is not defined or private tracking is enabled now shows error
* Fixed undefined notice on activation


= 1.0.9 =
* Fixed bug that avatars cannot be disabled in BP
* If registration is disabled in General -> Settings users won't be able to register

= 1.0.8 =
* Facebook Avatars now are working in Buddypress

= 1.0.7.2 =
* Fixed important bug with fb users login into wrong account

= 1.0.7.1 =
* Removed iconv as was causing problem in some servers

= 1.0.7 =
* Added redirect feature to template button
* Now username is generated only on registration

= 1.0.6 =
* Fixed bug when users deny to provide email with facebook
* Fb username is now more friendly
* Minor bugfixes and code improvement

= 1.0.5 =
* Changed the way users login to a more secure one ( Thanks Zoli! )
* Added fallback in case a Fb user change their email so they can still login to their account

= 1.0.4.1 =

* Minor css and js fixes
* Updated docs in readme

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

== Upgrade Notice ==

= 1.0.7.2 =
* Fixed important bug with fb users login into wrong account

= 1.0.5 =
This version fix a security issue where malicious users could login to another users accounts by knowing certain data. Upgrade as soon as possible
