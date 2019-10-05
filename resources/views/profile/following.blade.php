<script>urlRoute.setTitle("TH - Following List");</script>

<div class="small-12 column">
	<div class="content-holder">
		<div class="content contentpadding">
	  		<span>
					<a href="/home" class="bold web-page">Forum Home</a>
					<i class="fa fa-angle-double-right" aria-hidden="true"></i>
				<span>
					<a href="/search" class="bold web-page">Members</a>
					<i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<span>
						<a href="/profile/{!! $user['clean_username'] !!}" class="bold web-page">User Profile: {!! $user['clean_username'] !!}</a>
						<i class="fa fa-angle-double-right" aria-hidden="true"></i> Following
					</span>
				</span>
			</span>
	    </div>
	</div>
</div>

<div class="medium-4 column">
	<div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                <span>Quick Links</span>
            </div>
            <div class="small-12">
                <div class="menu-block">
            		<a href="/profile/{!! $user['clean_username'] !!}/followers" class="web-page">{!! $user['clean_username'] !!}'s Followers</a>
        		</div>
        		<div class="menu-block">
            		<a href="/profile/{!! $user['clean_username'] !!}/following" class="web-page">Who is {!! $user['clean_username'] !!} Following?</a>
        		</div>
            </div>
        </div>
    </div>
</div>
<div class="medium-8 column end">
	<div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>{!! $user['clean_username'] !!} is Following</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody><tr>
                        <th style="width:33.3%">Username</th>
                        <th style="width:33.3%">Habbo</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($following as $user)
                    <tr>
                        <td>{!! $user['username'] !!}</td>
                        <td>{{ $user['habbo'] }}</td>
                        <td><button class="pg-red headerRed gradualfader topBottom" onclick='window.open("/profile/{!! $user['clean_username'] !!}");' style="float:none !important;">Visit Profile</button></td>
                    </tr>
                    @endforeach
                </tbody></table>
            </div>
        </div>
    </div>
</div>
