<?php
/**
 * @package future-friendly-images
 * @subpackage uninstall
 * @version 0.1
*/

if( !defined( 'WP_UNISTALL_PLUGIN' ) ) {
  exit ();
}

/**
 * Uninstall Plugin
 *
 * {{@internal Missing Long Description}}}
 *
 * @since 0.1
 * @uses delete_option() to remove saved options from the database.
*/

function rgb_ffi_uninstall() {

  delete_option( 'rgb_ffi_options' );

}

rgb_ffi_uninstall();

?>