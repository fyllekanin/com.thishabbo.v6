<script>urlRoute.setTitle("TH - Clan");</script>

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

@if($pendingInvite == true || $clan->memberid_owner == Auth::user()->userid || $clan->memberid_2 == Auth::user()->userid || $clan->memberid_3 == Auth::user()->userid)
<div id="pageOptions" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
	<button id="openmenubutton" onclick="openMenu();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Clan Menu</button>
</div>
<div id="page_menu" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000; display: none;">
	@if($clan->memberid_owner == Auth::user()->userid)
		<button id="addMemberButton" onclick="openAddMember();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Add Member</button>
		<a href="/clans/{{ $clan->groupname }}/edit/avatar" class="web-page"><button id="addstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Edit Avatar</button></a><br />
		<a href="/clans/{{ $clan->groupname }}/edit/header" class="web-page"><button id="addstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Edit Header</button></a><br />
		<button id="disbandClanButton" onclick="openDisbandClan();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Disband Clan</button>
	@endif

	@if($clan->memberid_2 == Auth::user()->userid || $clan->memberid_3 == Auth::user()->userid))
		<button id="leaveClanButton" onclick="leaveClan();" class="gradualfader pg-red headerGreen profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Leave Clan</button>
	@endif

	@if( $pendingInvite == true )
		<button id="inviteButton" onclick="openInvite();" class="gradualfader pg-red headerBlue profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Invite Options</button>
	@endif

	<button id="closemenubutton" onclick="closeMenu();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Close Menu</button>
</div>
@endif

@if($pendingInvite == true)
	<div class="reveal" id="inviteModal" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
	    <div class="modal-header">
	        <h4 class="modal-title">You have been invited!</h4>
	    </div>
	    <div class="modal-footer">
	    	<button class="pg-red headerGreen gradualfader fullWidth" onclick="acceptInvite();">Accept Invite</button><br /><br />
	    	<button class="pg-red headerRed gradualfader fullWidth" onclick="rejectInvite();">Reject Invite</button>
	    </div>
	</div>
@endif

@if($clan->memberid_owner == Auth::user()->userid)
	<div class="reveal" id="menu_addMember" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
	    <div class="modal-header">
	        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
	        <h4 class="modal-title">Add Member</h4>
	    </div>
	    <div class="modal-body">
	    	<p><b>Member's Username:</b></p>
	        <p>
	            <input type="text" id="addMember_username" class="login-form-input">
	        </p>
	    </div>
	    <div class="modal-footer">
	        <button class="pg-red headerRed gradualfader fullWidth" onclick="inviteMember();">Add Member</button>
	    </div>
	</div>

	<div class="reveal" id="modal_disbandClan" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
	    <div class="modal-header">
	        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
	        <h4 class="modal-title">Are you sure you want to disband {{ $clan->groupname }}</h4>
	    </div>
	    <div class="modal-footer">
	        <button class="pg-red headerRed gradualfader fullWidth" onclick="disbandClan();">Disband Clan</button><br /><br />
	        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
	    </div>
	</div>
