<script> urlRoute.setTitle("TH - Search Users");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Search Users</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Search Users</span>
                  </div>
      <div class="content-holder">
        <div class="content">
        <input type="text" id="search-input-username" placeholder="Username or Habbo Name..." class="login-form-input"/>

<br />
 <button onclick="massSearch();" class="pg-red headerRed gradualfader fullWidth topBottom barFix">Search Username / Habbo</button>

 <button onclick="exactMatch();" class="pg-red headerRed gradualfader fullWidth topBottom">Exact Username Match</button>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var exactMatch = function() {
    var username = $('#search-input-username').val();
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/users/exact',
      type: 'post',
      data: {username:username},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('admincp/users/edit/'+data['userid']);
        } else {
          $('#search-input-username').addClass('form-reg-error');
          urlRoute.ohSnap('No user with that name!', 'red');
        }
      }
    });
  }

  var massSearch = function() {
    var username = $('#search-input-username').val();
     urlRoute.loadPage('/admincp/users/'+username+'/page/1');
  }

  var destroy = function() {
    exactMatch = null;
    massSearch = null;
  }
</script>
