<script> urlRoute.setTitle("TH - Conversation");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="reveal" id="report_visitormessage" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <h5>Report visitor message.</h5>
    <p>Don't report any visitor message without a good reason, unnecessary reports can be punished!</p>
    <fieldset>
        <legend>Reason for report: </legend>
        <input type="text" id="reason_for_report" placeholder="Reason..." class="login-form-input"/>
        <button class="pg-left pg-grey" style="margin-top: 0.5rem;" onclick="reportVisitorMessage();">Report</button>
    </fieldset>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

	<div class="small-12 column">
	  	<div class="content-holder">
	    		<div class="content contentpadding">
	  				<span>
						<a href="/home" class="bold web-page">Forum Home</a>
						<i class="fa fa-angle-double-right" aria-hidden="true"></i>
						<span>
							<a href="/members" class="bold web-page">Members</a>
							<i class="fa fa-angle-double-right" aria-hidden="true"></i> <span>Conversation between {{ $user['username1']}} and {{ $user['username2'] }}</span>
						</span>
					</span>
	    		</div>
	  	</div>
	</div>

@if(isset($can_infract_vm))
<div class="reveal" id="warning_comment" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Moderation</h4>
    </div>
    <div class="modal-body">
        <p><b>Reason:</b></p>
        <p>
            <select id="inputReasonif" class="login-form-input">
                @foreach($infraction_reasons as $reason)
                    <option value="{{ $reason['infractionrsnid'] }}">{{ $reason['reason'] }} - {{ $reason['points'] }} Point(s)</option>
                @endforeach
            </select>
        </p>
        <br />
        <p>
            <b>Infraction/Warning:</b></p>
        <p>
            <select id="inputTypeif" class="login-form-input">
                <option value="1" selected="">Infraction</option>
                <option value="0">Warning</option>
                <option value="2">Verbal Warning</option>
            </select>
        </p>
        <br />
        <div class="form-group">
            <label for="comment">Prewritten PM: <i>(Can be edited)</i></label>
            <textarea id="inputPmif" class="login-form-input" rows="10">
[b]Dear {USER},[/b]
You have received a {INFRACTION/WARNING} at ThisHabboForum.

Reason:
-------
{INFRACTION/WARNING HERE}
-------

[quote]{EVIDENCE}[/quote]

[b]All the best,
ThisHabboForum[/b]
            </textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button id="report" class="pg-red headerRed floatright gradualfader" onclick="giveWarnInf();" style="margin-left: 5px;">Add <i class="fa fa-check"></i></button>
        <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>

    </div>
</div>
@endif

<div class="small-12 medium-12 large-9 column">
            <div class="contentHeader headerRed">
                <span>Conversation</span>
            </div>
@if(Auth::check())
@if(Auth::user()->userid == $user['userid1'] OR Auth::user()->userid == $user['userid2'])
    <div class="content-holder">
        <div class="content">
			<div class="content-ct">
				<textarea id="profile_editor" style="height: 100px;"></textarea>
				<br>
		      	<button class="pg-blue headerRed gradualfader fullWidth topBottom" onclick="postVisitorMessage();">Post</button>
			</div>
		</div>
	</div>
@endif
@endif


	@if(count($visitormessages) > 0)
		<div class="profile_visitor_messages">
			@foreach($visitormessages as $visitormessage)
				{!! $visitormessage !!}
			@endforeach
		</div>
	@else
		<div class="content-holder" id="nomessages">
				<div class="content">
					<center>There are no messages between these two users</center>
				</div>
		</div>
	@endif

  	<div class="content-holder">
      	<div class="content">
    		{!! $pagi !!}
      	</div>
  	</div>
</div>

<div class="small-12 medium-12 large-3 column">
				<div class="contentHeader headerBlue">
			    <a href="/badges" class="web-page headerLink white_link">More</a>
			    Scanned Badges
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
	@if($is_mod)
		<div class="content-holder">
	        <div class="content">
				<button class="fullWidth pg-black headerBlack gradualfader" style="width: 100%;" onclick="deleteVisitorMessages();">Delete Visitor Messages</button>
			</div>
		</div>
	@endif
