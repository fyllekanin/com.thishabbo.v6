<script> urlRoute.setTitle("TH - {{ $forum->title }}");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content subNav">
    <b>
      <span><a href="/forum" class="web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      {!! $ForumHelper::getBreadCrum($forum->parentid) !!}
      <span><a href="/forum/category/{{ $forum->forumid }}/page/1" class="web-page">{{ $forum->title }}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>  </b>
      <span>New Thread</span>
    </div>
  </div>
</div>

<div class="small-12 medium-12 large-12 column">
  <div class="content-holder" id="threadPreview" style="display: none">
    <div class="content">
      <div class="contentHeader headerBlue">
        <a class ="web-page headerLink white_link" onclick="hidePreview();">
          X
        </a>
        Thread Preview
      </div>
      <div class="post_content_text" id="preview_box">

      </div>
    </div>
  </div>
  <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerBlue">
            <a href="/forum/bbcodes/list" class="web-page headerLink white_link">
                Stuck Formatting?
            </a>
            New thread in {{ $forum->title }}
    </div>
            <label for="new-thread-title">Title:</label>
            <input type="text" id="new-thread-title" placeholder="Title..." class="login-form-input"/>
            <label for="new-thread-prefix">Prefix:</label>
            <select id="new-thread-prefix" class="login-form-input">
              <option value="0" selected="">None</option>
              @foreach($prefixes as $prefix)
                <option value="{{ $prefix->prefixid }}">{{ $prefix->text }}</option>
              @endforeach
            </select>
            <label for="new-thread-title">Content:</label>
            <textarea id="thread_editor" style="height: 150px; font-size:12px !important;"></textarea>
            <br />
            <input type="checkbox" value="show" id="enable_poll" style="margin: 0.5rem 0 0.5rem 0;" /> Add Poll
            <label for="formodperm7">
              <span><span></span></span>
            </label>
            <div id="poll_answers" style="display: none;">
              <i>Poll Answers (maximum 20 answers):</i> <br />
              <div id="poll_inputs">
                <input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" placeholder="Answer..." />
                <input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" placeholder="Answer..." />
              </div>
              <br />
              <i class="fa fa-plus-square-o" aria-hidden="true" style="font-size: 1.3rem;" onclick="addInput();"></i><br />
              <br />
            <input type="checkbox" value="show" id="hideresults" style="margin: 0.5rem 0 0.5rem 0;" /> Hide Poll Results
            </div>
            <br>
            <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="showPreview();">Preview Thread</button>
            <button class="pg-blue headerRed gradualfader fullWidth topBottom" onclick="postThread();">Post</button>


        </div>

        </div></div>


</div>

</div>

<script type="text/javascript">
  var answers_amount = 2;

  $(document).ready(function() {
    $("#thread_editor").wysibb();
  });

  function showPreview() {
    var previewBox = document.getElementById('preview_box');
    var previewDiv = document.getElementById('threadPreview');
    var content = $("#thread_editor").htmlcode();
    $.ajax({
          url: '/forum/preview',
          type: 'POST',
          data: {
            content: content
          },
          success: function(data) {
            previewBox.innerHTML = data.message;
            previewDiv.style.display = "block";
          }
      })
  }

  var hidePreview = function() {
    var previewDiv = document.getElementById('threadPreview');
    previewDiv.style.display = "none";
  }

  var addInput = function() {
    if(answers_amount < 20) {
      $('#poll_inputs').append('<input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" placeholder="Answer..." />');
      answers_amount++;
    }
  }

  $('#enable_poll').change(function () {
    if($('#enable_poll').is(':checked')) {
      $('#poll_answers').fadeIn();
    } else {
      $('#poll_answers').fadeOut();
    }
  });

  var postThread = function() {
    var forumid = {{ $forum->forumid }};
    var content = $("#thread_editor").bbcode();
    var title = $("#new-thread-title").val();
    var prefixid = $('#new-thread-prefix').val();
    var answers = [];
    var poll_enabled = 0;
    var poll_results_visible = 1;

    if($('#enable_poll').is(':checked')) {
      $('.poll_inp').each(function() {
        if(this.value != "") {
          answers.push(this.value);
          poll_enabled = 1;
        }
      });
    }

    if($('#hideresults').is(':checked')) {
          poll_results_visible = 0;
    }

    $('.post-button').css("display", "none");
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/post/thread',
      type: 'post',
      data: {forumid:forumid, title:title, content:content, poll_enabled:poll_enabled, poll_results_visible:poll_results_visible, answers:answers, prefixid:prefixid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage(data['path']);
          urlRoute.ohSnap('Successfully posted!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
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
    addInput = null;
    postThread = null;
  }
</script>
<script>
    $('#thread_editor').wysibb('instance').width('98%');
    $('#thread_editor').wysibb('instance').height('170');
    if($('#thread_editor').wysibb('instance').addShortcut) {
      $('#thread_editor').wysibb('instance').addShortcut('alt+s', function() {postForumMessage(); });
    }
</script>
