<script> urlRoute.setTitle("TH - Staff List");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Staff Members</span>
    </div>
  </div>
</div>

<div class="small-12 column">
	<div class="staff_groups">
		@foreach($grps as $grp)
			{!! $grp !!}
		@endforeach
	</div>
</div>