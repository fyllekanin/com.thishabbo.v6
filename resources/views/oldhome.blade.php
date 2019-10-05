<script>
    urlRoute.setTitle("TH - Home");
</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(14); ?>
<?php $furnis = \App\Helpers\ForumHelper::getLatestFurnis(); ?>
<?php $editor_perms = false; ?>
<?php if(Auth::check())  $editor_perms = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 256)  ?>
<?php $latestActivitys = \App\Helpers\ForumHelper::getLatestActivity(); ?>
<?php $QuestsHelper = new \App\Helpers\QuestsHelper; ?>
@if(Auth::check())
<?php $username = \App\Helpers\UserHelper::getUserName(Auth::user()->userid); ?>
@endif
<div class="reveal" id="badge_info" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
</div>

<div class="medium-8 column">
    <div class="contentWrapper">
        <div class="small-12 medium-12 large-12 column">
            <div class="contentHeader headerBlue">
                <span>Quest Guides</span>
            </div>
        </div>
        @foreach($articles as $article)
          @if($article['availableID']===0)
            @if(Auth::check())
              @if(!$editor_perms)
                @continue
              @endif
            @else
              @continue
            @endif
          @endif
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

            <div id="thumbnail{{ $article['articleid'] }}" class="article-thumbnail gradualfader small-12 column" style="background-image: url('/_assets/img/thumbnails/{{ $article['articleid'] }}.gif');">

                @if($article['badge'] > 0 && $article['badge_code'] != '')
                  <div class="article-badge">
                      @if($article['completed']) <img src="/_assets/img/website/bcompleted.png" class="tick"></img> @endif
                      <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badge_code'] }}.gif" alt="badge"></img>
                  </div>
                @endif

              </div>
                      <div class="article-info">

                          <a class="articleTitle" href="/article/{{ $article['articleid'] }}" class="web-page">{{ $article['title'] }}</a>
                          by <a class="articleAuthor" href="/profile/{{ $article['clean_username'] }}" class="web-page">{!! $article['username'] !!}</a>
                          <div class="showHover" id="showHover{{ $article['articleid'] }}">{{ $article['snippet'] }}</div>
                          <div class="timeHome"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $article['time'] }}</div>


                      </div>
              </div>
            </a>
          </div>
        @endforeach
        <div class="medium-7 column end">
          <div class="contentHeader spacer headerBlue">
            <span>Latest Threads</span>
          </div>
          <div class="content-holder homebox">
            <div class="content">
              <div class="content-ct">
                <table>
                  @foreach($threads as $thread)
                  <tr class="threadrow">
                    <td><a class="web-page forumBold hover-box-info" title="Thread Posted {{ $thread['posted'] }}" href="/forum/thread/{{ $thread['id'] }}/page/1">{!! $thread['threadprefix'] !!}{{ $thread['title'] }}</a> by {!! $thread['username'] !!}</td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>

          </div>
        </div>
          <div class="medium-5 column end">
            <div class="contentHeader spacer headerBlue">
               <span>Staff Spotlight</span>
            </div>

            <div class="content-holder homebox">
               <div class="content">
                   <div class="ct-center">
                     <div class="sotwcar">

                       @foreach($sotw as $temp)
                         @if($temp['name'] != null AND $temp['habbo'] != '')
                         <div class="">
                            <center>
                             <img onerror="spotlightError(this);" class="habbo-spotlight hover-box-info" src="goodies/alteration/{{ $temp['habbo'] }}/{{ $temp['alt'] }}" alt=""><br>
                             <b>{{ $temp['text'] }}</b><br>{{ $temp['name'] }}
                             </center>

                           </div>
                           @endif
                       @endforeach

                       </div>
                   </div>
               </div>
            </div>

        </div>
      </div>

        <div class="contentHeader headerBlue">
              <span>Scanned Badges</span>
          </div>

          <div class="content-holder">
              <div class="content">
                  <div class="ct-center">

                      @foreach($badges as $badge)
                          <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                              <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" onclick="badgeInfo('{{ $badge['name'] }}', '{{ addslashes($badge['desc']) }}' , '{{ $QuestsHelper::getQuest($badge['name']) }}', '{{ $QuestsHelper::isSubscribed($badge['name']) }}' )"
                              /> @if($badge['new'])
                              <div class="badge-new-badge">New</div>@endif
                          </div>
                      @endforeach

                  </div>
              </div>
          </div>
    </div>

    <!--<div class="contentWrapper">
        <div class="small-4 column">
            <div class="contentHeader headerBlue">
                <span>Events: Now</span>
            </div>
        </div>

        <div class="small-4 column">
            <div class="contentHeader headerBlue">
                <span>Events:  Next</span>
            </div>
        </div>


        <div class="small-4 column">
            <div class="contentHeader headerBlue">
                <span>Events:  Later</span>
            </div>
        </div>


        <div class="small-4 column end">
            <div class="events-box">
                <Active one shouldn't have opacity aka "GRADUALFADER" class
                <a href="/profile/{{ $currentEvent['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $currentEvent['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">

                            <b>{{ $currentEvent['event'] }}</b>
                            <p>{{ $time }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="events-box gradualfader">
                <a href="/profile/{{ $nextEvent['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $nextEvent['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">
                            <b>{{ $nextEvent['event'] }}</b>
                            <p>{{ $nextTime }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="events-box gradualfader">
                <a href="/profile/{{ $laterEvent['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $laterEvent['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">
                            <b>{{ $laterEvent['event'] }}</b>
                            <p>{{ $laterTime }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>


        <div class="small-4 column">
            <div class="contentHeader headerRed">
                <span>Radio: Now</span>
            </div>
        </div>

        <div class="small-4 column">
            <div class="contentHeader headerRed">
                <span>Radio:  Next</span>
            </div>
        </div>


        <div class="small-4 column">
            <div class="contentHeader headerRed">
                <span>Radio:  Later</span>
            </div>
        </div>


        <div class="small-4 column end">
            <div class="events-box">
              Active one shouldn't have opacity aka "GRADUALFADER" class
                <a href="/profile/{{ $currentDJ['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $currentDJ['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">
                            <b>{{ $currentDJ['name'] }}</b>
                            <p>{{ $time }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="events-box gradualfader">
                <a href="/profile/{{ $nextDJ['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $nextDJ['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">
                            <b>{{ $nextDJ['name'] }}</b>
                            <p>{{ $nextTime }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="events-box gradualfader">
                <a href="/profile/{{ $laterDJ['name'] }}" class="web-page">
                    <div class="events-thumbnail" style="background-image: url({{ $laterDJ['image'] }}); background-size: 100%;">

                        <div class="text-events ct-center line">
                            <b>{{ $laterDJ['name'] }}</b>
                            <p>{{ $laterTime }}:00</p>
                        </div>

                    </div>
                </a>
            </div>
        </div>

        <div class="small-12 column">
            <div class="contentHeader headerPink">
                <span>Creations</span>
            </div>
        </div>
        <div class="contentWrapper">


            <div class="roomCarousel small-12 column">
                @foreach($rooms as $room)
                <div class="small-4 column">
                    <a href="/creation/{{ $room['id'] }}" class="web-page">
                        <div class="events-box">
                            <div class="creation-thumbnail" style="background: url({{ $room['image'] }});     background-size: 100%;">
                                <div class="text-events">
                                    <b>{{ $room['name'] }}</b><br>
                                    <i>by {{ $room['user'] }}</i>

                                </div>
                            </div>
                        </div>

                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>-->


<div class="small-4 mobileFunction column">

    <!--@if(Auth::check())
    <div class="contentHeader  headerBlue">
        <span>My Profile</span>
    </div>


    <div class="myProfile">

        @if(Auth::user()->habbo != '')
        <div class="myHabbo" style="background: url(https://www.habbo.com/habbo-imaging/avatarimage?user={{ Auth::user()->habbo}}&direction=4&head_direction=2&&gesture=nrm&size=m);"></div>
        @else
        <div class="myHabbo" style="background: url(https://www.habbo.com/habbo-imaging/avatarimage?user=irDez&direction=4&head_direction=2&&gesture=nrm&size=m);"></div>
        @endif

        <div class="statPosition">
            <div class="myStat"><i class="fa fa-comment" aria-hidden="true"></i> <b>{{ number_format(Auth::user()->postcount) }}</b> posts</div>
            <div class="myStat"><i class="fa fa-comment" aria-hidden="true"></i> <b>{{ number_format(Auth::user()->threadcount) }}</b> threads created</div>
            <div class="myStat"><i class="fa fa-ticket" aria-hidden="true"></i> <b>{{ number_format(Auth::user()->credits) }}</b> THC</div>
            <div class="myStat"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <b>{{ number_format(Auth::user()->likecount) }}</b> likes</div>
        </div>

        <div class="profileTitle">
            <b>Hello & welcome!</b><br /> How are you doing, <b><a href="/profile/{{ Auth::user()->username }}" class="web-page">{!! $username !!}</a></b>?<br>
        </div>


    </div>
    @endif

    <!--<div class="contentHeader headerGreen">
        <span>Site News</span>
    </div>
    <div class="content-holder">
      <div class="content">
        <div class="content-ct">
          <table>
            @foreach($sitenews as $thread)
            <tr class="newsrow">
              <td><a class="web-page forumBold hover-box-info" title="Thread Posted {{ $thread['posted'] }}" href="/forum/thread/{{ $thread['id'] }}/page/1">{{ $thread['title'] }}</a> by {!! $thread['username'] !!}</td>
            </tr>
            @endforeach
          </table>
      </div>
    </div>
  </div>-->
    <!--<div class="carousel">
        @foreach($carousel as $advert)
        <a href="{{ $advert['link'] }}" class="web-page">
            <div class="rotatingAdvert" style="background: url({{ $advert['image'] }}); background-size:100%;">
                <div class="profileTitle">
                    {{ $advert['text'] }}
                </div>
            </div>
        </a>
        @endforeach
    </div>-->

    <!--<div class="contentHeader headerGreen">
        <span>Scanned Badges</span>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="ct-center">

                @foreach($badges as $badge)
                <div class="small-2 column">
                    <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                        <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" onclick="badgeInfo('{{ $badge['name'] }}', '{{ addslashes($badge['desc']) }}' , '{{ $QuestsHelper::getQuest($badge['name']) }}', '{{ $QuestsHelper::isSubscribed($badge['name']) }}' )"
                        /> @if($badge['new'])
                        <div class="badge-new-badge">New</div>@endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="contentHeader headerBlue">
        <span>Staff of the Week</span>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                Staff of the Week is updated every <b>Sunday</b> at <b>7pm GMT</b>. Below, you'll see all of last weeks winners, well done to all of you!<br><br> Click <a href="/forum/category/19/page/1" class="web-page"><b>here</b></a> to see an archive
                of winners!
                <br><br>

                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tbody>
                            <tr>
                                <th style="width:50%">Department</th>
                                <th>Staff</th>
                            </tr>
                            <tr>
                                <td>EU Management</td>
                                <td>@if($eu_management != "__blank__")<a href="/profile/{{ $eu_management2 }}" class="web-page">{!! $eu_management !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>NA Management</td>
                                <td>@if($na_management != "__blank__")<a href="/profile/{{ $na_management2 }}" class="web-page">{!! $na_management !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>OC Management</td>
                                <td>@if($oc_management != "__blank__")<a href="/profile/{{ $oc_management2 }}" class="web-page">{!! $oc_management !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>EU Radio</td>
                                <td>@if($eu_radio != "__blank__")<a href="/profile/{{ $eu_radio2 }}" class="web-page">{!! $eu_radio !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>EU Events</td>
                                <td>@if($eu_events != "__blank__")<a href="/profile/{{ $eu_events2 }}" class="web-page">{!! $eu_events !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>NA Radio</td>
                                <td>@if($na_radio != "__blank__")<a href="/profile/{{ $na_radio2 }}" class="web-page">{!! $na_radio !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>NA Events</td>
                                <td>@if($na_events != "__blank__")<a href="/profile/{{ $na_events2 }}" class="web-page">{!! $na_events !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>OC Radio</td>
                                <td>@if($oc_radio != "__blank__")<a href="/profile/{{ $oc_radio2 }}" class="web-page">{!! $oc_radio !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>OC Events</td>
                                <td>@if($oc_events != "__blank__")<a href="/profile/{{ $oc_events2 }}" class="web-page">{!! $oc_events !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>Moderation</td>
                                <td>@if($moderation != "__blank__")<a href="/profile/{{ $moderation2 }}" class="web-page">{!! $moderation !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>Media</td>
                                <td>@if($media != "__blank__")<a href="/profile/{{ $media2 }}" class="web-page">{!! $media !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                            <tr>
                                <td>Quests</td>
                                <td>@if($quests != "__blank__")<a href="/profile/{{ $quests2 }}" class="web-page">{!! $quests !!}</a>@else <b>N/A</b>@endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>-->
    <div class="contentHeader headerRed">
      <span>Snow Controls</span>
    </div>
    <div class="content-holder">
      <div class="content">
        <div class="content-ct">
          Are you a bit cold? Do you need to turn the snow down? Or perhaps you need more to build a snowman! Here you can control how much snow falls on the site.

          <div class="homeSnowSlider">
              <div class="snowSlider">
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="contentHeader headerRed">
       <span>Radio Timetable</span>
   </div>
     <div class="content-holder">
       <div class="content">
         <div class="content-ct">
           <table>
             <?php $current = true; ?>
             @foreach($radioTimetable as $slot)
               <tr class="@if($current) current-slot @else dj-slot @endif">
                 <td><strong>{{ $slot['time'] }}:00</strong></td>
                 <td>{!! $slot['name'] !!}</td>
                 <td><img class="habbo-head" src="https://www.habbo.com/habbo-imaging/avatarimage?hb=image&user={{ $slot['habbo'] }}&headonly=1&direction=2&head_direction=3&action=&gesture=&size=s" alt="">
               </td>

               </tr>
               <?php $current = false; ?>
             @endforeach
           </table>
         </div>
       </div>

     </div>
      <div class="contentHeader headerRed">
        Upcoming Events
      </div>
          <!-- <div class="eventstimetable"> -->
            <div class="events-box">
                    <div class="events-thumbnail" style="background-image: url({{ $currentEvent['image'] }});">

                        <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $currentEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">



                        <div class="text-events-left ct-center line">

                            <span class="event-name"><b>Now:</b></span>
                            <b><span>{{ $currentEvent['event'] }}</span></b>
                            <p>{{ $time }}:00 - {{ $currentEvent['name'] }}</p>
                        </div>
                      </div>
            </div>
            <div class="events-box">
                    <div class="events-thumbnail faded" style="background-image: url({{ $nextEvent['image'] }});">

                          <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $nextEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">

                        <div class="text-events-left ct-center line">
                            <span class="event-name"><b>Next:</b></span>
                            <b><span>{{ $nextEvent['event'] }}</span></b>
                            <p>{{ $nextTime }}:00 - {{ $nextEvent['name'] }}</p>
                        </div>

                    </div>
            </div>
            <div class="events-box events-box-end">
                  <div class="events-thumbnail faded" style="background-image: url({{ $laterEvent['image'] }});">

                      <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $laterEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">

                      <div class="text-events-left ct-center line">
                          <span class="event-name"><b>Later:</b></span>
                          <b><span>{{ $laterEvent['event'] }}</span></b>
                          <p>{{ $laterTime }}:00 - {{ $laterEvent['name'] }}</p>
                      </div>

                  </div>
            <!-- </div> -->
</div>

<script type="text/javascript">

    $(function() {
      var snow_value = 50;

      var snowSlider = $(".snowSlider").slider({
        orientation: "horizontal",
        min: 0,
        max: 100,
        step: 1,
        animate: true,
        value: urlRoute.snow.flakesMax,
        stop: function(e, ui) {
          updateSnow(ui.value);
        }
      });

      $('.sotwcar').slick({
            infinite:true,
            autoplay: true,
            slidesToShow: 1,
            arrows: true,
            dots: true,
            lazyLoad: 'ondemand'
      });

        $('.carousel').slick({
            autoplay: true,
            arrows: false,
            lazyLoad: 'progressive'
        });


        $('.roomCarousel').slick({
            autoplay: true,
            slidesToShow: 3,
            dots: true,
            arrows: false,
            lazyLoad: 'progressive'
        });

    });
    var updateSnow = function (snow_value) {
      console.log('updating snow');

      var snowamt = snow_value;
      if(snowamt>150){
        snowamt = 150;
      }
      if(snowamt<0){
        snowamt = 0;
      }
      urlRoute.changeSnowAmt(snowamt);

      @if(Auth::check())
      $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/snow',
        type: 'post',
        data: {snow:snowamt}
      });
      @else
      urlRoute.setStorage('snow',snowamt);
      @endif

    }

    var showDesc = function(id) {
      $('#showHover'+id).toggle();
      $('#thumbnail'+id).toggleClass("hovered");

    }
    var badgeError = function(image) {
        image.onerror = "";
        image.src = '{{ asset("_assets/img/website/badge_error.gif") }}';
        return true;
    };


    var spotlightError = function(image) {
        image.onerror = "";
        image.src = 'goodies/alteration/irDez/18';
        return true;
    };

    var badgeInfo = function(badgeid, badgedesc, guide, subscribed) {

        $('#badge_info').html("<div class='small-2 column'><img onerror='badgeError(this)' src='https://habboo-a.akamaihd.net/c_images/album1584/" + badgeid + ".gif' style='width:40px;height:40px;' /></div>");
        $('#badge_info').append("<h6 class='small-10 column text-right'>" + badgeid + " - " + badgedesc + "</h6>");
        if (guide !== '-1') {
            $('#badge_info').append("<div class='right'><a class='bold' id='guide-link'>Find out how to get this badge &raquo;</a></div>");
            $('#guide-link').click(function() {
                urlRoute.loadPage('article/' + guide);
            });
            $('#badge_info').foundation('open');
        } else {
            @if(Auth::check())
            if (subscribed) {
                $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold' id='unsubscribe-link'>You're subscribed to this badge. Click to unsubscribe &raquo;</a></div>");
            } else {
                $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold' id='subscribe-link'>Subscribe to this badge to be notified when we write a guide for it &raquo;</a></div>");
            }
            @else $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold web-page' id='sign-up-link'>Sign up/log in to subscribe to this badge &raquo;</a></div>");
            @endif
            $('#guide-link').click(function() {
                $('#badge_info').foundation('close');
                urlRoute.loadPage('article/' + guide)
            });
            $('#subscribe-link').click(function() {
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/subscribe',
                    type: 'POST',
                    data: {
                        badgeid: badgeid
                    },
                    success: function(data) {
                        urlRoute.ohSnap("<span class=\"alert-title\">Congratulations!</span><br />Success, you!", 'green');
                        $('#' + badgeid).attr("onclick", "badgeInfo('" + badgeid + "','" + badgedesc + "','" + guide + "','1')");
                    },
                    error: function(data) {
                        urlRoute.ohSnap("<span class=\"alert-title\">Oh snap!</span><br />Something went wrong!", 'red');
                    },
                    complete: function(data) {
                        $('#badge_info').foundation('close');
                        $('#' + badgeid).attr("onclick", "");
                    }

                });
            });
            $('#unsubscribe-link').click(function() {
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/unsubscribe',
                    type: 'DELETE',
                    data: {
                        badgeid: badgeid
                    },
                    success: function(data) {
                        urlRoute.ohSnap("<span class=\"alert-title\">Oh man!</span><br />You have successfully unsubscribed!", 'green');
                        urlRoute.loadPage('badges');
                        $('#' + badgeid).attr("onclick", "badgeInfo('" + badgeid + "','" + badgedesc + "','" + guide + "','')");
                    },
                    error: function(data) {
                        urlRoute.ohSnap("<span class=\"alert-title\">Oh snap!</span><br />Something went wrong!", 'red');
                        urlRoute.loadPage('badges');
                    },
                    complete: function(data) {
                        $('#badge_info').foundation('close');
                    }
                });
            });
            $('#sign-up-link').click(function() {
                $('#badge_info').foundation('close');
                urlRoute.loadPage('register');
            });

        }
        $('#badge_info').foundation('open');
    }


    var skip = 144;

    if (urlRoute.currentUrl == "/badges") {
        $(window).scroll(function() {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/load/' + skip,
                    type: 'get',
                    success: function(data) {
                        $('#list_badges').append(data['returnHTML']);
                        skip += 144;
                        Tipped.create('.hover-box-info');
                    }
                });
            }
        });
    }

    var destroy = function() {
        badgeError = null;
        badgeInfo = null;
        $('.carousel').slick('unslick');
        $('.roomCarousel').slick('unslick');
    }
</script>
