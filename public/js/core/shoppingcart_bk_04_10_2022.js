var token = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {
	if ($('.cart-table').length > 0) {
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 500);	
	}
	DisplayPopupFreeGift();
	$('[data-toggle="tooltip"]').tooltip({html: true });
	if($("#shopcartpage").length > 0 || $('#proddetails').length > 0)
	{
		AfterpaySetup();
	}
});
$(document).on('click', ".addtocart", function () {
	var productID = $(this).attr('data-product');
	if($(this).attr('data-type') == 'sticky')
		var prodqty = $(".sticky-qty #prodqty").val();
	else
		var prodqty = $("#prodqty").val();
	AddToCart(productID, prodqty);
});
$(document).on('click', ".prodaddcart", function () {
	var productID = $(this).attr('data-product');
	var prodqty = 1;
	AddToCart(productID, prodqty);
});
$(document).on('click', "#buyitnow", function () {
	var productID = $(this).attr('data-product');
	var prodqty = 1;
	AddToCart(productID, prodqty, 'buynow');
})
$(document).on('click', '#clear-bag', function () {
	ClearBag();
});
$(document).on('click',"#btnpaybycc", function(){
	$('#fund_type').val('card');
	$('#frmfund').submit();
});
$("#btnreorder").click(function(){
	var prodDetails = new Array();
	$(".chkitem").each(function (index, ele) {
		if($(this).prop('checked'))
		{
			var productID = $(this).val();
			var prodqty = $('#prodqty_'+productID).val();
			prodDetails.push({'productID' : productID, 'prodqty' : prodqty});
		}
	});
	if(prodDetails.length > 0 )
	{
		AddToCartForReorder(prodDetails);
	} else {
		alert('Please select product.');
		return false;
	}
});
$(document).on("click",".wproduct",function(){
	var productID = $(this).attr('id');
	var prodqty = $('#prodqty_'+productID).val();
	console.log(productID+'==='+prodqty);
	if(prodqty == '' || prodqty == '0')
	{
		alert('Please add product quantity');
		return false;
	}
	AddToCart(productID, prodqty);
});
$(".btnwproduct").click(function(){
	var prodDetails = new Array();
	$(".wprodqty").each(function (index, ele) {
		if($(this).val() != '' && $(this).val() != '0')
		{
			var ExpID = $(this).attr('id').split('_');
			var productID = ExpID[1];
			var prodqty = $(this).val();
			prodDetails.push({'productID' : productID, 'prodqty' : prodqty});
		}
	});
	if(prodDetails.length > 0 )
	{
		AddToCartForReorder(prodDetails);
	} else {
		alert('Please add quantity for product.');
		return false;
	}
})
$(document).on('click','#btnfreegift', function(){
	var index = $(".rdofreegift:checked").val();
	var ProductID = $("#productsid"+index).val();
	var FreeGiftProductID = $("#freeproductsid"+index).val();
	
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'FreeGift',
			products_id: ProductID,
			freeproductsid: FreeGiftProductID
		},
		success: function (data) {
			window.location.href=site_url+"shoppingcart";
		}
	});
})
$(document).on('click', ".cartItemDel, .shopcartItemDel", function () {
	if(confirm('Are you sure you want to remove the item?'))
	{
        $("#page-spinner").show();
		var CartID = $(this).attr('data-index');
		var Page = "sidecart";
		if ($('.cart-table').length > 0) {
			$("#page-spinner").show();
			Page = "Shopcart";
		}
		$.ajax({
			type: 'POST',
			url: site_url + 'cart',
			headers: {
				'X-CSRF-TOKEN': token
			},
			datatype: 'JSON',
			data: {
				CartID: CartID,
				action: 'remove',
			},
			success: function (data) {
                if(data.IsYotpoFreeProduct && data.IsYotpoFreeProduct == 'Yes')
                {
                    $("#yotpo-loyalty-cart-data").attr('data-has-free-product',false);
                }
				GetCart();
				if (Page == "Shopcart") {
					GetCartPartial(data.message);
				}
                $("#page-spinner").hide();
			}
		});
	}
});
$(document).on('click', '.cart-table .btn-number',function (e) {
	e.preventDefault();
	fieldName = $(this).attr('data-field');
	type = $(this).attr('data-type');
	var input = $(".cart-table input[name='" + fieldName + "']");
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
$(document).on('click', '.cart-table .btn-number', function () {
	var ProductID = $(this).attr('data-product');
	$("#page-spinner").show();
	setTimeout(UpdateCart, 1000, ProductID);
})

$(document).on('input propertychange paste', '.cart-table .input-number', function () {
	var ExpProd = $(this).attr('id').split('_');
	var ProductID = ExpProd[1];
	$("#page-spinner").show();
	setTimeout(UpdateCart, 1000, ProductID);
})

$(document).on('click', '.clsgiftbox', function () {
	var giftwrap = 'No';
	if ($(this).prop('checked')) {
		giftwrap = 'Yes';
	}
	var ProductID = $(this).attr('data-product');
	UpdateCart(ProductID, giftwrap);
})
$(document).on('click', "#btncouponapply", function () {
	ApplyCouponCode();
	$('html, body').animate({
		scrollTop: $("body").offset().top
	}, 500);
});
$(document).on('click', "#btncredit", function () {
	ApplyCreditLimit();
	$('html, body').animate({
		scrollTop: $("body").offset().top
	}, 500);
});
$(document).on('click', "#btnremovecred", function () {
	RemoveCreditLimit();
	$('html, body').animate({
		scrollTop: $("body").offset().top
	}, 500);
});

$(document).on('click', "#btngiftcard", function () {
	ApplyGiftCoupon();
	$('html, body').animate({
		scrollTop: $("body").offset().top
	}, 500);
});
$(document).on('click', '.remdis', function () {
	var dataid = $(this).attr('data-id');
	if(dataid == 'CouponDiscount')
	{
		RemoveCouponDiscount();
	}
	if (dataid == 'GiftCoupon') {
		RemoveGiftCoupon();
	}
	if (dataid == 'YotpoRewardDiscount') {
		RemoveYotpoRewardDiscount();
	}
})
function AddToCart(productID, prodqty, btnFrom = '') {
    $("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			products_id: productID,
			prodqty: prodqty,
			action: 'insert',
		},
		success: function (data) {
			//console.log(data)
            $("#page-spinner").hide();
			if (btnFrom == 'buynow') {
				window.location.href = site_url + 'billing';
			} else {
				$('#cart-open').animate({
					right: '0px'
				});
				$('body').toggleClass('slide-open');
				setTimeout(function(){
                    
                },2500);
				GetCart();
			}
            fbq('track', 'AddToCart');
		}
	});
}

