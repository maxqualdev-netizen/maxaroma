$(document).on('click', '#wholesaleproducts .btn-number',function (e) {
	e.preventDefault();
	fieldName = $(this).attr('data-field');
	type = $(this).attr('data-type');
	var input = $("#wholesaleproducts input[name='" + fieldName + "']");
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
$(document).ready(function(){
	
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetWholesaleProducts();
	});
	
	//~ $(document.body).on('click', '#sbmitalert', function(){
		//~ alert("test");	
	//~ })	
	
});


$('.input-number').each(function(){
	$( this ).keydown(function(e) {
		var k = e.which;
		/* numeric inputs can come from the keypad or the numeric row at the top */
		if ( (k < 48 || k > 57) && (k < 96 || k > 105) && k!=8 && k!=46) {
			e.preventDefault();
			return false;
		}
	});	
});
$("#all_items").click(function(){
	var pageLink = site_url+'special-product-list';
	if($('#prev_search_keyword').val() != '')
		pageLink+='/key-'+$('#prev_search_keyword').val();
	if($("#all_items").prop('checked')) 
		pageLink+='/all-items';
	window.location = pageLink
})
var token11 = $('meta[name="csrf-token"]').attr('content');		
var searchData = "";
$("#search_keyword").autocomplete({	
	source: function( request, response ) {
		
		var all_items = 'No';
		if($("#all_items").prop('checked'))
			all_items = 'Yes';
		
		$.ajax({
		  url: site_url+'searchwholesaleproducts',
		  headers: {
			'X-CSRF-TOKEN': token11
		  },
		  type: "POST",
		  dataType: "json",
		  data: {
			search_keyword: request.term,
			all_items:all_items
		  },
		  success: function( data ) {
			   console.log(data);
			   searchData = request.term;
				response( data );
			}
		});
	  },
	  minLength: 3,
	  select: function( event, ui ) {
			var all_items = '';
			if($("#all_items").prop('checked'))
				all_items = 'all-items';
			event.preventDefault();
			//~ if (ui.item.value == -1) {
				//~ $("#search_keyword").val("");
				//~ return false;
			//~ }
			
			$("#search_keyword").val(searchData);
			//window.location =  site_url+'special-product-list/key-'+ui.item.label.split(' ').join('+').split('&').join('and');
			//window.location =  site_url+'special-product-list/key-'+encodeURI(ui.item.label);
			if(all_items !='')
				window.location =  site_url+'special-product-list/key-'+encodeURI(searchData)+'/'+all_items;
			else 
				window.location =  site_url+'special-product-list/key-'+encodeURI(searchData);
			
	  },
	  search  : function(){$(this).addClass('working');},
	  response: function(){$(this).removeClass('working');},
	  classes: {
		"ui-autocomplete": "cid111"
	  },open: function(){
        $('.ui-autocomplete').css({'height':'350px','overflow-y':'scroll','overflow-x':'hidden'});
      }
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	//console.log(item);
	
	let prname = String(item.data.product_name).replace(new RegExp(this.term, "gi"),"<b class=\"searchkey\">$&</b>");
	let sku = String(item.data.sku).replace(new RegExp(this.term, "gi"),"<b class=\"searchkey\">$&</b>");
	let upc = String(item.data.upc).replace(new RegExp(this.term, "gi"),"<b class=\"searchkey\">$&</b>");
	
	if(item.value == 0){
		var item = '<div class="mndiv"><div>'+prname+'</div></div>';
	}else{
		var item = '<div class="mndiv"><div style="width: 10%;float: left;"><img src="'+item.data.thumb_image+'" alt="'+item.data.product_name+'" ></div><div style="float: left;padding-left: 5px;width: 90%;">'+prname+'<br>SKU : '+sku+'&nbsp;<br>UPC : '+upc+'</div><div style="clear:both"></div></div>';
	}
	
	//var item = $('<div class="mndiv"><div><img src="'+item.data.thumb_image+'" alt="'+item.data.product_name+'" width="26px"></div><div>'+prname+'<br>SKU:'+sku+'&nbsp;&nbsp;UPC:'+upc+'</div></div>');

	return $("<li>").append(item).appendTo(ul);
};
/*

$(".add_cart").on("click",function(){
	var valid=0;
	var qty = "";
	var sku = "";
	var product_id = "";
	var name_arr = "";
	var attr_name = "";
	
	$(".input-number").each(function(index){
		qty = $(this).val(); 
		attr_name = $(this).attr("name"); 
		name_arr = attr_name.split("_");
		
		product_id += name_arr[1]+",";
		sku += $("#SKU_"+product_id).val()+",";
		
		if(qty != "" && parseInt(qty) > 0){
			valid = valid + 1;		
		}	
	});
	
	if(valid <= 0){
		alert("Please enter atleast one item quantity.");
		return false;
	}
})
*/
function GetWholesaleProducts() {
	var token = $('meta[name="csrf-token"]').attr('content');
	var search_keyword = $('#prev_search_keyword').val();
	$("#page-spinner").show();
	
	if($("#all_items").prop('checked'))
	{
		var ajaxdata = {'page' : parseInt($('.list-more').attr('data-page')),
		'search_keyword': search_keyword,'all_items':'Yes'};
	} else {
		var ajaxdata = {'page' : parseInt($('.list-more').attr('data-page')),
		'search_keyword': search_keyword};
	}
	$.ajax({
		type:'POST',
		url:site_url+'specialproductlistmore',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data:ajaxdata,
		success:function(data) {
			var TotalProds = data.TotalProducts;
			var PerPage = data.PerPage;
			var PageSize = parseInt($(".list-more").attr('data-page')) * PerPage;
			
			if(TotalProds > 0){
				if(PageSize >= TotalProds){
					$("#loadmore").hide();
				}else{
					$("#loadmore").show();
				}
			}
			
			var page = parseInt($('.list-more').attr('data-page'));
			if(page > 1 ){
				$('.listing_grid').append(data.ProductHTML).fadeIn(1000);
			}else{
				$('.listing_grid').html(data.ProductHTML).fadeIn(1000);
			}
			$("#page-spinner").hide();
		}
	});
 }
 
 function DisplayPopupBoxAlertMe(products_id,sku) {
			$("#page-spinner").show();
			$("#alertmodalpopup").html('');
			var token = $('meta[name="csrf-token"]').attr('content');
			
			var str="iproductid="+products_id;
	 	 	str+="&vsku="+sku;
	 	 	
	 	 	$.ajax({
				type:'POST',
				url:site_url+'wholesalealertpopup',
				headers: {
					'X-CSRF-TOKEN': token
				},
				data: str,
				success:function(data) {
					$("#alertmodalpopup").html(data.ProductHTML);
					$("#alertmodalpopup").show('modal');
					
					$("#page-spinner").hide();
				}
				
			});
}

function submitform_alert(){
	$('#form1').validate({
			ignore: ".ignore",
			rules: {
				alert_email: { required: true, email: true},		   
			},
			messages: {
				alert_email: {required: GetMessage('Validate','Email'),email: GetMessage('Validate','ValidEmail')}
					
			},
			onsubmit: false,		
		});		
		
	
	if($('#form1').valid()) {
		$("#page-spinner").show();
		$("#email_txt").remove();
		
		var data = $("#form1").serialize();
		var token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
				type:'POST',
				url:site_url+'wholesalealertpopup',
				headers: {
					'X-CSRF-TOKEN': token
				},
				data: data,
				dataType: "json",
				success:function(data) {
					var str = "<p>"+data.ProductHTML+"</p>";
					$(".alrtmsgfrm").hide();
					
					$("#alrtmsg").html(str);
					$("#alrtmsg").show();
					
					$("#page-spinner").hide();
				},
				error: function(data){
					//console.log(data);
					//data.responseJSON.errors
					
					var str = '<label for="alert_email" id="email_txt" class="error">'+GetMessage('Validate','ValidEmail')+'</label>';
					
					//$("#email_txt").html(str);
					$("#alert_email").after(str);
					$("#page-spinner").hide();
				}
			});	
	}

}
