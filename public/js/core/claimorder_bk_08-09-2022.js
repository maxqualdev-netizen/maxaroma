$(document).on('click',"#orderClaimclick",function () {
			var err = "0";
	
				var cur_val = $("#order_details_id").val();
			
				var ordersid = $("#orders_no").val(); 
				var custname = $("#cust_name").val();
				var customerClaimReason = $("#customerClaimReason").val();
				var customercomment = $("#comment").val();
				
				
							  
			   if(ordersid=='')
			   {
				   alert("Please enter order number");
				   $("[name='orders_no']").focus();
				   err = "1";
				   return false;
			   }
			   if(custname=='')
			   {
				   alert("Please enter customer name");
				   $("[name='cust_name']").focus();
				   err = "1";
				   return false;
			   }
			   if(customerClaimReason=='')
			   {
				   alert("Please select reason for claim");
				   $("[name='customerClaimReason']").focus();
				   err = "1";
				   return false;
			   }
			   
			   if(customercomment=='')
			   {
				   alert("Please enter comment");
				   $("[name='comment']").focus();
				   err = "1";
				   return false;
			   }
			   
			   if(document.getElementById('agreepolicy').checked===true)
				{ }
				else
				{
					alert('Please check the consent checkbox to agree the terms and conditions.');
					err = "1";
				   return false; 
				}
			   
		
		if(err == "1"){
			return false;
		}else{
		   $("#frmClaimOrder").submit();
		}	
});

