<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Edit Forum {{ $forum->title }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Forum: {{ $forum->title }}</span>
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
                    <span>Edit Forum: {{ $forum->title }}</span>
                    <a href="/admincp/forums" class="web-page headerLink white_link">Back</a>
                  </div>
          <div class="content-ct">
          <label for="forum-edit-title">Title</label>
          <input type="text" id="forum-edit-title" value="{{ $forum->title }}" class="login-form-input"/>
        </div>
          <label for="forum-edit-parent">Parent Forum</label>
          <select id="forum-edit-parent" class="login-form-input">
            <option value="-1">No one</option>
            @if($forum->parentid > 0)
              <?php $parent = $forum->parentid ?>
            @else
              <?php $parent = 0; ?>
            @endif
            @foreach($forums as $forumx)
              <option value="{{ $forumx['forumid'] }}" @if($forumx['forumid'] == $parent) selected="" @endif >{{ $forumx['title'] }}</option>
              @if(count($forumx['childs']))
                <?php $childs = $ForumHelper::getChildsSelect($parent, $forumx['childs']);?>
                {!! $childs !!}
              @endif
            @endforeach
          </select>
          <label for="forum-edit-desc">Description</label>
          <textarea class="login-form-input" id="forum-edit-desc">{{ $forum->description }}</textarea>
          <label for="forum-edit-display">Display Order</label>
          <input type="number" id="forum-edit-display" value="{{ $forum->displayorder }}" class="login-form-input"/>
          <label for="forum-edit-posts">Count Posts?</label>
          <select id="forum-edit-posts" class="login-form-input">
            <option value="0" @if($ForumHelper::forumHaveOption($forum->forumid, 2)) selected="" @endif >No</option>
            <option value="1" @if(!$ForumHelper::forumHaveOption($forum->forumid, 2)) selected="" @endif >Yes</option>
          </select>
          <label for="forum-edit-approve">Do new threads need approving?</label>
          <select id="forum-edit-approve" class="login-form-input">
            <option value="0" @if(!$ForumHelper::forumHaveOption($forum->forumid, 1)) selected="" @endif >No</option>
            <option value="1" @if($ForumHelper::forumHaveOption($forum->forumid, 1)) selected="" @endif >Yes</option>
          </select>

          <br><b>Thumbnail</b> <br /><br />
          <img src="{{ asset('_assets/img/forumthumbnails/'.$forum->forumid.'.gif') }}" />
          <br /><br />
          <div class="upload_avatar">
              <input type="file" id="forum-edit-thumbnail" />
          </div>

<br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="editForum();">Save</button>

        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var editForum = function() {

    var formData = new FormData();
    formData.append('title',$('#forum-edit-title').val());
    formData.append('parent',$('#forum-edit-parent').val());
    formData.append('display',$('#forum-edit-display').val());
    formData.append('desc',$('#forum-edit-desc').val());
    formData.append('posts',$('#forum-edit-posts').val());
    formData.append('approve',$('#forum-edit-approve').val());
    if ($('#forum-edit-thumbnail').get(0).files.length !== 0) {
      formData.append('thumbnail', $('#forum-edit-thumbnail')[0].files[0]);
    }
    formData.append('forumid','{{ $forum->forumid }}');
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/forum/edit',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['error'] == 1) {
          $('#forum-edit-title').removeClass('form-reg-error');
          $('#forum-edit-parent').removeClass('form-reg-error');
          $('#forum-edit-display').removeClass('form-reg-error');
          $('#forum-edit-desc').removeClass('form-reg-error');
          $('#forum-edit-posts').removeClass('form-reg-error');
          $('#forum-edit-approve').removeClass('form-reg-error');
          $('#forum-edit-thumbnail').removeClass('form-reg-error');

          if(data['field'] != "all") {
            $('#'+data['field']).addClass('form-reg-error');
          }

          urlRoute.ohSnap(data['message'], 'red');
        } else {
          //WOOP!
          urlRoute.loadPage('/admincp/forums');
          urlRoute.ohSnap('Forum edited!', 'green');
        }
      }
    });
  }
  var destroy = function() {
    editForum = null;
  }
</script>
