<?php
/**
 * Get customer data using AJAX
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_customer_data() {

	$data = array();

  if ( isset($_POST) ) {

    $user_id = $_POST['user_id'];

    if ( strpos($user_id, 'wc-') !== false ) {

      $user_id = str_replace('wc-', '', $user_id);

      $data['name'] = get_post_meta($user_id, '_full_name', true);
      $data['id'] = $user_id;
      $data['email'] = 'N/A';
      $data['company'] = 'N/A';
      $data['contactno'] = get_post_meta( $user_id, '_contactno', true );
      $data['address'] = get_post_meta( $user_id, '_address', true );
      $data['city'] = 'N/A';
      $data['postalcode'] = 'N/A';
      $data['orders'] = 'N/A';

    } else {

      $user_data = get_userdata($user_id);

      $data['name'] = $user_data->first_name . ' ' . $user_data->last_name;
      $data['id'] = $user_id;
      $data['email'] = $user_data->user_email;
      $data['company'] = get_user_meta($user_id, 'opos_company', true );
      $data['contactno'] = get_user_meta($user_id, 'opos_contactno', true);
      $data['address'] = get_user_meta($user_id, 'opos_address', true);
      $data['city'] = get_user_meta($user_id, 'opos_city', true);
      $data['postalcode'] = get_user_meta($user_id, 'opos_postalcode', true);

      $data['orders'] = Helper::user_total_orders('opos-sales', $user_id);

    }

	}

	echo wp_json_encode($data);

}
add_action( 'wp_ajax_optics_pos_customer_data', 'optics_pos_customer_data' );
