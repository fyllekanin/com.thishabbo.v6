<script>urlRoute.setTitle("TH - Clan Leaderboard");</script>

	<div class="small-12 column">
	  	<div class="content-holder" style="position: relative;">
	    	<div class="content contentpadding">
	  			<span>
					<a href="/home" class="bold web-page">Forum Home</a>
					<i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<span>
						Clans
					</span>
				</span>
	    	</div>
	  	</div>
	</div>

	<div class="medium-8 column">
		<div class="content-holder">
	        <div class="content">
	        	<div class="contentHeader headerBlue">
                    ThisHabbo's Clans!
                </div>
	        	<table class="responsive" style="width: 100%;">
	        		<tbody>
		        		<tr>
		        			<th style="width: 25%;">Name</th>
		        			<th style="width: 25%;">Leader</th>
		        			<th style="width: 25%;">Members</th>
		        			<th style="width: 25%;">Total Experience</th>
	    	    		</tr>
						<?php $nr = 1; ?>
	    	    		@foreach($clans as $clan)
	    	    			<tr>
			        			<td>{{$nr}}. <a href="/clans/{{ $clan['groupname'] }}/" class="bold web-page">{{ $clan['groupname'] }}</a></td>
			        			<td><a href="/profile/{{ $clan['owner_clean'] }}/" class="bold web-page">{!! $clan['owner'] !!}</a></td>
			        			<td>
			        				@if($clan['member2_clean'] !== '__blank__') <a href="/profile/{{ $clan['member2_clean'] }}/" class="bold web-page">{!! $clan['member2'] !!}</a> @endif
			        				@if($clan['member2_clean'] !== '__blank__' && $clan['member3_clean'] !== '__blank__') & @endif
			        				@if($clan['member3_clean'] !== '__blank__') <a href="/profile/{{ $clan['member3_clean'] }}/" class="bold web-page">{!! $clan['member3'] !!}</a> @endif
			        			</td>
			        			<td>{{ $clan['totalExp'] }}</td>
		    	    		</tr>
							<?php $nr++; ?>
	    	    		@endforeach
	    	    	</tbody>
	        	</table>
	        </div>
	    </div>
	</div>

	<div class="medium-4 column">
		<div class="content-holder">
	        <div class="content">
	        	<div class="contentHeader headerRed">
                    What are clans?
                </div>
                <div class="content-ct">
                	<b>What are Clans?</b><br>
Clans are just a group of 3 people who will gain XP to be #1 on the forum. After every season, the best clan will win forum rewards.<br>
<br>
<b>How do I gain XP?</b><br>
XP is gained by 2 ways, the easiest way to gain XP is by posting. The second way of gaining XP is winning a Clan event. Events are very rare so will give out the most XP.<br>
<br>
<b>How many Clans can I join?</b><br>
You may only join a maximum of 3 Clans. <br>
<br>
<b>What if my team make 2 other clans and we have 3?</b><br>
Then we'd only count your Clan once... so it's a waste of time!<br /><br />
                	<a href="/usercp/clans/create" class="bold web-page">Create a clan!</a>
                </div>
            </div>
        </div>

        <div class="content-holder">
	        <div class="content">
	        	<div class="contentHeader headerPink">
                    Your Clans
                </div>
                <div class="small-12">
                	@foreach($clans as $clan)
                		@if($clan['owner_clean'] == Auth::user()->username || $clan['member2_clean'] == Auth::user()->username || $clan['member3_clean'] == Auth::user()->username)
				            <div class="menu-block">
				                <a href="/clans/{{ $clan['groupname'] }}/" class="web-page"><i class="fa fa-users"></i> {{ $clan['groupname'] }}</a>
				            </div>
			            @endif
			        @endforeach
		        </div>
            </div>
        </div>
	</div>