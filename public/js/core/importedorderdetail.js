var currency_symbol = '$'; //'<?php config('global.SITE_CURRENCY_SYMBOL') ?>';
function Calculate()
{

	var total_items = $("#total_items").val();

	var i = 0;
	var tot = eval(total_items) + eval(1);
	var TotalQuantity = 0;
	for (i=1; i <= total_items; i++)
	{

	  var quantity = $("#quantity"+i).val();
	  var loopno= $("#loopvalue").val();

	  if(quantity=='')
	  {
		  alert("Please enter only numeric value for quantity.");
		   $("#quantity"+i).focus();
		  return false;
		  break;
	  }

	  if(!checkValidNumber1(quantity))
	  {
		  alert("Please enter only numeric value for quantity.");
		  $("#quantity"+i).focus();
		  return false;
		  break;
	  }
	  if($("#vtmpsku"+i).val()=='')
	  {
		  alert("Please enter sku");
		  $("#vtmpsku"+i).focus();
		  return false;
		  break;
	  }


	    TotalQuantity = parseInt(TotalQuantity) + parseInt(quantity);

	}

	for ( i=tot; i <= loopno; i++ )
	{

	  var quantity = $("#qty"+i).val();

	  if(quantity=='')
	  {
		  alert("Please enter only numeric value for quantity.");
		  $("#qty"+i).focus();
		  return false;
		  break;
	  }


	  if(!checkValidNumber1(quantity))
	  {
		  alert("Please enter only numeric value for quantity.");
		  $("#qty"+i).focus();
		  return false;
		  break;
	  }

	  if($("#form_productid"+i).val()=='')
	  {
		  alert("Please enter sku");
		  $("#form_productid"+i).focus();
		  return false;
		  break;
	  }

	  TotalQuantity = parseInt(TotalQuantity) + parseInt(quantity);

	}

	var sub_total = 0;

	for ( i=1; i <= total_items; i++ )
	{
	    var unit_price     		= $("#unit_price"+i).val();

	    var qty 	  			= $("#quantity"+i).val();

		var total_price   		= (unit_price * qty).toFixed();

		var obj_total_price    	= $("#total_price"+i).val();
		eval("document.frmImportedOrder.total_price"+i);
		$("#total_price"+i).val(total_price)

		sub_total = parseFloat(sub_total) + parseFloat(total_price);

	}

	for ( i=tot; i <= loopno; i++ )
	{
	   unit_price = $("#txtprdtotal"+i).val();
	   sub_total = parseFloat(sub_total) + parseFloat(unit_price);
	}

	sub_total = roundto(sub_total);
	$("#fsub_total").val(roundto(sub_total).toFixed(2));
	$("#sub_total_span").html(currency_symbol+roundto(sub_total).toFixed(2));
	var shipping_amt 		= $("#shipping_amt").val();



    $("#order_total").val((parseFloat(sub_total) + parseFloat(shipping_amt)));
	$("#order_total").val(roundto($("#order_total").val()));
	order_total = new Number($("#order_total").val()).toFixed(2);
	$("#order_total").val(order_total);

	$("#TotalQuantity").val(TotalQuantity);

	Ajax_GetShipMethod();

	return true;
}

function roundto(num)
{
	return Math.round(num * 100)/100;
}

$(document).ready(function()
{
	$("#btnEditShippingAddress").click(function(){
		var validationResult = validateForm();
		if(validationResult == true) {
			submitform('frmImportedOrder');
		}
	});

	$("#frmImportedOrder").submit(function()
	{
		/*$("#frmImportedOrder .frmerror").html('');
		$('#frmImportedOrder').validate({
			rules: 
			{
				ship_first_name: {required: true},
				ship_last_name: {required: true},
				ship_address1: {required: true},
				ship_city: {required: true},
				ship_zip: {required: true},
				otherstate: {required: "div#divotherstate:visible"},
				ship_country: {required: true},
				ship_state: {required: "div#divstate:visible"},
				ship_phone: {required: true}
			},
			messages: 
			{
				ship_first_name: { required: GetMessage('Validate','FirstName') },
				ship_last_name : { required: GetMessage('Validate','LastName') },
				ship_city: { required: GetMessage('Validate','City') },
				ship_address1: { required: GetMessage('Validate','Address') },
				ship_zip:  { required: GetMessage('Validate','ZipCode') },
				otherstate: { required: GetMessage('Validate','OtherState') },
				ship_country: { required: GetMessage('Validate','Country') },
				ship_state: { required: GetMessage('Validate','State') },
				ship_phone: { required: GetMessage('Validate','Phone') },
			},
			onsubmit: false,
			invalidHandler: function(form, validator) 
			{
				var errors = validator.numberOfInvalids();
				if (errors) 
				{
					for(var i=0;i<errors;i++)
					{
						var message = validator.errorList[i].message;
						var id = $(validator.errorList[i].element).attr('name');
						$("#frmImportedOrder #error_"+id).html(message);
						$("#frmImportedOrder #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmImportedOrder .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
				$('#error_captcha_js').hide();
				if (grecaptcha.getResponse()=="") {
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
					return false;
				}	
			}     
		});
		
		if(!$('#frmImportedOrder').valid()) {
			return false;
		} else {
			$("input[name*='pageAction']").val('Update Shipping Address');
		}*/
		
    });
});