@endif

	<div class="small-12 column">
	  	<div class="content-holder" style="position: relative;">
	    	<div class="content contentpadding">
	  			<span>
					<a href="/home" class="bold web-page">Forum Home</a>
					<i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<span>
						<a href="/clans" class="bold web-page">Clans</a>
						<i class="fa fa-angle-double-right" aria-hidden="true"></i>  Clans: {{ $clan->groupname }}
					</span>
				</span>
	    	</div>
	  	</div>
	</div>


    <div class="medium-4 column">
    	<div class="content-holder">
    		<div class="profile-header" style="background-image: url('/_assets/img/website/headers/2.jpg'); background-position: center center;">
    			<div class="profile-avatar" style="background-image: url('/_assets/img/clanAvatars/{{ $clan->avatar }}.gif'); background-size: 100%; background-repeat: no-repeat;"></div>
    			<br>
    			<h4>{{ $clan->groupname }}</h4>
    		</div>
    	</div>
    	@if(!$accolade_count == 0)
    	<div class="content-holder">
	        <div class="content">
	        	<table class="profile-table responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>Clan Accolades</th>
	    	    		</tr>
	    	    		@foreach($accolades as $accolade)
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
	        	<table class="responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>Clan Activity</th>
	    	    		</tr>
	    	    		@foreach($activity as $temp)
		    	    		<tr>
			        			<td><b><i class="fa fa-users"></i> {{ $temp['dateline'] }} »</b> <a href="/profile/{{ $temp['username_clean'] }}/page/1" class="web-page">{!! $temp['username'] !!}</a> {{ $temp['action'] }}</td>
		    	    		</tr>
	    	    		@endforeach
	    	    	</tbody>
	    	    </table>
	    	</div>
	    </div>
    	<div class="content-holder">
	        <div class="content">
	        	<table class="responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th>Name</th>
		        			<th>XP Contributed</th>
	    	    		</tr>
	    	    		<tr>
	    	    			<th>{!! $members['member1_name'] !!}</th>
	    	    			<td>{{ $members['member1_xpcount'] }}</td>
	    	    		</tr>
	    	    		@if($members['member2_name'] !== '__blank__')
	    	    		<tr>
	    	    			<th>{!! $members['member2_name'] !!}</th>
	    	    			<td>{{ $members['member2_xpcount'] }}</td>
	    	    		</tr>
	    	    		@endif
	    	    		@if($members['member3_name'] !== '__blank__')
	    	    		<tr>
	    	    			<th>{!! $members['member3_name'] !!}</th>
	    	    			<td>{{ $members['member3_xpcount'] }}</td>
	    	    		</tr>
	    	    		@endif
	    	    	</tbody>
	        	</table>
	        </div>
	    </div>
    </div>
    <div class="medium-8 column">
    	<div class="content-holder">
    		<div class="profile-header" style="background-image: url('/_assets/img/clanHeaders/{{ $clan->cover }}.gif'); background-position: center center;">
    			<div class="clan-buttons">
    				<p>Total EXP<br>{{ $clan->xpcount }}</p>
	            </div>
    		</div>
    	</div>
    	<div class="content-holder">
            <div class="content">
                <div class="contentHeader headerBlue">
                    {{ $clan->groupname }}'s Team
                </div>
                <div class="content-ct staff-list">
					<div class="small-12 medium-4 large-4 end column">
						<div class="user_holder">
							<div class="user_holder_avatar" style="background-image: url({!! $members['member1_avatar'] !!});"></div>
							<div class="user_holder_info">
								<div class="user_holder_username"><a href="/profile/{{ $members['member1']->username }}/page/1" class="web-page">{!! $members['member1_name'] !!}</a> <br>
								</div>
							</div>
						</div>
					</div>
					@if($members['member2_name'] !== '__blank__')
					<div class="small-12 medium-4 large-4 end column">
						<div class="user_holder">
							<div class="user_holder_avatar" style="background-image: url({!! $members['member2_avatar'] !!});"></div>
							<div class="user_holder_info">
								<div class="user_holder_username"><a href="/profile/{{ $members['member2']->username }}/page/1" class="web-page">{!! $members['member2_name'] !!}</a> <br>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($members['member3_name'] !== '__blank__')
					<div class="small-12 medium-4 large-4 end column">
						<div class="user_holder">
							<div class="user_holder_avatar" style="background-image: url({!! $members['member3_avatar'] !!});"></div>
							<div class="user_holder_info">
								<div class="user_holder_username"><a href="/profile/{{ $members['member3']->username }}/page/1" class="web-page">{!! $members['member3_name'] !!}</a> <br>
								</div>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
        </div>
    </div>

<script>
var openMenu = function() {
    $('#page_menu').fadeIn();
}

var openInvite = function() {
    $('#inviteModal').foundation('open');
}

var closeMenu = function() {
    $('#page_menu').fadeOut();
}

var openAddMember = function() {
	$('#menu_addMember').foundation('open');
}

@if($pendingInvite == true)

var rejectInvite = function() {
	$.ajax({
        url: urlRoute.getBaseUrl() + 'clans/post/invite/response',
        type: 'post',
        data: {clanid:{{ $clan->groupid }}, response:2},
        success: function(data) {
            if(data['response'] == true) {
            	urlRoute.loadPage("/clans/{{ $clan->groupname }}");
                urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />The invite has been rejected!', 'green');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
       	}
    });
}

var acceptInvite = function() {
	$.ajax({
        url: urlRoute.getBaseUrl() + 'clans/post/invite/response',
        type: 'post',
        data: {clanid:{{ $clan->groupid }}, response:1},
        success: function(data) {
            if(data['response'] == true) {
            	urlRoute.loadPage("/clans/{{ $clan->groupname }}");
                urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />The invite has been accepted!', 'green');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
       	}
    });
}

@endif

@if($clan->memberid_2 == Auth::user()->userid || $clan->memberid_3 == Auth::user()->userid)

var leaveClan = function() {
	$.ajax({
        url: urlRoute.getBaseUrl() + 'clans/post/leave',
        type: 'post',
        data: {clanid:{{ $clan->groupid }}},
        success: function(data) {
            if(data['response'] == true) {
            	urlRoute.loadPage("/clans/{{ $clan->groupname }}");
                urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />You have left the clan!', 'green');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
       	}
    });
}

@endif

@if($clan->memberid_owner == Auth::user()->userid)

var disbandClan = function() {
    $.ajax({
        url: urlRoute.getBaseUrl() + 'clans/post/disband',
        type: 'post',
        data: {clanid:{{ $clan->groupid }}},
        success: function(data) {
            if(data['response'] == true) {
                urlRoute.loadPage("/clans");
                urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />The clan has been disbanded!', 'green');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
       	}
    });
}

var inviteMember = function() {
	var username = $('#addMember_username').val();
    $.ajax({
        url: urlRoute.getBaseUrl() + 'clans/post/invite',
        type: 'post',
        data: {clanid:{{ $clan->groupid }}, username:username},
        success: function(data) {
            if(data['response'] == true) {
                $('#menu_addMember').foundation('close');
                urlRoute.ohSnap('<span class=\"alert-title\">Pew pew pew!</span><br />The invite has been sent!', 'green');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
       	}
    });
}

var openDisbandClan = function() {
	$('#modal_disbandClan').foundation('open');
}

@endif

</script>