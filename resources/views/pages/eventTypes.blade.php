<script> urlRoute.setTitle("TH - Event Types");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-topic topic-breadcrum" style="margin-bottom: 0.5rem;">
      <div class="content-topic-opacity"></div>
      <span><a href="/home" class="web-page">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
      <span>Event Types</span>
  </div>
</div>

<div class="small-12 medium-7 large-9 column">
  <div class="content-holder">
    <div class="inner-content-holder">
      <div class="content-topic topic-blue">
        <div class="content-topic-opacity"></div>
        <span>Event Guides</span>
      </div>
      <div class="content-ct">
          With all these new events popping up around Habbo, How could you ever keep up? Thats why we are working hard to always keep this list updated! If somethings missing send us a tweet! <a href="https://www.twitter.com/twitter">@Twitter</a>.
      </div>
    </div>
  </div>

  <div class="row">
    @foreach($event_types as $event_type)
      <div class="small-12 medium-6 column">
            <div class="content-holder">
              <div class="inner-content-holder">
                <a href="#"><div class="content-topic topic-blue">
                  <div class="content-topic-opacity"></div>
                  <span>{{ $event_type['event'] }}</span>
                </div></a>
                <div class="content-ct">
                  <div class="expand">
                    {!! $event_type['desc'] !!}
                    </div>
                </div>
              </div>
            </div>
        </div>
    @endforeach
  </div>
</div>
<div class="small-12 medium-5 large-3 column">
  <div class="content-holder">
    <div class="inner-content-holder">
      <div class="content-topic topic-red">
        <div class="content-topic-opacity"></div>
        <span>Scanned Badges</span> <a href="/badges" class="web-page"><b>See More</b></a>
      </div>
      <div class="content-ct ct-center">
        <div class="row" id="list_badges">
          @foreach($badges as $badge)
          <div class="small-3 column">
            <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
              <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" />
              @if($badge['new'])<div class="badge-new-badge">New</div>@endif
            </div>
           </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      $(document).foundation();
    });

$("div.topic-blue").click(function () {
    $(this).closest('.content-holder')
        .find('.expand').slideToggle("fast");
});

  var badgeError = function(image) {
    image.onerror = "";
    image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
    return true;
  }

  var destroy = function() {
      badgeError = null;
    }
</script>