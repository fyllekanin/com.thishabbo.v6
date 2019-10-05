<script> urlRoute.setTitle("TH - Kissing Generator");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>
<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Kissing Generator</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerBlue">
			    <span>Habbo Kissing Generator</span>
			  </div>
			  <div class="content-ct">
			  	<div class="ct-center">
			  		<img src="{{ asset('_assets/img/website/goodies/kissing/example1.png') }}" alt="example1" id="alt_display" />
			  		<div style="width: 170px; height: 170px; display: none;" id="height_display"></div>
			  	</div>
			  	<div id="altForm">
			  		<div class="row">
			  			<div class="small-6 column">
						  	<label for="goodie-form-habbo">Habbo #1</label>
					        <input type="text" id="goodie-form-habbo1" placeholder="Habbo Name" class="login-form-input"/>
					    </div>
					    <div class="small-6 column">
					        <label for="goodie-form-habbo">Habbo #2</label>
					        <input type="text" id="goodie-form-habbo2" placeholder="Habbo Name" class="login-form-input"/>
					    </div>
					</div><br />
			        <button class="pg-blue headerBlue gradualfader" style="width: 100%;" onclick="setHabbo();">Generate</button>
			    </div>
			  </div>
			 </div>
	</div>
	<div class="row">
		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 1);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/kissing/example1.png') }}" alt="example1" />
				<div class="alt-selected">Selected</div>
			</div>
		</div>

		<div class="small-12 medium-6 large-3 end column">
			<div class="content-holder ct-center" onclick="setExample(this, 2);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/kissing/example2.png') }}" alt="example2" />
			</div>
		</div>
	</div>
</div>
<div class="small-4 column mobileFunction">
	<div class="content-holder">
		<div class="content">
		<div class="contentHeader headerBlue">
		    <span>Scanned Badges</span> <a href="/badges" class="headerLink white_link web-page"><b>More</b></a>
		  </div>
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
	var habbo1 = "optra";
	var habbo2 = "bear94";
	var action = 1;

	$("#altForm").keypress(function(e) {
      if(e.which == 13) {
          setHabbo();
      }
    });

	var generate = function() {
		$('#alt_display').fadeOut(function() {
			$('#height_display').fadeIn();
			$('#alt_display').attr('src', urlRoute.getBaseUrl() + 'goodies/kissing/' + habbo1 + '/' + habbo2 + '/' + action);

			$('#alt_display').on('load', function(){
				$('#height_display').fadeOut(function () {
					$('#alt_display').fadeIn();
				});
			});
		});
	}

	var setHabbo = function() {
		habbo1 = $('#goodie-form-habbo1').val();
		habbo2 = $('#goodie-form-habbo2').val();

		generate();
	}

	var setExample = function(e, act) {
		$('.content-holder').find(".alt-selected").fadeOut("fast", function() {
			$('.alt-selected').remove();
			action = act;
			$(e).append('<div class="alt-selected">Selected</div>');
			$('.alt-selected').fadeIn();
			generate();
		});
	}

	var badgeError = function(image) {
		image.onerror = "";
		image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
		return true;
	};

	var destroy = function() {
    	generate = null;
    	setHabbo = null;
    	setExample = null;
    	badgeError = null;
  	}
</script>
