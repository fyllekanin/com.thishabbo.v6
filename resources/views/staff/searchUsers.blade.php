<script> urlRoute.setTitle("TH - Search Users");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">Staff Panel</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Search Users</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder">
    <div class="content">
  <div class="contentHeader headerRed">
    <span>Search Users</span>
  </div>
      <input type="text" id="search-input-username" placeholder="Username..." class="login-form-input"/>
      <br />
      <button onclick="massSearch();" class="pg-red headerRed gradualfader fullWidth topBottom">Search</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var massSearch = function() {
    var username = $('#search-input-username').val();
     urlRoute.loadPage('/staff/mod/users/'+username+'/page/1');
  }

  var destroy = function() {
    exactMatch = null;
    massSearch = null;
  }
</script>
