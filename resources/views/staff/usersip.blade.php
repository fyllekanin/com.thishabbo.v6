<script> urlRoute.setTitle("TH - Users IP");</script>

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
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Find users using same IP</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
                <div class="contentHeader headerRed">
                <span>Find users using same IP</span>
                <a href="/staff/mod/users/findsimilar" class="web-page headerLink white_link">Search IP</a>
              </div>
            <div class="content-ct">
                <table class="responsive" style="width: 100%;" id="user-mod-list">
                    <tr>
                        <th>Username</th>
                        <th>Last Activity</th>
                        <th>Banned</th>
                        <th>IP Address</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($users as $user)
                      <tr class="user-mod-user">
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['lastactivity'] }}</td>
                        @if($user['banned'])
                        <td>Yes</td>
                        @else
                        <td>No</td>
                        @endif
                        <td>Actions</td>
                      </tr>
                    @endforeach
                    </td>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
@if($can_ban_user)
  var banUser = function() {
    var reason = $('#ban_user_reason').val();
    var time = $('#ban_user_time').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/ban',
      type: 'post',
      data: {temp_userid:temp_userid, reason:reason, time:time},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("staff/mod/users/similar");
          urlRoute.ohSnap('User is banned!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }

        $('#ban_user').foundation('close');
      }
    });
  }
@endif

@if($can_unban_user)
  var unbanUser = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/unban',
      type: 'post',
      data: {temp_userid:temp_userid},
      success: function(data) {
        urlRoute.loadPage("staff/mod/user/similar");
        urlRoute.ohSnap('User is unbanned!', 'green');

        $('#unban_user').foundation('close');
      }
    });
  }
@endif

var userAction = function(userid) {

  var action = $('#userid-'+userid).val();
  temp_userid = userid;
  switch(action) {
    case "4":
      $('#unban_user').foundation('open');
    break;
    case "5":
      $('#ban_user').foundation('open');
    break;
  }
}
</script>
