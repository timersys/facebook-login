<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/admin
 * @author     Damian Logghe <info@timersys.com>
 */
class Facebook_Login_Admin {
	/**
	 * @var     string  $views    location of admin views
	 */
	protected $views;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->views = trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views' );
	}

	public function add_menu_items() {

		add_submenu_page(
			'options-general.php',
			'Facebook Login',
			'Facebook Login',
			'edit_posts',
			'facebook_login',
			array( $this, 'display_settings_page' )
		);
	}

	public function display_settings_page() {
		include_once $this->views . 'settings-page.php';
	}

	public function create_settings() {
		$settings = new Facebook_Login_Settings( $this->plugin_name, $this->version);
		$settings->register();
	}

	/**
	 *
	 * Register and enqueue scripts.
	 *
	 * @since     1.1
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function admin_scripts() {

		global $pagenow;
		if (  ( isset($_GET['page']) && 'facebook_login' == $_GET['page']  ) || $pagenow == 'profile.php' ) {

			wp_enqueue_style( 'fbl-admin-css', plugins_url( 'assets/css/admin.css', __FILE__ ) , '', $this->version );
			wp_enqueue_style( 'fbl-public-css', plugins_url( 'public/css/facebook-login.css', dirname( __FILE__ ) ) , '', $this->version );
			wp_enqueue_script( 'fbl-public-js', plugins_url( 'public/js/facebook-login.js', dirname( __FILE__ ) ) , '', $this->version );
			wp_localize_script( 'fbl-public-js', 'fbl', apply_filters( 'fbl/js_vars', array(
				'ajaxurl'      => admin_url('admin-ajax.php'),
				'site_url'     => home_url(),
				'scopes'       => 'email,public_profile',
				'l18n'         => array(
					'chrome_ios_alert'      => __( 'Please login into facebook and then click connect button again', 'fbl' ),
				)
			)));
		}
	}

	/**
	 * Add extra section on wp-admin/profile.php
	 * @param $user
	 * @since 1.1
	 */
	public function profile_buttons( $user ) {
		?><h3><?php _e("Facebook connection", "blank"); ?></h3><?php
		$fb_id = get_user_meta( $user->ID, '_fb_user_id' );
		if( $fb_id ) {
			echo '<p>' . __( 'Your profile is currently linked to your Facebook account. Click the button below to remove connection and avatar', 'fbl' ) . '</p>';
			do_action('facebook_disconnect_button');
		} else {
			echo '<p>' . __( 'Link your facebook account to your profile.', 'fbl' ) . '</p>';
			do_action('facebook_login_button');
		}
	}
}
