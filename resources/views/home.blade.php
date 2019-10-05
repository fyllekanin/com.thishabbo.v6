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
    @if(Auth::check())
    @if($gdpr == false)<div class="alert alert-danger">
        <b>Due to the GDPR regulations, you will need to give us concent to use your data - please check the box.</b><br />
        <br />
        - If you are 16 or over, you may consent yourself and make your own decision<br />
        - If you are 16 and under you must seek permission. Once your parents have given permission you may check the box.<br />
        <br />
        <center><a href="/forum/thread/566313/page/1" class="web-page" style="color: #000;"><u>Click here to see what data we store and collect of you.</u></a></center><br />
        <br />
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" name="button" onclick="gdprAgree()">I agree to allow ThisHabbo to use my data. <i class="fa fa-check"></i></button><br />
        <button class="pg-red fullWidth headerBlue gradualfader shopbutton" name="button" onclick="gdprDisagree()">I don't agree to allow ThisHabbo to use my data. <i class="fa fa-times"></i></button><br />
    </div>
    <br />
    <br />
    @endif
    @endif

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

                    <div class="article-tags tagFix">
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
                            <div class="" style="min-height: 191px;">
                                <center>
                                    <!-- <img onerror="spotlightError(this);" class="habbo-spotlight hover-box-info" src="/goodies/alteration/{{ $temp['habbo'] }}/{{ $temp['alt'] }}" alt=""><br> -->
                                    <img onerror="spotlightError(this);" class="habbo-spotlight hover-box-info" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $temp['habbo'] }}&direction=3&head_direction=3&action=wav&gesture=sml&size=m" alt=""><br>
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
        <div class="medium-12 column end">
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
</div>

<div class="small-4 mobileFunction column">
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
            $('.sotwcar').slick({
                infinite:true,
                autoplay: true,
                slidesToShow: 1,
                arrows: false,
                dots: false,
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
