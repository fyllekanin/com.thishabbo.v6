<script> urlRoute.setTitle("TH - Habbo Imager");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Habbo Imager</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerBlue">
		    <span>Habbo Imager</span>
		  </div>
		  <div class="content-ct">
	  		<div class="small-6 column" style="text-align: center;">
	  			<img src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m" id="habbo_image_holder"/>
	  		</div>
	  		<div class="small-6 column">
	  			<label for="habbo_image_name">Habbo Name</label>
	        	<input type="text" id="habbo_image_name" value="" class="login-form-input" onkeyup="changeHabbo();" />

	        	<label for="goodie-form-habbo">Expression</label>
	        	<select id="habbo_image_exp" class="login-form-input" onchange="changeExpression();">
		          <option value="gesture=nrm">Normal</option>
		          <option value="gesture=sml">Happy</option>
		          <option value="gesture=sad">Sad</option>
		          <option value="gesture=agr">Angry</option>
		          <option value="gesture=srp">Surprised</option>
		          <option value="gesture=eyb">Sleeping</option>
		          <option value="gesture=spk">Speaking</option>
		        </select>
	  		</div>
	  		<hr />
	  		<div class="small-12 medium-6 column">
	  			<div class="row">
	  				<div class="small-6 column">
			  			<label for="habbo_image_action">Action</label>
			        	<select id="habbo_image_action" class="login-form-input" onchange="changeAction();">
			        	  <option value="">Nothing</option>
				          <option value="action=wlk">Walking</option>
				          <option value="action=lay">Lying Down</option>
				          <option value="action=sit">Sitting</option>
				          <option value="action=wav">Waving</option>
				          <option value="action=crr">Holding</option>
				          <option value="action=drk">Drinking</option>
				        </select>
				    </div>
				    <div class="small-6 column">
				        <label for="habbo_image_item">Items to drink/hold</label>
			        	<select id="habbo_image_item" class="login-form-input" onchange="changeItem();">
				          <option value="=0">Nothing</option>
				          <option value="=2">Carrot</option>
				          <option value="=6">Coffee</option>
				          <option value="=667">Cocktail</option>
				          <option value="=5">Habbo Cola</option>
				          <option value="=3">Ice cream</option>
				          <option value="=42">Japanese tea</option>
				          <option value="=9">Love potion</option>
				          <option value="=44">Radioactive</option>
				          <option value="=43">Tomato</option>
				          <option value="=1">Water</option>
				        </select>
				    </div>
			    </div>
	  		</div>
	  		<div class="small-12 medium-6 column new-checkbox">
	  			<div class="row" style="text-align: center;">
	  				<div class="small-3 column">
			  			<input id="radio1" type="radio" name="radio" onclick="changeSize('headonly=1');">
			  			<label for="radio1">
			  				<span><span></span></span>
			  				<div style="font-size: 0.7rem;">Head</div>
			  			</label>
			  		</div>

			  		<div class="small-3 column">
			  			<input id="radio2" type="radio" name="radio" onclick="changeSize('size=s');">
			  			<label for="radio2">
			  				<span><span></span></span>
			  				<div style="font-size: 0.7rem;">Small</div>
			  			</label>
			  		</div>

			  		<div class="small-3 column">
			  			<input id="radio3" type="radio" name="radio" onclick="changeSize('size=m');" checked="checked">
			  			<label for="radio3">
			  				<span><span></span></span>
			  				<div style="font-size: 0.7rem;">Normal</div>
			  			</label>
			  		</div>

			  		<div class="small-3 column">
			  			<input id="radio4" type="radio" name="radio" onclick="changeSize('size=l');">
			  			<label for="radio4">
			  				<span><span></span></span>
			  				<div style="font-size: 0.7rem;">Large</div>
			  			</label>
			  		</div>
			  	</div>
	  		</div>
	  		<div class="small-12 column" style="text-align: left;">
	  			<label>Body Direction</label>
	  			<img onclick="changeBodyDirection(1);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=1&head_direction=1&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(2);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(3);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=3&head_direction=3&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(4);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=4&head_direction=4&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(5);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=5&head_direction=5&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(6);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=6&head_direction=6&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(7);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=7&head_direction=7&action=&gesture=nrm&size=m" />

	  			<img onclick="changeBodyDirection(8);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=8&head_direction=8&action=&gesture=nrm&size=m" />
	  		</div>
	  		<div class="small-12 column" style="text-align: left; margin-top: 1rem;">
	  			<label>Head Direction</label>
	  			<img onclick="changeHeadDirection(1);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=1&head_direction=1&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(2);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(3);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=3&head_direction=3&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(4);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=4&head_direction=4&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(5);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=5&head_direction=5&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(6);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=6&head_direction=6&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(7);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=7&head_direction=7&action=&gesture=nrm&size=m&headonly=1" />

	  			<img onclick="changeHeadDirection(8);" src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=8&head_direction=8&action=&gesture=nrm&size=m&headonly=1" />
	  		</div>
		  </div>
		</div>
	</div>
