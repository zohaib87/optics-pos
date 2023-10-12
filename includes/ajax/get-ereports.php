<?php
/**
 * Get labs data from sales using AJAX
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_get_ereports() {

	if (isset($_REQUEST)) :

    $page_no = $_REQUEST['page_no'];
		$from_date = $_REQUEST['from_date'];
		$till_date = $_REQUEST['till_date'];
    $final_data = array();
    $page_count = 1;
    $table = '';

    /**
     * List Expenses
     */
		$query = new WP_Query([
      'post_type'	=> 'opos-expenses',
      'posts_per_page' => 20,
      'paged' => $page_no,
      'meta_query' => array(
        Helper::order_date_query($from_date, $till_date, '_exp_date')
      )
    ]);

		if ( $query->have_posts() ) {

      $page_count = $query->max_num_pages;

			while ( $query->have_posts() ) :
				$query->the_post();

				$id = get_the_ID();
        $exp_desc = get_post_meta( $id, '_exp_cf', true );
        $expense = get_post_meta( $id, '_expense', true );
        $exp_cost = get_post_meta( $id, '_exp_cost', true );
        $exp_date = get_post_meta( $id, '_exp_date', true );

        $table .= '<tr>
          <td data-label="Expense ID"><a href="' . get_edit_post_link($id) . '">' . esc_html($id) . '</a></td>
          <td data-label="Description">' . esc_html($exp_desc) .'</td>
          <td data-label="Expense">' . wp_kses_post($expense) .'</td>
          <td data-label="Cost">' . wp_kses_post($exp_cost) .'</td>
          <td data-label="Date">' . wp_kses_post($exp_date) .'</td>
        </tr>';

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

    }

    /**
     * Calculate expense and cost
     */
    $exp_query = new WP_Query([
      'post_type'	=> 'opos-expenses',
      'posts_per_page' => -1,
      'meta_query' => array(
        Helper::order_date_query($from_date, $till_date, '_exp_date')
      )
    ]);

    $t_exp = $t_cost = 0;

    if ($exp_query->have_posts()) {

      while ($exp_query->have_posts()) {
        $exp_query->the_post();

        $id = get_the_ID();
        $t_exp += get_post_meta( $id, '_expense', true );
        $t_cost += get_post_meta( $id, '_exp_cost', true );

      }

      wp_reset_postdata();

    }

    $paging_nav = ((int) $page_count === 1) ? '' : Helper::paging_nav(1, $page_no, $page_count);

    $final_data = [
      'table' => $table,
      'paging' => $paging_nav,
      'expense' => $t_exp,
      'cost' => $t_cost
    ];

    echo wp_json_encode($final_data);

	endif;

  wp_die();

}
add_action( 'wp_ajax_optics_pos_get_ereports', 'optics_pos_get_ereports' );
