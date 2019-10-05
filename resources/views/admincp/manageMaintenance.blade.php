<script> urlRoute.setTitle("TH - Manage Maintenance");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Maintenance</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerGreen">
                    <span>Maintenance</span>
        </div>
  <div class="content-holder">
      <div class="content">
      <div class="content-ct">
        @if($active == 0)
          Currently there is no maintenance ongoing, if you wish to turn the maintenance on just fill in message that will appear for the users and start it. <br /><br />
          <i>Note: Anyone with access to AdminCP will be able to pass the maintenance mode!</i>
          <br />
          <br />
          <textarea id="maintenance_text" class="login-form-input">Reason for maintenance here!</textarea>
                    <br />
          <button class="pg-red headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="startMaintenance();">Turn On</button>
        @else
          <b>Maintenance Mode is currently enabled!</b>
          <br /><br />
          <b>By:</b> {!! $username !!} <br /><br />
          <b>Reason:</b> {{ $reason }} <br />
          <br />
                    <br />
          <button class="pg-red headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="stopMaintenance();">Turn Off</button>
        @endif
      </div>
  </div>
</div>

        <div class="contentHeader headerGreen">
                    Last 10 Maintenances
              </div>
  <div class="content-holder">
      <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Reason</th>
              <th>User</th>
              <th>Date</th>
            </tr>
            @foreach($maintenances as $maintenance)
              <tr>
                <td>{{ $maintenance['reason'] }}</td>
                <td>{!! $maintenance['username'] !!}</td>
                <td>{{ $maintenance['date'] }}</td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  var startMaintenance = function() {
    var reason = $('#maintenance_text').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/maintenance/start',
      type: 'post',
      data: {reason:reason},
      success: function(data) {
        urlRoute.loadPage('/admincp/settings/maintenances');
        urlRoute.ohSnap('Maintenance Mode Enabled!', 'green');
      }
    });
  }

  var stopMaintenance = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/maintenance/stop',
      type: 'post',
      success: function(data) {
        urlRoute.loadPage('/admincp/settings/maintenances');
        urlRoute.ohSnap('Maintenance Mode Disabled!', 'red');
      }
    });
  }

  var destroy = function() {
    startMaintenance = null;
    stopMaintenance = null;
  }
</script>
