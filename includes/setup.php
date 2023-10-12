<?php
/**
 * Plugin setup functions and definitions.
 *
 * @package Optics POS
 */

/**
 * Plugin Activation
 */
function optics_pos_plugin_activation() {
}
register_activation_hook(optics_pos_file(), 'optics_pos_plugin_activation');

/**
 * Plugin Deactivation
 */
function optics_pos_plugin_deactivation() {
}
register_deactivation_hook(optics_pos_file(), 'optics_pos_plugin_deactivation');

/**
 * Plugin Uninstall
 */
function optics_pos_uninstall() {

	global $wpdb;

	$wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('opos-sales', 'opos-frames', 'opos-glasses', 'opos-w-customers')");
	$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT id FROM {$wpdb->posts})");

	$wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'opos-glasses-cat'");

  $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key IN ('opos_company', 'opos_contactno', 'opos_address', 'opos_city', 'opos_postalcode')");

  $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name = 'opos_options'");

  remove_role('opos_sales_manager');

}
register_uninstall_hook( optics_pos_file(), 'optics_pos_uninstall' );

/**
 * Translate plugin
 */
function optics_pos_load_textdomain() {

  load_plugin_textdomain( 'optics-pos', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );

}
add_action('plugins_loaded', 'optics_pos_load_textdomain');

/**
 * Add sales manager role
 */
function optics_pos_add_roles() {

  add_role('opos_sales_manager', 'Sales Manager (Optics POS)', get_role('administrator')->capabilities);

}
add_action('init', 'optics_pos_add_roles');

/**
 * Redirect to login page
 */
function optics_pos_redirect_to_login() {

  if (!is_home() && !is_front_page()) return;

  wp_redirect(wp_login_url());
  exit;

}
add_action('template_redirect', 'optics_pos_redirect_to_login');