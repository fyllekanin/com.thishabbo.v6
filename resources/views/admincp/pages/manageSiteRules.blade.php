<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - Site Rules");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Site Rules</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder">
          <div class="contentHeader headerRed">  
          <span>Site Rules</span>
        </div>
      <div class="mainEditor">
      <div class="content-ct">
        <textarea id="rules_editor" style="height: 150px;">{{ $rules }}</textarea>
          @if($UserHelper::haveAdminPerm(Auth::user()->userid, 1048576))
          <br><br><br><br>
          <button onclick="saveRules();" class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right;margin: -40px 0px 0px 0px;">Save</button>
          @endif
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#rules_editor").wysibb();
  });
  @if($UserHelper::haveAdminPerm(Auth::user()->userid, 1048576))
  var saveRules = function() {
    var content = $('#rules_editor').bbcode();
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/save/rules',
      type: 'post',
      data: {content:content},
      success: function(data) {
        urlRoute.ohSnap('Site Rules Updated!', 'green');
        urlRoute.loadPage('/admincp/site/rules');
      }
    })
  }
  @endif
  var destroy = function() {
    saveRules = null;
  }
</script>
