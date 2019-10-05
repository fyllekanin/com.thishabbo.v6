<script> urlRoute.setTitle("TH - Edit Package {{ $name }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Subscription Package: {{ $name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Subscription Package: {{ $name }}</span>
                    <a href="/admincp/list/subscriptions" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
          <label for="sub-form-name">Name</label>
          <input type="text" id="sub-form-name" value="{{ $name }}" class="login-form-input"/>
          <label for="thcb">THClub Exclusive?</label>
          <select id="thcb" class="login-form-input" name="">
              <option value="1">Yes</option>
              <option value="0">No</option>
          </select>
          <label for="sub-form-userbartext">Userbar Text <i style="font-size: 0.7rem;">(Will only be used if specific userbar feature choosen)</i></label>
          <input type="text" id="sub-form-userbartext" value="{{ $userbartext }}" class="login-form-input"/>
          <label for="sub-form-usergroup">Usergroup</label>
          <select id="sub-form-usergroup" class="login-form-input">
            @foreach($usergroups as $usergroup)
              <option value="{{ $usergroup->usergroupid }}" @if($usergroupid == $usergroup->usergroupid) selected="" @endif>{{ $usergroup->title }}</option>
            @endforeach
            <option value="0" @if($usergroupid == 0) selected="" @endif>Usergroup don't exist anymore!</option>
          </select>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;">(Amount of Diamonds, monthly)</i></label>
          <input type="number" id="sub-form-dprice" value="{{ $dprice }}" class="login-form-input"/>
          <label for="sub-form-usernamefeature">Username Feature <i style="font-size: 0.7rem;">(These colors are customizable by the user)</i></label>
          <select id="sub-form-usernamefeature" class="login-form-input">
            <option value="0" @if($usernamefeature == 0) selected="" @endif>None</option>
            <option value="2" @if($usernamefeature == 2) selected="" @endif>One Color Username</option>
            <option value="4" @if($usernamefeature == 4) selected="" @endif>Rainbow Username</option>
            <option value="8" @if($usernamefeature == 8) selected="" @endif>Custom Rainbow</option>
          </select>
          <label for="sub-form-userbarfeature">Userbar Feature <i style="font-size: 0.7rem;">(These colors are customizable by the user)</i></label>
          <select id="sub-form-userbarfeature" class="login-form-input">
            <option value="0" @if($userbarfeature == 0) selected="" @endif>None</option>
            <option value="16" @if($userbarfeature == 16) selected="" @endif>One Color Userbar</option>
            <option value="32" @if($userbarfeature == 32) selected="" @endif>Rainbow Userbar</option>
            <option value="64" @if($userbarfeature == 64) selected="" @endif>Custom Rainbow Userbar</option>
          </select>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(List things they get etc)</i></label>
          <textarea id="sub-form-description" class="login-form-input">{!! $description !!}</textarea>
          <label for="sub-form-days">Days <i style="font-size: 0.7rem;">(How many days do they get)</i></label>
          <input type="text" id="sub-form-days" value="{{ $days }}"class="login-form-input"></input>
          <br>
          <button onclick="savePackage();" class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right">Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var savePackage = function() {
    var name = $('#sub-form-name').val();
    var userbartext = $('#sub-form-userbartext').val();
    var usergroup = $('#sub-form-usergroup').val();
    var dprice = $('#sub-form-dprice').val();
    var usernamefeature = $('#sub-form-usernamefeature').val();
    var userbarfeature = $('#sub-form-userbarfeature').val();
    var description = $('#sub-form-description').val();
    var packageid = {{ $packageid }};
    var days = $('#sub-form-days').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/subscription/edit',
      type: 'post',
      data: {name:name, userbartext:userbartext, days:days, usergroup:usergroup, dprice:dprice, usernamefeature:usernamefeature, userbarfeature:userbarfeature, description:description, packageid:packageid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/list/subscriptions');
          urlRoute.ohSnap(name + ' was edited!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    savePackage = null;
  }
</script>
