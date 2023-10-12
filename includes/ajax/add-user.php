<?php 
/**
 * Add new user using AJAX
 *
 * @package Optics POS
 */

function optics_pos_add_user() {

	if ( isset($_REQUEST) ) :

		if ( !current_user_can('edit_user') ) { 
	    return; 
	  }

    $username = sanitize_user($_REQUEST['username']);

	  if (username_exists($username)) {
	  	echo 'user-exists';
	  	return;
	  }

    $email = sanitize_email($_REQUEST['email']);
    
	  if (email_exists($email)) {
	  	echo 'email-exists';
	  	return;
	  }

    $password = $_REQUEST['password'];
    $firstname = sanitize_text_field($_REQUEST['firstname']);
    $lastname = sanitize_text_field($_REQUEST['lastname']);
    $company = sanitize_text_field($_REQUEST['company']);
    $contactno = sanitize_text_field($_REQUEST['contactno']);
    $address = sanitize_text_field($_REQUEST['address']);
    $city = sanitize_text_field($_REQUEST['city']);
    $postalcode = intval($_REQUEST['postalcode']);

    $user_id = wp_insert_user([
	    'user_login' => $username,
	    'user_pass' => $password,
	    'user_email' => $email,
	    'first_name' => $firstname,
	    'last_name' => $lastname,
		]);
		
    update_user_meta($user_id, 'opos_company', $company);
	  update_user_meta($user_id, 'opos_contactno', $contactno);
	  update_user_meta($user_id, 'opos_address', $address);
	  update_user_meta($user_id, 'opos_city', $city);
	  update_user_meta($user_id, 'opos_postalcode', $postalcode);

	  wp_new_user_notification($user_id, null, 'user');

  endif;

}
add_action( 'wp_ajax_optics_pos_add_user', 'optics_pos_add_user' );