function validateForm() {
	$("#frmImportedOrder .frmerror").html('');
	$('#frmImportedOrder').validate({
		rules: 
		{
			ship_first_name: {required: true},
			ship_last_name: {required: true},
			ship_address1: {required: true},
			ship_city: {required: true},
			ship_zip: {required: true},
			other_state: {required: "div#divotherstate:visible"},
			ship_country: {required: true},
			ship_state: {required: "div#divstate:visible"},
			ship_phone: {required: true}
		},
		messages: 
		{
			ship_first_name: { required: GetMessage('Validate','FirstName') },
			ship_last_name : { required: GetMessage('Validate','LastName') },
			ship_city: { required: GetMessage('Validate','City') },
			ship_address1: { required: GetMessage('Validate','Address') },
			ship_zip:  { required: GetMessage('Validate','ZipCode') },
			other_state: { required: GetMessage('Validate','OtherState') },
			ship_country: { required: GetMessage('Validate','Country') },
			ship_state: { required: GetMessage('Validate','State') },
			ship_phone: { required: GetMessage('Validate','Phone') },
		},
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmImportedOrder #error_"+id).html(message);
					$("#frmImportedOrder #error_"+id).show();
				}
			} 
		   else 
		   {
			   $("#frmImportedOrder .frmerror").html('');
		   }
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //	
		}     
	});
	
	if(!$('#frmImportedOrder').valid()) {
		return false;
	} else {
		$("input[name*='pageAction']").val('Update Shipping Address');
		return true;
	}
}

function checkValidNumber1(val)
{
	var checkOK1 = "0123456789"

	var allValid1 = true;

	var checkStr1 = val;

	for (i = 0;  i < checkStr1.length;  i++)
	{
		ch = checkStr1.charAt(i);
		for (j = 0;  j < checkOK1.length;  j++)
			if (ch == checkOK1.charAt(j))
				break;

		if (j == checkOK1.length)
		{
			allValid1 = false;
			break;
		}

	}

	return allValid1;
}

function Ajax_GetShipMethod()
{
	var ship_country 	= $('#ship_country').val();
	var ship_zip 	 	= $('#ship_zip').val();
	var ship_city 	 	= $('#ship_city').val();
	var sub_total		= $('#fsub_total').val();
	var TotalQuantity	= $('#TotalQuantity').val();
	var shippingId		= $('#shippingId').val();
	var shipping_sign   = $('#shipping_sign').val();
	if(ship_country=="US")
		var ship_state = $('#ship_state').val();
	else
		var ship_state = $('#other_state').val();

	var STR_POST_VAR = '1=1';
	var STR_POST_VAR = STR_POST_VAR + '&ship_country='+ship_country;
	var STR_POST_VAR = STR_POST_VAR + '&ship_state='+ship_state;
	var STR_POST_VAR = STR_POST_VAR + '&ship_zip='+ship_zip;
	var STR_POST_VAR = STR_POST_VAR + '&ship_city='+ship_city;
	var STR_POST_VAR = STR_POST_VAR + '&subTotal='+sub_total;
	var STR_POST_VAR = STR_POST_VAR + '&TotalQuantity='+TotalQuantity;
	var STR_POST_VAR = STR_POST_VAR + '&shippingId='+shippingId;
	if(shipping_sign > 0)
	{
		var STR_POST_VAR = STR_POST_VAR + '&shipping_sign='+shipping_sign;
	}
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
	  type: 'POST',
	  url: site_url + 'dropship-shipping-method-ajax',
		headers: {
			'X-CSRF-TOKEN': token
		},
	  dataType: "html",
	  data: STR_POST_VAR,
	  cache: false,
	  beforeSend: function() {
			    var html_loader = '<img src="'+site_url+'images/loading.gif"><span class="frmerror frmerror_shw">Please wait while shipping methods are loading....<br> if it takes more than 10-15 seconds then please refresh the page.</span>';
	 		    $('#show_shipping_method_box').html(html_loader);
	  },
  	  success: (function(data, status) {
  	  			var response = JSON.parse(data);
			    $('#show_shipping_method_box').html(response.html);
			   // $m('#count_shipmethod_temp').val(result_arr[0]);
			    Ajax_GetOrder_Summery();
  	  })
	});
}

Ajax_GetShipMethod();

