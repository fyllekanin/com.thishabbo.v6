<script> urlRoute.setTitle("TH - Edit Perm Show");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="inner-content-holder"><div class="content">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">Staff Panel</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Perm Show</span>
    </div></div>
  </div>
</div>

<div class="small-12 medium-5 large-3 column">
  @include('staff.menu')
</div>
<div class="small-12 medium-7 large-9 column">
  <div class="content-holder"><div class="content">
              <div class="contentHeader headerRed">
                Edit Perm Show
              </div>
            <div class="content-ct">
        <div class="medium-6 column">
          <label for="perm-form-username">Username</label>
          <input type="text" id="perm-form-username" value="{{ $username }}" class="login-form-input"/>
        </div>
        <div class="medium-6 column">
          <label for="perm-form-type">Type</label>
          <select id="perm-form-type" class="login-form-input">
            <option value="0" @if($type == 0) selected="" @endif >Radio</option>
            <option value="1" @if($type == 1) selected="" @endif >Event</option>
          </select>
        </div>
        <div class="medium-6 column">
          <label for="perm-form-day">Day</label>
          <select id="perm-form-day" class="login-form-input">
            <option value="1" @if($day == 1) selected="" @endif >Monday</option>
            <option value="2" @if($day == 2) selected="" @endif >Tuesday</option>
            <option value="3" @if($day == 3) selected="" @endif >Wednesday</option>
            <option value="4" @if($day == 4) selected="" @endif >Thursday</option>
            <option value="5" @if($day == 5) selected="" @endif >Friday</option>
            <option value="6" @if($day == 6) selected="" @endif >Saturday</option>
            <option value="7" @if($day == 7) selected="" @endif >Sunday</option>
          </select>
        </div>
        <div class="medium-6 column">
          <label for="perm-form-hour">Hour</label>
          <select id="perm-form-hour" class="login-form-input">
            <option value="0" @if($time == 0) selected="" @endif >12 AM</option>
            <option value="1" @if($time == 1) selected="" @endif >1 AM</option>
            <option value="2" @if($time == 2) selected="" @endif >2 AM</option>
            <option value="3" @if($time == 3) selected="" @endif >3 AM</option>
            <option value="4" @if($time == 4) selected="" @endif >4 AM</option>
            <option value="5" @if($time == 5) selected="" @endif >5 AM</option>
            <option value="6" @if($time == 6) selected="" @endif >6 AM</option>
            <option value="7" @if($time == 7) selected="" @endif >7 AM</option>
            <option value="8" @if($time == 8) selected="" @endif >8 AM</option>
            <option value="9" @if($time == 9) selected="" @endif >9 AM</option>
            <option value="10" @if($time == 10) selected="" @endif >10 AM</option>
            <option value="11" @if($time == 11) selected="" @endif >11 AM</option>
            <option value="12" @if($time == 12) selected="" @endif >12 PM</option>
            <option value="13" @if($time == 13) selected="" @endif >1 PM</option>
            <option value="14" @if($time == 14) selected="" @endif >2 PM</option>
            <option value="15" @if($time == 15) selected="" @endif >3 PM</option>
            <option value="16" @if($time == 16) selected="" @endif >4 PM</option>
            <option value="17" @if($time == 17) selected="" @endif >5 PM</option>
            <option value="18" @if($time == 18) selected="" @endif >6 PM</option>
            <option value="19" @if($time == 19) selected="" @endif >7 PM</option>
            <option value="20" @if($time == 20) selected="" @endif >8 PM</option>
            <option value="21" @if($time == 21) selected="" @endif >9 PM</option>
            <option value="22" @if($time == 22) selected="" @endif >10 PM</option>
            <option value="23" @if($time == 23) selected="" @endif >11 PM</option>
          </select>
        </div>
        <button class="pg-red headerRed gradualfader floatright" style="margin-top: 16px;" onclick="addPerm();">Add</button>
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
    var timetableid = {{ $timetableid }};

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/manager/perm/edit',
      type: 'post',
      data: {username:username, type:type, day:day, time:time, timetableid:timetableid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/staff/perm/manage');
          urlRoute.ohSnap('Perm show edited!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');

          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    addPerm = null;
  }
</script>
