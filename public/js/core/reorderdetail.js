$(document).on('click', '#frmReOrder .btn-number',function (e) {
	e.preventDefault();
	fieldName = $(this).attr('data-field');
	type = $(this).attr('data-type');
	var input = $("#frmReOrder input[name='" + fieldName + "']");
	var currentVal = parseInt(input.val());
	if (!isNaN(currentVal)) {
		if (type == 'minus') {
			if (currentVal > input.attr('min')) {
				input.val(currentVal - 1).change();
			}
			if (parseInt(input.val()) == input.attr('min')) {
				$(this).attr('disabled', true);
			}
		} else if (type == 'plus') {
			//if (currentVal < input.attr('max')) {
			input.val(currentVal + 1).change();
			//}
			/*if (parseInt(input.val()) == input.attr('max')) {
				$(this).attr('disabled', true);
			}*/
		}
	} else {
		input.val(0);
	}
});

$('#btnAddToCart').click(function () {
	qty_flag ="";
	product_id = "";
	var total=$('#frmReOrder').find('input[name="ch[]"]:checked').length;
	if(total>0)
	{	
		var arrayFromPHP = <?php echo json_encode($ProdRS); ?>;
		$.each(arrayFromPHP, function( index, value ) {
			var isChcked = $("#isChecked"+value.products_id).val();
			if(isChcked==0) {
				var qty1 = $("#quantity"+value.products_id).val();
				
				qty_flag+= qty1+",";
				product_id+=value.products_id+","; 
				if(checkValidNumber1(qty1)==false) {
					alert("Please Enter Valid Numeric Quantity Value.");	
					$("#quantity"+value.products_id).focus();
					return false;
				}
				if(qty1 < 1) {
					alert("Please enter quantity.");
					$("#quantity"+value.products_id).focus();
					return false;
				}
			}
		});

		rtn=confirm("Add Selected Items(s) To Shopping Cart ?");
		if(rtn==false)
		{	
			return false;	
		}
		else
		{		
			// Redirect to Add to cart.	
		}
	}
	else
	{
		alert("Please select Checkbox.");
		return false;
	}
});

function checkValidNumber1(val)
{
	var checkOK1 = "0123456789"
	var allValid1 = true;
	var checkStr1 = val;
	for (i = 0; i < checkStr1.length; i++)
	{
		ch = checkStr1.charAt(i);
		for (j = 0; j < checkOK1.length; j++)
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

function goCheck(products_id,isChecked)
{
	var str = "";
	var qty_temp = $("#quantity"+products_id).val();
	//var qty = number(qty_temp);
	if(isChecked==true)
	{
		$("#isChecked"+products_id).val(1);
	}
	else
	{
		$("#isChecked"+products_id).val(0);
	}
}
