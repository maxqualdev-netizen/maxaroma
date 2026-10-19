@include('layouts.popups')
<header>
	<div id="header-sticky-anchor"></div>
	<div id="header-sticky">
		<div class="header-sec ">
			<div class="container">
				<div class="d-flex flex-wrap justify-content-between position-relative">
					<div class="login-header d-flex">
						<div class="header-left d-flex">
							<div class="header-contact d-flex ml-auto">
								<ul class="">
									<li>
										<a href="tel:844-552-7662">
											<svg class="svg-whatsapp vam" aria-hidden="true" role="img" width="23" height="23">
												<use href="#svg-whatsapp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-whatsapp"></use>
											</svg>
											<span>844-552-7662</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="header-logo">
						<a href="{{config('global.SITE_URL')}}">
							<img src="{{config('global.SITE_IMAGES')}}logo.jpg" alt="">
						</a>
					</div>
					<div class="h-user-icon">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</header>