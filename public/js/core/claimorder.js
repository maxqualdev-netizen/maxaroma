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

$(document).on('click',"#claimReportclick",function () {
	var err = "0";
	
	var fileinput = $("#attach_item_file").val();
	
	if(fileinput=='')
	{
		alert("Please upload a file.");
		$("[name='attach_item_file']").focus();
		err = "1";
		return false;
	}
	
	var allowedFiles = [".pdf", ".png", ".jpg"];
	if($("#isAction").val() == "claimorderpicturesvideos"){			
		allowedFiles = [".pdf", ".png", ".jpg", ".mp4"];
		var fileInput = document.getElementById("attach_item_file");
		var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
		if(fileInput.files.length > 3){
			alert("Please upload maximum 3 files.");				
			$("#attach_item_file").focus();
			err = "1";
			return false;
		}	
		for(var i = 0; i < fileInput.files.length; i++){
			var file_nm = fileInput.files[i].name;
			if (!regex.test(file_nm.toLowerCase()) || fileInput.files[i].size > 2000000) {
				alert("Please upload a valid file.");
				//$("[name='attach_item_file']").focus();
				$("#attach_item_file").focus();
				err = "1";
				return false;
			}
		}
		if(err == "1"){
			return false;
		}else{			
			$("#page-spinner").show();			
			$("#frmPoliceReportOrder").submit();
		}
	} else {
		var fileUpload = document.getElementById("fileUpload");
		var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
		if (!regex.test(fileinput.toLowerCase())) {
			alert("Please upload a valid file.");
			$("[name='attach_item_file']").focus();
			err = "1";
			return false;
		}
		
		if(err == "1"){
			return false;
		}else{			
			$("#page-spinner").show();			
			$("#frmPoliceReportOrder").submit();
		}	
	}	
});

// $(document).on('click',"#claimReportclick",function () {
// 	var err = "0";
	
// 	var fileinput = $("#attach_item_file").val();
	
// 	if(fileinput=='')
// 	{
// 		alert("Please upload a file.");
// 		$("[name='attach_item_file']").focus();
// 		err = "1";
// 		return false;
// 	}
	
// 	var allowedFiles = [".pdf", ".png", ".jpg"];
// 	var fileUpload = document.getElementById("fileUpload");
// 	var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
// 	if (!regex.test(fileinput.toLowerCase())) {
// 		alert("Please upload a valid file.");
// 		$("[name='attach_item_file']").focus();
// 		err = "1";
// 		return false;
// 	}
	
// 	if(err == "1"){
// 		return false;
// 	}else{
// 		$("#frmPoliceReportOrder").submit();
// 	}	
// });