<?php
/**
 * Register custom menu and sub-menu pages.
 *
 * @package Optics POS
 */

function optics_pos_add_menu_pages() {

  global $menu;

  add_menu_page(
	  esc_html__('Labs', 'optics-pos'),
	  esc_html__('Labs', 'optics-pos'),
	  'manage_options',
	  'opos-labs',
	  'optics_pos_labs',
	  'dashicons-analytics'
	);

  add_menu_page(
		esc_html__('Reports', 'optics-pos'),
	  esc_html__('Reports', 'optics-pos'),
		'manage_options',
		'opos-reports',
		'optics_pos_reports',
	  'dashicons-chart-pie'
	);

  add_menu_page(
		esc_html__('Expense Reports', 'optics-pos'),
	  esc_html__('Expense Reports', 'optics-pos'),
		'manage_options',
		'opos-expense-reports',
		'optics_pos_expense_reports',
	  'dashicons-chart-pie'
	);

  // Get emails added in settings.
  $opt = get_option('opos_options');
  $emails = (isset($opt['emails'])) ? preg_replace('/\s+/', '', $opt['emails']) : '';
  $emails = explode(',', $emails);

  // Get current user email
  $curr_ue = wp_get_current_user()->user_email;

  // if ( in_array($curr_ue, $emails)) {
  // 	remove_menu_page('edit.php');
  // 	remove_menu_page('edit.php?post_type=page');
  // 	remove_menu_page('upload.php');
  // 	remove_menu_page('edit-comments.php');
  // 	remove_menu_page('themes.php');
  // 	remove_menu_page('plugins.php');
  // 	remove_menu_page('users.php');
  // 	remove_menu_page('tools.php');
  // 	remove_menu_page('options-general.php');
  // }

  // Menu pages to leave
  $menu_pages = array(
    'index.php',
    'edit.php?post_type=opos-sales',
    'edit.php?post_type=opos-frames',
    'edit.php?post_type=opos-glasses',
    'edit.php?post_type=opos-w-customers',
    'edit.php?post_type=opos-expenses',
    'opos-labs',
    'opos-reports',
    'opos-expense-reports'
  );

  // Remove unnecessary menu pages for Sales Manager
  foreach ($menu as $item) {
    if ( !in_array($item[2], $menu_pages) && (in_array('opos_sales_manager', wp_get_current_user()->roles) || in_array($curr_ue, $emails)) ) {
      remove_menu_page($item[2]);
    }
  }

}
add_action('admin_menu', 'optics_pos_add_menu_pages', 21);