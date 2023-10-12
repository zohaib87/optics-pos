<?php
/**
 * Get labs data from sales using AJAX
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_get_labs() {

	if (isset($_REQUEST)) :

    $page_no = $_REQUEST['page_no'];
		$from_date = $_REQUEST['from_date'];
		$till_date = $_REQUEST['till_date'];
		$store = $_REQUEST['store'];
    $final_data = array();
    $page_count = 1;
    $table = '';

		$query = new WP_Query([
      'post_type'	=> 'opos-sales',
      'posts_per_page' => 20,
      'paged' => $page_no,
      'meta_query' => array(
        array(
          'key' => '_lab',
					'value' => '',
					'compare' => '!=',
        ),
        Helper::order_date_query($from_date, $till_date)
      )
    ]);

		if ( $query->have_posts() && $store == get_site_url() ) {

      $page_count = $query->max_num_pages;

			while ( $query->have_posts() ) :
				$query->the_post();

				$id = get_the_ID();
				$sales_no = get_post_meta($id, '_sales_no', true);
				$lab = get_post_meta($id, '_lab', true);
				$date = get_post_meta( $id, '_order_date', true );

        $table .= '<tr>
          <td data-label="Order No"><a href="' . get_edit_post_link($id) . '">' . esc_html($sales_no) . '</a></td>
          <td data-label="Order Date">' . esc_html($date) .'</td>
          <td data-label="Lab Data">' . wp_kses_post($lab) .'</td>
        </tr>';

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		} else {

      $endpoints = (!empty($from_date)) ? '&from_date=' . $from_date : '';
      $endpoints .= (!empty($till_date)) ? '&till_date=' . $till_date : '';
      $endpoints .= '&meta_key=_lab&meta_value=&compare=!=';

      $rapi_url = esc_attr($store) . '/wp-json/wp/v2/opos-sales/';
      $pre_request = wp_remote_get(esc_url_raw($rapi_url.'?per_page=20' . $endpoints));
      $page_count = wp_remote_retrieve_header($pre_request, 'x-wp-totalpages');

      $request = wp_remote_get(esc_url_raw($rapi_url.'?per_page=20&page='.intval($page_no) . $endpoints));

      if (!is_wp_error($request)) {

        $body = wp_remote_retrieve_body($request);
        $data = json_decode($body);

        if (!isset($data->code)) {

          foreach ($data as $sale) {

            $sales_no = $sale->_sales_no;
            $order_date = $sale->_order_date;
            $lab = $sale->_lab;

            $table .= '<tr>
              <td data-label="Order No">' . esc_html($sales_no) .'</td>
              <td data-label="Order Date">' . esc_html($order_date) .'</td>
              <td data-label="Lab Data">' . wp_kses_post($lab) .'</td>
            </tr>';

          }

        }

      }

    }

    $paging_nav = ((int) $page_count === 1) ? '' : Helper::paging_nav(1, $page_no, $page_count);

    $final_data = [
      'table' => $table,
      'paging' => $paging_nav
    ];

    echo wp_json_encode($final_data);

	endif;

  wp_die();

}
add_action( 'wp_ajax_optics_pos_get_labs', 'optics_pos_get_labs' );
