<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Default Forum Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit default permissions in forum {{ $forum->title }}</span>
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
        <span>Edit default permissions in forum {{ $forum->title }}</span>
        <a href="/admincp/default/forum/perms" class="web-page headerLink white_link">Back</a>
      </div>
      These are the new permissions for V6. 
      <br>Please tick the box if the answer is a "yes". <br>Unticked = "no".
      </div>
      </div>

      <div class="contentHeader headerBlue">
        <span>Edit default permissions in forum {{ $forum->title }}</span>
      </div>

      <div class="content-holder">
        <div class="content">
        <div class="row">
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can users view this forum?</b> <br />
              <i style="font-size: 0.7rem;">in {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="1" @if($can_see_forum) checked="" @endif />
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can users create threads? </b><br />
              <i style="font-size: 0.7rem;">in {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="2" @if($can_create_thread) checked="" @endif />
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can user reply & post in others threads?</b><br />
              <i style="font-size: 0.7rem;">in {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="4" @if($can_reply_to_others_threads) checked="" @endif />
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can user edit own posts?</b><br />
              <i style="font-size: 0.7rem;">in {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="8" @if($can_edit_own_post) checked="" @endif />
            </div>
          </div>
          
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Do posts need to be approved before posting?</b> <br />
              <i style="font-size: 0.7rem;">This should only really be ticked if it's a Media forum</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="16" @if($can_skip_approve_thread) checked="" @endif />
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can see other users threads?</b><br />
              <i style="font-size: 0.7rem;">Can user see other users threads?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="32" @if($can_see_others_threads) checked="" @endif />
            </div>
          </div>
          
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can skip double posting?</b><br />
              <i style="font-size: 0.7rem;">Will not auto merge posts</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="64" @if($can_skip_double_post) checked="" @endif />
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can user reply & post in threads started by them?</b><br />
              <i style="font-size: 0.7rem;">in {{ $forum->title }}</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="forumperm" value="128" @if($can_reply_to_own_threads) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          <div class="small-12 column">
          <button class="pg-red headerBlue gradualfader fullWidth topBottom" style="float:right"onclick="saveForumPerms();">Save Perms</button>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var saveForumPerms = function() {
    var forumid = {{ $forum->forumid }};

    var permissions = 0;
    $('input:checkbox.forumperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;

    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/default/forum/perms',
      type: 'post',
      data: {forumid:forumid, permissions:permissions},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/default/forum/perms');
          urlRoute.ohSnap('Permissions saved!', 'green');
        } else {
          ohSnap('Something went wrong!', 'red');
        }
      }
    })
  }

  var destroy = function() {
    saveForumPerms = null;
  }
</script>
