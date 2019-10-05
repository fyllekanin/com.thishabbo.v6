<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Start of Social Networking -->
        <meta property="og:title" content="ThisHabbo.com" />
        <meta property="og:description" content="ThisHabbo is Habbo's most active international fansite. We were the first fansite to set a trend of NA, OC and EU. We are your number one international site." />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="https://www.thishabbo.com" />
        <meta name="twitter:site" content="@thishabbo">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="ThisHabbo.com" />
        <meta name="twitter:description" content="ThisHabbo is Habbo's most active international fansite. We were the first fansite to set a trend of NA, OC and EU. We are your number one international site." />
        <meta name="twitter:domain" content="https://www.thishabbo.com">
        <meta name="keywords" content="habbo, fansite, thishabbo, guides, tutorials, rooms, pictures, gifs, badges, events, radio, dj, stream, rewards, shop, international"/>
        <!-- End of Social Networking -->
        <link rel="shortcut icon" href="/favicon.ico" />
        <title>TH</title>
        {{ Html::style('_assets/css/foundation.min.css') }}
        {{ Html::style('_assets/css/font-awesome.min.css') }}
        {{ Html::style('_assets/css/responsive-tables.css') }}
        {{ Html::style('_assets/css/theme/default/wbbtheme.css') }}
        {{ Html::style('_assets/css/freezeframe_styles.css') }}
        {{ Html::style('_assets/css/tipped.css') }}
        {{ Html::style('_assets/css/jquery-ui.css' )}}
        {{ Html::style('_assets/css/v6-0-0.css?19') }}
        {{ Html::style('_assets/css/slick.css') }}
        {{ Html::style('_assets/css/slick-theme.css?1') }}
        <!-- Javascript Includes -->
        {{ Html::script('_assets/js/freezeframe.pkgd.js') }}
        {{ Html::script('_assets/js/vendor/jquery.js') }}
        <!-- {{ Html::script('_assets/js/jquery.mobile.js') }} -->
        {{ Html::script('_assets/js/vendor/jquery-ui.min.js') }}
        {{ Html::script('_assets/js/vendor/foundation.min.js') }}
        {{ Html::script('_assets/js/vendor/responsive-tables.js') }}
        {{ Html::script('_assets/js/vendor/jquery.wysibb.js') }}
        {{ Html::script('_assets/js/jquery.contextMenu.js ')}}
        {{ Html::script('_assets/js/vendor/jquery.slimscroll.min.js') }}
        {{ Html::script('_assets/js/vendor/jquery.ui.position.js') }}
        {{ Html::script('_assets/js/slick.js') }}
        {{ Html::script('https://www.gstatic.com/charts/loader.js') }}
        <!-- Slick Default Stuff -->
        <style type="text/css">
        {!! $default_css !!}
        </style>
    </head>
    <body>
        <a class="cd-top cd-is-visible cd-fade-out" onclick="$('body').scrollTo('body',{duration:'slow', offsetTop : '-75'});">Top</a>
        <div class="ohsnap-gg-fking-gg"></div>
        <audio src="http://{{ $ip }}:{{ $port }}/;stream.nsv" style="display: none;" id="radio_player" preload="none"></audio>
        <div class="overlay"></div>
        <div id="side-navigation">
            <div id="side-menu-items">
                @include('menu-stuff.menu-mobile', ['mobile' => 1, 'homePage' => $homePage])
            </div>
        </div>
        <nav>
            <div class="row">

                <div class="small-12 column">
                    <div id="mobile-menu-trigger">
                        <span id="triggerMenu">&#9776;</span>
                        <span id="mobile-extra-stuff">
                            @include('menu-stuff.extra-menu-mobile', ['mobile' => 1])
                        </span>
                    </div>
                    <div id="top-navigation">
                        @include('menu-stuff.menu', ['mobile' => 0])
                    </div>
                    <div id="top-log-reg">
                      <div class="radio3">
                        <span id="mobileRadioPlayButton"><i class="fa fa-play hover-box-info" title="Play Radio" aria-hidden="true"></i></span>
                      </div>
                        @include('menu-stuff.profile-top', ['radio_muted' => $radio_muted])

                    </div>

                </div>
            </div>
        </nav>
        <!-- Header with blue background, Hide this on mobile -->

