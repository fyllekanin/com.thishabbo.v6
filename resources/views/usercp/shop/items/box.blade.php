<?php $box = $box['html']; ?>
<div class="mobileShop medium-4 large-3 column">
    @if($box['duplicate'] == 1 || !$box['owned'])<a onclick="openYouSureBox({{ $box['boxid'] }}, {{ $box['price'] }}, '{{ $box['name'] }}');" class="red_link" data-open="box_you_sure">@endif

    <div class="shop-box" style="" title="">
        <div class="shop-thumbnail" style="background-image: url('{{ $box['box'] }}');" title="">
        <div class="shop-text">
            <b>{{ $box['name'] }}</b><br />
            <span>
                <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($box['price']) }} THC
                    <div id="buy">@if($box['duplicate'] == 1 || !$box['owned'])<i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy @else Owned @endif</div>

            </span>
        </div>
    </div>
    </div>
@if($box['duplicate'] == 1 || !$box['owned'])</a>@endif
</div>
