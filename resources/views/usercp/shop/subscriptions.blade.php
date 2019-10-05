<script> urlRoute.setTitle("TH - Subcriptions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
  <span><a href="/home" class="bold web-page">Home</a>  <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp" class="bold web-page">UserCP</a>   <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp/shop" class="bold web-page">ThisHabboShop</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> Subscriptions
    </div>
  </div>
</div>

<div class="medium-8 column">
                <div class="contentHeader headerRed">
                    Buy Subscriptions
                </div>
                <div class="minHeight">
                <div class="thshop">

                    @foreach($subs as $sub)
                        {!! $sub !!}
                    @endforeach
                </div>
                </div>
            
            <div class="content-holder" style="overflow: hidden;">
                <div class="content">
                    {!! $pagi !!}
                </div>
            </div>
        </div>
@include('usercp.shop.shopmenu')

        @include('usercp.shop.shopprofile')


    @include('usercp.shop.transactions')

</div>