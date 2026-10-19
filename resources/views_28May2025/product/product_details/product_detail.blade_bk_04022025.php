    @if ($productDetails->referencedName != '')
    <div>
        <p class="wetext" data-swiftype-name="Long Description" data-swiftype-type="text" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else {data-swiftype-index='true' @endif>{!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$productDetails->product_description)) !!}</p>
        @if (($productDetails->category_id == 68 || $productDetails->category_id == 69 || $productDetails->category_id == 70 || $productDetails->category_id == 71) && $productDetails->is_atomizer == 'Yes')
        
        <span><b>DISCLAIMER:</b></span>
        <p>Our travel atomizer spray are independently repackaged by
            <strong>Maxaroma.com</strong> - Long Island City, NY 11101
        </p>
        <p><strong>Maxaroma.com</strong> decants genuine fragrances into our Maxaroma travel
            spray atomizers.</p>
        <p><strong>Maxaroma.com</strong> or any associated entities, are not associated with the
            designer, or the designer's manufacturer in any way.</p>
        @endif
    </div>
    <div class="pt15 d-md-block">
        <div class="pb15">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Item #:</th>
                        <td>{{ $productDetails->sku }}</td>
                    </tr>
                    <tr>
                        <th>Brand:</th>
                        <td><a href="{{ $vmanufacture_link }}"><U data-swiftype-name="Brand" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->vmanufacture }}</U></a>
                        </td>
                    </tr>

                    @if ($productDetails->gender != '')
                    <tr>
                        <th>Gender:</th>
                        <td>
                            <span data-swiftype-name="Gender" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>
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
                        <th>Product Name:</th>
                        <td>{{ stripslashes($productDetails->referencedName) }}</td>
                    </tr>

                    @if ($productDetails->skin_type != '')
                    <tr>
                        <th>Skin Type:</th>
                        <td>
                            <div @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif data-swiftype-name="Skin Type"
                                data-swiftype-type="string" @if ($productDetails->current_stock == 'Out')
                                data-swiftype-index='false' @else data-swiftype-index='true'
                                @endif>{{ $productDetails->skin_type }}
                            </div>
                        </td>
                    </tr>
                    @endif

                    @if ($productDetails->size != '')
                    <tr>
                        <th>Size:</th>
                        <td>
                            <div data-swiftype-name="Size" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->size }}</div>
                        </td>
                    </tr>
                    @endif

                    @if ($productDetails->vtype != '')
                    <tr>
                        <th>Type:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->vtype }}</div>
                        </td>
                    </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_family != '')
                    <tr>
                        <th>Fragrance Family:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_family }}</div>
                        </td>
                    </tr>
                    @endif
                    
                     @if ($productDetails->formulation != '')
                    <tr>
                        <th>Formulation:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->formulation }}</div>
                        </td>
                    </tr>
                    @endif
                    
                     @if ($productDetails->coverage != '')
                    <tr>
                        <th>Coverage:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->coverage }}</div>
                        </td>
                    </tr>
                    @endif
                    
                     @if ($productDetails->finish != '')
                    <tr>
                        <th>Finish:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->finish }}</div>
                        </td>
                    </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_occasion != '')
                    <tr>
                        <th>Fragrance Occasion:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_occasion }}</div>
                        </td>
                    </tr>
                    @endif
					
					@if ($productDetails->fragrance_personality != '')
                    <tr>
                        <th>Fragrance Personality:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_personality }}</div>
                        </td>
                    </tr>
                    @endif
                    
                    @if ($productDetails->fragrance_seasons != '')
                    <tr>
                        <th>Fragrance Seasons:</th>
                        <td>
                            <div data-swiftype-name="Product Type" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_seasons }}</div>
                        </td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
        <div class="col-lg-4 col-xl-6 col-md-12 col-sm-5 col-xs-12 pb15">
        </div>
    </div>
    @endif
