<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Add Forum");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Add Forum</span>
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
                    <span>Add Forum</span>
                    <a href="/admincp/forums" class="web-page headerLink white_link">Back</a>
                  </div>
          <div class="content-ct">
          <label for="forum-add-title">Title</label>
          <input type="text" id="forum-add-title" placeholder="Forum Title..." class="login-form-input"/>
        </div>
          <label for="forum-add-parent">Parent Forum</label>
          <select id="forum-add-parent" class="login-form-input">
            <option value="-1">No one</option>
            @if(isset($_GET['forumid']))
              <?php $parent = $_GET['forumid']; ?>
            @else
              <?php $parent = 0; ?>
            @endif
            @foreach($forums as $forum)
              <option value="{{ $forum['forumid'] }}" @if($forum['forumid'] == $parent) selected="" @endif>{{ $forum['title'] }}</option>
              @if(count($forum['childs']))
                <?php $childs = $ForumHelper::getChildsSelect($parent, $forum['childs']);?>
                {!! $childs !!}
              @endif
            @endforeach
          </select>
          <label for="forum-add-desc">Description</label>
          <textarea id="forum-add-desc" class="login-form-input"></textarea>
          <label for="forum-add-display">Display Order</label>
          <input type="number" id="forum-add-display" placeholder="1..." class="login-form-input"/>
          <label for="forum-add-posts">Count Posts?</label>
          <select id="forum-add-posts" class="login-form-input">
            <option value="0">No</option>
            <option value="1" selected="">Yes</option>
          </select>
          <label for="forum-add-approve">Do new threads need approving?</label>
          <select id="forum-add-approve" class="login-form-input">
            <option value="0" selected="">No</option>
            <option value="1">Yes</option>
          </select><br>
          <label for="forum-add-thumbnail">Thumbnail</label>
          <div class="upload_avatar">
              <input type="file" id="forum-add-thumbnail" />
          </div>
          <br />
      <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addForum();">Add Forum</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var addForum = function() {
    var formData = new FormData();
    formData.append('title',$('#forum-add-title').val());
    formData.append('parent',$('#forum-add-parent').val());
    formData.append('display',$('#forum-add-display').val());
    formData.append('desc',$('#forum-add-desc').val());
    formData.append('posts',$('#forum-add-posts').val());
    formData.append('approve',$('#forum-add-approve').val());
    if ($('#forum-add-thumbnail').get(0).files.length !== 0) {
      formData.append('thumbnail',$('#forum-add-thumbnail')[0].files[0]);
    }
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/forum/add',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['error'] == 1) {
          $('#forum-add-title').removeClass('form-reg-error');
          $('#forum-add-parent').removeClass('form-reg-error');
          $('#forum-add-display').removeClass('form-reg-error');
          $('#forum-add-desc').removeClass('form-reg-error');
          $('#forum-add-posts').removeClass('form-reg-error');
          $('#forum-add-approve').removeClass('form-reg-error');
          $('#forum-add-thumbnail').removeClass('form-reg-error');

          if(data['field'] != "all") {
            $('#'+data['field']).addClass('form-reg-error');
          }

          urlRoute.ohSnap(data['message'], 'red');
        } else {
          //WOOP!
          urlRoute.loadPage('/admincp/forums');
          urlRoute.ohSnap('Forum ' + title + ' added!', 'green');
        }
      }
    });
  }

  var destroy = function() {
    addForum = null;
  }
</script>
