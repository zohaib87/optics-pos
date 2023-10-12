/*--------------------------------------------------------------
# Main Js Start
--------------------------------------------------------------*/
(function($) {

/*--------------------------------------------------------------
# Making Title Ready Only
--------------------------------------------------------------*/
$('body[class*="post-type-opos-"] #title, body[class*="post-type-opos-"] .ptitle').attr('readonly', true);

/*--------------------------------------------------------------
# Notification
--------------------------------------------------------------*/
function optics_pos_alert_timeout() {

  setTimeout(function() {
    $('.opos-alert:nth-child(1)').fadeOut('slow', function() {
      $(this).remove();
    });
  }, 3000);

}

window.optics_pos_alert = function (parent, message, alert) {

  var alert_element = '<div class="opos-alert opos-'+alert+'">'+
    '<span class="message">'+message+'</span>'+
    '<span class="dismiss">'+
      '<a title="Close">'+
        '<span class="dashicons dashicons-no-alt"></span>'+
      '</a>'+
    '</span>'+
  '</div>';
  $(parent).html(alert_element).fadeIn('slow');
  optics_pos_alert_timeout();

};

$('.opos-alertarea, .opos-useralert').on('click', '.dismiss', function() {
  $(this).parent().fadeOut('slow');
});

/*--------------------------------------------------------------
# Sales
--------------------------------------------------------------*/
function sales_title() {

	var customer = $('#customer').find(":selected").text();
	var customerID = $('#customer').find(":selected").val();
	var wcName = $('#wc_name').val();
	var wcID = $('#wc_id').val();

	if (customer === '' && customerID != 'walk-in') {

		$('#title-prompt-text').removeClass('screen-reader-text');
		$('#title').val('');

	} else if (customerID == 'walk-in') {

		$('#title-prompt-text').addClass('screen-reader-text');
		$('#title').val(wcName+' - '+wcID);

	} else {

		$('#title-prompt-text').addClass('screen-reader-text');
		$('#title').val(customer);

	}

}

if (opos.post_type == 'opos-sales') {

	$('#sales_no, #customer, #wc_name, #wc_id').on('keyup change', function(){
		sales_title();
	});

}

/*--------------------------------------------------------------
# Frames
--------------------------------------------------------------*/
if (opos.post_type == 'opos-frames') {

  $('#frame_name, #barcode').on('keyup', function(){

    var frameName = $('#frame_name').val();
		var barCode = $('#barcode').val();
		var framesCheck = frameName+barCode;

		if (framesCheck === '') {
      $('#title-prompt-text').removeClass('screen-reader-text');
			$('#title').val('');
		} else {
      $('#title-prompt-text').addClass('screen-reader-text');
			$('#title').val(frameName+' - '+barCode);
		}

	});

  // Get data using rest api
  $('.barcode-get-data').on('click', function(event){

    event.preventDefault();
    var currentElement = $(this);
    var store = $('#opos-stores').find(':selected').val();
    var barcode = $('#barcode').val();

    if (barcode !== '') {

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'optics_pos_barcode_get_data',
          barcode: barcode,
          store: store
        },
        beforeSend: function () {
          currentElement.attr('disabled', true).html('Please wait, loading data...');
        },
        success: function (data) {

          if (data !== '') {

            data = JSON.parse(data);
            $('#frame_name').val(data.name);
            $('#price').val(data.price);
            $('#cost').val(data.cost);
            $('#stock').val(data.stock);
            $('#stock_date').val(data.stock_date);
            $('#title-prompt-text').addClass('screen-reader-text');
            $('#title').val(data.name+' - '+data.barcode);

            if (data.media_url != '') {
              $('#postimagediv .inside').html('<p class="hide-if-no-js">'+
                '<a href="" id="set-post-thumbnail" aria-describedby="set-post-thumbnail-desc" class="thickbox">'+
                  '<img width="266" height="266" src="'+data.attach_300+'" class="attachment-266x266 size-266x266" alt="" loading="lazy" srcset="'+data.attach_300+' 300w, '+data.attach_150+' 150w, '+data.attach_60+' 60w, '+data.attach_100+' 100w" sizes="(max-width: 266px) 100vw, 266px">'+
                '</a></p>'+
              '<p class="hide-if-no-js howto" id="set-post-thumbnail-desc">Click the image to edit or update</p>'+
              '<p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">Remove featured image</a></p>'+
              '<input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="'+data.attach_id+'">');
            }

            optics_pos_alert('.opos-alertarea', 'Data imported scuccefully!', 'success');

          } else {

            optics_pos_alert('.opos-alertarea', 'Error! no match found.', 'danger');

          }

          currentElement.attr('disabled', false).html('Get Data');

        },
        error: function (error) {}
      });

    } else {

      optics_pos_alert('.opos-alertarea', 'Please enter barcode.', 'danger');

    }

  });

}

