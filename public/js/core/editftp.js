$('#frmEditFtp input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmEditFtp');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnEditFtp").click(function(){
		submitform('frmEditFtp');
	});

	$("#frmEditFtp").submit(function()
	{
		$("#frmEditFtp .frmerror").html('');
		$('#frmEditFtp').validate({
			rules: 
			{
				dropshipper_ftp_host: { required: true  },
				dropshipper_ftp_username: { required: true  },
				dropshipper_ftp_password: { required: true  },
				dropshipper_ftp_port: { required: true  },
				dropshipper_ftp_file_path: { required: true  },
				dropshipper_ftp_file_name: { required: true  }
			},
			messages: 
			{
				dropshipper_ftp_host: { required: GetMessage('FTP','Host') },
				dropshipper_ftp_username: { required: GetMessage('FTP','Username') },
				dropshipper_ftp_password: { required: GetMessage('FTP','Password') },
				dropshipper_ftp_port: { required: GetMessage('FTP','Port') },
				dropshipper_ftp_file_path: { required: GetMessage('FTP','Path') },
				dropshipper_ftp_file_name: { required: GetMessage('FTP','NamePrefix') }
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
						$("#frmEditFtp #error_"+id).html(message);
						$("#frmEditFtp #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmEditFtp .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmEditFtp').valid()) {
			return false;
		}
		
    });

});

$("#TestFTPVal").click(function(){

$('#frmEditFtp').validate({
			rules: 
			{
				dropshipper_ftp_host: { required: true  },
				dropshipper_ftp_username: { required: true  },
				dropshipper_ftp_password: { required: true  },
				dropshipper_ftp_port: { required: true  },
				dropshipper_ftp_file_path: { required: true  },
				dropshipper_ftp_file_name: { required: true  }
			},
			messages: 
			{
				dropshipper_ftp_host: { required: GetMessage('FTP','Host') },
				dropshipper_ftp_username: { required: GetMessage('FTP','Username') },
				dropshipper_ftp_password: { required: GetMessage('FTP','Password') },
				dropshipper_ftp_port: { required: GetMessage('FTP','Port') },
				dropshipper_ftp_file_path: { required: GetMessage('FTP','Path') },
				dropshipper_ftp_file_name: { required: GetMessage('FTP','NamePrefix') }
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
						$("#frmEditFtp #error_"+id).html(message);
						$("#frmEditFtp #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmEditFtp .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		

if(!$('#frmEditFtp').valid()) {
	return false;
}
var str ="dropshipper_ftp_type="+$("#dropshipper_ftp_type").val()+ "&dropshipper_ftp_host="+$("#dropshipper_ftp_host").val()+"&dropshipper_ftp_username="+$("#dropshipper_ftp_username").val()+"&dropshipper_ftp_password="+$("#dropshipper_ftp_password").val()+"&dropshipper_ftp_port="+$("#dropshipper_ftp_port").val()+"&dropshipper_ftp_file_path="+$("#dropshipper_ftp_file_path").val()+"&dropshipper_ftp_file_name="+$("#dropshipper_ftp_file_name").val()+"&dropshipper_ftp_picktime_order="+$("#dropshipper_ftp_picktime_order").val()+"&dropshipper_ftp_flag="+$("#dropshipper_ftp_flag").val()		
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
