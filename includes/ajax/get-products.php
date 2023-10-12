<?php
/**
 * Get products using AJAX
 *
 * @package Optics POS
 */

function optics_pos_get_products() {

	if (isset($_REQUEST)) :

		$keyword = $_REQUEST['keyword'];
		$data = array();

		$args = array(
      'post_type'	=> 'opos-frames',
      'posts_per_page' => -1
		);
		$query = new WP_Query($args);

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) :
				$query->the_post();

        $id = get_the_ID();
        $frame_name = get_post_meta($id, '_frame_name', true);
				$barcode = get_post_meta($id, '_barcode', true);
        $price = get_post_meta($id, '_price', true);

				$title = $frame_name . ' - ' . $barcode;
				if (stripos($title, $keyword) === false) {
          continue;
				}

				$stock = get_post_meta($id, '_stock', true);
				if ($stock <= 0 || empty($stock)) {
          continue;
				}

				$data[] = array(
					'price' => $price,
					'value' => $id,
					'label' => $frame_name . ' - ' . $barcode
				);

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		}

		echo wp_json_encode($data);

	endif;

}
add_action( 'wp_ajax_optics_pos_get_products', 'optics_pos_get_products' );
