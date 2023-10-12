<?php
/**
 * Custom Fields functions for Right and Left Lens CPT.
 *
 * @package Optics POS
 */

abstract class Optics_POS_LensMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-glasses' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_glasses_meta_box', // Unique ID
        'Glass Details', // Box title
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

    $lens_title = get_post_meta( $post->ID, '_lens_title', true );
    $lens_stock = get_post_meta( $post->ID, '_lens_stock', true );

    ?>
      <div class="opos-field">
        <div class="opos-label">
          <label for="lens_title">Title:</label>
        </div>
        <div class="opos-input">
          <input type="text" name="lens_title" id="lens_title" value="<?php echo esc_attr($lens_title); ?>">
        </div>
      </div>
      <div class="opos-field">
        <div class="opos-label">
          <label for="lens_stock">Stock:</label>
        </div>
        <div class="opos-input">
          <input type="number" name="lens_stock" id="lens_stock" value="<?php echo esc_attr($lens_stock); ?>">
        </div>
      </div>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save( int $post_id ) {

    if ( array_key_exists( 'lens_title', $_POST ) ) {
    	$lens_title = sanitize_text_field($_POST['lens_title']);
      update_post_meta($post_id, '_lens_title', $lens_title);
    }

    if ( array_key_exists( 'lens_stock', $_POST ) ) {
    	$lens_stock = intval($_POST['lens_stock']);
      update_post_meta($post_id, '_lens_stock', $lens_stock);
    }

  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_LensMetaBox', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_LensMetaBox', 'save' ] );
