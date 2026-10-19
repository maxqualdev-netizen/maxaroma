@if(config('global.ENV') == 'Dev')
	<style>
		<?php
			require_once(public_path('/css/core/jquery-ui1.12.1.css'));
			require_once(public_path('/css/core/common.css'));
            if(isset($NewHomeDesign) && $NewHomeDesign == 1)
			     require_once(public_path('/css/core/header-footer-new.css'));
            else
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
