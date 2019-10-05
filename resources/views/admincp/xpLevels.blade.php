<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - XP Levels");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage XP Levels</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>Add XP Levels</span>
                  </div>

      <div class="content-holder">
        <div class="content">
          <div class="content-ct">
            <label for="badge-form-desc">Level Name</label>
            <input type="text" id="level-name" placeholder="Text..." class="login-form-input"/>
            <label for="badge-form-desc">XP Required</label>
            <input type="text" id="level-posts" placeholder="Number..." class="login-form-input"/>
            <br>
          <button class="pg-red headerGreen gradualfader fullWidth topBottom" onclick="addLevel();">Add Level</button>
      </div>
    </div>
  </div>
</div>
<div class="medium-8 column">
        <div class="contentHeader headerPurple">
          <span>Manage XP Levels</span>
        </div>
<div class="content-holder">
      <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Level Name</th>
              <th>XP Required</th>
              <th>Moderate</th>
            </tr>
            @foreach($levels as $level)
              <tr>
                <td>{{ $level['name'] }}</td>
                <td>{{ $level['posts'] }}</td>
                <td><a href="/admincp/xplevels/edit/{{ $level['levelid'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a> <i class="fa fa-trash" aria-hidden="true" onclick="removeLevel({{ $level['levelid'] }});"></i></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  var addLevel = function() {
    var name = $('#level-name').val();
    var posts = $('#level-posts').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/xplevels/add',
      type: 'post',
      data: {name:name, posts:posts},
      success: function(data) {
        urlRoute.ohSnap('XP Level Added!', 'green');
        urlRoute.loadPage('/admincp/xplevels');
      }
    })
  }

  var removeLevel = function(levelid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/xplevels/remove',
      type: 'post',
      data: {levelid:levelid},
      success: function(data) {
        urlRoute.ohSnap('XP Level Removed!', 'green');
        urlRoute.loadPage('/admincp/xplevels');
      }
    })
  }

  var destroy = function() {
    addPrefix = null;
    removePrefix = null;
  }
</script>
