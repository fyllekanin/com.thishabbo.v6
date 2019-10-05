<script> urlRoute.setTitle("TH - Manage Badge");</script>
<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Badge</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder">
    <div class="content">
  <div class="contentHeader headerRed">
    Edit Badge
    <a href="/admincp/badges/manage/page/1" class="web-page headerLink white_link">Back</a>
  </div>
      <div class="content-ct">
        <label for="badge-form-desc">Username</label>
        <input type="text" id="badge-form-username" placeholder="Username..." class="login-form-input"/>
      </div>
      <BR>
      <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addBadgeToUser();">Add Badge to User</button>                
      <br> 
      <img src="{{ $badge_image }}" style="float: right;" />
    </div>
  </div>
</div>

<div class="medium-8 column">
  <div class="content-holder">
    <div class="content">
  <div class="contentHeader headerRed">
    Users with Badge
    <a href="/admincp/badges/manage/page/1" class="web-page headerLink white_link">Back</a>
  </div>
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

<script type="text/javascript">
  var badgeid = {{ $badgeid }};

  var removeBadge = function(userid) {
    if(confirm('Are you sure you want to remove this badge from user?')) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/badge/remove/user',
        type: 'post',
        data: {badgeid:badgeid, userid:userid},
        success: function(data) {
          urlRoute.ohSnap('Badge removed from user!', 'green');
          urlRoute.loadPage('/admincp/badge/manage/'+badgeid);
        }
      });
    }
  }

  var addBadgeToUser = function() {
    var username = $('#badge-form-username').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/badge/add/user',
      type: 'post',
      data: {username:username, badgeid:badgeid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('User added to badge!', 'green');
          urlRoute.loadPage('/admincp/badge/manage/'+badgeid);
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
