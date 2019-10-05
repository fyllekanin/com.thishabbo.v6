<script> urlRoute.setTitle("TH - Banned Users");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Banned Users</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>Banned Users</span>
                    <a href="/admincp/users/all/page/1" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Username</th>
              <th>Banned At</th>
              <th>Admin Name</th>
              <th>Until</th>
              <th>Reason</th>
              <th>Unban</th>
            </tr>
            @foreach($users as $user)
              <tr id="user-{{ $user['userid'] }}">
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['banned_at'] }}</td>
                <td>{!! $user['admin_name'] !!}</td>
                <td>@if($user['banned_untilraw'] == "0")Permenant @else {{ $user['banned_until'] }} @endif</td>
                <td>{{ $user['reason'] }}</td>
                <td><a onclick="setUserid({{ $user['userid'] }});"><i class="fa fa-trash editcog4" aria-hidden="true"></i></a>
              </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).foundation();
  var temp_userid = 0;

  var setUserid = function(userid) {
    temp_userid = userid;
  }

  var unbanUser = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/users/unban',
      type: 'post',
      data: {temp_userid:temp_userid},
      success: function(data) {
         $('#user-'+temp_userid).fadeOut();
         ohSnap('User Unbanned!', {'color':'green'});

         $('#unban_user').foundation('close');
      }
    });
  }

  var destroy = function() {
    setUserid = null;
    unbanUser = null;
  }
</script>
