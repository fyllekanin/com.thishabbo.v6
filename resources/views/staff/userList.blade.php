<script> urlRoute.setTitle("TH - Users");</script>


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


<div class="reveal" id="user_bio" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Edit Users Biography</h4>
    </div>
    <div class="modal-body">




  <fieldset>
      <textarea id="user_bio_edit" class="login-form-input"></textarea>
  </fieldset>


</div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="editUserBio();">Save</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>


<div class="reveal" id="user_signature" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Edit Users Signature</h4>
    </div>
    <div class="modal-body">




  <fieldset>

      <div id="signature_box"></div>

  </fieldset>

</div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="editUserSignature();">Save</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>


<div class="reveal" id="user_avatar" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Edit Users Avatar</h4>
    </div>
    <div class="modal-body">



<fieldset>

    <div class="medium-12 column">
      <div class="ct-center" id="user_current_avatar">

      </div>
    </div>

    <input type="file" id="avatar_file" />

    <div class="progress-bar green stripes">
        <span id="progress_bar_meter" style="width: 0%"></span>
    </div>

</fieldset>

</div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="editUserAvatar();">Save</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>











<div class="reveal" id="user_header" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Edit Users Avatar</h4>
    </div>
    <div class="modal-body">



  <fieldset>
    <div class="medium-12 column">
      <div class="ct-center" id="user_current_header">

      </div>
    </div>

    <input type="file" id="header_file" />
    <div class="progress-bar green stripes">
        <span id="progress_bar_meter" style="width: 0%"></span>
    </div>
  </fieldset>

</div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="editUserHeader();">Save</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>



<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Users</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>

<div class="medium-8 column">

  <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerRed">
    <span>Users: Searched {{ $searched }}</span>
    <a href="/staff/mod/users/search" class="web-page headerLink white_link">Search User</a>
  </div>
      <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;" id="user-mod-list">
            <tr>
              <th>Username</th>
              <th>Last Activity</th>
              <th>Banned</th>
              <th>Actions</th>
              <th>Edit</th>
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
              <td>
              <select id="userid-{{ $user['userid'] }}">
                <option value="1">Edit Avatar</option>
                <option value="6">Edit Header</option>
                <option value="2">Edit Signature</option>
                <option value="3">Edit Biography</option>
                @if($user['banned'])
                  @if($can_unban_user)
                  <option value="4" data-toggle="#unban_user">Unban User</option>
                  @endif
                @else
                  @if($can_ban_user)
                  <option value="5" data-toggle="#ban_user">Ban User</option>
                  @endif
                @endif
              </select>
              </td>
              <td><a onclick="userAction({{ $user['userid'] }});"><i class="fa fa-cog" aria-hidden="true"></i></a></td>
              <td id="bio-{{ $user['userid'] }}" style="display: none;">{!! $user['bio'] !!}</td>
              <td id="signature-{{ $user['userid'] }}" style="display: none;">{!! $user['signature'] !!}</td>
              <td id="avatar-{{ $user['userid'] }}" style="display: none;">{!! $user['avatar'] !!}</td>
              <td id="header-{{ $user['userid'] }}" style="display: none;">{!! $user['header'] !!}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>

<div class="content-holder">
  <div class="content">
    {!! $pagi !!}
  </div>
</div>

<script type="text/javascript">
  $(document).foundation();
  var temp_userid = 0;
  var wbbOpt = {
    buttons: "bold,italic,underline,|,img"
  };

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
            urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
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
          urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
          urlRoute.ohSnap('User is unbanned!', 'green');

          $('#unban_user').foundation('close');
        }
      });
    }
  @endif

  var editUserBio = function() {
    var bio = $('#user_bio_edit').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/bio',
      type: 'post',
      data: {temp_userid:temp_userid, bio:bio},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
          urlRoute.ohSnap('Users bio updated!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }

        $('#user_bio').foundation('close');
      }
    });
  }

  var editUserSignature = function() {
    var signature = $('#user_signature_edit').bbcode();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/signature',
      type: 'post',
      data: {temp_userid:temp_userid, signature:signature},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
          urlRoute.ohSnap('Users signature updated!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }

        $('#user_signature').foundation('close');
      }
    });
  }

  var editUserAvatar = function() {
    var formData = new FormData();
    formData.append('avatar', $('#avatar_file')[0].files[0]);
    formData.append('temp_userid', temp_userid);

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/avatar',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
          urlRoute.ohSnap('Users avatar updated!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }

        $('#user_avatar').foundation('close');
      }
    });
  }

  var editUserHeader = function() {
    var formData = new FormData();
    formData.append('header', $('#header_file')[0].files[0]);
    formData.append('temp_userid', temp_userid);

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/mod/user/header',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("staff/mod/users/all/page/{{ $current_page }}");
          urlRoute.ohSnap('Users header updated!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }

        $('#user_header').foundation('close');
      }
    });
  }

  var userAction = function(userid) {

    var action = $('#userid-'+userid).val();
    temp_userid = userid;
    switch(action) {
      case "1":
        //avatar
        var avatar = $('#avatar-'+userid).html();
        $('#user_current_avatar').html('<img src="' + avatar + '" alt="current avatar" />');
        $('#user_avatar').foundation('open');
      break;
      case "6":
        //avatar
        var avatar = $('#header-'+userid).html();
        $('#user_current_header').html('<img src="' + avatar + '" alt="current header" />');
        $('#user_header').foundation('open');
      break;
      case "2":
        //signature
        $('#signature_box').html('<textarea id="user_signature_edit"></textarea>');
        $('#user_signature_edit').val($('#signature-'+userid).html());
        $("#user_signature_edit").wysibb(wbbOpt);
        $('#user_signature').foundation('open');
      break;
      case "3":
        //bio
        var bio = $('#bio-'+userid).html();
        $('#user_bio_edit').val(bio);
        $('#user_bio').foundation('open');
      break;
      case "4":
        $('#unban_user').foundation('open');
      break;
      case "5":
        $('#ban_user').foundation('open');
      break;
    }
  }

  var destroy = function() {
    banUser = null;
    unbanUser = null;
    editUserBio = null;
    editUserSignature = null;
    editUserAvatar = null;
    userAction = null;
    editUserHeader = null;
  }
</script>
