<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="address-validate-title" aria-describedby="address-validate-desc">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        @if($missingComponentsAddress != "")
                        <h1 id="address-validate-title">Missing Address Field(s)</h1>
                        @elseif($cntUnConfirmedComponentTypes != "0")
                        <h1 id="address-validate-title">Unable to verify your address</h1>
                        @else
                        <h1 id="address-validate-title">SUGGESTED ADDRESS</h1>
                        @endif	
                    </div>

                    <div class="container" id="address-validate-desc">
                        <div class="row modal-space">
                            @if($cntUnConfirmedComponentTypes != "0")
                                <div class="col-md-12" role="alert" aria-live="polite">
                                    Unable to verify your address, Please review
                                </div>	
                            @elseif($missingComponentsAddress != "")
                                    
                            @else
                            <div class="col-md-12" role="status" aria-live="polite">
                                Use Recommended address instead?
                                <br><br>
                                We are unable to find your address, but found a close match.
                            </div>	
                            @endif
                            @if($missingComponentsAddress != "")
                            <div class="col-md-12" role="alert" aria-live="polite">
                                <br>
                                Missing Address Field(s):
                                <ul>
                                @foreach(explode(',', $missingComponentsAddress) as $missing) 
                                    @if(strtolower($missing) == 'route')
                                        @php
                                            $missing = 'Street Name';
                                        @endphp
                                    @elseif(strtolower($missing) == 'building')
                                        @php
                                            $missing = 'Missing Building Number';
                                        @endphp	
                                    @elseif(strtolower($missing) == 'street number')
                                        @php
                                            $missing = 'Missing Street Number';
                                        @endphp
                                    @elseif(strtolower($missing) == 'zip code')
                                        @php
                                            $missing = 'Wrong Zip Code';
                                        @endphp			
                                    @elseif(strtolower($missing) == 'apartment')
                                        @php
                                            $missing = 'Missing Apartment Number';
                                        @endphp	
                                    @endif
                                    <li><span style="color:red;">* {{$missing}}</span></li>
                                @endforeach
                                </ul>								
                            </div>	
                            @endif
                            
                            @if($unConfirmedComponentsAddress != "")
                            <div class="col-md-12" role="alert" aria-live="polite">
                                <br>
                                Incorrect Address Field(s):
                                <ul>
                                    @foreach(explode(",",$unConfirmedComponentsAddress) as $unconfirm)
                                        @if(strtolower($unconfirm) == 'route')
                                            @php
                                                $unconfirm = 'Street Name';
                                            @endphp
                                        @elseif(strtolower($unconfirm) == 'building')
                                            @php
                                                $unconfirm = 'Missing Building Number';
                                            @endphp
                                        @elseif(strtolower($unconfirm) == 'street number')
                                            @php
                                                $unconfirm = 'Missing Street Number';
                                            @endphp
                                        @elseif(strtolower($unconfirm) == 'zip code')
                                            @php
                                                $unconfirm = 'Wrong Zip Code';
                                            @endphp
                                        @elseif(strtolower($unconfirm) == 'apartment')
                                            @php
                                                $unconfirm = 'Missing Apartment Number';
                                            @endphp	
                                        @endif
                                        <li><span style="color:red;">* {{$unconfirm}}</span></li>
                                    @endforeach
                                </ul>
                            </div>							
                            @endif
                        </div>
                        <div class="row row10 modal-space">
                            @if($cntUnConfirmedComponentTypes != "0")							
                            <div class="col-md-6">	
                                <label for="entered-address"><strong>You Entered :</strong></label><br>
                                <span id="entered-address">{{ $enteredAddress }}</span>
                            </div>
                            @else
                                @if($missingComponentsAddress != "")
                                <div class="col-md-6">
                                    <label for="recommended-address"><strong>Recommended : </strong></label><br>
                                    <span id="recommended-address">{{ $suggestedAddress }}</span>
                                </div>
                                <div class="col-md-6">	
                                    <label for="entered-address"><strong>You Entered :</strong></label><br>
                                    <span id="entered-address">{{ $enteredAddress }}</span>
                                </div>
                                @elseif(str_replace(",","",$suggestedAddress)  != str_replace(",","",$enteredAddress))
                                <div class="col-md-6">
                                    <label for="recommended-address"><strong>Recommended : </strong></label><br>
                                    <span id="recommended-address">{{ $suggestedAddress }}</span>
                                </div>	
                                <div class="col-md-6">	
                                    <label for="entered-address"><strong>You Entered :</strong></label><br>
                                    <span id="entered-address">{{ $enteredAddress }}</span>
                                </div>
                                @endif	
                            @endif
                        </div>
                        <div class="row row10 modal-space" style="border-top: 1px solid #ccc;">
                            @if($cntUnConfirmedComponentTypes != "0")
                                @if($ship_country != 'US')
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="reEnterAddress();" style="text-transform:none;" role="button" aria-label="Re-enter Address">Re-enter Address</a>
                                </div>								
                                <div class="col-md-4">	
                                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="useYourAddress();" role="button" aria-label="Use Existing Address">Use Existing Address</a>
                                </div>
                                @else
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="reEnterAddress();" style="text-transform:none;" role="button" aria-label="Re-enter Address">Re-enter Address</a>
                                </div>
                                <!-- <div class="col-md-4">	
                                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="useYourAddress();">Use Existing Address</a>
								</div> -->
                                @endif	
                            @elseif($missingComponentsAddress != "")
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="reEnterAddress();" style="text-transform:none;" role="button" aria-label="Re-enter Address">Re-enter Address</a>
                                </div>	
                                <div class="col-md-4">	
                                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="useYourAddress();" role="button" aria-label="Use Existing Address">Use Existing Address</a>
                                </div>
                            @else
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="useRecommendedAddress();" role="button" aria-label="Use Recommended Address">Use Recommended</a>
                                </div>
                                <div class="col-md-4">	
                                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="useYourAddress();" role="button" aria-label="Use Your Address">Use Your Address</a>
                                </div>
                            @endif	
                            <div class="col-md-2"></div>							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>