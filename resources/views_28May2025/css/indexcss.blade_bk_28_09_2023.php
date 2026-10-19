@if(config('global.ENV') == 'Dev')
	<style>
		<?php
			require_once(public_path('/css/core/jquery-ui1.12.1.css'));
			
			if(Route::currentRouteName()=="shoppingcart_new" || Route::currentRouteName()=="setcart_new" || Route::currentRouteName()=="getcarthtml_new" || Route::currentRouteName()=="getcartpartial_new" || Route::currentRouteName()=="billing_new" || Route::currentRouteName()=="billing_new-shipping" || Route::currentRouteName()=="billing_new-payment")
			{
				require_once(public_path('/css/core/common_new.css'));
			}
			else
			{
				require_once(public_path('/css/core/common.css'));
			}
            require_once(public_path('/css/core/header-footer.css'));    
			require_once(public_path('/css/core/custom.css'));
			if(count($CSSFILES) > 0) {
				foreach($CSSFILES as $cssfile)
				{
					require_once(public_path('/css/core/'.$cssfile));	
				}
			} 
		?>
	</style>	
@else	
<?php if(count($CSSFILES) > 0) {?>
<style>
	<?php
		foreach($CSSFILES as $cssfile)
		{
			require_once(public_path('/css/'.$cssfile));	
		}
	?>
</style>
<?php } ?>
@endif
