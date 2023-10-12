<?php
/**
 * Custom Fields functions for Expenses CPT.
 *
 * @package Optics POS
 */

namespace Optics_POS\Includes\Metaboxes;

use Helpers\Optics_POS_Helpers as Helper;

class Expenses {

  function __construct() {

    add_action( 'add_meta_boxes', [ $this, 'add' ] );
    add_action( 'save_post_'.$this->post_type(), [ $this, 'save'] );

  }

  /**
   * # Define post type for current metabox
   */
  protected function post_type() {

    return 'opos-expenses';

  }

  /**
   * # Set up and add the meta box.
   */
  public function add() {

    add_meta_box( 'opos_expenses_meta_box', esc_html__( 'Expenses Detail', 'optics-pos' ), [ $this, 'html' ], $this->post_type() );

  }

  /**
   * # Display the meta box HTML to the user.
   */
  public function html( $post ) {

    $exp_name = get_post_meta( $post->ID, '_exp_name', true );
    $expense = get_post_meta( $post->ID, '_expense', true );
    $exp_cost = get_post_meta( $post->ID, '_exp_cost', true );
    $exp_date = get_post_meta( $post->ID, '_exp_date', true );
    $exp_cf = get_post_meta( $post->ID, '_exp_cf', true );

    ?>
      <div class="opos-field">
        <div class="opos-label">
          <label for="person_name">Person Name:</label>
        </div>
        <div class="opos-input">
          <input type="text" name="person_name" id="person_name" value="<?php echo esc_attr($exp_name); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="expense">Expense Name:</label>
        </div>
        <div class="opos-input">
          <input type="text" name="expense" id="expense" value="<?php echo esc_attr($expense); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="exp_cost">Cost:</label>
        </div>
        <div class="opos-input">
          <input type="number" step="any" name="exp_cost" id="exp_cost" value="<?php echo esc_attr($exp_cost); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="exp_date">Date:</label>
        </div>
        <div class="opos-input">
          <input type="date" name="exp_date" id="exp_date" value="<?php echo !empty($exp_date) ? esc_attr($exp_date) : date('Y-m-d'); ?>">
        </div>
      </div>

      <div class="opos-field">
        <div class="opos-label">
          <label for="exp_cf">Description:</label>
        </div>
        <div class="opos-input">
          <textarea type="text" name="exp_cf" id="exp_cf" cols="30" rows="5"><?php echo esc_textarea($exp_cf); ?></textarea>
        </div>
      </div>
    <?php

  }

  /**
   * # Save the meta box selections.
   */
  public function save( int $post_id ) {

    if ( array_key_exists('person_name', $_POST) ) {
      $exp_name = sanitize_text_field($_POST['person_name']);
      update_post_meta( $post_id, '_exp_name', $exp_name );
    }

    if ( array_key_exists('expense', $_POST) ) {
      $expense = sanitize_text_field($_POST['expense']);
      update_post_meta($post_id, '_expense', $expense);
    }

    if ( array_key_exists( 'exp_cost', $_POST ) ) {
      $exp_cost = floatval($_POST['exp_cost']);
      update_post_meta($post_id, '_exp_cost', $exp_cost);
    }

    if ( array_key_exists('exp_date', $_POST) ) {
      $exp_date = sanitize_text_field($_POST['exp_date']);
      update_post_meta($post_id, '_exp_date', $exp_date);
    }

    if ( array_key_exists('exp_cf', $_POST) ) {
      $exp_cf = sanitize_text_field($_POST['exp_cf']);
      update_post_meta($post_id, '_exp_cf', $exp_cf);
    }

  }

}
new Expenses();
