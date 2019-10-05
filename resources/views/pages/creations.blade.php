<script> urlRoute.setTitle("TH - Creations");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Creations</span>
    </div>
  </div>
</div>


<div class="small-12 column">
        <div class="contentHeader headerBlue">
          <span>User Room or Graphic Creations</span>
          @if(Auth::check())
            <a href="/creations/upload" class="web-page headerLink white_link">Upload Creation</a>
          @else
            <a class="headerLink white_link">
              You need to be logged in to upload a creation
            </a>
          @endif
        </div>
<div class="contentWrapper">


  @foreach($creations as $creation)
    <div class="small-6 medium-4 column end">

      <a href="/creation/{{ $creation['creationid'] }}" class="web-page">
      <div class="article-box">


      <div class="article-thumbnail gradualfader small-12 column" style="background-image: url({{ $creation['image'] }});">

        </div>
                <div class="article-info">

                  <a href="/creation/{{ $creation['creationid'] }}" class="web-page">{{ $creation['name'] }}</a><br />

                  <a href="/profile/{{ $creation['clean_username'] }}/page/1" class="web-page">{!! $creation['username'] !!}</a>

                  <div class="timeHome"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $creation['time'] }}</div>


                </div>
        </div>
      </a>
    </div>
  @endforeach



</div>
</div>
<div class="small-12 column">
  <div class="content-holder">
    <div class="content">
      {!! $pagi !!}
    </div>
  </div>
</div>
