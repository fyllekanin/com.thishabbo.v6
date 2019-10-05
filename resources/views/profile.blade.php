<script>urlRoute.setTitle("TH - Profile");</script>

<style>
canvas{display:block}
h1 {
  position: absolute;
  top: 20%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
  font-family: "Source Sans Pro";
  font-size: 5em;
  font-weight: 900;
  -webkit-user-select: none;
  user-select: none;
}
</style>

<!-- SYSTEM LOADED CSS FOR USERBARS -->
@if(count($userbars_css))
    <style type="text/css">
    @foreach($userbars_css as $userbars_css)
        {!! $userbars_css !!}
    @endforeach
    </style>
@endif

@if($user['userid'] == Auth::user()->userid)
<div id="edit_profile" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
    <button id="editbutton" onclick="makeStickersDraggable();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Edit</button>
</div>
<div id="add_sticker" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000; display: none;">
    <button id="addstickerbutton" onclick="openAdd();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Add Stickers</button>
    <button id="clearstickerbutton" onclick="clearStickers();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Clear Stickers</button>
    <button id="stopeditingbutton" onclick="stopEditing();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Save</button>
</div>
@endif
@if($can_ban_user)
    <div class="reveal" id="ban_user" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
     	<h5>Banning a User</h5>
      	<p>When banning a user, you are effectively revoking their access from logging in. They will still be able to use the website.</p>
      	<fieldset>
        	<div class="medium-12 column">
          		<label for="ban_user_time">Length of ban</label>
      			<select id="ban_user_time" class="login-form-input">
          			<option value="86400">1 day</option>
          			<option value="172800">2 days</option>
          			<option value="604800">1 week</option>
          			<option value="2419200">1 month</option>
          			<option value="0">Permanent</option>
      			</select>
          		<label for="ban_user_reason">Reason for ban</label>
          		<input type="text" id="ban_user_reason" placeholder="Reason..." class="login-form-input"/>
        	</div>
        	<div class="medium-12 column">
          		<button class="post-button" onclick="banUser();">Ban</button>
        	</div>
      	</fieldset>
      	<button class="close-button" data-close aria-label="Close modal" type="button">
        	<span aria-hidden="true">&times;</span>
      	</button>
    </div>
@endif
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
    </button>				  		</div>

</div>
@if(Auth::check())
	@include('profile.stickers')
@endif

		<div class="small-12 column">
	  	<div class="content-holder" style="position: relative;">
            <!--@if($user['userid'] == 122)
                <h1>Happy Birthday Andy!</h1>
                <canvas id="birthday" style="width: 100%; height: 500px"></canvas>
            @endif-->
	    		<div class="content contentpadding">
	  				<span>
						<a href="/home" class="bold web-page">Forum Home</a>
						<i class="fa fa-angle-double-right" aria-hidden="true"></i>
						<span>
							<a href="/search" class="bold web-page">Members</a>
							<i class="fa fa-angle-double-right" aria-hidden="true"></i>  User Profile: {!! $user['clean_username'] !!}
						</span>
					</span>
	    		</div>
	  	</div>
	</div>


<div id="profile">
    <div id="stickers-wrapper" data-edit-mode="1">
    </div>