<!-- Header with blue background, Hide this on mobile -->
<header id="header">
    <div class="wrapper">
        <a href="/forum" class="logo web-page gradualfader"></a>
    </div>
</header>
        <div id="splitter"></div>
        <div class="mobile">
<div class="wrapper">

<div class="radioInfo4">

        <div style="position: absolute;margin: -26px 0px 0px 1px;overflow: hidden; clip: rect(0px, 60px, 70px, 0px);">
            <span id="djhabbo"></span>
        </div>
        <div class="djInfo ellipsis">
            <b>
                <span id="dj_name" style="font-size: 12px;">Loading...</span><br />
                <marquee style="margin: 5px 0px 0px 0px;"><span><span id="djsaysname">Loading</span>: <span id="djsaysmessage">Loading this message.</span></span></marquee>
            </b>
        </div>

</div>

    <div class="radioPlayer">
        <div id="albumArt" class="djInfo2 radioInfo1">
            <div class="radio-picture-muted" @if(!$radio_muted) style="display:none;" @endif ><span class="radio-text-muted"><i id="radioPlayButton" title="Play Radio" class="fa fa-play-circle gradualfader hover-box-info"></i></span></div>
        </div>
        <div class="djInfo2 radioInfo2">
            <center>
                <div style="width: 267px;position: absolute;margin-top: 11px;">
                    <div style="height: 30px; overflow: hidden; margin-bottom: 2px;">
                         <span id="dj_song">Loading...</span>
                    </div>
                    <div id="radio-stats">
                        <div style="">
                            <i class="fa fa-headphones radioSizes" aria-hidden="true"></i><span id="dj_listeners" style="font-weight: bold;">0</span> listeners | <i class="fa fa-heart radioSizes" aria-hidden="true"></i><span id="dj_loves" style="font-weight: bold;">0</span> likes
                        </div>
                        <div style="">
                            Next DJ: <span id="dj_next_on_air" style="font-weight: bold;">Loading</span>
                        </div>
                    </div>
                </div>
            </center>
            <div class="djInfo2 radioInfo3">
                <i class="fa fa-pause hover-box-info radioSizes" title="Pause Radio" id="radioMuteButton" aria-hidden="true"></i>
                <i class="fa fa-heart hover-box-info radioSizes" title="Like DJ" id="radioLikeButton" aria-hidden="true"></i>
                <a href="/requests" class="web-page"><i class="fa fa-commenting hover-box-info radioSizes" title="Request Line" style="color:#ffffff;" aria-hidden="true" title=""></i></a>
                <a href="/jobs" class="web-page"><i class="fa fa-users hover-box-info radioSizes" title="Job Applications" style="color:#ffffff;" aria-hidden="true" title=""></i></a>
                <div class="radioSlider">
                <div id="audioSlider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
                    <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="left: 24%;"></div>
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 24%;"></a>
                </div>
            </div>
        </div>
        </div>

    </div>

              <div class="quest_articles_to_update">
                    <div id="latest_quest_id_holder" style="display: none;">{{ $latest_id }}</div>
                    <div class="small-4 column end">
                      <div class="inner-content-holder">
                        <div class="advertsfront gradualfader eventstimes">
                        <div class="events-thumbnail" style="background-image: url({{ $currentEvent['image'] }});">

                            <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $currentEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">

                            <div class="text-events-left ct-center line">

                                <span class="event-name"><b>Now:</b></span>
                                <b><span>{{ $currentEvent['event'] }}</span></b>
                                <p>{{ $currenttime }}:00 - {{ $currentEvent['name'] }}</p>
                            </div>
                        </div>
                        <div class="events-thumbnail" style="background-image: url({{ $nextEvent['image'] }});">

                          <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $nextEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">

                        <div class="text-events-left ct-center line">
                            <span class="event-name"><b>Next:</b></span>
                            <b><span>{{ $nextEvent['event'] }}</span></b>
                            <p>{{ $nextTime }}:00 - {{ $nextEvent['name'] }}</p>
                        </div>

                    </div>
                    <div class="events-thumbnail" style="background-image: url({{ $laterEvent['image'] }});">

                      <img class="event-habbo" src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $laterEvent['name'] }}&direction=4&head_direction=3&action=wav&gesture=sml&size=m" alt="">

                      <div class="text-events-left ct-center line">
                          <span class="event-name"><b>Later:</b></span>
                          <b><span>{{ $laterEvent['event'] }}</span></b>
                          <p>{{ $laterTime }}:00 - {{ $laterEvent['name'] }}</p>
                      </div>

                  </div>
                        </div>
                      </div>
                    </div>
                        <div class="small-4 column end">
                          <div class="inner-content-holder" style="height:114px;">
                            <div id="top_quest_article">
                                <div class="advertsfront gradualfader" style="background-image: url({{ $latest_article['image'] }}), url('/_assets/img/thumbnails/1010.gif');" title="" onclick="urlRoute.loadPage('/article/{{ $latest_article['articleid'] }}');">
                                    <div class="article-tags">
                                        @if($latest_article['available'] == 1)
                                            <span class="green-tag">Available</span>
                                        @elseif($latest_article['available'] == 2)
                                            <span class="red-tag">Unavailable</span>
                                        @else
                                            <span class="red-tag">Unavailable</span>
                                        @endif
                                        @if($latest_article['type'] === 0)
                                            @if($latest_article['difficulty'] === 0)
                                                <div class="yellow-tag">Easy</div>
                                            @endif
                                            @if($latest_article['difficulty'] === 1)
                                                <div class="pink-tag">Medium</div>
                                            @endif
                                            @if($latest_article['difficulty'] === 2)
                                                <div class="red-tag">Hard</div>
                                            @endif
                                            @if($latest_article['paid'] === 0)
                                                <div class="blue-tag">Free</div>
                                            @endif
                                            @if($latest_article['paid'] === 1)
                                                <div class="red-tag">Paid</div>
                                            @endif
                                        @endif
                                    </div>
                                    @if($latest_article['badge_code'] != '')
                                        <div class="badgebackground">
                                            @if($latest_article['completed'])<img src="{{ asset('_assets/img/website/bcompleted.png') }}" style="position:absolute; z-index:100; width: 40px; height: 40px;"></img>@endif
                                            <img src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $latest_article['badge_code'] }}.gif" @if($latest_article['available'] === 2)style="opacity: 0.5;"@endif />
                                        </div>
                                    @endif
                                    <div class="text">
                                        <a href="/article/{{ $latest_article['articleid'] }}" class="web-page red_link"><b>{{ $latest_article['title'] }}</a></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="innercontainer" style="margin-top: 180px !important;">
                <div id="site_notices">
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="container2">
                <div class="row" id="mainContent"></div>
            </div>
        </div>

        <!-- FOOTER -->
        <div id="footer">
            <div class="wrapper">
                <div class="footercontent">
                    <div id="footerheader">
                        <span class="fa fa-user"></span>
                        <b>Active Users:</b> {{ $online_users }} users and {{ $online_guests }} guests ({{ $online_users_today }} registered users in the last 24 hours)
                    </div>
                    <div id="actives">
                        @foreach($active_users as $active)
                            <a href="/profile/{{ $active['username'] }}/page/1" class="web-page">
                                <div class="footeractiveusers hover-box-info" style="background-image: url('{{ $active['avatar'] }}');" title="<center><b>{{ $active['username'] }}<br />Last Active:</b> {{ $active['lastactivity'] }}"></div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="footercontent footerwidth">
                    <div id="footerheader">
                        <span class="fa fa-user"></span>
                        Member of the Month</i>
                    </div>
                    <div class="footerstaffweek hover-box-info" style="background-image: url('{{ $motm[0]['avatar'] }}');" title="{{ $motm[0]['clean_username'] }}"></div>
                    <div id="staffWinner">
                        <a href="/profile/{{ $motm[0]['clean_username'] }}/page/1" class="web-page">{!! $motm[0]['username'] !!}</a>
                    </div>
                    <div id="staffDesc">
                        {{ $motm[0]['comment'] }}
                    </div>
                    <div class="footerstaffweek hover-box-info" style="background-image: url('{{ $motm[1]['avatar'] }}');" title="{{ $motm[1]['clean_username'] }}"></div>
                    <div id="staffWinner">
                        <a href="/profile/{{ $motm[1]['clean_username'] }}/page/1" class="web-page">{!! $motm[1]['username'] !!}</a>
                    </div>
                    <div id="staffDesc">
                        {{ $motm[1]['comment'] }}
                    </div>
                </div>

                <div class="footercontent footerwidth">
                    <div id="footerheader">
                        <span class="fa fa-camera"></span>
                        Photo Competition</i>
                    </div>

                    <div class="footerstaffweek hover-box-info" style="background-image: url('{{ $pcmw[0]['avatar'] }}');" title="{{ $pcmw[0]['clean_username'] }}"></div>
                    <div id="staffWinner">
                        <a href="/profile/{{ $pcmw[0]['clean_username'] }}/page/1" class="web-page">{!! $pcmw[0]['username'] !!}</a>
                    </div>
                    <div id="staffDesc">
                        {{ $pcmw[0]['comment'] }}
                    </div>

                    <div class="footerstaffweek hover-box-info" style="background-image: url('{{ $pcmw[1]['avatar'] }}');" title="{{ $pcmw[1]['clean_username'] }}"></div>
                    <div id="staffWinner">
                        <a href="/profile/{{ $pcmw[1]['clean_username'] }}/page/1" class="web-page">{!! $pcmw[1]['username'] !!}</a>
                    </div>
                    <div id="staffDesc">
                        {{ $pcmw[1]['comment'] }}
                    </div>
                </div>
            </div>
        </div>
        <div id="footerLinks">
            <div class="wrapper">
                <div id="disclaimer">
                    © ThisHabbo 2010 - 2018
                </div>
                <div id="links">
                    <div id="footerlink">
                        <a href="/contact" class="web-page footer_link"><i class="fa fa-envelope" aria-hidden="true"></i> Contact Us
                    </a>
                    </div>
                    <div id="footerlink">
                        <a href="/rules" class="web-page footer_link"><i class="fa fa-file-text" aria-hidden="true"></i> ToS</a>
                    </div>
                    <div id="footerlink">
                        <a href="/rules" class="web-page footer_link"><i class="fa fa-file-text" aria-hidden="true"></i> Privacy Policy</a>
                    </div>
                    <div id="footerlink">
                        <a href="https://twitter.com/ThisHabbo" target="_blank" class="footer_link"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a>
                    </div>
                    <div id="footerlink">
                        <a href="https://www.habbo.com/room/50632900" target="_blank" class="footer_link"><i class="fa fa-h-square" aria-hidden="true"></i> TH Hub</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FOOTER -->
        {{ Html::script('_assets/js/tipped.js') }}
        {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/slideout/1.0.1/slideout.min.js') }}
        {{ Html::script('_assets/js/urlRouting.js?v5') }}
        {{ Html::script('_assets/js/app.js') }}
        {{ Html::script('_assets/js/notifications.js') }}
        <script type="text/javascript">
        urlRoute.setBaseUrl('{{ url('/')}}');
        @if($homePage != '')
            urlRoute.checkCurrent('https://{!! $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] !!}', '{{ $homePage }}');
        @else
            urlRoute.checkCurrent('https://{!! $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] !!}');
        @endif
        var toggleRadio = function(){
            console.log("Toggled");
            $('#albumArt2').toggle('fast');
            $('#toggle-button').toggleClass('fa-rotate-180');
        }
        $('.eventstimes').slick({
            arrows:false,
            dots:true
        });
        </script>
        {{ Html::script('_assets/js/radio.js?v2') }}
    </body>
</html>