</div>
<div class="small-4 column mobileFunction">
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerBlue">
			    <span>Link & Codes</span>
			  </div>
			  <div class="content-ct">
			  	<label for="habbo_image_link">Direct Link</label>
			    <input type="text" id="habbo_image_link" value="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m" class="login-form-input"/>

			    <label for="habbo_image_bbcode">BBcode</label>
			    <input type="text" id="habbo_image_bbcode" value="[IMG]https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m[/IMG]" class="login-form-input"/>

			    <label for="habbo_image_html">HTML code</label>
			    <input type="text" id="habbo_image_html" value='<img src="https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=2&head_direction=2&action=&gesture=nrm&size=m" />' class="login-form-input"/>
			  </div>
			 </div>
	</div>
			  <div class="contentHeader headerBlue">
			    <span>Scanned Badges</span>
			    <a href="/badges" class="headerLink white_link web-page">More</a>
			  </div>
	<div class="content-holder">
			<div class="content">
			  <div class="content-ct ct-center">
			    <div class="row" id="list_badges">
			  		@foreach($badges as $badge)
			  		<div class="small-3 column">
				      <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
				        <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" />
				        @if($badge['new'])<div class="badge-new-badge">New</div>@endif
				      </div>
				     </div>
				    @endforeach
			  	</div>
			  </div>
			 </div>
		</div>
</div>

<script type="text/javascript">
	var habbo_name = "irDez";
	var expression = "gesture=nrm";
	var action = "";
	var holdrink = 0;
	var size = "size=m";
	var body_direction = "direction=2";
	var head_direction = "head_direction=2";

	var generateUrl = function() {
		var url = "https://www.habbo.com/habbo-imaging/avatarimage?user="+habbo_name+"&"+body_direction+"&"+head_direction+"&"+action+"&"+expression+"&"+size;
		$('#habbo_image_holder').attr('src', url);

		$('#habbo_image_html').val('<img src="' + url + '" />');
		$('#habbo_image_bbcode').val('[IMG]'+url+'[/IMG]');
		$('#habbo_image_link').val(url);
	}

	var changeBodyDirection = function(nr) {
		body_direction = "direction="+nr;
		generateUrl();
	}

	var changeHeadDirection = function(nr) {
		head_direction = "head_direction="+nr;
		generateUrl();
	}

	var changeSize = function(newSize) {
		size = newSize;
		generateUrl();
	}

	var changeExpression = function() {
		expression = $('#habbo_image_exp').val();
		generateUrl();
	}

	var changeHabbo = function() {
		habbo_name = $('#habbo_image_name').val();
		generateUrl();
	}

	var changeAction = function() {
		action = $('#habbo_image_action').val();

		if(action === "action=crr") {
			action = action + $('#habbo_image_item').val();
		}

		if(action === "action=drk") {
			action = action + $('#habbo_image_item').val();
		}

		generateUrl();
	}

	var changeItem = function() {
		action = $('#habbo_image_action').val();

		if(action === "action=crr") {
			action = action + $('#habbo_image_item').val();
		}

		if(action === "action=drk") {
			action = action + $('#habbo_image_item').val();
		}

		generateUrl();
	}

	var badgeError = function(image) {
		image.onerror = "";
		image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
		return true;
	};

	var destroy = function() {
		generateUrl = null;
		changeBodyDirection = null;
		changeHeadDirection = null;
		changeSize = null;
		changeExpression = null;
		changeHabbo = null;
		changeAction = null;
		changeItem = null;
		badgeError = null;
	}
</script>
