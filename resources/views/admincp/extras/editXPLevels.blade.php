<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Edit XP Level");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit XP Level</span>
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
                    <span>Edit XP Level</span>
                  </div>
          <div class="content-ct">
            <label for="level-form-name">Level Name</label>
            <input type="text" id="level-form-name" value="{{ $name }}" class="login-form-input"/>
            <label for="level-form-posts">XP Required</label>
            <input type="text" id="level-form-posts" value="{{ $posts }}" class="login-form-input"/>
            <br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="editXPLevel({{ $levelid }});">Edit XP Level</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var editXPLevel = function(xplevelid) {
    var name = $('#level-form-name').val();
    var posts = $('#level-form-posts').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/xplevels/update',
      type: 'post',
      data: {xplevelid:xplevelid, name:name, posts:posts},
      success: function(data) {
        urlRoute.ohSnap('XP Level Edited!', 'green');
        urlRoute.loadPage('/admincp/xplevels');
      }
    })
  }

  var destroy = function() {
    editPostLevel = null;
  }
</script>
