<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Welcome Bot");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Welcome Bot</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

          <div class="contentHeader headerRed">
                  Welcome Bot
            </div>
  <div class="content-holder">
      <div class="content">
          <div class="content-ct">
          <fieldset>
              <textarea id="bot_editor" style="height: 150px; font-size:12px !important;">{{ $text }}</textarea>
          </fieldset>
          </div>
          <br />

            <button type="button" class="pg-red headerRed gradualfader fullWidth topBottom" onclick="updateBot()">Save</button>
      </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
  $(document).foundation();

  $("#bot_editor").wysibb();
      main_editor_event = $('.mainEditor .wysibb-body').keydown(function(e){
        if(e.which == 83 && e.altKey) {
          e.preventDefault();
          updateBot();
        }
      });
  });

  var updateBot = function () {
      var text = $('#bot_editor').bbcode();

      $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/bot/update',
          type: 'post',
          data: {text:text},
          success: function(data) {
              if(data['response'] == true) {
                  urlRoute.ohSnap("Success!",'green');
                  urlRoute.loadPage('admincp/bot')
              } else {
                  urlRoute.ohSnap(data['message'],'red');
              }
          }
      });
  }




  var destroy = function() {
  }
</script>
