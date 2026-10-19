@if($starRating == 1)
<span class="rating" role="img" aria-label="Rated 1 out of 5 stars">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 2)
<span class="rating" role="img" aria-label="Rated 2 out of 5 stars">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 3)
<span class="rating" role="img" aria-label="Rated 3 out of 5 stars">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 4)
<span class="rating" role="img" aria-label="Rated 4 out of 5 stars">
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 5)
<span class="rating" role="img" aria-label="Rated 5 out of 5 stars">
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@else
<span class="rating" role="img" aria-label="No rating">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
</span>
@endif