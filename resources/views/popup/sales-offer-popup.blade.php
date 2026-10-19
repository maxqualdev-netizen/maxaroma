<div class="vertical-alignment-helper" role="presentation">
    <div class="modal-dialog modal-md vertical-align-center" role="dialog" aria-modal="true" aria-labelledby="salesOffersModalTitle">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close popup">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="sales-offers-modal">
                    <div class="modal-hd" id="salesOffersModalTitle">
                        <h1>Sales & Offers</h1>
                    </div>
                    <div class="modal-space">
                        <form aria-labelledby="salesOffersModalTitle">
                            <div class="login">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($sectionOne)
                                        <div class="so-hot-list" aria-label="Hot Sales Offers">
                                            <div class="row">
                                                @foreach($sectionOne as $key => $value)
                                                <div class="col-md-6">
                                                    <div class="so-div color-red" role="region" aria-label="Offer: {{$value['offer_title']}}">
                                                        <h4>{{$value['offer_title']}}</h4>
                                                        <div class="so-link">
                                                            <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}" aria-label="{{$value['offer_link_text']}}">{{ $value['offer_link_text'] }}</a>
                                                            | Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        @if ($sectionTwo)
                                        <div class="so-nom-list" aria-label="Normal Sales Offers">
                                            <div class="row">
                                                @foreach($sectionTwo as $key => $value)
                                                <div class="col-md-12">
                                                    <div class="so-div" role="region" aria-label="Offer: {{$value['offer_title']}}">
                                                        <h4>{{$value['offer_title']}}</h4>
                                                        <div class="so-link">
                                                            <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link'])}}" aria-label="{{$value['offer_link_text']}}">{{ $value['offer_link_text'] }}</a>
                                                            | Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>