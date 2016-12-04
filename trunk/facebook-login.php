<?php

/**
 *
 * @link              http://wp.timersys.com
 * @since             1.0.0
 * @package           Facebook_Login
 *
 * @wordpress-plugin
 * Plugin Name:       Facebook Login
 * Plugin URI:        http://wordpress.org/plugins/facebook-login
 * Description:       Facebook Login. Simple adds a facebook login button into wp-login.php and let you use fb avatars, period.
 * Version:           1.1.6
 * Author:            Damian Logghe
 * Author URI:        http://wp.timersys.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fbl
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FBL_VERSION', '1.1.6');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-facebook-login-activator.php
 */
function activate_facebook_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-facebook-login-activator.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fbl-upgrader.php';

	Facebook_Login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-facebook-login-deactivator.php
 */
function deactivate_facebook_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-facebook-login-deactivator.php';
	Facebook_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_facebook_login' );
register_deactivation_hook( __FILE__, 'deactivate_facebook_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-facebook-login.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_facebook_login() {

	$plugin = Facebook_Login::instance();
	$plugin->run();
	return $plugin;
}
$GLOBALS['fbl'] = run_facebook_login();
