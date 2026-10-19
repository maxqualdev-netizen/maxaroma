@if($starRating == 1)
<span class="rating">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 2)
<span class="rating">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 3)
<span class="rating">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 4)
<span class="rating">
    <span class="star"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@elseif($starRating == 5)
<span class="rating">
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
    <span class="star active"></span>
</span>
@else
<span class="rating">
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
    <span class="star"></span>
</span>
@endif