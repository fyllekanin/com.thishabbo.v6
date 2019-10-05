<head>
	{{ Html::style('_assets/css/blockrain.css') }}
</head>

<script> document.title = "TH - Blockrain"; </script>

<div class="small-12 column">
	<div class="content-topic topic-breadcrum" style="margin-bottom: 0.5rem;">
    	<div class="content-topic-opacity"></div>
    	<span><a href="/home" class="web-page">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
    	<span style="margin-left: 0.4rem;">Blockrain</span>
  	</div>
</div>

<div class="small-12 medium-5 large-3 column">
	<div class="content-holder">
		<div class="inner-content-holder">
	  		<div class="content-topic topic-green">
	    		<div class="content-topic-opacity"></div>
	    		<span>LeaderBoard</span>
	  		</div>
	  		<div class="content-ct">
	  			<div class="row">
		  			@foreach($leaders as $leader)
		  				<div class="row">
			  				<div class="small-12 column">
				  				<div class="small-3 column">
				    				<a href="/profile/{{ $leader['clean_username'] }}/page/1" class="web-page"><div class="last_online hover-box-info" style="background-image: url('{{ $leader['avatar'] }}');" title="{{ $leader['clean_username'] }}"></div></a>
				    			</div>
				    			<div class="small-9 column" style="text-align: left;">
				    				<a href="/profile/{{ $leader['clean_username'] }}/page/1" class="web-page">{!! $leader['username'] !!}</a> <br />
				    				<div class="forum-activity-info" style="padding-top: 0.25rem;">
						        		Score <span class="forum-activity-time">{{ $leader['score'] }}</span>
						    		</div>
				    			</div>
				    		</div>
			    		</div>
		    		@endforeach
	    		</div>
			</div>
	    </div>
	</div>
</div>
<div class="small-12 medium-7 large-6 column">
	<div class="content-holder">
		<div class="inner-content-holder">
		 	<div class="game" style="width:100%; height:600px;"></div>
		</div>
	</div>
</div>
<div class="small-12 medium-5 large-3 column">
	<div class="content-holder">
		<div class="inner-content-holder">
	  		<div class="content-topic topic-black">
	    		<div class="content-topic-opacity"></div>
	    		<span>Other Games</span>
	  		</div>
	  		<div class="content-ct">
	  			gg
			</div>
	    </div>
	</div>
</div>

{{ Html::script('_assets/js/vendor/blockrain.min.js') }}
<script type="text/javascript">
	var d = null;
	var v = null;
	var gm = 1;
	var game = $('.game').blockrain({
		blockWidth: 15,
		onStart: function(){
			d = Math.floor(Date.now() / 1000);
		},
		onGameOver: function(score){
			v = Math.floor(Date.now() / 1000) - d;
			console.log(v);
			if(v > 30) {
				$.ajax({
					url: urlRoute.getBaseUrl() + 'game/save/score',
					type: 'post',
					data: {score:score, gm:gm},
					success: function(data) {
						urlRoute.ohSnap(data['message'], 'blue');
					}
				});
			}
		},
	});
	var onDestroy = function () {
		game = null;
	}
</script>