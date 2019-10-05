<script> urlRoute.setTitle("TH - Hub");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content subNav">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Posts</span>
    </div>
  </div>
</div>


<div class="medium-12 column">


  <div class="content-holder">
      <div class="content">
    {!! $pagi !!}
      </div>
  </div>

  <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerBlue">
        <span>Latest Posts</span>
      </div>
      <div class="content-ct">
        @foreach($threads as $thread)
          <div class="forum-list">
            <div class="medium-4 large-5 column make-forum-bigger">
              <div class="forum-name">
                @if($thread['sticky'] == 1)
                  <span class="closed-label">Sticky</span>
                @endif
                @if($thread['open'] == 0)
                  <span class="closed-label">Closed</span>
                @endif
                @if($thread['visible'] == 0)
                  <span class="deleted-label">Deleted</span>
                @endif
                <a href="/forum/thread/{{ $thread['threadid'] }}/page/{{ $thread['page'] }}" class="web-page">{!! $thread['title'] !!}</a> <br />
                <i>{{ $thread['time'] }} by {!! $thread['username'] !!}</i>
              </div>
            </div>
            <div class="medium-4 large-2 column hide-stats">
              <div class="forum-stats">
                {!! $thread['replys'] !!}
              </div>
            </div>
            <div class="medium-4 large-5 column make-forum-smaller">
              <div class="forum-latest-post">
                <div class="forum-latest-post-av" style="background-image: url('{{ $thread['last_poster_avatar'] }}');">

                </div>
                <div class="forum-latest-post-info">
                  <b><a href="/forum/thread/{{ $thread['threadid'] }}/page/{{ $thread['page'] }}" class="web-page">Latest Poster</a></b> <br />
                  {{ $thread['last_poster_time'] }} <br />
                  {!! $thread['last_poster_username'] !!}
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>


  <div class="content-holder">
      <div class="content">
    {!! $pagi !!}
      </div>
  </div>

  <!-- <div class="contentHeader headerBlue">
                <span>My Daily Quests</span>
  </div>
<div class="content-holder">
<div class="content">
  <div class="content-ct">
    <table class="responsive" style="width: 100%;">
        <tr>
            <th>Quest</th>
            <th>Reward</th>
        </tr>
            @foreach($quests as $quest)
            <tr>
                <td>{{ $quest['text'] }}</td>
                <td>{{ $quest['box'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</div>
</div> -->

</div>

<script>
    $(document).ready(function(){
      $('.carousel').slick({
          autoplay: true,
          arrows: false
      });
  });

  var badgeError = function(image) {
      image.onerror = "";
      image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
      return true;
  };
var visitArticle = function(id) {
      urlRoute.loadPage('article/' + id);
  }
</script>
