<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('latestPosters', 'lp');">
                    Toggle
                </button>
                @endif
                <span>Latest Posts</span>
            </div>
            <div id="latestPosters" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('lp', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top15_lastest_posts as $top15_lastest_post)
                    <div class="top5stats">{{$nr}}. <a href="/forum/thread/{{ $top15_lastest_post['threadid'] }}/page/{{ $top15_lastest_post['page'] }}" class="web-page hover-box-info" title="{{ $top15_lastest_post['fulltitle'] }}">{!! $top15_lastest_post['title'] !!}</a> <div style="float: right;"><a href="/profile/{{ $top15_lastest_post['clean_username'] }}" class="web-page">{!! $top15_lastest_post['username'] !!}</a></div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('trendingBets', 'tb');">
                    Toggle
                </button>
                @endif
                <span>Trending Bets</span>
            </div>
            <div id="trendingBets" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('tb', Auth::user()->userid) == true) style="display: none" @endif @endif>
            <?php $nr = 1; ?>
            @foreach($trending_bets as $trending_bet)
            <div class="top5stats">{{$nr}}. <a href="/betting" class="web-page hover-box-info" title="{{ $trending_bet['bet'] }}">{!! $trending_bet['shortbet'] !!}</a> <div style="float: right;"> {!! $trending_bet['odds'] !!} </div></div>
            <?php $nr++; ?>
            @endforeach
        </div>
    </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlack">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('topPostersToday', 'tpt');">
                    Toggle
                </button>
                @endif
                <span>Top Posters Today</span>
            </div>
            <div id="topPostersToday" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('tpt', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top15_posters_today as $top15_poster_today)
                    <div class="top5stats">{{$nr}}. <a href="/profile/{{ $top15_poster_today['clean_username'] }}" class="web-page">{!! $top15_poster_today['username'] !!}</a> <div style="float: right;">({{$top15_poster_today['posts']}})</div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>


    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('topLeveled', 'tl');">
                    Toggle
                </button>
                @endif
                <span>Top Leveled</span>
            </div>
            <div id="topLeveled" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('tl', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top15_xp as $top15_xps)
                    <div class="top5stats">{{$nr}}. <a href="/profile/{{ $top15_xps['clean_username'] }}" class="web-page">{!! $top15_xps['username'] !!}</a> <div style="float: right;">({{$top15_xps['level']}})</div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('topPosters', 'tp');">
                    Toggle
                </button>
                @endif
                <span>Top Posters</span>
            </div>
            <div id="topPosters" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('tp', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top15_posters as $top_poster)
                    <div class="top5stats">{{$nr}}. <a href="/profile/{{ $top_poster['clean_username'] }}" class="web-page">{!! $top_poster['username'] !!}</a> <div style="float: right;">({{$top_poster['posts']}})</div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('topKeyHolders', 'keys');">
                    Toggle
                </button>
                @endif
                <span>Top Key Holders</span>
            </div>
            <div id="topKeyHolders" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('keys', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top10_keys as $top10_key)
                    <div class="top5stats">{{$nr}}. <a href="/profile/{{ $top10_key['clean_username'] }}" class="web-page">{!! $top10_key['username'] !!}</a> <div style="float: right;">({{$top10_key['keys']}})</div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerGreen">
                @if (Auth::check())
                <button class="web-page headerLink white_link" onclick="toggleCategory('topClans', 'clans');">
                    Toggle
                </button>
                @endif
                <span>Top Clans</span>
            </div>
            <div id="topClans" @if (Auth::check()) @if($ForumHelper::isForumCollapsed('clans', Auth::user()->userid) == true) style="display: none" @endif @endif>
                <?php $nr = 1; ?>
                @foreach($top10_clans as $top10_clan)
                    <div class="top5stats">{{$nr}}. <a href="/clans/{{ $top10_clan['groupname'] }}" class="web-page">{{ $top10_clan['groupname'] }}</a> <div style="float: right;">({{$top10_clan['totalexp']}})</div></div>
                    <?php $nr++; ?>
                @endforeach
            </div>
        </div>
    </div>
