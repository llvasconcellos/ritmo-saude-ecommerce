<?php
/**
 * Display SVG in attachment modal
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function bodhi_svgs_response_for_svg( $response, $attachment, $meta ) {

	if( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

		$svg_path = get_attached_file( $attachment->ID );
		$dimensions = bodhi_svgs_get_dimensions( $svg_path );

		$response[ 'sizes' ] = array(
			'full' => array(
				'url' => $response[ 'url' ],
				'width' => $dimensions->width,
				'height' => $dimensions->height,
				'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
				)
			);

	}

	return $response;

}
add_filter( 'wp_prepare_attachment_for_js', 'bodhi_svgs_response_for_svg', 10, 3 );

function bodhi_svgs_get_dimensions( $svg ) {

	$svg = simplexml_load_file( $svg );
	$attributes = $svg->attributes();
	$width = (string) $attributes->width;
	$height = (string) $attributes->height;

	return (object) array( 'width' => $width, 'height' => $height );

}