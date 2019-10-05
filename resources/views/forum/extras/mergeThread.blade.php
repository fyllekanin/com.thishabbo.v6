<script> urlRoute.setTitle("TH - {{ $thread->title }}");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="inner-content-holder"><div class="content">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
        <b>{!! $ForumHelper::getBreadCrum($thread->forumid) !!}</b>
      <span>Merge Thread</span>
    </div></div>
  </div>
</div>

<div class="small-12 medium-12 large-12 column">
<div class="content-holder">
    <div class="inner-content-holder"><div class="content">
      <div class="contentHeader headerBlue">
            Merge {{ $thread->title }} WITH {{ $merge->title }}
            </div>
        <div class="content-ct">
        <br>
        <textarea id="thread_editor" style="height: 150px;">{!! $content !!}</textarea>

        <button class="pg-right pg-grey" style="margin-top: 0.5rem;" onclick="postMergeThreads();">Merge Threads</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#thread_editor").wysibb();
  });

  var postMergeThreads = function() {
    var content = $("#thread_editor").bbcode();
    var threadid = {{ $thread->threadid }};
    var mergeid = {{ $merge->threadid }};

    $('.post-button').css("display", "none");
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/merge/threads',
      type: 'post',
      data: {threadid:threadid, mergeid:mergeid, content:content},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/forum/thread/'+mergeid+'/page/1');
          urlRoute.ohSnap('Thread edited!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }

        $('.post-button').css("display", "block");
      },
      error: function() {
        $('.post-button').css("display", "block");
        urlRoute.ohSnap('Something went wrong!', 'red');
      }
    });
  }

  var destroy = function() {
    postMergeThreads = null;
  }
</script>
