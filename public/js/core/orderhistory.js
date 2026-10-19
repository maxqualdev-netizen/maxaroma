/*$(document).ready(function() {
	$('#search_order').on('click', function (e) {
	    var orders_no = $('#orders_no').val();
		if(orders_no !== '') {
			$.ajax({
				type: 'POST',
				url: '<?php echo config("global.SITE_URL") ?>order-history.html',
				data: { 'orders_no' : orders_no },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() 
				{
				},
				success: (function(data) {
					console.log(data.status);
					if(data.status == 200) {
						$("#order-history-grid").html('');
						$("#order-history-grid").html(data.html);
					}
		       })
			});
		} else {
			alert('Please enter order no.');
		}
	});

});*/

$(document).ready(function() {
	$('#search_order').on('click', function (e) {
		$("#frmsearchorder").submit();
	});

});