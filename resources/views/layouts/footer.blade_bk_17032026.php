<footer class="border-top mt-3 mt-sm-3 mt-md-0" role="contentinfo" aria-label="Site Footer">
    <div class="container pt-3 pt-sm-3 pt-md-5">
        {!! $BottomHtmlText !!}
        <div class="footer-bottom pt-4 pb-4 text-center">
            <div class="footer-paylogo" aria-label="Accepted Payment Methods">
                <ul class="botcard text-center" role="list" aria-label="Payment Logos">
                    <li role="listitem">
                        <a href="#" aria-label="American Express">
                            <svg class="card-amex vam" aria-hidden="true" role="img" width="48" height="30"><use href="#card-amex" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-amex"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Visa">
                            <svg class="card-visa vam" aria-hidden="true" role="img" width="48" height="48"><use href="#card-visa" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-visa"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Mastercard">
                            <svg class="card-master vam" aria-hidden="true" role="img" width="48" height="30"><use href="#card-master" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-master"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Discover">
                            <svg class="card-discover vam" aria-hidden="true" role="img" width="48" height="48"><use href="#card-discover" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-discover"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="PayPal">
                            <svg class="card-paypal vam" aria-hidden="true" role="img" width="48" height="48"><use href="#card-paypal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-paypal"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="#" aria-label="Amazon Pay">
                            <svg class="card-amazon vam" aria-hidden="true" role="img" width="48" height="48"><use href="#card-amazon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#card-amazon"></use></svg>
                        </a>
                    </li>
                    <li role="listitem">
                        <a href="https://www.google.com/shopping/customerreviews/merchantreviews?q=maxaroma.com" target="_blank" rel="noopener" aria-label="Google Customer Reviews">
                            <img class="bot-cr" src="{{config('global.SITE_IMAGES')}}customer-review.jpg" width="135" height="60" alt="Google Customer Reviews">
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
<div class="footer-fixed" role="region" aria-label="Customer assistance">
    <div class="f-chat-i" aria-hidden="true">
        <svg class="chat-nw vam" width="20" height="20" focusable="false">
            <use href="#chat-nw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat-nw"></use>
        </svg>
    </div>

    <div class="f-chat-box">
        <a href="javascript:void(0);" id="getHelpChatNew" class="HelpChat" role="button" aria-haspopup="dialog" aria-controls="chat-dialog" aria-label="Chat with customer support">Chat with us</a>
    </div>
</div>

<script>
	const chatElements = document.getElementsByClassName("getHelpChatNew");
    for (const chatElement of chatElements) {
        chatElement.addEventListener("click", function() {
            loadSupportChat();
        });
    }
</script>
