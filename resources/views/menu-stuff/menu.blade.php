<?php
$admincp = false;
$staff = false;
if(Auth::check()) {
    $admincp = \App\Helpers\UserHelper::haveAdminPerm(Auth::user()->userid, 1);
    $staff = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 1);
}
?>
<ul>
    <li><div id="hovernav" style="height: 49px;"><a @if(isset($homePage) AND $homePage != '') href="{{ $homePage }}"  @else href="/home" @endif style="margin: 0px 0px 0px -2px;" id="home_page_button" class="web-page bold"><i class="fa fa-home" aria-hidden="true" style="font-size: 27px;"></i> @if($mobile == 1) Home @endif</a></div></li>

    <li><div id="hovernav"><a href="#" class="hoverwhite sub-menu bold"> <i class="fa fa-user" aria-hidden="true"></i> <span>ThisHabbo</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a> </div>
        <ul>
            <li><a href="/home" class="web-page">Home</a></li>
            <div class="divider"></div>
            <li><a href="/about" class="web-page">ThisHabbo History</a></li>
            <li><a href="/staff/list" class="web-page">Staff Members</a></li>
            <div class="divider"></div>
            <li><a href="/creations/page/1" class="web-page">Creations</a></li>
            <li><a href="/goodies/habbo/imager" class="web-page">Habbo Imager</a></li>
            <li><a href="/goodies/alterations" class="web-page">Alteration Generator</a></li>
            <li><a href="/goodies/kissing" class="web-page">Kissing Generator</a></li>
            <div class="divider"></div>
            <li><a href="/goodies/badge/scanner" class="web-page">Badge Scanner</a></li>
            <li><a href="/goodies/top25/badge/collectors" class="web-page">Top 25 Collectors</a></li>
            <li><a href="/leaderboard" class="web-page">Leaderboard</a></li>
            <div class="divider"></div>
            <li><a href="/contact" class="web-page">Contact Us</a></li>
        </ul>
    </li>

    <li><div id="hovernav"><a href="#" class="sub-menu bold"><i class="fa fa-book" aria-hidden="true"></i> <span>Quests</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>
        <ul>
            <li><a href="/badges" class="web-page">Badges</a></li>
            <li><a href="/articles/0/page/1" class="web-page">Badge Guides</a></li>
            <li><a href="/articles/2/page/1" class="web-page">Wired Guides</a></li>
            <div class="divider"></div>
            <li><a href="/jobs" class="web-page">Apply</a></li>
        </ul>
    </li>
    <li><div id="hovernav"><a href="#" class="sub-menu bold"><i class="fa fa-book" aria-hidden="true"></i> <span>Media</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>
        <ul>
            <li><a href="/forum/category/620/page/1" class="web-page">Around The World</a></li>
            <li><a href="/forum/category/65/page/1" class="web-page">Latest Gossip</a></li>
            <li><a href="/forum/category/67/page/1" class="web-page">Columns Corner</a></li>
            <li><a href="/forum/category/71/page/1" class="web-page">In The Community</a></li>
            <li><a href="/forum/category/673/page/1" class="web-page">Debates Corner</a></li>
            <div class="divider"></div>
            <li><a href="/jobs" class="web-page">Apply</a></li>
        </ul>
    </li>

    <li><div id="hovernav"><a href="#" class="sub-menu bold"><i class="fa fa-users" aria-hidden="true"></i> <span>Community</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>
        <ul>
            <li><a href="/events" class="web-page">Events Timetable</a></li>
            <li><a href="/timetable" class="web-page">Radio Timetable</a></li>
            <div class="divider"></div>
            <li><a href="/jobs" class="web-page">Apply</a></li>
        </ul>
    </li>

    <li><div id="hovernav"><a href="/betting" class="web-page bold"><i class="fa fa-ticket" aria-hidden="true"></i> Betting</a></div></li>

    <li><div id="hovernav"><a href="/clans" class="web-page bold"><i class="fa fa-shield" aria-hidden="true"></i> Clans</a></div></li>

    <li><div id="hovernav"><a href="/forum" class="web-page bold"><i class="fa fa-commenting" aria-hidden="true"></i> Forum</a></div></li>

    <li><div id="hovernav"><a href="/megarate" class="web-page bold"><i class="fa fa-question-circle-o" aria-hidden="true"></i> MegaRate</a></div></li>

    <li><div id="hovernav"><a href="/jobs" class="web-page sub-menu bold"><i class="fa fa-bookmark" aria-hidden="true"></i> <span>Jobs</span> <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>
        <ul>
            <li><a href="/about" class="web-page">Get to know us</a></li>
            <li><a href="/jobs" class="web-page">Apply</a></li>
        </ul>
    </li>
</ul>
