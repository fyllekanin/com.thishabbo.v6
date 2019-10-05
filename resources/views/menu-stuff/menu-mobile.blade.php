@if(Auth::check())
<?php
$admincp = \App\Helpers\UserHelper::haveAdminPerm(Auth::user()->userid, 1);
$staff = \App\Helpers\UserHelper::haveStaffPerm(Auth::user()->userid, 1);
?>
@endif
<br />
<a @if(isset($homePage) AND $homePage != '') href="{{ $homePage }}"  @else href="/home" @endif style="margin: 0px 0px 0px -2px;" id="home_page_button" class="web-page bold"><i class="fa fa-home" aria-hidden="true" style="font-size: 24px;"></i> Home</a><br />
<br />
@if(Auth::check())
<a href="/profile/{{ Auth::user()->username }}" class="web-page bold"><i class="fa fa-user" aria-hidden="true"></i> {{ Auth::user()->username }}'s Profile</a><br />
<a href="/usercp" class="web-page">UserCP</a><br />
<a href="/usercp/pm" class="web-page">Private Messages</a><br />
<br />
@if($staff || $admincp)
@if($admincp)
<a href="/admincp" class="web-page bold">AdminCP</a><br />
@endif
@if($staff)
<a href="/staff" class="web-page bold">StaffCP</a><br />
@endif
@endif
@endif
<br />
<a href="/forum" class="web-page bold"><i class="fa fa-commenting" aria-hidden="true"></i> Forum</a><br />
<a href="/megarate" class="web-page bold"><i class="fa fa-commenting" aria-hidden="true"></i> Megarate</a><br />
@if(Auth::check())
<a href="/usercp/shop" class="web-page bold"><i class="fa fa-ticket" aria-hidden="true"></i> Visit the Shop</a><br />
<a href="/betting" class="web-page bold"><i class="fa fa-ticket" aria-hidden="true"></i> Visit the Betting Hub</a><br />
@endif
<a href="/requests" class="web-page bold"><i class="fa fa-commenting" aria-hidden="true"></i> Request Line</a><br />
<br />
<a href="#" class="hoverwhite sub-menu bold"> <i class="fa fa-user" aria-hidden="true"></i>  <span>ThisHabbo</span></a><br />
<a href="/home" class="web-page">Home</a><br />
<a href="/about" class="web-page">ThisHabbo History</a><br />
<a href="/staff/list" class="web-page">Staff Members</a><br />
<a href="/creations/page/1" class="web-page">Creations</a><br />
<a href="/goodies/habbo/imager" class="web-page">Habbo Imager</a><br />
<a href="/goodies/alterations" class="web-page">Alteration Generator</a><br />
<a href="/goodies/kissing" class="web-page">Kissing Generator</a><br />
<a href="/goodies/badge/scanner" class="web-page">Badge Scanner</a><br />
<a href="/goodies/top25/badge/collectors" class="web-page">Top 25 Badge Collectors</a><br />
<a href="/leaderboard" class="web-page">Leaderboard</a><br />
<a href="/contact" class="web-page">Contact Us</a><br />
<br />

<a href="#" class="sub-menu bold"><i class="fa fa-book" aria-hidden="true"></i> <span>Quests</span></a><br />
<a href="/badges" class="web-page">Badges</a><br />
<a href="/articles/0/page/1" class="web-page">Badge Guides</a><br />
<a href="/articles/2/page/1" class="web-page">Wired Guides</a><br />
<a href="/jobs" class="web-page">Apply for a Job</a><br />
<br />

<a href="#" class="sub-menu bold"><i class="fa fa-book" aria-hidden="true"></i> Media</a><br />
<a href="/forum/category/620/page/1" class="web-page">Around The World</a><br />
<a href="/forum/category/65/page/1" class="web-page">Latest Gossip</a><br />
<a href="/forum/category/67/page/1" class="web-page">Columns Corner</a><br />
<a href="/forum/category/71/page/1" class="web-page">In The Community</a><br />
<a href="/forum/category/673/page/1" class="web-page">Debates Corner</a><br />
<a href="/jobs" class="web-page">Apply for a Job</a><br />
<br />

<a href="#" class="sub-menu bold"><i class="fa fa-users" aria-hidden="true"></i> <span>Community</span></a><br />
<a href="/events" class="web-page">Events Timetable</a><br />
<a href="/timetable" class="web-page">Radio Timetable</a><br />
<a href="/jobs" class="web-page">Apply for a Job</a><br />
<br />

<a href="/jobs" class="web-page bold"><i class="fa fa-bookmark" aria-hidden="true"></i> <span>Jobs</span></a><br />
<a href="/about" class="web-page">Get to know us</a><br />
<a href="/jobs" class="web-page">Apply for a Job</a><br />
<br />

@if(Auth::check())
<a onclick="signOut();" class="bold"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a><br />
<br />
@endif
&copy; ThisHabbo 2010-2018<br />
<div style="padding-bottom: 20px;"></div>
