<script> urlRoute.setTitle("TH - Effects");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
  <span><a href="/home" class="bold web-page">Home</a>  <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp" class="bold web-page">UserCP</a>   <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp/shop" class="bold web-page">ThisHabboShop</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> Name Effects
    </div>
  </div>
</div>

<div class="medium-8 column">
                 <div class="contentHeader headerRed">
                    Buy Name Effects
                </div>
                <div class="minHeight">
                <div class="thshop">
                    @foreach($effects as $effect)
                        {!! $effect !!}
                    @endforeach
                </div>
            </div>
        <div class="content-holder" style="overflow: hidden;">
                <div class="content">
                    {!! $pagi !!}
                </div>
        </div>
        </div></div>
@include('usercp.shop.shopmenu')

        @include('usercp.shop.shopprofile')


    @include('usercp.shop.transactions')

</div>