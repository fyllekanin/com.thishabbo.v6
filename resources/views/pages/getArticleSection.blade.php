<script> urlRoute.setTitle("TH - {{ $section }}");</script>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>
<?php $editor_perms = false; ?>
<?php if(Auth::check())  $editor_perms = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 256)  ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>{{ $section }}</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
  <div class="contentWrapper">
    <div class="small-12 medium-12 large-12 column">
      <div class="contentHeader headerRed">
        <span>{{ $section }}</span>
      </div>
    </div>
    @foreach($articles as $article)
      <div class="small-6 medium-4 column end">
        <a href="/article/{{ $article['articleid'] }}" class="web-page">
          <div class="article-box" onmouseout="showDesc({{ $article['articleid'] }})" onmouseover="showDesc({{ $article['articleid'] }})">
            <div class="article-tags">
              @if($article['availableID'] === 2)
                <div class="red-tag">Unavailable</div>
              @endif
              @if($article['availableID'] === 1)
                <div class="green-tag">Available</div>
              @endif
              @if($article['type'] === 0)
                @if($article['difficulty'] === 0)
                  <div class="yellow-tag">Easy</div>
                @endif
                @if($article['difficulty'] === 1)
                  <div class="pink-tag">Medium</div>
                @endif
                @if($article['difficulty'] === 2)
                  <div class="red-tag">Hard</div>
                @endif
                @if($article['paid'] === 0)
                  <div class="blue-tag">Free</div>
                @endif
                @if($article['paid'] === 1)
                  <div class="red-tag">Paid</div>
                @endif
              @endif
            </div>
            <div id="thumbnail{{ $article['articleid'] }}" class="article-thumbnail gradualfader small-12 column" style="background-image: url('/_assets/img/thumbnails/{{ $article['articleid'] }}.gif'), url('/_assets/img/thumbnails/1010.gif');">
              @if($article['badge'] > 0 && $article['badge_code'] != '')
              <div class="article-badge">
              @if($article['completed'])<img src="/_assets/img/website/bcompleted.png" class="tick">@endif
                <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badge_code'] }}.gif" alt="badge">
              </div>
              @endif
            </div>
            <div class="article-info">
              <a class="articleTitle" href="/article/{{ $article['articleid'] }}" class="web-page">{{ $article['title'] }}</a>
              by <a class="articleAuthor" href="/profile/{{ $article['clean_username'] }}" class="web-page">{!! $article['username'] !!}</a>
              <div class="timeHome"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $article['time'] }}</div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
    <div class="small-12 medium-12 large-12 column">
      <div class="content-holder">
        <div class="content">
          {!! $pagi !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="small-4 column mobileFunction">
  <div class="contentHeader headerRed">
    Scanned Badges
    <a href="/badges" class="headerLink white_link web-page">More</a>
  </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct ct-center">
        <div class="row" id="list_badges">
          @foreach($badges as $badge)
            <div class="small-3 column">
              <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" />
                @if($badge['new'])
                  <div class="badge-new-badge">New</div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var badgeError = function(image) {
image.onerror = "";
image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
return true;
}

var destroy = function() {
badgeError = null;
}
</script>
