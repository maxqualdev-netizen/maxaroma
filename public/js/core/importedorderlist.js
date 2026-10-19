function submitImportOrder(orders_id)
{
    // document.location = site_url+"imported-order-list.html";
    if (typeof orders_id  !== "undefined" && orders_id != '') { 
        if(confirm("Are you sure, You want to Delete?"))
        {   
            $.ajax({
                type: 'DELETE',
                url: site_url+'delete-imported-order-list.html',
                data: { 'orders_id' : orders_id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() 
                {
                },
                success: (function(data) {
                    if(data.status == 200) {
                        $("#delete-success-message").show();
                        $("#tr_"+orders_id).remove();
                    }
               })
            });
        }
    } else {
        alert("Something went wrong");
        return false;
    }
}

$("#btnPaypal").click(function(){
	$("#frmOrderList").attr('action',site_url+'set-dropship-order');
	$("#frmOrderList").submit();
});
$(document).on('click',".chkorder",function(){
	CheckOrders();
});
$(document).on('click','#btnDropshipPayment',function(){
	$("#frmOrderList").attr('action',site_url+'set-dropship-order');
	$('#order_action').val('Fund');
	$("#frmOrderList").submit();
})
$(document).ready(function(){
	DropshipperDetails();
});
$("#checkall").click(function(){
	if($(this).prop('checked'))
	{
		$('.chkorder').each(function(index,ele){
			if(!$(ele).attr('disabled'))
			{
				$(".chkorder").prop('checked',true);
			}
		});
		$('#dataselected').val('Yes');
	} else {
		$(".chkorder").prop('checked',false);
		$('#dataselected').val('No');
	}
	CheckOrders();
})

function CheckOrders()
{
	var OrderTotal = 0;
	var ChkFlag = 0;
	$('.chkorder').each(function(index,ele){
		if($(ele).prop('checked'))
		{
			var ExpChk = $(ele).attr('id').split('_');
			OrderTotal+=parseFloat($("#order_"+ExpChk[1]).val());
			ChkFlag = 1;
		} else {
			$("#checkall").prop('checked',false);
		}
	});
	if(OrderTotal > 0)
	{
		$("#order_amount").val(OrderTotal);
		$("#total").html('$'+OrderTotal);
	} else {
		var Total = parseFloat($("#ori_order_amount").val());
		$("#order_amount").val(Total);
		$("#total").html('$'+Total);
	}
	if(ChkFlag == 1)
	{
		$('#dataselected').val('Yes');
	} else {
		$('#dataselected').val('No');
	}
	DropshipperDetails();
}

function DropshipperDetails()
{
	$.ajax({
		url: site_url+'dropshipper-details',
		data:{'action' : 'dropshipdetails','order_amount' : $("#order_amount").val()},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: (function(data) {
			$("#DropshipperFund").html(data);
		})
	});
}