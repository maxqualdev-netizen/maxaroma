/* newsletter.js */

$('#frmNewsletter input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmNewsletter');
		return false;
	}
});

function newsletter_submit1() {
	submitform('frmNewsletter');
}

$(document).ready(function()
{
	jQuery.validator.addMethod('phoneUS', function (value, element){
 
		return this.optional(element) || /^[0-9 +-]+$/.test(value);
	   
	  }, "Please enter a valid phone number");

	$("#frmNewsletter").submit(function(e)
	{
		$("#frmNewsletter .frmerror").html('');
		$('#frmNewsletter').validate({
			rules: 
			{
				news_firstname: { required: true  },
				news_lastname: { required: true  },
				news_email1: { required: true, email: true  },
				news_contactno: {
					required: true,
					minlength: 10,
					phoneUS: true
				}
			},
			messages: 
			{
				news_firstname: 	{ required: GetMessage('Newsletter','FirstName') },
				news_lastname: 	{ required: GetMessage('Newsletter','LastName') },
				news_email1: 	{ required: GetMessage('Newsletter','Email'), email: GetMessage('Newsletter','ValidEmail') },
				news_contactno: 	{ required: GetMessage('Validate','Phone') }
			},
			onsubmit: false,
			invalidHandler: function(form, validator) 
			{
				var errors = validator.numberOfInvalids();
				if (errors) 
				{
					var allMessages = '';
					for(var i=0;i<errors;i++)
					{
						var message = validator.errorList[i].message;
						var id = $(validator.errorList[i].element).attr('name');
						$("#frmNewsletter #error_"+id).html(message);
						$("#frmNewsletter #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmNewsletter .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmNewsletter').valid()) {
			return false;
		} else {
			e.preventDefault();
			submitNewsletterFrm();	
		}
    });

});

function submitNewsletterFrm()
{
	var submitUrl 	= site_url+'newsletter-subscribe';
	var news_firstname = $("#news_firstname").val();
	var news_lastname = $("#news_lastname").val();
	var news_email1 = $("#news_email1").val();
	var news_contactno = $("#news_contactno").val();
	var str="news_firstname="+news_firstname+"&news_lastname="+news_lastname+"&news_email1="+news_email1+"&news_contactno="+news_contactno;
	$.ajax({
	   type: 'POST',
	   url: submitUrl,
	   data: str,
	   cache: false,
	   async: true,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
	   success:function(data){
		  if(data.success == false)
		  {
			$("#errmsgPopUp").html(data.message);
			$("#errmsgPopUp").show();
		  } 
		  else 
		  {
			$("#success-cls").html(data.message).show();
			$("#news_firstname").val('');
			$("#news_lastname").val('');
			$("#news_email1").val('');
			$("#news_contactno").val('');
		  }
	   }, 
	   error:function(err){
			$('#error_news_firstname').text('');
			$('#error_news_lastname').text('');
			$('#error_news_email1').text('');

			$('#error_news_firstname').text(err.responseJSON.errors.news_firstname);
			$('#error_news_lastname').text(err.responseJSON.errors.news_lastname);
			$('#error_news_email1').text(err.responseJSON.errors.news_email1);
			
			$("#error_news_firstname").show();
			$("#error_news_lastname").show();
			$("#error_news_email1").show();
	   },
	});
}
