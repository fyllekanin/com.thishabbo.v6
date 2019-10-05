<?php $theme = $theme['html']; ?>
<div class="small-3 mobileShop column end">
    @if($theme['canbuy'] === 1)<a onclick="openYouSureTheme({{ $theme['themeid'] }}, {{ $theme['price'] }}, '{{ $theme['name'] }}');" data-open="theme_you_sure">@endif

    <div class="shop-box">
    <div class="shop-thumbnail" style="background-image: url('{{ $theme['theme'] }}'); background-size: 100%;" title="">
        @if($theme['thcb'] === 1)
            <div class="red-tag">
                THClub Exclusive
            </div>
        @endif
        <div class="shop-text">
            <div class="article-tags">

            </div>
            <b>{{ $theme['name'] }} </b><br />
            <span>
                @if($theme['owns'])
                    <b>Owned</b>
                @else

                    <i class="fa fa-ticket" aria-hidden="true"></i> {{ number_format($theme['price']) }} THC
                    @if($theme['canbuy'] === 1)<div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy </div>@endif

                @endif
            </span>
        </div>
        </div>
    </div>
@if($theme['canbuy'] === 1)</a>@endif
</div>
