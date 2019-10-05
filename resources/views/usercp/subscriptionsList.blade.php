<script> urlRoute.setTitle("TH - Buy Diamonds");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                ThisHabbo Diamonds
            </div>
    </div>
</div>

<div class="medium-4 column">
  @include('usercp.menu')
</div>
<div class="medium-8 column">
            <div class="contentHeader headerRed">
                <span>ThisHabbo Diamonds (THD)</span>
            </div>

    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
				<b>ThisHabbo Diamonds (THD)</b> is a new currency introduced to <b>Version 6</b> soon after <b>ThisHabbo Credits (THC)</b> was released. Unlike previously released shops by us, they have quickly been forgotten about. THD/THC will not be. This will be used for absolutely everything in the ThisHabboShop (THS): Name Effects, ThisHabboClub (THCB), Icons, Stickers, Furni, Themes and much more!<br />
				<br />
				<br />
				<b>What is THD?</b><br />
				THD is the equivalent of Diamonds on Habbo. It's one of the two currencies here at ThisHabbo. Think of it mainly as monopoly money! 1 THD is equal to 1500 THC.<br />
				<br />
				<b>What is THC?</b><br />
				THC much like THD is a currency for ThisHabbo. Think of Credits on Habbo. You can exchange 1500 THC to get 1 THD which can be used to purchase ThisHabboClub (THCB).<br />
				<br />
				<b>How do I get THD?</b><br />
				Unfortunately, there are only two ways to get THD. One way is by filling out the form below and buying them with real money, or if you get 1500 THC, you can exchange it in our shop for 1 THD.<br />
				<br />
				<b>How do I get THC?</b><br />
				You can get THC by posting around the site and actively participating in different aspects of the site. Then once you hit 1500 THC, you can exchange it for THD!<br />
				<br />
				<br />
				<b>Current THC:</b> {{ Auth::user()->credits }}	<br />
				<b>Current Diamonds:</b> {{ Auth::user()->diamonds }}
				<hr />
				To buy Diamonds just fill in the amount you want below! <strong>£1 = 1 Diamond</strong><br />
				<br />
				<input type="number" id="amount-of-credits" placeholder="How many Diamonds do you wish to buy?" class="login-form-input"/><br />
				<button class="pg-red headerRed gradualfader fullWidth topBottom barFix" onclick="buyCredits();">Buy Diamonds</button>
				<a href="/usercp/shop" class="web-page"> <button id="signin-now" class="pg-red headerGreen floatright gradualfader fullWidth topBottom">Visit the ThisHabbo Shop!</button></a>
		  		<br />
		  		<br />
		  		@if($status >= 1)
		  			@if($status == 2)
				  		<b>SUCCESS!</b><br />
				  	@elseif($status == 1)
				  		<b>ERROR!</b><br />
				  		<i>Something went wrong! Contact an administrator!</i>
				  	@endif
				@endif
			</div>
		</div>
	</div>
</div>
<?php
  $folder = explode('/', $_SERVER['REQUEST_URI']);
  $url = "http://" . $_SERVER['SERVER_NAME'] . '/' . $folder[1] . '/';
?>
<script type="text/javascript">
	var buyCredits = function() {
		var amount = parseInt($('#amount-of-credits').val());
		if(!amount || amount === '' || amount === 0) {
			urlRoute.ohSnap('You need to fill in legit amount of credits', 'red');
		} else {
			$.ajax({
				url: urlRoute.getBaseUrl() + 'usercp/credits/paypal/url/'+amount,
				type: 'get',
				success: function(data) {
					if(data['response'] === true){
						window.location.replace(data['url']);
					} else {
						urlRoute.ohSnap('You must enter a value of 1 Diamond or more in order to proceed!', 'red');
					}
				}
			});
		}
	}
</script>
