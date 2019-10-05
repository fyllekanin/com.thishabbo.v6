<script> urlRoute.setTitle("TH - Event Booking Log");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Events Booking Log</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                Events Booking Logs
                <a onclick="filter();" class="web-page headerLink white_link">Filter</a>
              </div>
    <div class="content-ct">
        <div class="small-12">
          <label for="sub-form-name">Filter by User</label> <input type="text" placeholder="Username" class="login-form-input" id="user-name-filter" @if(isset($_GET['username'])) value="{{ $_GET['username'] }}" @endif/>
          <label for="sub-form-name">Filter by Content</label>
          <select class="login-form-input" id="action-id-filter">
            <option value="" @if(isset($_GET['action']) && $_GET['action'] == '') selected="selected" @endif>All</option>
            <option value="1" @if(isset($_GET['action']) && $_GET['action'] == 1) selected="selected" @endif>Booked Slot</option>
            <option value="2" @if(isset($_GET['action']) && $_GET['action'] == 2) selected="selected" @endif>Unbooked Slot</option>
          </select>
          <br />
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>User</th>
              <th>Action</th>
              <th>Slot</th>
              <th>Affected User</th>
              <th>Time</th>
            </tr>
            @foreach($logs as $log)
              <tr>
                <td>{!! $log['action_username'] !!}</td>
                <td>{!! $log['action'] !!}</td>
                <td>{{ $log['day'] }} at {{ $log['time'] }}:00 UTC</td>
                <td>{!! $log['affected_user'] !!}</td>
                <td>{{ $log['dateline'] }}</td>
              </tr>
            @endforeach
          </table>
        </div>
    </div>
    </div>
  </div>
  <div class="content-holder">
    <div class="content">
      {!! $pagi !!}
    </div>
  </div>
</div>
<script type="text/javascript">
  var filter = function() {
    var name = $('#user-name-filter').val();
    var action = $('#action-id-filter').val();
    if(name.length > 0) {
      name = '?username='+name;
    }
    if(action.length > 0) {
      action = name.length > 0 ? '&action='+action : '?action='+action;
    }
    urlRoute.loadPage('staff/logs/events/page/1'+name+action);
  }

  var destroy = function() {
    filter = null;
  }
</script>
