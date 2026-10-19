@extends('layouts.app')
@section('content')

<main class="pos-page" role="main" aria-label="POS Page">
	<section class="breadcrumb" aria-label="Breadcrumb">
		<div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
			<a href="{{url('/')}}" aria-label="Home">Home</a>
			<span class="active" aria-current="page">POS</span>
		</div>
	</section>
	<!-- New Order Page Content Page Start Here -->
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function() {
			const tabLinks = document.querySelectorAll('#tabOptions .nav-link');
			const tabPanes = document.querySelectorAll('#tabContent .tab-pane');

			tabLinks.forEach(link => {
				link.addEventListener('click', function(event) {
					event.preventDefault();

					// Remove 'active' from all tabs and tab panes
					tabLinks.forEach(link => link.classList.remove('active'));
					tabPanes.forEach(pane => {
						pane.classList.remove('show', 'active');
					});

					// Activate clicked tab
					this.classList.add('active');

					// Get the associated pane via href
					const targetId = this.getAttribute('href');
					const targetPane = document.querySelector(targetId);
					if (targetPane) {
						targetPane.classList.add('show', 'active');
					}
				});
			});
		});
	</script>
	<div class="myact pb-0 pt-0">
		<div class="container pt-3 pb-md-5 pb-3 pos-new-order-main" role="region" aria-labelledby="pos-new-order-title">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
					@include('pos.leftmenu')
				</div>

				<div class="col-lg-9 col-md-12 col-xs-12  pt-3 pt-lg-0">
					<div class="myact_hd">
						<svg class="sv_details" aria-hidden="true" role="img">
							<use href="#sv_details" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_details"></use>
						</svg>
						<h2>My Details</h2>
					</div>
					<div class="p-2">
						<form action="" method="post" id="frmEditProfile">
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="eusertype" id="eusertype" value="{{ Session::get('eusertype') }}" />
							@csrf
							@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess',
										'message' => Session::get('success')]" />
							@endif
							<div class="row">
								<div class="col-md-6">
									<div class="row row10">
										<div class="col-sm-6 pt-3">
											<div class="field required">
												<label for="first_name">First Name</label>
												<input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name', $Userdata->first_name ?? '') }}" />
												<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_first_name']" />
											</div>
										</div>
										<div class="col-sm-6 pt-3">
											<div class="field required">
												<label for="last_name">Last Name</label>
												<input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name', $Userdata->last_name ?? '') }}" />
												<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_last_name']" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 pt-3">
									<div class="field required">
										<label for="store_user_email">Email Address</label>
										<input type="store_user_email" id="store_user_email" name="store_user_email" placeholder="Email Address" class="form-control" value="{{ old('store_user_email', $Userdata->store_user_email ?? '') }}" readonly="readonly" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 pt-3">
									<div class="field">
										<label for="store_user_phone">Phone</label>
										<input type="text" id="store_user_phone" name="store_user_phone" placeholder="phone" class="form-control" value="{{ old('store_user_phone', $Userdata->phone ?? '') }}" />
										<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_phone']" />
									</div>
								</div>
							</div>
							<div class="text-right pt-3">
								<x-button link="javascript:void(0);" btntext="Save Changes" classname="btn btn-primary" bid="btnEditProfile" />
							</div>
						</form>
					</div>
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group"
						aria-label="Navigation and Actions">
						<a href="{{config('global.SITE_URL')}}store/store-dashboard.html" class="back-link" aria-label="Back to POS Home">
							<i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Dashboard
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection