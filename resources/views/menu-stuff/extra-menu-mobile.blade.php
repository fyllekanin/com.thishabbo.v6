@if(Auth::check())
<span>
	<div class="top-button globe-size" style="display: block;">
		<div class="new-notis new-notif new-tag-pc">New</div>
		<i class="fa fa-globe" aria-hidden="true" style="font-size: 2.2rem; color: #717171;" onclick="urlRoute.loadPage('/usercp/notifications');"></i>
	</div>
</span>
<span>
    <a href="/forum/newposts/page/1" class="sub-menu web-page bold"><i class="fa fa-users" aria-hidden="true" style="font-size: 2.2rem; color: #717171;" ></i></a>
</span>
<span>
	<a href="/search" class="sub-menu web-page bold"><i class="fa fa-search" aria-hidden="true" style="font-size: 2.2rem; color: #717171;"></i></a>
</span>
@endif
