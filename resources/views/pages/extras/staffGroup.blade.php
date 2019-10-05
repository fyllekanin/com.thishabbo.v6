<div class="content-holder">
	<div class="content">
		<div class="contentHeader {{ $color }}">
			<span>{{ $title }}</span>
		</div>
		<div class="content-ct staff-list">
			@foreach($users as $user)
				<div class="small-12 medium-4 large-3 end column">
					<div class="user_holder">
						<div class="user_holder_avatar" style="background-image: url('{{ $user['avatar'] }}');"></div>
						<div class="user_holder_info">
							<div class="user_holder_username"><a href="/profile/{{ $user['clean_username'] }}/page/1" class="web-page">{!! $user['username'] !!}</a> <br />
							@if(strlen($user['role'])>0)<i>{{ $user['role'] }}</i><br>@endif</div>
							<div class="user_holder_socials">
								<i class="fa fa-globe" aria-hidden="true"></i> {{ $user['country'] }} <br />
								@if(strlen($user['habbo']) > 0)<i class="fa fa-h-square" aria-hidden="true"></i> {{ $user['habbo'] }} <br />@endif
							</div>
							@if($can_see_slots)
							<div class="user_holder_slots">
								<i class="fa fa-gamepad" aria-hidden="true"></i> {{ number_format($user['eventSlots']) }}<br />
								<i class="fa fa-microphone" aria-hidden="true"></i> {{ number_format($user['radioSlots']) }}
							</div>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
