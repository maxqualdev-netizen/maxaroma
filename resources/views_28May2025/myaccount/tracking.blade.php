@extends('layouts.app')
@section('content')
<form name="trackorder" method="post" action = "https://wwwapps.ups.com/etracking/tracking.cgi">
  <input type="hidden" size=35 name="InquiryNumber1" value="{{ $tracking_no }}">
  <input type="hidden" name="TypeOfInquiryNumber" value="T">
  <input type="hidden" name="UPS_HTML_License" value="abcd">
  <input type="hidden" name="UPS_HTML_Version" value="3.0">
  <input type="hidden" name="IATA" value = "us">
  <input type="hidden" name="Lang" value = "eng">
  <input type="image" src="{{config('global.SITE_URL')images/spacer.gif" name="submit" value ="">
  <input type="hidden" name="nonUPS_body" value="BGCOLOR=#FFFFFF">
  <input type="hidden" name="nonUPS_title" value="Beverly Diamonds UPS Package Tracking">
  <input type="hidden" name="nonUPS_header" value="<img src='{{config('global.SITE_URL')images/logo.png'>">
  <input name="nonUPS_footer" type="hidden" value="<table width='600' border='0' cellspacing='0' cellpadding='0'><tr><td align='right'><a href='#' onclick='window.close();'>Close</a></td></tr></table>">
</form>
<script language="javascript">
	document.trackorder.submit();
</script>
@endsection
