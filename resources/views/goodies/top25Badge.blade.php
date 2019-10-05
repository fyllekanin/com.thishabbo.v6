<script> urlRoute.setTitle("TH - Top 25 Badge Collectors");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>


<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Top 25 Badge Collectors</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
			<div class="content-holder">
				<div class="content">
				<div class="contentHeader headerBlue">
				    <span>Top 25 Badge Collectors</span>
				  </div>
				  <div class="content-ct">
				    <table class="responsive" style="width: 100%;">
				      <tr>
				      	<th></th>
				        <th>Habbo</th>
				        <th>Habbo Name</th>
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
				          <td><img src="https://www.habbo.com/habbo-imaging/avatarimage?user={{ $top['habbo'] }}&direction=3&headonly=1" style="width: 30px; height: 35px;" /></td>
				          <td>{{ $top['habbo'] }}</td>
				          <td>{{ $top['amount'] }}</td>
				        </tr>
				        <?php $nr++; ?>
				      @endforeach
				    </table>
				  </div>
				</div>
			</div>
	</div>
<div class="medium-4 mobileFunction column">
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerGreen">
            		Information
          		</div>
	  			<div class="content-ct">
	    			Users listed here are everyone who is signed up to the website. This chart shows the top 25 badge collectors on ThisHabbo.<br><br>

	    			Not showing up? Just verify your Habbo acount <a href="/usercp/habbo" class="web-page"><i>here</i></a>.
	  			</div>
			</div>
	</div>
	<div class="content-holder">
			<div class="content">
			<div class="contentHeader headerBlue">
            			Scanned Badges
            			 <a href="/badges" class="headerLink white_link web-page"><b>More</b></a>
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
