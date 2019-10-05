<script> urlRoute.setTitle("TH - Move Thread");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span>Move Thread</span>
    </div>
  </div>
</div>

<div class="small-12 medium-12 large-12 column">
      <div class="contentHeader headerBlue">
      @if($threadcount > 1)
            Moving {{ $threadcount }} threads
      @else
            Moving Thread - {{ $threads[0]->title }}
      @endif

            </div>
<div class="content-holder">
    <div class="content">
        <div class="content-ct">
          <label for="forum-select">Move thread(s) to..</label>
          <select id="forum-select" class="login-form-input">
            @foreach($forums as $forumx)
              <option value="{{ $forumx['forumid'] }}">{{ $forumx['title'] }}</option>
                <?php $childs = $ForumHelper::getChildsSelect($threads[0]->forumid, $forumx['childs']);?>
                {!! $childs !!}
            @endforeach
          </select>
      </div>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" style="margin-top: 1rem;" onclick="moveThread();">Move &#187;</button>
    </div>
</div>

<script type="text/javascript">
  var moveThread = function() {

    var forumid = $('#forum-select').val();
    var threadid = 0;
    @foreach($threads as $thread)
    threadid = {{ $thread->threadid }};

    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/move/thread',
      type: 'post',
      data: {threadid: threadid, forumid:forumid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.ohSnap('Thread edited!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }
        @if($threadcount==1)
        urlRoute.loadPage('/forum/thread/'+threadid+'/page/1');
        @endif
      }
    });
    @endforeach
    urlRoute.loadPage('/forum/category/'+forumid+'/page/1');
  }

  var destroy = function() {
    moveThread = null;
  }
</script>
