<script> urlRoute.setTitle("TH - {{ $forum->title }}");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

@if($can_soft_delete OR $can_hard_delete)
    <div class="reveal" id="delete_threads" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Delete</h4>
        </div>
        <div class="modal-body">
            <fieldset>
                <legend>Select delete type: </legend>
                <input type="radio" name="delete_type" value="0" id="softDelete" checked="" /> Soft Delete <i>(Still in the database)</i><br />
                @if($can_hard_delete == 1)
                    <input type="radio" name="delete_type" value="1" id="hardDelete" /> Hard Delete <i>(Removed from database)</i> <br />
                @endif
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerRed floatright gradualfader" onclick="deleteSelected();" style="margin-left: 5px;">Delete</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
@endif


@if($can_change_owner)
    <div class="reveal" id="change_owner" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Change Thread Owners</h4>
        </div>
        <div class="modal-body">
            <fieldset>
                <legend>New owner's name: </legend>
                <input type="text" id="change_owner_new_name" placeholder="Username..." class="login-form-input"/>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerRed floatright gradualfader" onclick="changeOwner();" style="margin-left: 5px;">Change Owner</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
@endif

@if($can_open_close_thread OR $can_soft_delete OR $can_hard_delete OR $can_edit_post OR $can_move_threads OR $can_open_close_thread OR $can_merge_threads OR $can_change_owner)
<div id="thread_tools" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
    <button id="thread_edit_button" onclick="threadtools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Thread Tools</button>

</div>

<div id="thread_tools_options" style="display:none; position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
  <!-- needs $can_open_close_thread, $can_soft_delete, $can_hard_delete, $can_change_owner, $can_approve_unapprove-->
  <!--<button id="thread_edit_button" onclick="selectAll();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Select All</button>
  <button id="thread_edit_button" onclick="deselectAll();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Deselect All</button>-->

  <button id="thread_edit_button" onclick="selectPage();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Select Page</button>
  <button id="thread_edit_button" onclick="deselectPage();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Deselect All</button>
  <!-- open/close thread -->
  @if($can_open_close_thread)
  <button id="addstickerbutton" onclick="closeThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i>Close Thread(s)</button>
  <button id="addstickerbutton" onclick="openThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i>Open Thread(s)</button>
  @endif

  <!-- move thread -->
  @if($can_move_threads)
  <button id="thread_edit_button" onclick="moveThreads();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Move Threads</button>
  @endif

  <!-- delete thread -->
  @if($can_soft_delete OR $can_hard_delete)
  <button id="addstickerbutton" onclick="setType();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Delete Thread</button>
  <button id="addstickerbutton" onclick="unDeleteSelected(1);" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Undelete Thread</button>
  @endif

  <!-- change thread owner -->
  @if($can_change_owner)
  <a data-open="change_owner" onclick="setChangeType(1);"><button id="clearstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Change Owner(s)</button></a><br />
  @endif

  <!-- approve thread-->
  @if($can_approve_unapprove_threads)
      <button id="addstickerbutton" onclick="unapproveThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Unapprove Thread</button>
      <button id="addstickerbutton" onclick="approveThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Approve Thread</button>
  @endif

  <!-- sticky/unsticky -->
  @if($can_open_close_thread)
      <button id="addstickerbutton" onclick="unstickyThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Unsticky Thread</button>
      <button id="addstickerbutton" onclick="stickyThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Sticky Thread</button>
  @endif

  <button id="stop_editing_thread" onclick="stopEditingThreadTools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Cancel</button>


</div>
@endif
<!-- START OF POST STATS -->
<div class="reveal" id="forumStats" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Top 20 Posters</h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Username</th>
              <th>Posts</th>
            </tr>
          </thead>
          <tbody id="postersOfThread">
            <tr>
              <td>John</td>
              <td>Doe</td>
            </tr>
          </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>
<!-- END OF POST STATS -->