if (opos.post_type === 'opos-frames' && opos.base === 'edit') {

  // Barcode Genrator
  $('.page-title-action').after('<a href="" class="page-title-action gen-barcode">Generate Barcodes</a>');
  $(".jsbarcodes").JsBarcode("Hi!");

  $('.page-title-action.gen-barcode').on('click', function (event) {

    event.preventDefault();
    $('#opos-barcodes').html('');

    $('#the-list input:checked').each(function() {

      var barcode = $(this).closest('tr').find('.column-barcode').text();
      var image = '<img class="barcode" id="br-'+barcode+'">';
      var barID = '#br-'+barcode;

      $('#opos-barcodes').append(image);
      $(barID).JsBarcode(barcode);

    });

    if ($('#the-list input').is(':checked')) {
      $('#opos-barcodes').printThis();
    } else {
      alert('Please select product first.');
    }

  });

}
/*--------------------------------------------------------------
# Glasses
--------------------------------------------------------------*/
if (opos.post_type == 'opos-glasses') {

	$('#lens_title').on('keyup', function(){

		var lensTitle = $('#lens_title').val();

		if (lensTitle === '') {
			$('#title-prompt-text').removeClass('screen-reader-text');
			$('#title').val('');
		} else {
			$('#title-prompt-text').addClass('screen-reader-text');
			$('#title').val(lensTitle);
		}

	});

}

/*--------------------------------------------------------------
# Walk-in Customers
--------------------------------------------------------------*/
if (opos.post_type == 'opos-w-customers') {

	$('#wc_name').on('keyup', function(){

		var wcName = $('#wc_name').val();

		if (wcName === '') {
			$('#title-prompt-text').removeClass('screen-reader-text');
			$('#title').val('');
		} else {
			$('#title-prompt-text').addClass('screen-reader-text');
			$('#title').val(wcName);
		}

	});

}

/*--------------------------------------------------------------
# Expense
--------------------------------------------------------------*/
if (opos.post_type == 'opos-expenses') {

	$('#person_name').on('keyup', function(){

		var personName = $('#person_name').val();

		if (personName === '') {
			$('#title-prompt-text').removeClass('screen-reader-text');
			$('#title').val('');
		} else {
			$('#title-prompt-text').addClass('screen-reader-text');
			$('#title').val(personName);
		}

	});

}

