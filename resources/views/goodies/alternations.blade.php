<script> urlRoute.setTitle("TH - Alteration Generator");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Habbo Alteration</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerBlue">
				    <span>Habbo Alterations</span>
				  </div>
				  <div class="content-ct">
				  	<div class="ct-center">
				  		<img src="{{ asset('_assets/img/website/goodies/examples/example1.png') }}" alt="example1" id="alt_display" />
				  		<div style="width: 150px; height: 150px; display: none;" id="height_display"></div>
				  	</div>
				  	<div id="altForm">
					  	<label for="goodie-form-habbo">Habbo Name</label>
				        <input type="text" id="goodie-form-habbo" placeholder="Habbo Name" class="login-form-input"/><br/>
				        <button class="pg-blue headerBlue gradualfader" style="width: 100%;" onclick="setHabbo();">Generate</button>
				    </div>
				  </div>
				</div>
	</div>
	<div class="row">
		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 1);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example1.png') }}" alt="example1" />
				<div class="alt-selected">Selected</div>
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 2);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example2.png') }}" alt="example2" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 3);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example3.png') }}" alt="example3" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 4);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example4.png') }}" alt="example4" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 5);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example5.png') }}" alt="example5" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 6);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example6.png') }}" alt="example6" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 7);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example7.png') }}" alt="example7" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 8);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example8.png') }}" alt="example9" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 9);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example9.png') }}" alt="example10" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 10);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example10.png') }}" alt="example11" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 11);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example11.png') }}" alt="example12" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 12);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example12.png') }}" alt="example13" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 13);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example13.png') }}" alt="example14" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 14);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example14.png') }}" alt="example15" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 15);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example15.png') }}" alt="example16" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 16);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example16.png') }}" alt="example17" />
			</div>
		</div>

		<div class="small-12 medium-6 large-3 column">
			<div class="content-holder ct-center" onclick="setExample(this, 17);" style="position: relative;">
				<img src="{{ asset('_assets/img/website/goodies/examples/example17.png') }}" alt="example21" />
			</div>
		</div>
	</div>
</div>
<div class="small-4 column mobileFunction">
	<div class="content-holder">
		<div class="content">
		<div class="contentHeader headerBlue">
		    <span>Scanned Badges</span>
		    <a href="/badges" class="headerLink white_link web-page">More</a>
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
		</div></div>
	</div>
</div>
<script type="text/javascript">
	var habbo = "optra";
	var action = 1;

	$("#altForm").keypress(function(e) {
      if(e.which == 13) {
          setHabbo();
      }
    });

	var generate = function() {
		$('#alt_display').fadeOut(function() {
			$('#height_display').fadeIn();
			$('#alt_display').attr('src', urlRoute.getBaseUrl() + 'goodies/alteration/' + escape(habbo) + '/' + action);

			$('#alt_display').on('load', function(){
				$('#height_display').fadeOut(function () {
					$('#alt_display').fadeIn();
				});
			});
		});
	}

	var setHabbo = function() {
		habbo = $('#goodie-form-habbo').val();

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
