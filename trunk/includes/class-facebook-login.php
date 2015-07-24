<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Facebook_Login
 * @subpackage Facebook_Login/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Facebook_Login {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Facebook_Login_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * @var array of plugin settings
	 */
	protected $opts;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name  = 'facebook-login';
		$this->version      = '1.0.2';
		$this->opts         = get_option('fbl_settings');

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Facebook_Login_Loader. Orchestrates the hooks of the plugin.
	 * - Facebook_Login_i18n. Defines internationalization functionality.
	 * - Facebook_Login_Admin. Defines all hooks for the admin area.
	 * - Facebook_Login_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-facebook-login-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-facebook-login-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-facebook-login-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-facebook-login-settings.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-facebook-login-public.php';


		$this->loader = new Facebook_Login_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Facebook_Login_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Facebook_Login_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Facebook_Login_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_items');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'create_settings');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Facebook_Login_Public( $this->get_plugin_name(), $this->get_version() );

		if( !empty( $this->opts['fb_id'] ) ) {
			$this->loader->add_action( 'login_form', $plugin_public, 'add_button_to_login_form' );
			$this->loader->add_action( 'login_head', $plugin_public, 'add_fb_scripts' );
			$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_ajax_fbl_facebook_login', $plugin_public, 'login_or_register_user' );
			$this->loader->add_action( 'wp_ajax_nopriv_fbl_facebook_login', $plugin_public, 'login_or_register_user' );
			$this->loader->add_action( 'facebook_login_button', $plugin_public, 'add_button_to_login_form' );
			$this->loader->add_action( 'facebook_login_button', $plugin_public, 'add_fb_scripts' );
			$this->loader->add_action( 'facebook_login_button', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'facebook_login_button', $plugin_public, 'enqueue_styles' );

		}
		if(  !empty( $this->opts['fb_avatars'] ) )
			$this->loader->add_filter( 'get_avatar', $plugin_public, 'use_fb_avatars',10, 5 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Facebook_Login_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
