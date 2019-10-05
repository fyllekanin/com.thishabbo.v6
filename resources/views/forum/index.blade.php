<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Forum");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content subNav">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Forum</span>
    </div>
  </div>
</div>
<div class="small-12 medium-12 large-8 mobileFunction column">
@if($can_use_shoutbox)
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <button class="web-page headerLink white_link" id="shoutbox-button" onclick="toggleCategory('shoutbox', 'sbx');">
                    Toggle
                </button>
                <span>Shoutbox</span>
            </div>
            <div class="content-ct" @if($ForumHelper::isForumCollapsed('sbx', Auth::user()->userid) == true) style="display: none" @endif id="shoutbox">
                <input type="text" placeholder="Type your message here! BBCode is supported!" class="login-form-input" id="postShoutboxMessage"/>
                <div class="forum_shoutbox">
                    <table>
                        <tbody id="addMessagesHere">
                            @foreach($shoubox_messages as $message)
                                <tr>
                                    <td>[{{ $message['dateline'] }}] <a href="/profile/{{ $message['clean_username'] }}" class="web-page">{!! $message['username'] !!}:</a></td>
                                    <td>{!! $message['message'] !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Auth::check())
    @if($gdpr == false)<div class="small-8 column" style="padding-bottom: 10px;"><div class="alert alert-danger">
      <b>Due to the GDPR regulations, you will need to give us concent to use your data - please check the box.</b><br />
        <br />
      - If you are 16 or over, you may consent yourself and make your own decision<br />
      - If you are 16 and under you must seek permission. Once your parents have given permission you may check the box.<br />
      <br />
      <center><a href="/forum/thread/566313/page/1" class="web-page" style="color: #000;"><u>Click here to see what data we store and collect of you.</u></a></center><br />
      <br />
      <button class="pg-red fullWidth headerRed gradualfader shopbutton" name="button" onclick="gdprAgree()">I agree to allow ThisHabbo to use my data. <i class="fa fa-check"></i></button><br />
      <button class="pg-red fullWidth headerBlue gradualfader shopbutton" name="button" onclick="gdprDisagree()">I don't agree to allow ThisHabbo to use my data. <i class="fa fa-times"></i></button><br />
    </div></div><br /><br />
    @endif
  @endif

  <?php $nr = 1; ?>
  @foreach($forums as $forum)
    <div class="content-holder">
      <div class="content">
      <div class="contentHeader @if($nr % 2 == 0) headerRed @else headerBlue @endif">
          <button class="web-page headerLink white_link" id="cat-{{ $forum['forumid'] }}-button" onclick="toggleCategory('cat-{{ $forum['forumid'] }}', '{{ $forum['forumid'] }}');">
              Toggle
          </button>
          <span>{{ $forum['title'] }}</span>
        </div>
        <div class="content-ct" @if($forum['collapsed'] == true) style="display: none" @endif id="cat-{{ $forum['forumid'] }}">

          @foreach($forum['sub_forums'] as $sub)
            <div class="forum-list">
              <div class="small-6 medium-6 large-8 column make-forum-bigger">
                <div class="forum-icon">
                  <img src="{{ $sub['thumbnail'] }}" alt="unread post" @if($sub['have_read_forum']) class="grayscale-class newClass" @else class="newClass" @endif/>
                </div>
                <div class="forum-name">
                  <a href="/forum/category/{{ $sub['forumid'] }}/page/1" class="web-page forumBold">{{ $sub['title'] }}</a> <br />
                  <div class="forumdec">{{ $sub['desc'] }}</div>
                  <div class="forumsubs">{!! implode(', ', $sub['subForums']) !!}</div>
                </div>
              </div>
              <div class="small-6 medium-6 large-4 column make-forum-smaller">
                @if($sub['can_see_last_post'] == true AND $sub['private'] == 0)
                  <div class="forum-latest-post">
                    @if(Auth::check())
                        @if($hideavs == true)
                            <div class="forum-latest-post-av" style="box-shadow: 0 0 0 0 !important;"></div>
                        @else
                        <div class="forum-latest-post-av" style="background-image: url('{{ $sub['last_post_avatar'] }}');">
                        </div>
                        @endif
                    @endif
                    <div class="forum-latest-post-info">
                      <a href="/forum/thread/{{ $sub['last_post_threadid'] }}/page/{{ $sub['last_post_page']}}" class="bold web-page hover-box-info" title="{{ $sub['last_post_full_title'] }}">{!! $sub['last_post_title'] !!}</a> <br />
                      {{ $sub['last_post_time'] }} <br />
                      <a href="/profile/{{ $sub['last_post_postername']}}/page/1" class="web-page">{!! $sub['last_post_poster'] !!}</a>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <?php $nr++; ?>
  @endforeach
