
$(document).on('click',"#orderReturnclick",function () {
			var err = "0";
	
				var cur_val = $("#order_details_id").val();
			
				var customerReturnReason = $("#customerReturnReason").val();
				var oldquantity = $("#oldquantity").val();
				var quantity = $("#quantity").val();
				
			  
			   if(customerReturnReason=='')
			   {
				   alert("Please select reason for return");
				   $("[name='customerReturnReason']").focus();
				   err = "1";
				   return false;
			   }
			   if(customerReturnReason=='Other')
			   {
				   customerOther = $("[name='otherreason']").val();
				   if(customerOther=="")
				   {
					   alert("Please enter other reason");
					   $("[name='otherreason']").focus();
					   err = "1";
					   return false;
				   }
			   }

			   if(customerReturnReason=='Item Damaged' || customerReturnReason == 'Wrong item received')
			   {
				   damagedImage = $("#damaged_item_image").val();
				   if(damagedImage=="")
				   {
					if(customerReturnReason=='Item Damaged'){
						alert("Please upload image for damaged item");
					} else {
						alert("Please upload image for wrong item received");
					}   
					   $("#damaged_item_image").focus();
					   return false;
				   }
				   if(damagedImage!=""){
					   var ext = $("#damaged_item_image").val().split('.').pop().toLowerCase();
						if ($.inArray(ext, ['jpg','jpeg','pjpeg']) == -1){
						  alert("Only jpg,jpeg,pjpeg types are allowed");
						   $("#damaged_item_image").focus();
						   return false;
						}
				   }
				}
			   
			   if(quantity=='' || quantity<=0)
			   {
				 alert("Please enter valid quantity.");
				 $("[name='quantity']").focus();
				 err = "1";
				 return false;
			   }
			   
			   if(!checkValidNumber1(quantity))
			   {
				   alert("Please enter only numeric value for quantity.");
				   $("[name='quantity']").focus();
				   err = "1";
				   return false;
			   }
			 
				if(parseInt(quantity) > parseInt(oldquantity))
				{
				   alert("Please enter valid quantity, Return quantity should not be greater then ordered quantity.");
				   $("[name='quantity']").focus();
				   err = "1";
				   return false;
				}

		
		if(err == "1"){
			return false;
		}else{
			$("#page-spinner").show();
			$("#frmReturnOrder").submit();
		}	
});

// function otherFieldnew(val)
// {
//  $("#OtherInput").hide();
//  if(val == "Other")
//  {
// 	$("#OtherInput").show();
//  }
// }

function otherFieldnew(val)
{
 $("#OtherInput").hide();
 $("#DamageItemImage").hide();
 
 if(val == "Other")
 {
	$("#OtherInput").show();
 }
 
 if(val == "Item Damaged")
 {
	$("#DamageItemImage").show();
 }
 
}

function checkValidNumber1(val)
{
	var checkOK1 = "0123456789"

	var allValid1 = true;

	var checkStr1 = val;
	
	if(isNaN(checkStr1)){
		return false;
	}

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

$(document).on('click',"#return-select-all",function () {
	if($(this).is(':checked')){
		$(".return-checkbox").not(':disabled').prop( "checked", true );
	}
	else{
		$(".return-checkbox").not(':disabled').prop( "checked", false );
	}
});

$(document).on('click', '#orderItemReturnclick', function() {
	var is_error	= "no";
	var checkbox 	= $('input[name="return_item[]"]:checked');
	var checkboxln 	= $('input[name="return_item[]"]:checked').length;
	
	if(checkboxln > 0){
		checkbox.each(function(){
			var chIndex 	= $(this).attr('data-index');
			var resonVal 	= $("#return-item-"+chIndex).val();
			var quantity 	= $("#return_qty_"+chIndex).val();
			var oldquantity	= $("#old-qty-"+chIndex).val();
			
			if(resonVal == ''){
				is_error	= "yes";
				alert("Please select reason for return");
				$("#return-item-"+chIndex).focus();
				return false;
			}
			
			if(resonVal == 'Other'){
				var customerOther = $("#otherreason-"+chIndex).val();
				if(customerOther==""){
					alert("Please enter other reason");
					$("#otherreason-"+chIndex).focus();
					is_error = "yes";
					return false;
				}
			}
			
			if(resonVal=='Item Damaged' || resonVal == 'Wrong item received'){
				damagedImage = $("#damaged_item_image_"+chIndex).val();
				if(damagedImage==""){
					if(resonVal=='Item Damaged'){
						alert("Please upload image for damaged item");
					} else {
						alert("Please upload image for Wrong item received");
					}
					$("#damaged_item_image_"+chIndex).focus();
					is_error = "yes";
					return false;
				}
				if(damagedImage!=""){
					var ext = $("#damaged_item_image_"+chIndex).val().split('.').pop().toLowerCase();
					if ($.inArray(ext, ['jpg','jpeg','pjpeg']) == -1){
						alert("Only jpg,jpeg,pjpeg types are allowed");
						$("#damaged_item_image_"+chIndex).focus();
						is_error = "yes";
						return false;
					}
				}
			}
			   
			if(quantity=='' || quantity<=0){
				alert("Please enter valid quantity.");
				$("#return_qty_"+chIndex).focus();
				is_error = "yes";
				return false;
			}
			
			if(!checkValidNumber1(quantity)){
				alert("Please enter only numeric value for quantity.");
				$("#return_qty_"+chIndex).focus();
				is_error = "yes";
				return false;
			}
			 
			if(parseInt(quantity) > parseInt(oldquantity)){
				alert("Please enter valid quantity, Return quantity should not be greater then ordered quantity.");
				$("#return_qty_"+chIndex).focus();
				is_error = "yes";
				return false;
			}
		});
	}
	else{
		is_error	= "yes";
		alert('Please select atleast one item to return.');
		return false;
	}
	if(is_error == 'yes'){
		return false;
	}else{
		$("#page-spinner").show();
		$("#frmReturnItemPopup").submit();
	}
});

function popupOtherFieldnew(val, index){
	
	$("#OtherInput-"+index).hide();
	$("#DamageItemImage-"+index).hide();
 
	if(val == "Other"){
		$("#OtherInput-"+index).show();
	}

	if(val == "Item Damaged" || val == "Wrong item received"){
		if(val == "Item Damaged"){
			$("#lblReturnImageUpload").html('Image for damaged item :<span class="errmsg">*</span>');
		} else {
			$("#lblReturnImageUpload").html('Image for wrong item :<span class="errmsg">*</span>');
		}
		$("#DamageItemImage-"+index).show();
	}
}
