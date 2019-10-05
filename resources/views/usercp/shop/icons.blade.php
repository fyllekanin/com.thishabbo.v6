<script> urlRoute.setTitle("TH - Icons");</script>

<div class="reveal" id="icon_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the icon <b id="ICON_NAME"></b> for <b id="ICON_PRICE"></b> credits?
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="buyIcon();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>


<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
  <span><a href="/home" class="bold web-page">Home</a>  <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp" class="bold web-page">UserCP</a>   <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp/shop" class="bold web-page">ThisHabboShop</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> Name Icons
    </div>
  </div>
</div>

<div class="medium-8 column">
                <div class="contentHeader headerRed">
                    Buy Name Icons
                </div>
                <div class="minHeight">
                <div class="thshop">
                    @foreach($icons as $icon)
                        {!! $icon !!}
                    @endforeach
                </div>
                </div>
        <div class="content-holder" style="overflow: hidden;">
                <div class="content">
                    {!! $pagi !!}
                </div>
        </div></div>
@include('usercp.shop.shopmenu')

        @include('usercp.shop.shopprofile')


    @include('usercp.shop.transactions')

</div>