</div>

<div class="small-12 medium-4 column end">
    <div class="" id="top-stats-content-div">
        Loading..
    </div>
</div>

<script type="text/javascript">
  var gdprAgree = function() {
      $.ajax({
            url: urlRoute.getBaseUrl() + 'user/gdpr',
            type: 'post',
            data: {},
            success: function(data) {
                if (data['response']) {
                    urlRoute.ohSnap("Thankyou for agreeing!", 'green');
                    urlRoute.loadPage('forum/thread/566313/page/1');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var gdprDisagree = function() {
      signOut();
    }

    var toggleDiv = function(elName){
      $('#'+elName).slideToggle();
    }

    var toggleCategory = function(elName,forumid){
      $('#'+elName).slideToggle();
      $.ajax({
          url: '/forum/collapse',
          type: 'POST',
          data: {
            forumid: forumid
          },
          success: function(data) {
            urlRoute.ohSnap(data.message,'green');
          }
      })
    }

  var loadTopStats = function() {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/top/stats',
      type: 'GET',
      success: function(data) {
        $('#top-stats-content-div').html(data['returnHTML']);
        Tipped.create('.hover-box-info');
      }
    });
  }

  $(document).ready(function(){
    loadTopStats();
  });

@if($can_use_shoutbox)
        var noPosting = false;
        var lastTime = {{ $lastTime }};
        var postMessage = function() {
            var message = $('#postShoutboxMessage').val();
            if(message.length === 0) {
                urlRoute.ohSnap('Can\'t post an empty message', 'red');
                return;
            }

            $.ajax({
              url: urlRoute.getBaseUrl() + 'shoutbox/post',
              type: 'post',
              data: {message: message},
              success: function(data) {
                  noPosting = false;
                  if(data['response'] === true) {
                      lastTime = data['lastTime'];
                      addMessage(data);
                      urlRoute.ohSnap('Message posted!', 'green');
                  } else {
                      urlRoute.ohSnap('You are posting too quick, slow down!', 'red');
                  }
              },
              error: function() {
                  noPosting = false;
                  urlRoute.ohSnap('Something went wrong!', 'red');
              }
            });
        }

        var addMessage = function(message) {
            var template = '<tr><td>[_dateline_] _username_:</td><td>_message_</td></tr>';
            var insert = template.replace('_dateline_', message['dateline']);
            insert = insert.replace('_username_', '<a href="/profile/' + message['clean_username'] + '" class="web-page">' + message['username'] + '</a>');
            insert = insert.replace('_message_', message['message']);
            $('#addMessagesHere').prepend(insert);
        }

        var getMessages = function() {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'shoutbox/getMessages/'+lastTime,
                type: 'get',
                success: function(data) {
                    lastTime = data['new_lastTime'];
                    for(var i = 0; i < (data['messages'] ? data['messages'].length : 0); i++) {
                        addMessage(data['messages'][i]);
                    }
                },
                error: function() {
                    noPosting = false;
                    urlRoute.ohSnap('Something went wrong!', 'red');
                }
            });
        }

        var int_val = setInterval(function() {
            getMessages();
        }.bind(this), 20000);

        $('#postShoutboxMessage').keypress(function(e) {
            if(e.which === 13 && !noPosting) {
                noPosting = true;
                postMessage();
                $('#postShoutboxMessage').val('');
            }
        });
@endif

    var destroy = function() {
      if(typeof getMessages !== 'undefined') {
        getMessages = null;
      }
      if(typeof addMessage !== 'undefined') {
        addMessage = null;
      }
      if(typeof postMessage !== 'undefined') {
        postMessage = null;
      }
      if(typeof int_val !== 'undefined') {
        clearInterval(int_val);
      }
      if(typeof loadTopStats !== 'undefined') {
        loadTopStats = null;
      }
    }
</script>
