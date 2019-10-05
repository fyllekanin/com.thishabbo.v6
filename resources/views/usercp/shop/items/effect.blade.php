<?php $effect = $effect['html']; ?>
<div class="mobileShop small-3 column end">
    @if($effect['canbuy'] === 1)<a onclick="openYouSureEffect({{ $effect['effectid'] }}, {{ $effect['price'] }}, '{{ $effect['name'] }}');" class="red_link" data-open="effect_you_sure">@endif

    <div class="shop-box">
    <div class="shop-thumbnail" style="background-image: url('{{ $effect['effect'] }}');" title="">
        <div class="article-tags">
            @if($effect['thcb'] === 1)
                <div class="red-tag">
                    THClub Exclusive
                </div>
            @endif
        </div>
        <div class="shop-text">
            <b>{{ $effect['name'] }}</b><br />
            <span>
                @if($effect['owns'])
                    <b>Owned</b>
                @else
                    @if($effect['limit_left'] > 0 || $effect['limit_left'] == -1)
                        <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($effect['price']) }} THC
                        @if($effect['canbuy'] === 1)<div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy {{ $effect['limit_left'] > 0 ? '(' . $effect['limit_left'] . ' left!)' : '' }}
                        </div>@endif
                    @else
                        <b>Sold Out</b>
                    @endif
                @endif
            </span>
            </div>
        </div>
    </div>
@if($effect['canbuy'] === 1)</a>@endif
</div>
