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