<div class="">
    <div class="medium-4 column">
    	<div class="content-holder">
    		<div class="profile-header" style="background-image: url('/_assets/img/website/headers/2.jpg'); background-position: center center;">
    			<div class="profile-avatar" style="background-image: url({{ $user['avatar'] }}); background-size: 100%; background-repeat: no-repeat;"></div>
    			<br>
    			<h4>{!! $user['username'] !!}</h4>
    		</div>
    	</div>
    </div>
    <div class="medium-8 column">
    	<div class="content-holder">
    		<div class="profile-header" style="background-image: url({{ $user['header'] }}); background-position: center center;">
    			<div class="profile-buttons">
    				<p><a href="/profile/{!! $user['clean_username'] !!}/followers" class="web-page" style="color:#fff !important;">Followers<br>{{ $user['followers'] }}</a></p>
    				<p><a href="/profile/{!! $user['clean_username'] !!}/following" class="web-page" style="color:#fff !important;">Following<br>{{ $user['following'] }}</a></p>
	            </div>
    		</div>
    	</div>
    </div>
    <div class="medium-4 column">
    	<div class="content-holder">
	        <div class="content">
	        	<table class="responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>Latest Activity</th>
	    	    		</tr>
	    	    		@foreach($recentactivity as $activity)
	    	    		<tr>
	    	    			<td><i class="fa fa-comment"></i> {{ $activity['time'] }} &raquo; {!! $user['username'] !!} {!! $activity['message'] !!}</td>
	    	    		</tr>
	    	    		@endforeach
	    	    	</tbody>
	        	</table>
	        </div>
	    </div>
	    <div class="content-holder">
	        <div class="content">
	        	<table class="profile-table responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>Quick Links</th>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><i class="fa fa-users"></i> <a href="/search?criteria=&type=post&searchforum=-1&from=all&newerolder=newer&sort=DESC&user={{ $user['clean_username'] }}" class="web-page">Find Users Posts</a></td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><i class="fa fa-users"></i> <a href="/search?criteria=&type=thread&searchforum=-1&from=all&newerolder=newer&sort=DESC&user={{ $user['clean_username'] }}" class="web-page">Find Users Threads</a></td>
	    	    		</tr>
	    	    		@if(Auth::check() && Auth::user()->userid != $user['userid'])
	    	    		<tr>
	    	    			<td><i class="fa fa-user"></i>
	    	    				<a onclick="toggleFollow()">
	    	    					@if($isFollowing)
	    	    						Unfollow
	    	    					@else
	    	    						Follow
	    	    					@endif
	    	    				</a>
	    	    			</td>
	    	    		</tr>
	    	    		@endif
	    	    		<tr>
	    	    			<td><i class="fa fa-envelope"></i> <a href="/usercp/pm?userid={{ $user['userid'] }}" class="web-page">Private Message</a></td>
	    	    		</tr>
	    	    	</tbody>
	    	    </table>
	    	</div>
	    </div>
	    @if(count($latest_visitors) > 0)
			<div class="content-holder">
	                <div class="content">
	    				<div class="profile_bio">
	    					@foreach($latest_visitors as $latest_visitor)
	    				    	<a href="/profile/{{ $latest_visitor['username'] }}/page/1" class="web-page">
	    				    		<div class="last_online hover-box-info" style="background-image: url({{ $latest_visitor['avatar'] }});" title="{{ $latest_visitor['username'] }} {{ $latest_visitor['time'] }}"></div>
	    				    	</a>
	    				    @endforeach
	    				</div>
	            </div>
			</div>
		@endif
    </div>
    <div class="medium-4 column">

		@if(Auth::check() AND Auth::user()->userid != $user['userid'])
			<div class="content-holder">
        		<div class="mainEditor">
            		<div class="content-ct">
						@if($verified===1)
							<textarea id="profile_editor" style="height: 100px;"></textarea>
							<br />
				      		<button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="postVisitorMessage();">Post</button>
              			@else
              				<p>You must verify your habbo to use this function! <a href="/usercp/habbo" class="web-page">Click here to verify!</a></p>
              			@endif
            	</div>
					</div>
			</div>
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
						<div class="profile_visitor">
							@if(Auth::check())
								@if(Auth::user()->userid == $user['userid'])
									<center>Sorry! Nobody has been writing anything on your profile! Why don't you go to someone else's profile and write something cute to them?</center>
								@else
									<center>This user does not have any messages yet! Be the first to say hello!</center>
								@endif
							@else
								<center>This user does not have any messages yet! Be the first to say hello!</center>
							@endif
						</div>
					</div>
			</div>

			<div class="profile_visitor_messages"></div>

		@endif

		<div class="content-holder">
				<div class="content">
			    	{!! $pagi !!}
				</div>
		</div>


    </div>
    <div class="medium-4 column">
    	@if(!$accolade_count == 0)
    	<div class="content-holder">
	        <div class="content">
	        	<table class="profile-table responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>ThisHabbo Accolades</th>
	    	    		</tr>
	    	    		@foreach($users_accolades as $accolade)
	    				    <tr>
		        				<th>{!! $accolade !!}</th>
	    	    			</tr>
	    				@endforeach
	    	    	</tbody>
	    	    </table>
	    	</div>
	    </div>
	    @endif
	    <div class="content-holder">
	        <div class="content">
	        	<table class="profile-table responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th colspan="2">Community</th>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Shop Items Owned:</b></td>
	    	    			<td>{{ number_format($user['shop_owned']) }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Default Theme:</b></td>
	    	    			<td>{{ $user['theme'] }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Referrals:</b></td>
	    	    			<td>{{ number_format($user['referrals']) }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Likes:</b></td>
	    	    			<td>{{ number_format($user['likes']) }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Threads Created:</b></td>
	    	    			<td>{{ number_format($user['threads']) }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Posts Created:</b></td>
	    	    			<td>{{ number_format($user['posts']) }}</td>
	    	    		</tr>
						<tr>
	    	    			<td><b><i class="fa fa-user"></i> XP Level:</b></td>
	    	    			<td>{{ $user['level_name'] }} ({{ $user['level_pro'] }}%)</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> DJ Likes:</b></td>
	    	    			<td>{{ number_format($user['djlikes']) }}</td>
	    	    		</tr>
	    	    	</tbody>
	    	    </table>
	    	</div>
	    </div>
	    <div class="content-holder">
	        <div class="content">
	        	<table class="responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th colspan="2">About {!! $user['username'] !!}</th>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> ThisHabbo ID:</b></td>
	    	    			<td>#{{ $user['userid'] }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Location:</b></td>
	    	    			<td>{{ $user['country'] }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Habbo ID:</b></td>
	    	    			<td>{{ $user['habbo'] }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Last Active:</b></td>
	    	    			<td>{{ $user['lastactivity'] }}</td>
	    	    		</tr>
	    	    		<tr>
	    	    			<td><b><i class="fa fa-user"></i> Registered:</b></td>
	    	    			<td>{{ $user['joined'] }}</td>
	    	    		</tr>
	    	    		@if($user['bio'] != "")
	    	    			<tr>
	    	    				<td colspan="2"><b><i class="fa fa-user"></i> Bio:</b></td>
	    	    			</tr>
	    	    			<tr>
	    	    				<td colspan="2">{!! $user['bio'] !!}</td>
	    	    			</tr>
	    	    		@endif
	    	    	</tbody>
	        	</table>
	        </div>
	    </div>
    </div>
</div>


<script type="text/javascript">

// @if($user['userid'] == 122)
//     // helper functions
//     const PI2 = Math.PI * 2
//     const random = (min, max) => Math.random() * (max - min + 1) + min | 0
//     const timestamp = _ => new Date().getTime()
//
//     // container
//     class Birthday {
//       constructor() {
//         this.resize()
//
//         // create a lovely place to store the firework
//         this.fireworks = []
//         this.counter = 0
//
//       }
//
//       resize() {
//         this.width = canvas.width = window.innerWidth
//         let center = this.width / 2 | 0
//         this.spawnA = center - center / 4 | 0
//         this.spawnB = center + center / 4 | 0
//
//         this.height = canvas.height = window.innerHeight
//         this.spawnC = this.height * .1
//         this.spawnD = this.height * .5
//
//       }
//
//       onClick(evt) {
//          let x = evt.clientX || evt.touches && evt.touches[0].pageX
//          let y = evt.clientY || evt.touches && evt.touches[0].pageY
//
//          let count = random(3,5)
//          for(let i = 0; i < count; i++) this.fireworks.push(new Firework(
//             random(this.spawnA, this.spawnB),
//             this.height,
//             x,
//             y,
//             random(0, 260),
//             random(30, 110)))
//
//          this.counter = -1
//
//       }
//
//       update(delta) {
//         ctx.globalCompositeOperation = 'hard-light'
//         ctx.fillStyle = `rgba(20,20,20,${ 7 * delta })`
//         ctx.fillRect(0, 0, this.width, this.height)
//
//         ctx.globalCompositeOperation = 'lighter'
//         for (let firework of this.fireworks) firework.update(delta)
//
//         // if enough time passed... create new new firework
//         this.counter += delta * 3 // each second
//         if (this.counter >= 1) {
//           this.fireworks.push(new Firework(
//             random(this.spawnA, this.spawnB),
//             this.height,
//             random(0, this.width),
//             random(this.spawnC, this.spawnD),
//             random(0, 360),
//             random(30, 110)))
//           this.counter = 0
//         }
//
//         // remove the dead fireworks
//         if (this.fireworks.length > 1000) this.fireworks = this.fireworks.filter(firework => !firework.dead)
//
//       }
//     }
//
//     class Firework {
//       constructor(x, y, targetX, targetY, shade, offsprings) {
//         this.dead = false
//         this.offsprings = offsprings
//
//         this.x = x
//         this.y = y
//         this.targetX = targetX
//         this.targetY = targetY
//
//         this.shade = shade
//         this.history = []
//       }
//       update(delta) {
//         if (this.dead) return
//
//         let xDiff = this.targetX - this.x
//         let yDiff = this.targetY - this.y
//         if (Math.abs(xDiff) > 3 || Math.abs(yDiff) > 3) { // is still moving
//           this.x += xDiff * 2 * delta
//           this.y += yDiff * 2 * delta
//
//           this.history.push({
//             x: this.x,
//             y: this.y
//           })
//
//           if (this.history.length > 20) this.history.shift()
//
//         } else {
//           if (this.offsprings && !this.madeChilds) {
//
//             let babies = this.offsprings / 2
//             for (let i = 0; i < babies; i++) {
//               let targetX = this.x + this.offsprings * Math.cos(PI2 * i / babies) | 0
//               let targetY = this.y + this.offsprings * Math.sin(PI2 * i / babies) | 0
//
//               birthday.fireworks.push(new Firework(this.x, this.y, targetX, targetY, this.shade, 0))
//
//             }
//
//           }
//           this.madeChilds = true
//           this.history.shift()
//         }
//
//         if (this.history.length === 0) this.dead = true
//         else if (this.offsprings) {
//             for (let i = 0; this.history.length > i; i++) {
//               let point = this.history[i]
//               ctx.beginPath()
//               ctx.fillStyle = 'hsl(' + this.shade + ',100%,' + i + '%)'
//               ctx.arc(point.x, point.y, 1, 0, PI2, false)
//               ctx.fill()
//             }
//           } else {
//           ctx.beginPath()
//           ctx.fillStyle = 'hsl(' + this.shade + ',100%,50%)'
//           ctx.arc(this.x, this.y, 1, 0, PI2, false)
//           ctx.fill()
//         }
//
//       }
//     }
//
//     let canvas = document.getElementById('birthday')
//     let ctx = canvas.getContext('2d')
//
//     let then = timestamp()
//
//     let birthday = new Birthday
//     window.onresize = () => birthday.resize()
//     document.onclick = evt => birthday.onClick(evt)
//     document.ontouchstart = evt => birthday.onClick(evt)
//
//       ;(function loop(){
//       	requestAnimationFrame(loop)
//
//       	let now = timestamp()
//       	let delta = now - then
//
//         then = now
//         birthday.update(delta / 1000)
//
//
//       })()
// @endif

$(document).ready(function(){
	$(document).foundation();
    console.log('{{ $background }}');
    $('#profile').css('background-image','url("{{ $background }}")');
});

var openAdd = function() {
    $('#sticker_collection').fadeIn();
    $('#addstickerbutton').html("Close");
    $('#addstickerbutton').attr('onclick','closeAdd();');
}

var closeAdd = function() {
    $('#sticker_collection').fadeOut();
    $('#addstickerbutton').html("<i class='fa fa-sticky-note' aria-hidden='true'></i> Add Stickers");
    $('#addstickerbutton').attr('onclick','openAdd();');
}



var postVisitorMessage = null;
var deleteVisitorMessages = null;
var temp_userid = {{ $user['userid'] }};
var toggleFollow = null;
var banUser = null;
var modActions = null;
var reportVm = null;
var reportVisitorMessage = null;
var vmid = 0;

window.twttr = (function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0],
	t = window.twttr || {};
	if (d.getElementById(id)) return t;
	js = d.createElement(s);
	js.id = id;
	js.src = "https://platform.twitter.com/widgets.js";
	fjs.parentNode.insertBefore(js, fjs);

	t._e = [];
	t.ready = function(f) {
	t._e.push(f);
	};

	return t;
}(document, "script", "twitter-wjs"));

@if(Auth::check())

	reportVm = function(xvmid) {
		vmid = xvmid;
		$('#report_visitormessage').foundation('open');
	}

	reportVisitorMessage = function() {
		var reason = $('#reason_for_report').val();
        var pagenumber = "{{ $current_page }}";
		$.ajax({
			url: urlRoute.getBaseUrl() + 'profile/vm/report',
			type: 'post',
			data: {vmid:vmid, reason:reason, pagenumber:pagenumber},
			success: function(data) {
				if(data['response'] == true) {
					$('#reason_for_report').val("");
            		urlRoute.ohSnap('<span class=\"alert-title\">User Reported!</span><br />Your report has been submitted!', 'green');
            		$('#report_visitormessage').foundation('close');
				} else {
					urlRoute.ohSnap(data['message'], 'red');
				}
			}
		});
	}

	@if($can_ban_user || $can_edit_user || $can_delete_vm)
		modActions = function() {
			var action = $('#mod-form-action').val();

			switch(action) {
				case "ban_user":
					$('#ban_user').foundation('open');
				break;
				case "edit_user":
					urlRoute.loadPage("{{$edit_url}}");
				break;
				case "delete_vms":
					deleteVisitorMessages();
				break;
			}
		}
	@endif

	@if(Auth::user()->userid != $user['userid'])
		$(document).ready(function() {
		  $(document).foundation();
            var wbbOpt = {
                buttons:"bold,italic,underline,|,link,img,removeformat"
            }
		    $("#profile_editor").wysibb(wbbOpt);
		});

        @if($can_ban_user)
            banUser = function() {
            	var reason = $('#ban_user_reason').val();
              	var time = $('#ban_user_time').val();

              	$.ajax({
                	url: urlRoute.getBaseUrl() + 'admincp/users/ban',
                	type: 'post',
                	data: {temp_userid:temp_userid, time:time, reason:reason},
                	success: function(data) {
                  		$('#ban_user').foundation('close');
                  			if(data['response'] == true) {
                    		urlRoute.loadPage("/profile/{{$user['clean_username']}}/page/1");
                    		urlRoute.ohSnap('<span class=\"alert-title\">Banhammer Initiated!</span><br />The user has been banned!', 'green');
                  		} else {
                    		urlRoute.ohSnap('<span class=\"alert-title\">Oops!</span><br />Something went wrong!', 'red');
                  		}
                	}
              	});
          	}
        @endif

        var isFollowing = {{$isFollowing}};

        toggleFollow = function() {
            var userid = {{ $user['userid'] }};
            $.ajax({
				url: urlRoute.getBaseUrl() + 'profile/toggleFollow',
				type: 'post',
                data: {userid:userid},
				success: function(data) {
					if(data['response'] == true) {
						$('#followBtn').html(data['btnText']);

						urlRoute.ohSnap('Success, you ' + data['noticeText'] + '!','green');
					} else {
						urlRoute.ohSnap(data['message'],'red');
					}
				}
			});
        }

    @if($verified===1)
		postVisitorMessage = function() {
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
						urlRoute.ohSnap('<span class=\"alert-title\">Love you!</span><br />Success, you have successfully posted a visitor message!','green');
					} else {
						urlRoute.ohSnap(data['message'],'red');
					}
				}
			});
		}
    @endif
	@endif

	@if($can_delete_vm)
		deleteVisitorMessages = function() {
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
		    			urlRoute.ohSnap('<span class=\"alert-title\">Trashbin Initiated!</span><br />Those messages have been deleted!', 'green');
		    			urlRoute.loadPage('/profile/{!! $user['clean_username'] !!}/page/1');
		    		}
		    	});
		    }
		}
	@endif
@endif

var destroy = function() {
    if(banUser) {
        banUser = null;
    }
    if(postVisitorMessage) {
	    postVisitorMessage = null;
    }
    if(deleteVisitorMessages) {
	    deleteVisitorMessages = null;
    }
    if(toggleFollow) {
        toggleFollow = null;
    }
    reportVm = null;
    reportVisitorMessage = null;
}
</script>
