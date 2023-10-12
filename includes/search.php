<?php
/**
 * Extending the search context in the admin list post screen
 *
 * @link https://wordpress.stackexchange.com/questions/11758/extending-the-search-context-in-the-admin-list-post-screen
 *
 * @package Optics POS
 */

function optics_pos_search_join($join) {

  global $pagenow, $wpdb;

  $curr_post_types = [
    'opos-sales',
    'opos-frames',
    'opos-glasses',
    'opos-expenses',
    'opos-w-customers'
  ];

  if ( is_admin() && 'edit.php' === $pagenow && isset($_GET['post_type']) && in_array($_GET['post_type'], $curr_post_types) && !empty($_GET['s']) ) {
    $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
  }

  return $join;

}

function optics_pos_search_where($where) {

  global $pagenow, $wpdb;

  $curr_post_types = [
    'opos-sales',
    'opos-frames',
    'opos-glasses',
    'opos-expenses',
    'opos-w-customers'
  ];

  if ( is_admin() && 'edit.php' === $pagenow && isset($_GET['post_type']) && in_array($_GET['post_type'], $curr_post_types) && !empty($_GET['s']) ) {
    $where = preg_replace(
      "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where
    );
    $where.= " GROUP BY {$wpdb->posts}.id"; // Solves duplicated results
  }

  return $where;

}

function optics_pos_custom_search_query($query) {

  $searchterm = $query->query_vars['s'];

  // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
  $query->query_vars['s'] = "";

  if ($searchterm != "") {

    $meta_query = array('relation' => 'OR');

    array_push($meta_query, array(
      'key' => $_GET['fbp'],
      'value' => $searchterm,
      'compare' => 'LIKE'
    ));

    $query->set("meta_query", $meta_query);

  }

}

global $pagenow;

if ( is_admin() && 'edit.php' === $pagenow && (isset($_GET['fbp']) && $_GET['fbp'] !== 'all')  ) {
  add_filter('pre_get_posts', 'optics_pos_custom_search_query');
} else {
  add_filter('posts_join', 'optics_pos_search_join');
  add_filter('posts_where', 'optics_pos_search_where');
}