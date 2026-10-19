$('#checkAll').click(function () {    
	$('input:checkbox').prop('checked', this.checked);    
});

$('#btnDeleteWishProduct').click(function () {    
	var total=$('#frmwish').find('input[name="ch[]"]:checked').length;
	if (total>0)
	{	
		rtn=confirm("Delete Selected Record ?");
		if(rtn==false)
		{	
			return false;	
		}
		else
		{	
			document.frmwish.action.value="DeleteWishProd";
			submitform('frmwish');	
		}
	}
	else
	{
		alert("Please select Checkbox.");
		return false;
	}
});
