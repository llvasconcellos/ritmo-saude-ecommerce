<?php
/*
Plugin Name: 	SVG Support
Plugin URI:		http://wordpress.org/plugins/svg-support/
Description: 	Allow SVG file uploads using the WordPress Media Library uploader plus the ability to inline SVG files for direct targeting of SVG elements for CSS and JS.
Version: 		2.3.5
Author: 		Benbodhi
Author URI: 	http://benbodhi.com
Text Domain: 	svg-support
Domain Path:	/languages
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html

	Copyright 2013 and beyond | Benbodhi (email : wp@benbodhi.com)

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Global variables
 */
$svgs_plugin_version = '2.3.5';										// for use on admin pages
$plugin_file = plugin_basename(__FILE__);							// plugin file for reference
define( 'BODHI_SVGS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// define the absolute plugin path for includes
define( 'BODHI_SVGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// define the plugin url for use in enqueue
$bodhi_svgs_options = get_option('bodhi_svgs_settings');			// retrieve our plugin settings from the options table

/**
 * Includes - keeping it modular
 */
include( BODHI_SVGS_PLUGIN_PATH . 'admin/admin-init.php' );         		// initialize admin menu & settings page
include( BODHI_SVGS_PLUGIN_PATH . 'admin/plugin-action-meta-links.php' );	// add links to the plugin on the plugins page
include( BODHI_SVGS_PLUGIN_PATH . 'admin/admin-notice.php' );				// dismissable admin notice to warn users to update settings
include( BODHI_SVGS_PLUGIN_PATH . 'functions/mime-types.php' );				// setup mime types support for SVG
include( BODHI_SVGS_PLUGIN_PATH . 'functions/thumbnail-display.php' );		// make SVG thumbnails display correctly in media library
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attachment-modal.php' );		// make SVG thumbnails display correctly in attachment modals
include( BODHI_SVGS_PLUGIN_PATH . 'functions/enqueue.php' );				// enqueue js & css for inline replacement & admin
include( BODHI_SVGS_PLUGIN_PATH . 'functions/localization.php' );			// setup localization & languages
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attribute-control.php' );		// auto set SVG class & remove dimensions during insertion
include( BODHI_SVGS_PLUGIN_PATH . 'functions/featured-image.php' );			// allow inline SVG for featured images

/**
 * TEMP FIX FOR 4.7.1
 * Issue should be fixed in 4.7.2 in which case this will be deleted.
 */
function bodhi_svgs_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext = $wp_filetype['ext'];
	$type = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}
add_filter( 'wp_check_filetype_and_ext', 'bodhi_svgs_disable_real_mime_check', 10, 4 );

/**
 * Version based conditional / Check for stored plugin version
 *
 * Versions prior to 2.3 did not store the version number,
 * these older versions need to have the "Advanced Mode" switched on to retain inline functionality if upgrading to 2.3 or higher.
 * If no version number is stored, load the one-time-upgrade-activate.php file and store current plugin version number.
 * If there is a version number stored, update it with the new version number.
 */
// get the stored plugin version
$svgs_plugin_version_stored = get_option( 'bodhi_svgs_plugin_version' );
// only run this if there is no stored version number (have never stored the number in previous versions)
if ( empty( $svgs_plugin_version_stored ) ) {

	// include the one time script to activate the new "Advanced Mode" setting
	include( BODHI_SVGS_PLUGIN_PATH . 'one-time-upgrade-activate.php');

	// add plugin version number to options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

} else {

	// update plugin version number in options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

}