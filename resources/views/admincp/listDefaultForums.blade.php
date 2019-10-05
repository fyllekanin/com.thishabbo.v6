<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Default Forums");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Default Forum Permissions</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>Default Forum Permissions</span>
                  </div>

<div class="content-holder">
        <div class="content">
          <div class="content-ct">

          <input type="text" placeholder="Forum Name" class="login-form-input" id="admin-name-filter">

          </div>
        </div>
      </div>
      <div class="content-holder">
        <div class="content">
        <b>What are default permissions?</b><br>They basically control users who haven't been put in a usergroup and if you're not logged in.<br />
        <br />
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Title</th>
              <th>Can see forum</th>
              <th>Edit</th>
            </tr>
            @foreach($forums as $forum)
              <tr>
                <td>{{ $forum['title'] }}</td>
                <td>
                  @if($forum['can_see'])
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i> Yes
                  @else
                    <i class="fa fa-circle-o" aria-hidden="true"></i> No
                  @endif
                </td>
                <td>

                <a href="/admincp/default/forum/perms/{{ $forum['forumid'] }}" class="web-page">Edit permissions</a></td>
              </tr>

              <?php $childs = $ForumHelper::getChildsDefault($forum['childs']);?>
              {!! $childs !!}
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var forumAction = function(forumid) {
    var action = $('#forumid-'+forumid).val();
    switch(action) {
      case "1":
        //Edit Forum
        urlRoute.loadPage('/admincp/forums/edit/'+forumid);
      break;
      case "2":
        var r = confirm("Sure you wanna delete this forum?");
        if(r == true) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/forum/remove',
            type: 'post',
            data: {forumid:forumid},
            success: function(data) {
              if(data['response'] == true) {
                urlRoute.loadPage('/admincp/forums');
                urlRoute.ohSnap('Forum Removed!', 'green');
              } else {
                urlRoute.ohSnap('Something went wrong!', 'red');
              }
            }
          });
        }
      break;
      case "3":
        //Add Child
        urlRoute.loadPage('/admincp/forums/add?forumid=' + forumid);
      break;
    }
  }

  var destroy = function() {
    forumAction = null;
  }
</script>
