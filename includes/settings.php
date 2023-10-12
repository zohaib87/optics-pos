<?php
/**
 * Register setting fields.
 *
 * @package Optics POS
 */

function optics_pos_setting_fields() {

  register_setting('general', 'opos_options');

  // Callbacks
  function optics_pos_section($arg) {
  }

  function optics_pos_receipt_tn() {

    $opt = get_option('opos_options');
    $thank_note = (isset($opt['tn']) && !empty($opt['tn'])) ? $opt['tn'] : 'Thank you for your business!';

    ?><input type="text" name="opos_options[tn]" id="opos_receipt_tn" value="<?php echo esc_attr($thank_note); ?>" /><?php

  }

  function optics_pos_receipt_sn() {

    $opt = get_option('opos_options');
    $sign_note = (isset($opt['sn']) && !empty($opt['sn'])) ? $opt['sn'] : 'This is computer generated invoice. No signature required.';

    ?><input type="text" name="opos_options[sn]" id="opos_receipt_tn" value="<?php echo esc_attr($sign_note); ?>" /><?php

  }

  function optics_pos_emails() {

    $opt = get_option('opos_options');
    $emails = (isset($opt['emails'])) ? preg_replace('/\s+/', ' ', $opt['emails']) : '';

    ?><textarea type='textarea' name='opos_options[emails]' id='opos_emails' rows='7' cols='50'><?php echo esc_html($emails); ?></textarea>
    <p class="description">Provide comma seperated list.</p><?php

  }

  // Section
  add_settings_section( 'opos_settings', esc_html__('Optics POS', 'optics-pos'), 'optics_pos_section', 'general');

  // Fields
  add_settings_field('opos_receipt_tn', esc_html__('Receipt Thank You Note', 'optics-pos'), 'optics_pos_receipt_tn', 'general', 'opos_settings', ['label_for' => 'opos_receipt_tn']);

  add_settings_field('opos_receipt_sn', esc_html__('Receipt Signature Note', 'optics-pos'), 'optics_pos_receipt_sn', 'general', 'opos_settings', ['label_for' => 'opos_receipt_sn']);

  add_settings_field('opos_emails', esc_html__('Sales Manager Emails', 'optics-pos'), 'optics_pos_emails', 'general', 'opos_settings', ['label_for' => 'opos_emails']);

}
add_action('admin_init', 'optics_pos_setting_fields');
