<?php $UserHelper = new \App\Helpers\UserHelper; ?>

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 16) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 8388608) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 16777216) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 33554432) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 67108864) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 131072) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 134217728) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 268435456) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 1) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 536870912))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Website Settings
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 0))
        <div class="menu-block">
            <a href="/admincp/notices/list" class="web-page"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Manage Site Notices</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 16))
        <div class="menu-block">
            <a href="/admincp/settings/bbcodes" class="web-page"><i class="fa fa-code" aria-hidden="true"></i> Manage BBCodes</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 8589934592))
        <div class="menu-block">
            <a href="/admincp/carousel" class="web-page"><i class="fa fa-picture-o" aria-hidden="true"></i> Manage Carousel</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 8388608))
        <div class="menu-block">
            <a href="/admincp/settings/automated" class="web-page"><i class="fa fa-magic" aria-hidden="true"></i> Manage Automated Threads</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 16777216))
        <div class="menu-block">
            <a href="/admincp/settings/staff/list" class="web-page"><i class="fa fa-users" aria-hidden="true"></i> Manage Staff List</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 33554432))
        <div class="menu-block">
            <a href="/admincp/settings/maintenances" class="web-page"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Website Maintenance Mode</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 67108864))
        <div class="menu-block">
            <a href="/admincp/dailyquest" class="web-page"><i class="fa fa-tasks" aria-hidden="true"></i> Manage Daily Quests</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 131072))
        <div class="menu-block">
            <a href="/admincp/site/rules" class="web-page"><i class="fa fa-question-circle" aria-hidden="true"></i> Site Rules</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 134217728))
        <div class="menu-block">
            <a href="/admincp/site/partners" class="web-page"><i class="fa fa-user" aria-hidden="true"></i> Link Partners</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 34359738368))
        <div class="menu-block">
            <a href="/admincp/xplevels" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> Manage XP Levels</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 268435456))
        <div class="menu-block">
            <a href="/admincp/site/sotw" class="web-page"><i class="fa fa-trophy" aria-hidden="true"></i> Change Staff of the Week</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/site/motm" class="web-page"><i class="fa fa-trophy" aria-hidden="true"></i> Change Member of the Month</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/site/photo" class="web-page"><i class="fa fa-camera-retro" aria-hidden="true"></i> Change Photo Comp Monthly Winner</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 1))
        <div class="menu-block">
            <a href="/admincp/badges/manage/page/1" class="web-page"><i class="fa fa-certificate" aria-hidden="true"></i> Manage Badges</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 536870912))
        <div class="menu-block">
            <a href="/admincp/twitter/twitterUserTimeLine" class="web-page"><i class="fa fa-twitter-square" aria-hidden="true"></i> Make a Tweet</a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 8192))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Shop Settings
        </div>
        <div class="menu-block">
            <a href="/admincp/list/stickers" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Stickers</a>
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 65536))
        <div class="menu-block">
            <a href="/admincp/list/nameicons" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage  Name Icons</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 16384))
        <div class="menu-block">
            <a href="/admincp/list/nameeffects" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Name Effects</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 256))
        <div class="menu-block">
            <a href="/admincp/list/subscriptions" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Subscriptions</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4194304))
        <div class="menu-block">
            <a href="/admincp/list/themes" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Themes</a>
        </div>
        @endif
        <div class="menu-block">
            <a href="/admincp/list/backgrounds" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Backgrounds</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/list/boxes" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Mystery Boxes</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/box" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Mystery Box Contents</a>
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 32768))
        <div class="menu-block">
            <a href="/admincp/manage/voucher" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Manage Voucher Codes</a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 2))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Forum Management
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 17179869184))
        <div class="menu-block">
            <a href="/admincp/bot" class="web-page"><i class="fa fa-commenting" aria-hidden="true"></i> Welcome Bot</a>
        </div>
        @endif
        <div class="menu-block">
            <a href="/admincp/settings/modforum" class="web-page"><i class="fa fa-commenting" aria-hidden="true"></i> Moderation Forum</a>
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 2048))
        <div class="menu-block">
            <a href="/admincp/prefixes" class="web-page"><i class="fa fa-thumb-tack" aria-hidden="true"></i> Manage Prefixes</a>
        </div>
        @endif
        <div class="menu-block">
            <a href="/admincp/forums" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> List Forums</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/forums/add" class="web-page"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Add Forum</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/search/threads" class="web-page"><i class="fa fa-search" aria-hidden="true"></i> Search Threads</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 8))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlack">
            Usergroup Management
        </div>
        <div class="menu-block">
            <a href="/admincp/usergroups" class="web-page black_link"><i class="fa fa-list" aria-hidden="true"></i> List Usergroups</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/usergroups/add" class="web-page black_link"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Add Usergroups</a>
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 512))
        <div class="menu-block">
            <a href="/admincp/default/forum/perms" class="web-page black_link"><i class="fa fa-users" aria-hidden="true"></i> Default Forum Permissions</a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 4))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerRed">
            User Management
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
        <div class="menu-block">
            <a href="/admincp/points/issue" class="web-page red_link"><i class="fa fa-ticket" aria-hidden="true"></i> Add THC to User</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/points/requests" class="web-page red_link"><i class="fa fa-ticket" aria-hidden="true"></i> THC Requests</a>
        </div>
        @endif
        <div class="menu-block">
            <a href="/admincp/users/all/page/1" class="web-page red_link"><i class="fa fa-list" aria-hidden="true"></i> List Users</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/users/search" class="web-page red_link"><i class="fa fa-search" aria-hidden="true"></i> Search Users</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/users/banned" class="web-page red_link"><i class="fa fa-lock" aria-hidden="true"></i> View Banned Users</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 68719476736))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerPink">
            Betting Hub
        </div>
        <div class="menu-block">
            <a href="/admincp/bets/create" class="web-page green_link"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Create Bet</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/bets/page/1" class="web-page green_link"><i class="fa fa-list" aria-hidden="true"></i> List Bets</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/bets/logs/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> Betting Logs</a>
        </div>
    </div>
</div>

<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerPink">
            Clanning
        </div>
        <div class="menu-block">
            <a href="/admincp/clans/page/1" class="web-page green_link"><i class="fa fa-list" aria-hidden="true"></i> List Clans</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 1073741824) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 1073741824) OR $UserHelper::haveAdminPerm(Auth::user()->userid, 2147483648))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerGreen">
            Logs & Statistics
        </div>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 2147483648))
        <div class="menu-block">
            <a href="/admincp/statistics" class="web-page green_link"><i class="fa fa-list" aria-hidden="true"></i> Statistics</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 1073741824))
        <div class="menu-block">
            <a href="/admincp/adminlog/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> Admin Log</a>
        </div>
        @endif
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 1073741824))
        <div class="menu-block">
            <a href="/admincp/modlog/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> Mod Log</a>
        </div>
        @endif
        <div class="menu-block">
            <a href="/admincp/postingfest" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> Posting Fest Log</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/radiodetailslog/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> Radio Info Log</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/pointslogs/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> THC Issuing Log</a>
        </div>
        <div class="menu-block">
            <a href="/admincp/voucherlog/page/1" class="web-page green_link"><i class="fa fa-book" aria-hidden="true"></i> THC Voucher Log</a>
        </div>
    </div>
</div>
@endif