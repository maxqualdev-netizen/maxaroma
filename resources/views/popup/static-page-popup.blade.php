<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="static-page-title" aria-describedby="static-page-desc">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="static-page-desc">
                <div class="sales-offers-modal">
                    <div class="modal-hd">
                        <h1 id="static-page-title">{{$PageTitle}}</h1>
                    </div>
                    <div class="modal-space">
                        <div class="row" role="region" aria-labelledby="static-page-title">
                            <div class="col-md-12">
                                <div class="static-pop-up-content">
                                    {!! $PageContent !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>