<?php

/*
*  Upgrader Class
*
*  @description: Upgrade rutines and upgrade messages
*  @since 1.0.4.1
*  @version 1.0
*/

class Fbl_Upgrader {

	public function upgrade_plugin() {
		global $wpdb;
		$current_version = get_option('fbl_version');

		if( !get_option('fbl_plugin_updated') ) {
			// show feedback box if updating plugin
			if ( ! empty( $current_version ) && version_compare( $current_version, SPU_VERSION, '<' ) ) {
				update_option( 'fbl_plugin_updated', true );
			}
		}
	}
}