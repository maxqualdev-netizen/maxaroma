<div class="container">
    <div class="fragrance-notes-bottom justify-content-center d-md-none pt-0 pb-5"><span class="m-0">{{ $productDetails->gender }}</span></div>
    <div class="fragrance-notes">
        <h2 id="fragrance-heading" class="visually-hidden">Fragrance Notes</h2>
        <div class="note note-top">
            <div class="note-text d-flex align-items-center justify-content-end text-right">
                <div class="note-textinr">
                    <span class="note-label" aria-label="Top notes">TOP</span>
                    @foreach($productDetails->topnote as $topnote)
                    <p>{{remove_html_entities($topnote)}}</p>
                    @endforeach
                </div>
            </div>
            <div class="image-placeholder">
                <img src="{{$productDetails->sec1_image1}}" alt="Top notes visual: Aldehydes, Amber Wood, Australian Sandalwood" class="brands-image" loading="eager" decoding="async" width="340" height="420">
            </div>
        </div>

        <div class="note note-middle">
            <div class="note-text d-flex align-items-md-end align-items-center justify-content-md-end text-md-right">
                <div class="note-textinr">
                    <span class="note-label" aria-label="Middle notes">MIDDLE</span>
                    @foreach($productDetails->middlenote as $middlenote)
                    <p>{{remove_html_entities($middlenote)}}</p>
                    @endforeach
                </div>
            </div>
            <div class="image-placeholder"><img src="{{$productDetails->sec1_image2}}" alt="Middle notes visual: Orris, Musk" class="brands-image" loading="eager" decoding="async" width="270" height="480"></div>

        </div>

        <div class="note note-base">
            <div class="image-placeholder"><img src="{{$productDetails->sec1_image3}}" alt="Base notes visual: Peppermint, Spearmint, Virginia Cedar" class="brands-image" loading="eager" decoding="async" width="320" height="430"></div>
            <div class="note-text d-flex align-items-md-center align-items-end pb-md-0 pb-4 justify-content-start">
                <div class="note-textinr">
                    <span class="note-label" aria-label="Base notes">BASE</span>
                    @foreach($productDetails->basenote as $basenote)
                    <p>{{remove_html_entities($basenote)}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="fragrance-notes-bottom justify-content-center">designer: <strong>{{ remove_html_entities($productDetails->vmanufacture) }}</strong> <span class="d-none d-md-block">{{ $productDetails->gender }}</span></div>
</div>