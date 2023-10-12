<?php
/**
 * Main page for Expense Reports
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_expense_reports() {

  $daily = Helper::expense_stats()['daily'];
  $weekly = Helper::expense_stats()['weekly'];
  $monthly = Helper::expense_stats()['monthly'];

	?>
	<div class="wrap">
	  <h1><?php echo esc_html( 'Expense Reports', 'optics-pos' ); ?></h1>

	  <table class="opos-stats-table">
      <caption><h1><?php echo esc_html( 'Expeses Report', 'optics-pos' ); ?></h1></caption>
      <thead>
        <tr>
          <th scope="col">Today</th>
          <th scope="col">Last 7 Days</th>
          <th scope="col">Last 30 Days</th>
          <th scope="col">Current Expense</th>
          <th scope="col">Current Cost</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td data-label="Today"><?php echo esc_html($daily); ?></td>
          <td data-label="Last 7 Days"><?php echo esc_html($weekly); ?></td>
          <td data-label="Last 30 Days"><?php echo esc_html($monthly); ?></td>
          <td data-label="Currrent Expense" class="opos-expense">0</td>
          <td data-label="Currrent Cost" class="opos-cost">0</td>
        </tr>
      </tbody>
		</table>

    <br>

	  <table class="opos-reports-table">
		  <caption>
		  	<label>From: <input type="date" name="opos-ereports-from" id="opos-ereports-from" class="opos-ereports-date"></label>
		  	<label>Till: <input type="date" name="opos-ereports-till" id="opos-ereports-till" class="opos-ereports-date"></label>
        <button id="ereports-search" class="button button-primary button-medium ereports-search">Search</button>
        <button class="button button-primary button-medium opos-ereports-reset">Reset</button>
  	    <button class="button button-primary button-medium print-ereports">Print</button>
		  </caption>
		  <thead>
		    <tr>
		      <th scope="col">Expense ID</th>
		      <th scope="col">Description</th>
		      <th scope="col">Expense</th>
		      <th scope="col">Cost</th>
		      <th scope="col">Date</th>
		    </tr>
		  </thead>
		  <tbody>
        <?php
			  	$page_count = 1;
          $page_no = 1;
          $table = '';
					$query = new WP_Query([
            'post_type'	=> 'opos-expenses',
						'posts_per_page' => 20,
						'paged' => 1
          ]);

					if ( $query->have_posts() ) {

            $page_count = $query->max_num_pages;

						while ( $query->have_posts() ) :
							$query->the_post();

							$id = get_the_ID();
              $exp_desc = get_post_meta( $id, '_exp_cf', true );
              $expense = get_post_meta( $id, '_expense', true );
              $exp_cost = get_post_meta( $id, '_exp_cost', true );
              $exp_date = get_post_meta( $id, '_exp_date', true );

              $table .= '<tr>
                <td data-label="Expense ID"><a href="' . get_edit_post_link($id) . '">' . esc_html($id) . '</a></td>
                <td data-label="Description">' . esc_html($exp_desc) .'</td>
                <td data-label="Expense">' . wp_kses_post($expense) .'</td>
                <td data-label="Cost">' . wp_kses_post($exp_cost) .'</td>
                <td data-label="Date">' . wp_kses_post($exp_date) .'</td>
              </tr>';

            endwhile;

            /* Restore original Post Data */
            wp_reset_postdata();

					}

          if (!empty($table)) {
            echo wp_kses_post($table);
          } else {
            echo '<tr>
              <td colspan="5">No data to display.</td>
            </tr>';
          }

        ?>
		  </tbody>
		</table>
	</div>

  <div class="opos-ereports-nav">
    <?php echo ((int) $page_count === 1) ? '' : Helper::paging_nav(1, $page_no, $page_count); ?>
  </div>
	<?php

}