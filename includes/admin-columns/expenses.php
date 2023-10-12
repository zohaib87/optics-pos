<?php
/**
 * Custom MetaBox for Adding custom columns for expenses CPT.
 *
 * @package Optics POS
 */

/**
 * Add the custom columns to the book post type
 */
function optics_pos_custom_expenses_columns($columns) {

  unset($columns['date']);

  $columns['desc'] = esc_html__('Description', 'optics-pos');
  $columns['expense'] = esc_html__('Expense', 'optics-pos');
  $columns['cost'] = esc_html__('Cost', 'optics-pos');
  $columns['date'] = esc_html__('Date', 'optics-pos');

  return $columns;

}
add_filter('manage_opos-expenses_posts_columns', 'optics_pos_custom_expenses_columns');

/**
 * Add the data to the custom columns for the book post type
 */
function optics_pos_expenses_column($column, $post_id) {

	switch ($column) {

	  case 'desc' :
      $terms = get_post_meta( $post_id, '_exp_cf', true );;
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get cost', 'optics-pos' );
      }
      break;

    case 'expense' :
      $terms = get_post_meta( $post_id, '_expense', true );;
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get expense', 'optics-pos' );
      }
      break;

    case 'cost' :
      $terms = get_post_meta($post_id, '_exp_cost', true);
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get cost', 'optics-pos' );
      }
      break;

	}

}
add_action('manage_opos-expenses_posts_custom_column' , 'optics_pos_expenses_column', 10, 2);
