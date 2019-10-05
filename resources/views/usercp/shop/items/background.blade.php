<?php $background = $background['html']; ?>
<div class="mobileShop small-3 column end">
    @if($background['thcb']===0)<a onclick="openYouSureBackground({{ $background['backgroundid'] }}, {{ $background['price'] }}, '{{ $background['name'] }}');" data-open="background_you_sure">@endif

    <div class="shop-box">
    <div class="shop-thumbnail" style="background-image: url('{{ $background['background'] }}');" title="">
        <div class="article-tags">
            @if($background['thcb'] === 1)
                <div class="red-tag">
                    THClub Exclusive
                </div>
            @endif
        </div>
        <div class="shop-text">
            <b>{{ $background['name'] }}</b><br />
            <span>
                @if($background['owns'])
                    <b>Owned</b>
                @else

                        <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($background['price']) }} THC
                        @if($background['thcb'] === 0)<div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy</div>@endif

                @endif
            </span>
            </div>
        </div>
    </div>
@if($background['thcb']===0)</a>@endif
</div>
