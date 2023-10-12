<?php
/**
 * Get reports using AJAX
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_get_reports() {

	if (isset($_REQUEST)) :

    $page_no = $_REQUEST['page_no'];
		$from_date = $_REQUEST['from_date'];
		$till_date = $_REQUEST['till_date'];
		$status = $_REQUEST['status'];
    $final_data = array();
    $page_count = 1;
    $table = '';

    if ('all' == $status) {

      $status_query = array();
      $or_query = array(
        'relation' => 'OR',
        Helper::order_date_query($from_date, $till_date, '_order_date'),
        array(
          'relation' => 'AND',
          Helper::order_date_query($from_date, $till_date, '_delivered_date'),
          array(
            'key' => '_paid',
            'value' => 0,
            'compare' => '>'
          ),
          array(
            'key' => '_status',
            'value' => 'completed',
            'compare' => '='
          )
        ),
      );

    } elseif ('to-be-paid' == $status) {

      $status_query = array(
        'relation' => 'AND',
        Helper::order_date_query($from_date, $till_date, '_delivered_date'),
        array(
          'key' => '_paid',
          'value' => 0,
          'compare' => '>'
        ),
        array(
          'key' => '_status',
          'value' => 'completed',
          'compare' => '='
        )
      );
      $or_query = array();

    } else {

      $status_query = array(
        'key' => '_status',
        'value' => $status,
        'compare' => '=',
      );
      $or_query = Helper::order_date_query($from_date, $till_date, '_order_date');

    }

		$query = new WP_Query([
      'post_type'	=> 'opos-sales',
      'posts_per_page' => 20,
      'paged' => $page_no,
      'post_status' => 'publish',
      'meta_query' => array(
        $status_query,
        $or_query
      )
    ]);

		if ( $query->have_posts() ) {

      $page_count = $query->max_num_pages;

			while ( $query->have_posts() ) :
				$query->the_post();

				$id = get_the_ID();
				$sales_no = get_post_meta($id, '_sales_no', true);
				$order_status = get_post_meta($id, '_status', true);
				$customer = get_post_meta($id, '_customer', true);
		    $order_date = get_post_meta($id, '_order_date', true);
		    $delivery_date = get_post_meta( $id, '_delivery_date', true );
		    $delivered_date = get_post_meta( $id, '_delivered_date', true );
		    $total = get_post_meta( $id, '_total', true );
		    $to_be_paid = get_post_meta( $id, '_to_be_paid', true );
		    $advance = get_post_meta( $id, '_advance', true );
        $paid = get_post_meta( $id, '_paid', true );
		    $pending = get_post_meta( $id, '_pending_amount', true );

        $p_titles = get_post_meta($id, '_p_titles', true);
        $products = array();

        if (isset($p_titles[0])) {
          foreach ($p_titles as $title) {
            $products[] = $title;
          }
        }
        $products = implode(', ', $products);

		    $user_data = (object) get_userdata($customer);
				$full_name = $user_data->first_name . ' ' . $user_data->last_name;
				$name = ($full_name != ' ') ? $full_name : $user_data->user_nicename;

				if (empty($name)) {
					$wc_id = get_post_meta($id, '_wc_id', true);
					$name = get_post_meta($wc_id, '_full_name', true);

					if (empty($name)) :
						$name = get_post_meta($id, '_wc_name', true);
					endif;
				}

        $table .= '<tr>
          <td data-label="Order No"><a href="' . get_edit_post_link($id) . '">' . esc_html($sales_no) . '</a></td>
          <td data-label="Customer">' . esc_html($name) . '</td>
          <td data-label="Date of Order">' . esc_html($order_date) . '</td>
          <td data-label="Date of Delivery">' . esc_html($delivery_date) . '</td>
          <td data-label="Delivered Date">' . esc_html($delivered_date) . '</td>
          <td data-label="Grand Total" class="r-total">' . esc_html($total) . '</td>
          <td data-label="To Be Paid" class="r-to-be-paid">' . esc_html($to_be_paid) . '</td>
          <td data-label="Advance" class="r-advance">' . esc_html($advance) . '</td>
          <td data-label="Paid On Delivery" class="r-paid">' . esc_html($paid) . '</td>
          <td data-label="Pending" class="r-pending">' . esc_html($pending) . '</td>
          <td data-label="Status"><span class="sale-status ' . esc_attr($order_status) . '">' . esc_html(ucfirst($order_status)) . '</span></td>
          <td data-label="Sold Products"><span class="sold-products">' . esc_html($products) . '</span></td>
        </tr>';

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		}

    /**
     * Expenses
     */
    $expense_query = new WP_Query([
      'post_type'	=> 'opos-expenses',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
        Helper::order_date_query($from_date, $till_date, '_exp_date')
      )
    ]);

    $exp_cost = 0;

    if ( $expense_query->have_posts() ) {

			while ( $expense_query->have_posts() ) :
				$expense_query->the_post();

        $exp_id = get_the_ID();
        $exp_cost += (float) get_post_meta($exp_id, '_exp_cost', true);

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		}

    /**
     * Sales Current Stats
     */
    $total = 0;
    $to_be_paid = 0;
    $advance = 0;
    $paid = 0;
    $pending = 0;
    $c_status = 0;
    $p_status = 0;

    $sales_query = new WP_Query([
      'post_type'	=> 'opos-sales',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
        $status_query,
        $or_query
      )
    ]);

    if ( $sales_query->have_posts() ) {

      while ( $sales_query->have_posts() ) :
        $sales_query->the_post();

        $sales_id = get_the_ID();
        $order_date = get_post_meta($sales_id, '_order_date', true);
		    $delivered_date = get_post_meta( $sales_id, '_delivered_date', true );
        $tb_paid = (float) get_post_meta( $sales_id, '_to_be_paid', true );

        // check if this sale is queried by order date
        if (($order_date >= $from_date) && ($order_date <= $till_date)) {

          $advance += (float) get_post_meta( $sales_id, '_advance', true );
          $pending += (float) get_post_meta( $sales_id, '_pending_amount', true );

        }

        // check if this sale is queried by delivered date
        if (($delivered_date >= $from_date) && ($delivered_date <= $till_date)) {

          $paid += (float) get_post_meta( $sales_id, '_paid', true );

        }

        // check if to be paid amount is not zero
        if (empty($tb_paid) || $tb_paid == 0) {
          $to_be_paid += (float) get_post_meta( $sales_id, '_total', true );
        } else {
          $to_be_paid += $tb_paid;
        }

        $st = get_post_meta( $sales_id, '_status', true );

        if ($st == 'pending') {
          $p_status++;
        }
        if ($st == 'completed') {
          $c_status++;
        }

      endwhile;

      /* Restore original Post Data */
      wp_reset_postdata();

    }

    $paging_nav = ((int) $page_count === 1) ? '' : Helper::paging_nav(1, $page_no, $page_count);

    $final_data = [
      'table' => $table,
      'paging' => $paging_nav,
      'expense' => $exp_cost,
      'pStatus' => $p_status,
      'cStatus' => $c_status,
      'earned' => $to_be_paid,
      'advance' => $advance,
      'paid' => $paid,
      'pending' => $pending,
      'profit' => ($advance + $paid) - $exp_cost
    ];

    echo wp_json_encode($final_data);

  endif;

  wp_die();

}
add_action( 'wp_ajax_optics_pos_get_reports', 'optics_pos_get_reports' );
