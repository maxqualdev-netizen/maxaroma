<footer class="border-top mt-3 mt-sm-3 mt-md-0" role="contentinfo" aria-label="Site Footer">
    <div class="container pt-3 pt-sm-3 pt-md-5">
        {!! $BottomHtmlText !!}
        <div class="footer-bottom pt-4 pb-4 text-center">
            <div class="footer-paylogo" aria-label="Accepted Payment Methods">
                <ul class="botcard text-center" role="list" aria-label="Payment Logos">
                    <li role="listitem">
                        <a href="#" aria-label="American Express">
                            <img src="{{config('global.SITE_IMAGES')}}card-amex.svg" width="48" height="48" alt="American Express Card">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Visa">
                            <img src="{{config('global.SITE_IMAGES')}}card-visa.svg" width="48" height="48" alt="Visa Card">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Mastercard">
                            <img src="{{config('global.SITE_IMAGES')}}card-master.svg" width="48" height="48" alt="Mastercard">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Discover">
                            <img src="{{config('global.SITE_IMAGES')}}card-discover.svg" width="48" height="48" alt="Discover Card">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="PayPal">
                            <img src="{{config('global.SITE_IMAGES')}}card-paypal.svg" width="48" height="48" alt="PayPal">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Amazon Pay">
                            <img src="{{config('global.SITE_IMAGES')}}card-amazon.svg" width="48" height="48" alt="Amazon Pay">
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="https://www.google.com/shopping/customerreviews/merchantreviews?q=maxaroma.com" target="_blank" rel="noopener" aria-label="Google Customer Reviews">
                            <img class="bot-cr" src="{{config('global.SITE_IMAGES')}}customer-review.svg" width="135" height="52" alt="Google Customer Reviews">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="fot-copy text-center" aria-label="Copyright">
                Copyright © {{ date('Y') }} MAXAROMA.com All Rights Reserved.
            </div>
        </div>
    </div>
</footer>
</div>
<script type="text/javascript">
    var newroutenm = '<?php echo Route::currentRouteName(); ?>';
</script>
<!-- <div class="footer-fixed">
    <a href="#" class="chat-footer" aria-label="Open Chat">
        <svg class="sv-chat vam" aria-hidden="true" role="img" width="20" height="20" focusable="false">
            <use href="#sv-chat" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chat"></use>
        </svg>
    </a>
    <a href="#top" style="opacity: 100;" class="back-to-top" aria-label="Back to top">
        <svg class="top-sv-up vam" aria-hidden="true" role="img" width="16" height="16" focusable="false">
            <use href="#top-sv-up" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#top-sv-up"></use>
        </svg>
    </a>
</div> -->
<div class="footer-fixed" role="region" aria-label="Footer Chat and Navigation">
    <div class="f-chat-i">
        <svg class="chat-nw vam" aria-hidden="true" role="img" width="20" height="20" focusable="false">
            <use href="#chat-nw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat-nw"></use>
        </svg>
    </div>
    <div class="f-chat-box">
        <a href="javascript:void(0);" id="getHelpChat" class="HelpChat" role="button" aria-label="Chat with us">Chat with us</a>
    </div>
</div>