<div class="small-12 column">
  <div class="content-holder">
    <div class="content subNav">

          @if($ignored)
            <a class="web-page floatright" onclick="toggleIgnore();">Unignore Forum</a>
            @else
            <a class="web-page floatright" onclick="toggleIgnore();">Ignore Forum</a>
            @endif
    <b>
      <span><a href="/forum" class="web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      {!! $ForumHelper::getBreadCrum($forum->parentid) !!}</b>
      <span>{{ $forum->title }}</span>
  </div>

</div>
</div>

<div class="small-12 medium-12 large-12 column">
  <div class="content-holder">
    <div class="content">
    {!! $pagi !!}
    </div>
  </div>
</div>

@if(count($sub_forums))
  <div class="small-12 medium-12 large-12 column">
    <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerRed">
            Sub Forums
        </div>
        <div class="content-ct">

          @foreach($sub_forums as $sub)
            <div class="forum-list">
              <div class="medium-4 large-5 column make-forum-bigger">
                <div class="forum-icon">
                  <img src="{{ asset('_assets/img/website/forum_new.gif') }}" alt="unread post" @if($sub['have_read_forum']) class="grayscale-class" @endif/>
                </div>
                <div class="forum-name">
                  <a href="/forum/category/{{ $sub['forumid'] }}/page/1" class="web-page forumBold">{{ $sub['title'] }}</a> <br />
                  <div class="forumdec">{{ $sub['desc'] }}</div>
                  <div class="forumsubs">{!! implode(', ', $sub['subForums']) !!}</div>
                </div>
              </div>
              <div class="medium-4 large-4 column hide-stats">
                <div class="forum-stats">
                  {!! $sub['threads'] !!}
                </div>

                <div class="forum-stats">
                  {!! $sub['posts'] !!}
                </div>
              </div>
              <div class="medium-4 large-3 column make-forum-smaller">
                @if($sub['can_see_last_post'] == true)
                  <div class="forum-latest-post">
                    <div class="forum-latest-post-av" style="background-image: url('{{ $sub['last_post_avatar'] }}');">

                    </div>
                    <div class="forum-latest-post-info">
                      <a href="/forum/thread/{{ $sub['last_post_threadid'] }}/page/{{ $sub['last_post_page']}}" class="web-page forumBold no-scroll">{!! $sub['last_post_title'] !!}</a><br />
                      <a href="/profile/{{ $sub['last_post_postername'] }}/page/1" class="web-page">{!! $sub['last_post_poster'] !!}</a><br />
                      {{ $sub['last_post_time'] }}
                    </div>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endif

