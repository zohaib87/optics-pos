<?php
/**
 * Main page for Reports
 *
 * @package Optics POS
 */

use Helpers\Optics_POS_Helpers as Helper;

function optics_pos_reports() {

	$complete = Helper::stats()['complete'];
	$incomplete = Helper::stats()['incomplete'];
	$advance = Helper::stats()['advance'];
	$pending = Helper::stats()['pending'];
	$earned = Helper::stats()['earned'];
  $expense = Helper::expense_stats()['total'];
	$profit = (float) $earned - (float) $expense;

	?>
    <div class="wrap">
      <h1><?php echo esc_html( 'Reports', 'optics-pos' ); ?></h1>

      <table class="opos-stats-table" style="display: none;">
        <caption><h1>Total Statistics</h1></caption>
        <thead>
          <tr>
            <th scope="col">Total Complete Sales</th>
            <th scope="col">Total Incomplete Sales</th>
            <th scope="col">Total Advanced Received</th>
            <th scope="col">Total Pending Amount</th>
            <th scope="col">Total Expense</th>
            <th scope="col">Total Earned</th>
            <th scope="col">Total Profit</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="Total Complete Sales"><?php echo esc_html($complete); ?></td>
            <td data-label="Total Incomplete Sales"><?php echo esc_html($incomplete); ?></td>
            <td data-label="Total Advance Received"><?php echo esc_html($advance); ?></td>
            <td data-label="Total Pending Amount"><?php echo esc_html($pending); ?></td>
            <td data-label="Total Expense"><?php echo esc_html($expense); ?></td>
            <td data-label="Total Earned"><?php echo esc_html($earned); ?></td>
            <td data-label="Total Profit"><?php echo esc_html($profit); ?></td>
          </tr>
        </tbody>
      </table>

      <table class="opos-stats-table">
        <caption><h1>Current Statistics</h1></caption>
        <thead>
          <tr>
            <th scope="col">Current Complete Sales</th>
            <th scope="col">Current Incomplete Sales</th>
            <th scope="col">Current Advanced Received</th>
            <th scope="col">Current Pending Amount</th>
            <th scope="col">Current Expense</th>
            <th scope="col">Current Earnings</th>
            <th scope="col">Current Cash In Hand</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="Current Complete Sales" class="cs-cstatus">0</td>
            <td data-label="Current Incomplete Sales" class="cs-pstatus">0</td>
            <td data-label="Current Advance Received" class="cs-advance">0</td>
            <td data-label="Current Pending Amount" class="cs-pending">0</td>
            <td data-label="Current Expense" class="cs-expense">0</td>
            <td data-label="Current Earned" class="cs-earned">0</td>
            <td data-label="Current Profit" class="cs-profit">0</td>
          </tr>
        </tbody>
      </table>

      <br>

      <table class="opos-reports-table">
        <caption>
          <label>From: <input type="date" name="opos-reports-from" id="opos-reports-from" class="opos-reports-date"></label>
          <label>Till: <input type="date" name="opos-reports-till" id="opos-reports-till" class="opos-reports-date"></label>
          <label>Status: <select name="opos-reports-status" id="opos-reports-status">
            <option value="all">All</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="to-be-paid">Paid On Delivery</option>
          </select></label>
          <button id="reports-search" class="button button-primary button-medium reports-search">Search</button>
          <button class="button button-primary button-medium print-reports">Print</button>
        </caption>
        <thead>
          <tr>
            <th scope="col">Order No</th>
            <th scope="col">Customer</th>
            <th scope="col">Date of Order</th>
            <th scope="col">Date of Delivery</th>
            <th scope="col">Delivered Date</th>
            <th scope="col">Grand Total</th>
            <th scope="col">To Be Paid</th>
            <th scope="col">Advance</th>
            <th scope="col">Paid On Delivery</th>
            <th scope="col">Pending</th>
            <th scope="col">Status</th>
            <th scope="col">Sold Products</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="12">No data to display.</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">Total</td>
            <td data-label="Grand Total" class="rf-total">0</td>
            <td data-label="Advance" class="rf-to-be-paid">0</td>
            <td data-label="Advance" class="rf-advance">0</td>
            <td data-label="Paid On Delivery" class="rf-paid">0</td>
            <td data-label="Pending" class="rf-pending">0</td>
            <td data-label="Sold Products" class="sold-products"></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="opos-reports-nav"></div>
	<?php

}