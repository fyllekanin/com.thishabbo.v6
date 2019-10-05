<script> urlRoute.setTitle("TH - Issue Points");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Add/Remove THC from User</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
              <div class="contentHeader headerGreen">
                Add/Remove THC from User
              </div>
  <div class="content-holder"><div class="content">

          <label for="points-form-username">Username</label>
          <input type="text" id="points-form-username" placeholder="Username..." class="login-form-input" />

          <label for="points-form-amount">Amount of THC to issue...</label>
          <input type="number" id="points-form-amount" placeholder="Number..." class="login-form-input" />
          <label for="points-form-action">Add or remove THC...</label>
          <select id="points-form-action" class="login-form-input">
            <option value="Add" selected="">Add</option>
            <option value="Remove">Remove</option>
          </select>
         <label for="points-form-reason">Reason for THC issuing...</label>
          <input type="text" id="points-form-reason" placeholder="Reason..." class="login-form-input" /><br>
            <button class="pg-red headerGreen gradualfader fullWidth topBottom" onclick="issuePoints();">Issue THC</button>
        </div>
    </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var issuePoints = function() {
    var username = $('#points-form-username').val();
    var amount = $('#points-form-amount').val();
    var action = $('#points-form-action').val();
    var reason = $('#points-form-reason').val();
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/points/submit',
      type: 'post',
      data: {username:username, amount:amount, action:action, reason:reason},
      success: function(data) {
        urlRoute.ohSnap('THC added/deducted from user successfully!', 'green');
        urlRoute.loadPage('/admincp/points/issue');
      }
    })
  }

  var destroy = function() {
    issuePoints = null;
  }
</script>
