<script> urlRoute.setTitle("TH - Link Partners");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Link Partners</span>
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
          <span>Link Partners</span>
        </div>
      <div class="content-ct">
        <textarea id="partners_editor" style="height: 150px;">{{ $linkpartners }}</textarea>

<br>

<button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="savePartners();">Save</button>


      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#partners_editor").wysibb();
  });
  var savePartners = function() {
    var content = $('#partners_editor').bbcode();
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/save/partners',
      type: 'post',
      data: {content:content},
      success: function(data) {
        urlRoute.ohSnap('Link Partners Updated!', 'green');
        urlRoute.loadPage('/admincp/site/partners');
      }
    })
  }
  var destroy = function() {
    savePartners = null;
  }
</script>
