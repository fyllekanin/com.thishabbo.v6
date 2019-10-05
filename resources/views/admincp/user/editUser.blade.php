<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - {{ $user->username }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp/users/all/page/1" class="bold web-page">List Users</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit User: {{ $user->username }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-4 column">

  @if($can_edit_details)
  <div class="content-holder">
      <div class="content">
          <div class="contentHeader headerRed">
            <span>Edit User: {{ $user->username }}</span>
          </div>
      <div class="content-ct">
        <label for="edit-user-username">Username <i style="font-size: 0.7rem;">(A-Z a-z 0-9, no special characters)</i></label>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
        <input type="text" id="edit-user-username" value="{{ $user->username }}" class="login-form-input"/>
        @else
        <input type="text" id="edit-user-username" value="{{ $user->username }}" class="login-form-input" disabled=""/>
        @endif
        <label for="edit-user-password">Password</label>
        <input type="password" id="edit-user-password" class="login-form-input"/>
        <label for="edit-user-email">Referrals</label>
        <input type="text" id="view-user-ipaddress" value="{{ $referrals }}" class="login-form-input" disabled="disabled" />
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="updateGeneral();">Save</button>
      </div>
    </div>
  </div>

 <div class="content-holder">
      <div class="content">
          <div class="contentHeader headerPurple">
              <span>Extra Information</span>
          </div>
          <div class="content-ct">
              <label for="view-user-posts">Posts</label>
              <input type="text" id="view-user-posts" value="{{ $user->postcount }}" class="login-form-input" disabled="disabled" />
              <label for="view-user-donator">Donator Color</label>
              <input type="text" id="view-user-donator" value="{{ $user->username_color }}" class="login-form-input" disabled="disabled" />
          </div>
      </div>
  </div>

 <div class="content-holder">
      <div class="content">
          <div class="contentHeader headerBlue">
            <span>Region, Country & Timezone</span>
          </div>
      <div class="content-ct">
        <label for="edit-user-region">Region (Staff Only)</label>
        <select id="edit-user-region" class="login-form-input">
            <option value="" @if($region === '')selected=""@endif>Not Set</option>
            <option value="EU" @if($region === 'EU')selected=""@endif>(EU) Europe</option>
            <option value="NA" @if($region === 'NA')selected=""@endif>(NA) North America</option>
            <option value="OC" @if($region === 'OC')selected=""@endif>(OC) Oceania</option>
        </select>

        <label for="edit-user-country">Country</label>
        <select id="edit-user-country" class="login-form-input">
          @foreach($countrys as $country)
            <option value="{{ $country->countryid }}" @if($country->countryid == $user->country) selected="" @endif>{{ $country->name }}</option>
          @endforeach
        </select>

        <label for="edit-user-timezone">Timezone</label>
        <select id="edit-user-timezone" class="login-form-input">
          @foreach($timezones as $timezone)
            <option value="{{ $timezone->timezoneid }}" @if($timezone->timezoneid == $user->timezone) selected="" @endif>{{ $timezone->name }}</option>
          @endforeach
        </select>


<br />
        <button class="pg-red headerBlue gradualfader fullWidth topBottom" onclick="updateTimeContry();">Save</button>
      </div>
    </div>
  </div>
  @endif

<div class="content-holder">
      <div class="content">
          <div class="contentHeader headerPink">
            <span>Habbo Information</span>
          </div>
      <div class="content-ct">
        <label for="edit-user-habbo-name">Habbo</label>
        <input type="text" id="edit-user-habbo-name" value="{{ $user->habbo }}" class="login-form-input"/>

        <label for="edit-user-habbo-verified">Verified Habbo</label>
        <select id="edit-user-habbo-verified" class="login-form-input">
          <option value="0" @if($user->habbo_verified == 0) selected="" @endif >No</option>
          <option value="1" @if($user->habbo_verified == 1) selected="" @endif >Yes</option>
        </select>

        <br />

        <button class="pg-red headerPink gradualfader fullWidth topBottom" onclick="updateHabboStuff();">Save</button>
      </div>
    </div>
  </div>

<div class="content-holder">
      <div class="content">
          <div class="contentHeader headerBlue">
            <span>Biography</span>
          </div>
      <div class="content-ct">
        <textarea id="bio_update" class="login-form-input">{!! $user->bio !!}</textarea>

        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="updateBio();">Save</button>
      </div>
    </div>
  </div>

</div>

<div class="medium-4 column">

  @if($can_edit_usergroups)

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Usergroups</span>
            </div>
          <select id="edit-user-display" class="login-form-input">
            <option value="0">Registered Users</option>
            @foreach($groups as $group)
              @if($group['in_it'])
                <option value="{{ $group['groupid'] }}" @if($group['groupid'] == $user->displaygroup) selected="" @endif >{{ $group['title'] }}</option>
              @endif
            @endforeach
          </select>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
        <div class="content-ct">
          @foreach($groups as $group)
            <input type="checkbox" value="{{ $group['groupid'] }}" class="usergroupscheck" @if($group['in_it']) checked="" @endif /> {{ $group['title'] }} <br />
          @endforeach<br>

          <label for="custom_role">Custom role:</label>
          <input type="text" value="{{ $user->role }}" id="custom_role" class="login-form-input"></input>
          <label for="role_priority">Display priority:</label>
          <input type="number" value="{{ $user->priority }}" id="role_priority" class="login-form-input"></input>
          <br />
          <button class="pg-red headerBlue gradualfader fullWidth topBottom" onclick="updateUsergroups();">Save</button>
          <br />
        </div>
      </div>
    </div>
  @endif
</div>
<div class="medium-8 column">

  <div class="content-holder">
      <div class="content">
          <div class="contentHeader headerRed">
            <span>Profile Banner</span>
          </div>
      <div class="content-ct">
        <div class="ct-center">
          <img src="{!! $header !!}" alt="Avatar" id="users_header"/>
        </div>
        <br>
        <b>Upload new header</b><br />
        <div class="upload_header">
          <input type="file" id="header_file" />
        </div>

        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="updateHeader();">Save</button>
        <div class="progress-bar1 green stripes">
            <span id="progress_bar_meter1" style="width: 0%"></span>
        </div>

      </div>
    </div>
  </div>

<div class="content-holder">
      <div class="content">
          <div class="contentHeader headerBlack">
              <span>Avatar</span>
          </div>
      <div class="content-ct">
        <div class="ct-center">
          <img src="{!! $avatar !!}" alt="Avatar" id="users_avatar"/>
        </div><br>
        <b>Upload new avatar</b> <br />
        <div class="upload_avatar">
          <input type="file" id="avatar_file" />
        </div>

        <button class="pg-red headerBlack gradualfader fullWidth topBottom" onclick="updateAvatar();">Save</button>

        <div class="progress-bar green stripes">
            <span id="progress_bar_meter" style="width: 0%"></span>
        </div>

      </div>
    </div>
  </div>

    <div class="content-holder">
      <div class="content">
          <div class="contentHeader headerBlack">
              <span>Users Signature</span>
          </div>
      <div class="content-ct">
        <textarea id="edit-user-signature" class="login-form-input" style="height: 4rem;">{!! $user->signature !!}</textarea>

        <br />
        <button class="pg-red headerBlack gradualfader fullWidth topBottom" onclick="updateSignature();">Save</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $(document).foundation();

    $("#edit-user-signature").wysibb();
  });

  var userid = {{ $user->userid }};

  var updateBio = function() {
    var bio = $('#bio_update').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/user/update/bio',
      type: 'post',
      data: {userid:userid, bio:bio},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/users/edit/'+userid);
          urlRoute.ohSnap('Bio updated!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var updateAvatar = function() {
    var formData = new FormData();
    formData.append('avatar', $('#avatar_file')[0].files[0]);
    formData.append('userid', userid);

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/update/avatar',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if(data['response'] == true) {
            $('#progress_bar_meter').css("width", "100%");
            $('.progress-bar').delay(1000).fadeOut("slow", function() {
              $('#progress_bar_meter').css("width", "0%");
            });

            $('#users_avatar').attr("src", data['new_avatar']);
          }
          else {
            urlRoute.ohSnap('You din\'t choose a image', 'red');
            $('#progress_bar_meter').css("width", "0%");
            $('.progress-bar').delay(1000).fadeOut();
          }
        }
      });
    });
  }

  var updateHeader = function() {
    var formData = new FormData();
    formData.append('header', $('#header_file')[0].files[0]);
    formData.append('userid', userid);

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter1').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/update/header',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if(data['response'] == true) {
            $('#progress_bar_meter1').css("width", "100%");
            $('.progress-bar1').delay(1000).fadeOut("slow", function() {
              $('#progress_bar_meter1').css("width", "0%");
            });

            $('#users_header').attr("src", data['new_header']);
          }
          else {
            urlRoute.ohSnap('You din\'t choose a image', 'red');
            $('#progress_bar_meter1').css("width", "0%");
            $('.progress-bar1').delay(1000).fadeOut();
          }
        }
      });
    });
  }

  @if($can_edit_details)
    var updateGeneral = function() {
      var username = $('#edit-user-username').val();
      var password = $('#edit-user-password').val();

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/update/general',
        type: 'post',
        data: {userid:userid, username:username, password:password},
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.loadPage('/admincp/users/edit/'+userid);
            urlRoute.ohSnap('General updated!', 'green');
          } else {
            $('#'+data['field']).addClass('form-reg-error');
            urlRoute.ohSnap(data['message'], 'red');
          }
        }
      });
    }
  @endif

  var updateHabboStuff = function() {
    var habbo_name = $('#edit-user-habbo-name').val();
    var habbo_veri = $('#edit-user-habbo-verified').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/user/update/habbo',
      type: 'post',
      data: {userid:userid, habbo_name:habbo_name, habbo_veri:habbo_veri},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/users/edit/'+userid);
          urlRoute.ohSnap('Habbo Stuff updated!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    })
  }

  @if($can_edit_details)
    var updateTimeContry = function() {
      var region = $("#edit-user-region").val();
      var country = $("#edit-user-country").val();
      var timezone = $("#edit-user-timezone").val();

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/update/timecountry',
        type: 'post',
        data: {region:region, country:country, timezone:timezone, userid:userid},
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.loadPage('/admincp/users/edit/'+userid);
            urlRoute.ohSnap('Time & Country updated!', 'green');
          } else {
            urlRoute.ohSnap('Something went wrong!', 'red');
          }
        }
      });
    }
  @endif

  var updateSignature = function() {
    var signature = $("#edit-user-signature").bbcode();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/user/update/signature',
      type: 'post',
      data: {signature:signature, userid:userid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/users/edit/'+userid);
          urlRoute.ohSnap('Signature updated!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }
      }
    });
  }

  @if($can_edit_usergroups)
    var updateUsergroups = function() {
      var usergroups = "";

      $('input:checkbox.usergroupscheck').each(function() {
        var value = (this.checked ? $(this).val() : 0);

        value = parseInt(value);

        if(value > 0) {
          usergroups = usergroups + "," + value;
        }

      });

      var displaygroup = $('#edit-user-display').val();

      var customrole = $('#custom_role').val();
      var rolepriority = $('#role_priority').val()

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/update/usergroups',
        type: 'post',
        data: {userid:userid, usergroups:usergroups, displaygroup:displaygroup, customrole:customrole, rolepriority:rolepriority },
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.loadPage('/admincp/users/edit/'+userid);
            urlRoute.ohSnap('Usergroups updated!', 'green');
          } else {
            urlRoute.ohSnap('Something went wrong!','red');
          }
        }
      });
    }
  @endif

  var destroy = function() {
    updateBio = null;
    updateAvatar = null;
    updateHeader = null;
    updateGeneral = null;
    updateHabboStuff = null;
    updateTimeContry = null;
    updateSignature = null;
    updateUsergroups = null;
  }
</script>