function AddToCartForYotpoFreeGift(products_id,yotpo_free_gift_coupon) {
    var added = false;
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			products_id: products_id,
			action: 'yotpo_free_gift_insert',
            yotpo_free_gift_coupon: yotpo_free_gift_coupon,
		},
        async : false,
		success: function (data) {	
			GetCart();
			$('#cart-open').animate({
				right: '0px'
			});
			$('body').toggleClass('slide-open');
            
            if(data.Added && parseInt(data.Added) == 1)
            {
                added = true;
            } else {
                added = false;
            }
		}
	});
    return added;
}

function AddToCartForReorder(prodDetails) {
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			prodDetails: prodDetails,
			action: 'reorder',
		},
		success: function (data) {			
			GetCart();
			$('#cart-open').animate({
				right: '0px'
			});
			$('body').toggleClass('slide-open');
            fbq('track', 'AddToCart');
		}
	});
}
function UpdateCart(ProductID, giftwrap = 'No') {
	var prodqty = $("#prodqty_" + ProductID).val();
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			products_id: ProductID,
			prodqty: prodqty,
			giftwrap: giftwrap,
			action: 'update',
		},
		success: function (data) {
			if (data.CartErrors) {
				var msg = '';
				for (var i = 0; i < data.CartErrors.length; i++) {
					msg += data.CartErrors[i] + '<br>';
				}
				var OldQty = $("#prodqty_" + ProductID).attr('data-qty');
				$("#prodqty_" + ProductID).val(OldQty);
				ShowMsg('error_cart', msg);
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial();
				DisplayPopupFreeGift();
			}
		}
	});
}
function GetCartPartial(message = '',msgtype='') {
	$.ajax({
		type: 'POST',
		url: site_url + 'getshopcart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		success: function (data) {
			if (data.TotalItemInCart > 0) {
				$(".cart-table").html(data.Cart);
				$("#cartsubtotal").html(data.SubtotalBoxHTML);
				$("#checkoutbox").html(data.CheckoutBoxHTML);
				$("#credcoupboxes").html(data.CreditCouponBoxesHtml);
				$("#page-spinner").hide();
				$("#shoptotal").html(data.Total);
				if(msgtype == 'error')
					ShowMsg('error_cart', message);
				else
					ShowMsg('cart_msg', message);
				AfterpaySetup();
			} else {
				$("#shopcartpage").html(data.EmptyCartHTML);
			}
			$("#page-spinner").hide();
		}
	});
	return true;
}
function GetCart() {
	$.ajax({
		type: 'POST',
		url: site_url + 'getcart',
		headers: {
			'X-CSRF-TOKEN': token
		},
        async:false,
		datatype: 'JSON',
		data: {
			cartpopup: 1,
		},
		success: function (data) {
			$("#shopcart").html(data.ShoppingCart);
			var TotalItemInCart = data.TotalItemInCart;
			if (TotalItemInCart == 0)
				TotalItemInCart = '';
			if(TotalItemInCart != '' && TotalItemInCart > 0)
			{
				$(".cart-qty").show();
				$(".cart-qty").html(TotalItemInCart);
			} else {
				$(".cart-qty").hide();
				$(".cart-qty").html(TotalItemInCart);
			}
			/*
			if(data.MerchantID && data.MerchantID != '' )
			{				
				var authRequest;
					OffAmazonPayments.Button("AmazonPayButtonAll", data.MerchantID, {
						type: "PwA",
						size: "large",
						authorization: function () {
							loginOptions = { scope: "profile postal_code payments:widget payments:shipping_address", popup: true };
							authRequest = amazon.Login.authorize(loginOptions, data.CallBackURL);
					},
					onError: function (error) {
						// something bad happened
					}
				});
			}
			*/	
		}
	});
}
function ClearBag() {
	if(confirm('Are you sure you want to remove all items?'))
	{
		$.ajax({
			type: 'POST',
			url: site_url + 'cart',
			headers: {
				'X-CSRF-TOKEN': token
			},
			datatype: 'JSON',
			data: {
				action: 'clear_bag',
			},
			success: function (data) {
				window.location.reload();
			}
		});
	}	
}
function ApplyCouponCode() {
	var coupon_number = $("#coupon_number").val();
	if (coupon_number == "") {
		alert("Please enter coupon");
		return false;
	}
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			coupon_number: coupon_number,
			action: 'apply_coupon',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				var msgType = '';
				if(data.error && data.error == 1)
					msgType = 'error';
				GetCartPartial(data.message,msgType);
				$("#page-spinner").hide();
			}
		}
	});
}

