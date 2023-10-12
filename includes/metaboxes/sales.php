<?php
/**
 * Custom Fields functions for Sales CPT.
 *
 * @package Optics POS
 */

abstract class Optics_POS_SalesMetaBox {

  /**
   * Set up and add the meta box.
   */
  public static function add() {

    $screens = ['opos-sales'];

    foreach ($screens as $screen) {
      add_meta_box(
        'opos_sales_meta_box', // Unique ID
        'Sale Detail', // Box title
        [self::class, 'html'], // Content callback, must be of type callable
        $screen, // Post type
        'advanced',
        'high'
      );
    }

  }

  /**
   * Display the meta box HTML to the user.
   */
  public static function html($post) {

    global $wpdb;

    $sales_no = get_post_meta($post->ID, '_sales_no', true);
    $customer = get_post_meta($post->ID, '_customer', true);
    $wc_name = get_post_meta($post->ID, '_wc_name', true);
    $wc_id = get_post_meta($post->ID, '_wc_id', true);
    $wc_contact = get_post_meta($post->ID, '_wc_contact', true);
    $wc_address = get_post_meta($post->ID, '_wc_address', true);
    $salesman = get_post_meta($post->ID, '_salesman', true);
    $doctor = get_post_meta($post->ID, '_doctor', true);
    $order_date = get_post_meta($post->ID, '_order_date', true);
    $delivery_date = get_post_meta($post->ID, '_delivery_date', true);
    $delivered_date = get_post_meta($post->ID, '_delivered_date', true);
    $lab = get_post_meta($post->ID, '_lab', true);
    $note = get_post_meta($post->ID, '_note', true);

    $last_sales = get_posts([
      'post_type' => 'opos-sales',
      'numberposts' => 1
    ]);

    if (!empty($last_sales)) {
      foreach ($last_sales as $last_sale) {

        $order_no = get_post_meta($last_sale->ID, '_sales_no', true);
        $order_no++;

        if (empty($salesman)) {
          $salesman = get_post_meta($last_sale->ID, '_salesman', true);
        }
        if (empty($doctor)) {
          $doctor = get_post_meta($last_sale->ID, '_doctor', true);
        }

      }
    } else {
      $order_no = 1;
    }

    ?>

    <div class="opos-sr">

      <div class="opos-sc">
        <!-- Sale No -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="sales_no">Order No:</label>
          </div>
          <div class="opos-input">
            <input type="number" name="sales_no" id="sales_no" value="<?php echo !empty($sales_no) ? esc_attr($sales_no) : $order_no; ?>">
          </div>
        </div>

        <!-- Customer -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="customer">Customer:</label>
          </div>
          <div class="opos-input">
            <select name="customer" id="customer" class="opos-select2">
              <option value="" disabled>-- Select Customer --</option>
              <option value="walk-in" <?php selected($customer, ''); ?>>Walk-in Customer</option>
              <?php
                $users = get_users();
                $w_custoemrs = get_posts([
                  'post_type' => 'opos-w-customers',
                  'numberposts' => -1
                ]);

                foreach ($w_custoemrs as $w_customer) :
                  $name = get_post_meta($w_customer->ID, '_full_name', true);
                  $contactno = get_post_meta($w_customer->ID, '_contactno', true);

                  echo '<option value="wc-' . esc_attr($contactno) . '" ' . selected($customer, 'wc-' . $contactno, false) . '>' . esc_html($name . ' - ' . $contactno) . '</option>';
                endforeach;

                foreach ($users as $user) :
                  $user_data = get_userdata($user->ID);
                  $full_name = $user_data->first_name . ' ' . $user_data->last_name;
                  $name = ($full_name != ' ') ? $full_name : $user->user_nicename;

                  echo '<option value="' . esc_attr($user->ID) . '" ' . selected($customer, $user->ID, false) . '>' . esc_html($name . ' - ' . $user->ID) . '</option>';
                endforeach;
              ?>
            </select>

            <br>

            <!-- Customer Detail -->
            <?php add_thickbox(); ?>
            <div id="opos-user-detail" style="display:none;">
              <br>
              <div class="customer-detail">
                <div class="opos-field">
                  <div class="opos-label"><b>Name:</b></div>
                  <div class="opos-input name"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>ID:</b></div>
                  <div class="opos-input id"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Company:</b></div>
                  <div class="opos-input company"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Email:</b></div>
                  <div class="opos-input email"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Contact No:</b></div>
                  <div class="opos-input contactno"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Total Orders:</b></div>
                  <div class="opos-input orders"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Address:</b></div>
                  <div class="opos-input address"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>City:</b></div>
                  <div class="opos-input city"></div>
                </div>
                <div class="opos-field">
                  <div class="opos-label"><b>Postal Code:</b></div>
                  <div class="opos-input postalcode"></div>
                </div>
                <br>
                <button class="button button-primary button-large print-user-detail">Print</button>
              </div>
            </div>
            <a href="#TB_inline?&width=600&height=550&inlineId=opos-user-detail" class="button button-primary button-medium thickbox view-user" name="">View</a>

            <!-- Add User -->
            <div id="opos-add-user" style="display:none;">
              <br>
              <div class="add-customer">
                <!-- Username -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-username">Username: *</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-username" id="opos-username" value="">
                  </div>
                </div>

                <!-- Email -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-email">Email: *</label>
                  </div>
                  <div class="opos-input">
                    <input type="email" name="opos-email" id="opos-email" value="">
                  </div>
                </div>

                <!-- Password -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-password">Password: *</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-password" id="opos-password" value="<?php echo wp_generate_password(18, true, false); ?>">
                    <button type="button" class="button opos-hide-pw hide-if-no-js" aria-label="Hide Password">
                      <span class="dashicons dashicons-hidden" aria-hidden="true"></span>
                      <span class="text">Hide</span>
                    </button>
                    <button type="button" class="button opos-hide-pw hide-if-no-js" style="display: none;" aria-label="Show Password">
                      <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                      <span class="text">Show</span>
                    </button>
                  </div>
                </div>

                <!-- First Name -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-firstname">First Name: *</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-firstname" id="opos-firstname" value="">
                  </div>
                </div>

                <!-- Last Name -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-lastname">Last Name: *</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-lastname" id="opos-lastname" value="">
                  </div>
                </div>

                <!-- Company -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-company">Company:</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-company" id="opos-company" value="">
                  </div>
                </div>

                <!-- Contact No -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-contactno">Contact No:</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-contactno" id="opos-contactno" value="">
                  </div>
                </div>

                <!-- Address -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-address">Address:</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-address" id="opos-address" value="">
                  </div>
                </div>

                <!-- City -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-city">City:</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-city" id="opos-city" value="">
                  </div>
                </div>

                <!-- Postal Code -->
                <div class="opos-field">
                  <div class="opos-label">
                    <label for="opos-postalcode">Postal Code:</label>
                  </div>
                  <div class="opos-input">
                    <input type="text" name="opos-postalcode" id="opos-postalcode" value="">
                  </div>
                </div>

                <button class="button button-primary button-medium add-user">Add Customer</button>
              </div>
              <div class="opos-useralert"></div>
            </div><!-- #opos-add-user -->
            <a href="#TB_inline?&width=600&height=550&inlineId=opos-add-user" class="button button-primary button-medium thickbox">Add New</a>

            <a href="" class="button button-primary button-medium edit-user" target="_blank">Edit Customer</a>
          </div>
        </div>

        <!-- Walk-in Customers -->
        <div class="opos-field walk-in" style="display: none;">
          <div class="opos-label">
            <label for="wc_name">Full Name:</label>
          </div>
          <div class="opos-input">
            <input type="text" name="wc_name" id="wc_name" value="<?php echo esc_attr($wc_name); ?>">
          </div>
        </div>

        <div class="opos-field" style="display: none;"> <!-- .walk-in class is removed to make it always hidden -->
          <div class="opos-label">
            <label for="wc_id">Customer ID:</label>
          </div>
          <div class="opos-input">
            <?php
              $random_id = $wpdb->insert_id;
              $random_id += 3;
            ?>
            <input type="number" name="wc_id" id="wc_id" value="<?php echo !empty($wc_id) ? esc_attr($wc_id) : $random_id; ?>">
          </div>
        </div>

        <div class="opos-field walk-in" style="display: none;">
          <div class="opos-label">
            <label for="wc_contact">Contact No:</label>
          </div>
          <div class="opos-input">
            <input type="text" name="wc_contact" id="wc_contact" value="<?php echo esc_attr($wc_contact); ?>">
          </div>
        </div>

        <div class="opos-field walk-in" style="display: none;">
          <div class="opos-label">
            <label for="wc_address">Address:</label>
          </div>
          <div class="opos-input">
            <input type="text" name="wc_address" id="wc_address" value="<?php echo esc_attr($wc_address); ?>">
          </div>
        </div>

        <div class="opos-field">
          <div class="opos-label">
            <label for="salesman">Salesman:</label>
          </div>
          <div class="opos-input">
            <input type="text" name="salesman" id="salesman" value="<?php echo esc_attr($salesman); ?>">
          </div>
        </div>

        <div class="opos-field">
          <div class="opos-label">
            <label for="doctor">Doctor:</label>
          </div>
          <div class="opos-input">
            <input type="text" name="doctor" id="doctor" value="<?php echo esc_attr($doctor); ?>">
          </div>
        </div>
      </div>
      <!-- .opos-sc -->

      <div class="opos-sc">
        <!-- Date of Order -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="order_date">Date of Order:</label>
          </div>
          <div class="opos-input">
            <input type="date" name="order_date" id="order_date" value="<?php echo !empty($order_date) ? esc_attr($order_date) : date('Y-m-d'); ?>">
          </div>
        </div>

        <!-- Date of Delivery -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="delivery_date">Date of Delivery:</label>
          </div>
          <div class="opos-input">
            <input type="date" name="delivery_date" id="delivery_date" class="" value="<?php echo esc_attr($delivery_date); ?>">
          </div>
        </div>

        <!-- Delivered Date -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="delivered_date">Delivered Date:</label>
          </div>
          <div class="opos-input">
            <input type="date" name="delivered_date" id="delivered_date" class="" value="<?php echo esc_attr($delivered_date); ?>">
          </div>
        </div>

        <!-- Lab -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="lab">LAB:</label>
          </div>
          <div class="opos-input">
            <textarea name="lab" id="lab" cols="30" rows="5"><?php echo esc_textarea($lab); ?></textarea>
          </div>
        </div>

        <!-- Note -->
        <div class="opos-field">
          <div class="opos-label">
            <label for="note">Note:</label>
          </div>
          <div class="opos-input">
            <textarea name="note" id="note" cols="30" rows="5"><?php echo esc_textarea($note); ?></textarea>
          </div>
        </div>
      </div><!-- .opos-sc -->

    </div>
    <!-- .opos-sr -->

    <!-- Alerts -->
    <div class="opos-alertarea"></div>

    <?php

  }

