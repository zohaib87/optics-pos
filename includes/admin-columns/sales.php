<?php
/**
 * Custom MetaBox for Adding custom columns for sales CPT.
 *
 * @package Optics POS
 */

/**
 * Add the custom columns to the book post type
 */
function optics_pos_custom_sales_columns($columns) {

  unset($columns['date']);

  $columns['salesman'] = esc_html__('Salesman', 'optics-pos');
  $columns['order_no'] = esc_html__('Order No', 'optics-pos');
  $columns['contact_no'] = esc_html__('Contact No', 'optics-pos');
  $columns['date'] = esc_html__('Date', 'optics-pos');
  $columns['sales_status'] = esc_html__( 'Sales Status', 'optics-pos' );

  return $columns;

}
add_filter('manage_opos-sales_posts_columns', 'optics_pos_custom_sales_columns');

/**
 * Add the data to the custom columns for the book post type
 */
function optics_pos_sales_column($column, $post_id) {

	switch ($column) {

    case 'salesman' :
      $terms = get_post_meta($post_id, '_salesman', true);
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get salesman', 'optics-pos' );
      }
      break;

    case 'order_no' :
      $terms = get_post_meta($post_id, '_sales_no', true);
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get order no', 'optics-pos' );
      }
      break;

    case 'contact_no' :
      $terms = get_post_meta($post_id, '_contactno', true);
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__('N/A', 'optics-pos');
      }
      break;

	  case 'sales_status' :
      $terms = get_post_meta($post_id, '_status', true);
      if ( isset($terms) ) {
        echo '<span class="' . esc_attr($terms) . '">' . esc_html(ucfirst($terms)) . '</span>';
      } else {
        echo esc_html__( 'Unable to get sale status', 'optics-pos' );
      }
      break;

	}

}
add_action('manage_opos-sales_posts_custom_column' , 'optics_pos_sales_column', 10, 2);
