<?php
class Facebook_Login_Settings {

	public function __construct() {
		$this->views    = trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/' );
		$this->fields   = array(
			'app_id'        => 'App id',
			'app_secret'   => __('App Secret Key', 'fbl'),
			'fb_avatars'    => 'Facebook Avatars?',
		);
	}

	/**
	 * Register sections fields and settings
	 */
	public function register() {

		register_setting(
			'fbl_settings',		// Group of options
			'fbl_settings',     	        // Name of options
			array( $this, 'sanitize' )	// Sanitization function
		);

		add_settings_section(
			'fbl-main',			// ID of the settings section
			'Main Settings',  			// Title of the section
			'',
			'fbl-section'		// ID of the page
		);

		foreach( $this->fields as $key => $name) {
			add_settings_field(
				$key,        // The ID of the settings field
				$name,                // The name of the field of setting(s)
				array( $this, 'display_'.$key ),
				'fbl-section',        // ID of the page on which to display these fields
				'fbl-main'            // The ID of the setting section
			);
		}
	}

	/**
	 * Display APP id field
	 */
	public function display_app_id() {
		// Now grab the options based on what we're looking for
		$opts = get_option( 'fbl_settings' );
		$fb_id = isset( $opts['fb_id'] ) ? $opts['fb_id'] : '';
		// And display the view
		include_once $this->views . 'settings-app-id-field.php';
	}

	/**
	 * Display Secret id field
	 */
	public function display_app_secret() {
		// Now grab the options based on what we're looking for
		$opts = get_option( 'fbl_settings' );
		$fb_app_secret = isset( $opts['fb_app_secret'] ) ? $opts['fb_app_secret'] : '';
		// And display the view
		include $this->views . 'settings-app-secret-field.php';
	}

	/**
	 * Display Facebook field
	 */
	public function display_fb_avatars() {
		// Now grab the options based on what we're looking for
		$opts = get_option( 'fbl_settings' );
		$fb_avatars = isset( $opts['fb_avatars'] ) ? $opts['fb_avatars'] : '';
		// And display the view
		include_once $this->views . 'settings-fb-avatars-field.php';
	}

	/**
	 * Simple sanitize function
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize( $input ) {

		$new_input = array();

		// Loop through the input and sanitize each of the values
		foreach ( $input as $key => $val ) {
			$new_input[ $key ] = sanitize_text_field( $val );
		}

		return $new_input;
	}
}