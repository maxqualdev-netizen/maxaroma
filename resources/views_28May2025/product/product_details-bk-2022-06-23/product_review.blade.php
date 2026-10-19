<div class="container">
  <div class="dtl_rview_form">
    <div class="hd">Ratings & Reviews</div>
    <div class="review-lt">
    <div class="row">
      <div class="col-lg-4 pt-3">
        <p class="f14">
          <x-ratingstar :starRating="$avg_rate" />
          @if (count($arr_product_review) > 0)
          {{ count($arr_product_review) }} REVIEWS
          @else
          {{ count($arr_product_review) }} REVIEWS
          @endif
        </p>
        <p class="f12">
          {!! $review_reward_point_text !!}
        </p>
      </div>
      <div class="col-lg-8">
        <div class="row no-gutters">
          <div class="col-sm-5 pt-3 text-center">
            <div class="review-rating-bar">

              @foreach ($productreviewBystar as $key => $value)
              <div class="rw-side">
                <div>{{$value['star_rate']}} star</div>
              </div>
              <div class="rw-middle">
                <div class="rw-bar-container">
                  <div class="rw-bar bar-{{$value['star_rate']}}" style="width: {{ $value['reviewpercentage']}}%"></div>
                </div>
              </div>
              <div class="rw-side right">
                <div>{{$value['starcount']}}</div>
              </div>
              @endforeach

            </div>
          </div>
          @if (count($arr_product_review) > 0)
          <div class="col-sm-3 pt-3 text-center f12">
            <x-ratingstar :starRating="$avg_rate" />
            <br />
            {{$avg_rate}} / 5 Stars
          </div>
          @else
          <div class="col-sm-3 pt-3 text-center f12">
          </div>
          @endif
          <div class="col-sm-4 pt-3 text-center text-sm-right review-btn-s">
            <a href="javascript:void(0);" id="productratingsreviewbottom" class="btn btn-primary productratingsreview" data-pid="{{ $productDetails->products_id }}">
              <svg class="pencil_svg vam" aria-hidden="true" role="img" width="20" height="19">
                <use href="#pencil_svg" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#pencil_svg"></use>
              </svg>
              Write A Review
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  @if (count($arr_product_review) > 0)
  <div class="dtl_rview_list" id="sort_read_review">
    <div class="row">
      @foreach ($arr_product_review as $key => $value)

      <div class="col-md-4 pt-3 @if ($key> 2) hideReviewCs @endif" @if ($key> 2) style="display:none;"
        @endif>
        <p class="f18">
          <strong>
            @if ($productDetails->short_description != '')
            {{ $productDetails->short_description }}
            @endif
          </strong>
        </p>
        <p class="f18">{{ $value['first_name'] }}</p>
        <p><em>{{ $value['city'] }}, {{ $value['state'] }}, {{ $value['country'] }}</em></p>

        <x-ratingstar :starRating="$value['star_rate']" />

        <p id="reviewtextdisplay{{ $key }}">
          {{ \Illuminate\Support\Str::limit($value['user_review'], 160, $end = '...') }}
        </p>
        <p id="reviewtexthide{{ $key }}" style="display:none;">{!! $value['user_review'] !!}</p>
        @if (strlen($value['user_review']) > 160)
        <span id="reviewshow{{ $key }}"><a class="rw-more" href="javascript:void(0);" onclick="morereviewshow('{{ $key }}');">Read More</a>
        </span>
        <span id="reviewhide{{ $key }}" style="display:none;"><a class="rw-more rw-minus" href="javascript:void(0);" onclick="morereviewhide('{{ $key }}');">See Less</a>
        </span>
        @endif
      </div>
      @endforeach
    </div>
    @if (count($arr_product_review) > 3)
    <div class="text-center pt-4"> <a href="javascript:void(0);" class="btn btn-secondary" id="SeeReviewMore" onclick="SeeMoreReview();">See More Reviews</a> </div>
    @endif
  </div>
  @endif
</div>