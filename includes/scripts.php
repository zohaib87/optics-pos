<?php
/**
 * Enqueue scripts and styles for admin panel and front end.
 *
 * @package Optics POS
 */

/**
 * Enqueue scripts and styles for admin panel.
 */
function optics_pos_admin_scripts() {

	global $current_screen;

	/**
   * Styles
   */
  wp_enqueue_style( 'select2', optics_pos_directory_uri() . '/assets/css/select2.min.css' );
  wp_enqueue_style( 'optics-pos-admin', optics_pos_directory_uri() . '/assets/css/admin.css' );

  /**
   * Scripts
   */
	wp_enqueue_script( 'printThis', optics_pos_directory_uri() . '/assets/js/printThis.js', array('jquery'), '20151215', true );
  wp_enqueue_script( 'select2', optics_pos_directory_uri() . '/assets/js/select2.min.js', array('jquery'), '20151215', true );
  wp_enqueue_script( 'JsBarcode', optics_pos_directory_uri() . '/assets/js/JsBarcode.all.min.js', array('jquery'), '20151215', true );
  wp_enqueue_script( 'optics-pos-admin', optics_pos_directory_uri() . '/assets/js/admin.js', array('jquery'), '20151215', true );
  if ($current_screen->post_type === 'opos-sales') {
    wp_enqueue_script( 'optics-pos-sales', optics_pos_directory_uri() . '/assets/js/sales.js', array('jquery'), '20151215', true );
  }

	wp_localize_script('optics-pos-admin', 'opos', [
		'post_type' => $current_screen->post_type,
		'base' => $current_screen->base,
    'site_url' => get_site_url()
	]);

}
add_action( 'admin_enqueue_scripts', 'optics_pos_admin_scripts', 9999 );

/**
 * Admin quick links css for sales manager
 */
function optics_pos_styles() {

  if (!in_array('opos_sales_manager', wp_get_current_user()->roles)) {
    return;
  }

  echo "<style type='text/css'>
    #wp-admin-bar-comments,
    #wp-admin-bar-new-content,
    #wpadminbar .quicklinks .menupop ul li[id$='-n'],
    #wpadminbar .quicklinks .menupop ul li[id$='-c'] {
      display: none;
    }
  </style>";

}
add_action('admin_head', 'optics_pos_styles');