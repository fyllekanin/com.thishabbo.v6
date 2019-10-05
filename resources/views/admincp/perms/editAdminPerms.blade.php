<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Admin Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit Admin Perms: {{ $group->title }}</span>
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
                    <span>Edit Admin Perms: {{ $group->title }}</span>
                    <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>
                  </div>
            <div class="alert alert-danger">
                <p>Please <b>tick the box</b> if you wish to <b>grant access</b> to the feature. <br>
              Please <b>leave the box unticked</b> if you <b>do NOT wish to grant access</b> to the feature.</p>
            </div>
          </div>
      </div>

      <div class="content-holder">
        <div class="content">
          <div class="contentHeader headerRed">
            <span>Admin Permissions</span>
          </div>
        <div class="row new-checkbox"> 
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access Admin CP?</b><br />
              <i style="font-size: 0.7rem;">Should they be able to see "AdminCP" in their navigation?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="1" @if($permissions['can_use_admincp'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage forums?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="2" @if($permissions['can_admin_forums'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage usergroups?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="8" @if($permissions['can_admin_usergroups'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage BB Codes?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="16" @if($permissions['can_admin_bbcodes'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} edit admin permissions on usergroups?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="32" @if($permissions['can_edit_adminpermissions'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} edit general moderation permissions on usergroups?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="2097152" @if($permissions['can_edit_generalmodperm'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} edit moderation permissions?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="64" @if($permissions['can_edit_moderationpermissions'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} edit staff permissions?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="128" @if($permissions['can_edit_staffpermissions'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} edit default users/visitors forum permissions?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="512" @if($permissions['can_edit_default_forumpermissions'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view (Read-Only) the site rules editor page?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="131072" @if($permissions['can_view_site_rules'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view and edit (Read and Write) the site rules?</b><br />
              <i style="font-size: 0.7rem;">Please ensure "Read Only" is checked so that read and write access is granted. Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="1048576" @if($permissions['can_edit_site_rules'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Badges?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="262144" @if($permissions['can_manage_badges'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage thread prefixes?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="2048" @if($permissions['can_manage_prefixes'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Automated Threads?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="8388608" @if($permissions['can_manage_automated_threads'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Staff List?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="16777216" @if($permissions['can_manage_staff_list'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} turn site maintenance on or off?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="33554432" @if($permissions['can_edit_maintenance'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Daily Quests?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="67108864" @if($permissions['can_manage_daily_quests'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Link Partners?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Developer?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="134217728" @if($permissions['can_manage_link_partners'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Staff of the Week?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="268435456" @if($permissions['can_manage_sotw'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} tweet from the AdminCP?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="536870912" @if($permissions['can_tweet_via_admincp'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view Admin/Moderator Logs?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="1073741824" @if($permissions['can_view_logs'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view Statistics?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="2147483648" @if($permissions['can_view_statistics'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage the Carousel?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="8589934592" @if($permissions['can_manage_carousel'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage Welcome Bot?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="17179869184" @if($permissions['can_manage_welcome_bot'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage XP Levels?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="34359738368" @if($permissions['can_manage_post_levels'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} add THC to users?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration or Owners?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="4294967296" @if($permissions['can_issue_points'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>


        <div class="content-holder">
          <div class="content">
        <div class="contentHeader headerRed">
          <span>User related permissions</span>
        </div>
          <div class="row new-checkbox"> 

      <div class="small-12 column" style="margin-bottom: 1rem;">
        <div class="small-12 medium-6 column">
          <b>Can {{ $group->title }} manage users?</b><br />
          <i style="font-size: 0.7rem;">Change avatar, coverphoto, bio etc</i>
        </div>
        <div class="small-12 medium-6 column">
          <input type="checkbox" class="adminperm" value="4" @if($permissions['can_admin_users'] == 1) checked="checked" @endif />
          <label for="formodperm7">
            <span><span></span></span>
          </label>
        </div>
      </div>

      <div class="small-12 column" style="margin-bottom: 1rem;">
        <div class="small-12 medium-6 column">
          <b>Can {{ $group->title }} manage users usergroups?</b><br />
          <i style="font-size: 0.7rem;">What usergroups they are in and what display group they have</i>
        </div>
        <div class="small-12 medium-6 column">
          <input type="checkbox" class="adminperm" value="524288" @if($permissions['can_edit_users_usergroups'] == 1) checked="checked" @endif />
          <label for="formodperm7">
            <span><span></span></span>
          </label>
        </div>
      </div>

      <div class="small-12 column" style="margin-bottom: 1rem;">
        <div class="small-12 medium-6 column">
          <b>Can {{ $group->title }} manage users specific details?</b><br />
          <i style="font-size: 0.7rem;">Username, password, email, timezone etc</i>
        </div>
        <div class="small-12 medium-6 column">
          <input type="checkbox" class="adminperm" value="1024" @if($permissions['can_edit_users_details'] == 1) checked="checked" @endif />
          <label for="formodperm7">
            <span><span></span></span>
          </label>
        </div>
      </div>
    </div>
  </div>
  </div>



       <div class="content-holder">
          <div class="content">
        <div class="contentHeader headerRed">
          <span>Shop Permissions</span>
        </div>
          <div class="row new-checkbox"> 
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} view shop settings?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="8192" @if($permissions['can_see_shop_settings'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} give/remove subscriptions from shop?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="4096" @if($permissions['can_give_subscription'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} edit subscriptions from shop?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="256" @if($permissions['can_edit_subscriptions'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} manage name icons?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="65536" @if($permissions['can_manage_name_icons'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} manage name effects?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="16384" @if($permissions['can_manage_name_effects'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} manage voucher codes?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="32768" @if($permissions['can_manage_voucher_codes'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>SHOP: Can {{ $group->title }} manage themes?</b><br />
              <i style="font-size: 0.7rem;">Are they Community Administration or Site Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="adminperm" value="4194304" @if($permissions['can_manage_themes'] == 1) checked="checked" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>


          <div class="small-12 column" style="margin-bottom: 1rem;">
              <div class="small-12 medium-6 column">
                  <b>SHOP: Can {{ $group->title }} manage voucher codes?</b><br />
                  <i style="font-size: 0.7rem;">Are they Community Administration or Senior Administration?</i>
              </div>
              <div class="small-12 medium-6 column">
                  <input type="checkbox" class="adminperm" value="68719476736" @if($permissions['can_admin_bets'] == 1) checked="checked" @endif />
                  <label for="formodperm7">
                        <span><span></span></span>
                  </label>
              </div>
          </div>
        </div>
                    <button onclick="saveAdminPerms();" class="pg-red headerRed gradualfader fullWidth topBottom" >Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var saveAdminPerms = function() {
    var groupid = {{ $group->usergroupid }};

    var permissions = 0;
    $('input:checkbox.adminperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;
    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroup/adminpermissions',
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
    saveAdminPerms = null;
  }
</script>
