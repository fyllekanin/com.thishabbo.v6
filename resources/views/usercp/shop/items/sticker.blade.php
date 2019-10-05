<?php $sticker = $sticker['html']; ?>
<div class="mobileShop small-3 column end">
    @if($sticker['canbuy'] ===1)<a onclick="openYouSureSticker({{ $sticker['stickerid'] }}, {{ $sticker['price'] }}, '{{ $sticker['name'] }}');" data-open="sticker_you_sure">@endif

    <div class="shop-box">
    <div class="shop-thumbnail" style="background-image: url('{{ $sticker['sticker'] }}');" title="">
        @if($sticker['thcb'] === 1)
            <div class="red-tag">
                THClub Exclusive
            </div>
        @endif
        <div class="shop-text">
            <b>{{ $sticker['name'] }}</b><br />
            <span>

                    @if($sticker['limit_left'] > 0 || $sticker['limit_left'] == -1)
                        <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($sticker['price']) }} THC
                        @if($sticker['canbuy'] ===1)<div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy {{ $sticker['limit_left'] > 0 ? '(' . $sticker['limit_left'] . ' left!)' : '' }}</div>@endif

                    @else
                        <b>Sold Out</b>
                    @endif
                    </div>
            </span>
        </div>
    </div>
@if($sticker['canbuy']===1)</a> @endif
</div>
