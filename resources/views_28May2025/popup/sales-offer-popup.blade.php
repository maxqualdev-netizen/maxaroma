<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="sales-offers-modal">
                    <div class="modal-hd">
                        <h1>Sales & Offers</h1>
                    </div>
                    <div class="modal-space">
                        <form>
                            <div class="login">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($sectionOne)
                                        <div class="so-hot-list">
                                            <div class="row">
                                                @foreach($sectionOne as $key => $value)
                                                <div class="col-md-6">
                                                    <div class="so-div color-red">
                                                        <h4>{{$value['offer_title']}}</h4>
                                                        <div class="so-link"><a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}">{{$value['offer_link_text']}}</a> | Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        @if ($sectionTwo)
                                        <div class="so-nom-list">
                                            <div class="row">
                                                @foreach($sectionTwo as $key => $value)
                                                <div class="col-md-12">
                                                    <div class="so-div">
                                                        <h4>{{$value['offer_title']}}</h4>
                                                        <div class="so-link"><a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link'])}}">{{$value['offer_link_text']}}</a> | Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}
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