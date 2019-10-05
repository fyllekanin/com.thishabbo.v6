<script> urlRoute.setTitle("TH - Request Line");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Request Line</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
  <div class="content-holder">
      <div class="content">
      <div class="contentHeader headerBlue">
        <span>Request Line</span>
      </div>
          Fed up with the same old same old? Same! Use this form to request any song you'd like. Whether it's pop, rock or classic! ThisHabbo tries to provides the best music and that means playing all genres. Not only can you request songs, but you can also use this to get in contact with the Live DJ.<br />
          <br />
          Anyone purposely abusing the Request Line will be immediately banished from the website and forum, This is why your IP Address is logged for security reasons.
          <br /><br />
          <fieldset>
            @if(Auth::check())
              <input type="text" id="request_name" value="{{ Auth::user()->username }}" class="login-form-input"/>
            @else
              <legend>Name:</legend>
              <input type="text" id="request_name" placeholder="Name..." class="login-form-input"/>
            @endif
            <br>
            <textarea class="login-form-input" placeholder="Request message" id="request_message"></textarea>
            <br>

            <button class="pg-red headerBlue gradualfader fullWidth topBottom" onclick="requestSend();">Submit <i class="fa fa-check" aria-hidden="true"></i></button>

          </fieldset>
        </div>
      </div>
    </div>
</div>
<div class="small-4 mobileFunction column">
  <div class="content-holder">
    <div class="content">
     <div class="contentHeader headerRed">
            Scanned Badges
        </div>
      <div class="content-ct ct-center">
        <div id="list_badges">
          @foreach($badges as $badge)
          <div class="small-2 column">
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

<script type="text/javascript">
    var requestSend = function() {
        var name = $('#request_name').val();
	    var message = $('#request_message').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'radio/request',
            type: 'post',
            data: {name:name, message:message},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('<span class=\"alert-title\">Woohoo!</span><br />Your request has been sent!', 'green');
                    $('#request_message').val("");
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

	var badgeError = function(image) {
		image.onerror = "";
		image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
		return true;
	};

	var destroy = function() {
        requestSend = null;
    	badgeError = null;
  	}
</script>
