<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("Open Box!");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
        <span><a href="/home" class="bold web-page">Home</a>
            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        <span><a href="/usercp" class="bold web-page">UserCP</a>
            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        <span><a href="/usercp/shop" class="bold web-page">ThisHabboShop</a>
            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        <span><a href="/usercp/shop/boxes/page/1" class="bold web-page">Mystery Box</a>
            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        Open Box
    </span>
    </div>
  </div>
</div>

<div class="small-12 medium-12 large-12 column">
        <div class="headerRed contentHeader">
            What's inside your box?!
        </div>
</div>
                @foreach($items as $item)
                    @if($item['type'] == 4)
                    <div class="mobileShop small-4 column end">
                        <div class="shop-box">
                            @if($item['usertext']['haveUsertext'])
                            {!! $item['usertext']['text'] !!}
                           @endif
                           @if($item['userbar']['haveUserbar'])
                                <style type="text/css">
                                    {!! $item['userbar']['css'] !!}
                                </style>
                                {!! $item['userbar']['html'] !!}
                            @endif
                            <div class="shop-boxtext">
                                <b>{{ $item['texttype'] }} - {{ $item['name'] }}</b><br />
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mobileShop small-4 column end">
                        <div class="shop-box" style="background-image: url('{{ $item['picture'] }}');" title="">
                            <div class="shoptext">
                                <b>{{ $item['texttype'] }} - {{ $item['name'] }}</b><br />
                            </div>
                        </div>
                    </div>

                    @endif
                @endforeach

<script type="text/javascript">
</script>