/*--------------------------------------------------------------
# Labs
--------------------------------------------------------------*/
function opticsPosLabs(curr_element) {

  var pageNo = 1;

  if (typeof curr_element == 'undefined') {
    // do nothing...
  } else {
    pageNo = Number( $(curr_element).text() );
  }

  var fromDate = $('#opos-labs-from').val();
	var tillDate = $('#opos-labs-till').val();
  var store = $('#opos-stores').find(':selected').val();

  $.ajax({
		url: ajaxurl,
		data: {
			action: 'optics_pos_get_labs',
			from_date: fromDate,
			till_date: tillDate,
      store: store,
      page_no: pageNo
		},
		beforeSend: function() {

      var loading = '<tr>'+
	    	'<td colspan="3"><div class="loader"></div> <div class="loading-txt">Please wait, loading data...</div></td>'+
	    '</tr>';

      $('.labs-search').attr('disabled', true);
	    $('.opos-labs-table tbody').html(loading);
      $('.opos-labs-nav').html('');

    },
		success: function(data) {

      data = JSON.parse(data);

      if (data.table !== '') {

        $('.opos-labs-table tbody').html(data.table);
        $('.opos-labs-nav').html(data.paging);

      } else {

        $('.opos-labs-table tbody').html('<tr>'+
          '<td colspan="3">No data to display.</td>'+
        '</tr>');

      }

      $('.labs-search').attr('disabled', false);

		},
		error: function(error){
    }
	});

}

$('#labs-search').on('click', function() {
  opticsPosLabs();
});

$('.opos-labs-nav').on('click', 'a', function(event){

	event.preventDefault();
	opticsPosLabs(this);

});

/*--------------------------------------------------------------
# Print Labs Data
--------------------------------------------------------------*/
$('.print-labs').on('click', function(event){

	event.preventDefault();
	$('.opos-labs-table').printThis();

});

/*--------------------------------------------------------------
# Reports
--------------------------------------------------------------*/
function opticsPosReports(curr_element) {

  var pageNo = 1;

  if (typeof curr_element == 'undefined') {
    // do nothing...
  } else {
    pageNo = Number( $(curr_element).text() );
  }

  var fromDate = $('#opos-reports-from').val();
	var tillDate = $('#opos-reports-till').val();
	var status = $('#opos-reports-status').find(':selected').val();

	$.ajax({
		url: ajaxurl,
		data: {
			action: 'optics_pos_get_reports',
			from_date: fromDate,
			till_date: tillDate,
			status: status,
      page_no: pageNo
		},
		beforeSend: function() {

      var loading = '<tr>'+
	    	'<td colspan="12"><div class="loader"></div> <div class="loading-txt">Please wait, loading data...</div></td>'+
	    '</tr>';

	    $('.reports-search').attr('disabled', true);
	    $('.opos-reports-table tbody').html(loading);
      $('.opos-reports-nav').html('');

    },
		success: function(data){

      data = JSON.parse(data);

			if (data.table !== '') {

				$('.opos-reports-table tbody').html(data.table);
        $('.opos-reports-nav').html(data.paging);

				var total = 0;
        var toBePaid = 0;
				var advance = 0;
        var paid = 0;
				var pending = 0;

				$('.r-total').each(function(){
					total += Number($(this).text());
				});
				$('.r-to-be-paid').each(function(){
					toBePaid += Number($(this).text());
				});
				$('.r-advance').each(function(){
					advance += Number($(this).text());
				});
				$('.r-paid').each(function(){
					paid += Number($(this).text());
				});
				$('.r-pending').each(function(){
					pending += Number($(this).text());
				});

				$('.rf-total').text(total);
				$('.rf-to-be-paid').text(toBePaid);
				$('.rf-advance').text(advance);
				$('.rf-paid').text(paid);
				$('.rf-pending').text(pending);

				$('.cs-pstatus').text(data.pStatus);
				$('.cs-cstatus').text(data.cStatus);
				$('.cs-advance').text(data.advance);
				$('.cs-pending').text(data.pending);
				$('.cs-expense').text(data.expense);
				$('.cs-earned').text(data.earned);
				$('.cs-profit').text(data.profit);

			} else {

		    $('.opos-reports-table tbody').html('<tr>'+
          '<td colspan="12">No data to display.</td>'+
        '</tr>');

			}

      $('.reports-search').attr('disabled', false);

		},
		error: function(error){}
	});

}

$('#reports-search').on('click', function(){
  opticsPosReports();
});

$('.opos-reports-nav').on('click', 'a', function(event){
	event.preventDefault();
	opticsPosReports(this);
});

