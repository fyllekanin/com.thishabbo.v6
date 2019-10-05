<script> urlRoute.setTitle("TH - Search Users");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Search IP Address</span>
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
    <span>Search IP Address</span>
  </div>
      <input type="text" id="search-input-ip" placeholder="IP Address..." class="login-form-input"/>
      <br />
      <button onclick="massSearch();" class="pg-red headerRed gradualfader fullWidth topBottom">Search</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var massSearch = function() {
    var ipaddress = $('#search-input-ip').val();
     urlRoute.loadPage('/staff/mod/users/similar/'+ipaddress+'');
  }

  var destroy = function() {
    exactMatch = null;
    massSearch = null;
  }
</script>
