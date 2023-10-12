<?php
/**
 * Custom MetaBox for product list.
 *
 * @package Optics POS
 */

abstract class Optics_POS_ProductListMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-sales' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_product_list_meta_box', // Unique ID
        'Order List', // Box title
        [ self::class, 'html' ], // Content callback, must be of type callable
        $screen // Post type
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html($post) {

    $last_sales = get_posts([
      'post_type' => 'opos-sales',
      'numberposts' => 1
    ]);

    if (!empty($last_sales)) {
      foreach ($last_sales as $last_sale) {

        $last_sale_ids = get_post_meta($last_sale->ID, '_p_ids', true);

        if (!empty($last_sale_ids)) {
          foreach ($last_sale_ids as $item_id) {
            $item_stock = get_post_meta($item_id, '_stock', true);
            if ( (int) $item_stock === 0 ) {
              wp_trash_post($item_id);
            }
          }
        }

      }
    }

  	$p_titles = get_post_meta( $post->ID, '_p_titles', true );
    $p_ids = get_post_meta( $post->ID, '_p_ids', true );
    $p_quantities = get_post_meta( $post->ID, '_p_quantities', true );
    $p_prices = get_post_meta( $post->ID, '_p_prices', true );
    $p_discounts = get_post_meta( $post->ID, '_p_discounts', true );

    $lens_ids = get_post_meta( $post->ID, '_lens_ids', true );
    $left_lenses = get_post_meta( $post->ID, '_left_lenses', true );
    $right_lenses = get_post_meta( $post->ID, '_right_lenses', true );
    $lens_prices = get_post_meta( $post->ID, '_lens_prices', true );

    $total_quantity = get_post_meta( $post->ID, '_total_quantity', true );
		$total_price = get_post_meta( $post->ID, '_total_price', true );
		$total_lens = get_post_meta( $post->ID, '_total_lens', true );
		$total_discount = get_post_meta( $post->ID, '_total_discount', true );

    $total = get_post_meta( $post->ID, '_total', true );
    $to_be_paid = get_post_meta( $post->ID, '_to_be_paid', true );
    $advance = get_post_meta( $post->ID, '_advance', true );
    $paid = get_post_meta( $post->ID, '_paid', true );
    $pending = get_post_meta( $post->ID, '_pending_amount', true );
    $status = get_post_meta( $post->ID, '_status', true );

    ?>

    <!-- List products and calculation -->
	  <div class="row">
  		<table class="opos-list-products table table-striped table-bordered table-hover">
  			<thead>
  				<tr>
  					<th scope="col">No.</th>
  					<th scope="col">Product Name</th>
            <th scope="col">Image</th>
  					<th scope="col">Quantity</th>
  					<th scope="col">Price</th>
  					<th scope="col">Lens</th>
  					<th scope="col">Discount</th>
  					<th scope="col"><span class="dashicons dashicons-trash"></span></th>
  				</tr>
  			</thead>
  			<tbody class="sortable">
  				<?php
	  				$i = 0;
	  				$sr_no = 1;

	  				if (isset($p_titles[$i])) :

	  					$count = count($p_titles);

					    while ($i < $count) {

					    	$title = $p_titles[$i];
					    	$id = isset($p_ids[$i]) ? $p_ids[$i] : '';
					    	$quantity = isset($p_quantities[$i]) ? $p_quantities[$i] : '';
					    	$price = isset($p_prices[$i]) ? $p_prices[$i] : '';
					    	$discount = isset($p_discounts[$i]) ? $p_discounts[$i] : '';

					    	$lens_id = isset($lens_ids[$i]) ? $lens_ids[$i] : '';
					    	$left_lens = isset($left_lenses[$i]) ? $left_lenses[$i] : '';
					    	$right_lens = isset($right_lenses[$i]) ? $right_lenses[$i] : '';
					    	$lens_price = isset($lens_prices[$i]) ? $lens_prices[$i] : '';

                if (has_post_thumbnail($id)) {
                  $img = get_the_post_thumbnail($id, ['60', '60']);
                } else {
                  $img = '<img src="' . esc_url(optics_pos_directory_uri() . '/assets/img/placeholder.png') . '" width="60" height="60" class="attachment-60x60 size-60x60 wp-post-image" loading="lazy">';
                }

					    	$glass_terms = get_the_terms($lens_id, 'opos-glasses-cat');

					    	?>
					    	<tr class="ui-sortable-handle">
									<td class="text-center sr-no"><?php echo esc_html($sr_no); ?></td>

									<td>
										<span class="product-name"><?php echo esc_html($title); ?></span>
										<a href="/wp-admin/post.php?post=<?php echo esc_attr($id); ?>&action=edit" class="float-right" target="_blank">Edit</a>
										<input type="hidden" name="p_titles[]" class="title" value="<?php echo esc_attr($title); ?>">
										<input type="hidden" name="p_ids[]" class="id" value="<?php echo esc_attr($id); ?>">
									</td>

                  <td><?php echo $img; ?></td>

									<td class="opos-field">
										<input type="number" name="p_quantities[]" class="quantity opos-field-sm" data-quantity="<?php echo esc_attr($quantity); ?>" value="<?php echo esc_attr($quantity); ?>">
									</td>

									<td class="opos-field">
										<input type="number" step="any" name="p_prices[]" class="price opos-field-sm" value="<?php echo esc_attr($price); ?>">
									</td>

									<td class="opos-field">
										<select name="lens_ids[]" class="lens-id opos-select2 opos-field-md">
											<option value="" <?php selected( $lens_id, '' ); ?> disabled>-- Select Lens --</option>
										  <?php
                        $args = array(
                          'post_type'	=> 'opos-glasses',
                          'numberposts' => -1
                        );
                        $glasses = get_posts($args);

                        foreach ($glasses as $glass) :
                          $glass_stock = get_post_meta($glass->ID, '_lens_stock', true);
                          if ($glass_stock > 0 || $lens_id == $glass->ID) {
                            ?><option value="<?php echo esc_attr($glass->ID) ?>" <?php selected($lens_id, $glass->ID); ?>><?php echo esc_html($glass->post_title) ?></option><?php
                          }
                        endforeach;
                      ?>
										</select>
										<input type="hidden" name="lens_id" class="lens-id-h" value=""><br>

										<label>Left Lens:
											<select name="left_lenses[]" class="left-lens opos-select2 opos-field-md">
												<option value="" <?php selected( $left_lens, '' ); ?> disabled>-- Select No --</option>
												<?php
													if ($glass_terms) {
														foreach ($glass_terms as $term) :
															?><option value="<?php echo esc_attr($term->term_id) ?>" <?php selected( $left_lens, $term->term_id ); ?>><?php echo esc_html($term->name) ?></option><?php
														endforeach;
													}
												?>
											</select>
										</label> <br>

										<label>Right Lens:
											<select name="right_lenses[]" class="right-lens opos-select2 opos-field-md">
												<option value="" <?php selected( $left_lens, '' ); ?> disabled>-- Select No --</option>
												<?php
													if ($glass_terms) {
														foreach ($glass_terms as $term) :
															?><option value="<?php echo esc_attr($term->term_id) ?>" <?php selected( $left_lens, $term->term_id ); ?>><?php echo esc_html($term->name) ?></option><?php
														endforeach;
													}
												?>
											</select>
										</label> <br>

										<label>Price:
										<input type="number" step="any" name="lens_prices[]" class="lens-price opos-field-md" value="<?php echo esc_attr($lens_price); ?>" autocomplete="off"></label>
									</td>

									<td class="opos-field">
										<input type="number" name="p_discounts[]" class="discount opos-field-sm" value="<?php echo esc_attr($discount); ?>">
									</td>

									<td class="text-center del"><span class="dashicons dashicons-no-alt"></span></td>
								</tr>
					    	<?php

					    	$i++;
					    	$sr_no++;

					    }

					  endif;
  				?>
  			</tbody>
  			<tfoot>
  				<tr id="tfoot" class="tfoot active">
  					<th colspan="3" class="text-left">Total</th>
  					<th class="text-center opos-field total-quantity">
  						<span><?php echo !empty($total_quantity) ? esc_attr($total_quantity) : '0'; ?></span>
  						<input type="hidden" name="total_quantity" class="total_quantity" value="<?php echo !empty($total_quantity) ? esc_attr($total_quantity) : '0'; ?>">
						</th>

  					<th class="text-center opos-field total-price">
  						<span><?php echo !empty($total_price) ? esc_attr($total_price) : '0'; ?></span>
  						<input type="hidden" name="total_price" class="total_price" value="<?php echo !empty($total_price) ? esc_attr($total_price) : '0'; ?>">
  					</th>

  					<th class="text-center opos-field total-lens">
  						<span><?php echo !empty($total_lens) ? esc_attr($total_lens) : '0'; ?></span>
  						<input type="hidden" name="total_lens" class="total_lens" value="<?php echo !empty($total_lens) ? esc_attr($total_lens) : '0'; ?>">
  					</th>

  					<th class="text-center opos-field total-discount">
  						<span><?php echo !empty($total_discount) ? esc_attr($total_discount) : '0'; ?></span>
  						<input type="hidden" name="total_discount" class="total_discount" value="<?php echo !empty($total_discount) ? esc_attr($total_discount) : '0'; ?>">
  					</th>

  					<th class="text-center"><span class="dashicons dashicons-trash"></span></th>
  				</tr>

  				<tr class="tfoot active">
  					<th colspan="5" class="text-right">Grand Total</th>
  					<th colspan="3" class="text-left opos-field grand-total">
  						<input type="number" step="any" name="total" class="total" value="<?php echo !empty($total) ? esc_attr($total) : '0'; ?>" readonly>
  					</th>
  				</tr>

          <tr class="tfoot active">
  					<th colspan="5" class="text-right">To Be Paid</th>
  					<th colspan="3" class="text-left opos-field to-be-paid">
  						<input type="number" step="any" name="to_be_paid" class="to_be_paid" value="<?php echo !empty($to_be_paid) ? esc_attr($to_be_paid) : '0'; ?>">
  					</th>
  				</tr>

  				<tr class="tfoot active">
  					<th colspan="5" class="text-right">Advance</th>
  					<th colspan="3" class="text-left opos-field total-advance">
  						<input type="number" step="any" name="advance" class="advance" value="<?php echo !empty($advance) ? esc_attr($advance) : '0'; ?>">
  					</th>
  				</tr>

  				<tr class="tfoot active">
  					<th colspan="5" class="text-right">Paid on Delivery</th>
  					<th colspan="3" class="text-left opos-field total-paid">
  						<input type="number" step="any" name="paid" class="paid" value="<?php echo !empty($paid) ? esc_attr($paid) : '0'; ?>">
  					</th>
  				</tr>

          <tr class="tfoot active">
  					<th colspan="5" class="text-right">Pending</th>
  					<th colspan="3" class="text-left opos-field total-pending">
  						<input type="number" step="any" name="pending_amount" class="pending" value="<?php echo !empty($pending) ? esc_attr($pending) : '0'; ?>" readonly>
  					</th>
  				</tr>

  				<tr class="tfoot active">
  					<th colspan="5" class="text-right">Order Status</th>
  					<th colspan="3" class="text-left opos-field order-status">
  						<select name="status">
							  <option value="pending" <?php selected( $status, 'pending' ); ?>>Pending</option>
							  <option value="completed" <?php selected( $status, 'completed' ); ?>>Completed</option>
							</select>
  					</th>
  				</tr>
  			</tfoot>
  		</table>
	  </div>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save(int $post_id) {

  	if ( array_key_exists( 'p_titles', $_POST ) ) {
  		$p_titles = array_map('sanitize_text_field', $_POST['p_titles']);
      update_post_meta( $post_id, '_p_titles', $p_titles);
    } else {
      delete_post_meta($post_id, '_p_titles');
    }
    if ( array_key_exists( 'p_ids', $_POST ) ) {
    	$p_ids = array_map('intval', $_POST['p_ids']);
      update_post_meta($post_id, '_p_ids', $p_ids);
    }
    if ( array_key_exists( 'p_quantities', $_POST ) ) {
    	$p_quantities = array_map('intval', $_POST['p_quantities']);
      update_post_meta($post_id, '_p_quantities', $p_quantities);
    }
    if ( array_key_exists( 'p_prices', $_POST ) ) {
    	$p_prices = array_map('floatval', $_POST['p_prices']);
      update_post_meta($post_id, '_p_prices', $p_prices);
    }
    if ( array_key_exists( 'p_discounts', $_POST ) ) {
    	$p_discounts = array_map('floatval', $_POST['p_discounts']);
      update_post_meta($post_id, '_p_discounts', $p_discounts);
    }

    if ( array_key_exists( 'lens_ids', $_POST ) ) {
    	$lens_ids = array_map('intval', $_POST['lens_ids']);
      update_post_meta($post_id, '_lens_ids', $lens_ids);
    }
    if ( array_key_exists( 'left_lenses', $_POST ) ) {
    	$left_lenses = array_map('sanitize_text_field', $_POST['left_lenses']);
      update_post_meta($post_id, '_left_lenses', $left_lenses);
    }
    if ( array_key_exists( 'right_lenses', $_POST ) ) {
    	$right_lenses = array_map('sanitize_text_field', $_POST['right_lenses']);
      update_post_meta($post_id, '_right_lenses', $right_lenses);
    }
    if ( array_key_exists( 'lens_prices', $_POST ) ) {
    	$lens_prices = array_map('floatval', $_POST['lens_prices']);
      update_post_meta($post_id, '_lens_prices', $lens_prices);
    }

    if ( array_key_exists( 'total_quantity', $_POST ) ) {
    	$total_quantity = intval($_POST['total_quantity']);
      update_post_meta($post_id, '_total_quantity', $total_quantity);
    }
    if ( array_key_exists( 'total_price', $_POST ) ) {
    	$total_price = floatval($_POST['total_price']);
      update_post_meta($post_id, '_total_price', $total_price);
    }
    if ( array_key_exists( 'total_lens', $_POST ) ) {
    	$total_lens = floatval($_POST['total_lens']);
      update_post_meta($post_id, '_total_lens', $total_lens);
    }
    if ( array_key_exists( 'total_discount', $_POST ) ) {
    	$total_discount = intval($_POST['total_discount']);
      update_post_meta($post_id, '_total_discount', $total_discount);
    }

    if ( array_key_exists( 'total', $_POST ) ) {
    	$total = floatval($_POST['total']);
      update_post_meta($post_id, '_total', $total);
    }
    if ( array_key_exists( 'to_be_paid', $_POST ) ) {
    	$to_be_paid = floatval($_POST['to_be_paid']);
      update_post_meta($post_id, '_to_be_paid', $to_be_paid);
    }
    if ( array_key_exists( 'advance', $_POST ) ) {
    	$advance = floatval($_POST['advance']);
      update_post_meta($post_id, '_advance', $advance);
    }
    if ( array_key_exists( 'paid', $_POST ) ) {
    	$paid = floatval($_POST['paid']);
      update_post_meta($post_id, '_paid', $paid);
    }
    if ( array_key_exists( 'pending_amount', $_POST ) ) {
    	$pending_amount = floatval($_POST['pending_amount']);
      update_post_meta($post_id, '_pending_amount', $pending_amount);
    }
    if ( array_key_exists( 'status', $_POST ) ) {
    	$status = sanitize_text_field($_POST['status']);
      update_post_meta($post_id, '_status', $status);
    }

  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_ProductListMetaBox', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_ProductListMetaBox', 'save' ] );
