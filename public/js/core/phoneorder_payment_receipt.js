window.onload = function(){
	$("#page-spinner").hide();
}

$(".pay-amazon").on('click',function(){
	$(".amazonpay-button-inner-image").trigger('click');
});
 
$(".btn_linked").on('click',function(){
	var linkto = $(this).data("linkto");
	location.href = linkto;
}); 

$(document).on('click',"#btnAmazonOrder",function(){
	$('#frmamazon').submit();
});

function GetAmazonDetails(AmazonOrderID)
{
	$.ajax({
		type:'POST',
		url:site_url+'amazon/phoneorder-details',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			amazon_order_id: AmazonOrderID
		},
		success:function(data) {
			//success
		}
	});
}
