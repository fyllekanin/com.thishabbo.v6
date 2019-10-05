<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Usergroups");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Usergroups</span>
    </div>
  </div>
</div>

<div class="reveal" id="list_users" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="paddingHelper">
    <h5>Users in <span id="usergroup_name"></span></h5>
    <p>List of all the users in the usergroup, if you got permission you can also remove their access to it.</p>
    <fieldset id="members_list">

    </fieldset>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                  <span>Usergroups</span>
                  <a href="/admincp/usergroups/add" class="web-page headerLink white_link">Add Usergroup</a>
                  </div>
<div class="content-holder">
        <div class="content">
          <div class="content-ct">

          <input type="text" placeholder="Forum Name" class="login-form-input" id="admin-name-filter">

          </div>
        </div>
      </div>
                  
      <div class="content-holder">
        <div class="content">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Title</th>
              <th>Avatar Height</th>
              <th>Avatar Width</th>
              <th>User(s) in Group</th>
              <th>Actions</th>
              <th>Edit</th>              
              <th>Last Edited</th>
            </tr>
            @foreach($groups as $group)
              <tr>
                <td>{{ $group['title'] }}</td>
                <td>{{ $group['avatar_height'] }}</td>
                <td>{{ $group['avatar_width'] }}</td>
                <td><span id="groupidamount-{{ $group['groupid'] }}">{{ count($group['users']) }}</span> <i class="fa fa-users" aria-hidden="true" onclick="showMembers({{ $group['groupid'] }});" style="cursor:pointer"></i></td>
                <td>
                  <select id="groupid-{{ $group['groupid'] }}">
                    <option value="1">Edit Usergroup</option>
                    @if($edit_admin_perms)<option value="5">Edit Administration Permissions</option>@endif
                    @if($can_admin_general_mod_perms)<option value="8">Edit General Moderation Permissions</option>@endif
                    @if($edit_mod_perms)<option value="4">Edit Forum Moderation Permissions</option>@endif                    
                    @if($edit_staff_perms)<option value="7">Edit Staff Permissions</option>@endif
                    <option value="3">Edit Forum Permissions</option>
                    <option value="6">Edit Userbar</option>
                    <option value="2">Delete Usergroup</option>
                  </select>
                </td>
                <td><a onclick="groupAction({{ $group['groupid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>                
                <td>{{ $group['lastedited'] }}</td>
              </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var groups = [];

  $(document).ready(function() {
    $(document).foundation();
  });

  @foreach($groups as $group)
      groups['{{ $group['groupid'] }}'] = [];
      @foreach($group['users'] as $user)
          groups['{{ $group['groupid'] }}'].push({
            userid: {{ $user->userid }},
            username: '{{ $user->username }}'
          });
      @endforeach
  @endforeach

  var showMembers = function(groupid) {
    $('#members_list').html('');
    groups[groupid].forEach(function(user) {
      $('#members_list').append(`
        <div class="small-4 column" id="member-`+user.userid+`">
          ` + user.username + `  @if($can_admin_users) <i class="fa fa-trash" aria-hidden="true" onclick="removeUserFromGroup(`+user.userid+`, `+groupid+`)"></i> @endif
        </div>
      `);
    });
    $('#list_users').foundation('open');
  }

  @if($can_admin_users)
    var removeUserFromGroup = function(userid, groupid) {
      $('#member-'+userid).fadeOut();
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/user/remove/group',
        type: 'post',
        data: {userid: userid, groupid: groupid},
        success: function(data) {
          urlRoute.ohSnap('User removed from group', 'green');
          groups[groupid] = groups[groupid].filter(function(users){
            return users.userid !== userid;
          });
          $("#groupidamount-"+groupid).html('' + groups[groupid].length);
        }
      });
    }
  @endif

  var groupAction = function(groupid) {
    var action = $('#groupid-'+groupid).val();
    switch(action) {
      case "1":
        //Edit Group
        urlRoute.loadPage('/admincp/usergroups/edit/'+groupid);
      break;
      case "2":
        //Remove Group
        var r = confirm("Sure you wanna delete this group?");
        if(r == true) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/group/remove',
            type: 'post',
            data: {groupid:groupid},
            success: function(data) {
              if(data['response'] == true) {
                urlRoute.loadPage('/admincp/usergroups');
                urlRoute.ohSnap('Group Removed!', 'green');
              } else {
                urlRoute.ohSnap('Something went wrong', 'red');
              }
            }
          });
        }
      break;
      case "3":
        //Edit Forumpermissions
        urlRoute.loadPage('/admincp/usergroups/select/'+groupid+'/forum');
      break;
      @if($edit_mod_perms)
        case "4":
          //Edit Moderationpermissions
          urlRoute.loadPage('/admincp/usergroups/select/'+groupid+'/mod');
        break;
      @endif
      @if($edit_admin_perms)
        case "5":
          //Edit Adminpermissions
          urlRoute.loadPage('/admincp/usergroup/'+groupid+'/edit/adminpermissions');
        break;
      @endif
      case "6":
        //Edit Userbars
        urlRoute.loadPage('/admincp/usergroups/edit/bar/'+groupid);
      break;
      case "7":
        //Edit Staff Perms
        urlRoute.loadPage('/admincp/usergroup/'+groupid+'/edit/staffpermissions');
      break;
      case "8":
        //Edit general mod perms
        urlRoute.loadPage('/admincp/usergroup/'+groupid+'/edit/generalmodperms');
      break;
    }
  }

  var destroy = function() {
    groupAction = null;
  }
</script>
