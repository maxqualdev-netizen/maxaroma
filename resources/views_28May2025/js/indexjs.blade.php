@if(config('global.ENV') == 'Dev1111')
	<script>
	<?php 
		require_once(public_path('/js/core/jquery-3.4.1.min.js'));
		require_once(public_path('/js/core/jquery-ui1.12.1.js'));
		require_once(public_path('/js/core/jquery.validate.js')); 
		require_once(public_path('/js/core/jquery.selectbox-0.2.min.js'));
		require_once(public_path('/js/core/modal.js'));
		
		if(Route::currentRouteName()=="shoppingcart_new" || Route::currentRouteName()=="setcart_new" || Route::currentRouteName()=="getcarthtml_new" || Route::currentRouteName()=="getcartpartial_new" || Route::currentRouteName()=="billing_new" || Route::currentRouteName()=="billing_new-shipping" || Route::currentRouteName()=="billing_new-payment")
		{
			require_once(public_path('/js/core/common_new.js'));
		}
		else
		{
			require_once(public_path('/js/core/common.js'));
		}
		
		require_once(public_path('/js/core/popup.js'));
		require_once(public_path('/js/core/smooth-scroll.js'));
		require_once(public_path('/js/core/menu-new.js'));
		require_once(public_path('/js/core/jquery.slimscroll.js'));
		require_once(public_path('/js/core/head-bottom.js'));
		if(Route::currentRouteName()=="shoppingcart_new" || Route::currentRouteName()=="setcart_new" || Route::currentRouteName()=="getcarthtml_new" || Route::currentRouteName()=="getcartpartial_new" || Route::currentRouteName()=="billing_new" || Route::currentRouteName()=="billing_new-shipping" || Route::currentRouteName()=="billing_new-payment")
		{
			require_once(public_path('/js/core/shoppingcart_new.js'));	
		}
		else
		{
			require_once(public_path('/js/core/shoppingcart.js'));	
		}
		require_once(public_path('/js/core/newsletter.js'));		
		if(count($JSFILES) > 0) {
	?>
		
		<?php foreach($JSFILES as $jsfile){
			require_once(public_path('/js/core/'.$jsfile));	
		}?>
	<?php }?>
	</script>
@endif 
