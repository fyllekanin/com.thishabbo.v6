<?php
	$conv = \App\Helpers\UserHelper::getConverstation($_GET['userid']);
?>

  	<div class="content-holder">
  		<div class="content">
		<div class="contentHeader headerRed">
			    Conversation with @if($conv['clean_username'] == '__blank__') User not found @else <a href="/profile/{{ $conv['clean_username'] }}" class="web-page" style="color: #ffffff; text-decoration: underline;">{{ $conv['clean_username'] }}</a>@endif
			</div>
			<div class="content-ct converstation_container">
			  	@if(!array_key_exists('messages', $conv) || count($conv['messages']) == 0)
			  		You and @if($conv['clean_username'] == '__blank__') User not found @else {{ $conv['clean_username'] }}@endif don't have messages between you, send him/her a message?
			  	@else
			  		@foreach($conv['messages'] as $message)
			  			<div style="padding: 2px; float: left; width: 100%; padding-right: 12px;">
				  			<div class="@if($message['me']) pm-part-me @else pm-part-other @endif">
				  				 {!! $message['content'] !!}
				  				 <br><br>
				  				<div style="float:right;"><i>{{ $message['dateline'] }}</i></div>
				  			</div>
							<div style="padding: 10px; @if($message['me']) float: right; @else float: left; @endif ">
					  		</div>
				  		</div>
			  		@endforeach
			  	@endif
			</div>
		</div>
	</div>
<div class="content-holder">
        <div class="mainEditor">
			@if($verified===1)<textarea id="private_message_editor" style="height: 100px; font-size:12px !important;"></textarea>
<br>
                    @if($conv['clean_username'] != '__blank__')<button class="pg-red headerRed fullWidth gradualfader" onclick="postMessage()">Post</button>@endif
                </center>
                @else
                <p>You must verify your habbo to use this function! <a href="/usercp/habbo" class="web-page">Click here to verify!</a></p>
           @endif</div>
		</div>
	</div>
	<script type="text/javascript">
  @if($verified === 1)
	var pm_editor = null;
	$(document).ready(function() {
			$(document).foundation();
			$('.converstation_container').slimScroll({
				height: '500px',
        alwaysVisible: true,
        railVisible: true
    	});
			$('.new-message-{{ $conv['userid'] }}').fadeOut();
	    $("#private_message_editor").wysibb();
	    pm_editor = $('.wysibb-body').keyup(function(e){
	      if(e.key === 's' && e.altKey) {
	        e.preventDefault();
	        postMessage();
	      }
	    });
	});
	var postMessage = function() {
		var content = $('#private_message_editor').bbcode();
		var userid = {{ $conv['userid'] }};
		$.ajax({
			url: urlRoute.getBaseUrl() + 'usercp/pm/post',
			type: 'post',
			data: {content:content, userid:userid},
			success: function(data) {
				if(data['response'] === true) {
					urlRoute.ohSnap('Message sent to {!! $conv['username'] !!}!', 'green');
					urlRoute.loadPage('/usercp/pm?userid={{ $conv['userid'] }}');
				} else {
					urlRoute.ohSnap('Can\'t post an empty message!', 'red');
				}
			}
		});
	}
  @endif
	var destroy = function() {
		pm_editor = null;
		postMessage = null;
	}
</script>
