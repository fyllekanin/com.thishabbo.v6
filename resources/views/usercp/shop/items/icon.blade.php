<?php $icon = $icon['html']; ?>
<div class="mobileShop small-3 column end">
@if($icon['canbuy'] ===1)<a onclick="openYouSureIcon({{ $icon['iconid'] }}, {{ $icon['price'] }}, '{{ $icon['name'] }}');" data-open="icon_you_sure">@endif
    <div class="shop-box">
    <div class="shop-thumbnail" style="background-image: url('{{ $icon['icon'] }}');" title="">
        <div class="article-tags">
            @if($icon['thcb'] === 1)
                <div class="red-tag">
                    THClub Exclusive
                </div>
            @endif
        </div>
        <div class="shop-text">
            <b>{{ $icon['name'] }}</b><br />
            <span>
                @if($icon['owns'])
                    <b>Owned</b>
                @else

                    @if($icon['limit_left'] > 0 || $icon['limit_left'] == -1)
                        <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($icon['price']) }} THC
                            @if($icon['canbuy'] ===1)<div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy {{ $icon['limit_left'] > 0 ? '(' . $icon['limit_left'] . ' left!)' : '' }}</div>@endif
                    @else
                        <b>Sold Out</b>
                    @endif
                @endif
            </span>
            </div>
        </div>
    </div>
    @if($icon['canbuy'] ===1)</a>@endif
</div>