/*--------------------------------------------------------------
# Print Reports
--------------------------------------------------------------*/
$('.print-reports').on('click', function(event){

	event.preventDefault();
	$('.opos-stats-table, .opos-reports-table').printThis();

});

/*--------------------------------------------------------------
# Expense Reports
--------------------------------------------------------------*/
function opticsPosExpenseReports(currElement) {

  var pageNo = 1;

  if (typeof currElement == 'undefined') {
    // do nothing...
  } else {
    pageNo = Number( $(currElement).text() );
  }

  var fromDate = $('#opos-ereports-from').val();
	var tillDate = $('#opos-ereports-till').val();

	$.ajax({
		url: ajaxurl,
		data: {
			action: 'optics_pos_get_ereports',
			from_date: fromDate,
			till_date: tillDate,
      page_no: pageNo
		},
		beforeSend: function() {

      var loading = '<tr>'+
	    	'<td colspan="5"><div class="loader"></div> <div class="loading-txt">Please wait, loading data...</div></td>'+
	    '</tr>';

      $('.ereports-search').attr('disabled', true);
	    $('.opos-reports-table tbody').html(loading);
      $('.opos-ereports-nav').html('');

    },
		success: function(data){

      data = JSON.parse(data);

			if (data.table !== '') {

				$('.opos-reports-table tbody').html(data.table);
        $('.opos-ereports-nav').html(data.paging);
        $('.opos-expense').html(data.expense);
        $('.opos-cost').html(data.cost);

			} else {

		    $('.opos-reports-table tbody').html('<tr>'+
          '<td colspan="5">No data to display.</td>'+
        '</tr>');

			}

      $('.ereports-search').attr('disabled', false);

		},
		error: function(error){}
	});

}

$('#ereports-search').on('click', function(){
  opticsPosExpenseReports();
});

$('.opos-ereports-nav').on('click', 'a', function(event){
	event.preventDefault();
	opticsPosExpenseReports(this);
});

/*--------------------------------------------------------------
# Print Expense Reports
--------------------------------------------------------------*/
$('.print-ereports').on('click', function(event){

	event.preventDefault();
	$('.opos-stats-table, .opos-reports-table').printThis();

});

/*--------------------------------------------------------------
# Reset Expense Reports
--------------------------------------------------------------*/
$('.opos-ereports-reset').on('click', function(event){

	event.preventDefault();
	$('.opos-ereports-date').val('');
  opticsPosExpenseReports();

});

/*--------------------------------------------------------------
# Reset Labs and Reports Page
--------------------------------------------------------------*/
$('.opos-reports-reset, .opos-labs-reset').on('click', function(event){

	event.preventDefault();
	$('.opos-labs-date').val('');
	$('#opos-stores').val(opos.site_url);
  opticsPosLabs();

});

/*--------------------------------------------------------------
# Sales Search
--------------------------------------------------------------*/
function opticsPosGetUrlVars() {

  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

  for(var i = 0; i < hashes.length; i++) {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;

}

if (opos.post_type === 'opos-sales' && opos.base === 'edit') {

  var oposGet = opticsPosGetUrlVars();
  var all = 'selected="selected"';
  var salesman = '';
  var doctor = '';
  var customer = '';

  if (oposGet.fbp == '_salesman') {
    all = '';
    salesman = 'selected="selected"';
  }
  if (oposGet.fbp == '_doctor') {
    all = '';
    doctor = 'selected="selected"';
  }
  if (oposGet.fbp == '_wc_name') {
    all = '';
    customer = 'selected="selected"';
  }

  $('.search-box').prepend('<select name="fbp" id="filter-by-postmeta" style="float: left; margin-right: 5px;">'+
    '<option '+all+' value="all">All</option>'+
    '<option '+salesman+' value="_salesman">Salesman</option>'+
    '<option '+doctor+' value="_doctor">Doctor</option>'+
    '<option '+customer+' value="_wc_name">Customer</option>'+
  '</select>');

}

})( jQuery );
