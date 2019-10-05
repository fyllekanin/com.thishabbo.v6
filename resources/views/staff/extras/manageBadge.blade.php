<script> urlRoute.setTitle("TH - Manage Badge");</script>
<div class="small-12 column">
    <div class="content-topic topic-breadcrum" style="margin-bottom: 0.5rem;">
        <div class="content-topic-opacity"></div>
        <span><a href="/home" class="web-page">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
        <span><a href="/staff" class="web-page">Staff Panel</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
        <span>Manage Badge</span>
    </div>
</div>
<div class="small-12 medium-5 large-3 column">
    @include('staff.menu')
</div>
<div class="small-12 medium-7 large-9 column">
    <div class="content-holder">
        <div class="inner-content-holder">
            <div class="content-topic topic-red">
                <div class="content-topic-opacity"></div>
                <span>Give badge to user</span>
            </div>
            <div class="content-ct">
              <div class="small-12">
                <div class="small-12 column">
                  <label for="badge-form-username">Username</label>
                  <input type="text" id="badge-form-username" placeholder="Username..." class="login-form-input"/>
                </div>
                <div class="small-12 column">
                  <button onclick="addBadgeToUser();" class="pg-left pg-grey" style="margin-left: 0.5rem; margin-bottom: 0.5rem;">Add Badge</button> 
                  <img src="{{ $badge_image }}" style="float: right;" />
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="content-holder">
      <div class="inner-content-holder">
          <div class="content-topic topic-red">
              <div class="content-topic-opacity"></div>
              <span>Users with badge</span>
          </div>
          <div class="content-ct">
            <table class="responsive" style="width: 100%;">
              <tr>
                <th>Username</th>
                <th>Remove</th>
              </tr>
              @foreach($users as $user)
                <tr>
                  <td>{{ $user['username'] }}</td>
                  <td>
                    Remove <i class="fa fa-trash" aria-hidden="true" onclick="removeBadge({{ $user['userid'] }});"></i>
                  </td>
                </tr>
              @endforeach
            </table>
          </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  var badgeid = {{ $badgeid }};

  var removeBadge = function(userid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/badge/remove/user',
      type: 'post',
      data: {badgeid:badgeid, userid:userid},
      success: function(data) {
        urlRoute.ohSnap('Badge removed from user!', 'green');
        urlRoute.loadPage('/staff/badge/manage/'+badgeid);
      }
    });
  }

  var addBadgeToUser = function() {
    var username = $('#badge-form-username').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/badge/add/user',
      type: 'post',
      data: {username:username, badgeid:badgeid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('User added to badge!', 'green');
          urlRoute.loadPage('/staff/badge/manage/'+badgeid);
        } else {
          urlRoute.ohSnap(data['message'], 'red'); 
        }
      }
    });
  }

  var destroy = function() {
    removeBadge = null;
    addBadgeToUser = null;
  }
</script>