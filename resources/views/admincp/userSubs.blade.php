<script> urlRoute.setTitle("TH - Moderation Forums");</script>


<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>{{ $user->username }}'s subscriptions</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>{{ $user->username }}'s subscriptions</span>
                    <a href="/admincp/users/all/page/1" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
        <div class="small-12 column">
          <label for="new-sub-form-package">Subscription</label>
          <select id="new-sub-form-package" class="login-form-input">
            @foreach($available_subs as $available_sub)
              <option value="{{ $available_sub['packageid'] }}">{{ $available_sub['name'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="small-12 column">
          <label for="new-sub-form-package">End Date</label>
          <input type="date" id="new-sub-form-end_date" class="login-form-input" />
        </div>
        <div class="small-12 column">
        <br>
        <button onclick="addSub();" class="pg-red headerGreen gradualfader fullWidth topBottom">Add</button>
        </div>
      </div>
    </div>




                  <div class="contentHeader headerPurple">
                    <span>{{ $user->username }}'s Current Subscriptions</span>
                  </div>
      <div class="content-holder">
        <div class="content">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Subscription Name</th>
              <th>Started</th>
              <th>Ends at</th>
              <th></th>
            </tr>
            @foreach($users_subs as $users_sub)
              <tr id="users_sub-{{ $users_sub['subid'] }}">
                <td>{{ $users_sub['name'] }}</td>
                <td>{{ $users_sub['started'] }}</td>
                <td>{{ $users_sub['ends'] }}</td>
                <td onclick="removeSub({{ $users_sub['subid'] }});">Remove</td>
              </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">

  var userid = {{ $user->userid }};

  var addSub = function() {
    var packageid = $('#new-sub-form-package').val();
    var end_date = $('#new-sub-form-end_date').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/user/add/subscription',
      type: 'post',
      data: {packageid:packageid, userid:userid, end_date:end_date},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('Subscription Added!', 'green');
          urlRoute.loadPage('/admincp/user/'+userid+'/subscriptions');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var removeSub = function(subid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/user/remove/subscription',
      type: 'post',
      data: {subid:subid, userid:userid},
      success: function(data) {
        urlRoute.ohSnap('Subscription removed!', 'green');
        $('#users_sub-'+subid).fadeOut();
      }
    })
  }

  var destroy = function() {
    addSub = null;
    removeSub = null;
  }
</script>