function Ajax_GetOrder_Summery()
{

	var   shipping_signature = "";
	if($("#shipping_signature").prop("checked")== true)
	{
	var  shipping_signature = $('input[name=shipping_signature]:checked').val();
	}

	var ship_country 	= $('#ship_country').val();
	var ship_zip 	 	= $('#ship_zip').val();
	var ship_city 	 	= $('#ship_city').val();
	var sub_total		= $('#fsub_total').val();
	var TotalQuantity	= $('#TotalQuantity').val();
	var shipping_sign   = $('#shipping_sign').val();
	if(ship_country=="US")
		var ship_state = $('#ship_state').val();
	else
		var ship_state = $('#other_state').val();

	var  shipping_mode_id = $('input[name=shippingModeId]:checked').val();
	$("#shippingId").val(shipping_mode_id);

	var STR_POST_VAR = '1=1';
	var STR_POST_VAR = STR_POST_VAR + '&shipping_mode_id='+shipping_mode_id
	var STR_POST_VAR = STR_POST_VAR + '&ship_country='+ship_country;
	var STR_POST_VAR = STR_POST_VAR + '&ship_state='+ship_state;
	var STR_POST_VAR = STR_POST_VAR + '&ship_city='+ship_city;
	var STR_POST_VAR = STR_POST_VAR + '&ship_zip='+ship_zip;
	var STR_POST_VAR = STR_POST_VAR + '&sub_total='+sub_total;
	var STR_POST_VAR = STR_POST_VAR + '&TotalQuantity='+TotalQuantity;
	if(shipping_signature!="")
	{
		var STR_POST_VAR = STR_POST_VAR + '&shipping_signature='+shipping_signature;
	}
    var token = $('meta[name="csrf-token"]').attr('content');
    $("#page-spinner").show();
	$.ajax({
			  type: 'POST',
			  url: site_url + 'dropship-order-summary-ajax',
				headers: {
					'X-CSRF-TOKEN': token
				},
			  dataType: "html",
			  data: STR_POST_VAR,
			  cache: false,
			  async:false,
			  beforeSend: function()
			  {
			  	 /*var html_loader = '<table width="100%" cellspacing="0" cellpadding="7" border="0" align="center"><tbody><tr><td align="center"><img src="'+site_url+'images/loading.gif"></td></tr><tr><td align="center"><span class="error">Updating Result</span></td></tr><tbody></table>';
				 $('#show_order_summary_data').html(html_loader);*/
				 
			  },
			  success: (function(data, status)
			  {
  	  			var response = JSON.parse(data);
			    $('#show_order_summary_data').html();
			    $('#show_order_summary_data').html(response.html);
			    $("#page-spinner").hide();
			  })

	});

}

function RemoveItem(orders_detail_id, orders_id)
{
	flg = confirm('Are you sure to remove item from order ?');
	if(flg == true) {
    	var token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			  type: 'DELETE',
			  url: site_url + 'remove-dropship-order-item',
				headers: {
					'X-CSRF-TOKEN': token
				},
			  // dataType: "html",
			  data: { 'orders_id' : orders_id, 'orders_detail_id' : orders_detail_id },
			  cache: false,
			  async:false,
			  beforeSend: function()
			  {
			  	 var html_loader = '<table width="100%" cellspacing="0" cellpadding="7" border="0" align="center"><tbody><tr><td align="center"><img src="'+site_url+'images/loading.gif"></td></tr><tr><td align="center"><span class="error">Updating Result</span></td></tr><tbody></table>';
				 $('#Rptable').html(html_loader);
			  },
			  success: (function(data, status)
			  {
				if(data.status == 200) {
					location.reload();
				}
			  })
		});
	}	else {
		return false;
	}
}

$( "#btnAddNewItems" ).on( "click", function() {
	addnewitem();
});

function addnewitem()
{
	var currentloopvalue=$("#loopvalue").val();
	if(currentloopvalue==30)
	{
		alert("Item can not be more then 30");
		return false;
	}
	var nextloopvalue=eval(currentloopvalue)+1;
	$("#carttrid"+nextloopvalue).show();
	$("#loopvalue").val(nextloopvalue);
}

function RemoveLineItem(no)
{
	$("#carttrid"+no).css("display","none");
	document.getElementById('loopvalue').value= document.getElementById('loopvalue').value-1
}

