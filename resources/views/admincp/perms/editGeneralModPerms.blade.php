<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Admin Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit General Moderations Perms: {{ $group->title }}</span>
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
                    <span>Edit General Moderations Perms: {{ $group->title }}</span>
                    <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>
                  </div>
        @if($dont_have_staff) <div class="alertt alert-danger" role="alertt"><b>Permission Error:</b><br>This usergroup doesn't have access to the Staff Panel. This means some permissions may not work. Please correct this by going editing the "Edit Staff Permissions" and ticking the box "Can user access staff panel?". This message will be removed when it has been ticked!</div> @endif
        These are the new permissions for V6. <br>
        Please tick the box if the answer is a "yes". <br>
        Unticked = "no".
              </div>
      </div>
      

        <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
          <span>Access Tabs + Shoutbox</span>
        </div>
        <div class="row new-checkbox">      
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view moderation tab in staff panel?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="1" @if($permissions['can_see_moderation'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
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
             <b>Can {{ $group->title }} ban users?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="2" @if($permissions['can_ban_user'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} unban users?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="4" @if($permissions['can_unban_user'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>soft-delete</u> visitor messages?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="8" @if($permissions['can_soft_delete_vm'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>soft-delete</u> article comments?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="32" @if($permissions['can_soft_delete_article_comments'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>soft-delete</u> creation comments?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="1024" @if($permissions['can_soft_delete_creation_comments'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>hard-delete</u> visitor messages?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="2048" @if($permissions['can_hard_delete_vm'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>hard-delete</u> article comments?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="4096" @if($permissions['can_hard_delete_article_comments'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} <u>hard-delete</u> creation comments?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="8192" @if($permissions['can_hard_delete_creation_comments'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} search users with IP?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="64" @if($permissions['can_search_users_using_same_ip'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage creations?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="128" @if($permissions['can_manage_creations'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} infract or warn on Article Comments?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="256" @if($permissions['can_infract_article_comments'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} infract or warn on VMs?</b><br />
              <i style="font-size: 0.7rem;">Are they Moderators, Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="generalmodperm" value="512" @if($permissions['can_infract_vm'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          </div>
            <button onclick="saveGeneralModPerms();" class="pg-red headerRed gradualfader fullWidth topBottom">Save</button>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
  var saveGeneralModPerms = function() {
    var groupid = {{ $group->usergroupid }};

    var permissions = 0;
    $('input:checkbox.generalmodperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;
    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroup/generalmodperms',
      type: 'post',
      data: {groupid:groupid, permissions:permissions},
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
    saveGeneralModPerms = null;
  }
</script>
