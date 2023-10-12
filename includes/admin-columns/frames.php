<?php
/**
 * Custom MetaBox for Adding custom columns for frames CPT.
 *
 * @package Optics POS
 */

/**
 * Add the custom columns to the book post type
 */
function optics_pos_custom_frames_columns($columns) {

  unset($columns['date']);

  $columns['featured_img'] = esc_html__('Image', 'optics-pos');
  $columns['barcode'] = esc_html__('Barcode', 'optics-pos');
  $columns['price'] = esc_html__('Price', 'optics-pos');
  $columns['cost'] = esc_html__('Cost', 'optics-pos');
  $columns['date'] = esc_html__('Date', 'optics-pos');
  $columns['stock'] = esc_html__('Stock', 'optics-pos');

  return $columns;

}
add_filter('manage_opos-frames_posts_columns', 'optics_pos_custom_frames_columns');

/**
 * Add the data to the custom columns for the book post type
 */
function optics_pos_frames_column($column, $post_id) {

	switch ($column) {

	  case 'featured_img' :
      if (has_post_thumbnail($post_id)) {
        the_post_thumbnail(['60', '60']);
      } else {
        echo '<img src="' . esc_url(optics_pos_directory_uri() . '/assets/img/placeholder.png') . '" width="60" height="60" class="attachment-60x60 size-60x60 wp-post-image" loading="lazy">';
      }
      break;

    case 'barcode' :
      $terms = get_post_meta($post_id, '_barcode', true);
      if (isset($terms)) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get barcode', 'optics-pos' );
      }
      break;

	  case 'price' :
      $terms = get_post_meta($post_id, '_price', true);
      if (isset($terms)) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get price', 'optics-pos' );
      }
      break;

	  case 'cost' :
      $terms = get_post_meta($post_id, '_cost', true);
      if (isset($terms)) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get cost', 'optics-pos' );
      }
      break;

	  case 'stock' :
      $terms = get_post_meta($post_id, '_stock', true);
      if (isset($terms)) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get stock', 'optics-pos' );
      }
      break;

	}

}
add_action('manage_opos-frames_posts_custom_column' , 'optics_pos_frames_column', 10, 2);
