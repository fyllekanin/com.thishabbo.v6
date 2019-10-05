<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Mod Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit Moderation Perms: {{ $group->title }}</span>
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
          <span>Moderation Permissions for {{ $group->title }} in forum {{ $forum->title }}</span>
          <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a> 
                  </div>
        These are the new permissions for V6. <br/>
        Please tick the box if the answer is a "yes".<br/> 
        Unticked = "no".
      </div>
      </div>

      <div class="content-holder">
        <div class="content">
<div class="contentHeader headerBlack">
        <span>Moderation Permissions</span>
      </div>
          <div class="row new-checkbox">
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} edit posts?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm1" type="checkbox" class="modperm" value="1" @if($permissions['can_edit_posts'] == 1) checked="" @endif />
              <label for="formodperm1">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} soft delete posts/threads?<br />
              <i style="font-size: 0.7rem;">Soft delete should be given to all Management/Admins</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm2" type="checkbox" class="modperm" value="2" @if($permissions['can_soft_delete_posts'] == 1) checked="" @endif />
              <label for="formodperm2">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can hard delete posts/threads?</b><i style="font-size: 0.7rem;">(Overrides soft delete)</i><br />
              <i style="font-size: 0.7rem;">Senior Administration, Site Administration and Community Administration only!</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm3" type="checkbox" class="modperm" value="4" @if($permissions['can_hard_delete_posts'] == 1) checked="" @endif />
              <label for="formodperm3">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} open/close threads?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm4" type="checkbox" class="modperm" value="8" @if($permissions['can_open_close_threads'] == 1) checked="" @endif />
              <label for="formodperm4">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} move threads?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm5" type="checkbox" class="modperm" value="16" @if($permissions['can_move_posts_threads'] == 1) checked="" @endif />
              <label for="formodperm5">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} merge threads?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input id="formodperm6" type="checkbox" class="modperm" value="32" @if($permissions['can_merge_threads'] == 1) checked="" @endif />
              <label for="formodperm6">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} change owner of posts/threads<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="modperm" value="64" @if($permissions['can_change_owner'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} move posts to another thread?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="modperm" value="128" @if($permissions['can_move_posts'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} infract or warn users on the forum?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="modperm" value="256" @if($permissions['can_warninf_users'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} approve or unapprove threads in the forum?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="modperm" value="512" @if($permissions['can_approve_unapprove_threads'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} view unapproved threads in the forum?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="modperm" value="1024" @if($permissions['can_view_unapproved_threads'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
        </div>
                <button onclick="saveModPerms();" class="pg-red headerBlack gradualfader fullWidth topBottom" style="float:right;">Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var saveModPerms = function() {
    var forumid = {{ $forum->forumid }};
    var groupid = {{ $group->usergroupid }};

    var permissions = 0;
    $('input:checkbox.modperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;

    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroup/moderationpermissions',
      type: 'post',
      data: {forumid:forumid, groupid:groupid, permissions:permissions},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/usergroups');
          urlRoute.ohSnap('Permissions saved!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }
      }
    })
  }

  var destroy = function() {
    saveModPerms = null;
  }
</script>
