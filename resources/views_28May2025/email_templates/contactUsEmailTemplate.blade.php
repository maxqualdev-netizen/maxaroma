@php
$freeshippinginfo = '';
if(config('Settings.FREESHIPPING_VALUE')!="")
{
	$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
}
@endphp
<table style="margin: 0px auto; width: 75%; padding: 20px;" border="0" cellspacing="0" cellpadding="0">
   <tbody>
      <tr>
         <td style="padding-bottom:10px;">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                    <tr>
                        <td colspan="2" align="center" style="padding-bottom:30px;">
                            <img src="{{config('global.SITE_URL')}}public/images/logo.png" alt="Maxaroma"/>
                        </td>
                    </tr>   
               </tbody>
            </table>
         </td>
      </tr> 
      <tr>
         <td style="border-bottom:1px solid #000;">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                  <tr align="center">
                     <td style="padding-bottom:5px;">
                         <span><a style="color:#000; text-decoration:none; padding-right:25px; text-transform:uppercase;" href="{{ config('global.SITE_URL') }}fragrances/cid/1">Fragrances</a></span>
                         <span><a style="color:#000; text-decoration:none; padding-right:25px; text-transform:uppercase;" href="{{ config('global.SITE_URL') }}pocket-perfume/cid/68">Pocket Perfume</a></span>
                         <span><a style="color:#000; text-decoration:none; padding-right:25px; text-transform:uppercase;" href="{{ config('global.SITE_URL') }}candles/cid/208">Candles</a></span>
                         <span><a style="color:#000; text-decoration:none; padding-right:25px; text-transform:uppercase;" href="{{ config('global.SITE_URL') }}offers.html">Sale & Offer</a></span>
                    </td> 
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td>
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                  <tr>
                     <td style="padding:20px 10px;" colspan="2">
                        <p><strong>Customer Name :</strong> {{$request['your_name']}}</p>
                        <p><strong>Email : </strong> {{$request['your_email']}}</p>
                        <p><strong>Subject : </strong> {{$request['your_subject']}}</p>
                        <p><strong>Questions : </strong> {{$request['your_comment']}}</p>
                        <p>Sincerely,</p>
                        <p><strong>MaxAroma</strong></p>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td></td>
      </tr>
      <tr>
         <td style="background-color:#fafafa; padding:10px; text-align:center;">
            <p style="color:#cc3399; margin-bottom:5px;">
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}myaccount.html">My Account</a> &middot;  					 				
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/about_us.html">About Us</a> &middot;  					 				
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}contact-us.html">Contact Us</a>
            </p>
            <p style="color:#000;margin-top:5px; margin-bottom:25px;"> 
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/shipping_policy.html">Shipping Policy</a> &middot;  					 				
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/privacy_policy.html">Privacy Policy</a> &middot;  					 				
                <a style="color:#000; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/authenticity_promise.html">Authenticity Promise</a>
            </p>
            <p style="margin-bottom:25px;">Please add <a style="color:#000;text-decoration:none;" href="mailto:{{config('Settings.CONTACT_MAIL')}}">{{ config('Settings.CONTACT_MAIL') }}</a> to your address book to ensure our emails reach your inbox!<br /> If you had rather not receive emails, simply manage your subscriptions. We will miss you terribly, though.</p>
            <p style='margin-bottom:25px;'>&copy; 2021 MaxAroma.com, LLC. or its affiliates<br /> 3117 38th Ave, Long Island City , NY,11101</p>
            <p>Products on MaxAroma.com are sold by MaxAroma.com, LLC.</p>
            <p>
                <a href="https://www.facebook.com/yousmellawesome/"><img src="{{ config('global.SITE_URL') }}images/facebook.png" alt="" /></a> 
                <a href="https://twitter.com/Max_Aroma"><img src="{{ config('global.SITE_URL') }}images/twitter.png" alt="" /></a> 
                <a href="https://www.pinterest.com/MaxAroma/"><img src="{{ config('global.SITE_URL') }}images/pinterest.png" alt="" /></a> 
                <a href="https://www.instagram.com/maxaroma/"><img src="{{ config('global.SITE_URL') }}images/instagram.png" alt="" /></a></p> 
         </td>
      </tr>
   </tbody>
</table>