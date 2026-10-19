@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" media="all" href="{{config('global.SITE_STYLE')}}myaccount.css" />
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
				<div class="col-lg-9 col-md-12 col-xs-12">

					<div class="myact_hd"> <svg class="sv_dpp_list" aria-hidden="true" role="img">
					<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
					</svg><h2>Download Product Price List</h2></div>
					<div class="dp_pricelist">
                <div class="title">Please click on the following links to view our updated Wholesale Special's Price List in .xls and .pdf format and our company profile.</div>
                <div class="dp_pricelist_box">
					 <table>
					  @if($complete_wholesale_export_file_name_xls != "")
						  <tr>
							   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
								<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
								</svg></a></td>
							   <td>Complete Wholesale Price List In Excel</td>
							   <td class="text-right">
								<a href="{{$complete_wholesale_export_file_name_xls}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
								<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
								</svg><span class="d-none d-sm-inline-block">Download</span></a>
							   </td>
						  </tr>
					  @endif
					  
					  @if($complete_giftset_export_file_name_xls != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Gift Set Price List In Excel</td>
						   <td class="text-right">
							<a href="{{$complete_giftset_export_file_name_xls}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($complete_tester_export_file_name_xls != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Tester Price List In Excel</td>
						   <td class="text-right">
							<a href="{{$complete_tester_export_file_name_xls}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($complete_newarrival_export_file_name_xls != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>New Arrival Price List In Excel</td>
						   <td class="text-right">
							<a href="{{$complete_newarrival_export_file_name_xls}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($sunglasses_wholesale_export_file_name_xls != "")
					  
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Sunglasses Price List In Excel</td>
						   <td class="text-right">
							<a href="{{$sunglasses_wholesale_export_file_name_xls}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					   
					   @if($complete_wholesale_export_file_name_pdf != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Complete Wholesale Price List In PDF</td>
						   <td class="text-right">
							<a href="{{$complete_wholesale_export_file_name_pdf}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($complete_giftset_export_file_name_pdf != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Gift set Price List In PDF</td>
						   <td class="text-right">
							<a href="{{$complete_giftset_export_file_name_pdf}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($complete_tester_export_file_name_pdf != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Tester Price List In PDF</td>
						   <td class="text-right">
							<a href="{{$complete_tester_export_file_name_pdf}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($complete_newarrival_export_file_name_pdf != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>New Arrival Price List In PDF</td>
						   <td class="text-right">
							<a href="{{$complete_newarrival_export_file_name_pdf}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					  
					  @if($sunglasses_wholesale_export_file_name_pdf != "")
					  <tr>
						   <td width="5%"><a href="#"><svg class="sv_group" aria-hidden="true" role="img">
							<use href="#sv_group" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_group"></use>
							</svg></a></td>
						   <td>Sunglasses Price List In PDF</td>
						   <td class="text-right">
							<a href="{{$sunglasses_wholesale_export_file_name_pdf}}" class="btn_dowload"><svg class="sv_downarrow" aria-hidden="true" role="img">
							<use href="#sv_downarrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_downarrow"></use>
							</svg><span class="d-none d-sm-inline-block">Download</span></a>
						   </td>
					  </tr>
					   @endif
					 </table>
                </div>
               </div>
           
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
