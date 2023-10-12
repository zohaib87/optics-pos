<?php
/**
 * Custom MetaBox for sales receipt.
 *
 * @package Optics POS
 */

abstract class Optics_POS_SalesReceiptMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = [ 'opos-sales' ];

    foreach ( $screens as $screen ) {
      add_meta_box(
        'opos_sales_receipt_meta_box', // Unique ID
        'Receipt', // Box title
        [ self::class, 'html' ], // Content callback, must be of type callable
        $screen, // Post type
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html( $post ) {

  	$title = get_bloginfo('name');

    ?>
	  <div id="opos-invoice-da" class="opos-invoice" style="display: none;">

	    <div class="opos-invoice-top">
        <h2><?php echo esc_html($title); ?></h2>
	    </div><!-- End InvoiceTop -->

	    <div class="opos-invoice-mid">
	    	<table>
	    		<tr>
	    			<td class="title">Order No:</td>
	    			<td class="order-no"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Order Date:</td>
	    			<td class="order-date"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Date of Delivery:</td>
	    			<td class="delivery-date"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Delivered Date:</td>
	    			<td class="delivered-date"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Customer Name:</td>
	    			<td class="name"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Customer ID:</td>
	    			<td class="id"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Contact No:</td>
	    			<td class="contactno"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Address:</td>
	    			<td class="address"></td>
	    		</tr>
	    		<tr>
	    			<td class="title">Doctor:</td>
	    			<td class="doctor"></td>
	    		</tr>
          <tr>
            <td class="title">Labs:</td>
            <td class="labs">N/A</td>
          </tr>
          <tr>
            <td class="title">Salesman:</td>
            <td class="salesman"></td>
          </tr>
	    	</table>
	    </div><!-- End Invoice Mid -->

	    <div class="opos-invoice-bot">
				<div id="table">
					<table>
						<thead>
							<tr class="tabletitle">
								<td class="item text-center">Item</td>
								<td class="qty text-center">Qty</td>
								<td class="totl text-center">Total</td>
							</tr>
						</thead>

						<tbody>
						</tbody>

						<tfoot>
							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Discount:</td>
								<td class="discount text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Grand Total:</td>
								<td class="total text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Advance Received:</td>
								<td class="advance text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Pending Amount:</td>
								<td class="pending text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Order Status:</td>
								<td class="status text-center">Pending</td>
							</tr>
						</tfoot>
					</table>
				</div><!-- End Table -->

        <?php
          $opt = get_option('opos_options');
          $thank_note = (isset($opt['tn']) && !empty($opt['tn'])) ? $opt['tn'] : 'Thank you for your business!';
          $sign_note = (isset($opt['sn']) && !empty($opt['sn'])) ? $opt['sn'] : 'This is computer generated invoice. No signature required.';
        ?>

				<div id="legalcopy">
					<p class="legal text-center">
						<strong><?php echo esc_html($thank_note); ?></strong><br>
						<?php echo esc_html($sign_note); ?>
					</p>
				</div>
			</div><!--End InvoiceBot-->
      <br>
	  </div><!--End Invoice-->

    <div id="opos-invoice" class="opos-invoice style-2">

	    <div class="opos-invoice-top opos-print-only">
	    </div><!-- End InvoiceTop -->

      <div class="opos-invoice-mid fable">
        <div class="fol">
          <span class="title">Order No:</span>
          <span class="value order-no"></span>
        </div>

        <div class="fol">
          <span class="title">Booking Date:</span>
          <span class="value order-date"></span>
        </div>

        <div class="fol">
          <span class="title">Delivery Date:</span>
          <span class="value delivery-date"></span>
        </div>

        <div class="fol">
          <span class="title">Delivered Date:</span>
          <span class="value delivered-date"></span>
        </div>

        <div class="fol">
          <span class="title">Name:</span>
          <span class="value name"></span>
        </div>

        <div class="fol">
          <span class="title">Phone:</span>
          <span class="value contactno"></span>
        </div>
	    </div><!-- End Invoice Mid -->

      <div class="opos-invoice-mid fresc">
        <div class="title">Glass Info:</div>
        <div class="labs">N/A</div>
      </div><!-- End Invoice Mid -->

      <div class="opos-invoice-mid fresc">
        <div class="title">Prescription:</div>
        <div class="presc"></div>
      </div><!-- End Invoice Mid -->

      <br>

      <div class="opos-invoice-bot">
				<div id="table">
					<table>
						<thead>
							<tr class="tabletitle">
								<td class="item text-center">Item</td>
								<td class="qty text-center">Qty</td>
								<td class="totl text-center">Total</td>
							</tr>
						</thead>

						<tbody>
						</tbody>

						<tfoot>
							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Discount:</td>
								<td class="discount text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Grand Total:</td>
								<td class="total text-center">0</td>
							</tr>

              <tr class="tabletitle">
								<td colspan="2" class="Rate text-right">To Be Paid:</td>
								<td class="to-be-paid text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Advance Received:</td>
								<td class="advance text-center">0</td>
							</tr>

              <tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Paid on Delivery:</td>
								<td class="paid-on-delivery text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Pending Amount:</td>
								<td class="pending text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Order Status:</td>
								<td class="status text-center">Pending</td>
							</tr>
						</tfoot>
					</table>
				</div><!-- End Table -->
			</div><!--End InvoiceBot-->

      <hr class="cut">

      <div class="opos-invoice-title">
        <h1><?php echo esc_html($title); ?></h1>
      </div>

      <!-- Second Part -->
      <div class="opos-invoice-mid fable">
        <div class="fol">
          <span class="title">Order No:</span>
          <span class="order-no"></span>
        </div>

        <div class="fol">
          <span class="title">Booking Date:</span>
          <span class="order-date"></span>
        </div>

        <div class="fol">
          <span class="title">Delivery Date:</span>
          <span class="delivery-date"></span>
        </div>

        <div class="fol">
          <span class="title">Name:</span>
          <span class="name"></span>
        </div>
	    </div><!-- End Invoice Mid -->

      <br>

	    <div class="opos-invoice-bot">
				<div id="table">
					<table>
						<thead>
							<tr class="tabletitle">
								<td class="item text-center">Item</td>
								<td class="qty text-center">Qty</td>
								<td class="totl text-center">Total</td>
							</tr>
						</thead>

						<tbody>
						</tbody>

						<tfoot>
							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Discount:</td>
								<td class="discount text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Grand Total:</td>
								<td class="total text-center">0</td>
							</tr>

              <tr class="tabletitle">
								<td colspan="2" class="Rate text-right">To Be Paid:</td>
								<td class="to-be-paid text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Advance Received:</td>
								<td class="advance text-center">0</td>
							</tr>

              <tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Paid on Delivery:</td>
								<td class="paid-on-delivery text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Pending Amount:</td>
								<td class="pending text-center">0</td>
							</tr>

							<tr class="tabletitle">
								<td colspan="2" class="Rate text-right">Order Status:</td>
								<td class="status text-center">Pending</td>
							</tr>
						</tfoot>
					</table>
				</div><!-- End Table -->
			</div><!--End InvoiceBot-->
	  </div><!--End Invoice-->
	  <br>
	  <button class="button button-primary button-large print-invoice">Print</button>
    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save(int $post_id) {
  }

}
add_action( 'add_meta_boxes', [ 'Optics_POS_SalesReceiptMetaBox', 'add' ] );
add_action( 'save_post', [ 'Optics_POS_SalesReceiptMetaBox', 'save' ] );
