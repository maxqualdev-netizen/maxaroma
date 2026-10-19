<footer class="border-top mt-3 mt-sm-3 mt-md-0 ">
  <div class="container pt-3 pt-sm-3 pt-md-5">
    {!! $BottomHtmlText !!}
    <div class="footer-bottom pt-4 pb-4 text-center">

         <div class="footer-paylogo">
          <ul class="botcard text-center">
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-amex.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-visa.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-master.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-discover.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-paypal.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-amazon.svg" width="48" height="48" class="" alt=""></a></li>
            <li><a href="https://www.google.com/shopping/customerreviews/merchantreviews?q=maxaroma.com" target="_blank"><img class="bot-cr" src="{{config('global.SITE_IMAGES')}}customer-review.svg" width="135" height="52" class="" alt=""></a></li>
          </ul>
        </div>
        <div class="fot-copy text-center">
          Copyright © {{ date('Y') }} MAXAROMA.com All Rights Reserved.
        </div>
    </div>
  </div>
</footer>
</div>
<script type="text/javascript">
var newroutenm='<?php echo Route::currentRouteName();?>'; 
</script>
<!-- <div class="footer-fixed">
<a href="#" class="chat-footer">
   <svg class="sv-chat vam" aria-hidden="true" role="img" width="20" height="20">
      <use href="#sv-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chat"></use>
    </svg>
</a>
<a href="#top" style="opacity: 100;" class="back-to-top">
   <svg class="top-sv-up vam" aria-hidden="true" role="img" width="16" height="16">
      <use href="#top-sv-up" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#top-sv-up"></use>
    </svg>
</a>
</div> -->
<div class="footer-fixed">
  <div class="f-chat-i">
    <svg class="chat-nw vam" aria-hidden="true" role="img" width="20" height="20">
      <use href="#chat-nw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat-nw"></use>
    </svg>
  </div>
  <div class="f-chat-box">
    <a href="javascript:void(0);" id="getHelpChat" class="HelpChat">Chat with us</a>
  </div>
</div>
