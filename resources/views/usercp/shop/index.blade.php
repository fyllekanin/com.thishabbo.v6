<script> urlRoute.setTitle("TH - Shop");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
  <span><a href="/home" class="bold web-page">Home</a>  <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp" class="bold web-page">UserCP</a>   <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp/shop" class="bold web-page">ThisHabboShop</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> ShopHome
    </div>
  </div>
</div>


<div class="medium-8 column">
                <div class="contentHeader headerRed">
                    ThisHabbo Shop
                </div>
                <div class="minHeight">
                <div class="thshop">

                    <?php $len = count($latestItems) > 16 ? 16 : count($latestItems); ?>
                    @for($i = 0; $i < $len; $i++) 
                        {!! $latestItems[$i] !!}
                    @endfor
                </div>
                </div>
        </div>
@include('usercp.shop.shopmenu')

        @include('usercp.shop.shopprofile')

        
    @include('usercp.shop.transactions')

</div>