function ApplyCreditLimit() {
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'apply_credit_limit',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial(data.message);
				if(data.NetTotal > 0)
				{
					$("#Paymethods").show();
				} else {
					$("#Paymethods").hide();
				}
			}
		}
	});
}
function RemoveCreditLimit() {
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'remove_credit_limit',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial(data.message);
				if(data.NetTotal > 0)
				{
					$("#Paymethods").show();
				} else {
					$("#Paymethods").hide();
				}
			}
		}
	});
}
function ApplyGiftCoupon() {
	var giftcard = $("#txtgiftcard").val();
	if (giftcard == "") {
		alert("Please enter gift card");
		return false;
	}
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			giftcard: giftcard,
			action: 'apply_gift_coupon',
		},
		success: function (data) {
			GetCart();
			var msgType = '';
			if(data.error && data.error == 1)
				msgType = 'error';
			GetCartPartial(data.message,msgType);
		}
	});
}

function RemoveCouponDiscount() {
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'remove_coupon',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial(data.message);
			}
		}
	});
}

function RemoveYotpoRewardDiscount() {
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'remove_yotporeward',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial(data.message);
			}
		}
	});
}


function RemoveGiftCoupon() {
	$("#page-spinner").show();
	$.ajax({
		type: 'POST',
		url: site_url + 'cart',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data: {
			action: 'remove_gift_coupon',
		},
		success: function (data) {
			if (data.CartErrors) {
				$("#page-spinner").hide();
			} else {
				GetCart();
				GetCartPartial(data.message);
			}
		}
	});
}

function ShowMsg(msgid, message) {
	if (message != '') {
		$("#" + msgid).show().html(message);
	}
}
function DisplayPopupFreeGift() {
	if ($("#shopcartpage").length > 0 || $("#checkout-page").length > 0)  {
		//var TotalValue = parseFloat($(".business_page").attr('data-total'));
		//if (TotalValue > 0) {
			var token = $('meta[name="csrf-token"]').attr('content');
			$("#FreeGiftViewPopup").html('');
			$("#cover-spin").show();
			$.ajax({
				type: 'POST',
				url: site_url + 'get_freegift_products',
				data: { },
				headers: {
					'X-CSRF-TOKEN': token
				},
				success: function (data) {
					if (data) {
						$("#cover-spin").hide();
						$("#FreeGiftViewPopup").html(data);
						$("#FreeGiftViewPopup").modal('show');
					}
				}
			});
		//}
	}
}
