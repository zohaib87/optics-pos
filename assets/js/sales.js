/*--------------------------------------------------------------
# Main Js Start
--------------------------------------------------------------*/
(function ($, window, document) {

  $(document).ready(function () {

    /*--------------------------------------------------------------
    # Select2
    --------------------------------------------------------------*/
    $('.opos-select2').select2();

    /*--------------------------------------------------------------
    # Current Date
    --------------------------------------------------------------*/
    function optics_pos_curr_date(){

      var d = new Date();

      var month = d.getMonth() + 1;
      var day = d.getDate();

      var output = d.getFullYear() + '-' +
      (month < 10 ? '0' : '') + month + '-' +
      (day < 10 ? '0' : '') + day;

      return output;

    }

    /*--------------------------------------------------------------
    # Add Glasses Back to Stock
    --------------------------------------------------------------*/
    function optics_pos_pre_glass() {

      $('.opos-list-products .lens-id').each(function(){
        var preGlass = $(this).find(":selected").val();
        $(this).parent().find('.lens-id-h').val(preGlass);
      });

    }

    optics_pos_pre_glass();

    $('.opos-list-products').on('change', '.lens-id', function() {

      var preGlassID = $(this).parent().find('.lens-id-h').val();

      if (preGlassID == '') {

        optics_pos_pre_glass();

      } else {

        $.ajax({
          url: ajaxurl,
          data: {
            action: 'optics_pos_add_to_lens_stock',
            id: preGlassID,
          },
          success:function(data) {
            optics_pos_pre_glass();
          },
          error:function(error){
          }
        });

      }

    });

    /*--------------------------------------------------------------
    # Barcode Scanner
    --------------------------------------------------------------*/
    var barcode = "";

    $('#product-search').keydown( function(event) {

      var code = (event.keyCode ? event.keyCode : event.which);

      if (code == 13 || code == 9) { // Enter (13) and Tab (9) key hit

        event.preventDefault();
        barcode = $('#product-search').val();

        if (barcode != '') {

          var srNo = $('.opos-list-products tr:last-child() .sr-no').text();
          srNo++;

          $.ajax({
            url: ajaxurl,
            data: {
              action: 'optics_pos_add_product_by_barcode',
              barcode: barcode,
              srNo: srNo
            },
            success: function(data) {

              data = data.slice(0,-1);
              var backspace = $.Event('keydown', { keyCode: 20 });

              $('.opos-list-products tbody').append(data);
              calculation();
              generate_receipt();
              $('#product-search').val('').trigger(backspace);
              $('.opos-select2').select2();

            },
            error: function(error) {}
          });

        } // End of (barcode != '')

      } else {

        barcode = barcode+String.fromCharCode(code);

      }

    });

    /*--------------------------------------------------------------
    # Customer Data
    --------------------------------------------------------------*/
    function view_customer_data() {

      if ( opos.base === 'edit' ) return false;

      var userID = $('#customer').val();

      if (userID == 'walk-in') {

        $('.opos-field.walk-in').css('display', 'block');
        var name = $('#wc_name').val();
        var id = $('#wc_id').val();
        var contact = $('#wc_contact').val();
        var address = $('#wc_address').val();

        $('.view-user').attr('name', name);
        $('.customer-detail .name, .opos-invoice-mid .name').html(name);
        $('.customer-detail .id, .opos-invoice-mid .id').html(id);
        $('.customer-detail .company').html('N/A');
        $('.customer-detail .contactno, .opos-invoice-mid .contactno').html(contact);
        $('.customer-detail .orders').html('N/A');
        $('.customer-detail .address, .opos-invoice-mid .address').html(address);

      } else {

        $('.opos-field.walk-in').css('display', 'none');
        $('.edit-user').attr('href', '/wp-admin/user-edit.php?user_id='+userID);

        $.ajax({
          url: ajaxurl,
          data: {
            action: 'optics_pos_customer_data',
            user_id: userID
          },
          success:function(data) {

            data = JSON.parse( data.slice(0,-1) );

            $('.view-user').attr('name', data.name);
            $('.customer-detail .name, .opos-invoice-mid .name').html(data.name);
            $('.customer-detail .id, .opos-invoice-mid .id').html(data.id);
            $('.customer-detail .company').html(data.company);
            $('.customer-detail .contactno, .opos-invoice-mid .contactno').html(data.contactno);
            $('.customer-detail .orders').html(data.orders);
            $('.customer-detail .address, .opos-invoice-mid .address').html(data.address);

          },
          error:function(error){
          }
        }); // $.ajax

      }

    }

    view_customer_data();

    // Check if contact no already exists
    $('#wc_contact').on('keyup', function(){

      var num = $(this).val();
      var option = $("#customer option[value='wc-"+num+"']");

      if (option.length > 0 && num != '') {
        $('#customer').val('wc-'+num).change();
      }

    });

    $('#customer, input[id^="wc_"]').on('change keyup', function(){
      view_customer_data();
    });

    $('#lab').on('change keyup', function(){

      var labs = $('#lab').val();

      if (labs != '') {
        $('.opos-invoice-mid .labs').html(labs);
      } else {
        $('.opos-invoice-mid .labs').html('N/A');
      }

    });

    /*--------------------------------------------------------------
    # Refresh Customers
    --------------------------------------------------------------*/
    function refresh_customers() {

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'optics_pos_refresh_customers',
        },
        success: function(data) {

          data = data.slice(0,-1);
          $('#customer').html(data);

        },
        error: function(error){
        }
      });

    }

    /*--------------------------------------------------------------
    # Add New Customer
    --------------------------------------------------------------*/
    $('.opos-hide-pw').on('click', function() {

      $('.opos-hide-pw').toggle();
      var pwAttr = $(this).parent().find('#opos-password');

      if (pwAttr.attr('type') == 'text') {
        pwAttr.attr('type', 'password');
      } else {
        pwAttr.attr('type', 'text');
      }

    });

    $('.add-user').on('click', function(event){

      event.preventDefault();

      var username = ($('#opos-username').val().length >= 5);
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      var emailVal = $('#opos-email').val();
      var email = ($('#opos-email').val().length >= 3);
      var password = ($('#opos-password').val().length >= 8);
      var firstname = ($('#opos-firstname').val().length >= 2);
      var lastname = ($('#opos-lastname').val().length >= 2);

      if (username == false) {

        optics_pos_alert('.opos-useralert', 'Invalid Username. Min 5 characters required.', 'danger');

      } else if (emailReg.test(emailVal) == false || email == false) {

        optics_pos_alert('.opos-useralert', 'Invalid Email.', 'danger');

      } else if (password == false) {

        optics_pos_alert('.opos-useralert', 'Invalid Password. Min 8 characters required.', 'danger');

      } else if (firstname == false) {

        optics_pos_alert('.opos-useralert', 'First Name is Invalid.', 'danger');

      } else if (lastname == false) {

        optics_pos_alert('.opos-useralert', 'Last Name is Invalid.', 'danger');

      } else if (username && emailReg.test(emailVal) && email && password && firstname && lastname) {

        $.ajax({
          url: ajaxurl,
          data: {
            action: 'optics_pos_add_user',
            username: $('#opos-username').val(),
            email: $('#opos-email').val(),
            password: $('#opos-password').val(),
            firstname: $('#opos-firstname').val(),
            lastname: $('#opos-lastname').val(),
            company: $('#opos-company').val(),
            contactno: $('#opos-contactno').val(),
            address: $('#opos-address').val(),
            city: $('#opos-city').val(),
            postalcode: $('#opos-postalcode').val()
          },
          beforeSend: function() {
            $('.add-user').attr('disabled', 'disabled').text('Please wait...');
          },
          success: function(data) {

            data = data.slice(0,-1);

            if (data == 'user-exists') {

              $('#opos-username').val('');
              optics_pos_alert('.opos-useralert', 'Username already exists', 'danger');

            } else if (data == 'email-exists') {

              $('#opos-email').val('');
              optics_pos_alert('.opos-useralert', 'Email already exists', 'danger');

            } else {

              $('#opos-username, #opos-email, #opos-firstname, #opos-lastname, #opos-company, #opos-contactno, #opos-address, #opos-city, #opos-postalcode').val('');
              optics_pos_alert('.opos-useralert', 'Customer added successfully.', 'success');

            }

            $('.add-user').removeAttr('disabled').text('Add Customer');

          },
          complete: function() {
            refresh_customers();
          }
        });

      } else {
        optics_pos_alert('.opos-useralert', 'Please fill all required (*) fields with valid input.', 'danger');
      }

    });

    /*--------------------------------------------------------------
    # Add and List Products
    --------------------------------------------------------------*/
    $("#product-search").autocomplete({

      source: function(request, response){

        $.ajax({
          url: ajaxurl,
          data: {
            action: 'optics_pos_get_products',
            keyword: request.term
          },
          success:function(data) {

            data = JSON.parse( data.slice(0,-1) );
            response(data);

          },
          error:function(error){
          }
        });

      },
      focus: function (event, ui) {

        $('#product-search').val(ui.item.label);

        return false;

      },
      select: function (event, ui) {

        var srNo = $('.opos-list-products tr:last-child() .sr-no').text();
        srNo++;

        $.ajax({
          url: ajaxurl,
          data: {
            action: 'optics_pos_add_product',
            id: ui.item.value,
            srNo: srNo
          },
          success:function(data) {

            data = data.slice(0,-1);
            $('.opos-list-products tbody').append(data);
            calculation();
            generate_receipt();
            $('#product-search').val('');
            $('.opos-select2').select2();
            optics_pos_alert('.opos-alertarea', 'Added Successfully!', 'success');

          },
          error:function(error){
          }
        });

        return false;

      }

    });

    $('.opos-list-products').on('click', '.del', function(){

      var currQuan = Number($(this).closest('tr').find('.quantity').val());
      var id = $(this).parent('tr').find('.id').val();
      $(this).parent('tr').remove();

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'optics_pos_add_to_stock',
          id: id,
          curr_quan: currQuan
        },
        success:function(data) {
        },
        error:function(error){
        }
      });

      calculation();
      generate_receipt();
      optics_pos_alert('.opos-alertarea', 'Removed Successfully!', 'warning');

    });

    /*--------------------------------------------------------------
    # Increase or decrease product stock
    --------------------------------------------------------------*/
    function opticsPosIncDecStock(elem){

      var diff = '';
      var incDec = '';

      var currElem = $(elem);
      var currVal = Number($(elem).val());
      var preVal = Number($(elem).data('quantity'));
      var id = $(elem).closest('tr').find('.id').val();

      if (preVal < currVal) {
        diff = currVal - preVal;
        incDec = 'remove';
      } else if (preVal > currVal) {
        diff = preVal - currVal;
        incDec = 'add';
      } else {
        return false;
      }

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'optics_pos_incdec_stock',
          id: id,
          diff: diff,
          inc_dec: incDec
        },
        success: function(data){
          currElem.data('quantity', currVal);
        },
        error: function(error){}
      });

    }

    var timer = null;

    $('.opos-list-products').on('keyup change', '.quantity', function(){

      if (timer) {
        clearTimeout(timer);
        timer = null;
      }
      timer = setTimeout(opticsPosIncDecStock.bind(null, this), 2000);

    });

    /*--------------------------------------------------------------
    # Get Lens Numbers of Current Glass
    --------------------------------------------------------------*/
    $('.opos-list-products').on('change', '.lens-id', function() {

      var currentElement = $(this);
      var lensID = currentElement.find(":selected").val();

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'optics_pos_get_lens_numbers',
          lens_id: lensID
        },
        beforeSend: function () {
          currentElement.parent().find('.left-lens, .right-lens').attr('disabled', true);
        },
        success: function(data) {

          data = data.slice(0,-1);
          currentElement.parent().find('.left-lens, .right-lens').html(data).attr('disabled', false);

        },
        error: function(error) {}
      });

    });

    /*--------------------------------------------------------------
    # Calculation
    --------------------------------------------------------------*/
    function calculation() {

      var quantity = [];
      var total_quantity = 0;
      var price = [];
      var discount = 0;
      var total = 0;
      var grand_total = 0;
      var toBePaid = $('.to-be-paid input').val();
      var tbpDiscount = 0;
      var lens = 0;
      var advance = $('.total-advance input').val();
      var paid = $('.total-paid input').val();
      var pending = 0;

      $('.quantity').each(function(){
        quantity.push( Number($(this).val()) );
        total_quantity += Number($(this).val());
      });
      $('.price').each(function(){
        price.push( Number($(this).val()) );
      });

      for (var i = 0; i < price.length; i++) {

        var value = quantity[i] * price[i];
        total += value;

      }

      $('.discount').each(function(){
        discount += Number($(this).val());
      });
      $('.lens-price').each(function(){
        lens += Number($(this).val());
      });

      grand_total = total + lens;
      grand_total = grand_total - discount;

      if (Number(toBePaid) == 0) {
        pending = grand_total - advance - paid;
      } else {
        tbpDiscount = grand_total - toBePaid;
        pending = toBePaid - advance - paid;
      }

      discount += tbpDiscount;

      $('.total-quantity span').text(total_quantity);
      $('.total-quantity input').val(total_quantity);

      $('.total-price span').text(total);
      $('.total-price input').val(total);

      $('.total-lens span').text(lens);
      $('.total-lens input').val(lens);

      $('.total-discount span').text(discount);
      $('.total-discount input').val(discount);

      $('.grand-total input').val(grand_total);
      $('.total-pending input').val(pending);

    }

    function generate_receipt() {

      var tableRow = $('.opos-list-products tbody tr');

      if (tableRow.length) {

        tableRow.each(function(){

          var srNo = Number($(this).find('.sr-no').text());
          var prodcutName = $(this).find('.product-name').text();
          var quantity = $(this).find('.quantity').val();
          var price = $(this).find('.price').val();
          var lensName = $(this).find('.lens-id :selected').text();
          var leftNo = $(this).find('.left-lens :selected').text();
          var rightNo = $(this).find('.right-lens :selected').text();
          var lensPrice = $(this).find('.lens-price').val();

          if (lensName == '-- Select Lens --') {
            lensName = '';
          } else {
            lensName = '<b>Lens:</b> ' + lensName + '<br>';
          }

          if (leftNo == '-- Select No --') {
            leftNo = '';
          } else {
            leftNo = '<b>Left No:</b> ' + leftNo + '<br>';
          }

          if (rightNo == '-- Select No --') {
            rightNo = '';
          } else {
            rightNo = '<b>Right No:</b> ' + rightNo + '<br>';
          }

          if (lensPrice == '') {
            lensPriceTitle = '';
            lensPrice = '';
          } else {
            lensPriceTitle = '<b>Lens Price:</b>';
            lensPrice = '<p class="itemtext">'+lensPrice+'</p>';
          }

          var content = '<tr class="service">'+
            '<td class="tableitem">'+
              '<p class="itemtext">'+
                prodcutName+'<br>'+
                lensName+
                leftNo+
                rightNo+
                lensPriceTitle+
              '</p>'+
            '</td>'+
            '<td class="tableitem text-center">'+
              '<p class="itemtext">'+quantity+'</p>'+
            '</td>'+
            '<td class="tableitem text-center">'+
              '<p class="itemtext">'+price+'</p>'+
              lensPrice+
            '</td>'+
          '</tr>';

          if (srNo == 1) {
            $('.opos-invoice-bot tbody').html(content);
          } else {
            $('.opos-invoice-bot tbody').append(content);
          }

        });

      } else {
        $('.opos-invoice-bot tbody').html('');
      }

      var salesNo = $('#sales_no').val();
      var salesman = $('#salesman').val();
      var doctor = $('#doctor').val();
      var orderDate = $('#order_date').val();
      var deliveryDate = $('#delivery_date').val();
      var deliveredDate = $('#delivered_date').val();
      var discount = $('.total-discount input').val();
      var total = $('.grand-total input').val();
      var toBePaid = $('.to-be-paid input').val();
      var advance = $('.total-advance input').val();
      var paidOnDelivery = $('.total-paid input').val();
      var pending = $('.total-pending input').val();
      var currDate = optics_pos_curr_date();

      var stCompleted = $('.opos-list-products .order-status option[value="completed"]');
      var stPending = $('.opos-list-products .order-status option[value="pending"]');

      if (currDate >= deliveredDate && deliveredDate != '') {
        stPending.attr('selected', false);
        stCompleted.attr('selected', true);
      } else {
        stCompleted.attr('selected', false);
        stPending.attr('selected', true);
      }
      var status = $('.order-status').find(":selected").text();

      $('.opos-invoice-mid .order-no').text(salesNo);
      $('.opos-invoice-mid .salesman').text(salesman);
      $('.opos-invoice-mid .doctor').text(doctor);
      $('.opos-invoice-mid .order-date').text(orderDate);
      $('.opos-invoice-mid .delivery-date').text(deliveryDate);
      $('.opos-invoice-mid .delivered-date').text(deliveredDate);
      $('.opos-invoice-bot .discount').text(discount);
      $('.opos-invoice-bot .total').text(total);
      $('.opos-invoice-bot .to-be-paid').text(toBePaid);
      $('.opos-invoice-bot .advance').text(advance);
      $('.opos-invoice-bot .paid-on-delivery').text(paidOnDelivery);
      $('.opos-invoice-bot .pending').text(pending);
      $('.opos-invoice-bot .status').text(status);

    }

    generate_receipt();

    $('.opos-list-products, .opos-field').on('keyup change', function(){
      calculation();
      generate_receipt();
    });

    /*--------------------------------------------------------------
    # Sort Products
    --------------------------------------------------------------*/
    if (opos.post_type == 'opos-sales' && opos.base == 'post') {
      $( ".opos-list-products .sortable" ).sortable({
        handle: ".sr-no"
      });
    }

    /*--------------------------------------------------------------
    # Print Customer Data
    --------------------------------------------------------------*/
    $('.print-user-detail').on('click', function(event){

      event.preventDefault();
      $('.customer-detail').printThis();

    });

    /*--------------------------------------------------------------
    # Print Receipt
    --------------------------------------------------------------*/
    $('.print-invoice').on('click', function(event){

      event.preventDefault();
      $('.opos-invoice').printThis();

    });

    if (opos.post_type == 'opos-sales') {

      $(document).on('keydown', function(event){
        if (event.ctrlKey && event.keyCode == 80) {
          event.preventDefault();
          $('.opos-invoice').printThis();
        }
      });

    }

  });

}(jQuery, window, document));