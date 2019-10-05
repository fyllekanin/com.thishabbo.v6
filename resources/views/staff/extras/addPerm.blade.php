<script> urlRoute.setTitle("TH - Add Perm Show");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Add Perm Shows</span>
    </div>
  </div>
</div>

<div class="small-4 column">
  @include('staff.menu')
</div>

<div class="small-8 medium-8 large-8 column">
              <div class="contentHeader headerRed">
                Add Perm Show
                <a href="/staff/perm/manage" class="headerLink white_link web-page">Back</a>
              </div>
  <div class="content-holder"><div class="content">
            <div class="content-ct">
          <label for="perm-form-username">Username</label>
          <input type="text" id="perm-form-username" placeholder="Username..." class="login-form-input"/>
          <label for="perm-form-type">Type</label>
          <select id="perm-form-type" class="login-form-input">
            <option value="0">Radio</option>
            <option value="1">Event</option>
          </select>
          <label for="perm-form-day">Day</label>
          <select id="perm-form-day" class="login-form-input">
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="7">Sunday</option>
          </select>
          <label for="perm-form-hour">Hour</label>
          <select id="perm-form-hour" class="login-form-input">
            <option value="0">12 AM</option>
            <option value="1">1 AM</option>
            <option value="2">2 AM</option>
            <option value="3">3 AM</option>
            <option value="4">4 AM</option>
            <option value="5">5 AM</option>
            <option value="6">6 AM</option>
            <option value="7">7 AM</option>
            <option value="8">8 AM</option>
            <option value="9">9 AM</option>
            <option value="10">10 AM</option>
            <option value="11">11 AM</option>
            <option value="12">12 PM</option>
            <option value="13">1 PM</option>
            <option value="14">2 PM</option>
            <option value="15">3 PM</option>
            <option value="16">4 PM</option>
            <option value="17">5 PM</option>
            <option value="18">6 PM</option>
            <option value="19">7 PM</option>
            <option value="20">8 PM</option>
            <option value="21">9 PM</option>
            <option value="22">10 PM</option>
            <option value="23">11 PM</option>
          </select>
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" style="margin-top: 16px;" onclick="addPerm();">Add</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var addPerm = function() {
    var username = $('#perm-form-username').val();
    var type = $('#perm-form-type').val();
    var day = $('#perm-form-day').val();
    var time = $('#perm-form-hour').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/manager/perm/add',
      type: 'post',
      data: {username:username, type:type, day:day, time:time},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/staff/perm/manage');
          urlRoute.ohSnap('Perm show added!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');

          urlRoute.ohSnap(data['message'],'red');
        }
      }
    });
  }

  var destroy = function() {
    addPerm = null;
  }
</script>
