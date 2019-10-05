<div class="content-holder">
	    <div class="content">
			<div class="profile_visitor_from">
				<a href="/profile/{{ $clean_username }}/page/1" class="web-page">{!! $username !!}</a> - {!! $time !!}
			</div>

				@if($can_delete_vm)
				<div class="pullrightpls positionmod">
					<input type="checkbox" class="vm_checkbox" style="margin: 0;" value="{{ $vmid }}"/>
				</div>
				@endif

			<div class="profile_visitor_text">
				{!! $message !!}
			</div>
			<div style="position: inherit;float: right;margin:33px 0px 0px 0px;">
				@if(isset($show_link))
					<a href="/conversation/{{ $clean_username }}/{{ $username2 }}/page/1" class="web-page">View Conversation</a>
				@endif
				@if(isset($can_report_post))
					@if(Auth::check() && ($userid != Auth::user()->userid))
						<i class="fa fa-bullhorn" aria-hidden="true" onclick="reportVm({{ $vmid }});"></i>
					@endif
				@endif
				@if(isset($can_infract_vm))
					@if(Auth::check() && ($userid != Auth::user()->userid))
						<i class="fa fa-bell" aria-hidden="true" onclick="infWarn({{ $vmid }});"></i>
					@endif
				@endif
			</div>
		</div>
</div>
