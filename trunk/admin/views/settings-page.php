<?php
/**
 * Displays the UI for editing Facebook Login
 *
 * @since      1.0.0
 *
 * @subpackage Facebook_Login/Admin/Views
 * @package    Facebook_Login
 *
 */
?>

<div class="wrap">

	<h2>Facebook Login</h2>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'fbl_settings' );
		do_settings_sections( 'fbl-section' );
		?>
		<div class="premium-box">
			<h3>Premium Version</h3>
			 <p>Check the <b>new premium version</b> available in <a href="https://timersys.com/plugins/facebook-login-pro/?utm_source=readme%20file&utm_medium=readme%20links&utm_campaign=facebook-login" target="_blank">https://timersys.com/plugins/facebook-login-pro/</a>
			<ul>
				<li> Powerful Login / Registration AJAX sidebar widget,</li>
				<li> Also available with a shortcode and php template function</li>
				<li> Compatible with WooCommerce and Easy Digital Downloads checkout pages</li>
				<li> Compatible with BuddyPress</li>
				<li> Login widget in Popups</li>
				<li> Premium support</li>
			</ul>
		</div>
		 <?php
		submit_button();
		?>
	</form>

</div><!-- .wrap -->