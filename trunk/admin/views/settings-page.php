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
		submit_button();
		?>
	</form>

</div><!-- .wrap -->