@if(isset($BrandProducts) && count($BrandProducts)>0)
    <section id="other-brand-scents" class="the-brand-sec brandpage-space bg-white" role="region" aria-labelledby="brand-scents-heading">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 id="brand-scents-heading">THE OTHER SCENTS OF THE BRAND</h2>
                    <div class="brand-ex-list">
                        <div class="brand-slider">
                            <div class="brand-page-listul prd-grid-4 brandProducts-sl" data-slider="scent-itemslider">
                                @foreach($BrandProducts as $key => $BrandProduct)
                                    @include('brand.maxaroma-brand-productbox', ['Product' => $BrandProduct])
                                @endforeach
                                {{--
                                <div class="brand-page-list">
                                    <div class="product product-brand"  productname="OJAR Unisex Eau De Parfum" brand="OJAR"  aria-label="OJAR Unisex Eau De Parfum">
                                        <a href="http://27.109.8.106:8253/83/maxaroma/fragrance/unisex/ojar-kashmir-print/pid/33234/4" class="p-thumb" aria-label="View product OJAR Unisex Eau De Parfum">
                                            <img src="http://27.109.8.106:8253/83/maxaroma/images/noimage-lrg.jpg" loading="lazy" alt="OJAR fragrance bottle" width="185" height="185">
                                        </a>
                                        <div class="product-name">
                                            <a href="http://27.109.8.106:8253/83/maxaroma/ojar/smid-1001" tabindex="0"><strong><br>OJAR</strong></a> Unisex
                                            Eau De Parfum
                                        </div>

                                        <div class="minheightprodbox">
                                            <div class="product-price">
                                                <div class="price pb-1 opstion-avl">
                                                </div>
                                            </div>
                                            <div class="pb-2 product-price">
                                                <div class="price">
                                                    $30 - $230
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-action pt-4">
                                            <button type="button" class="btn btn-secondary" aria-label="Add OJAR Unisex Eau De Parfum to cart">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="brand-page-list">
                                    <div class="product product-brand"  productname="OJAR Unisex Eau De Parfum" brand="OJAR"  aria-label="OJAR Unisex Eau De Parfum">
                                        <a href="http://27.109.8.106:8253/83/maxaroma/fragrance/unisex/ojar-kashmir-print/pid/33234/4" class="p-thumb" aria-label="View product OJAR Unisex Eau De Parfum">
                                            <img src="http://27.109.8.106:8253/83/maxaroma/images/noimage-lrg.jpg" loading="lazy" alt="OJAR fragrance bottle" width="185" height="185">
                                        </a>
                                        <div class="product-name">
                                            <a href="http://27.109.8.106:8253/83/maxaroma/ojar/smid-1001" tabindex="0"><strong><br>OJAR</strong></a> Unisex
                                            Eau De Parfum
                                        </div>

                                        <div class="minheightprodbox">
                                            <div class="product-price">
                                                <div class="price pb-1 opstion-avl">
                                                </div>
                                            </div>
                                            <div class="pb-2 product-price">
                                                <div class="price">
                                                    $30 - $230
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-action pt-4">
                                            <button type="button" class="btn btn-secondary" aria-label="Add OJAR Unisex Eau De Parfum to cart">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="brand-page-list">
                                    <div class="product product-brand"  productname="OJAR Unisex Eau De Parfum" brand="OJAR"  aria-label="OJAR Unisex Eau De Parfum">
                                        <a href="http://27.109.8.106:8253/83/maxaroma/fragrance/unisex/ojar-kashmir-print/pid/33234/4" class="p-thumb" aria-label="View product OJAR Unisex Eau De Parfum">
                                            <img src="http://27.109.8.106:8253/83/maxaroma/images/noimage-lrg.jpg" loading="lazy" alt="OJAR fragrance bottle" width="185" height="185">
                                        </a>
                                        <div class="product-name">
                                            <a href="http://27.109.8.106:8253/83/maxaroma/ojar/smid-1001" tabindex="0"><strong><br>OJAR</strong></a> Unisex
                                            Eau De Parfum
                                        </div>

                                        <div class="minheightprodbox">
                                            <div class="product-price">
                                                <div class="price pb-1 opstion-avl">
                                                </div>
                                            </div>
                                            <div class="pb-2 product-price">
                                                <div class="price">
                                                    $30 - $230
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-action pt-4">
                                            <button type="button" class="btn btn-secondary" aria-label="Add OJAR Unisex Eau De Parfum to cart">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="brand-page-list">
                                    <div class="product product-brand"  productname="OJAR Unisex Eau De Parfum" brand="OJAR"  aria-label="OJAR Unisex Eau De Parfum">
                                        <a href="http://27.109.8.106:8253/83/maxaroma/fragrance/unisex/ojar-kashmir-print/pid/33234/4" class="p-thumb" aria-label="View product OJAR Unisex Eau De Parfum">
                                            <img src="http://27.109.8.106:8253/83/maxaroma/images/noimage-lrg.jpg" loading="lazy" alt="OJAR fragrance bottle" width="185" height="185">
                                        </a>
                                        <div class="product-name">
                                            <a href="http://27.109.8.106:8253/83/maxaroma/ojar/smid-1001" tabindex="0"><strong><br>OJAR</strong></a> Unisex
                                            Eau De Parfum
                                        </div>

                                        <div class="minheightprodbox">
                                            <div class="product-price">
                                                <div class="price pb-1 opstion-avl">
                                                </div>
                                            </div>
                                            <div class="pb-2 product-price">
                                                <div class="price">
                                                    $30 - $230
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-action pt-4">
                                            <button type="button" class="btn btn-secondary" aria-label="Add OJAR Unisex Eau De Parfum to cart">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                                 --}}
                            </div>
                        </div>
                        <div class="brand-slider-progress" aria-hidden="true">
                            <div class="bar" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif