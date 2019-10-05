<script> urlRoute.setTitle("TH - Jobs");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="medium-8 column">

    <div class="content-holder">
        <div class="content">
	<div class="contentHeader headerRed">
            Join ThisHabbo
            </div>
          <div class="content-ct">
		  	<strong>ThisHabbo</strong> has remained strong for the last 8 years, but to continue, we'll need <strong>your</strong> help. Have a look at what we have available below and see what you want to apply for. Remember, you can apply for multiple roles, so don't feel as if you should only apply for one, the more - the merrier!
		  	<br /><br />
		  	As of 2017, ThisHabbo moved to Discord because it's smarter and safer for our users. Don't worry, we can help you if you're having problems setting one up!
		  </div>

		  <div class="content-ct">
			  @if(Auth::check())
		  	  <fieldset>
			  	<div class="row">
			  	  <div class="small-12 medium-6 column">
				    <label for="job-form-username">Forum Name</label>
				    <input type="text" id="job-form-username" value="{{ Auth::user()->username }}" disabled="disabled" class="login-form-input"/>
				  </div>
				  <div class="small-12 medium-6 column">
				    <label for="job-form-habbo">Habbo Username</label>
				    <input type="text" id="job-form-habbo" placeholder="Habbo.com Username" class="login-form-input"/>
				  </div>
				  <div class="small-12 medium-6 column">
				    <label for="job-form-discord">Discord</label>
				    <input type="text" id="job-form-discord" placeholder="Discord Username and ID, in the format name#0000" class="login-form-input"/>
				  </div>
				  <div class="small-12 medium-6 column">
				    <label for="job-form-job">I'm interested in... (don't worry we'll place you somewhere!)</label>
			        <select id="job-form-job" class="login-form-input">
					<option value="Events Host">Events</option>
				      	<option value="Graphics Artist">Graphics</option>
				      	<option value="Media Reporter">Media</option>
				      	<option value="Quests & Tutorials">Quests &amp; Tutorials Reporter</option>
				      	<option value="Radio DJ">Radio</option>
				      	<option value="Any" selected="">I'm not sure - choose the best for me</option>
			        </select>
				  </div>
				  <div class="small-12 medium-6 column">
				    <label for="job-form-country">Country</label>
				    <input type="text" id="job-form-country" class="login-form-input"/>
				  </div>
				  <div class="small-12 column">
				    <label for="job-form-why">Tell us a little bit' about yourself and why you want to work here!</label>
				    <textarea id="job-form-why" placeholder="Im looking to be some kind of Radio DJ. I have a microphone and music. I am loud, confident and would love to DJ on ThisHabbo.com. Also, can I be a news reporter? I love writing..." class="login-form-input"></textarea>
				  </div>
				  <div class="small-12 column"><br>
				    <button class="pg-red headerRed floatright gradualfader fullWidth topBottom" onclick="sendApp();">Apply <i class="fa fa-check" aria-hidden="true"></i></button>
				  </div>
				</div>
			  </fieldset>
			@else
			<br />
			<div class="alert alert-danger">
            	<p><b>You must be logged in to submit a job application!</b></p>
        	</div>
			<br />
			<a href="/login" class="web-page"> <button id="signin-now" class="pg-red headerRed floatright gradualfader fullWidth">Sign in now</button></a>
			@endif
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
	var badgeError = function(image) {
		image.onerror = "";
		image.src = '{{ asset('_assets/img/website/badge_error.gif') }}';
		return true;
	};

	function sendApp() {
	var email = $('#job-form-email').val();
	var discord = $('#job-form-discord').val();
	var job = $('#job-form-job').val();
	var country = $('#job-form-country').val();
	var why = $('#job-form-why').val();
	var habbo = $('#job-form-habbo').val();
	$.ajax({
		url: urlRoute.getBaseUrl() + 'jobs/post',
		type: 'post',
		data: {email:email, discord:discord, job:job, country:country, why:why, habbo:habbo},
		success: function(data) {
			if(data['response'] == true) {
            	urlRoute.ohSnap('Request Sent!', 'green');
                urlRoute.loadPage('/forum/thread/' + data['threadid'] + '/page/1');
			} else {
				urlRoute.ohSnap(data['message'], 'red');
			}
		}
	});
}

 	var destroy = function() {
 		badgeError = null;
 		sendApp = null;
    }
</script>
