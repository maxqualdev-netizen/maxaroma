
function submitformreturn(indexval)
{
	if($(".cls_"+indexval+":checked").length > 0){
		var err = "0";
		$(".cls_"+indexval).each(function(){
			if($(this).is(":checked")){
				var cur_val = $(this).val();
				
				var customerReturnReason = $("#frmReturn"+indexval+" [name='customerReturnReason"+cur_val+"']").val();
				var oldquantity = $("#frmReturn"+indexval+" [name='oldquantity"+cur_val+"']").val();
				var quantity = $("#frmReturn"+indexval+" [name='quantity"+cur_val+"']").val();
				
			  
			   if(customerReturnReason=='')
			   {
				   alert("Please select reason for return");
				   $("#frmReturn"+indexval+" [name='customerReturnReason"+cur_val+"']").focus();
				   err = "1";
				   return false;
			   }
			   if(customerReturnReason=='Other')
			   {
				   customerOther = $("#frmReturn"+indexval+" [name='otherreason"+cur_val+"']").val();
				   if(customerOther=="")
				   {
					   alert("Please enter other reason");
					   $("#frmReturn"+indexval+" [name='otherreason"+cur_val+"']").focus();
					   err = "1";
					   return false;
				   }
			   }
			   
			     
			   if(quantity=='' || quantity<=0)
			   {
				 alert("Please enter valid quantity.");
				 $("#frmReturn"+indexval+" [name='quantity"+cur_val+"']").focus();
				 err = "1";
				 return false;
			   }
			   
			   if(!checkValidNumber1(quantity))
			   {
				   alert("Please enter only numeric value for quantity.");
				   $("#frmReturn"+indexval+" [name='quantity"+cur_val+"']").focus();
				   err = "1";
				   return false;
			   }
			 
				if(parseInt(quantity) > parseInt(oldquantity))
				{
				   alert("Please enter valid quantity, Return quantity should not be greater then ordered quantity.");
				   $("#frmReturn"+indexval+" [name='quantity"+cur_val+"']").focus();
				   err = "1";
				   return false;
				}

			}
		});
		
		if(err == "1"){
			return false;
		}else{
		   $("#frmReturn"+indexval).submit();
		  //alert("done");
		}
	}else{
		$(".msg11").remove();

		if($("#od_"+indexval).is(":visible") == false){
			//$("#od_"+indexval).show();
			show_hide('pluse',indexval)
		}else{
			//$(".msgchk").prepend("<small class='msg_success msg11'>Please select at least one item to return.</small>");
			alert("Please select at least one item to return.");
		}
		return false;	
	}
	
}

function otherFieldnew(val,indexval)
{
 $("#OtherInput"+indexval).hide();
 if(val == "Other")
 {
	$("#OtherInput"+indexval).show();
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

function check_all(no){
	$(".cls_"+no).prop("checked",$("#chkall_"+no).is(":checked"));
}

function show_hide(type,no){
	$(".oddetails").hide();
	$(".pluse").show();
	$(".minus").hide();
	
	if(type == "pluse"){
		$("#svpluse_"+no).hide();
		$("#svminus_"+no).show();
		$("#od_"+no).show();
	}else{
		$("#svpluse_"+no).show();
		$("#svminus_"+no).hide();
		$("#od_"+no).hide();
	}
}