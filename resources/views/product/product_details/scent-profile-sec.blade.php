<div class="container">
    <div class="scent-profile">
        <h2 id="scent-heading" class="visually-hidden">
            What you'll actually smell
        </h2>

        <div class="image-placeholder">
            <picture>
                <source
                    media="(max-width: 767px)"
                    srcset="{{$productDetails->sec2_mobile_banner}}"
                >

                <img
                    src="{{$productDetails->sec2_banner}}"
                    alt="Fragrance mood visual: What you'll actually smell"
                    class="brands-image"
                    loading="lazy"
                    width="760"
                    height="350"
                >
            </picture>
        </div>

        <div class="scent-content">
            <p class="title">
            <strong>What you'll actually smell:</strong>
            </p>

            <div class="scent-description">
                    <ul class="d-flex gap-2">
                    @if(!empty($productDetails->opening))
                    <li><label>opening:</label> {{ remove_html_entities($productDetails->opening) }}</li>
                    @endif
                    @if(!empty($productDetails->heart))
                    <li><label>heart:</label> {{ remove_html_entities($productDetails->heart) }}</li>
                    @endif
                    @if(!empty($productDetails->drydown))
                    <li><label>drydown:</label> {{ remove_html_entities($productDetails->drydown) }}</li>
                    @endif
                    </ul>
            </div>
            @if(!empty($productDetails->longevity))
            <p class="longevity">
                <strong>Longevity: {{ remove_html_entities($productDetails->longevity) }}</strong>
            </p>
            @endif
            @if(!empty($productDetails->fragrance_seasons))
            <p class="season">
                <span>SEASON:&nbsp;</span> <strong>{{ strtoupper(remove_html_entities($productDetails->fragrance_seasons)) }}</strong>
            </p>
            @endif
        </div>
    </div>
</div>