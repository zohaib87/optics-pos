<?php
/**
 * Custom Fields functions for Walk-in Customers CPT.
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

abstract class Optics_POS_WalkinCustomers {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-w-customers' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_w_customers_meta_box', // Unique ID
        'Customer Details', // Box title
        [ self::class, 'html' ], // Content callback, must be of type callable
        $screen, // Post type
        'advanced',
        'high'
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html( $post ) {

    $sales = get_posts(array(
      'post_type'	=> 'opos-sales',
			'numberposts'	=> -1,
      'post_status' => 'publish',
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => '_customer',
          'value' => get_the_ID(),
          'compare' => '=='
        ),
        array(
          'key' => '_wc_id',
          'value' => get_the_ID(),
          'compare' => '=='
        )
      )
		));

		$full_name = get_post_meta( $post->ID, '_full_name', true );
    $contactno = get_post_meta( $post->ID, '_contactno', true );
    $address = get_post_meta( $post->ID, '_address', true );

    $completed_orders = Helper::user_total_orders('opos-sales', $contactno, 'completed');
    $pending_orders = Helper::user_total_orders('opos-sales', $contactno, 'pending');

    ?>

    <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_name">Full Name:</label>
		  </div>
		  <div class="opos-input">
		    <input type="text" name="wc_name" id="wc_name" value="<?php echo esc_attr($full_name); ?>">
		  </div>
	  </div>

	  <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_contact">Contact:</label>
		  </div>
		  <div class="opos-input">
		    <input type="text" name="wc_contact" id="wc_contact" value="<?php echo esc_attr($contactno); ?>">
		  </div>
	  </div>

	  <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_address">Address:</label>
		  </div>
		  <div class="opos-input">
		    <input type="text" name="wc_address" id="wc_address" value="<?php echo esc_attr($address); ?>">
		  </div>
	  </div>

    <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_completed">Total Completed Orders:</label>
		  </div>
		  <div class="opos-input">
		    <input type="text" name="wc_completed" id="wc_completed" value="<?php echo esc_attr($completed_orders); ?>" readonly>
		  </div>
	  </div>

    <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_pending">Total Pending Orders:</label>
		  </div>
		  <div class="opos-input">
		    <input type="text" name="wc_pending" id="wc_pending" value="<?php echo esc_attr($pending_orders); ?>" readonly>
		  </div>
	  </div>

    <div class="opos-field">
    	<div class="opos-label">
		    <label for="wc_pending">Orders:</label>
		  </div>
		  <div class="opos-input">
        <?php
          $count = count($sales);
          $i = 1;

          foreach ($sales as $sale) {
            $order_no = get_post_meta($sale->ID, '_sales_no', true);
            echo '<a href="' . get_edit_post_link($sale->ID) . '">' . esc_html($order_no) . '</a>';
            echo ($i === $count) ? '' : ', ';
            $i++;
          }
        ?>
		  </div>
	  </div>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save( int $post_id ) {

  	$full_name = '';

    if ( array_key_exists( 'wc_name', $_POST ) ) {
    	$full_name = sanitize_text_field($_POST['wc_name']);
      update_post_meta($post_id, '_full_name', $full_name);
    }

    if ( array_key_exists( 'wc_contact', $_POST ) ) {
    	$contactno = sanitize_text_field($_POST['wc_contact']);
      update_post_meta($post_id, '_contactno', $contactno);
    }

    if ( array_key_exists( 'wc_address', $_POST ) ) {
    	$address = sanitize_text_field($_POST['wc_address']);
      update_post_meta($post_id, '_address', $address);
    }

    if (get_post_type($post_id) == 'opos-w-customers') :

	  	static $updated = false;

	    if ($updated) {
	      return;
	    }

	    $updated = true;

	    wp_update_post([
	    	'post_type' => 'opos-w-customers',
	    	'ID' => $post_id,
	    	'post_title' => $full_name . ' - ' . $post_id,
	    ]);

    endif;

  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_WalkinCustomers', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_WalkinCustomers', 'save' ] );
