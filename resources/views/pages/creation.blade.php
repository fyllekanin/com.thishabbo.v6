<script> urlRoute.setTitle("TH - {{ $name }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/creations/page/1" class="bold web-page">Creations</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Creation: {{ $name }}</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
			  <div class="contentHeader headerBlue">
			    <span>{{ $name }}</span>
			    	<a onclick="javascript:history.back();" class="headerLink white_link" style="margin-left: 15px;">Back</a>
			    @if($can_manage_creations)
			    	<a onclick="deleteCreation();" class="headerLink white_link">Delete Creation</a>
			    @endif
			  </div>
	<div class="content-holder">
			<div class="content">
			  <div class="content-ct ct-center">
			  	<div class="row">
			  		<div class="small-12 column">
					  		<img src="{{ $image }}" alt="{{ $name }}" />
					</div>
			  		<div class="small-12 column" style="margin-top: 0.5rem;">
			  			<div class="row">
				  			<div class="small-4 column">
				  				@if($liked)
				  					<a onclick="unlikeCreation();"><i class="fa fa-heart" aria-hidden="true"></i> {{ $likes }} @if($likes != 1) Likes @else Like @endif</a>
				  				@else
				  					<a onclick="likeCreation();"><i class="fa fa-heart-o" aria-hidden="true"></i> {{ $likes }} @if($likes != 1) Likes @else Like @endif</a>
				  				@endif
				  			</div>
				  			<div class="small-4 column">
				  				<i class="fa fa-commenting-o" aria-hidden="true"></i> {{ $commentsAmount }} @if($commentsAmount != 1) comments @else comment @endif
				  			</div>
				  			<div class="small-4 column">
				  				<a href="{{ url('/') }}/download/creation/{{ $creationid }}"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download</a>
				  			</div>
				  		</div>
			  		</div>
				</div>
			  </div>
			</div>
	</div>

			  <div class="contentHeader headerRed">
			    <span>Other creations by {{ $clean_username }}</span>
			  </div>
	<div class="content-holder">
			<div class="content">
			  <div class="content-ct">
			        @foreach($other_creations as $creation)
			          <div class="small-6 large-3 end column">
			            <a href="/creation/{{ $creation['creationid'] }}" class="web-page">
			            <div class="creation-box" style="background-image: url('{{ $creation['image'] }}'); height: 75px;"></div></a>
			          </div>
			        @endforeach
			      </div>
			</div>
	</div>
	@foreach($comments as $comment)
		<div class="content-holder" id="commentid-{{ $comment['commentid'] }}">
            <div class="content">
                <div style="float: left;width: 107px;min-height: 127px;" class="ct-center">
                    <div class="profile-avatar-aa" style="background-image: url({{ $comment['avatar'] }}); "></div>
                    <div class="ct-center profileUser">
                        <a href="/profile/{{ $comment['clean_username'] }}" class="web-page">{!! $comment['username'] !!}</a>
                    </div>
                </div>
                <div class="article-comment-content">
                    @if(isset($can_infract_article_comments))
                        @if(Auth::check() && ($comment['userid'] != Auth::user()->userid))
                            <i onclick="issueWarnInf({{ $comment['commentid'] }});" class="fa fa-bell article-comment-delete editcog4" aria-hidden="true"></i>
                        @endif
                    @endif
                    @if(isset($can_soft_delete_article_comments))
                        @if(Auth::check() && ($comment['userid'] != Auth::user()->userid))
                            <i onclick="deleteComment({{ $comment['commentid'] }});" class="fa fa-trash article-comment-infwarn editcog4" aria-hidden="true"></i>
                        @endif
                    @endif
                    <div class="time">{{ $comment['dateline'] }}</div>
                        <div class="contentarticle" id="comment_content_{{ $comment['commentid'] }}">
                            <div>{!! $comment['content'] !!}</div>
                        </div>
                        <p></p>
                </div>
                <!-- comments here -->
                <div class="replies" id="{{ $comment['commentid'] }}" style="display:none;">
                    @if(Auth::check())
                        <textarea class="editor" id="editor_{{ $comment['commentid'] }}" style="height: 100px; font-size:12px !important;"></textarea>
                        <center><br>
                            <button class="pg-blue headerBlue gradualfader fullWidth" onclick="postComment({{ $comment['commentid'] }},{{ $comment['commentid'] }})">Post</button>
                        </center>
                        <br />
                    @endif
                </div>
            </div>
        </div>
	@endforeach
	@if(Auth::check())
	        	<div class="contentHeader headerRed">
	                Post A Comment
	            </div>
		<div class="content-holder">
	  		<div class="mainEditor">
		  		<textarea id="creation-form-comment" style="height: 100px; font-size:12px !important;"></textarea>
                <center><br />
                    <button class="pg-red headerRed gradualfader fullWidth topBottom"  onclick="postComment()">Send</button>
                </center>
			</div>
		</div>
	@endif
	@if(count($comments) > 0)
		<div class="content-holder">
		  	<div class="content">
				{!! $pagi !!}
			</div>
		</div>
	@endif
</div>


<div class="small-4 column mobileFunction">
			  <div class="contentHeader headerRed">
			    <span>Creator</span>
			  </div>
	<div class="content-holder">
			<div class="content">
    				<div class="small-3 column">
		  				<div style="text-align: center;">
		  					<div class="profile-avatar-pm" style="background-image: url({{ $avatar }}); width: 80px; height: 80px; margin-bottom: 5px;"></div>
		  					<a href="/profile/{{ $clean_username }}" class="web-page">{{ $clean_username }}</a>
		  				</div>
    				</div>
    				<div class="meetCreator">
    					{!! $bio !!}
    				</div>
			</div>
	</div>
</div>


<script type="text/javascript">
	var creation_editor = null;
	$(document).ready(function() {
	    $('#creation-form-comment').wysibb();
	    creation_editor = $('.wysibb-body').keyup(function(e){
	      if(e.key === 's' && e.altKey) {
	        e.preventDefault();
	        postComment();
	      }
	    });
  	});

	@if(Auth::check())
		var creationid = {{ $creationid }};
		@if($can_manage_creations)
			var deleteCreation = function() {
				if(confirm('You sure you wanna delete this?')) {
					$.ajax({
						url: urlRoute.getBaseUrl() + 'staff/mod/creation/delete',
						type: 'post',
						data: {creationid:creationid},
						success: function(data) {
							urlRoute.ohSnap('Creation deleted!', 'green');
							urlRoute.loadPage('/creations/page/1');
						}
					});
				}
			}
		@endif

		@if(!$liked)
			var likeCreation = function() {
				$.ajax({
					url: urlRoute.getBaseUrl() + 'creation/like',
					type: 'post',
					data: {creationid:creationid},
					success: function(data) {
						urlRoute.ohSnap('You liked the creation!', 'green');
						urlRoute.loadPage("/creation/{{ $creationid }}");
					}
				});
			}
		@else
			var unlikeCreation = function() {
				$.ajax({
					url: urlRoute.getBaseUrl() + 'creation/unlike',
					type: 'post',
					data: {creationid:creationid},
					success: function(data) {
						urlRoute.ohSnap('You unliked the creation!', 'green');
						urlRoute.loadPage("/creation/{{ $creationid }}");
					}
				});
			}
		@endif

		$("#commentForm").keypress(function(e) {
	      if(e.which == 13) {
	          postComment();
	      }
	    });
  		var postComment = function() {
  			var content = $('#creation-form-comment').bbcode();

  			if(content.lenght == 0) {
  				urlRoute.ohSnap("Can't post empty comment!", "blue");
  			} else {
  				$.ajax({
  					url: urlRoute.getBaseUrl() + 'creation/post/comment',
  					type: 'post',
  					data: {creationid:creationid, content:content},
  					success: function(data) {
  						if(data['response'] == true) {
  							urlRoute.loadPage('/creation/'+creationid)
  						} else {
  							urlRoute.ohSnap(data['message'],'red');
  						}
  					}
  				});
  			}
  		}

  		@if($can_delete_creation_comments)
  			var deleteComment = function(commentid) {
  				if(confirm("You sure you wanna delete this comment?")) {
  					$.ajax({
  						url: urlRoute.getBaseUrl() + 'staff/mod/delete/ccomment',
  						type: 'post',
  						data: {commentid:commentid},
  						success: function(data) {
  							$('#commentid-' + commentid).fadeOut();
  							urlRoute.ohSnap('Commented deleted!','green');
  						}
  					});
  				}
  			}
  		@endif
	@endif

    var destroy = function() {
        likeCreation = null;
        unlikeCreation = null;
        postComment = null;
        deleteComment = null;
        deleteCreation = null;
        creation_editor = null;
    }
</script>
