<?php
/**
 * Add custom post metas to rest api
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_reg_post_meta() {

  /**
   * Sales
   */
  register_rest_field('opos-sales', '_sales_no', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_sales_no', true);
    },
  ));
  register_rest_field('opos-sales', '_customer', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_customer', true);
    },
  ));
  register_rest_field('opos-sales', '_wc_name', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_wc_name', true);
    },
  ));
  register_rest_field('opos-sales', '_order_date', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_order_date', true);
    },
  ));
  register_rest_field('opos-sales', '_delivery_date', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_delivery_date', true);
    },
  ));
  register_rest_field('opos-sales', '_delivered_date', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_delivered_date', true);
    },
  ));
  register_rest_field('opos-sales', '_lab', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_lab', true);
    },
  ));
  register_rest_field('opos-sales', '_total', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_total', true);
    },
  ));
  register_rest_field('opos-sales', '_advance', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_advance', true);
    },
  ));
  register_rest_field('opos-sales', '_paid', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_paid', true);
    },
  ));
  register_rest_field('opos-sales', '_pending_amount', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_pending_amount', true);
    },
  ));
  register_rest_field('opos-sales', '_status', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_status', true);
    },
  ));

  /**
   * Frames
   */
  register_rest_field('opos-frames', '_frame_name', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_frame_name', true);
    },
  ));
  register_rest_field('opos-frames', '_barcode', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_barcode', true);
    },
  ));
  register_rest_field('opos-frames', '_price', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_price', true);
    },
  ));
  register_rest_field('opos-frames', '_cost', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_cost', true);
    },
  ));
  register_rest_field('opos-frames', '_stock', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_stock', true);
    },
  ));
  register_rest_field('opos-frames', '_stock_date', array(
    'get_callback' => function($post_arr) {
      return get_post_meta($post_arr['id'], '_stock_date', true);
    },
  ));

}
add_action('rest_api_init', 'optics_pos_reg_post_meta');

/**
 * Filter user fields for REST API
 */
function optics_pos_filter_users($response, $user, $request) {

  $response->data['first_name'] = get_user_meta( $user->ID, 'first_name', true );
  $response->data['last_name'] = get_user_meta( $user->ID, 'last_name', true );

  return $response;

}
add_filter('rest_prepare_user', 'optics_pos_filter_users', 10, 3);

/**
 * Extend WP REST API
 *
 * @example http://tudominio.test/wp-json/wp/v2/ajde_events?year=2020&month=3
 */
function optics_pos_extend_sales_api($args, $request) {

  $meta_key = sanitize_text_field($request['meta_key']);
  $meta_value = sanitize_text_field($request['meta_value']);
  $compare = sanitize_text_field($request['compare']);
  $from_date = sanitize_text_field($request['from_date']);
  $till_date = sanitize_text_field($request['till_date']);

  if (!empty($meta_key)) {
    $meta_query = array(
      array(
        'key' => $meta_key,
        'value' => $meta_value,
        'compare' => $compare
      )
    );
  } else {
    $meta_query = array();
  }

  $args += array(
    'meta_query' => array(
      $meta_query,
      Helper::order_date_query($from_date, $till_date)
    )
  );

  return $args;

}
add_filter('rest_opos-sales_query', 'optics_pos_extend_sales_api', 20, 2);
