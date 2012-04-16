<?php

/**
 * @package future-friendly-images
 * @version 0.1
*/

/*
Plugin Name: Future Friendly Images
Plugin URI: http://rgbboy.com/wordpress-plugins/future-friendly-images
Description: <strong>Future Friendly Images</strong> makes your images future-friendly. Out of the box Wordpress hardcodes the <img> tag and all of your chosen settings into your content when you insert an image. Future Friendly Images alleviates this problem by inserting the <strong>[ffimage]</strong> shortcode instead. You can then update your images accross your entire site by adjusting the settings on the plugin page.
Version: 0.1
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

//Example function documentation:

/**
 * {{@internal Missing Short Description}}}
 *
 * {{@internal Missing Long Description}}}
 *
 * @since 0.1
 * @uses function_name() {{@internal Missing Description}}}
 *
 * @param type varName Description
 * @return type Description
*/

/**
 * On Plugin Activation
 *
 * {{@internal Missing Long Description}}}
 *
 * @since 0.1
*/

function rgb_ffi_activate() {
  //activation code here
}

register_activation_hook( __FILE__, 'rgb_ffi_activate');

/**
 * On Plugin Deactivation
 *
 * {{@internal Missing Long Description}}}
 *
 * @since 0.1
*/

function rgb_ffi_deactivate() {
  //deactivation code here
}

register_deactivation_hook( __FILE__, 'rgb_ffi_deactivate');

/**
 * Plugin Setup
 *
 * Fired on 'plugins_loaded' action
 *
 * @since 0.1
*/

function rgb_ffi_setup() {
  
  add_shortcode( 'ffimage', 'rgb_ffi_shortcode' );
  
  //if current user can edit posts/pages/custom-post-types
  
  if ( is_admin() ) {
    add_filter( 'image_send_to_editor', 'rgb_ffi_shortcode_send_to_editor', 10, 8);
  }
  
  //add ability to insert ffimage via media insert
  //add_filter( 'image_send_to_editor', 'rgb_ffi_shortcode_send_to_editor'), 10, 8);

}

add_action( 'plugins_loaded', 'rgb_ffi_setup');

/**
 * ffimage shortcode
 *
 * {{@internal Missing Long Description}}}
 *
 * @since 0.1
*/

function rgb_ffi_shortcode() {
  return '<img src="' .plugins_url( 'images/test-image.jpg', __FILE__ ). '" alt="Test Image" />';
}

/**
 * ffimage shortcode send to editor
 *
 * Copied and modified from function get_image_send_to_editor in wp-admin/includes/media.php
 *
 * @since 0.1
 *
 * @param unknown_type $html
 * @param unknown_type $id
 * @param unknown_type $alt
 * @param unknown_type $title
 * @param unknown_type $align
 * @param unknown_type $url
 * @param unknown_type $rel
 * @param unknown_type $size
 * @return unknown
 *
 * @todo $url = none, file URL, attachment post URl or custom (just adds specified string)
*/

function rgb_ffi_shortcode_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt = '') {

  $alignString = '';
  $urlString = '';
  $sizeString = '';
  
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