function get_Sku_List(skuandloopvalue)
{

	$("#sendphoneorderid").show();
	var skuandloop =  skuandloopvalue.split("*^");
	if(skuandloop[0]!="")
	{
		var sku = skuandloop[0];
		var no = skuandloop[1];
		var quantity = $('#qty'+no).val();
		var data = 'skukwd='+sku+'&no='+no+'&quantity='+quantity;
    	var token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
					type: "POST",
					url: site_url + 'ajax-list-skus',
					headers: {
						'X-CSRF-TOKEN': token
					},
					data:data,
					dataType: "html",
					success: function(response){
					var result = JSON.parse(response); 
					var product_price = "";
					var no = "";
					var price = "";
					var data = result.html.split("###");
					if(result.html!="No")
					{
						no = data[2];
						var quantity = $('#qty'+no).val();
						$('#vprod_image'+no).html('<img src="'+data[3]+'" border="0" width="150">');
						$('#price'+no).val((data[1]*quantity).toFixed(2));
						var prodprice =$('#price'+no).val();
						$("#txtprdtotal"+no).val((prodprice * quantity).toFixed(2));
						Calculate();
					}
					else
					{
						var no = $("#loopvalue").val();
						alert("The product is not exist or the product is out of stock or inactive");
						$("#carttrid"+no).css("display","none");
						$("#price"+no).val("");
						$("#txtprdtotal"+no).val("");
						$("#form_productid"+no).val("");
						document.getElementById('loopvalue').value= document.getElementById('loopvalue').value-1
						Calculate();
					}

				}
		});
	}
}

$( "#btnUpdateSku" ).on( "click", function() {
	UpdateSKUItems();
});

function UpdateSKUItems()
{
	if(!Calculate()) {
		return false;
	}
	$("input[name*='pageAction']").val('UpdateSKUOrder');
	submitform('frmImportedOrder');

	/*var formData = $('#frmImportedOrder').serialize();
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
	  type: 'POST',
	  url: site_url + 'update-sku-order',
		headers: {
			'X-CSRF-TOKEN': token
		},
	  dataType: "json",
	  data: formData,
	  cache: false,
	  // beforeSend: function() {
			//     var html_loader = '<img src="'+site_url+'images/loading.gif"><span class="frmerror frmerror_shw">Please wait....<br> if it takes more than 10-15 seconds then please refresh the page.</span>';
	 	// 	    $('#Rptable').html(html_loader);
	  // },
  	  success: (function(data, status) {
		if(data.status == 200) {
			location.reload();
		}
  	  })
	});*/
	return true;
}

function UpdateSKU()
{
	if($('#frmImportedOrder').valid())
	{
		if(!Calculate()) {
			return false;
		}
		$("input[name*='pageAction']").val('UpdateOrder');
		submitform('frmImportedOrder');

		/*var formData = $('#frmImportedOrder').serialize();
		var token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
		  type: 'POST',
		  url: site_url + 'update-order',
			headers: {
				'X-CSRF-TOKEN': token
			},
		  dataType: "json",
		  data: formData,
		  cache: false,
		  beforeSend: function() {
				    var html_loader = '<img src="'+site_url+'images/loading.gif"><span class="frmerror frmerror_shw">Please wait....<br> if it takes more than 10-15 seconds then please refresh the page.</span>';
		 		    $('#Rptable').html(html_loader);
		  },
	  	  success: (function(data, status) {
			if(data.status == 200) {
				location.reload();
			}
	  	  })
		});*/
		return true;
	}
}

function prdtotal(no)
{
	var skuno ='form_productid'+no;

	if($("#form_productid"+no).val()=="")
	{
		alert("Select the Sku");
		$("#form_productid"+no).focus();
		return false;
	}
	else
	{
		var txtprdtotalno ='txtprdtotal'+no;
		var qtyno ='qty'+no;
		var priceno ='price'+no;
		$("#txtprdtotal"+no).val(($("#qty"+no).val() * $("#price"+no).val()).toFixed(2) )
		Calculate();
	}
}

Number.prototype.toFixed = function(decimals)
{
  var decimals = decimals || 2 // defaults to two
  var catalyst = Math.pow(10, decimals);

  var fixedNum = Math.round(parseFloat(this) * catalyst) / catalyst;

  if (fixedNum % 1)
  {
    return fixedNum
  }
  else
  {
    var str = "."

    while(decimals--)
    {
      str += "0"
    }

    return fixedNum + str
  }
}

function statecheck() {
	var frmOBJ = document.frmImportedOrder;
	if(frmOBJ.ship_country.options[frmOBJ.ship_country.selectedIndex].value=="US") {
		document.getElementById("divstate").style.display="";
		document.getElementById("divotherstate").style.display="none";
	}
	else {
		document.getElementById("divstate").style.display="none";
		document.getElementById("divotherstate").style.display="";
		// frmOBJ.other_state.focus();
	}
}
statecheck();

$(document).ready(function(){
	$("#ship_country").change(function(){
		if($(this).val() != 'US') {
			$("#divstate").hide();
			$("#divotherstate").show();
		} else {
			$("#divotherstate").hide();
			$("#divstate").show();
		}
	});
});


$( "#ship_country" ).on( "click", function() {
	statecheck();
	Ajax_GetShipMethod();
});

$( "#ship_state" ).on( "click", function() {
	Ajax_GetShipMethod();
});
