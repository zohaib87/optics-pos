<?php
/**
 * Class for adding custom post types.
 *
 * @package Optics POS
 */

if (!class_exists('Optics_POS_CustomPostTypes')) :

class Optics_POS_CustomPostTypes {

  function __construct() {

    add_action( 'init', array($this, 'custom_post_types') );
    register_activation_hook( __FILE__, array($this, 'rewrite_flush') );

  }

  protected function sales() {

    $labels = array(
      'name'               => 'Sales',
      'singular_name'      => 'Sale',
      'menu_name'          => 'Sales',
      'name_admin_bar'     => 'Sale',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Sale',
      'new_item'           => 'New Sale',
      'edit_item'          => 'Edit Sale',
      'view_item'          => 'View Sale',
      'all_items'          => 'Sales',
      'search_items'       => 'Search Sales',
      'parent_item_colon'  => 'Parent Sales:',
      'not_found'          => 'No Sales found.',
      'not_found_in_trash' => 'No Sales found in Trash.',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => false,
      'publicly_queryable' => false,
      'show_in_rest'       => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-cart',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'opos-sales' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'revisions' ),
    );
    return register_post_type( 'opos-sales', $args );

  }

  protected function frames() {

    $labels = array(
      'name'               => 'Products',
      'singular_name'      => 'Product',
      'menu_name'          => 'Products',
      'name_admin_bar'     => 'Product',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Product',
      'new_item'           => 'New Product',
      'edit_item'          => 'Edit Product',
      'view_item'          => 'View Product',
      'all_items'          => 'Products',
      'search_items'       => 'Search Products',
      'parent_item_colon'  => 'Parent Products:',
      'not_found'          => 'No Products found.',
      'not_found_in_trash' => 'No Products found in Trash.',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => false,
      'publicly_queryable' => false,
      'show_in_rest'       => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-superhero-alt',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'opos-frames' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'thumbnail', 'revisions' ),
    );
    return register_post_type( 'opos-frames', $args );

  }

  protected function glasses() {

    $labels = array(
      'name'               => 'Glasses',
      'singular_name'      => 'Glass',
      'menu_name'          => 'Glasses',
      'name_admin_bar'     => 'Glass',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Glass',
      'new_item'           => 'New Glass',
      'edit_item'          => 'Edit Glass',
      'view_item'          => 'View Glass',
      'all_items'          => 'Glasses',
      'search_items'       => 'Search Glasses',
      'parent_item_colon'  => 'Parent Glasses:',
      'not_found'          => 'No Glasses found.',
      'not_found_in_trash' => 'No Glasses found in Trash.',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => false,
      'publicly_queryable' => false,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-visibility',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'opos-glasses' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'thumbnail', 'revisions' ),
    );
    return register_post_type( 'opos-glasses', $args );

  }

  protected function walkin_customers() {

    $labels = array(
      'name'               => 'Walk-in Customers',
      'singular_name'      => 'Walk-in Customer',
      'menu_name'          => 'Walk-in Customers',
      'name_admin_bar'     => 'Walk-in Customer',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Walk-in Customer',
      'new_item'           => 'New Walk-in Customer',
      'edit_item'          => 'Edit Walk-in Customer',
      'view_item'          => 'View Walk-in Customer',
      'all_items'          => 'Walk-in Customers',
      'search_items'       => 'Search Walk-in Customers',
      'parent_item_colon'  => 'Parent Walk-in Customers:',
      'not_found'          => 'No Walk-in Customers found.',
      'not_found_in_trash' => 'No Walk-in Customers found in Trash.',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => false,
      'publicly_queryable' => false,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-businessman',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'opos-w-customers' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array('title', 'revisions'),
    );
    return register_post_type( 'opos-w-customers', $args );

  }

  protected function expenses() {

    $labels = array(
      'name'               => 'Expenses',
      'singular_name'      => 'Expense',
      'menu_name'          => 'Expenses',
      'name_admin_bar'     => 'Expense',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Expense',
      'new_item'           => 'New Expense',
      'edit_item'          => 'Edit Expense',
      'view_item'          => 'View Expense',
      'all_items'          => 'Expenses',
      'search_items'       => 'Search Expenses',
      'parent_item_colon'  => 'Parent Expenses:',
      'not_found'          => 'No Expenses found.',
      'not_found_in_trash' => 'No Expenses found in Trash.',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => false,
      'publicly_queryable' => false,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-money-alt',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'opos-expenses' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array('title', 'revisions'),
    );
    return register_post_type( 'opos-expenses', $args );

  }

  public function custom_post_types() {

    $this->sales();
    $this->frames();
    $this->glasses();
    $this->walkin_customers();
    $this->expenses();

  }

  public function rewrite_flush() {

    $this->custom_post_types();
    flush_rewrite_rules();

  }

}
new Optics_POS_CustomPostTypes();

endif;