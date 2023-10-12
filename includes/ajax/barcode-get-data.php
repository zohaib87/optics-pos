<?php
/**
 * Get data using barcode through REST API and AJAX
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_barcode_get_data() {

	if (isset($_REQUEST)) :

		$barcode = $_REQUEST['barcode'];
		$store = $_REQUEST['store'];
    $url = esc_attr($store) . '/wp-json/wp/v2/opos-frames/';
    $request = wp_remote_get(esc_url($url) . '?per_page=100');
    $total_pages = wp_remote_retrieve_header($request, 'x-wp-totalpages');
    $final_data = array();

    for ($i=1; $i <= $total_pages; $i++) {

      $request = wp_remote_get(esc_url($url) . '?per_page=100&page='.$i);

      if (!is_wp_error($request)) {

        $body = wp_remote_retrieve_body($request);
        $data = json_decode($body);

        foreach ($data as $frame) {

          if ($barcode !== $frame->_barcode) {
            continue;
          }

          // get feature image url
          $media_url = '';
          $attach_300 = '';
          $attach_150 = '';
          $attach_100 = '';
          $attach_60 = '';
          $media_id = (int) $frame->featured_media;

          if ($media_id > 0) {

            $media_req = wp_remote_get(esc_attr($store) . '/wp-json/wp/v2/media/' . esc_attr($media_id));

            if (!is_wp_error($media_req)) {
              $media = json_decode(wp_remote_retrieve_body($media_req));
              $media_url = $media->guid->rendered;
              $attach_id = Helper::insert_attachment_from_url($media_url);
              $attach_300 = wp_get_attachment_image_src($attach_id, [300, 300]);
              $attach_150 = wp_get_attachment_image_src($attach_id, [150, 150]);
              $attach_100 = wp_get_attachment_image_src($attach_id, [100, 100]);
              $attach_60 = wp_get_attachment_image_src($attach_id, [60, 60]);
            }

          }

          $final_data = [
            'name' => $frame->_frame_name,
            'barcode' => $frame->_barcode,
            'price' => $frame->_price,
            'cost' => $frame->_cost,
            'stock' => $frame->_stock,
            'stock_date' => $frame->_stock_date,
            'attach_id' => $attach_id,
            'media_url' => $media_url,
            'attach_300' => $attach_300[0],
            'attach_150' => $attach_150[0],
            'attach_100' => $attach_100[0],
            'attach_60' => $attach_60[0]
          ];

          echo wp_json_encode($final_data);

        }

      }

    }

	endif;

  wp_die();

}
add_action( 'wp_ajax_optics_pos_barcode_get_data', 'optics_pos_barcode_get_data' );
