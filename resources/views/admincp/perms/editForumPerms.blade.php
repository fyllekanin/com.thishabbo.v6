<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Forum Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Forum Permissions for {{ $group->title }} in forum {{ $forum->title }}</span>
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
        <span>Forum Permissions for {{ $group->title }} in forum {{ $forum->title }}</span>
          <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>        
      </div>
        These are the new permissions for V6. <br>
        Please tick the box if the answer is a "yes". <br>
        Unticked = "no".
        </div>
      </div>

      <div class="content-holder">
        <div class="content">
         <div class="contentHeader headerBlack">
        <span>Forum Permissions</span>
      </div>   
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} view this forum? <br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="1" @if($permissions['can_see_forum'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} create threads? <br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="2" @if($permissions['can_create_thread'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} reply & post in others threads?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="4" @if($permissions['can_reply_to_others_threads'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} edit their own posts?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="8" @if($permissions['can_edit_own_post'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Does {{ $group->title }}'s posts need to be approved before posting? <br />
              <i style="font-size: 0.7rem;">This should only really be ticked if it's a Media forum</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="16" @if($permissions['can_skip_approve_thread'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} see other users threads? <br />
              <i style="font-size: 0.7rem;">Can user see other users threads?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="32" @if($permissions['can_see_others_threads'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Should we merge {{ $group->title }}'s double posts automatically?<br />
              <i style="font-size: 0.7rem;">If no, they won't be merged automatically</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="64" @if($permissions['can_skip_double_post'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              Can {{ $group->title }} reply & post in threads started by them?<br />
              <i style="font-size: 0.7rem;">in usergroup {{ $group->title }} in forum {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="128" @if($permissions['can_reply_to_own_threads'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
            <br>
          <button class="pg-red headerBlack gradualfader fullWidth topBottom" onclick="saveForumPerms();">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var saveForumPerms = function() {
    var forumid = {{ $forum->forumid }};
    var groupid = {{ $group->usergroupid }};

    var permissions = 0;
    $('input:checkbox.forumperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;

    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroup/forumpermissions',
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
    saveForumPerms = null;
  }
</script>
