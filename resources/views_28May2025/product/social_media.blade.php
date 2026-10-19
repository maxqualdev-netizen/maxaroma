<div class="f-fix row">
    <div class="col-lg-8 col-xl-6 col-md-12 col-sm-7 col-xs-12 pb15">
        <ul class="dtl-tab-social">
            <!-- <li> -->
                <!----------- For Facebook Like Button Start Here ---------------->
                <!-- <iframe id="sort_facebook" src="https://www.facebook.com/plugins/like.php?href={{$fburl}}&send=false&layout=button_count&width=100&amp;height=25&show_faces=true&action=like&colorscheme=light&font&height=25px" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:25px;" allowTransparency="true"></iframe> -->
                <!----------- For Facebook Like Button End Here ------------------>
            <!-- </li> -->
            <!-- <li>   -->
            <!----------- For Google Plus Button Start Here --------------->
            <!--<g:plusone href="{$fburl}" data-annotation="vertical-bubble" class="g-follow"></g:plusone>-->
            <!-- <div class="g-plusone" id="sort_gplusone" href="{{$fburl}}" data-annotation="bubble" data-height="15" data-size="medium"></div> -->
            <!----------- For Google Plus Button End Here ----------------->
            <!-- </li> -->
            <!-- <li> -->
            <!----------- For Twitter Button Start Here --------------->
            <!-- <a href="https://twitter.com/share" class="twitter-share-button" data-url="{{$fburl}}" id="sort_twitter" data-count="horizontal" data-lang="en" data-size="small">Tweet</a> -->
            <!----------- For Twitter Button End Here ----------------->
            <!-- </li> -->
            <li>
            <!----------- For Pinit Button Start Here ----------------->
            <a href="http://pinterest.com/pin/create/button/?media={{$productDetails->mainImage}}&url={{$fburl}}&description={{$productDetails->product_name}}" id="sort_pinrest" data-pin-config="beside" data-pin-do="buttonPin" class="pin-it-button"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
            <script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
            <!----------- For Pinit Button End Here ------------------>
            </li>
        </ul>
        
    </div>
</div>
<script>
    ! function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = "//platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);
        }
    }(document, "script", "twitter-wjs");
</script>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
    (function() {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();
</script>
<script type="text/javascript">
    var pi = document.createElement('script');
    pi.type = 'text/javascript';
    pi.src = '//assets.pinterest.com/js/pinit.js';
    var ps = document.getElementsByTagName('script')[0];
    ps.parentNode.insertBefore(pi, ps);
</script>