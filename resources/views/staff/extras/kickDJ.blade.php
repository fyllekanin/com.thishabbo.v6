<script> urlRoute.setTitle("TH - Kick DJ");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Trial Radio
            </div>
    </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
            <span>Shoutcast Admin</span>
    </div>
            <div class="content-ct">
              <div class="alertt alert-danger" role="alertt"><b>{{ $currentdj }}</b> is currently on air with <b>{{ $currentlisteners }} listeners!</b></div><br />
              Are you sure you would like to kick this DJ?
              <button id="signin-now" onclick="kickDJ();" class="pg-red headerRed gradualfader fullWidth topBottom" style="margin-top: 16px;">Kick DJ</button>
            </div>
        </div>
    </div>
</div>
<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
            <span>Latest Radio DJ Kick Log</span>
    </div>
            <div class="content-ct">
              <table class="responsive" style="width: 100%;">
                <tr>
                  <th>User</th>
                  <th>DJ Kicked</th>
                  <th>Time</th>
                </tr>
                @foreach($kicklogs as $kicklog)
                <tr>
                  <td>{!! $UserHelper::getUsername($kicklog->userid) !!}</td>
                  <td>{{ $kicklog->dj_kicked }}</td>
                  <td>{{ $ForumHelper::timeAgo($kicklog->dateline) }}</td>
                </tr>
                @endforeach
              </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var kickDJ = function() {
    $.ajax({
          url: urlRoute.getBaseUrl() + 'staff/manage/kickdj',
          type: 'post',
          data: {timetableid:0},
          success: function(data) {
            urlRoute.loadPage('/staff/manage/kick');
            urlRoute.ohSnap('DJ Kicked!', 'green');
          }
        });
  }

  var destroy = function() {
    kickDJ = null;
  }
</script>
