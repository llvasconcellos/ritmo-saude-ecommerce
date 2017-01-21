<?php
/**
 * One time function to make sure new "Advanced Mode" setting is activated
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Update plugin settings on activate/update
 */
function bodhi_svgs_plugin_update() {

	$new_option 		= array( 'advanced_mode' => 'on' );
	$existing_settings 	= get_option( 'bodhi_svgs_settings' );
	$new_settings 		= array_merge( $new_option, $existing_settings );

	update_option( 'bodhi_svgs_settings', $new_settings );

}
register_activation_hook( BODHI_SVGS_PLUGIN_PATH . 'svg-support.php', 'bodhi_svgs_plugin_update' );
add_action( 'upgrader_process_complete', 'bodhi_svgs_plugin_update', 10, 2);