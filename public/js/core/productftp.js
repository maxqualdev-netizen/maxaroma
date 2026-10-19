$('#frmProductFtp input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmProductFtp');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnEditFtp").click(function(){		
		submitform('frmProductFtp');
	});

	
	$("#frmProductFtp").submit(function()
		{
			$("#frmProductFtp .frmerror").html('');
			//alert($("#ftp_flag").val());	
			$("#error_ftp_host").html('');		
			$("#error_ftp_username").html('');
			$("#error_ftp_password").html('');
			$("#error_ftp_port").html('');	
			$("#error_ftp_file_path").html('');
					
			if($("#ftp_flag").val() !='' && $("#ftp_flag").val().trim() == 'Yes'){
				$('#frmProductFtp').validate({
					rules: 
					{
						ftp_host: { required: true  },
						ftp_username: { required: true  },
						ftp_password: { required: true  },
						ftp_port: { required: true  },
						ftp_file_path: { required: true  }
					},
					messages: 
					{
						ftp_host: { required: GetMessage('FTP','Host') },
						ftp_username: { required: GetMessage('FTP','Username') },
						ftp_password: { required: GetMessage('FTP','Password') },
						ftp_port: { required: GetMessage('FTP','Port') },
						ftp_file_path: { required: GetMessage('FTP','Path') }
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
								$("#frmProductFtp #error_"+id).html(message);
								$("#frmProductFtp #error_"+id).show();
							}
						} 
					else 
					{
						$("#frmProductFtp .frmerror").html('');
					}
					},
					errorPlacement: function(error, element) 
					{
						// Override error placement to not show error messages beside elements //
					}     
				});
			}
			if(!$('#frmProductFtp').valid() && $("#ftp_flag").val().trim() == 'Yes') {
				return false;
			}
			
	});
	
});




$("#TestProductFTPVal").click(function(){

$('#frmProductFtp').validate({
			rules: 
			{
				ftp_host: { required: true  },
				ftp_username: { required: true  },
				ftp_password: { required: true  },
				ftp_port: { required: true  },
				ftp_file_path: { required: true  }
			},
			messages: 
			{
				ftp_host: { required: GetMessage('FTP','Host') },
				ftp_username: { required: GetMessage('FTP','Username') },
				ftp_password: { required: GetMessage('FTP','Password') },
				ftp_port: { required: GetMessage('FTP','Port') },
				ftp_file_path: { required: GetMessage('FTP','Path') }
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
						$("#frmProductFtp #error_"+id).html(message);
						$("#frmProductFtp #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmProductFtp .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		

if(!$('#frmProductFtp').valid()) {
	return false;
}
var str ="dropshipper_ftp_type="+$("#ftp_type").val()+ "&dropshipper_ftp_host="+$("#ftp_host").val()+"&dropshipper_ftp_username="+$("#ftp_username").val()+"&dropshipper_ftp_password="+$("#ftp_password").val()+"&dropshipper_ftp_port="+$("#ftp_port").val()+"&dropshipper_ftp_file_path="+$("#ftp_file_path").val()+"&dropshipper_ftp_file_name=''&dropshipper_ftp_picktime_order=''&dropshipper_ftp_flag="+$("#ftp_flag").val()

 $.ajax({
        type: "POST",
        url: site_url + "test_ftp_connection.php",
        data: str,
        success: function (response) {
          var newdata = JSON.parse(response);          
          if(newdata.status == 'testFTP') {
					if(newdata.success_mess != ''){
						$("#frmsuccess1").html(newdata.success_mess);
						$("#frmsuccess1").show();
					}
					if(newdata.error_mess != ''){
						$("#frmerror1").html(newdata.error_mess);
						$("#frmerror1").show();
					}
				}
          
        }
    })
});    
