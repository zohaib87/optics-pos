<?php
/**
 * Custom MetaBox for product search.
 *
 * @package Optics POS
 */

abstract class Optics_POS_ProductSearchMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-sales' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_product_search_meta_box', // Unique ID
        'Product Search', // Box title
        [ self::class, 'html' ], // Content callback, must be of type callable
        $screen // Post type
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html($post) {

    ?>
      <!-- Search and add products -->
      <div class="opos-field opos-add-product">
        <div class="opos-barcode">
          <img src="<?php echo optics_pos_directory_uri() . '/assets/img/barcode.png'; ?>" alt="Barcode">
        </div>
        <input type="text" name="product-search" id="product-search" class="opos-search-product" placeholder="Scan/Search product by name/code" value="">
      </div>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save(int $post_id) {
  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_ProductSearchMetaBox', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_ProductSearchMetaBox', 'save' ] );
