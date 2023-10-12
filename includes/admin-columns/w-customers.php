<?php
/**
 * Custom MetaBox for Adding custom columns for walk-in customers CPT.
 *
 * @package Optics POS
 */

/**
 * Add the custom columns to the book post type
 */
function optics_pos_custom_w_customers_columns($columns) {

  unset($columns['date']);

  $columns['contact_no'] = esc_html__('Contact No', 'optics-pos');
  $columns['date'] = esc_html__('Date', 'optics-pos');

  return $columns;

}
add_filter('manage_opos-w-customers_posts_columns', 'optics_pos_custom_w_customers_columns');

/**
 * Add the data to the custom columns for the book post type
 */
function optics_pos_w_customers_column($column, $post_id) {

	switch ($column) {

	  case 'contact_no' :
      $terms = get_post_meta($post_id, '_contactno', true);
      if ( isset($terms) ) {
        echo esc_html($terms);
      } else {
        echo esc_html__( 'Unable to get contact no', 'optics-pos' );
      }
      break;

	}

}
add_action('manage_opos-w-customers_posts_custom_column' , 'optics_pos_w_customers_column', 10, 2);
