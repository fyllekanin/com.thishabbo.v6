<?php $sub = $sub['html']; ?>
<div class="mobileShop small-3 column end">
    <a onclick="openYouSureSub({{ $sub['packageid'] }}, {{ $sub['dprice'] }}, '{{ $sub['name'] }}', '{{ $sub['description'] }}');" data-open="sub_you_sure">

    <div class="shop-box">
        <div class="article-tags">
        </div>
        @if($sub['usertext']['haveUsertext'])
           {!! $sub['usertext']['text'] !!}
        @endif
        @if($sub['userbar']['haveUserbar'])
            <style type="text/css">
                {!! $sub['userbar']['css'] !!}
            </style>
            {!! $sub['userbar']['html'] !!}
        @endif
        <div class="shop-text" class="red_link">
            <b>{{ $sub['name'] }}</b><br />
            <span>
                    <i class="fa fa-diamond" aria-hidden="true"></i> {{ number_format($sub['dprice']) }}
                    <div id="buy"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy</div>

            </span>
        </div>
    </div>
</a>
</div>
