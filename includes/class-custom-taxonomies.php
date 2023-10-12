<?php 
/**
 * Class for adding custom taxonomies.
 *
 * @package Optics POS
 */

if (!class_exists('Optics_POS_CustomTaxonomies')) :

class Optics_POS_CustomTaxonomies {

  function __construct() {

  	add_action( 'init', array($this, 'register_taxonomies'), 0 );

  }

	protected function glasses_cat() {
	
    $labels = array(
      'name'              => 'Lens Numbers',
      'singular_name'     => 'Lens Number',
      'search_items'      => 'Search Lens Numbers',
      'all_items'         => 'All Lens Numbers',
      'parent_item'       => 'Parent Lens Number',
      'parent_item_colon' => 'Parent Lens Number:',
      'edit_item'         => 'Edit Lens Number',
      'update_item'       => 'Update Lens Number',
      'add_new_item'      => 'Add New Lens Number',
      'new_item_name'     => 'New Lens Number Name',
      'menu_name'         => 'Lens Numbers',
    );

    $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'opos-glasses-cat' ),
    );

    return $args;

	}

	public function register_taxonomies() {
 
		register_taxonomy( 'opos-glasses-cat', array('opos-glasses'), $this->glasses_cat() );

	}

}
new Optics_POS_CustomTaxonomies();

endif;