</div>

<script type="text/javascript">
var deleteVisitorMessages = null;
var postVisitorMessage = null;
var reportVm = null;
var reportVisitorMessage = null;
@if(Auth::check())
	reportVm = function(xvmid) {
		vmid = xvmid;
		$('#report_visitormessage').foundation('open');
	}

	reportVisitorMessage = function() {
		var reason = $('#reason_for_report').val();
		$.ajax({
			url: urlRoute.getBaseUrl() + 'profile/vm/report',
			type: 'post',
			data: {vmid:vmid, reason:reason},
			success: function(data) {
				if(data['response'] == true) {
					$('#reason_for_report').val("");
            		urlRoute.ohSnap('<span class=\"alert-title\">Report Sent!</span><br />Congratulations, your visitor message report has been submitted!', 'green');
            		$('#report_visitormessage').foundation('close');
				} else {
					urlRoute.ohSnap(data['message'], 'red');
				}
			}
		});
	}

	@if($can_infract_vm)
	var vmid = 0;

	infWarn = function(xvmid) {
		vmid = xvmid;
		$('#warning_comment').foundation('open');
	}

	giveWarnInf = function() {
		var reason = $('#inputReasonif').val();
        var type = $('#inputTypeif').val();
        var pm = $('#inputPmif').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'conversations/moderation/inf/war',
            type: 'post',
            data: {reason:reason, type:type, pm:pm, vmid:vmid},
            success: function(data) {
                if(data['response'] == true) {
                    $('#warning_comment').foundation('close');
                    urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />Your infraction or warning has been submitted!', 'green');
                    } else {
                    urlRoute.ohSnap(data['message'], 'red');
                    }
                }
        });
    }
    @endif

	@if(Auth::user()->userid == $user['userid1'] OR Auth::user()->userid == $user['userid2'])
		$(document).ready(function() {
		  $(document).foundation();

		  $("#profile_editor").wysibb();
		});

		var postVisitorMessage = function() {
			var message = $('#profile_editor').bbcode();
			var userid = {{ $user['userid'] }};

			$.ajax({
				url: urlRoute.getBaseUrl() + 'profile/post/vm',
				type: 'post',
				data: {userid:userid, message:message},
				success: function(data) {
					if(data['response'] == true) {
						$('.profile_visitor_messages').prepend(data['new_visitor']);
						$('#nomessages').fadeOut();
						$('#profile_editor').htmlcode("");
						urlRoute.ohSnap('Success, you posted!','green');
					} else {
						urlRoute.ohSnap(data['message'],'red');
					}
				}
			});
		}
	@endif
	@if($is_mod)
		var deleteVisitorMessages = function() {
			var vmids = [];
			$('input:checkbox.vm_checkbox').each(function() {
		      if(this.checked) {
		      	vmids.push($(this).val());
		      }
		    });

		    if(confirm("Sure you wanna delete these messages?") && vmids.length > 0) {
		    	$.ajax({
		    		url: urlRoute.getBaseUrl() + 'staff/mod/delete/vms',
		    		type: 'post',
		    		data: {vmids:vmids},
		    		success: function(data) {
		    			urlRoute.ohSnap('Messages deleted!', 'green');
		    			urlRoute.loadPage('/conversation/{{ $user['username1'] }}/{{ $user['username2'] }}/page/1');
		    		}
		    	});
		    }
		}
	@endif
@endif

	var badgeError = function(image) {
		image.onerror = "";
		image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
		return true;
	};

	var destroy = function() {
    	postVisitorMessage = null;
    	deleteVisitorMessages = null;
    	badgeError = null;
    	reportVm = null;
    	reportVisitorMessage = null;
  	}
</script>
