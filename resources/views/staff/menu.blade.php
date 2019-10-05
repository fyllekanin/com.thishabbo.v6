<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<?php
    $amountOfCreations = \App\Helpers\StaffHelper::creations();
    $amountOfFlagged = \App\Helpers\StaffHelper::flaggedArticles();
?>

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 1))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            All Staff
        </div>
        <div class="menu-block">
            <a href="/staff" class="web-page"><i class="fa fa-home" aria-hidden="true"></i> Staff Home</a>
        </div>
        <div class="menu-block">
            <a href="/forum/category/1/page/1" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> Staff Forums</a>
        </div>
        <div class="menu-block">
            <a href="/staff/region" class="web-page"><i class="fa fa-cog" aria-hidden="true"></i> Change Displayed Region</a>
        </div>
        <div class="menu-block">
            <a href="/staff/request" class="web-page"><i class="fa fa-ticket" aria-hidden="true"></i> Request THC</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 2))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Radio
        </div>
        <div class="menu-block">
            <a href="/staff/radio/timetable" class="web-page"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Book Radio Slot</a>
        </div>
        <div class="menu-block">
            <a href="/staff/radio/connection" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> View Connection Information</a>
        </div>
        <div class="menu-block">
            <a href="/staff/radio/request/page/1" class="web-page"><i class="fa fa-commenting" aria-hidden="true"></i> View Requests</a>
        </div>
        <div class="menu-block">
            <a href="/staff/radio/djsays" class="web-page"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Set DJ Says</a>
        </div>
        <div class="menu-block">
            <a href="/staff/radio/live" class="web-page"><i class="fa fa-headphones" aria-hidden="true"></i> Live Radio Stats</a>
        </div>
        <div class="menu-block">
            <a href="http://jingle.house" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Radio Imaging</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 1048576))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Events
        </div>
        <div class="menu-block">
            <a href="/staff/event/timetable" class="web-page"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Book Event Slot</a>
        </div>
        <div class="menu-block">
            <a href="/forum/thread/506970/page/1" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> Ban On Sight</a>
        </div>
    </div>
</div>
@endif

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 128))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Quests
        </div>
        <div class="menu-block">
            <a href="/staff/media/article/addbadge" class="web-page"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Write Badge Guide</a>
        </div>
        <div class="menu-block">
            <a href="/staff/media/article/addbundle" class="web-page"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Write Bundle Guide</a>
        </div>
        <div class="menu-block">
            <a href="/staff/media/article/addrare" class="web-page"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Write Rare Guide</a>
        </div>
        <div class="menu-block">
            <a href="/staff/media/article/add" class="web-page"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Write Other Habbo Article</a>
        </div>
        <div class="menu-block">
            <a href="/staff/media/articles/page/1" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> Manage Articles</a>
        </div>
        @if($UserHelper::haveStaffPerm(Auth::user()->userid, 16384))
        <div class="menu-block">
            <a href="/staff/media/flagged/articles" class="web-page">
                <i class="fa fa-flag" aria-hidden="true"></i> Flagged Articles
                @if($amountOfFlagged > 0) ({{ $amountOfFlagged }}) @endif
            </a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 512))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlue">
            Graphics
        </div>
        <div class="menu-block">
            <a href="/staff/graphic/gallery/page/1" class="web-page"><i class="fa fa-list" aria-hidden="true"></i> Gallery</a>
        </div>
        @if($UserHelper::haveStaffPerm(Auth::user()->userid, 1024))
        <div class="menu-block">
            <a href="/staff/graphic/upload" class="web-page"><i class="fa fa-upload" aria-hidden="true"></i> Upload Image</a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveGeneralModPerm(Auth::user()->userid, 1))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerBlack">
            Moderator
        </div>
        <div class="menu-block">
            <a href="/staff/mod/infractions/page/1" class="black_link web-page"><i class="fa fa-list" aria-hidden="true"></i> Moderator Infractions</a>
        </div>
        <div class="menu-block">
            <a href="/staff/mod/users/all/page/1" class="black_link web-page"><i class="fa fa-list" aria-hidden="true"></i> User List</a>
        </div>
        <div class="menu-block">
            <a href="/staff/mod/users/banned" class="black_link web-page"><i class="fa fa-lock" aria-hidden="true"></i> Banned Users</a>
        </div>
        @if($UserHelper::haveGeneralModPerm(Auth::user()->userid, 128))
        <div class="menu-block">
            <a href="/staff/mod/creations" class="black_link web-page"><i class="fa fa-picture-o" aria-hidden="true"></i> Creations @if($amountOfCreations > 0) ({{ $amountOfCreations }}) @endif</a>
        </div>
        @endif
    </div>
</div>
@endif

@if($UserHelper::haveStaffPerm(Auth::user()->userid, 32))
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerGreen">
            Management
        </div>
        <div class="menu-block">
            <a href="/forum/thread/506318/page/1" class="green_link web-page"><i class="fa fa-commenting" aria-hidden="true"></i> View Do Not Hire List</a>
        </div>
        <div class="menu-block">
            <a href="/forum/category/1016/page/1" class="green_link web-page"><i class="fa fa-commenting" aria-hidden="true"></i> View Job Applications</a>
        </div>
        <div class="menu-block">
            <a href="/forum/thread/551914/page/1" class="green_link web-page"><i class="fa fa-commenting" aria-hidden="true"></i> Request Permissions</a>
        </div>
        <div class="menu-block">
            <a href="/staff/logs/events/page/1" class="green_link web-page"><i class="fa fa-book" aria-hidden="true"></i> Event Booking Log</a>
        </div>
        <div class="menu-block">
            <a href="/staff/logs/radio/page/1" class="green_link web-page"><i class="fa fa-book" aria-hidden="true"></i> Radio Booking Log</a>
        </div>
        @if($UserHelper::haveStaffPerm(Auth::user()->userid, 64))
        <div class="menu-block">
            <a href="/staff/perm/manage" class="green_link web-page"><i class="fa fa-list" aria-hidden="true"></i> Manage Perm Show</a>
        </div>
        @endif
        @if($UserHelper::haveStaffPerm(Auth::user()->userid, 524288))
        <div class="menu-block">
            <a href="/staff/events/manage" class="green_link web-page"><i class="fa fa-tags" aria-hidden="true"></i> Manage Events Type</a>
        </div>
        @endif
        @if($UserHelper::haveStaffPerm(Auth::user()->userid, 8192))
        <div class="menu-block">
            <a href="/staff/manage/kick" class="green_link web-page"><i class="fa fa-gavel" aria-hidden="true"></i> Kick DJ</a>
        </div>
        <div class="menu-block">
            <a href="/staff/manage/trialradio" class="green_link web-page"><i class="fa fa-play" aria-hidden="true"></i> Trial Radio</a>
        </div>
        <div class="menu-block">
            <a href="/staff/manage/radio" class="green_link web-page"><i class="fa fa-list" aria-hidden="true"></i> Manage Radio Information</a>
        </div>
        <div class="menu-block">
            <a href="/staff/manage/analytics" class="green_link web-page"><i class="fa fa-line-chart" aria-hidden="true"></i> Radio Analytics</a>
        </div>
        @endif
    </div>
</div>
@endif