  /**
   * Save the meta box selections.
   */
  public static function save(int $post_id) {

    $wc_name = $wc_id = $wc_contact = $wc_address = $customer = '';

    if (array_key_exists('sales_no', $_POST)) {
      $sales_no = sanitize_text_field($_POST['sales_no']);
      update_post_meta($post_id, '_sales_no', $sales_no);
    }

    if (array_key_exists('customer', $_POST)) {
      $customer = sanitize_text_field($_POST['customer']);
      update_post_meta($post_id, '_customer', $customer);
    }

    if (array_key_exists('wc_name', $_POST)) {
      $wc_name = sanitize_text_field($_POST['wc_name']);
      update_post_meta($post_id, '_wc_name', $wc_name);
    }

    if (array_key_exists('wc_id', $_POST)) {
      $wc_id = intval($_POST['wc_id']);
      update_post_meta($post_id, '_wc_id', $wc_id);
    }

    if (array_key_exists('wc_contact', $_POST)) {
      $wc_contact = sanitize_text_field($_POST['wc_contact']);
      update_post_meta($post_id, '_wc_contact', $wc_contact);
    }

    if (array_key_exists('wc_address', $_POST)) {
      $wc_address = sanitize_text_field($_POST['wc_address']);
      update_post_meta($post_id, '_wc_address', $wc_address);
    }

    if (array_key_exists('salesman', $_POST)) {
      $salesman = sanitize_text_field($_POST['salesman']);
      update_post_meta($post_id, '_salesman', $salesman);
    }

    if (array_key_exists('doctor', $_POST)) {
      $doctor = sanitize_text_field($_POST['doctor']);
      update_post_meta($post_id, '_doctor', $doctor);
    }

    if (array_key_exists('order_date', $_POST)) {
      $order_date = sanitize_text_field($_POST['order_date']);
      update_post_meta($post_id, '_order_date', $order_date);
    }

    if (array_key_exists('delivery_date', $_POST)) {
      $delivery_date = sanitize_text_field($_POST['delivery_date']);
      update_post_meta($post_id, '_delivery_date', $delivery_date);
    }

    if (array_key_exists('delivered_date', $_POST)) {
      $delivered_date = sanitize_text_field($_POST['delivered_date']);
      update_post_meta($post_id, '_delivered_date', $delivered_date);
    }

    if (array_key_exists('lab', $_POST)) {
      $lab = wp_filter_post_kses($_POST['lab']);
      update_post_meta($post_id, '_lab', $lab);
    }

    if (array_key_exists('note', $_POST)) {
      $note = wp_filter_post_kses($_POST['note']);
      update_post_meta($post_id, '_note', $note);
    }

    // Add walk-in customer
    if (get_post($wc_id)) {
      $post_exists = true;
    } else {
      $post_exists = false;
    }
    $user_exists = ($post_exists === false);
    $check_pt = (get_post_type($post_id) == 'opos-sales');
    $check_cstmr = ($customer == 'walk-in');

    if ($check_pt && $check_cstmr && !empty($wc_name) && $user_exists) :

      $wCustomer_id = wp_insert_post([
        'post_type' => 'opos-w-customers',
        'import_id' => $wc_id,
        'post_title' => $wc_name . ' - ' . $wc_id,
        'post_status' => 'publish'
      ]);

      update_post_meta($wCustomer_id, '_full_name', $wc_name);
      update_post_meta($wCustomer_id, '_contactno', $wc_contact);
      update_post_meta($wCustomer_id, '_address', $wc_address);

    endif;

  }

}
add_action('add_meta_boxes', ['Optics_POS_SalesMetaBox', 'add']);
add_action('save_post', ['Optics_POS_SalesMetaBox', 'save']);

