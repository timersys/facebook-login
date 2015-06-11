<?php
/**
 * Represents the partial view for where users can check to use fb avatars
 *
 * @since      1.0.0
 *
 * @subpackage Facebook_Login/Admin/Partials
 * @package    Facebook_Login
 *
 */
?>

<input type="checkbox" name="fbl_settings[fb_avatars]" value="1" <?php checked( $fb_avatars, '1');?> /> Yes
<p class="description" ><?php _e('If checked, users registered with facebook will display fb avatar', 'fbl');?></p>