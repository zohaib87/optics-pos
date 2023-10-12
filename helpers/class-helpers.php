<?php
/**
 * Functions that helps to ease plugin development.
 *
 * @package Optics POS
 */

namespace Helpers;

if (!class_exists('Optics_POS_Helpers')) :

class Optics_POS_Helpers {

  /**
   * Order date query for WP_Query
   */
  public static function order_date_query($from_date, $till_date, $show_by = '_order_date') {

    if (!empty($from_date) && empty($till_date)) {
      $date_query = array(
        'key' => $show_by,
        'value' => $from_date,
        'compare' => '>='
      );
    } elseif (empty($from_date) && !empty($till_date)) {
      $date_query = array(
        'key' => $show_by,
        'value' => $till_date,
        'compare' => '<='
      );
    } elseif (!empty($from_date) && !empty($till_date)) {
      $date_query = array(
        'key' => $show_by,
        'value' => [$from_date, $till_date],
        'compare' => 'BETWEEN'
      );
    } else {
      $date_query = array();
    }

    return $date_query;

  }

	/**
	 * Count orders by user ids
	 */
	public static function user_total_orders($post_type, $user_id, $status = 'all') {

    if ($status === 'all') {
      $status_query = array();
    } else {
      $status_query = array(
        'key' => '_status',
        'value' => $status,
        'compare' => '=='
      );
    }

		$posts = get_posts(array(
      'post_type'	=> $post_type,
			'numberposts'	=> -1,
      'post_status' => 'publish',
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'relation' => 'OR',
          array(
            'key' => '_customer',
            'value' => $user_id,
            'compare' => '=='
          ),
          array(
            'key' => '_wc_contact',
            'value' => $user_id,
            'compare' => '=='
          )
        ),
        $status_query
      )
		));

		return count($posts);

	}

	/**
	 * Sales statistics
	 */
	public static function stats() {

		$data = [];
		$t_complete = 0;
		$t_incomplete = 0;
		$t_advance = 0;
		$t_pending = 0;
		$t_earned = 0;

		$args = array(
      'post_type'	=> 'opos-sales',
      'posts_per_page' => -1
		);
		$query = new \WP_Query($args);

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) :
				$query->the_post();

				$id = get_the_ID();
				$total = get_post_meta( $id, '_total', true );
        $to_be_paid = get_post_meta( $id, '_to_be_paid', true );
		    $advance = get_post_meta( $id, '_advance', true );
		    $pending = get_post_meta( $id, '_pending_amount', true );
		    $status = get_post_meta( $id, '_status', true );

		    if ($status == 'completed') {
          $t_complete++;
		    } else {
          $t_incomplete++;
		    	$t_advance += (float) $advance;
		    	$t_pending += (float) $pending;
        }

        if (empty($to_be_paid)) {
          $t_earned += (float) $total;
        } else {
          $t_earned += (float) $to_be_paid;
        }

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		}

		return $data = [
			'complete' => $t_complete,
			'incomplete' => $t_incomplete,
			'advance' => $t_advance,
			'pending' => $t_pending,
			'earned' => $t_earned
		];

	}

  public static function expense_stats() {

		$data = [];
		$t_daily = 0;
		$t_weekly = 0;
		$t_monthly = 0;
		$t_expense = 0;
    $today_date = date('Y-m-d');
    $last7_date = date('Y-m-d', strtotime('-7 days'));
    $last30_date = date('Y-m-d', strtotime('-30 days'));

		$query = new \WP_Query([
      'post_type'	=> 'opos-expenses',
      'posts_per_page' => -1
    ]);

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) :
				$query->the_post();

				$id = get_the_ID();
        $exp_cost = get_post_meta( $id, '_exp_cost', true );
        $exp_date = get_post_meta( $id, '_exp_date', true );

        if ($exp_date == $today_date) {
          $t_daily += (float) $exp_cost;
        }
        if ($exp_date > $last7_date) {
          $t_weekly += (float) $exp_cost;
        }
        if ($exp_date > $last30_date) {
          $t_monthly += (float) $exp_cost;
        }
        $t_expense += (float) $exp_cost;

			endwhile;

			/* Restore original Post Data */
			wp_reset_postdata();

		}

		return $data = [
			'daily' => $t_daily,
			'weekly' => $t_weekly,
			'monthly' => $t_monthly,
      'total' => $t_expense
		];

	}

	/**
   * Custom Page Navigation
   */
  public static function paging_nav($count_around, $current, $count_pages) {

    $output = '';
    $isGap = false; // A "gap" is the pages to skip
    $count_around; // count_around is the number of pages to show before and after the current
    $current--; // Current page
    $count_pages; // Total number of pages

    for ($i = 0; $i < $count_pages; $i++) { // Run through pages

      $isGap = false;

      // Are we at a gap? If beyond "count_around" and not first or last.
      if ($count_around >= 0 && $i > 0 && $i < $count_pages - 1 && abs($i - $current) > $count_around) {

        $isGap = true;

        // Skip to next linked item (or last if we've already run past the current page)
        $i = ($i < $current ? $current - $count_around : $count_pages - 1) - 1;

      }

      // If gap, write ellipsis, else page number
      if($isGap) :
      	$lnk = '<span class="opos-navgap">...</span>';
      else :
      	$lnk = $i + 1;
      endif;

      // Do not link gaps and current
      if ($i != $current && !$isGap) :
        $lnk = '<a href="#" class="opos-navlink">' . $lnk . '</a>';
      elseif ($i == $current && !$isGap) :
      	$lnk = '<span class="opos-navcurr">' . $lnk . '</span>';
      endif;

      $output .= $lnk;

    }

    return $output;

	}

  /**
   * Insert an attachment from a URL address.
   */
  public static function insert_attachment_from_url($url, $parent_post_id = null) {

    if ( ! class_exists( 'WP_Http' ) ) {
      require_once ABSPATH . WPINC . '/class-http.php';
    }

    $http     = new \WP_Http();
    $response = $http->request($url);
    // if ( 200 !== $response['response']['code'] ) {
    //   return false;
    // }
    if ( is_wp_error($response) ) {
      return false;
    }

    $upload = wp_upload_bits( basename( $url ), null, $response['body'] );
    if ( ! empty( $upload['error'] ) ) {
      return false;
    }

    $file_path        = $upload['file'];
    $file_name        = basename( $file_path );
    $file_type        = wp_check_filetype( $file_name, null );
    $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
    $wp_upload_dir    = wp_upload_dir();

    $post_info = array(
      'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
      'post_mime_type' => $file_type['type'],
      'post_title'     => $attachment_title,
      'post_content'   => '',
      'post_status'    => 'inherit',
    );

    // Create the attachment.
    $attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

    // Include image.php.
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Generate the attachment metadata.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

    // Assign metadata to attachment.
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;

  }

}

endif;
