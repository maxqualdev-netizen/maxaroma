@php
$freeshippinginfo = '';
if(config('Settings.FREESHIPPING_VALUE')!="")
{
	$freeshippinginfo .= '<span style="font-size:16px; font-family:Arial;"><strong>FREE</strong> Shipping On $'.config('Settings.FREESHIPPING_VALUE').' or more Orders</span>';
}
@endphp
<table style="margin: 0px auto; width: 100%; padding: 20px; border: 1px solid #d1d1d1;" border="0" cellspacing="0" cellpadding="0">
   <tbody>
      <tr>
         <td style="padding-bottom:10px;border-bottom:1px solid #e8e8e8;">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                  <tr>
                     <td width="50%">Welcome to <a style="color:#666666;" href="{{ config('global.SITE_URL') }}">MaxAroma.com</a></td>
                     <td width="50%" align="right"></td>
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
                     <td width="50%" align="center"><a href= "{{ config('global.SITE_URL') }}"><img src="{{ config('global.SITE_URL') }}images/logo.png" alt="" /></a></td>
                     <td width="50%" align="center">{!! $freeshippinginfo !!}</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td>
            <table style="background-color: #2d2d2d; width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                  <tr align="center">
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block; text-transform:uppercase;" href="{{ config('global.SITE_URL') }}fragrances/cid/1">Fragrances</a></td>
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}skincare/cid/18">Skincare</a></td>
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}makeup/cid/30">Makeup</a></td>
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}bath-body/cid/12">Bath &amp; Body</a></td>
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}at-home/cid/15">At Home</a></td>
                     <td style="border-right:1px solid #e8e8e8;"><a style="color:#fff; text-decoration:none; padding:8px 0px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}sunglasses/cid/68">Sunglasses</a></td>
                     <td><a style="color:#fff; text-decoration:none; padding:5px; display:block;text-transform:uppercase;" href="{{ config('global.SITE_URL') }}perfumesale/p4u/special-sl/view">Sale</a></td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td style=" border:1px solid #e8e8e8;">
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                  <tr>
                     <td style="border-bottom:1px solid #e8e8e8; padding:0px 10px;" colspan="2">
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
         <td style="background-color:#e5e5e5; padding:10px; text-align:center;">
            <p><a href="https://www.facebook.com/yousmellawesome/"><img src="{{ config('global.SITE_URL') }}images/facebook.png" alt="" /></a> <a href="https://twitter.com/Max_Aroma"><img src="{{ config('global.SITE_URL') }}images/twitter.png" alt="" /></a> <a href="https://www.pinterest.com/MaxAroma/"><img src="{{ config('global.SITE_URL') }}images/pinterest.png" alt="" /></a> <a href="https://www.instagram.com/maxaromaofficial/"><img src="{{ config('global.SITE_URL') }}images/instagram.png" alt="" /></a></p>
            <p style="color:#cc3399; margin-bottom:5px;"><a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}myaccount.html">My Account</a> &middot;  					 				<a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/about_us.html">About Us</a> &middot;  					 				<a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}contact-us.html">Contact Us</a></p>
            <p style="color:#cc3399;margin-top:5px; margin-bottom:25px;"><a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/shipping_policy.html">Shipping Policy</a> &middot;  					 				<a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/privacy_policy.html">Privacy Policy</a> &middot;  					 				<a style="color:#cc3399; text-decoration:none;" href="{{ config('global.SITE_URL') }}site-page/authenticity_promise.html">Authenticity Promise</a></p>
            <p style="margin-bottom:25px;">Please add <a href='mailto:"{{config('Settings.CONTACT_MAIL')}}>"{{ config('Settings.CONTACT_MAIL') }}"</a> to your address book to ensure our emails reach your inbox!<br /> If you'd rather not receive emails, simply <span style='color:#cc3399;'>manage your subscriptions.</span> We'll miss you terribly, though.</p>
            <p style='margin-bottom:25px;'>&copy; 2010 - 2015 MaxAroma.com, LLC. or its affiliates<br /> 1122 44th Rd, LIC ,NY,11101</p>
            <p>Products on MaxAroma.com are sold by MaxAroma.com, LLC.</p>
         </td>
      </tr>
   </tbody>
</table>