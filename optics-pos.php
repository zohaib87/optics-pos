<?php
/**
 * Plugin Name: Optics POS
 * Description: Optics Point of Sale system for WordPress.
 * Version:     0.0.1
 * Author:      Muhammad Zohaib
 * Author URI:  https://www.xecreators.pk
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: optics-pos
 */

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

require 'helpers/functions.php';

/**
 * Enqueue scripts and styles for admin and front end.
 */
require optics_pos_directory() . '/includes/setup.php';

/**
 * Class that holds helper methods.
 */
require optics_pos_directory() . '/helpers/class-helpers.php';

/**
 * Enqueue scripts and styles for admin and front end.
 */
require optics_pos_directory() . '/includes/scripts.php';

/**
 * Class for adding custom post types.
 */
require optics_pos_directory() . '/includes/class-custom-post-types.php';

/**
 * Class for adding custom taxonomies.
 */
require optics_pos_directory() . '/includes/class-custom-taxonomies.php';

/**
 * MetaBoxes
 */
require optics_pos_directory() . '/includes/metaboxes/user-profile.php';
require optics_pos_directory() . '/includes/metaboxes/sales.php';
require optics_pos_directory() . '/includes/metaboxes/product-search.php';
require optics_pos_directory() . '/includes/metaboxes/product-list.php';
require optics_pos_directory() . '/includes/metaboxes/frames.php';
require optics_pos_directory() . '/includes/metaboxes/glasses.php';
require optics_pos_directory() . '/includes/metaboxes/receipt.php';
require optics_pos_directory() . '/includes/metaboxes/walkin-customers.php';
require optics_pos_directory() . '/includes/metaboxes/class-expenses.php';

/**
 * AJAX functions
 */
require optics_pos_directory() . '/includes/ajax/customer-data.php';
require optics_pos_directory() . '/includes/ajax/refresh-customers.php';
require optics_pos_directory() . '/includes/ajax/get-products.php';
require optics_pos_directory() . '/includes/ajax/add-product.php';
require optics_pos_directory() . '/includes/ajax/add-product-by-barcode.php';
require optics_pos_directory() . '/includes/ajax/add-to-stock.php';
require optics_pos_directory() . '/includes/ajax/get-labs.php';
require optics_pos_directory() . '/includes/ajax/get-reports.php';
require optics_pos_directory() . '/includes/ajax/get-ereports.php';
require optics_pos_directory() . '/includes/ajax/add-user.php';
require optics_pos_directory() . '/includes/ajax/get-lens-numbers.php';
require optics_pos_directory() . '/includes/ajax/barcode-get-data.php';

/**
 * Admin Columns
 */
require optics_pos_directory() . '/includes/admin-columns/sales.php';
require optics_pos_directory() . '/includes/admin-columns/frames.php';
require optics_pos_directory() . '/includes/admin-columns/glasses.php';
require optics_pos_directory() . '/includes/admin-columns/w-customers.php';
require optics_pos_directory() . '/includes/admin-columns/expenses.php';

/**
 * Menu or sub-menu Pages
 */
require optics_pos_directory() . '/includes/menu-pages.php';
require optics_pos_directory() . '/includes/callbacks/labs.php';
require optics_pos_directory() . '/includes/callbacks/reports.php';
require optics_pos_directory() . '/includes/callbacks/expense-reports.php';

/**
 * Restriction for Sales Manager
 */
require optics_pos_directory() . '/includes/restrictions.php';

/**
 * Settings section and fields.
 */
require optics_pos_directory() . '/includes/settings.php';

/**
 * Extend search context in the admin list post screen
 */
require optics_pos_directory() . '/includes/search.php';

/**
 * Change the buttons text e.g: Publish or Preview
 */
require optics_pos_directory() . '/includes/button-text.php';

/**
 * Add custom post metas to rest api
 */
require optics_pos_directory() . '/includes/rest-api.php';
