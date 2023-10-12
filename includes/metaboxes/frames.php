<?php
/**
 * Custom Fields functions for Frames CPT.
 *
 * @package Optics POS
 */

abstract class Optics_POS_FramesMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-frames' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_frames_meta_box', // Unique ID
        'Frame Details', // Box title
        [ self::class, 'html' ], // Content callback, must be of type callable
        $screen // Post type
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html( $post ) {

    $frame_name = get_post_meta( $post->ID, '_frame_name', true );
    $barcode = get_post_meta( $post->ID, '_barcode', true );
    $price = get_post_meta( $post->ID, '_price', true );
    $cost = get_post_meta( $post->ID, '_cost', true );
    $stock = get_post_meta( $post->ID, '_stock', true );
    $stock_date = get_post_meta( $post->ID, '_stock_date', true );

    ?>
      <img class="jsbarcodes"></img>

      <div class="opos-field">
        <div class="opos-label">
          <label for="frame_name">Frame Name:</label>
        </div>
        <div class="opos-input">
          <input type="text" name="frame_name" id="frame_name" value="<?php echo esc_attr($frame_name); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="barcode">Barcode:</label>
        </div>
        <div class="opos-input">
          <input type="text" name="barcode" id="barcode" value="<?php echo esc_attr($barcode); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="price">Price:</label>
        </div>
        <div class="opos-input">
          <input type="number" step="any" name="price" id="price" value="<?php echo esc_attr($price); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="cost">Cost:</label>
        </div>
        <div class="opos-input">
          <input type="number" step="any" name="cost" id="cost" value="<?php echo esc_attr($cost); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="stock">Stock:</label>
        </div>
        <div class="opos-input">
          <input type="number" name="stock" id="stock" value="<?php echo esc_attr($stock); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="stock_date">Stock Date:</label>
        </div>
        <div class="opos-input">
          <input type="date" name="stock_date" id="stock_date" value="<?php echo esc_attr($stock_date); ?>">
        </div>
      </div>

      <?php if (is_multisite()) { ?>
        <div class="opos-field">
          <div class="opos-label">
            <label for="opos-stores">Get Data From:</label>
          </div>
          <div class="opos-input">
            <select name="opos-stores" id="opos-stores">
              <?php
                $subsites = get_sites();

                foreach ($subsites as $subsite) {

                  $subsite_id = get_object_vars($subsite)['blog_id'];
                  $subsite_name = get_blog_details($subsite_id)->blogname;
                  $protocol = is_ssl() ? 'https://' : 'http://';
                  $domain = get_object_vars($subsite)['domain'];
                  $path = get_object_vars($subsite)['path'];

                  echo '<option value="' . esc_url($protocol . $domain . substr($path,0,-1)) . '">' . esc_html($subsite_name) . '</option>';

                }
              ?>
            </select>
            <button class="button button-primary button-medium barcode-get-data">Get Data</button>
          </div>
        </div>
      <?php } ?>

      <!-- Alerts -->
      <div class="opos-alertarea"></div>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save( int $post_id ) {

    if ( array_key_exists('frame_name', $_POST) ) {
    	$frame_name = sanitize_text_field($_POST['frame_name']);
      update_post_meta( $post_id, '_frame_name', $_POST['frame_name'] );
    }

    if ( array_key_exists('barcode', $_POST) ) {
    	$barcode = sanitize_text_field($_POST['barcode']);
      update_post_meta($post_id, '_barcode', $barcode);
    }

    if ( array_key_exists('price', $_POST) ) {
    	$price = floatval($_POST['price']);
      update_post_meta($post_id, '_price', $price);
    }

    if ( array_key_exists( 'cost', $_POST ) ) {
    	$cost = floatval($_POST['cost']);
      update_post_meta($post_id, '_cost', $cost);
    }

		if ( array_key_exists('stock', $_POST) ) {
			$stock = intval($_POST['stock']);
      update_post_meta($post_id, '_stock', $stock);
    }

    if ( array_key_exists('stock_date', $_POST) ) {
    	$stock_date = sanitize_text_field( $_POST['stock_date']);
      update_post_meta($post_id, '_stock_date', $stock_date);
    }

  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_FramesMetaBox', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_FramesMetaBox', 'save' ] );

/**
 * Barcodes container
 */
function optics_pos_generated_barcodes() {

  global $current_screen;

  if (is_admin() && $current_screen->base === 'edit' && $current_screen->post_type === 'opos-frames') {

    ?><div id="opos-barcodes" class="opos-barcodes"></div><?php

  }

}
add_action('admin_notices', 'optics_pos_generated_barcodes');