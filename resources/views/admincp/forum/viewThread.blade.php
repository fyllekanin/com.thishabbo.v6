<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - View Thread {{ $threadTitle }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>View Thread: {{ $threadTitle }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
      <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                <span>View Thread: {{ $threadTitle }}</span>
                <a href="/admincp/forums" class="web-page headerLink white_link">Back</a>
            </div>
            <div class="content-ct">
                <label for="forum-edit-title">Title</label>
                <input type="text" id="forum-edit-title" value="{{ $threadTitle }}" class="login-form-input" disabled/>

                <label for="forum-edit-title">Forum</label>
                <input type="text" id="forum-edit-title" value="{{ $forumName }}" class="login-form-input" disabled/>

                <label for="forum-edit-title">Author</label>
                <input type="text" id="forum-edit-title" value="{{ $threadAuthor }}" class="login-form-input" disabled/>
            </div>
        </div>
      </div>

      @foreach($posts as $post)
        <div class="content-holder">
            <div class="content">
            	<div class="contentHeader headerBlue">
                	<span>Post by {{ $post['author'] }}</b> (<i>{{ $post['postid'] }}</i>)</span>
                	<button onclick="toggleDiv('post-{{ $post['postid'] }}');" class="web-page headerLink white_link">Toggle</button>
            	</div>
            	<div class="content-ct" id="post-{{ $post['postid'] }}" style="display: none;">
            		{!! $post['content'] !!}
            	</div>
            </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<script type="text/javascript">
	var toggleDiv = function(elName){
      $('#'+elName).slideToggle();
    }
</script>
