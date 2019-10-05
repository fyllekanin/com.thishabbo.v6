<script> urlRoute.setTitle("TH - Manage Staff List");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Staff List</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>Add usergroup to staff list</span>
                  </div>
  <div class="content-holder">
                <div class="content">
      <div class="content-ct">
          <label for="staff-form-group">Usergroup</label>
          <select id="staff-form-group" class="login-form-input">
            @foreach($usergroups as $usergroup)
              <option value="{{ $usergroup->usergroupid }}">{{ $usergroup->title }}</option>
            @endforeach
          </select>
          <label for="custom-role">Should the usergroup use Custom Role?</label>
          <select id="custom-role" class="login-form-input">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
          <label for="staff-form-color">Topic Color</label>
          <select id="staff-form-color" class="login-form-input">
            <option value="headerBlue">Blue</option>
            <option value="headerBlack">Black</option>
            <option value="headerPurple">Pink</option>
            <option value="headerRed">Red</option>
            <option value="headerGreen">Green</option>
          </select>
        <label for="staff-form-display">Display Order</label>
          <input type="number" id="staff-form-display" placeholder="Display Order..." class="login-form-input"/>
        <br />
        <button class="pg-red headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="postAddStaff();">Save</button>
        </div>
  </div>
</div>

                  <div class="contentHeader headerGreen">
                    <span>Add usergroup to staff list</span>
                  </div>
  <div class="content-holder">
                <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Title</th>
              <th>Display Order</th>
              <th>Colour</th>
              <th>Custom Role</th>
              <th>Edit</th>
            </tr>
            @foreach($groups as $group)
              <tr>
                <td>{{ $group['title'] }}</td>
                <td>{{ $group['displayorder'] }}</td>
                <td>{{ $group['color'] }}</td>
                <td>@if($group['custom'] == 1) Yes @else No @endif</td>
                <td onclick="postRemoveStaff({{ $group['groupid'] }});"><i class="fa fa-trash" aria-hidden="true"></i></td>
              </tr>
            @endforeach
          </table>
      </div>
  </div>
  </div>

  <script type="text/javascript">
    var postAddStaff = function() {
      var groupid = $('#staff-form-group').val();
      var displayorder = $('#staff-form-display').val();
      var color = $('#staff-form-color').val();
      var customrole = $('#custom-role').val();

      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/settings/add/staff',
        type: 'post',
        data: {groupid:groupid, displayorder:displayorder, color:color, customrole:customrole},
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.loadPage('/admincp/settings/staff/list');
            urlRoute.ohSnap('Usergroup added to staff list!', 'green');
          } else {
            urlRoute.ohSnap(data['message'], 'red');
          }
        }
      })
    }

    var postRemoveStaff = function(groupid) {
      if(confirm('Are you sure you want to remove this usergroup from the Staff List?')) {
        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/settings/remove/staff',
          type: 'post',
          data: {groupid:groupid},
          success: function(data) {
            urlRoute.loadPage('/admincp/settings/staff/list');
            urlRoute.ohSnap('Usergroup removed from staff list!', 'greed');
          }
        })
      }
    }

    var destroy = function() {
      postAddStaff = null;
      postRemoveStaff = null;
    }
  </script>
