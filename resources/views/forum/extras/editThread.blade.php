<script> urlRoute.setTitle("TH - {{ $thread->title }}");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
<b>
      {!! $ForumHelper::getBreadCrum($thread->forumid) !!}</b>
      <span>Thread Edit: {{ $thread->title }}</span>
    </div>
  </div>
</div>

<div class="small-12 medium-12 large-12 column">
      <div class="contentHeader headerBlue">
            Edit Thread {{ $thread->title }}
            </div>
<div class="content-holder">
    <div class="content">
        <div class="content-ct">
            <label for="new-thread-title">Title</label>
            <input type="text" id="edit-thread-title" value="{{ $thread->title }}" class="login-form-input"/>
            <label for="new-thread-prefix">Prefix</label>
            <select id="new-thread-prefix" class="login-form-input">
              <option value="0" @if($thread->prefixid == 0) selected="" @endif>None</option>
              @foreach($prefixes as $prefix)
                <option value="{{ $prefix->prefixid }}" @if($thread->prefixid == $prefix->prefixid) selected="" @endif>{{ $prefix->text }}</option>
              @endforeach
            </select>
            <label for="new-thread-prefix">Content</label>
            <textarea id="thread_editor" style="height: 150px; font-size:12px !important;">{!! $content !!}</textarea>
          </div>
            <br />
            @if($thread->got_poll == 0)
              <input type="checkbox" value="show" id="enable_poll" /> Add Poll
              <div id="poll_answers" style="display: none;">
                <i>Poll Answers (maximum 20 answers)</i> <br />
                <div id="poll_inputs">
                  <input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" placeholder="Answer..." />
                  <input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" placeholder="Answer..." />
                </div>
                <br />
                <i class="fa fa-plus-square-o" aria-hidden="true" style="font-size: 1.3rem;" onclick="addInput();"></i>
              </div>
            @else
              <i>Poll Answers</i> <br />
              <div id="poll_inputs">
                @foreach($answers as $answer)
                  <input type="text" name="poll_answer[]" class="login-form-input-poll poll_inp" id="{{ $answer['pollanswerid'] }}" value="{{ $answer['answer'] }}" />
                @endforeach
              </div>
              <input type="checkbox" value="show" id="hideresults" style="margin: 0.5rem 0 0.5rem 0;" /> Hide Poll Results
            @endif
          <br>
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="postEditThread();">Edit Thread</button>
            <br><button class="pg-red headerBlue gradualfolder fullWidth topBottom" onclick="cancelEdit();">Cancel Edit</button>
        </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#thread_editor").wysibb();
  });

  @if($thread->got_poll == 0)
    var answers_amount = 2;

    var addInput = function() {
      if(answers_amount < 20) {
        $('#poll_inputs').append('<input type="text" name="poll_answer[]" class="login-form-input poll_inp" placeholder="Answer..." />');
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
  @endif

  var postEditThread = function() {
    var content = $("#thread_editor").bbcode();
    var title = $("#edit-thread-title").val();
    var threadid = {{ $thread->threadid }};
    var answers = [];
    var poll_enabled = {{ $thread->got_poll }};
    var poll_edit = {{ $thread->got_poll }};
    var prefixid = $('#new-thread-prefix').val();
    var poll_results_visible = 1;

    @if($thread->got_poll == 0)
    if($('#enable_poll').is(':checked')) {
      $('.poll_inp').each(function() {
        if(this.value != "") {
          answers.push(this.value);
          poll_enabled = 1;
        }
      });
    }
    @else
      $('.poll_inp').each(function() {
        if(this.value != "") {
          answers.push(this.id + ".:." + this.value);
        }
      });
    @endif
    if($('#hideresults').is(':checked')) {
          poll_results_visible = 0;
    }

    $('.post-button').css("display", "none");
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/edit/thread',
      type: 'post',
      data: {threadid:threadid, title:title, content:content, poll_enabled:poll_enabled, poll_edit:poll_edit, poll_results_visible:poll_results_visible, answers:answers, prefixid:prefixid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/forum/thread/'+threadid+'/page/1');
          urlRoute.ohSnap('Thread edited!', 'green');
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

  var cancelEdit = function() {
    var threadid = {{ $thread->threadid }};
    urlRoute.loadPage('/forum/thread/'+threadid+'/page/1');
  }

  var destroy = function() {
    addInput = null;
    postEditThread = null;
  }
</script>
