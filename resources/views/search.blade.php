<script> urlRoute.setTitle("TH - Search");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/search" class="bold web-page">Search</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Search ThisHabbo</span>
    </div>
  </div>
</div>
<div class="small-12 column">
<div class="contentHeader headerRed">
                    <span>Search</span>
                </div>
</div>
@if($results)

<div class="medium-8 column">
            <div class="contentHeader headerBlue">
                <span>Search - @if(isset($_GET['criteria'])) {{ $_GET['criteria'] }} @endif</span>
            </div>


        @if($results['type'] === 'user')
            <div class="content-holder">
                <div class="content">
                @foreach($results['results'] as $result)
                <div class="small-12 column" style="margin-bottom:10px;">
                    <div class="small-8 column">
                        <div class="forum-name">
                            <a href="/profile/{{ $result['username'] }}" class="web-page">{!! $result['username'] !!}</a> <br />
                            <i><b>Last Online:</b> {{ $result['last_online'] }}</i>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        @endif

        @if($results['type'] === 'thread')
                <div class="content-holder">
                    <div class="content">
                        <div class="content-ct">
                            @foreach($results['results'] as $result)
                                <div class="small-7 column">
                                    <div class="forum-name">
                                        <a href="/forum/thread/{{ $result['threadid'] }}/page/1" class="web-page">{!! $result['title'] !!}</a> <br />
                                        <i>{{ $result['date'] }} by {!! $result['poster'] !!}</i>
                                    </div>
                                </div>
                                <div class="small-5 column">
                                    <div class="forum-stats">
                                      {!! $result['posts'] !!}<br />
                                      posts
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        @endif

        @if($results['type'] === 'article')
                <div class="content-holder">
                    <div class="content">
                        <div class="content-ct">
                            <div class="forum-list">
                            @foreach($results['results'] as $result)
                            <div class="small-12 column" style="margin-bottom:10px;">
                                <div class="small-8 column">
                                    <div class="forum-name">
                                      <a href="/article/{{ $result['articleid'] }}" class="web-page">{{ $result['title'] }}</a> <br />
                                      <i>{{ $result['date'] }} by {!! $result['poster'] !!}</i>
                                    </div>
                                </div>
                                <div class="small-4 column">
                                    <div class="forum-stats">
                                        <img src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $result['badgecode'] }}.gif" />
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        </div>
                    </div>
                </div>
        @endif

        @if($results['type'] === 'post')
        <?php $nr = 1; ?>
        @foreach($results['results'] as $result)
                    <div class="content-holder">
                        <div class="content">
                            <div class="contentHeader headerBlue">
                                {{ $result['date'] }}
                            </div>
                            <div class="content-ct">
                                <b>Thread</b>: <a href="{{ $result['threadurl'] }}" class="web-page">{{ $result['threadtitle'] }}</a><br />
                                <b>Posted by:</b> {{ $result['poster'] }}<hr></hr>
                                {!! $result['content'] !!}
                            </div>
                        </div>
                    </div>
                    <?php $nr++; ?>
                    @endforeach
        @endif

        <div class="small-12">
          <div class="content-holder">
                      <div class="content">
                          {!! $pagi !!}
                      </div>
                  </div>
        </div>

    @endif


</div>



<div class="@if($results) medium-4 @else medium-12 large-12 @endif small-12 column">
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
            <label for="sub-form-name">Search for...</label>
            <div class="medium-12 column">
                <input id="criteria" class="login-form-input" type="text" name="criteria" value="@if(isset($_GET['criteria'])){{ $_GET['criteria'] }}@endif"></input>
            </div>
            <label for="sub-form-name">Search type?</label>
            <div class="medium-12 column">
                <select id="type" class="login-form-input" name="type">
                    <option value="thread">Thread</option>
                    <option value="post">Post</option>
                    <option value="article">Article</option>
                    <option value="user">User</option>
                </select>
            </div>
            <label for="sub-form-name">Search in specific forum?</label>
            <div class="medium-12 column">
                <select id="searchforum" class="login-form-input " name="forum">
                    <option value="-1">All forums</option>
                    @foreach($forums as $forum)
                        <option value="{{ $forum['forumid'] }}"> {{ $forum['title'] }}</option>
                        @foreach($forum['sub_forums'] as $sub)
                            <option value="{{ $sub['forumid'] }}"> -- {{ $sub['title'] }} </option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <label>Search for content by a specific user?</label>
            <div class="medium-12 column">
                <input id="user" class="login-form-input" type="text" name="user" value="@if(isset($_GET['user'])){{ $_GET['user'] }}@endif"></input>
            </div>
            <label for="sub-form-name">Find posts from...</label>
            <div class="small-12 medium-6 column">
                <select id="from" class="login-form-input" name="from">
                    <option value="all">Anytime</option>
                    <option value="week">A week ago</option>
                    <option value="month">A month ago</option>
                    <option value="year">A year ago</option>
                </select>
            </div>
            <div class="small-12 medium-6 column">
                <select id="newerolder" class="login-form-input" name="from">
                    <option value="newer">and Newer</option>
                    <option value="older">and Older</option>
                </select>
            </div>
            <label>Sort results...</label>
            <div class="medium-12 column">
                <select class="login-form-input" id="sort" name="">
                <option value="ASC">Oldest first...</option>
                <option value="DESC">Newest first...</option>
                </select>
            </div>
            <br>
            <button onclick="executeSearch();" class="pg-red headerRed gradualfader fullWidth topBottom">Search</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    var executeSearch = function(){
        var criteria = $('#criteria').val();
        var type = $('#type').val();
        var searchforum = $('#searchforum').val();
        var from = $('#from').val();
        var newerolder = $('#newerolder').val();
        var user = $('#user').val();
        var sort = $('#sort').val();

        if(criteria === "" && user === ""){
          urlRoute.ohSnap('Enter criteria or user!','red');
        } else {

                  var path = encodeURI('search?criteria='+criteria+'&type='+type+'&searchforum='+searchforum+'&from='+from+'&newerolder='+newerolder+'&sort='+sort+'&user='+user);
                  urlRoute.loadPage(path);
        }



    };

</script>