<div class="small-12 medium-12 large-12 column">
  <div class="content-holder">
  <div class="content">
  <div class="contentHeader headerBlue">
          @if($can_post_thread AND $verified === 1 AND $gdpr === 1)<a href="/forum/category/{{ $forum->forumid }}/new/thread" class="web-page headerLink white_link">
            New Thread
          </a>@endif
            Threads
        </div>
    <div class="content-ct">
      @if(count($stickys) > 0)
        @foreach($stickys as $sticky)
          <div class="forum-list">
            <div class="medium-4 large-5 column make-forum-bigger">
              <div class="forum-icon">
                <img src="{{ asset('_assets/img/website/forum_new.gif') }}" class="thread_title hover-box-info @if($sticky['have_already_seen']) grayscale-class @endif" alt="unread post"/>
              </div>
              <div class="forum-name">
                <span class="sticky-label">Sticky</span>
                @if($sticky['open'] == 0)
                  <span class="closed-label">Closed</span>
                @endif
                <a href="/forum/thread/{{ $sticky['threadid'] }}/page/{{ $sticky['page'] }}" class="web-page @if($sticky['have_already_seen']) @else forumBold @endif no-scroll">{!! $sticky['title'] !!}</a><br />
                {{ $sticky['time'] }} <br>
                by <a href="/profile/{{ $sticky['clean_username'] }}/page/1" class="web-page">{!! $sticky['username'] !!}</a>
              </div>
            </div>
            <div class="medium-4 large-4 column hide-stats">
              <div class="forum-stats" onclick="loadPosters({{ $sticky['threadid'] }});" style="cursor:pointer;">
                {!! $sticky['replys'] !!}
              </div>

              <div class="forum-stats">
                {!! $sticky['views'] !!}
              </div>
            </div>
            <div class="medium-4 large-3 column make-forum-smaller">
              <div class="forum-latest-post">
                <div class="forum-latest-post-av" style="background-image: url('{{ $sticky['last_poster_avatar'] }}');">

                </div>
                <div class="forum-latest-post-info">
                  <b>Latest Post</b> <br />
                  <a href="/profile/{{ $sticky['last_poster_clean_username'] }}/page/1" class="web-page">{!! $sticky['last_poster_username'] !!}</a><br />
                  {{ $sticky['last_poster_time'] }}
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endif
      @foreach($threads as $thread)
        <div class="forum-list">
          <div class="medium-4 large-5 column make-forum-bigger">
            <div class="forum-icon">
              <img src="{{ asset('_assets/img/website/forum_new.gif') }}" alt="unread post" class="thread_title hover-box-info @if($thread['have_already_seen']) grayscale-class @endif"/>
            </div>
            <div class="forum-name">
              @if($thread['open'] == 0)
                <span class="closed-label">Closed</span>
              @endif
              @if($thread['visible'] == 0)
                @if($thread['soft_deleted'] == 1)
                <span class="deleted-label">Deleted</span>
                @else
                <span class="deleted-label">Pending Approval</span>
                @endif
              @endif
              <a href="/forum/thread/{{ $thread['threadid'] }}/page/{{ $thread['page'] }}" class="web-page @if($thread['have_already_seen']) @else forumBold @endif no-scroll">{!! $thread['title'] !!}</a> <br />
              {{ $thread['time'] }} <br>
              by <a href="/profile/{{ $thread['clean_username'] }}/page/1" class="web-page">{!! $thread['username'] !!}</a>
            </div>
          </div>
          <div class="medium-4 large-4 column hide-stats">
            <div class="forum-stats" onclick="loadPosters({{ $thread['threadid'] }});" style="cursor:pointer;">
              {!! $thread['replys'] !!}
            </div>

            <div class="forum-stats">
              {!! $thread['views'] !!}
            </div>
          </div>
          <div class="medium-4 large-3 column make-forum-smaller">
            <div class="forum-latest-post">
              <div class="forum-latest-post-av" style="background-image: url('{{ $thread['last_poster_avatar'] }}');">

              </div>
              <div class="forum-latest-post-info">
                <b>Latest Post</b> <br />
                <a href="/profile/{{ $thread['last_poster_clean_username'] }}/page/1" class="web-page">{!! $thread['last_poster_username'] !!}</a><br />
                {{ $thread['last_poster_time'] }}
              </div>
            </div>


              @if($have_mod)
              <div class="thread_number thread-checkbox">
                  <input onchange="updateChecked({{ $thread['threadid'] }},this)" id="thead_check_{{ $thread['threadid'] }}" class="thread_check" type="checkbox" value="{{ $thread['threadid'] }}" style="float:right; margin-top:-20px;" />
              </div>
              @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
</div>

<div class="small-12 medium-12 large-12 column">
  <div class="content-holder">
    <div class="content">
    {!! $pagi !!}
  </div>
  </div>
</div>