/**
 * Redirect to new sale after save
 */
function optics_pos_redirect_on_publish($location) {

  if ('opos-sales' == get_post_type()) {

    if (isset($_POST['publish'])) {
      return admin_url('post-new.php?post_type=opos-sales');
    }

  }

  return $location;

}
add_filter('redirect_post_location', 'optics_pos_redirect_on_publish');

/**
 * Add to stock on sale delete
 */
function optics_pos_before_sale_delete($postid) {

  global $post_type;

  if ('opos-sales' !== $post_type) {
    return;
  }

  $ids = get_post_meta($postid, '_p_ids', true);

  if (is_array($ids) || is_object($ids)) {
    foreach ($ids as $id) {

      $status = get_post_status($id);

      if ($status === 'trash') {

        wp_untrash_post($id);
        wp_publish_post($id);
        update_post_meta($id, '_stock', 1);

      } else {

        $stock = get_post_meta($id, '_stock', true);
        $stock++;

        update_post_meta($id, '_stock', $stock);

      }

    }
  }

}
add_action('before_delete_post', 'optics_pos_before_sale_delete');

/**
 * Remove auto empty trash
 */
function optics_pos_remove_schedule_delete() {
  remove_action( 'wp_scheduled_delete', 'wp_scheduled_delete' );
}
add_action( 'init', 'optics_pos_remove_schedule_delete' );