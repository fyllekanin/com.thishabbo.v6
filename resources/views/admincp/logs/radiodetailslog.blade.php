<script> urlRoute.setTitle("TH - Radio Details Log");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Radio Details Log</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>

<div class="medium-8 column">

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
        Radio Details Log
        <a onclick="filter();" class="web-page headerLink white_link">Filter</a>
    </div>
            <div class="content-ct">
                <div class="small-12">
                    <label for="sub-form-name">Filter by User</label>
                    <input type="text" placeholder="Username" class="login-form-input" id="user-name-filter" @if(isset($_GET['username'])) value="{{ $_GET['username'] }}" @endif/>
                    <label for="sub-form-name">Filter by Content</label>
                    <select class="login-form-input" id="action-id-filter">
                        <option value="" @if(isset($_GET['action']) && $_GET['action'] == '') selected="selected" @endif>All</option>
                        <option value="1" @if(isset($_GET['action']) && $_GET['action'] == 1) selected="selected" @endif>Successfully Viewed Radio Info</option>
                        <option value="2" @if(isset($_GET['action']) && $_GET['action'] == 2) selected="selected" @endif>Unsuccessfully Viewed Radio Info</option>
                    </select>
                    <br />
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                        @foreach($logs as $log)
                            <tr>
                                <td>{!! $log['username'] !!}</td>
                                <td>{!! $log['action'] !!}</td>
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
    urlRoute.loadPage('admincp/radiodetailslog/page/1'+name+action);
  }

  var destroy = function() {
    filter = null;
  }
</script>
