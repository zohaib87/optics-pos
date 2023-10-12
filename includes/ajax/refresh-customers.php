<?php
/**
 * Refresh customers using AJAX
 *
 * @package Optics POS
 */

function optics_pos_refresh_customers() {

	?>
	<option value="" selected>-- Select Customer --</option>
  <option value="walk-in">Walk-in Customer</option>
  <?php 
	$users = get_users();
	$w_custoemrs = get_posts([
  	'post_type' => 'opos-w-customers',
  	'numberposts' => -1
  ]);

  foreach ($w_custoemrs as $w_customer) :
  	$name = get_post_meta($w_customer->ID, '_full_name', true);

  	echo '<option value="wc-' . esc_attr($w_customer->ID) . '">' . esc_html($name . ' - ' . $w_customer->ID) . '</option>';
  endforeach;

	foreach ($users as $user) :
		$user_data = get_userdata( $user->ID );
		$full_name = $user_data->first_name . ' ' . $user_data->last_name;
		$name = ($full_name != ' ') ? $full_name : $user->user_nicename;

		echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($name . ' - ' . $user->ID) . '</option>';
	endforeach;

}
add_action( 'wp_ajax_optics_pos_refresh_customers', 'optics_pos_refresh_customers' );
