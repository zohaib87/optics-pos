<?php
/**
 * Deny access to other than allowed post types for Sales Manager.
 *
 * @package Optics POS
 */

function optics_pos_manage_access(){

  if (wp_doing_ajax()) {
    return;
  }

  if (!is_user_logged_in()) {
    return;
  }

  // Get current post type
  if (isset($_GET['post_type'])) {
    $post_type = $_GET['post_type'];
  } elseif (isset($_GET['page'])) {
    $post_type = $_GET['page'];
  } elseif (isset($_GET['post'])) {
    $post_type = get_post_type($_GET['post']);
  } else {
    $curr_link = $_SERVER['PHP_SELF'];
    $curr_link_array = explode('/',$curr_link);
    $post_type = end($curr_link_array);
  }

  // Get emails added in settings.
  $opt = get_option('opos_options');
  $emails = (isset($opt['emails'])) ? preg_replace('/\s+/', '', $opt['emails']) : '';
  $emails = explode(',', $emails);

  // Get current user email
  $curr_ue = wp_get_current_user()->user_email;

  // Allowed menu pages
  $menu_pages = array(
    'index.php',
    'profile.php',
    'post.php',
    'edit-tags.php',
    'my-sites.php',
    'opos-sales',
    'opos-frames',
    'opos-glasses',
    'opos-w-customers',
    'opos-expenses',
    'opos-labs',
    'opos-reports',
    'opos-expense-reports'
  );

  if ( !in_array($post_type, $menu_pages) && (in_array('opos_sales_manager', wp_get_current_user()->roles) || in_array($curr_ue, $emails)) ) {
    wp_die('Sorry, you are not allowed to access this page.');
  }

}
add_action('admin_init', 'optics_pos_manage_access');