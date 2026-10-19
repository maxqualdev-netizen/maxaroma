@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="pageTitle">
    <section class="breadcrumb">
        <div class="container">
            <a href="#">Home</a>
            <span class="active">Contact Us</span>
        </div>
    </section>
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="https://www.maxaroma.com" aria-label="Home">Home</a>
            <span class="active" aria-current="page">Contact Us</span>
        </div>
    </section>

    <!-- Content Part Start Here -->
    <div class="container">
        <div class="static-inner contact-us">
            <div class="row">
                <div class="col-12">
                    <div class="mainhd">
                        <h1 id="pageTitle">Contact Us</h1>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="float-left w-100 pr-3 border-right cont-main">
                        {!! $Content !!}
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    @if (Session::has('success'))
                        <div class="col-md-12 pb-4 text-center" role="status" aria-live="polite" aria-atomic="true">
                            <small class="pt-1 d-block frmsuccess"><strong>{{ Session::get('success') }}</strong></small>
                        </div>
                    @endif
                    <div class="col-md-12 pb-4 text-center d-none" id="ThankyouMessage" role="status" aria-live="polite" aria-atomic="true">
                        <small class="pt-1 d-block frmsuccess"><strong>Your Email has been send successfully</strong></small>
                    </div>
                    <MET A HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8"></MET>
                    <form action="" method="post" id="frmContactUs" aria-label="Contact us form">
                        <input type="hidden" name="action" value="submit" />
                        <!-- <input type=hidden name="orgid" value="00D6g000005gu5P">
                        <input type=hidden name="retURL" value="https://www.maxaroma.com/contact-us.html"> -->
                        <!-- <input type=hidden name="Origin" value="Web"> -->
                        <!-- <input type="submit" name="submit" value="submit" /> -->
                        <!-- <input type="hidden" name="debugEmail" value="qualdev.devs@gmail.com"> -->
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="pb-3 col-12 text-right fw500 f14"><span class="errmsg" aria-hidden="false">*</span>Required Fields</div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">Your Name<span class="errmsg" aria-hidden="false">*</span></label>
                                    <input id="name" name="name" type="text" placeholder="Your Name" class="form-control" autofocus="" value="{{ old('name') }}" required aria-required="true" aria-describedby="error_name">
                                    <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_name']" />
                                    @if ($errors->has('name'))
                                        <x-message :attr="[ 'classname' => 'frmerror', 'message' => $errors->first('name')]" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Email Address<span class="errmsg" aria-hidden="false">*</span></label>
                                    <input id="email" type="email" placeholder="Email Address" class="form-control" name="email" value="{{ old('email') }}" required aria-required="true" aria-describedby="error_email">
                                    <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email']" />
                                    @if ($errors->has('email'))
                                        <x-message :attr="[ 'classname' => 'frmerror', 'message' => $errors->first('email')]" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="subject" class="col-form-label">Subject<span class="errmsg" aria-hidden="false">*</span></label>
                                    <select name="subject" id="subject" class="form-control" required aria-required="true" aria-describedby="error_subject">
                                        <option value="" aria-label="Please select a subject">---select---</option>
                                        <option value="Cancellation Request">Cancellation Request</option>
                                        <option value="Status Of Shipment">Status Of My Order</option>
                                        <option value="Shipping fees">Shipping Fees</option>
                                        <option value="How to return my Item?">How To Return My Item?</option>
                                        <option value="Wrong/Damaged Item">Wrong/Damaged Item</option>
                                        <option value="I have not received My Order">I Have Not Received My Order</option>
                                        <option value="Offer/ Promotions">What Offers/Promotions Are Available Now?</option>
                                        <option value="others">Others</option>
                                    </select>
                                    <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_subject']" />
                                    @if ($errors->has('subject'))
                                        <x-message :attr="[ 'classname' => 'frmerror', 'message' => $errors->first('subject')]" />
                                    @endif
                                </div>
                            </div>
                            <select hidden id="00N6g00000VJm87" name="00N6g00000VJm87" title="Automated Response Type" aria-hidden="true"> </select>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="00N6g00000SdQCZ" class="col-form-label">Order Number<span id="orderreq" class="errmsg d-none" aria-hidden="true">*</span></label>
                                    <input id="00N6g00000SdQCZ" type="text" placeholder="Order Number" class="form-control" name="00N6g00000SdQCZ" value="{{ old('00N6g00000SdQCZ') }}" aria-describedby="error_00N6g00000SdQCZ">
                                    <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_00N6g00000SdQCZ']" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">Questions<span id="otherreq" class="errmsg d-none" aria-hidden="true">*</span></label>
                                    <textarea id="description" rows="4" cols="50" placeholder="Questions" class="form-control" name="description" aria-describedby="error_description">{{ old('description') }}</textarea>
                                    <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_description']" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group required" role="group" aria-labelledby="captcha-label">
                                    <span id="captcha-label" class="d-none">reCAPTCHA verification required</span>
                                    @include('googlecaptcha')
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-actions clearfix pt-0">
                                    <div class="float-right">
                                        <input type="hidden" id="edesk_sub" value="0">
                                        <input type="hidden" id="qstr" value="{{$qstr}}">
                                        <x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnContactUsReset" aria-label="Reset contact form" />
                                        <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnContactUs" onsubmit="return validateForm()" aria-label="Submit contact form" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="home_lets">
                        <h3>Let's be friends</h3>
                        <p><strong>Follow Us: <a target="_blank" href="https://www.instagram.com/maxaromaofficial/" aria-label="Follow Maxaroma on Instagram (opens in new window)">@Maxaroma</a></strong></p>
                        <p><strong>Share Your Scents: <a target="_blank" href="https://www.instagram.com/maxaromaofficial/" aria-label="Share with Maxaroma hashtag on Instagram (opens in new window)">#Maxaroma</a></strong></p>
                        <p>We obsessively watch those hashtags, share a picture &amp; you might be featured :)</p>
                    </div>
                    <div class="contact-frame">
                        <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" title="Google Maps showing our location at 31-17 38th Ave, Long Island City, NY 11101, USA" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.3807549476637!2d-73.93344028494103!3d40.75364957932731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25f2c1426ce29%3A0x7bbfc35d1cbb2ea5!2s31-17%2038th%20Ave%2C%20Long%20Island%20City%2C%20NY%2011101%2C%20USA!5e0!3m2!1sen!2sin!4v1595478297611!5m2!1sen!2sin"></iframe>
                        <br>
                        <small>
                            <a href="https://www.google.com/maps/place/31-17+38th+Ave,+Long+Island+City,+NY+11101,+USA/@40.75365,-73.931252,16z/data=!4m5!3m4!1s0x89c25f2c1426ce29:0x7bbfc35d1cbb2ea5!8m2!3d40.7536496!4d-73.9312516?hl=en" target="_blank" aria-label="View Maxaroma location on Google Maps in a new window">View Larger Map</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Part End Here -->
</main>
    <script type="text/javascript">
        var select = document.getElementById('subject');
        var otherreq = document.getElementById("otherreq");
        var ordreq = document.getElementById("orderreq");
        select.onchange = function() {
            if (this.value == 'Status Of Shipment') {
                $('#00N6g00000SdQCZ').attr('required', true);
                ordreq.classList.remove("d-none");
            } else {
                ordreq.classList.add("d-none");
                $('#00N6g00000SdQCZ').attr('required', false);
            }
            if (this.value == 'others') {
                $('#description').attr('required', true);
                otherreq.classList.remove("d-none");

            } else {
                otherreq.classList.add("d-none");
                $('#description').attr('required', false);
            }
        };

        var from = document.referrer;
        if (from == "https://webto.salesforce.com/") {
            if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
                var thankyou = document.getElementById("ThankyouMessage");
                thankyou.classList.add("d-none");
            } else {
                var thankyou = document.getElementById("ThankyouMessage");
                thankyou.classList.remove("d-none");
            }
        }
    </script>
@endsection