<script type="text/javascript">

    var toggleIgnore = function(){
      $.ajax({
          url: '/forum/category/ignore',
          type: 'POST',
          data: {
            forumid: {{ $forum->forumid }}
          },
          success: function(data) {
            urlRoute.ohSnap(data.message,'green');
            urlRoute.loadPage('/forum/category/{{ $forum->forumid }}/page/1');
          }
      })
    }
    @if($have_mod)
    $(function () {
      var checked = urlRoute.getStorage('mod-cat-{{ $forum->forumid }}');
      if(checked !== null && checked.value !== null){
        var list = checked.value.split(',');
        $('input:checkbox.thread_check').each(function() {
          if(list.indexOf(this.value) >= 0){
            this.checked = true;
          }
        });
      }
    });


    var updateChecked = function(id,elem){
      var cat = {{ $forum->forumid }};
      if(elem.checked){
        str = "";
        if(urlRoute.getStorage('mod-cat-'+cat) !== null){
          str = urlRoute.getStorage('mod-cat-'+cat).value + ",";
        }
        urlRoute.setStorage('mod-cat-'+cat,str+id);
      } else {
        if(urlRoute.getStorage('mod-cat-'+cat) !== null){
          var re = new RegExp("/"+id+"/g");
          str = urlRoute.getStorage('mod-cat-'+cat).value.replace(re,'').replace(/,,/g,',');
          if(str[str.length - 1] == ','){
            str = str.slice(0,str.length-1);
          }
          if(str[0] == ','){
            str = str.slice(1,str.length);
          }
          urlRoute.setStorage('mod-cat-'+cat,str);
        }
      }
    }

    var threadtools = function() {
        $('#thread_tools').fadeOut();
        $('#thread_tools_options').fadeIn();
    }

    var stopEditingThreadTools = function() {
        $('#thread_tools').fadeIn();
        $('#thread_tools_options').fadeOut();
        $('.removes').remove();
   }

    var getChecked = function () {
      if(urlRoute.getStorage('mod-cat-{{ $forum->forumid }}') !== null){
        const value = urlRoute.getStorage('mod-cat-{{ $forum->forumid }}').value;
        urlRoute.setStorage('mod-cat-{{ $forum->forumid }}', null);
        return value;
      } else {
        return "";
      }
    }

    var deselectPage = function () {
      urlRoute.setStorage('mod-cat-{{ $forum->forumid }}','');
      $('input:checkbox.thread_check').each(function() {
          this.checked = false;
      });
    }

    var selectPage = function () {
      urlRoute.setStorage('mod-cat-{{ $forum->forumid }}','');
      var first = true;
      var str = ""
      $('input:checkbox.thread_check').each(function() {
          if(first){
            str = this.value;
            first = false;
          } else {
            str = str + "," + this.value;
          }
          this.checked = true;
      });
      urlRoute.setStorage('mod-cat-{{ $forum->forumid }}',str);
    }

    /*var selectAll = function () {
      urlRoute.setStorage('mod-cat-{{ $forum->forumid }}','');
      $('input:checkbox.thread_check').each(function() {
          this.checked = false;
      });
    }

    var deselectAll = function () {
      urlRoute.setStorage('mod-cat-{{ $forum->forumid }}','');
      $('input:checkbox.thread_check').each(function() {
          this.checked = false;
      });
    }*/
    @endif

    @if($can_move_threads)
    var moveThreads = function(){
      var checked = getChecked().replace(/,/g,'-')
      urlRoute.loadPage('/forum/move/thread/'+checked);
    }

    @endif
    @if($can_open_close_thread)

    var stickyThread = function() {
      var checked = getChecked().split(',');
      for(var i = 0; i<checked.length; i++){

        if(checked[i] !== ''){
          var threadid = checked[i];
          $.ajax({
            url: urlRoute.getBaseUrl() + 'forum/sticky/thread',
            type: 'post',
            data: {threadid:threadid},
            success: function(data) {
              urlRoute.ohSnap('Thread Stickied!', 'green');
            }
          })
        }
      }
    }

    var unstickyThread = function() {
        var checked = getChecked().split(',');
        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
  var threadid = checked[i];
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/unsticky/thread',
              type: 'post',
              data: {threadid:threadid},
              success: function(data) {
                urlRoute.ohSnap('Thread Unstickied!', 'green');
              }
            })
          }
        }
    }

      var openThread = function() {

        var checked = getChecked().split(',');

        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
  var threadid = checked[i];
            var type = "open";
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/openclose/thread',
              type: 'post',
              data: {threadid:threadid, type:type},
              success: function(data) {
                urlRoute.ohSnap('Thread(s) Open!', 'green');
              }
            })
          }
        }
      }

      var closeThread = function() {

        var checked = getChecked().split(',');

        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
  var threadid = checked[i];
            var type = "close";
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/openclose/thread',
              type: 'post',
              data: {threadid:threadid, type:type},
              success: function(data) {
                urlRoute.ohSnap('Thread(s) Closed!', 'green');
              }
            })
          }
        }
      }
    @endif

    @if($can_change_owner)
      var setChangeType = function() {
        $('#change_owner').foundation('open');
      }

      var changeOwner = function() {
        var checked = getChecked().split(',');
        console.log(checked);

        var username = $('#change_owner_new_name').val();
        console.log(username);

        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
            var threadid = checked[i];
            console.log('changing '+threadid)
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/change/post/owner',
              type: 'post',
              data: {threadid:threadid, username:username},
              success: function(data) {
                if(data['response'] == true) {
                  $('#change_owner').foundation('close');
                  urlRoute.ohSnap('Thread Owner(s) Changed!', 'green');;
                } else {
                  urlRoute.ohSnap(data['message'], 'red');
                }
              }
            });
          }
        }
      }
    @endif

    @if($can_approve_unapprove_threads)
      var approveThread = function() {
        var checked = getChecked().split(',');
        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
  var threadid = checked[i];
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/approve/thread',
              type: 'post',
              data: {threadid:threadid},
              success: function(data) {
                urlRoute.ohSnap('Thread(s) Approved!', 'green');
              }
            })
          }
        }
      }

      var unapproveThread = function() {
        var checked = getChecked().split(',');
        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){
  var threadid = checked[i];
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/unapprove/thread',
              type: 'post',
              data: {threadid:threadid},
              success: function(data) {
                urlRoute.ohSnap('Thread(s) Unapproved!', 'green');
              }
            })
          }
        }
      }
      @endif

    var setType = function() {
      $('#delete_threads').foundation('open');
    }

    var unDeleteSelected = function(type) {


        var checked = getChecked().split(',');
        for(var i = 0; i<checked.length; i++){

          if(checked[i] !== ''){

            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/undelete/posts',
              type: 'post',
              data: {threadid:checked[i]},
              success: function(data) {
                if(data['response'] == true) {
                  urlRoute.ohSnap('Undeleted Thread!', 'green');
                } else {
                  urlRoute.ohSnap('Something went wrong!', 'red');
                }
              }
            });
          }
        }
    }

    var deleteSelected = function() {
      var checked = getChecked().split(',');

      var type = $('input[name=delete_type]:checked').val();

      if(type != 0) {
        if(type != 1) {
          type = 0;
        }
      }

      if(type != 1) {
        if(type != 0) {
          type = 0;
        }
      }

      for(var i = 0; i<checked.length; i++){

        if(checked[i] !== ''){
  var threadid = checked[i];
          $.ajax({
            url: urlRoute.getBaseUrl() + 'forum/delete/posts',
            type: 'post',
            data: {type:type, threadid:threadid},
            success: function(data) {
              if(data['response'] == true) {
                urlRoute.ohSnap('Deleted Thread(s)!', 'green');
                if(data['stay'] == 1) {
                } else {
                }
              } else {
                urlRoute.ohSnap('Something went wrong!', 'red');
              }
            }
          });

          $('#delete_threads').foundation('close');
        }
      }
    }


    var loadPosters = function(threadid) {
        $('#forumStats').foundation('open');
        $('#postersOfThread').empty();

    $.ajax({
        url: urlRoute.getBaseUrl() + 'xhrst/forum/thread/loadPosters/' + threadid,
        type: 'get',
        success: function(data) {
            if(data['response'] == true) {
                var count = data['count'];
                var array = data['users'];

                for(var i = 0; i < count; i++) {
                  $('#postersOfThread').append('<tr><td>' + array[i]['username'] + '</td><td><a href="/forum/thread/'+ threadid +'/page/1/' + array[i]['clean_username'] + '" onclick="$(\'#forumStats\').modal(\'hide\');" data-close aria-label="Close modal" class="web-page">' + array[i]['posts'] + '</a></td></tr>');
                }
            }
        }
    });
    }
</script>
