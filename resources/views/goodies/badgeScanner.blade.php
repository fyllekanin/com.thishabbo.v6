<script> urlRoute.setTitle("TH - Badge Scanner");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Badge Scanner</span>
    </div>
  </div>
</div>


<div class="small-12 medium-12 large-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Badge Scanner</span>
            </div>
				  		<div class="habbo_image_user"></div>
				  		<div style="width: 150px; height: 150px; display: none;" id="height_display"></div>

				  	<div id="scanForm">
					  	<label for="scan-form-habbo">Habbo Name</label>
				        <input type="text" id="scan-form-habbo" placeholder="Habbo Name" class="login-form-input"/>
				        <br />
				        <button class="pg-red fullWidth headerBlue floatright gradualfader shopbutton" style="width: 100%;" onclick="scanHabbo();">Scan Habbo</button>
					</div>
			</div>
		</div>

		<div id="badge_loading_box" style="display: none;">
			<img src="{{ asset('_assets/img/website/gears.svg') }}" alt="loading gears" />
		</div>

		<div id="badge_error_box" style="display: none;">
			<div class="small-12 medium-12 large-12 column">
				<div class="contentHeader headerRed">
		            <span>Sorry. We couldn't find that Habbo...</span>
		        </div>
		    	<div class="content-holder">
			        <div class="content">
						<img src="{{ asset('_assets/img/website/goodies/examples/example6.png') }}" alt="Error Alt" style="float: left; margin-top: -35px; margin-left: -55px; margin-bottom: -30px;" />
						I really tried! but I could not find the user you where looking for! I have a few Theories! <br />
						<br />
						<li>No one with this username.</li>
						<li>The person have a private profile.</li>						<li>No one with this username.</li>

						<li>The person is banned.</li>
						<li>API is broken.</li>
					</div>
				</div>
			</div>
		</div>

		<div id="habbo_information" style="display: none;">
			<div class="small-12 medium-12 large-12 column">
	            <div class="contentHeader headerBlue">
	                <span>Badges shown by Habbo</span>
	                <a href="/badges" class="headerLink white_link web-page"><b id="amount_badges"></b></a>
	            </div>
			    <div class="content-holder">
			        <div class="content">
					  	<div class="display_badge">

					  	</div>
					</div>
				</div>

            <div class="contentHeader headerPink">
                <span>Other badges we found...</span>
            </div>
			    <div class="content-holder">
			        <div class="content">
			        	<div class="ct-center">

						  	<div class="" id="scanned_badges_users" style="padding-right: 1.2rem;">

						  	</div>

						 </div>
					</div>
				</div>
		</div>



<script type="text/javascript">

	$("#scanHabbo").keypress(function(e) {
      if(e.which == 13) {
          setHabbo();
      }
    });

	var scanHabbo = function() {
		var habbo = $('#scan-form-habbo').val();
		$('#badge_error_box').fadeOut("fast", function() {
			$('#habbo_information').fadeOut('fast', function() {
				$('#scanned_badges_users').html("");
				$('.display_badge').html("");
				$('#badge_loading_box').fadeIn();
			});
		});
		$.ajax({
			url: urlRoute.getBaseUrl() + 'goodies/badge/scan',
			type: 'post',
			data: {habbo:habbo},
			success: function(data) {
				if(data['response'] == true) {
					$.each(data['badges'], function(index, value) {
						$('.display_badge').append('<div class="small-3 medium-2 large-1 column"><div class="badge-container" title="' + value['name'] + '"><img onerror="badgeError(this);" src="http://habboo-a.akamaihd.net/c_images/album1584/' + value['code'] + '.gif" alt="badge" /></div></div>');
					});

					if(data['amountBadges'] > 0) {
						$.each(data['allBadges'], function(index, value) {
							$('#scanned_badges_users').append('<div class="small-3 medium-2 large-1 column"><div class="badge-container" title="' + value['name'] + '"><img onerror="badgeError(this);" src="http://habboo-a.akamaihd.net/c_images/album1584/' + value['code'] + '.gif" alt="badge" /></div></div>');
						});
					} else {
						urlRoute.ohSnap('This habbo have a private profile', 'blue');
					}

					$('#amount_badges').html(data['amountBadges'] + ' Badges');

					$('#badge_loading_box').fadeOut("fast", function() {
						$('#habbo_information').fadeIn();
						Tipped.create('.badge-container');
					});
				} else {
					urlRoute.ohSnap('Habbo not found!', 'blue');
				}
			},
			error: function(data) {
				$('#badge_loading_box').fadeOut("fast", function() {
					$('#badge_error_box').fadeIn();
				});
			}
		});
	}

	var badgeError = function(image) {
		image.onerror = "";
		image.src = "https://thishabbo.com/_assets/img/website/badge_error.gif";
		return true;
	};

	var destroy = function() {
		scanHabbo = null;
		badgeError = null;
	}
</script>
