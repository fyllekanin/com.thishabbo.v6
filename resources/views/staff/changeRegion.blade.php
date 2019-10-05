<?php $avatar = \App\Helpers\UserHelper::getAvatar(Auth::user()->userid); ?>
<script> urlRoute.setTitle("TH - Change Region");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Change Region
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
            <span>Change Region</span>
    </div>
            <div class="content-ct">
                <label for="reg-form-password">Region</label>
                <select id="edit-user-region" class="login-form-input">
                    <option value="" @if(Auth::user()->region == '')selected="selected"@endif>Not Set</option>
                    <option value="EU" @if(Auth::user()->region == 'EU')selected="selected"@endif>(EU) Europe</option>
                    <option value="NA" @if(Auth::user()->region == 'NA')selected="selected"@endif>(NA) North America</option>
                    <option value="OC" @if(Auth::user()->region == 'OC')selected="selected"@endif>(OC) Oceania</option>
                </select>
                <br />
                <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="saveRegion();">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var saveRegion = function() {
    var region = $('#edit-user-region').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/region/save',
      type: 'post',
      data: {region:region},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('Your region has been changed!', 'green');
          urlRoute.loadPage('/staff/region');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    saveRegion = null;
  }
</script>
