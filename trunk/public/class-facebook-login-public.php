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
			'scopes'       => 'email,public_profile',
		)));
	}

	/**
	 * Print the button on login page
	 * @since   1.0.0
	 */
	public function add_button_to_login_form() {
		$redirect = apply_filters( 'flp/redirect_url', ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		// if we are in login page we don't want to redirect back to it
		if ( isset( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) )
			$redirect = apply_filters( 'flp/redirect_url', '');

		echo apply_filters('fbl/login_button', '<a href="#" class="css-fbl js-fbl" data-redirect="'.$redirect.'" data-fb_nonce="' . wp_create_nonce( 'facebook-nonce' ).'">'. __('Connect with Facebook', $this->plugin_name) .'<img src="'.site_url('/wp-includes/js/mediaelement/loading.gif').'" alt=""/></a>');
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

		// Get user from Facebook with given access token
		$fb_url = add_query_arg( array(
			'fields'        =>  'id,first_name,last_name,email,link',
			'access_token'  =>  $_POST['fb_response']['authResponse']['accessToken'],
		), 'https://graph.facebook.com/v2.4/'.$_POST['fb_response']['authResponse']['userID'] );

		$fb_response = wp_remote_get( $fb_url );

		if( is_wp_error( $fb_response ) )
			$this->ajax_response( array( 'error' => $fb_response->get_error_message() ) );

		$fb_user = json_decode( wp_remote_retrieve_body( $fb_response ), true );

		//check if user at least provided email
		if( empty( $fb_user['email'] ) )
			$this->ajax_response( array( 'error' => __('We need your email in order to continue. Please try loging again. ', $this->plugin_name ) ) );

		// Map our FB response fields to the correct user fields as found in wp_update_user
		$user = apply_filters( 'fbl/user_data_login', array(
			'fb_user_id' => $fb_user['id'],
			'first_name' => $fb_user['first_name'],
			'last_name'  => $fb_user['last_name'],
			'user_email' => $fb_user['email'],
			'user_url'   => $fb_user['link'],
			'user_pass'  => wp_generate_password(),
		));

		do_action( 'fbl/before_login', $user);

		$status = array( 'error' => __( 'Invalid User', $this->plugin_name ) );

		if ( empty( $user['fb_user_id'] ) )
			$this->ajax_response( $status );

		$user_obj = $this->getUserBy( $user );

		$meta_updated = false;

		if ( $user_obj ){
			$user_id = $user_obj->ID;
			$status = array( 'success' => $user_id);
			// check if user email exist or update accordingly
			if( empty( $user_obj->user_email ) )
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $user['user_email'] ) );

		} else {
			// generate a new username
			$user['user_login'] = apply_filters( 'fbl/generateUsername', $this->generateUsername( $fb_user ) );

			$user_id = $this->register_user( apply_filters( 'fbl/user_data_register',$user ) );
			if( !is_wp_error($user_id) ) {
				update_user_meta( $user_id, '_fb_user_id', $user['fb_user_id'] );
				$meta_updated = true;
				$status = array( 'success' => $user_id);
			}
		}
		if( is_numeric( $user_id ) ) {
			wp_set_auth_cookie( $user_id, true );
			if( !$meta_updated )
				update_user_meta( $user_id, '_fb_user_id', $user['fb_user_id'] );
			do_action( 'fbl/after_login', $user, $user_id);
		}
		$this->ajax_response( $status );
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

	/**
	 * Function to send ajax response in script
	 * @param $status
	 */
	private function ajax_response( $status ) {
		wp_send_json( $status );
		die();
	}

	/**
	 * Try to retrieve an user by email or username
	 *
	 * @param $user array of username and pass
	 *
	 * @return false|WP_User
	 */
	private function getUserBy( $user ) {

		$user_data = get_user_by('email', $user['user_email']);

		if( ! $user_data )
			$user_data = reset(
				get_users(
					array(
						'meta_key'      => '_fb_user_id',
						'meta_value'    => $user['fb_user_id'],
						'number'        => 1,
						'count_total'   => false
					)
				)
			);
		return $user_data;
	}

	/**
	 * Generated a friendly username for facebook users
	 * @param $user
	 *
	 * @return string
	 */
	private function generateUsername( $user ) {
		global $wpdb;

		do_action( 'flb/generateUsername', $user );

		if( !empty( $user['first_name'] ) && !empty( $user['last_name'] ) ) {
			$username = strtolower( "{$user['first_name']}.{$user['last_name']}" );
		} else {
			// use email
			$email    = explode( '@', $user['user_email'] );
			$username = strtolower( $email[0] );
		}

		// remove special characters
		$username = sanitize_user( $username, true );

		// "generate" unique suffix
		$suffix = $wpdb->get_var( $wpdb->prepare(
			"SELECT 1 + SUBSTR(user_login, %d) FROM $wpdb->users WHERE user_login REGEXP %s ORDER BY 1 DESC LIMIT 1",
			strlen( $username ) + 2, '^' . $username . '(\.[0-9]+)?$' ) );

		if( !empty( $suffix ) ) {
			$username .= ".{$suffix}";
		}

		return $username;
	}

}
