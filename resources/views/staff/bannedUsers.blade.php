<script> urlRoute.setTitle("TH - Banned Users");</script>

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
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="unbanUser();">Unban User</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>
@endif

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Banned Users</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
<div class="contentHeader headerRed">
                Banned Users
              </div>
            <div class="content-ct">
      <div class="small-12">
        <table class="responsive" style="width: 100%;">
          <tr>
            <th>Username</th>
            <th>Banned At</th>
            <th>Admin Name</th>
            <th>Until</th>
            <th>Reason</th>
            @if($can_unban_user)<th>Unban</th>@endif
          </tr>
          @foreach($bannedUsers as $user)
            <tr id="user-{{ $user['userid'] }}">
              <td>{{ $user['username'] }}</td>
              <td>{{ $user['banned_at'] }}</td>
              <td>{{ $user['admin_name'] }}</td>
              <td>@if($user['banned_untilraw'] == "0")Permenant @else {{ $user['banned_until'] }} @endif</td>
              <td>{{ $user['reason'] }}</td>
              @if($can_unban_user)<td><a data-toggle="unban_user" onclick="setUserid({{ $user['userid'] }});"><i class="fa fa-check editcog4" aria-hidden="true"></i></a></td>@endif
            </tr>
          @endforeach
        </table>
      </div>
    </div>
    </div>
  </div>
</div>

@if($can_unban_user)
<script type="text/javascript">
  $(document).foundation();
  var temp_userid = 0;

  var setUserid = function(userid) {
    temp_userid = userid;
  }
  var unbanUser = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/unban',
      type: 'post',
      data: {temp_userid:temp_userid},
      success: function(data) {
        $('#user-'+temp_userid).fadeOut();
        $('#unban_user').foundation('close');
        urlRoute.ohSnap('User unbanned!', 'green');
      }
    });
  }

  var destroy = function() {
    setUserid = null;
    unbanUser = null;
  }
</script>
@endif
