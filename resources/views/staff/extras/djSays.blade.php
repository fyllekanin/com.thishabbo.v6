<?php $avatar = \App\Helpers\UserHelper::getAvatar(Auth::user()->userid); ?>
<script> urlRoute.setTitle("TH - Set DJ Says");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Set DJ Says
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
            <span>Set DJ Says</span>
    </div>
            <div class="content-ct">
                @if($can_access)
                <label for="radio-dj-says">Set the DJ Says</label>
                <input type="text" id="radio-dj-says" placeholder="Come on down to the event, search whatever..." class="login-form-input"/><br />
                @else
                <label for="radio-dj-says">Set the DJ Says</label>
                <input type="text" placeholder="Come on down to the event, search whatever..." class="login-form-input" disabled=""/><br />
                <br />
                <div class="alert alert-danger">You are not the current or next DJ, so you are unable to set the DJ says yet.</div>
                @endif
                <label for="radio-dj-says">Current DJ Says</label>
                <div style="padding:5px 0 5px 3px; border:1px dashed #ababab;"><b>{!! $djname !!}</b> says: {{ $djmessage }}</div>
                <br />
                @if($can_access)
                <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="submitDjSays();">Save</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var submitDjSays = function() {
    var message = $('#radio-dj-says').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/radio/djsays/save',
      type: 'post',
      data: {message:message},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('The DJ says has been changed!', 'green');
          urlRoute.loadPage('/staff/radio/djsays');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    submitDjSays = null;
  }
</script>
