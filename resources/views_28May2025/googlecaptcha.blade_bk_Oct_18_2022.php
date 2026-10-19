<div class="d-block mt-3">
	{!! NoCaptcha::display() !!}
</div>
<x-message :attr="[
	'classname' => 'frmerror', 
	'message' => '',
	'mid' => 'error_captcha_js']" 
/>
@if ($errors->has('g-recaptcha-response'))
<span class="help-block">
	<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
</span>
@endif
{!! NoCaptcha::renderJs() !!}
<input id="hiddenRecaptcha" name="hiddenRecaptcha" type="text" class="form-control" style="display:none;" required>
