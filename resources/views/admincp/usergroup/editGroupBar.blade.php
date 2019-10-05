<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Edit {{ $group->title }} Bar");</script>


<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit Userbar: {{ $group->title }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Userbar: {{ $group->title }}</span>
                    <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
        <div class="medium-6 column">
          <label for="forum-edit-desc">HTML</label>
          <textarea id="current-html" class="login-form-input" style="height: 5rem;">{{ $html }}</textarea>
        </div>
        <div class="medium-6 column">
          <label for="forum-edit-desc">CSS <i style="font-size: 0.7rem;">(Unique names please!)</i></label>
          <textarea id="current-css" class="login-form-input" style="height: 5rem;">{{ $css }}</textarea>
        </div>
        <div style="width: 100%; float: left; text-align: center;">
          <iframe id="live-view" style="border: none;"></iframe>
        </div>
<br>
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveUserBar({{ $group->usergroupid }});">Save</button>
        @if($html != "" OR $css != "") 
          <textarea id="hidden-html" style="display: none;">{{ $html }}</textarea>
          <textarea id="hidden-css" style="display: none;">{{ $css }}</textarea>
        @endif
      </div>
    </div>
  </div>
</div>

<style type="text/css"></style>

<script type="text/javascript">
  @if($html != "" OR $css != "") 
    $(document).ready(function() {
      var html = $('#hidden-html').val();
      var css = $('#hidden-css').val();

      $('#live-view').contents().find("head").html("<style> body { text-align: center; } " + css + "</style>");
      $('#live-view').contents().find("body").html(html);
    });
  @endif
  $('#current-html').on('change keyup paste', function() {
    var html = $('#current-html').val();
    var css = $('#current-css').val();

    $('#live-view').contents().find("head").html("<style> body { text-align: center; } " + css + "</style>");
    $('#live-view').contents().find("body").html(html);
  });

  $('#current-css').on('change keyup paste', function() {
    var html = $('#current-html').val();
    var css = $('#current-css').val();

    $('#live-view').contents().find("head").html("<style> body { text-align: center; } " + css + "</style>");
    $('#live-view').contents().find("body").html(html);
  });

  var saveUserBar = function(groupid) {
    var html = $('#current-html').val();
    var css = $('#current-css').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroups/edit/bar',
      type: 'post',
      data: {html:html, css:css, groupid:groupid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/usergroups');
          urlRoute.ohSnap('Userbar saved!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }
      }
    });
  }

  var destroy = function() {
    saveUserBar = null;
  }
</script>