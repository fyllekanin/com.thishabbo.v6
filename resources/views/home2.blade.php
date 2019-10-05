<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(13); ?>
<?php $furnis = \App\Helpers\ForumHelper::getLatestFurnis(); ?>
<?php $editor_perms = false; ?>
<?php if(Auth::check())  $editor_perms = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 256)  ?>
<?php $latestActivitys = \App\Helpers\ForumHelper::getLatestActivity(); ?>
<?php $QuestsHelper = new \App\Helpers\QuestsHelper; ?>
@if(Auth::check())
<?php $username = \App\Helpers\UserHelper::getUserName(Auth::user()->userid); ?>
@endif

<div class="medium-8 column end">

    <div class="contentHeader headerBlue">
      <span>Quests</span>
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

          <div class="article-box">
            @if($article['badge'] > 0 && $article['badge_code'] != '')
              <div class="article-badge">
                  @if($article['completed']) <img src="/_assets/img/website/bcompleted.png" class="tick"></img> @endif
                  <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badge_code'] }}.gif" alt="badge"></img>
              </div>
            @endif
              <a href="/article/{{ $article['articleid'] }}" class="web-page">
                  <div class="article-thumbnail gradualfader" style="background-image: url('/_assets/img/thumbnails/{{ $article['articleid'] }}.gif');">

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
                  </div>
              </a>

                  <div class="article-info">
                      <a href="/article/{{ $article['articleid'] }}" class="web-page">{{ $article['title'] }}</a>
                      <a href="/profile/{{ $article['clean_username'] }}" class="web-page">{!! $article['username'] !!}</a>
                      <div class="timeHome"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $article['time'] }}</div>
                  </div>
          </div>
    @endforeach


    </div>

    <div class="contentWrapper">
    <div class="medium-7 column end">
      <div class="contentHeader headerGreen">
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
        <div class="contentHeader headerPink">
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
                         <img class="habbo-head hover-box-info" src="https://www.thishabbo.com/goodies/alteration/{{ $temp['habbo'] }}/{{ $temp['alt'] }}" alt="">

                         <p><b>{{ $temp['text'] }}</b><br>{{ $temp['name'] }}</p>
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




  </div>

 <div class="medium-4 column end">
  <div class="contentHeader headerBlue">
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
    <div class="contentHeader headerBlue">
      Upcoming Events
    </div>
        <div class="eventstimetable">
          <div class="events-box">
                  <div class="events-thumbnail gradualfader" style="background-image: url({{ $currentEvent['image'] }});">

                      <div class="text-events ct-center line">

                          <b><span class="event-name">{{ $currentEvent['event'] }}</span></b>
                          <p>{{ $time }}:00 - {{ $currentEvent['name'] }}</p>
                      </div>
                    </div>
          </div>
          <div class="events-box">
                  <div class="events-thumbnail faded" style="background-image: url({{ $nextEvent['image'] }});">

                      <div class="text-events ct-center line">

                          <b><span class="event-name">{{ $nextEvent['event'] }}</spam></b>
                          <p>{{ $nextTime }}:00 - {{ $nextEvent['name'] }}</p>
                      </div>

                  </div>
          </div>
          <div class="events-box events-box-end">
                <div class="events-thumbnail faded" style="background-image: url({{ $laterEvent['image'] }});">

                    <div class="text-events ct-center line">

                        <b><span class="event-name">{{ $laterEvent['event'] }}</span></b>
                        <p>{{ $laterTime }}:00 - {{ $laterEvent['name'] }}</p>
                    </div>

                </div>
          </div>

      </div>


       <!--<div class="contentHeader headerBlue">
          <span>DJ Likes Leaderboard</span>
      </div>
      <div class="content-holder">
        <div class="content">
          <div class="content-ct">
            <table>
              @foreach($likesLeaderboard as $dj)
                <tr>
                  <td><img class="habbo-head" src="https://www.habbo.com/habbo-imaging/avatarimage?hb=image&user={{ $dj['habbo'] }}&headonly=1&direction=2&head_direction=3&action=&gesture=&size=s" alt=""></td>
                  <td>{!! $dj['name'] !!}</td>
                  <td>{{ $dj['likes'] }}</td>
                </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>-->


  </div>

  <script type="text/javascript">
    $(document).ready(function(){
        $('.sotwcar').slick({
              infinite:true,
              autoplay: true,
              slidesToShow: 1,
              arrows: true,
              dots: true,
              lazyLoad: 'ondemand'
        });
    });
  </script>
