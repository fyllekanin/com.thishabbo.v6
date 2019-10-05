<script> urlRoute.setTitle("TH - Leaderboard");</script>

<div class="small-12 mobileFunction column">
    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerBlue">
                    Top Threads
                </div>
                <div class="content-ct ct-center">
                        @foreach($top_threads as $top_thread)
                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_thread['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_thread['avatar'] }}');" title="{{ $top_thread['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_thread['clean_username'] }}/page/1" class="web-page">{!! $top_thread['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Threads <span class="forum-activity-time">{{ $top_thread['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerPink">
                    Top Posters
                </div>
                <div class="content-ct ct-center">
                        @foreach($top_posters as $top_poster)
                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_poster['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_poster['avatar'] }}');" title="{{ $top_poster['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_poster['clean_username'] }}/page/1" class="web-page">{!! $top_poster['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Posts <span class="forum-activity-time">{{ $top_poster['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerRed">
                    Most Liked
                </div>
                <div class="content-ct ct-center">
                        @foreach($top_forumloves as $top_forumlove)

                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_forumlove['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_forumlove['avatar'] }}');" title="{{ $top_forumlove['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_forumlove['clean_username'] }}/page/1" class="web-page">{!! $top_forumlove['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Likes <span class="forum-activity-time">{{ $top_forumlove['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="small-12 mobileFunction column">
    <div class="small-4 mobileFunction column end">
            <div class="content-holder">
                <div class="content">
                <div class="contentHeader headerBlue">
                        Top Creation Contributors
                    </div>
                    <div class="content-ct ct-center">
                            @foreach($top_creatives as $top_creative)
                                    <div class="small-12 column">
                                        <div class="small-3 column">
                                            <a href="/profile/{{ $top_creative['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_creative['avatar'] }}');" title="{{ $top_creative['clean_username'] }}"></div></a>
                                        </div>
                                        <div class="small-8 column" style="text-align: left;">
                                            <a href="/profile/{{ $top_creative['clean_username'] }}/page/1" class="web-page">{!! $top_creative['username'] !!}</a> <br />
                                            <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                                Uploads <span class="forum-activity-time">{{ $top_creative['amount'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                    </div>
                </div>
            </div>
    </div>

    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerPink">
                    Most Liked DJS
                </div>
                <div class="content-ct ct-center">
                        @foreach($top_djlikes as $top_djliked)
                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_djliked['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_djliked['avatar'] }}');" title="{{ $top_djliked['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_djliked['clean_username'] }}/page/1" class="web-page">{!! $top_djliked['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Likes <span class="forum-activity-time">{{ $top_djliked['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerRed">
                    Top TH Badge Collectors
                </div>
                <div class="content-ct ct-center">
                        @foreach($top_collectors as $top_collector)
                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_collector['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_collector['avatar'] }}');" title="{{ $top_collector['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_collector['clean_username'] }}/page/1" class="web-page">{!! $top_collector['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Badges <span class="forum-activity-time">{{ $top_collector['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="small-12 mobileFunction column">
    <div class="small-4 mobileFunction column end">
        <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerBlack">
                    Most Followed
                </div>
                <div class="content-ct ct-center">
                    <div class="row">
                        @foreach($top_followed as $top_follow)
                            <div class="row">
                                <div class="small-12 column">
                                    <div class="small-3 column">
                                        <a href="/profile/{{ $top_follow['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $top_follow['avatar'] }}');" title="{{ $top_follow['clean_username'] }}"></div></a>
                                    </div>
                                    <div class="small-8 column" style="text-align: left;">
                                        <a href="/profile/{{ $top_follow['clean_username'] }}/page/1" class="web-page">{!! $top_follow['username'] !!}</a> <br />
                                        <div class="forum-activity-info" style="padding-top: 0.25rem;">
                                            Followers <span class="forum-activity-time">{{ $top_follow['amount'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="small-4 mobileFunction column end"></div>
    <div class="small-4 mobileFunction column end"></div>
</div>
