<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/public
 * @author     Damian Logghe <info@timersys.com>
 */
class Facebook_Login_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts         = get_option('fbl_settings');
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/facebook-login.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/facebook-login.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'fbl', apply_filters( 'fbl/js_vars', array(
			'ajaxurl'      => admin_url('admin-ajax.php'),
			'site_url'     => home_url(),
		)));
	}

	/**
	 * Print the button on login page
	 * @since   1.0.0
	 */
	public function add_button_to_login_form() {
		echo apply_filters('fbl/login_button', '<a href="#" class="css-fbl js-fbl" data-fb_nonce="' . wp_create_nonce( 'facebook-nonce' ).'">'. __('Connect with Facebook', $this->plugin_name) .'<img src="'.site_url('/wp-includes/js/mediaelement/loading.gif').'" alt=""/></a>');
	}

	/**
	 * Prints fb script in login head
	 * @since   1.0.0
	 */
	public function add_fb_scripts(){
		?>
		<script>

			window.fbAsyncInit = function() {
				FB.init({
					appId      : '<?php echo $this->opts['fb_id'];?>',
					cookie     : true,  // enable cookies to allow the server to access
					xfbml      : true,  // parse social plugins on this page
					version    : 'v2.2' // use version 2.2
				});

			};

			// Load the SDK asynchronously
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

		</script><?php

	}

	/**
	 * Main function that handles user login/ registration
	 */
	public function login_or_register_user() {
		check_ajax_referer( 'facebook-nonce', 'security' );

		// Map our FB response fields to the correct user fields as found in wp_update_user
		$user = apply_filters( 'fbl/user_data_login', array(
			'username'   => $_POST['fb_response']['id'],
			'user_login' => $_POST['fb_response']['id'],
			'first_name' => $_POST['fb_response']['first_name'],
			'last_name'  => $_POST['fb_response']['last_name'],
			'user_email' => $_POST['fb_response']['email'],
			'user_url'   => $_POST['fb_response']['link'],
		));
		do_action( 'fbl/before_login', $user);
		$status = array( 'error' => 'Invalid User');
		if ( empty( $user['username'] ) ){
			wp_send_json( $status );
			die();
		} else {

			$user_obj = get_user_by( 'login', $user['user_login'] );

			if ( $user_obj ){
				$user_id = $user_obj->ID;
				$status = array( 'success' => $user_id);
			} else {
				$user_id = $this->register_user( $user );
				if( !is_wp_error($user_id) ) {
					update_user_meta( $user_id, '_fb_user_id', $user['user_login'] );
					$status = array( 'success' => $user_id);
				}
			}
			if( is_numeric( $user_id ) ) {
				wp_set_auth_cookie( $user_id, true );
				do_action( 'fbl/after_login', $user, $user_id);
			}
		}

		wp_send_json( $status );
	}

	/**
	 * Register new user
	 * @param $user Array of user values captured in fb
	 *
	 * @return int user id
	 */
	private function register_user( $user ) {
		return wp_insert_user( $user );
	}

	/**
	 * Replaces the default gravatar with the Facebook profile picture if is fb user.
	 *
	 * @param string $avatar The default avatar
	 *
	 * @param int $id_or_email The user id
	 *
	 * @param int $size The size of the avatar
	 *
	 * @param string $default The url of the Wordpress default avatar
	 *
	 * @param string $alt Alternate text for the avatar.
	 *
	 * @return string $avatar The modified avatar
	 */
	public function use_fb_avatars($avatar, $id_or_email, $size, $default, $alt ) {
		$user = false;

		if ( is_numeric( $id_or_email ) ) {

			$id   = (int) $id_or_email;
			$user = get_user_by( 'id', $id );

		} elseif ( is_object( $id_or_email ) ) {

			if ( ! empty( $id_or_email->user_id ) ) {
				$id   = (int) $id_or_email->user_id;
				$user = get_user_by( 'id', $id );
			}

		} else {
			$user = get_user_by( 'email', $id_or_email );
		}
		if ( $user && is_object( $user ) ) {
			$user_id = $user->data->ID;

			// We can use username as ID but checking the usermeta we are sure this is a facebook user
			if ( $fb_id = get_user_meta( $user_id, '_fb_user_id', true ) ) {

				$fb_url = 'https://graph.facebook.com/' . $fb_id . '/picture?width=' . $size . '&height=' . $size;
				$avatar = "<img alt='facebook-profile-picture' src='{$fb_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

			}

		}

		return $avatar;
	}

}
