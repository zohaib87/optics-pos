<?php
/**
 * Custom Fields for user profile.
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_user_profile_fields( $user ) {

	$company = get_the_author_meta( 'opos_company', $user->ID );
	$contactno = get_the_author_meta( 'opos_contactno', $user->ID );
	$address = get_the_author_meta( 'opos_address', $user->ID );
	$city = get_the_author_meta( 'opos_city', $user->ID );
	$postalcode = get_the_author_meta( 'opos_postalcode', $user->ID );

	$orders = Helper::user_total_orders('opos-sales', $user->ID);

	?>
    <h3><?php echo esc_html__('Optics POS Extra Fields', 'optics-pos'); ?></h3>

    <table class="form-table">
      <tr>
        <th><label for="opos_company"><?php echo esc_html__('Company', 'optics-pos'); ?></label></th>
        <td>
          <input type="text" name="opos_company" id="opos_company" value="<?php echo esc_attr($company); ?>" class="regular-text"><br>
        </td>
      </tr>

      <tr>
        <th><label for="opos_contactno"><?php echo esc_html__('Contact No', 'optics-pos'); ?></label></th>
        <td>
          <input type="text" name="opos_contactno" id="opos_contactno" value="<?php echo esc_attr($contactno); ?>" class="regular-text"><br>
          <span class="description"><?php echo esc_html__('Contact no of the customer', 'optics-pos'); ?></span>
        </td>
      </tr>

      <tr>
        <th><label for="opos_orders"><?php echo esc_html__('Total Orders', 'optics-pos'); ?></label></th>
        <td>
          <input type="text" name="opos_orders" id="opos_orders" value="<?php echo esc_attr($orders); ?>" class="regular-text" disabled><br>
          <span class="description"><?php echo esc_html__('Total no of orders made by this customer till today.', 'optics-pos'); ?></span>
        </td>
      </tr>

      <tr>
        <th><label for="opos_address"><?php echo esc_html__('Address', 'optics-pos'); ?></label></th>
        <td>
          <input type="text" name="opos_address" id="opos_address" value="<?php echo esc_attr($address); ?>" class="regular-text"><br>
        </td>
      </tr>

      <tr>
        <th><label for="opos_city"><?php echo esc_html__('City', 'optics-pos'); ?></label></th>
        <td>
          <input type="text" name="opos_city" id="opos_city" value="<?php echo esc_attr($city); ?>" class="regular-text"><br>
        </td>
      </tr>

      <tr>
      <th><label for="opos_postalcode"><?php echo esc_html__('Postal Code', 'optics-pos'); ?></label></th>
        <td>
          <input type="number" name="opos_postalcode" id="opos_postalcode" value="<?php echo esc_attr($postalcode); ?>" class="regular-text"><br>
        </td>
      </tr>
    </table>
	<?php

}
add_action( 'show_user_profile', 'optics_pos_user_profile_fields' );
add_action( 'edit_user_profile', 'optics_pos_user_profile_fields' );

/**
 * Save user profile fields
 */
function optics_pos_save_user_profile_fields( $user_id ) {

  if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
    return;
  }

  if ( !current_user_can('edit_user', $user_id) ) {
    return false;
  }

  $opos_company = sanitize_text_field($_POST['opos_company']);
  $opos_contactno = sanitize_text_field($_POST['opos_contactno']);
  $opos_address = sanitize_text_field($_POST['opos_address']);
  $opos_city = sanitize_text_field($_POST['opos_city']);
  $opos_postalcode = intval($_POST['opos_postalcode']);

  update_user_meta($user_id, 'opos_company', $opos_company);
  update_user_meta($user_id, 'opos_contactno', $opos_contactno);
  update_user_meta($user_id, 'opos_address', $opos_address);
  update_user_meta($user_id, 'opos_city', $opos_city);
  update_user_meta($user_id, 'opos_postalcode', $opos_postalcode);

}
add_action( 'personal_options_update', 'optics_pos_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'optics_pos_save_user_profile_fields' );
