<?php
/**
 * Main page for Labs
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_labs() {

	?>
    <div class="wrap">
      <h1><?php echo esc_html( 'Labs', 'optics-pos' ); ?></h1>

      <table class="opos-labs-table">
        <caption>
          <label>From: <input type="date" name="opos-labs-from" id="opos-labs-from" class="opos-labs-date"></label>
          <label>Till: <input type="date" name="opos-labs-till" id="opos-labs-till" class="opos-labs-date"></label>
          <?php if (is_multisite()) { ?>
            <label>Store: <select name="opos-stores" id="opos-stores">
              <?php
                $subsites = get_sites();

                foreach ($subsites as $subsite) {

                  $subsite_id = get_object_vars($subsite)['blog_id'];
                  $subsite_name = get_blog_details($subsite_id)->blogname;
                  $protocol = is_ssl() ? 'https://' : 'http://';
                  $domain = get_object_vars($subsite)['domain'];
                  $path = get_object_vars($subsite)['path'];

                  echo '<option value="' . esc_url($protocol . $domain . substr($path,0,-1)) . '">' . esc_html($subsite_name) . '</option>';

                }
              ?>
            </select></label>
          <?php } ?>
          <button id="labs-search" class="button button-primary button-medium labs-search">Search</button>
          <button class="button button-primary button-medium opos-labs-reset">Reset</button>
          <button class="button button-primary button-medium print-labs">Print</button>
        </caption>
        <thead>
          <tr>
            <th scope="col">Order No</th>
            <th scope="col">Order Date</th>
            <th scope="col">Lab Data</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $page_count = 1;
            $page_no = 1;
            $table = '';
            $query = new WP_Query([
              'post_type'	=> 'opos-sales',
              'posts_per_page' => '20',
              'paged' => 1,
              'meta_query' => array(
                array(
                  'key' => '_lab',
                  'value' => '',
                  'compare' => '!=',
                )
              )
            ]);

            if ( $query->have_posts() ) {

              $page_count = $query->max_num_pages;

              while ( $query->have_posts() ) :
                $query->the_post();

                $id = get_the_ID();
                $sales_no = get_post_meta($id, '_sales_no', true);
                $order_date = get_post_meta( $id, '_order_date', true );
                $lab = get_post_meta($id, '_lab', true);

                $table .= '<tr>
                  <td data-label="Order No"><a href="' . get_edit_post_link($id) . '">' . esc_html($sales_no) . '</a></td>
                  <td data-label="Order Date">' . esc_html($order_date) . '</td>
                  <td data-label="Lab Data">' . wp_kses_post($lab) . '</td>
                </tr>';

              endwhile;

              /* Restore original Post Data */
              wp_reset_postdata();

            }

            if (!empty($table)) {
              echo wp_kses_post($table);
            } else {
              echo '<tr>
                <td colspan="3">No data to display.</td>
              </tr>';
            }

            ?>
        </tbody>
      </table>
    </div>

    <div class="opos-labs-nav">
      <?php echo ((int) $page_count === 1) ? '' : Helper::paging_nav(1, $page_no, $page_count); ?>
    </div>
  <?php

}