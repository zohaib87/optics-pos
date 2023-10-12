<?php
/**
 * Add selected product and subtract from stock using AJAX
 *
 * @package Optics POS
 */

function optics_pos_add_product() {

	if ( isset($_REQUEST) ) :

    $id = $_REQUEST['id'];
    $sr_no = $_REQUEST['srNo'];
    $stock = get_post_meta( $id, '_stock', true );

    if ($stock > 0) {

	    $stock--;
	    update_post_meta( $id, '_stock', $stock );

      if (has_post_thumbnail($id)) {
        $img = get_the_post_thumbnail($id, ['60', '60']);
      } else {
        $img = '<img src="' . esc_url(optics_pos_directory_uri() . '/assets/img/placeholder.png') . '" width="60" height="60" class="attachment-60x60 size-60x60 wp-post-image" loading="lazy">';
      }
	    $title = get_the_title($id);
	    $price = get_post_meta($id, '_price', true);

	    $args = array(
			  'post_type'	=> 'opos-glasses',
			  'numberposts'	=> -1
			);
			$glasses = get_posts($args); ?>

	  	<tr class="ui-sortable-handle">
				<td class="text-center sr-no"><?php echo esc_html($sr_no); ?></td>

				<td>
					<span class="product-name"><?php echo esc_html($title); ?></span>
					<a href="/wp-admin/post.php?post=<?php esc_attr_e($id); ?>&action=edit" class="float-right" target="_blank">Edit</a>
					<input type="hidden" name="p_titles[]" class="title" value="<?php echo esc_attr($title); ?>">
					<input type="hidden" name="p_ids[]" class="id" value="<?php echo esc_attr($id); ?>">
				</td>

        <td><?php echo $img; ?></td>

				<td class="opos-field">
					<input type="number" name="p_quantities[]" class="quantity opos-field-sm" data-quantity="1" value="1">
				</td>

				<td class="opos-field">
					<input type="number" step="any" name="p_prices[]" class="price opos-field-sm" value="<?php echo esc_attr($price); ?>">
				</td>

				<td class="opos-field">
					<select name="lens_ids[]" class="lens-id opos-select2 opos-field-md">
						<option value="" selected disabled>-- Select Lens --</option>
						<?php
							foreach ($glasses as $glass) :
								$glass_stock = get_post_meta($glass->ID, '_lens_stock', true);
								if ($glass_stock > 0) {
									echo '<option value="' . esc_attr($glass->ID) . '">' . esc_html($glass->post_title) . '</option>';
								}
							endforeach;
						?>
					</select>
					<input type="hidden" name="lens_id" class="lens-id-h" value=""><br>

					<label>Left Lens:
						<select name="left_lenses[]" class="left-lens opos-select2 opos-field-md">
							<option value="" selected disabled>-- Select No --</option>
						</select>
					</label> <br>

					<label>Right Lens:
						<select name="right_lenses[]" class="right-lens opos-select2 opos-field-md">
							<option value="" selected disabled>-- Select No --</option>
						</select>
					</label> <br>

					<label>Price:
					<input type="number" step="any" name="lens_prices[]" class="lens-price opos-field-md" value="" autocomplete="off"></label>
				</td>

				<td class="opos-field">
					<input type="number" name="p_discounts[]" class="discount opos-field-sm" value="">
				</td>

				<td class="text-center del"><span class="dashicons dashicons-no-alt"></span></td>
			</tr> <?php

	  }

  endif;

}
add_action( 'wp_ajax_optics_pos_add_product', 'optics_pos_add_product' );
