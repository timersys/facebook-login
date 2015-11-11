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


}
