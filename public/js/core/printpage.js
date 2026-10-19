
	function printpreview()
	{
		rtn = confirm('Do You Want To Print Order Slip?')
		if(rtn==false) 
		{
			//window.close();
		} 
		else 
		{
			//document.getElementById('HIDEROW1').style.display='none';
			//document.getElementById('HIDEROW2').style.display='none';
			$(".hiderow").hide();
			window.print();
		}
	}