@extends('layouts.app')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	

				<div class="col-lg-9 col-md-12 col-xs-12">
				   <div class="myact_hd helpv-hd">
				      <svg class="sv_helpvideo" aria-hidden="true" role="img">
				         <use href="#sv_helpvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_helpvideo"></use>
				      </svg>
				      <h2>Help Videos</h2>
				   </div>
				   <div class="edit_ftp_box help-video">
				      	<div class="row">
							@if(generalsetting("DROPSHIPPER_VIDEO1") != '' && generalsetting("DROPSHIPPER_VIDEO_TITLE1") != '')
							<div class="col-md-6 col-xs-6 pt-4 pb-4">
								<iframe width="100%" height="400" frameBorder="0" src="{{generalsetting("DROPSHIPPER_VIDEO1")}}"></iframe>
								<h3 class="text-center pt-2 mt-1">{{generalsetting("DROPSHIPPER_VIDEO_TITLE1")}}</h3>								
							</div>
							@endif
							@if(generalsetting("DROPSHIPPER_VIDEO2") != '' && generalsetting("DROPSHIPPER_VIDEO_TITLE2") != '')
							<div class="col-md-6 col-xs-6 pt-4 pb-4">								
								<iframe width="100%" height="400" frameBorder="0" src="{{generalsetting("DROPSHIPPER_VIDEO2")}}"></iframe>								
								<h3 class="text-center pt-2 mt-1">{{generalsetting("DROPSHIPPER_VIDEO_TITLE2")}}</h3>																
							</div>
							@endif
							@if(generalsetting("DROPSHIPPER_VIDEO3") != '' && generalsetting("DROPSHIPPER_VIDEO_TITLE3") != '')
							<div class="col-md-6 col-xs-6 pt-4 pb-4">								
								<iframe width="100%" height="400" frameBorder="0" src="{{generalsetting("DROPSHIPPER_VIDEO3")}}"></iframe>									
								<h3 class="text-center pt-2 mt-1">{{generalsetting("DROPSHIPPER_VIDEO_TITLE3")}}</h3>								
							</div>
							@endif
							@if(generalsetting("DROPSHIPPER_VIDEO4") != '' && generalsetting("DROPSHIPPER_VIDEO_TITLE4") != '')
							<div class="col-md-6 col-xs-6 pt-4 pb-4">								
								<iframe width="100%" height="400" frameBorder="0" src="{{generalsetting("DROPSHIPPER_VIDEO4")}}"></iframe>
								<h3 class="text-center pt-2 mt-1">{{generalsetting("DROPSHIPPER_VIDEO_TITLE4")}}</h3>								
							</div>
							@endif
						</div>												      
				   </div>
				</div>


			</div>
		</div>
	</div>
</main>	

@endsection
