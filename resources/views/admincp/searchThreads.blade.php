<script> urlRoute.setTitle("TH - Search Threads");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Search Threads</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Search Threads</span>
            </div>
            <input type="text" id="search-input-threadid" placeholder="Thread ID" class="login-form-input"/>
            <br />
            <button onclick="Search();" class="pg-red headerRed gradualfader fullWidth topBottom barFix">Search</button>
        </div>
    </div>
</div>


<script type="text/javascript">
  var Search = function() {
    var threadid = $('#search-input-threadid').val();
     urlRoute.loadPage('/admincp/threads/'+threadid);
  }

  var destroy = function() {
    exactMatch = null;
    massSearch = null;
  }
</script>
