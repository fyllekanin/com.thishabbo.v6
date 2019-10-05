<script> urlRoute.setTitle("TH - DJ Love Leaderboard");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="small-12 column">
  <div class="content-topic topic-breadcrum" style="margin-bottom: 0.5rem;">
      <div class="content-topic-opacity"></div>
      <span><a href="/home" class="web-page">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
      <span>Radio <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
      <span>Love Leaderboard</span>
  </div>
</div>
<div class="small-12 medium-7 large-9 column">
    <div class="content-holder">
      <div class="inner-content-holder">
        <div class="content-topic topic-blue">
          <div class="content-topic-opacity"></div>
          <span>DJ Love Leaderboard</span>
        </div>
        <div class="content-ct">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th></th>
              <th>Username</th>
              <th>Member Since</th>
              <th>Amount</th>
            </tr>
            <?php $nr = 1; ?>
            @foreach($top25 as $top)
              <tr>
                <td>
                  <div class="nr_holder">
                    @if($nr > 3) 
                      <img src="{{ asset('_assets/img/website/EXH.gif') }}" style="width: 20px; height: 20px; margin-left: 0.43rem; margin-top: 0.7rem;" />
                    @elseif($nr == 1)
                      <img src="{{ asset('_assets/img/website/gold.gif') }}" />
                    @elseif($nr == 2)
                      <img src="{{ asset('_assets/img/website/silver.png') }}" />
                    @elseif($nr == 3)
                      <img src="{{ asset('_assets/img/website/bronze.png') }}" />
                    @endif
                  </div>
                </td>
                <td>{!! $top['username'] !!}</td>
                <td>{{ $top['joined'] }}</td>
                <td>{{ $top['amount'] }}</td>
              </tr>
              <?php $nr++; ?>
            @endforeach
          </table>
        </div>
      </div>
    </div>
</div>
<div class="small-12 medium-5 large-3 column">
    <div class="content-holder">
      <div class="inner-content-holder">
        <div class="content-topic topic-red">
          <div class="content-topic-opacity"></div>
          <span>Scanned Badges</span> <a href="/badges" class="web-page"><b>See More</b></a>
        </div>
        <div class="content-ct ct-center">
          <div class="row" id="list_badges">
              @foreach($badges as $badge)
              <div class="small-3 column">
                <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                  <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" />
                  @if($badge['new'])<div class="badge-new-badge">New</div>@endif
                </div>
               </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
	var badgeError = function(image) {
		image.onerror = "";
		image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
		return true;
	};

	var destroy = function() {
    	badgeError = null;
  	}	
</script>