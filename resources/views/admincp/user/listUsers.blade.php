<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - User List");</script>

@if($can_ban_user)
<div class="reveal" id="ban_user" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Ban a User</h4>
    </div>
    <div class="modal-body">



    <fieldset>
        <label for="ban_user_time">Time to be banned</label>
        <select id="ban_user_time" class="login-form-input">
          <option value="86400">1 day</option>
          <option value="172800">2 days</option>
          <option value="604800">1 week</option>
          <option value="2419200">1 month</option>
          <option value="0">Permanent</option>
        </select>
        <label for="ban_user_reason">Reason for ban</label>
        <input type="text" id="ban_user_reason" placeholder="Reason..." class="login-form-input"/>
    </fieldset>


</div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="banUser();">Ban</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>
@endif

@if($can_unban_user)
<div class="reveal" id="unban_user" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Unban a User</h4>
    </div>
    <div class="modal-body">

By unbanning this user, they will be able to access all areas of the website again. If this ban has been authorised by another Administrator, please ensure you have their permission to allow the user to regain access.

    </div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="unbanUser();">Ban</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>
@endif

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Users: Searched {{ $searched }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Users: Searched {{ $searched }}</span>
                    <a href="/admincp/users/search" class="web-page headerLink white_link">Search User</a>
                  </div>
      <div class="content-holder">
        <div class="content">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Username</th>
              <th>Habbo</th>
              <th>Last Activity</th>
              <th>Banned</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($users as $user)
              <tr>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['habbo'] }}</td>
                <td>{{ $user['lastactivity'] }}</td>
                @if($user['banned'])
                  <td>Yes</td>
                @else
                  <td>No</td>
                @endif
                <td>
                  <select id="userid-{{ $user['userid'] }}">
                    <option value="1">Edit User</option>
                    @if($user['banned'])
                      <option value="2" data-toggle="#unban_user">Unban User</option>
                    @else
                      <option value="3" data-toggle="#ban_user">Ban User</option>
                    @endif
                    @if($can_give_subscription)
                      <option value="4">Subscriptions</option>
                    @endif
                    <option value="5">Accolades</option>
                  </select>
                  <td><a onclick="userAction({{ $user['userid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
                </td>
              </tr>
            @endforeach
          </table>
        </div>
      </div></div>

  <div class="content-holder">
      <div class="content">
    {!! $pagi !!}
      </div>
  </div>


<script type="text/javascript">
  $(document).foundation();
  var temp_userid = 0;

  var banUser = function() {

    var reason = $('#ban_user_reason').val();
    var time = $('#ban_user_time').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/users/ban',
      type: 'post',
      data: {temp_userid:temp_userid, time:time, reason:reason},
      success: function(data) {
          $('#ban_user').foundation('close');
        if(data['response'] == true) {
          urlRoute.loadPage("/admincp/users/{{ $searched }}/page/{{ $current_page }}");
          urlRoute.ohSnap('User Banned!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong', 'red');
        }
      }
    });
  }

  var unbanUser = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/users/unban',
      type: 'post',
      data: {temp_userid:temp_userid},
      success: function(data) {
         $('#unban_user').foundation('close');
         urlRoute.loadPage("/admincp/users/{{ $searched }}/page/{{ $current_page }}");
         urlRoute.ohSnap('User Unbanned!', 'green');
      }
    });
  }

  var userAction = function(userid) {

    var action = $('#userid-'+userid).val();

    switch(action) {
      case "1":
        urlRoute.loadPage('/admincp/users/edit/'+userid);
      break;
      case "2":
        temp_userid = userid;
        $('#unban_user').foundation('open');
      break;
      case "3":
        temp_userid = userid;
        $('#ban_user').foundation('open');
      break;
      @if($can_give_subscription)
      case "4":
        urlRoute.loadPage('/admincp/user/'+userid+'/subscriptions');
      break;
      @endif
      case "5":
        urlRoute.loadPage('/admincp/users/accolade/'+userid);
      break;
    }
  }

  var destroy = function() {
    banUser = null;
    unbanUser = null;
    userAction = null;
  }
</script>
