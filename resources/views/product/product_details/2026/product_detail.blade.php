@if ($productDetails->referencedName != '')
    <div role="region" aria-labelledby="product-description-heading">
        <h2 id="product-description-heading" class="sr-only">Product Description</h2>
        <p class="wetext" data-swiftype-name="Long Description" data-swiftype-type="text" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Product long description">{!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$productDetails->product_description)) !!}</p>
        @if (($productDetails->category_id == 68 || $productDetails->category_id == 69 || $productDetails->category_id == 70 || $productDetails->category_id == 71) && $productDetails->is_atomizer == 'Yes')
            <span aria-label="Disclaimer"><b>DISCLAIMER:</b></span>
            <p aria-label="Atomizer repackaging notice">Our travel atomizer spray are independently repackaged by <strong>Maxaroma.com</strong> - Long Island City, NY 11101</p>
            <p aria-label="Decanting notice"><strong>Maxaroma.com</strong> decants genuine fragrances into our Maxaroma travel spray atomizers.</p>
            <p aria-label="No designer association"><strong>Maxaroma.com</strong> or any associated entities, are not associated with the designer, or the designer's manufacturer in any way.</p>
        @endif
    </div>
    <div class="pt15 d-md-block" role="region" aria-labelledby="product-details-table-heading">
        <h2 id="product-details-table-heading" class="sr-only">Product Details Table</h2>
        <div class="pb15">
            <table class="table" role="table" aria-label="Product details">
                <tbody>
                    <tr>
                        <th scope="row">Item #:</th>
                        <td id="detail_prod_sku">{{ $productDetails->sku }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Brand:</th>
                        <td>
                            <a href="{{ $vmanufacture_link }}">
                                <u data-swiftype-name="Brand"
                                   data-swiftype-type="string"
                                   @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif
                                   aria-label="Brand: {{ $productDetails->vmanufacture }}"
                                >{{ $productDetails->vmanufacture }}</u>
                            </a>
                        </td>
                    </tr>

                    @if ($productDetails->gender != '')
                        <tr>
                            <th scope="row">Gender:</th>
                            <td>
                                <span data-swiftype-name="Gender" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Gender">
                                    @if ($productDetails->gender == 'M')
                                        Men
                                    @elseif($productDetails->gender=="W")
                                        Women
                                    @elseif($productDetails->gender=="K")
                                        Kids
                                    @elseif($productDetails->gender=="U")
                                        Unisex
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <th scope="row">Product Name:</th>
                        <td aria-label="Product Name">{{ stripslashes($productDetails->referencedName) }}</td>
                    </tr>

                    @if ($productDetails->skin_type != '')
                        <tr>
                            <th scope="row">Skin Type:</th>
                            <td>
                                <div data-swiftype-name="Skin Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Skin Type">{{ $productDetails->skin_type }}</div>
                            </td>
                        </tr>
                    @endif

                    @if ($productDetails->size != '')
                        <tr>
                            <th scope="row">Size:</th>
                            <td>
                                <div data-swiftype-name="Size" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Size">{{ $productDetails->size }}</div>
                            </td>
                        </tr>
                    @endif

                    @if ($productDetails->vtype != '')
                        <tr>
                            <th scope="row">Type:</th>
                            <td>
                                <div data-swiftype-name="Product Type"
                                    data-swiftype-type="string"
                                    @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif
                                    aria-label="Type"
                                >{{ $productDetails->vtype }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_family != '')
                        <tr>
                            <th scope="row">Fragrance Family:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Fragrance Family">{{ $productDetails->fragrance_family }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->formulation != '')
                        <tr>
                            <th scope="row">Formulation:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Formulation">{{ $productDetails->formulation }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->coverage != '')
                        <tr>
                            <th scope="row">Coverage:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Coverage">{{ $productDetails->coverage }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->finish != '')
                        <tr>
                            <th scope="row">Finish:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Finish">{{ $productDetails->finish }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_occasion != '')
                        <tr>
                            <th scope="row">Fragrance Occasion:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Fragrance Occasion">{{ $productDetails->fragrance_occasion }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_personality != '')
                        <tr>
                            <th scope="row">Fragrance Personality:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Fragrance Personality">{{ $productDetails->fragrance_personality }}</div>
                            </td>
                        </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_seasons != '')
                        <tr>
                            <th scope="row">Fragrance Seasons:</th>
                            <td>
                                <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif aria-label="Fragrance Seasons">{{ $productDetails->fragrance_seasons }}</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-lg-4 col-xl-6 col-md-12 col-sm-5 col-xs-12 pb15"></div>
    </div>
@endif