<script> urlRoute.setTitle("TH - New Subscription");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Subscription Package</span>
    </div>
  </div>
</div>


<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

                  <div class="contentHeader headerRed">
                    <span>Add New Package</span>
                    <a href="/admincp/list/subscriptions" class="web-page headerLink white_link">Back</a>

                 </div>
  <div class="content-holder">
                <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="sub-form-name" placeholder="Name..." class="login-form-input"/>
          <label for="thcb">THClub Exclusive?</label>
          <select id="thcb" class="login-form-input" name="">
              <option value="1">Yes</option>
              <option value="0">No</option>
          </select>
          <label for="sub-form-userbartext">Userbar Text <i style="font-size: 0.7rem;">(Will only be used if specific userbar feature choosen)</i></label>
          <input type="text" id="sub-form-userbartext" placeholder="Userbar Text..." class="login-form-input"/>
          <label for="sub-form-usergroup">Usergroup</label>
          <select id="sub-form-usergroup" class="login-form-input">
            @foreach($usergroups as $usergroup)
              <option value="{{ $usergroup->usergroupid }}">{{ $usergroup->title }}</option>
            @endforeach
          </select>
          <label for="sub-form-dprice">Price <i style="font-size: 0.7rem;">(Amount of Diamonds, monthly)</i></label>
          <input type="number" id="sub-form-dprice" placeholder="20" class="login-form-input"/>
          <label for="sub-form-usernamefeature">Username Feature <i style="font-size: 0.7rem;">(These colors are customizable by the user)</i></label>
          <select id="sub-form-usernamefeature" class="login-form-input">
            <option value="0">None (will use the usergroups name color)</option>
            <option value="2">One Color Username</option>
            <option value="4">Rainbow Username</option>
            <option value="8">Custom Rainbow</option>
          </select>
          <label for="sub-form-userbarfeature">Userbar Feature <i style="font-size: 0.7rem;">(These colors are customizable by the user)</i></label>
          <select id="sub-form-userbarfeature" class="login-form-input">
            <option value="0">None (will use the usergroups bar color)</option>
            <option value="16">One Color Userbar</option>
            <option value="32">Rainbow Userbar</option>
            <option value="64">Custom Rainbow Userbar</option>
          </select>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(What do they get?)</i></label>
          <textarea id="sub-form-description" class="login-form-input"></textarea>
          <label for="sub-form-days">Days <i style="font-size: 0.7rem;">(How many days do they get)</i></label>
          <input type="text" id="sub-form-days" class="login-form-input"></input>
        </div><br />
                <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="savePackage();">Save</button>
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
    var days = $('#sub-form-days').val();
    var thcb = $('#thcb').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/subscription/add',
      type: 'post',
      data: {thcb:thcb, name:name, days:days, userbartext:userbartext, usergroup:usergroup, dprice:dprice, usernamefeature:usernamefeature, userbarfeature:userbarfeature, description:description},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/list/subscriptions');
          urlRoute.ohSnap(name + ' was added!', 'green');
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
