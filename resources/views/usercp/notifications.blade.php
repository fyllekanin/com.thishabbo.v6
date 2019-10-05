<script> urlRoute.setTitle("TH - Notifications");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
  <span><a href="/home" class="bold web-page">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <span><a href="/usercp/shop" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> All Notifications
    </div>
  </div>
</div>

<div class="large-12 column">
                <div class="contentHeader headerRed">
                  <a onclick="clearAllNotifications()" class="web-page headerLink white_link">
                    Clear
                  </a>
                    Notifications
                </div>
    <div class="content-holder">
            <div class="content">
                <div class="content-ct">
                    @foreach($new_notifications as $notification)
                        <div class="notif-box" id="{{ $notification['notificationid'] }}">
                            <div class="notif-avatar">
                                <div class="notif-show" style="background-image: url('{{ $notification['avatar'] }}'); background-size: 100%; background-repeat: no-repeat;"></div>
                            </div>
                            <div class="notif-text">
                                {!! $notification['message'] !!}
                                <i style="font-size: 0.7rem;"><i class="fa fa-tag" aria-hidden="true"></i> {{ $notification['time'] }} - Unread</i>
                            </div>
                        </div>
                    @endforeach
                    @foreach($read_notifications as $notification)
                      <div class="notif-box" id="{{ $notification['notificationid'] }}">
                          <div class="notif-avatar">
                              <div class="notif-show" style="background-image: url('{{ $notification['avatar'] }}'); background-size: 100%; background-repeat: no-repeat;"></div>
                          </div>
                          <div class="notif-text">
                              {!! $notification['message'] !!}
                              <i style="font-size: 0.7rem;"><i class="fa fa-tag" aria-hidden="true"></i> {{ $notification['time'] }}</i>
                          </div>
                      </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="small-12 medium-12 large-12 column">
        <div class="content-holder">
            <div class="content">
                {!! $pagi !!}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var readNotifications = function(notificationid, fadeOut = 1) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'xhrst/' + 'usercp/notices/read/'+notificationid,
      type: 'get',
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.currentNotis--;
          urlRoute.reloadTitle();
          if(fadeOut == 1) {
            $('#notification-'+notificationid).fadeOut();
          }

          if(data['amount'] < 1) {
            $('.new-tag-pc').fadeOut();
            $('.new-tag-phone').fadeOut();
          }

          urlRoute.reloadTitle();
        }
      }
    });
  }

  var clearAllNotifications = function () {
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
                  urlRoute.loadPage('usercp/notifications');
              }
          }
      });
  }


  $("body").on('click', '.notif-link', function(event) {
    event.preventDefault();
    id = $(this).parent().parent().attr('id');
    readNotifications(id);
   });
});

</script>
