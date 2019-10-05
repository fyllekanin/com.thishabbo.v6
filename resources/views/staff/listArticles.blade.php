<script> urlRoute.setTitle("TH - List Articles");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>List Articles</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerBlue">
                List Articles
              </div>
            <div class="content-ct">
                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Author</th>
                            <th>Posted</th>
                            <th>Approved</th>
                            <th>Actions</th>
                            <th>Edit</th>
                        </tr>
                        @foreach($articles as $article)
                            <tr>
                                <td>
                                    @if($article['badge'])
                                      <div class="badge-container hover-box-info" style="background: #3a3a3a; filter: grayscale(0%); -webkit-filter: grayscale(0%); margin-left: 0;">
                                         <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badge_code'] }}.gif" alt="badge" />
                                      </div>
                                    @else
                                      <img src="{{ $article['thumbnail'] }}?{{ time() }}" alt="thumbnail" style="width: 50px; height: 50px;" />
                                    @endif
                                </td>
                                <td>{{ $article['title'] }}</td>
                                <td>{{ $article['type'] }}</td>
                                <td>{!! $article['author'] !!}</td>
                                <td>{{ $article['time'] }}</td>
                                <td>{{ $article['approved'] }}</td>
                                <td>
                                    <select id="articleid-{{ $article['articleid'] }}">
                                        @if($article['userid'] == Auth::user()->userid OR $can_manage_articles)
                                            <option value="1">Edit Article</option>
                                            @if($article['approved'] == "Yes")
                                                <option value="2">Unapprove  Article</option>
                                            @else
                                                <option value="3">Approve Article</option>
                                                <option value="5">Silently Approve Article</option>
                                            @endif
                                        @endif
                                        @if($can_manage_articles)
                                            <option value="4">Delete Article</option>
                                        @endif
                                    </select>
                                </td>
                                    <td><a onclick="articleAction({{ $article['articleid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>

                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="content-holder">
    <div class="content">
                <div class="pagination">
                    @if($paginator['previous_exists'])
                        <a href="/staff/media/articles/page/{{ $paginator['previous'] }}" class="web-page"><button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Previous</button></a>
                    @else
                        <button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Previous</button>
                    @endif
                    <div class="pg-pages">
                        <ul>
                            @if($paginator['gap_backward'])
                                <a href="/staff/media/articles/page/1" class="web-page"><li>1</li></a>
                                <a href="/staff/media/articles/page/2" class="web-page"><li>2</li></a>
                                <a href="/staff/media/articles/page/3" class="web-page"><li>3</li></a>
                                <li>...</li>
                                <a href="/staff/media/articles/page/{{ $paginator['current']-2 }}" class="web-page"><li>{{ $paginator['current']-2 }}</li></a>
                                <a href="/staff/media/articles/page/{{ $paginator['current']-1 }}" class="web-page"><li>{{ $paginator['current']-1 }}</li></a>
                            @else
                                @for($x = 1;$x < $paginator['current']; $x++)
                                    <a href="/staff/media/articles/page/{{ $x }}" class="web-page"><li>{{ $x }}</li></a>
                                @endfor
                            @endif
                            <li class="pg-pages-current">{{ $paginator['current'] }}</li>
                            @if($paginator['gap_forward'])
                                <li>...</li>
                                <a href="/staff/media/articles/page/{{ $paginator['total']-2 }}" class="web-page"><li>{{ $paginator['total']-2 }}</li></a>
                                <a href="/staff/media/articles/page/{{ $paginator['total']-1 }}" class="web-page"><li>{{ $paginator['total']-1 }}</li></a>
                                <a href="/staff/media/articles/page/{{ $paginator['total'] }}" class="web-page"><li>{{ $paginator['total'] }}</li></a>
                            @else
                                @for($x = $paginator['current']+1;$x <= $paginator['total']; $x++)
                                    <a href="/staff/media/articles/page/{{ $x }}" class="web-page"><li>{{ $x }}</li></a>
                                @endfor
                            @endif
                        </ul>
                    </div>
                    @if($paginator['next_exists'])
                        <a href="/staff/media/articles/page/{{ $paginator['next'] }}" class="web-page"><button class="pg-blue headerBlue floatright">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button></a>
                    @else
                        <button class="pg-blue headerBlue floatright">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                    @endif
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

	var articleAction = function(articleid) {
    	var action = $('#articleid-'+articleid).val();

	    switch(action) {
	      case "1":
	        urlRoute.loadPage('/staff/media/article/edit/'+articleid);
	      break;
	      case "2":
	        $.ajax({
	          url: urlRoute.getBaseUrl() + 'staff/media/article/deprove',
	          type: 'post',
	          data: {articleid:articleid},
	          success: function(data) {
	            urlRoute.loadPage('staff/media/articles/page/{{ $paginator['current'] }}');
	            urlRoute.ohSnap('Article deproved!', 'green');
	          }
	        });
	      break;
	      case "3":
	        $.ajax({
	          url: urlRoute.getBaseUrl() + 'staff/media/article/approve',
	          type: 'post',
	          data: {articleid:articleid},
	          success: function(data) {
	            urlRoute.loadPage('staff/media/articles/page/{{ $paginator['current'] }}');
	            urlRoute.ohSnap('Article approved!', 'green');
	          }
	        });
	      break;
	      case "4":
            if(confirm('Are you sure you wanna delete this article?')) {
    	        $.ajax({
    	          url: urlRoute.getBaseUrl() + 'staff/media/article/delete',
    	          type: 'post',
    	          data: {articleid:articleid},
    	          success: function(data) {
    	            urlRoute.loadPage('staff/media/articles/page/{{ $paginator['current'] }}');
    	            urlRoute.ohSnap('Article deleted!', 'green');
    	          }
    	        });
            }
	      break;
          case "5":
	        $.ajax({
	          url: urlRoute.getBaseUrl() + 'staff/media/article/sapprove',
	          type: 'post',
	          data: {articleid:articleid},
	          success: function(data) {
	            urlRoute.loadPage('staff/media/articles/page/{{ $paginator['current'] }}');
	            urlRoute.ohSnap('Article Silently approved!', 'green');
	          }
	        });
	      break;
    	}
  	}

  var destroy = function() {
    articleAction = null;
    badgeError = null;
  }
</script>
