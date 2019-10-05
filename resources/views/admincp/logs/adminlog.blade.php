<script> urlRoute.setTitle("TH - Admin Log");</script>


<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Admin Log</span>
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
                    <span>Admin Log</span>
                    <a onclick="filter();" class="web-page headerLink white_link">Filter</a>
                  </div>
          <label for="bbcode-form-name">Filter by Admin</label>
           <input type="text" placeholder="Username" class="login-form-input" id="admin-name-filter" @if(isset($_GET['username'])) value="{{ $_GET['username'] }}" @endif/>
          <label for="bbcode-form-name">Filter by Content</label>
          <select class="login-form-input" id="content-id-filter">
            <option value="" @if(isset($_GET['content']) && $_GET['content'] == '') selected="selected" @endif>All</option>
            <option value="1" @if(isset($_GET['content']) && $_GET['content'] == 1) selected="selected" @endif>Usergroup</option>
            <option value="10" @if(isset($_GET['content']) && $_GET['content'] == 10) selected="selected" @endif>Subcriptions (Users)</option>
            <option value="2" @if(isset($_GET['content']) && $_GET['content'] == 2) selected="selected" @endif>Subscriptions (Packages)</option>
            <option value="3" @if(isset($_GET['content']) && $_GET['content'] == 3) selected="selected" @endif>User</option>
            <option value="4" @if(isset($_GET['content']) && $_GET['content'] == 4) selected="selected" @endif>BBcode</option>
            <option value="5" @if(isset($_GET['content']) && $_GET['content'] == 5) selected="selected" @endif>Moderation Forum</option>
            <option value="6" @if(isset($_GET['content']) && $_GET['content'] == 6) selected="selected" @endif>Maintenance</option>
            <option value="7" @if(isset($_GET['content']) && $_GET['content'] == 7) selected="selected" @endif>Automated Thread</option>
            <option value="8" @if(isset($_GET['content']) && $_GET['content'] == 8) selected="selected" @endif>Forum</option>
            <option value="11" @if(isset($_GET['content']) && $_GET['content'] == 9) selected="selected" @endif>Badges To User</option>
            <option value="12" @if(isset($_GET['content']) && $_GET['content'] == 12) selected="selected" @endif>Manage Badges</option>
            <option value="13" @if(isset($_GET['content']) && $_GET['content'] == 13) selected="selected" @endif>Prefixes</option>
            <option value="14" @if(isset($_GET['content']) && $_GET['content'] == 14) selected="selected" @endif>Name Icons</option>
            <option value="15" @if(isset($_GET['content']) && $_GET['content'] == 15) selected="selected" @endif>Voucher Codes</option>
            <option value="16" @if(isset($_GET['content']) && $_GET['content'] == 16) selected="selected" @endif>Name Effect</option>
            <option value="17" @if(isset($_GET['content']) && $_GET['content'] == 17) selected="selected" @endif>Manage Prefixes</option>
            <option value="18" @if(isset($_GET['content']) && $_GET['content'] == 18) selected="selected" @endif>Manage XP Level</option>
            <option value="19" @if(isset($_GET['content']) && $_GET['content'] == 19) selected="selected" @endif>Manage Site Notices</option>
          </select>
          <br />
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Admin</th>
              <th>Description</th>
              <th>Content</th>
              <th>Artifact</th>
              <th>Affected User</th>
              <th>Time</th>
            </tr>
            @foreach($logs as $log)
              <tr>
                <td>{{ $log['admin_username'] }}</td>
                <td>{{ $log['description'] }}</td>
                <td>{{ $log['content'] }}</td>
                <td>{!! $log['contentid'] !!}</td>
                <td>{!! $log['affected_user'] !!}</td>
                <td>{{ $log['time'] }}</td>
              </tr>
            @endforeach
          </table>
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
    var name = $('#admin-name-filter').val();
    var content = $('#content-id-filter').val();
    if(name.length > 0) {
      name = '?username='+name;
    }
    if(content.length > 0) {
      content = name.length > 0 ? '&content='+content : '?content='+content;
    }
    urlRoute.loadPage('admincp/adminlog/page/1'+name+content);
  }

  var destroy = function() {
    filter = null;
  }
</script>
