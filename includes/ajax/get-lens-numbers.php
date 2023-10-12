<?php 
/**
 * Get lens numbers of current glass using AJAX
 *
 * @package Optics POS
 */

function optics_pos_get_lens_numbers() {

	if ( isset($_REQUEST) ) :

    $lens_id = $_REQUEST['lens_id'];
    
    $stock = get_post_meta( $lens_id, '_lens_stock', true );

    if ($stock > 0) {

	    $stock--;
	    update_post_meta( $lens_id, '_lens_stock', $stock );

			$glass_terms = get_the_terms($lens_id, 'opos-glasses-cat');

			?><option value="" selected>-- Select No --</option><?php
	    if ($glass_terms) :
				foreach ($glass_terms as $term) :
					?><option value="<?php echo esc_attr($term->term_id); ?>" <?php selected( $left_lens, $term->term_id ); ?>><?php echo esc_html($term->name); ?></option><?php
				endforeach;
			endif;

		}

  endif;

}
add_action( 'wp_ajax_optics_pos_get_lens_numbers', 'optics_pos_get_lens_numbers' );
