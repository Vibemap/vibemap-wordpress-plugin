<?php
/*
Plugin Name: vibemap
Plugin URI: http://wordpress.org/plugins/vibemap/
Description: [iframe src="http://www.youtube.com/embed/oDlbBy9vfgI" width="100%" height="500"] shortcode
Version: 1.0
Author: vibmemap
Author URI: https://vibemap.com
License: MIT
*/

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}

define('IFRAME_PLUGIN_VERSION', '1.0');

function iframe_plugin_add_shortcode_cb( $atts ) {
	$defaults = array(
		'src' => 'http://www.youtube.com/embed/oDlbBy9vfgI',
		'width' => '100%',
		'height' => '500',
		'scrolling' => 'yes',
		'class' => 'iframe-class',
		'frameborder' => '0'
	);

	foreach ( $defaults as $default => $value ) { // add defaults
		if ( ! @array_key_exists( $default, $atts ) ) { // mute warning with "@" when no params at all
			$atts[$default] = $value;
		}
	}

	$html = "\n".'<!-- iframe plugin v.'.IFRAME_PLUGIN_VERSION.' wordpress.org/plugins/iframe/ -->'."\n";
	$html .= '<iframe';
	foreach( $atts as $attr => $value ) {
		if ( strtolower($attr) == 'src' ) { // sanitize url
			$value = esc_url( $value );
		}
		if ( strtolower($attr) != 'same_height_as' AND strtolower($attr) != 'onload'
			AND strtolower($attr) != 'onpageshow' AND strtolower($attr) != 'onclick') { // remove some attributes
			if ( $value != '' ) { // adding all attributes
				$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			} else { // adding empty attributes
				$html .= ' ' . esc_attr( $attr );
			}
		}
	}
	$html .= '></iframe>'."\n";

	if ( isset( $atts["same_height_as"] ) ) {
		$html .= '
			<script>
			document.addEventListener("DOMContentLoaded", function(){
				var target_element, iframe_element;
				iframe_element = document.querySelector("iframe.' . esc_attr( $atts["class"] ) . '");
				target_element = document.querySelector("' . esc_attr( $atts["same_height_as"] ) . '");
				iframe_element.style.height = target_element.offsetHeight + "px";
			});
			</script>
		';
	}

	return $html;
}

add_shortcode( 'vibemap', 'iframe_plugin_add_shortcode_cb' );


function iframe_plugin_row_meta_cb( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$row_meta = array(
			'support' => '<a href="https://vibemap.com" target="_blank">' . __( 'Iframe', 'iframe' ) . '</a>',
			'donate' => '<a href="https://vibemap.com" target="_blank">' . __( 'Donate', 'iframe' ) . '</a>',
			'pro' => '<a href="https://vibemap.com" target="_blank">' . __( 'Advanced iFrame Pro', 'iframe' ) . '</a>'
		);
		$links = array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter( 'plugin_row_meta', 'iframe_plugin_row_meta_cb', 10, 2 );
