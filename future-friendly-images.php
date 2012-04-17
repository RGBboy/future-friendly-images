<?php

/**
 * @package future-friendly-images
 * @version 0.2.0
 *
 * @todo Add functionality to update all existing images inserted into posts to use ffimage shortcode.
 * @todo Add functionality to display image instead of shortcode in wysiwig.
*/

/*
Plugin Name: Future Friendly Images
Plugin URI: http://rgbboy.com/wordpress-plugins/future-friendly-images
Description: <strong>Future Friendly Images</strong> makes your images future-friendly. Out of the box Wordpress hardcodes the <img> tag and all of your chosen settings into your content when you insert an image. Future Friendly Images alleviates this problem by inserting the <strong>[ffimage]</strong> shortcode instead. You can then update your images accross your entire site by adjusting the settings on the plugin page.
Version: 0.2.0
Author: RGBboy
Author URI: http://rgbboy.com/
License: GPLv2

Copyright 2012  RGBboy  (email : me@rgbboy.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Plugin Setup
 *
 * Fired on 'plugins_loaded' action
 *
 * @since 0.1.0
 *
 * @uses add_shortcode() Adds [ffimage] Shortcode
 * @uses is_admin() Checks if admin to add filter.
 * @uses add_filter() Add filter to image_send_to_editor to inject [ffimage] Shortcode.
*/

function rgb_ffi_setup() {
  
  add_shortcode( 'ffimage', 'rgb_ffi_shortcode' );
  
  if ( is_admin() ) {
    add_filter( 'image_send_to_editor', 'rgb_ffi_shortcode_send_to_editor', 100, 8);
  }

}

add_action( 'plugins_loaded', 'rgb_ffi_setup');

/**
 * [ffimage] Shortcode
 *
 * Takes the [ffimage] Shortcode and apats it to the intended image markup. Uses the same
 * process from /wp-admin/includes/media.php that an image being inserted would 
 *
 * @since 0.1.0
 *
 * @uses extract() Gets shortcode attributes and maps to variables.
 * @uses shortcode_atts() Combines user shortcode attributes with known attributes and fills in defaults when needed. 
 * @uses wp_get_attachment_url()
 * @uses get_attachment_link() 
 * @uses get_post() Gets attachment.
 * @uses get_post_meta() Gets attachments alt text value.
 * @uses get_image_send_to_editor() Gets the intended image markup from the core.
 * @uses do_shortcode() Translates shortcodes added by get_image_send_to_editor().
 * @uses remove_filter() Removes rgb_ffi_shortcode_send_to_editor from filter so correct output is created.
 * @uses add_filter() Adds rgb_ffi_shortcode_send_to_editor back to filter.
 *
 * @param Array $atts Array of attribute => value pairs.
 * @return String
*/

function rgb_ffi_shortcode($atts) {
  
  extract( shortcode_atts( array(
		'id' => null,
		'align' => 'none',
		'size' => 'medium',
		'link' => ''
		), $atts ) );
		
	if( $link == 'file' ) {
	  $url = wp_get_attachment_url( $id );
	} elseif( $link == 'post') {
	  $url = get_attachment_link( $id );
	} else {
	  $url = $link;
	}
	
	$rel = ( $url == get_attachment_link($id) );
	$post = get_post($id);
	$caption = $post -> post_excerpt;
  $title = $post -> post_title;
  $alt = get_post_meta( $id, '_wp_attachment_image_alt', true) ;
  
  remove_filter( 'image_send_to_editor', 'rgb_ffi_shortcode_send_to_editor', 100, 8);
  
  //Should this file be included like this? Is there a way that this directory be moved by the user?
  require_once( ABSPATH.'wp-admin/includes/media.php' );
  
  $html = do_shortcode(get_image_send_to_editor($id, $caption, $title, $align, $url, $rel, $size, $alt));
  add_filter( 'image_send_to_editor', 'rgb_ffi_shortcode_send_to_editor', 100, 8);
  
  return $html;
  
}

/**
 * Sends ffimage shortcode to editor.
 *
 * Copied and modified from the function get_image_send_to_editor() in wp-admin/includes/media.php
 * Takes the intended settings for the image and adapts them to the [ffimage] shortcode.
 *
 * @since 0.1.0
 *
 * @uses wp_get_attachment_url() Compares $url to this to determine type of link.
 * @uses get_attachment_link() Compares $url to this to determine type of link.
 *
 * @param unknown_type $html
 * @param unknown_type $id
 * @param unknown_type $alt
 * @param unknown_type $title
 * @param unknown_type $align
 * @param unknown_type $url
 * @param unknown_type $rel
 * @param unknown_type $size
 * @return String
*/

function rgb_ffi_shortcode_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt = '') {

  $alignString = '';
  $urlString = '';
  $sizeString = '';
  
  if( wp_get_attachment_url( $id ) == $url ) {
    $url = 'file';
  } elseif( get_attachment_link( $id ) == $url ) {
    $url = 'post';
  }
  
  if( $align ) {
    $alignString = 'align="'.$align.'" ';
  }
  if( $url ) {
    $urlString = 'link="'.$url.'" ';
  }
  if ( $size ) {
    $sizeString = 'size="'.$size.'" ';
  }
  
  $html = '[ffimage id="'.$id.'" ' .$alignString.$urlString.$sizeString. '/]';
  
  return $html;
  
}

?>