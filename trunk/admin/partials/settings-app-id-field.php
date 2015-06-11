<?php
/**
 * Represents the partial view for where users can enter fb app id
 *
 * @since      1.0.0
 *
 * @subpackage Facebook_Login/Admin/Partials
 * @package    Facebook_Login
 *
 */
?>

<input type="text" name="fbl_settings[fb_id]" value="<?php echo $fb_id; ?>" placeholder="Facebook App id" />
<p class="description" ><?php _e('Create a new app and paste App Id here', 'fbl');?></p>