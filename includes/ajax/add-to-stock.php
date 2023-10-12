<?php
/**
 * Add products to stock using AJAX
 *
 * @package Optics POS
 */

function optics_pos_add_to_stock() {

	if ( isset($_REQUEST) ) {

    $id = $_REQUEST['id'];
    $curr_quan = $_REQUEST['curr_quan'];
    $status = get_post_status($id);

    if ($status === 'trash') {

      wp_untrash_post($id);
      wp_publish_post($id);
      update_post_meta($id, '_stock', 1);

    } else {

      $stock = get_post_meta($id, '_stock', true);
      $stock += $curr_quan;

      update_post_meta($id, '_stock', $stock);

    }

  }

}
add_action('wp_ajax_optics_pos_add_to_stock', 'optics_pos_add_to_stock');

/**
 * Add Glasses to Stock
 */
function optics_pos_add_to_lens_stock() {

	if ( isset($_REQUEST) ) {

    $id = $_REQUEST['id'];

    $stock = get_post_meta($id, '_lens_stock', true);
		$stock++;

    update_post_meta($id, '_lens_stock', $stock);

  }

}
add_action('wp_ajax_optics_pos_add_to_lens_stock', 'optics_pos_add_to_lens_stock');

/**
 * Increase or decrease product stock
 */
function optics_pos_incdec_stock() {

  if ( isset($_REQUEST) ) {

    $id = $_REQUEST['id'];
    $diff = $_REQUEST['diff'];
    $inc_dec = $_REQUEST['inc_dec'];

    $stock = get_post_meta($id, '_stock', true);

    if ($inc_dec === 'add') {

      $stock += $diff;

    } elseif ($inc_dec === 'remove') {

      $stock -= $diff;

    }

    update_post_meta($id, '_stock', $stock);

  }

  wp_die();

}
add_action('wp_ajax_optics_pos_incdec_stock', 'optics_pos_incdec_stock');