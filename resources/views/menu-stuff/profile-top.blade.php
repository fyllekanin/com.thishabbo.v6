@if(Auth::check())
  <?php
    $admincp = \App\Helpers\UserHelper::haveAdminPerm(Auth::user()->userid, 1);
    $staff = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 1);
    $username = \App\Helpers\UserHelper::getUserName(Auth::user()->userid);
  ?>

  <div id="top-navigation">
    <ul>
      <li>
        <div id="hovernav">
          <a href="/profile/{{ Auth::user()->username }}" class="sub-menu web-page bold">
            <span>{!! $username !!}</span>
            <i class="fa fa-angle-down" aria-hidden="true"></i>
          </a>
        </div>
        <ul>
          <li><a href="/usercp/shop" class="web-page">ThisHabboShop</a></li>
          <li><a href="/usercp/credits" class="web-page">Buy THD</a></li>
          @if($staff || $admincp)
            <div class="divider"></div>
            @if($admincp)
              <li><a href="/admincp" class="web-page">AdminCP</a></li>
            @endif
            @if($staff)
              <li><a href="/staff" class="web-page">StaffCP</a></li>
            @endif
          @endif
          <div class="divider"></div>
          <li><a href="/usercp" class="web-page">UserCP</a></li>
          <li><a href="/forum/newposts/page/1" class="web-page">New Posts</a></li>
          <li><a href="/usercp/pm" class="web-page">Private Messages</a></li>
          <div class="divider"></div>
          <li><a href="/profile/{{ Auth::user()->username }}" class="web-page">Profile</a></li>
          <li><a href="/rules" class="web-page">Site Rules</a></li>
          <li><a onclick="signOut();">Logout</a></li>
          <div class="divider"></div>
          <li><a onclick="markAllRead();">Mark All Read</a></li>

        </ul>
      </li>
      <li>
        <div id="hovernav">
          <a href="/forum/newposts/page/1" class="sub-menu web-page bold">
            <i class="fa fa-users" aria-hidden="true"></i>
          </a>
        </div>
      </li>
      <li>
        <div id="hovernav">
          <a href="/search" class="sub-menu web-page bold">
            <i class="fa fa-search" aria-hidden="true"></i>
          </a>
        </div>
      </li>
      <div class="top-button globe-size">
        <div class="new-notis new-notif new-tag-pc">New</div>
        <i class="fa fa-globe" aria-hidden="true" style="font-size: 13px;"></i>
        <div class="globe-content">
          <div class="notification-top">
            <b>Notifications</b>
            <a class="notification-settings notifPos web-page" href="/usercp/notifications">See More</a>
            <a class="notification-settings notifPos web-page">-</a>
            <a class="notification-settings notifPos" onclick="clearAllNotifications();">Clear</a>
          </div>
          <div class="notification-notises">
          </div>
        </div>
      </div>
    </ul>
  </div>
@else
  <a href="/register" class="web-page sub-menu bold"><button class="top-button">Register, IT'S FREE!</button></a> <a href="/login" class="web-page"><button class="top-button" id="login_button_display">Login</button></a>
@endif


@if(Auth::check())
<script>
    $('.notification-notises').slimScroll({
      height: '13rem',
      railVisible: true,
      alwaysVisible: true,
      width: '100%'
    });
    if(typeof urlRoute !== 'undefined') {
      urlRoute.loadNotifications(true);
    }
    
    var clearAllNotifications = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/notices/clear',
            type: 'post',
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.currentNotis = 0;
                    urlRoute.reloadTitle();
                    $('.notification-notises').html('');
                    $('.new-tag-pc').fadeOut();
                    $('.new-tag-phone').fadeOut();
                    urlRoute.ohSnap('Notifications cleared!', 'green');
                }
            }
        });
    }

    var markAllRead = function() {
      $.ajax({
          url: urlRoute.getBaseUrl() + 'forum/markall',
          type: 'post',
          success: function(data) {
              if(data['response'] == true) {
                  urlRoute.ohSnap('Marked all read!', 'green');
              }
          }
      });
    }

</script>
@endif
