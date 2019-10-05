<script>
    urlRoute.setTitle("TH - {{ $art['title'] }}");
</script>

@if(Auth::check())
    <div class="reveal" id="rate_guide" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>

        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4>Rate The Guide</h4>
        </div>
        <div class="modal-body">
            @if(!$art['rates_article'])
                <fieldset>
                    <legend>Guide was helpful?</legend>
                    <select id="helpful" class="login-form-input">
                        <option value="1">Agree</option>
                        <option value="0">Disagree</option>
                    </select><br />
                    @if($art['type'] == 0)
                        <legend>Creative badge?</legend>
                        <select id="badge" class="login-form-input">
                            <option value="1">Agree</option>
                            <option value="0">Disagree</option>
                        </select><br />

                        <legend>Recommended?</legend>
                        <select id="recommended" class="login-form-input">
                            <option value="1">Agree</option>
                            <option value="0">Disagree</option>
                        </select><br />
                        <legend>Rate the event</legend>
                        <div class="small-10 columns">
                            <div class="slider" data-start="0" data-end="10" data-slider>
                                <span class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="rateEvent"></span>
                                <span class="slider-fill" data-slider-fill></span>
                            </div>
                        </div>
                        <div class="small-2 columns">
                            <input min="0" max="10" class="login-form-input" type="number" id="rateEvent">
                        </div>
                    @endif
                </fieldset>
            </div>
            <div class="modal-footer">
                <button class="pg-red fullWidth headerBlue gradualfader shopbutton" onclick="rateGuide();">Rate <i class="fa fa-check"></i></button>
                <button id="close" class="pg-red fullWidth headerBlue gradualfader" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
            </div>
        @else
            You have already rated this article!
        </div>
        @endif
    </div>

    @if($can_report_comment)
        <div class="reveal" id="report_comment" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
                <h4 class="modal-title">Report Comment</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p><b>Your reason why you're reporting this post:</b><br />
                        <input type="text" id="reason_for_report" placeholder="Reason..." class="login-form-input" />
                        <br />
                        <p><b>Are you sure?</b></p>
                        <p>Are you sure you wish to <span id="moderationUsername" style="font-weight: bold;">Username</span>? Your account may get suspended on false reports. If you wish to continue the report, a Moderator will look into the situation. If a Moderator fails to find the problem, they may request further assistance from you.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="reportPoster();">Report <i class="fa fa-check"></i></button>
                <button id="close" class="pg-red fullWidth headerBlue gradualfader" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
            </div>
        </div>

        <div class="reveal" id="flag-dialog" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
                <h4 class="modal-title">Got a problem?</h4>
            </div>
            <div class="modal-body">
                <select id="type" class="login-form-input">
                    <option value="1">Option 1: There is a problem with the guide!</option>
                    <option value="2">Option 2: The guide is offensive, help!</option>
                </select><br />
                <textarea class="login-form-input" id="reason" placeholder="Reason..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="pg-red headerRed floatright gradualfader" name="button" onclick="flagArticle()" style="margin-left: 5px;">Flag</button>
                <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
            </div>
        </div>
    @endif

    <div class="reveal" id="sharelink" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4 class="modal-title">Share the guide!</h4>
        </div>
        <div class="modal-body">
            <p>Use the following link to share this guide with your friends and get on the leaderboard!</p>
            <input class="login-form-input" type="text" name="" value="{{ $sharelink }}"></input><br />
            <br />
            <p>Use the below button to tweet with your share link!</p>
            <button class="pg-red headerBlue gradualfader" onclick="sharelinkToTwitter()">Share Guide <i class="fa fa-twitter" aria-hidden="true"></i></button>
        </div>
    </div>


    <div class="reveal" id="thc-dialog" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4 class="modal-title">Enjoy the guide? Send the author THC!</h4>
        </div>
        <div class="modal-body">
            <fieldset>
                <div class="small-10 columns">
                    <div class="slider" data-start="0" data-end="{{ Auth::user()->credits }}" data-slider>
                        <span class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="amt-thc"></span>
                        <span class="slider-fill" data-slider-fill></span>
                    </div>
                </div>
                <div class="small-2 columns">
                    <input min="0" max="{{ Auth::user()->credits }}" class="login-form-input" type="number" id="amt-thc">
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red fullWidth headerBlue gradualfader shopbutton" name="button" onclick="sendTHC()">Send <i class="fa fa-check"></i></button>
            <button id="close" class="pg-red fullWidth headerBlue gradualfader headerBlue" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- START OF ISSUE WARNING AND INFRACTION -->
    @if($can_infract_article_comments)
        <div class="reveal" id="warning_comment" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
                <h4 class="modal-title">Moderation</h4>
            </div>
            <div class="modal-body">
                <legend>Reason</legend>

                <select id="inputReasonif" class="login-form-input">
                    @foreach($infraction_reasons as $reason)
                        <option value="{{ $reason['infractionrsnid'] }}">{{ $reason['reason'] }} - {{ $reason['points'] }} Point(s)</option>
                    @endforeach
                </select>

                <br />
                <legend>Infraction/Warning</i>
                </legend>

                <select id="inputTypeif" class="login-form-input">
                    <option value="1" selected="">Infraction</option>
                    <option value="0">Warning</option>
                    <option value="2">Verbal Warning</option>
                </select>

                <br />
                <div class="form-group">
                    <legend>Prewritten PM: <i>(Can be edited)</i></legend>
<textarea id="inputPmif" class="login-form-input" rows="10">
[b]Dear {USER},[/b]
You have received a {INFRACTION/WARNING} at ThisHabboForum.

Reason:
-------
{INFRACTION/WARNING HERE}
-------

[quote]{EVIDENCE}[/quote]

[b]All the best,
ThisHabboForum[/b]
</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button id="report" class="pg-blue headerBlue gradualfader fullWidth shopbutton" onclick="giveWarnInf();">Add <i class="fa fa-check"></i></button>
                <button id="close" class="pg-blue headerBlue gradualfader fullWidth" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>

            </div>
        </div>
    @endif
    <!-- END OF ISSUE WARNING AND INFRACTION -->
@endif


<div class="medium-8 column">
    <div class="contentHeader headerBlue">
        {{ $art['title'] }} - {{ $art['time'] }}
        @if($can_edit_article)
            <a href="/staff/media/article/edit/{{ $art['articleid'] }}" class="headerLink white_link web-page">Edit Article</a>
        @endif
    </div>


    <div class="content-holder">
        <div class="content">
            <div id="article_content_{{ $art['articleid'] }}">
                {!! $art['content'] !!}
            </div>


            @if($art['have_likers'] == 0)
                <div class="post_likes article-likes-line-{{ $art['articleid'] }}">
                    {!! $art['likers_strike'] !!}

                </div>
            @endif
            @if(Auth::check())
                <br />
                <div class="time timeBar" style="clear:both;">

                    <div class="post-tools-left">
                        <div class="tool-mark" title="Report this post!" onclick="flagPressed();">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                        <!-- REPORT POST -->
                        </div>
                    </div>
                    <div class="post-tools-right">
                        @if(Auth::user()->userid != $author['userid'])
                            <span class="likes_area_{{ $art['articleid'] }}"><script>
    urlRoute.setTitle("TH - {{ $art['title'] }}");
</script>

@if(Auth::check())
    <div class="reveal" id="rate_guide" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>

        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4>Rate The Guide</h4>
        </div>
        <div class="modal-body">
            @if(!$art['rates_article'])
                <fieldset>
                    <legend>Guide was helpful?</legend>
                    <select id="helpful" class="login-form-input">
                        <option value="1">Agree</option>
                        <option value="0">Disagree</option>
                    </select><br />
                    @if($art['type'] == 0)
                        <legend>Creative badge?</legend>
                        <select id="badge" class="login-form-input">
                            <option value="1">Agree</option>
                            <option value="0">Disagree</option>
                        </select><br />

                        <legend>Recommended?</legend>
                        <select id="recommended" class="login-form-input">
                            <option value="1">Agree</option>
                            <option value="0">Disagree</option>
                        </select><br />
                        <legend>Rate the event</legend>
                        <div class="small-10 columns">
                            <div class="slider" data-start="0" data-end="10" data-slider>
                                <span class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="rateEvent"></span>
                                <span class="slider-fill" data-slider-fill></span>
                            </div>
                        </div>
                        <div class="small-2 columns">
                            <input min="0" max="10" class="login-form-input" type="number" id="rateEvent">
                        </div>
                    @endif
                </fieldset>
            </div>
            <div class="modal-footer">
                <button class="pg-red fullWidth headerBlue gradualfader shopbutton" onclick="rateGuide();">Rate <i class="fa fa-check"></i></button>
                <button id="close" class="pg-red fullWidth headerBlue gradualfader" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
            </div>
        @else
            You have already rated this article!
        </div>
        @endif
    </div>

    @if($can_report_comment)
        <div class="reveal" id="report_comment" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
                <h4 class="modal-title">Report Comment</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p><b>Your reason why you're reporting this post:</b><br />
                        <input type="text" id="reason_for_report" placeholder="Reason..." class="login-form-input" />
                        <br />
                        <p><b>Are you sure?</b></p>
                        <p>Are you sure you wish to <span id="moderationUsername" style="font-weight: bold;">Username</span>? Your account may get suspended on false reports. If you wish to continue the report, a Moderator will look into the situation. If a Moderator fails to find the problem, they may request further assistance from you.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="reportPoster();">Report <i class="fa fa-check"></i></button>
                <button id="close" class="pg-red fullWidth headerBlue gradualfader" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
            </div>
        </div>

        <div class="reveal" id="flag-dialog" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
                <h4 class="modal-title">Got a problem?</h4>
            </div>
            <div class="modal-body">
                <select id="type" class="login-form-input">
                    <option value="1">Option 1: There is a problem with the guide!</option>
                    <option value="2">Option 2: The guide is offensive, help!</option>
                </select><br />
                <textarea class="login-form-input" id="reason" placeholder="Reason..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="pg-red headerRed floatright gradualfader" name="button" onclick="flagArticle()" style="margin-left: 5px;">Flag</button>
                <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
            </div>
        </div>
    @endif

    <div class="reveal" id="sharelink" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4 class="modal-title">Share the guide!</h4>
        </div>
        <div class="modal-body">
            <p>Use the following link to share this guide with your friends and get on the leaderboard!</p>
            <input class="login-form-input" type="text" name="" value="{{ $sharelink }}"></input><br />
            <br />
            <p>Use the below button to tweet with your share link!</p>
            <button class="pg-red headerBlue gradualfader" onclick="sharelinkToTwitter()">Share Guide <i class="fa fa-twitter" aria-hidden="true"></i></button>
        </div>
    </div>


    <div class="reveal" id="thc-dialog" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4 class="modal-title">Enjoy the guide? Send the author THC!</h4>
        </div>
        <div class="modal-body">
            <fieldset>
                <div class="small-10 columns">
                    <div class="slider" data-start="0" data-end="{{ Auth::user()->credits }}" data-slider>
                        <span class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="amt-thc"></span>
                        <span class="slider-fill" data-slider-fill></span>
                    </div>
                </div>
                <div class="small-2 columns">
                    <input min="0" max="{{ Auth::user()->credits }}" class="login-form-input" type="number" id="amt-thc">
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red fullWidth headerBlue gradualfader shopbutton" name="button" onclick="sendTHC()">Send <i class="fa fa-check"></i></button>
            <button id="close" class="pg-red fullWidth headerBlue gradualfader headerBlue" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- START OF ISSUE WARNING AND INFRACTION -->
    @if($can_infract_article_comments)
        <div class="reveal" id="warning_comment" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
            <div class="modal-header">
                <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
                <h4 class="modal-title">Moderation</h4>
            </div>
            <div class="modal-body">
                <legend>Reason</legend>

                <select id="inputReasonif" class="login-form-input">
                    @foreach($infraction_reasons as $reason)
                        <option value="{{ $reason['infractionrsnid'] }}">{{ $reason['reason'] }} - {{ $reason['points'] }} Point(s)</option>
                    @endforeach
                </select>

                <br />
                <legend>Infraction/Warning</i>
                </legend>

                <select id="inputTypeif" class="login-form-input">
                    <option value="1" selected="">Infraction</option>
                    <option value="0">Warning</option>
                </select>

                <br />
                <div class="form-group">
                    <legend>Prewritten PM: <i>(Can be edited)</i></legend>
<textarea id="inputPmif" class="login-form-input" rows="10">
[b]Dear {USER},[/b]
You have received a {INFRACTION/WARNING} at ThisHabboForum.

Reason:
-------
{INFRACTION/WARNING HERE}
-------

Original Post: {POST LINK HERE}

[quote]{EVIDENCE}[/quote]

[b]All the best,
ThisHabboForum[/b]
</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button id="report" class="pg-blue headerBlue gradualfader fullWidth shopbutton" onclick="giveWarnInf();">Add <i class="fa fa-check"></i></button>
                <button id="close" class="pg-blue headerBlue gradualfader fullWidth" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>

            </div>
        </div>
    @endif
    <!-- END OF ISSUE WARNING AND INFRACTION -->
@endif

<script type="text/javascript">
    $('body').ready(function() {
        @if(isset($_GET['userid']))
        var articleid = {{ $art['articleid'] }};
        var referrerid = {{ $_GET['userid'] }};

        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/share',
            type: 'post',
            data: {
                articleid: articleid,
                referrerid: referrerid
            },
            success: function(data) {}
        });

        @endif
    });


    // FUNCTIONS YOU MUSSED BE LOGGED IN FOR
    @if(Auth::check() AND $verified === 1)
    var article_editor = null;
    $(document).ready(function() {
        $(document).foundation();
        var wbbOpt = {
            buttons:"bold,italic,underline,|,img,video,link,|,fontcolor,fontsize,fontfamily,|,smilebox,|,removeFormat,|",
        }
        $("#editor_main").wysibb(wbbOpt);
        $(".editor").each(function() {
            $(this).wysibb(wbbOpt);
        });
        article_editor = $('.wysibb-body').keyup(function(e) {
            if (e.key === 's' && e.altKey) {
                e.preventDefault();
                postComment();
            }
        });
        @if(isset($_GET['parent']) && $_GET['parent'] !== 0)
        $('#{{ $_GET["parent"] }}').toggle("fast");
        @endif
        @if(isset($_GET['comment']) && $_GET['comment'] !== 0)
        if($('#commentid-{{ $_GET['comment'] }}').length){
          $('html,body').animate({
              scrollTop: $("#commentid-{{ $_GET['comment'] }}").offset().top - 50
          });
        }
        @endif


    });


    var old_comment_id = 0;
    var old_comment_content = "";
    var editComment = function(commentid) {
        if (old_comment_id == 0) {
            old_comment_content = $('#comment_content_' + commentid).html();
            old_comment_id = commentid;
        } else {
            $('#comment_content_' + old_comment_id).html(old_comment_content);
            old_comment_content = $('#comment_content_' + commentid).html();
            old_comment_id = commentid;
        }
        $.ajax({
            url: urlRoute.getBaseUrl() + '/article/edit/get/comment',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#comment_content_' + commentid).html('<textarea id="article_comment_editor" style="font-size:12px !important;">' + data['content'] + '</textarea> <br><a><button class="pg-red headerBlue floatright gradualfader fullWidth topBottom barFix" onclick="postEdit();">Save Post</button></a><a><button class="pg-blue headerRed gradualfader fullWidth topBottom" onclick="cancelEdit();">Cancel</button></a>');
                    $('#article_comment_editor').wysibb();
                    edit_editor_event = $('.comment_content .wysibb-body').keyup(function(e) {
                        if (e.key === 's' && e.altKey) {
                            e.preventDefault();
                            postEdit();
                        }
                    });
                }
            }
        });
    }

    var cancelEdit = function() {
        $('#comment_content_' + old_comment_id).html(old_comment_content);
        old_comment_content = "";
        old_comment_id = 0;
    }
    var postEdit = function() {
        var content = $('#article_comment_editor').bbcode();
        var commentid = old_comment_id;
        $('#comment_content_' + commentid).html("");
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/comment/edit',
            type: 'post',
            data: {
                commentid: commentid,
                content: content
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#comment_content_' + commentid).html(data['content']);
                }
            }
        });
    }

    var postComment = function(parent = null, id = "article_comment_editor") {
        console.log(id);
        var articleid = {{ $art['articleid'] }};
        if (id === "article_comment_editor") {
            var content = $('#' + id).bbcode();
        } else if(id === "main"){
            $('#editor_main').wysibb();
            var content = $('#editor_' + id).bbcode();
        } else {
          var content = $('#editor_' + id).bbcode();
        }
        console.log(content);
        if (content.length == 0) {
            urlRoute.ohSnap("Can't post empty comment!", "blue");
        } else {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'article/post/comment',
                type: 'post',
                data: {
                    articleid: articleid,
                    content: content,
                    parent: parent
                },
                success: function(data) {
                    if (data['response'] == true) {
                        urlRoute.ohSnap(data['message'], 'green');
                        urlRoute.loadPage('/article/{{ $art["articleid"] }}?comment=' + data['commentid'] + '&parent=' + parent, false);
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var toggleFollow = function() {
        var userid = {{ $author['userid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'profile/toggleFollow',
            type: 'post',
            data: {
                userid: userid
            },
            success: function(data) {
                if (data['response'] === true) {
                    $('#followbutton').html(data['btnText'] + ' <i class="fa fa-user" aria-hidden="true"></i>');
                    urlRoute.ohSnap(data['noticeText'], "green");
                } else {
                    urlRoute.ohSnap(data['noticeText'], "red");
                }
            }
        });
    }

    var likeArticle = function(articleid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/like',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.likes_area_' + articleid).html('<div class="tool-mark" title="Unlike the post!" style="color: #de1f1f;" onclick="unlikeArticle(' + articleid + ');"><i class="fa fa-heart" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You liked the post!', 'blue');

                    if (data['likers']['have_likers'] == 0) {
                        if ($('.article-likes-line-' + articleid).length) {
                            $('.article-likes-line-' + articleid).html(data['likers']['likers_strike']);
                            $('.article-likes-line-' + articleid).fadeIn();
                        } else {
                            $('#article_content_' + articleid).append('<div class="post_likes article-likes-line-' + articleid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.article-likes-line-' + articleid).fadeOut();
                    }
                }
            }
        });
    }

    var unlikeArticle = function(articleid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/unlike',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.likes_area_' + articleid).html('<div class="tool-mark" title="Like the article!" onclick="likeArticle(' + articleid + ');"><i class="fa fa-heart-o" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You unliked the article!', 'blue');
                    if (data['likers']['have_likers'] == 0) {
                        if ($('.article-likes-line-' + articleid).lentgth) {
                            $('.article-likes-line-' + articleid).html(data['likers']['likers_strike']);
                            $('.article-likes-line-' + articleid).fadeIn();
                        } else {
                            $('#article_content_' + articleid).append('<div class="post_likes post-likes-line-' + articleid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.article-likes-line-' + articleid).fadeOut();
                    }
                }
            }
        });
    }

    var shareLink = function() {
        $('#sharelink').foundation("open");
    }

    var thcClicked = function() {
        $('#thc-dialog').foundation("open");
    }

    var rate = function() {
        $('#rate_guide').foundation("open");
    }

    var rateGuide = function() {
        var articleid = {{ $art['articleid'] }};
        var helpful = $('#helpful').val();
        var badge = $('#badge').val();
        var recommended = $('#recommended').val();
        var rate = $('#rateEvent').val();
        console.log(articleid);
        console.log(helpful);
        console.log(badge);
        console.log(recommended);
        console.log(rate);
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/rate',
            type: 'post',
            data: {
                articleid: articleid,
                helpful: helpful,
                badge: badge,
                recommended: recommended,
                rate: rate
            },
            success: function(data) {
                if (data['response']) {
                    urlRoute.ohSnap("Success!", 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var sendTHC = function() {
        var username = '{{ $author["clean_username"] }}';
        var points = $('#amt-thc').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/gift/points',
            type: 'post',
            data: {
                username: username,
                points: points
            },
            success: function(data) {
                if (data['response'] === true) {
                    urlRoute.ohSnap('THC sent!', 'green');
                    $('#thc-dialog').foundation("close");
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var likeComment = function(commentid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/like',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.comment_likes_area_' + commentid).html('<div class="tool-mark" title="Unlike the comment!" style="color: #de1f1f;" onclick="unlikeComment(' + commentid + ');"><i class="fa fa-heart" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You liked the comment!', 'blue');

                    if (data['likers']['have_likers'] == 0) {
                        if ($('.comment-likes-line-' + commentid).length) {
                            $('.comment-likes-line-' + commentid).html(data['likers']['likers_strike']);
                            $('.comment-likes-line-' + commentid).fadeIn();
                        } else {
                            $('#comment_content_' + commentid).append('<div class="post_likes comment-likes-line-' + commentid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.comment-likes-line-' + commentid).fadeOut();
                    }
                }
            }
        });
    }

    var unlikeComment = function(commentid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/unlike',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.comment_likes_area_' + commentid).html('<div class="tool-mark" title="Like the comment!" onclick="likeComment(' + commentid + ');"><i class="fa fa-heart-o" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You unliked the comment!', 'blue');
                    if (data['likers']['have_likers'] == 0) {
                        if ($('.comment-likes-line-' + commentid).length) {
                            $('.comment-likes-line-' + commentid).html(data['likers']['likers_strike']);
                            $('.comment-likes-line-' + commentid).fadeIn();
                        } else {
                            $('#comment_content_' + commentid).append('<div class="post_likes comment-likes-line-' + commentid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.comment-likes-line-' + commentid).fadeOut();
                    }
                }
            }
        });
    }

    var completeArticle = function(id) {
        var articleid = {{ $art['articleid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/complete',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    urlRoute.ohSnap(data['message'], 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var uncompleteArticle = function(id) {
        var articleid = {{ $art['articleid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/uncomplete',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    urlRoute.ohSnap(data['message'], 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var flagPressed = function() {
        $('#flag-dialog').foundation("open");
    }

    var flagArticle = function() {
        var articleid = {{ $art['articleid'] }};
        var type = $('#type').val();
        var reason = $('#reason').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/flag',
            type: 'post',
            data: {
                articleid: articleid,
                type: type,
                reason: reason
            },
            success: function(data) {
                urlRoute.ohSnap(data['message'], 'green');
                $('#flag-dialog').foundation("close");
            }
        });
    }

    @if($can_report_comment)
    var report_commentid = 0;

    var reportComment = function(postid) {
        report_commentid = postid;

        $('#report_comment').foundation('open');
    }

    var reportPoster = function() {
        var reason = $('#reason_for_report').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/report',
            type: 'post',
            data: {
                report_commentid: report_commentid,
                reason: reason
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#reason_for_report').val("");
                    $('#report_comment').foundation('close');
                    urlRoute.ohSnap('Report Sent!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
    @endif

    @if($can_edit_article)
    var changeAvailability = function() {
        var articleid = {{ $art['articleid'] }};
        var available = $('#article-availability').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/article/change/available',
            type: 'post',
            data: {
                articleid: articleid,
                available: available
            },
            success: function(data) {
                urlRoute.ohSnap('Availability updated!', 'green');
                urlRoute.loadPage('/article/{{ $art["articleid"] }}');
            }
        });
    }
    @endif
    @if($can_soft_delete_article_comments)
    var warning_commentid = 0;

    var issueWarnInf = function(commentid) {
        warning_commentid = commentid;
        $('#warning_comment').foundation('open');
    }

    var giveWarnInf = function() {
        var reason = $('#inputReasonif').val();
        var type = $('#inputTypeif').val();
        var pm = $('#inputPmif').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/moderation/inf/war',
            type: 'post',
            data: {
                reason: reason,
                type: type,
                pm: pm,
                warning_commentid: warning_commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#warning_comment').foundation('close');
                    urlRoute.ohSnap('Infraction/Warning sent!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
    @endif
    @if($can_soft_delete_article_comments)
    var deleteComment = function(commentid) {
        if (confirm("You sure you want delete this comment?")) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'staff/mod/delete/comment',
                type: 'post',
                data: {
                    commentid: commentid
                },
                success: function(data) {
                    $('#commentid-' + commentid).fadeOut();
                    urlRoute.ohSnap('Commented deleted!', 'green');
                }
            });
        }
    }
    @endif

    @endif

    // FUNCTIONS THAT CAN BE USED BY ANYBODY

    var openReplies = function(id) {
        $('#' + id).toggle('fast');
        $('#article_comment_editor').attr('id', '');
        $('.editor_' + id).attr('id', 'article_comment_editor');
        $('#article_comment_editor').wysibb();
        $('#button'+id).attr('onclick','closeReplies('+id+')');
    }

    var closeReplies = function(id) {
      $('#' + id).toggle('fast');
      $('#article_comment_editor').attr('id', '');
      $('#editor_main').wysibb();
      $('#button'+id).attr('onclick','openReplies('+id+')');
    }

    var badgeError = function(image) {
        image.onerror = "";
        image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
        return true;
    };

    var sharelinkToTwitter = function() {
        window.open("https://twitter.com/intent/tweet?text={{ $tweetsharebody }}");
    }

    var shareToTwitter = function() {
        window.open("https://twitter.com/intent/tweet?text={{ $tweetsharebody }}");
    }

    var visitArticle = function(id) {
        urlRoute.loadPage('article/' + id);
    }

    var destroy = function() {
        postComment = null;
        likeArticle = null;
        unlikeArticle = null;
        likeComment = null;
        unlikeComment = null;
        completeArticle = null;
        uncompleteArticle = null;
        flagPressed = null;
        flagArticle = null;
        reportComment = null;
        reportPoster = null;
        changeAvailability = null;
        deleteComment = null;
        toggleReplies = null;
        badgeError = null;
        shareToTwitter = null;
        article_editor = null;
        sharelinkToTwitter = null;
        shareLink = null;
        visitArticle = null;
        giveWarnInf = null;
    }
</script>

                                @if($art['likes_article'])
                                    <div class="tool-mark" title="Unlike the article!" style="color: #de1f1f;" onclick="unlikeArticle({{ $art['articleid'] }});">
                                        <i class="fa fa-heart" aria-hidden="true"></i> <!-- UNLIKE POST -->
                                    </div>
                                @else
                                    <div class="tool-mark" title="Like the post!" onclick="likeArticle({{ $art['articleid'] }});">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i> <!-- LIKE POST -->
                                    </div>
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                @if($art['completed'])
                    <button class="pg-red headerBlue pg-important gradualfader topBottom barFix" onclick="uncompleteArticle();">Mark as uncomplete <i class="fa fa-times"></i></button>
                @else
                    <button class="pg-red headerBlue pg-important gradualfader topBottom barFix" onclick="completeArticle();">Mark as complete <i class="fa fa-check" aria-hidden="true"></i></button>
                @endif
                <button class="pg-red headerBlue pg-important gradualfader topBottom barFix" onclick="rate()">Rate Guide <i class="fa fa-thumbs-up" aria-hidden="true"></i></button>
                <button class="pg-red headerBlue pg-important gradualfader topBottom" onclick="thcClicked()">Send Author THC <i class="fa fa-ticket" aria-hidden="true"></i></button>
            @endif
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
                    @if($can_infract_article_comments)
                        <i onclick="issueWarnInf({{ $comment['commentid'] }});" class="fa fa-bell article-comment-delete editcog4" aria-hidden="true"></i>
                    @endif
                    @if($can_soft_delete_article_comments)
                        <i onclick="deleteComment({{ $comment['commentid'] }});" class="fa fa-trash article-comment-infwarn editcog4" aria-hidden="true"></i>
                    @endif
                    <div class="time">{{ $comment['date'] }}</div>
                        <div class="contentarticle" id="comment_content_{{ $comment['commentid'] }}">
                            <div>{!! $comment['content'] !!}</div>
                            @if($comment['have_likers'] == 0)
                                <div class="post_likes comment-likes-line-{{ $comment['commentid'] }}">
                                    {!! $comment['likers_strike'] !!}
                                </div>
                            @endif
                        </div>
                        <p></p>
                </div>
                <div class="time" style="clear:both;">
                    @if(Auth::check())
                        @if($can_report_comment)
                            @if(Auth::check() && ($comment['userid'] != Auth::user()->userid))
                                <div class="post-tools-left">
                                    <div class="tool-mark" title="Report this post!" onclick="reportComment({{ $comment['commentid'] }});">
                                       <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                       <!-- REPORT POST -->
                                   </div>
                               </div>
                            @endif
                        @endif
                    @endif
                    <div class="post-tools-right">
                        @if(Auth::check())
                            @if(Auth::user()->userid != $comment['userid'])
                                <span class="comment_likes_area_{{ $comment['commentid'] }}">
                                    @if($comment['likes_comment'])
                                        <div class="tool-mark" title="Unlike the article!" style="color: #de1f1f;" onclick="unlikeComment({{ $comment['commentid'] }});">
                                            <i class="fa fa-heart" aria-hidden="true"></i> <!-- UNLIKE POST -->
                                        </div>
                                    @else
                                        <div class="tool-mark" title="Like the post!" onclick="likeComment({{ $comment['commentid'] }});">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i> <!-- LIKE POST -->
                                        </div>
                                    @endif
                                </span>
                            @endif
                        @endif
                        @if($comment['can_edit_comment'])
                            <div class="tool-mark" title="Edit the comment" onclick="editComment({{ $comment['commentid'] }});">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                <!-- EDIT POST -->
                            </div>
                        @endif
                        <div id="button-{{ $comment['commentid'] }}" class="tool-mark" onclick="openReplies({{ $comment['commentid'] }});">
                            @if($comment['repliescount']>0)
                                <div class="comment-count" style="display: block;">{{ $comment['repliescount'] }}</div>
                            @endif
                            <i class="fa fa-comments" aria-hidden="true" title=""></i>
                            <!-- QUOTE POST -->
                        </div>
                    </div>
                </div>
                <!-- comments here -->
                <div class="replies" id="{{ $comment['commentid'] }}" style="display:none;">
                    @foreach($comment['replies'] as $reply)
                        <div class="contentReply" id="commentid-{{ $reply['commentid'] }}">
                            <div style="float: left; width: 107px;min-height: 127px;" class="ct-center">
                                <div class="profile-avatar-aa" style="background-image: url({{ $reply['avatar'] }}); "></div>
                                <div class="ct-center profileUser">
                                    <a href="/profile/{{ $reply['clean_username'] }}" class="web-page">{!! $reply['username'] !!}</a>
                                </div>
                            </div>
                            <div class="article-comment-content">
                                @if($can_soft_delete_article_comments)
                                    <i onclick="deleteComment({{ $reply['commentid'] }});" class="fa fa-trash article-comment-delete" aria-hidden="true"></i>
                                @endif
                                <div class="time">{{ $reply['date'] }}</div>
                                <div class="contentarticle" id="comment_content_{{ $reply['commentid'] }}">
                                    <div>{!! $reply['content'] !!}</div>
                                    @if($reply['have_likers'] == 0)
                                        <div class="post_likes comment-likes-line-{{ $reply['commentid'] }}">
                                            {!! $reply['likers_strike'] !!}
                                        </div>
                                    @endif
                                </div>
                                <p></p>
                            </div>
                            <div class="time" style="clear:both;">
                                <div class="post-tools-left">
                                    @if(Auth::check())
                                        @if($can_report_comment)
                                            <div class="tool-mark" title="Report this post!" onclick="reportComment({{ $reply['commentid'] }})">
                                                <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                                <!-- REPORT POST -->
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="post-tools-right">
                                    @if(Auth::check())
                                        @if(Auth::user()->userid != $reply['userid'])
                                            <span class="comment_likes_area_{{ $reply['commentid'] }}">

                                                @if($reply['likes_reply'])
                                                    <div class="tool-mark" title="Unlike the comment!" style="color: #de1f1f;" onclick="unlikeComment({{ $reply['commentid'] }});">
                                                        <i class="fa fa-heart" aria-hidden="true"></i> <!-- UNLIKE POST -->
                                                    </div>
                                                @else
                                                    <div class="tool-mark" title="Like the comment!!" onclick="likeComment({{ $reply['commentid'] }});">
                                                        <i class="fa fa-heart-o" aria-hidden="true"></i> <!-- LIKE POST -->
                                                    </div>
                                                @endif
                                            </span>
                                        @endif
                                    @endif

                                    @if($reply['can_edit_reply'])
                                        <div class="tool-mark" title="Edit this comment" onclick="editComment({{ $reply['commentid'] }})">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            <!-- EDIT POST -->
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(Auth::check())
                        @if($verified === 1)
                        <textarea class="editor" id="editor_{{ $comment['commentid'] }}" style="height: 100px; font-size:12px !important;"></textarea>
                        <center><br>
                            <button class="pg-blue headerBlue gradualfader fullWidth" onclick="postComment({{ $comment['commentid'] }},{{ $comment['commentid'] }})">Post</button>
                        </center>
                        <br />
                        @else
                        <p>You must verify your habbo to use this function! <a href="/usercp/habbo" class="web-page">Click here to verify!</a></p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    @if(Auth::check())
        <div class="content-holder">
            <div class="content">
                <div class="content-ct">
                    @if($verified === 1)
                    <textarea id="editor_main" style="height: 100px; font-size:12px !important;"></textarea>
                    <center><br>
                        <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="postComment(null,'main')">Post</button>
                    </center>
                    @else
                    <p>You must verify your habbo to use this function! <a href="/usercp/habbo" class="web-page">Click here to verify!</a></p>
                    @endif
                </div>
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

<div class="small-4 mobileFunction column">
    <div class="contentHeader headerBlue">
        Info @if(Auth::check()) @if($can_report_comment)
        <a onclick="flagPressed();" class="headerLink white_link">Flag Article</a> @endif @endif
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                <div class="small-6 column">
                    <b>Guide Status</b> <br /> @if($art['available'] == 1)Available @endif @if($art['available'] == 2)Not Available @endif @if($art['available'] == 0)Hidden @endif
                </div>
                <div class="small-6 column">
                    <b>Views</b><br /> {{ $views }}

                </div>
                <div class="small-6 column">
                    <div class="article-badge1">
                        <b>Total Shares</b>
                        <br />
                        {{ $totalshares }}
                    </div>
                </div>

                <div class="small-6 column pulldown">
                    <b>Room Link</b><br /> @if(strlen($art['room_link']) > 0)
                    <a href="https://www.habbo.com/hotel?room={{ $art['room_link'] }}" target="_blank">
                                <img src="{{ asset('_assets/img/website/room.gif') }}" alt="room link" width="32" height="32" />
                                </a> @else No Room @endif
                </div>

                @if($art['type']===0)
                <div class="small-6 column pulldown">
                    <b>Free/Paid</b><br />
                    @if($art['paid']===0) Free @endif
                    @if($art['paid']===1) Paid @endif
                </div>
                <div class="small-6 column pulldown">
                    <b>Difficulty</b><br />
                    @if($art['difficulty']===0) Easy @endif
                    @if($art['difficulty']===1) Medium @endif
                    @if($art['difficulty']===2) Hard @endif
                </div>
                @endif

                <div class="large column pulldown">
                    <button class="pg-red headerBlue gradualfader fullWidth" onclick="shareToTwitter()">Share Guide <i class="fa fa-twitter" aria-hidden="true"></i></button>

                    <br />
                    <br />
                    <div id="pullleft">Guide was helpful?</div>
                    <div id="floatrightprog">{{ $rate['helpful'] }} out of {{ $rate['count'] }} agreed</div><br />

                    <div class="progress">
                        <div class="progress-bar2" role="progressbar" aria-valuenow="{{ $rate['helpfulperc'] }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $rate['helpfulperc'] }}%">
                            <div id="progtext">{{ $rate['helpfulperc'] }}% agree</div>
                        </div>
                    </div>
                    @if($art['type'] == 0)
                    <div id="pullleft">Creative badge?</div>
                    <div id="floatrightprog">{{ $rate['badge'] }} out of {{ $rate['count'] }} agreed</div><br />

                    <div class="progress">
                        <div class="progress-bar2" role="progressbar" aria-valuenow="{{ $rate['badgeperc'] }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $rate['badgeperc'] }}%">
                            <div id="progtext">{{ $rate['badgeperc'] }}% agree</div>
                        </div>
                    </div>


                    <div id="pullleft">Recommended?</div>
                    <div id="floatrightprog">{{ $rate['recommended'] }} out of {{ $rate['count'] }} agreed</div><br />

                    <div class="progress">
                        <div class="progress-bar2" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:{{ $rate['recommendedperc'] }}%">
                            <div id="progtext">{{ $rate['recommendedperc'] }}% recommendation</div>
                        </div>
                    </div>


                    <div id="pullleft">Rate the event /10</div>
                    <div id="floatrightprog">{{ $rate['over8'] }} voted 8 or higher</div><br />

                    <div class="progress">
                        <div class="progress-bar2" role="progressbar" aria-valuenow="{{ $rate['rate'] }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $rate['rateperc'] }}%">
                            <div id="progtext">{{ $rate['rate'] }}/10</div>
                        </div>
                    </div>
                    @endif



                </div>
            </div>
        </div>
    </div>



    <div class="contentHeader headerBlue">
        Top 10 Sharers
    </div>
    <div class="content-holder">
        <div class="content">


            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody>

                        <tr>
                            <th>Username</th>
                            <th>Shares</th>
                        </tr>
                        @foreach($art['shares'] as $share)
                        <tr>
                            <td>{!! $share['name'] !!}</td>
                            <td>{{ $share['count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @if(Auth::check())
                <div class="large column pulldown ct-center">
                    <button class="pg-red headerBlue gradualfader topBottom" onclick="shareLink()">Get Share Link <i class="fa fa-comment" aria-hidden="true"></i>
                        	</button>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="contentHeader headerBlue">
        Badges
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                @foreach($art['badges'] as $badge)
                <div class="small-2 column">
                    <div class="badge-container hover-box-info">
                        <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge }}.gif" alt="badge" />
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="contentHeader headerBlue">
        <a href="/badges" class="web-page headerLink white_link">More</a> Other Guides
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                <div class="row">
                    @foreach($other_articles as $article)
                    <div class="small-2 column">
                        <div class="badge-container hover-box-info" title="<b>{{ $article['badgecode'] }}:</b> <i>{{ $article['title'] }}</i>">
                            <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badgecode'] }}.gif" onclick="visitArticle({{ $article['articleid'] }})" alt="badge" />
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="contentHeader headerBlue">
        Other Guides by {{ $author['clean_username'] }}
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                <div class="row">
                    @foreach($other_articles_author as $article)
                    <div class="small-2 column">
                        <div class="badge-container hover-box-info" title="<b>{{ $article['badgecode'] }}:</b> <i>{{ $article['title'] }}</i>">
                            <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article['badgecode'] }}.gif" onclick="visitArticle({{ $article['articleid'] }})" alt="badge" />
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="contentHeader headerBlue">
        Meet the Author
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                        <div>
                            <a href="/profile/{{ $art['clean_username'] }}" class="web-page">
                                <div class="profile-avatar-ab" style="background-image: url({{ $author['avatar'] }});"></div>
                            </a>
                        </div>
                        <div>
                        </div>
                    <div class="meetAuthor">
                        {!! $author['username'] !!}<br />
                        <b>{{ $author['followers'] }}</b> followers <br /> {!! $author['bio'] !!}
                    </div>
                <center>
                    @if(Auth::check() && Auth::user()->userid != $author['userid'])
                        <button id="followbutton" class="pg-red headerBlue pg-important gradualfader topBottom" onclick="toggleFollow()">
                                @if($author['follows']) Unfollow <i class="fa fa-user" aria-hidden="true"></i>
                                @else Follow <i class="fa fa-user" aria-hidden="true"></i> @endif
                        </button>
                    @endif
                </center>
            </div>
        </div>
    </div>
    @if($can_edit_article)

    <div class="contentHeader headerBlack">
        Moderation: Availability
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                <select id="article-availability" class="login-form-input">
                    <option value="0" @if($art['available'] == 0) selected="" @endif >Don't Show</option>
                    <option value="1" @if($art['available'] == 1) selected="" @endif >Available</option>
                    <option value="2" @if($art['available'] == 2) selected="" @endif >Not Available</option>
                    </select>
                <center><br>
                    <button class="pg-red headerBlack pg-important gradualfader topBottom" onclick="changeAvailability();">Save <i class="fa fa-pencil" aria-hidden="true"></i></button>
                </center>
            </div>
        </div>
    </div>
    @endif
</div>
<script type="text/javascript">
    $('body').ready(function() {
        @if(isset($_GET['userid']))
        var articleid = {{ $art['articleid'] }};
        var referrerid = {{ $_GET['userid'] }};

        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/share',
            type: 'post',
            data: {
                articleid: articleid,
                referrerid: referrerid
            },
            success: function(data) {}
        });

        @endif
    });


    // FUNCTIONS YOU MUSSED BE LOGGED IN FOR
    @if(Auth::check() AND $verified === 1)
    var article_editor = null;
    $(document).ready(function() {
        $(document).foundation();
        $("#editor_main").wysibb();
        $(".editor").each(function() {
            $(this).wysibb();
        });
        article_editor = $('.wysibb-body').keyup(function(e) {
            if (e.key === 's' && e.altKey) {
                e.preventDefault();
                postComment();
            }
        });
        @if(isset($_GET['parent']) && $_GET['parent'] !== 0)
        $('#{{ $_GET["parent"] }}').toggle("fast");
        @endif
        @if(isset($_GET['comment']) && $_GET['comment'] !== 0)
        if($('#commentid-{{ $_GET['comment'] }}').length){
          $('html,body').animate({
              scrollTop: $("#commentid-{{ $_GET['comment'] }}").offset().top - 50
          });
        }
        @endif


    });


    var old_comment_id = 0;
    var old_comment_content = "";
    var editComment = function(commentid) {
        if (old_comment_id == 0) {
            old_comment_content = $('#comment_content_' + commentid).html();
            old_comment_id = commentid;
        } else {
            $('#comment_content_' + old_comment_id).html(old_comment_content);
            old_comment_content = $('#comment_content_' + commentid).html();
            old_comment_id = commentid;
        }
        $.ajax({
            url: urlRoute.getBaseUrl() + '/article/edit/get/comment',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#comment_content_' + commentid).html('<textarea id="article_comment_editor" style="font-size:12px !important;">' + data['content'] + '</textarea> <br><a><button class="pg-red headerBlue floatright gradualfader fullWidth topBottom barFix" onclick="postEdit();">Save Post</button></a><a><button class="pg-blue headerRed gradualfader fullWidth topBottom" onclick="cancelEdit();">Cancel</button></a>');
                    $('#article_comment_editor').wysibb();
                    edit_editor_event = $('.comment_content .wysibb-body').keyup(function(e) {
                        if (e.key === 's' && e.altKey) {
                            e.preventDefault();
                            postEdit();
                        }
                    });
                }
            }
        });
    }

    var cancelEdit = function() {
        $('#comment_content_' + old_comment_id).html(old_comment_content);
        old_comment_content = "";
        old_comment_id = 0;
    }
    var postEdit = function() {
        var content = $('#article_comment_editor').bbcode();
        var commentid = old_comment_id;
        $('#comment_content_' + commentid).html("");
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/comment/edit',
            type: 'post',
            data: {
                commentid: commentid,
                content: content
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#comment_content_' + commentid).html(data['content']);
                }
            }
        });
    }

    var postComment = function(parent = null, id = "article_comment_editor") {
        console.log(id);
        var articleid = {{ $art['articleid'] }};
        if (id === "article_comment_editor") {
            var content = $('#' + id).bbcode();
        } else if(id === "main"){
            $('#editor_main').wysibb();
            var content = $('#editor_' + id).bbcode();
        } else {
          var content = $('#editor_' + id).bbcode();
        }
        console.log(content);
        if (content.length == 0) {
            urlRoute.ohSnap("Can't post empty comment!", "blue");
        } else {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'article/post/comment',
                type: 'post',
                data: {
                    articleid: articleid,
                    content: content,
                    parent: parent
                },
                success: function(data) {
                    if (data['response'] == true) {
                        urlRoute.ohSnap(data['message'], 'green');
                        urlRoute.loadPage('/article/{{ $art["articleid"] }}?comment=' + data['commentid'] + '&parent=' + parent, false);
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var toggleFollow = function() {
        var userid = {{ $author['userid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'profile/toggleFollow',
            type: 'post',
            data: {
                userid: userid
            },
            success: function(data) {
                if (data['response'] === true) {
                    $('#followbutton').html(data['btnText'] + ' <i class="fa fa-user" aria-hidden="true"></i>');
                    urlRoute.ohSnap(data['noticeText'], "green");
                } else {
                    urlRoute.ohSnap(data['noticeText'], "red");
                }
            }
        });
    }

    var likeArticle = function(articleid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/like',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.likes_area_' + articleid).html('<div class="tool-mark" title="Unlike the post!" style="color: #de1f1f;" onclick="unlikeArticle(' + articleid + ');"><i class="fa fa-heart" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You liked the post!', 'blue');

                    if (data['likers']['have_likers'] == 0) {
                        if ($('.article-likes-line-' + articleid).length) {
                            $('.article-likes-line-' + articleid).html(data['likers']['likers_strike']);
                            $('.article-likes-line-' + articleid).fadeIn();
                        } else {
                            $('#article_content_' + articleid).append('<div class="post_likes article-likes-line-' + articleid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.article-likes-line-' + articleid).fadeOut();
                    }
                }
            }
        });
    }

    var unlikeArticle = function(articleid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/unlike',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.likes_area_' + articleid).html('<div class="tool-mark" title="Like the article!" onclick="likeArticle(' + articleid + ');"><i class="fa fa-heart-o" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You unliked the article!', 'blue');
                    if (data['likers']['have_likers'] == 0) {
                        if ($('.article-likes-line-' + articleid).lentgth) {
                            $('.article-likes-line-' + articleid).html(data['likers']['likers_strike']);
                            $('.article-likes-line-' + articleid).fadeIn();
                        } else {
                            $('#article_content_' + articleid).append('<div class="post_likes post-likes-line-' + articleid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.article-likes-line-' + articleid).fadeOut();
                    }
                }
            }
        });
    }

    var shareLink = function() {
        $('#sharelink').foundation("open");
    }

    var thcClicked = function() {
        $('#thc-dialog').foundation("open");
    }

    var rate = function() {
        $('#rate_guide').foundation("open");
    }

    var rateGuide = function() {
        var articleid = {{ $art['articleid'] }};
        var helpful = $('#helpful').val();
        var badge = $('#badge').val();
        var recommended = $('#recommended').val();
        var rate = $('#rateEvent').val();
        console.log(articleid);
        console.log(helpful);
        console.log(badge);
        console.log(recommended);
        console.log(rate);
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/rate',
            type: 'post',
            data: {
                articleid: articleid,
                helpful: helpful,
                badge: badge,
                recommended: recommended,
                rate: rate
            },
            success: function(data) {
                if (data['response']) {
                    urlRoute.ohSnap("Success!", 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var sendTHC = function() {
        var username = '{{ $author["clean_username"] }}';
        var points = $('#amt-thc').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/gift/points',
            type: 'post',
            data: {
                username: username,
                points: points
            },
            success: function(data) {
                if (data['response'] === true) {
                    urlRoute.ohSnap('THC sent!', 'green');
                    $('#thc-dialog').foundation("close");
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var likeComment = function(commentid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/like',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.comment_likes_area_' + commentid).html('<div class="tool-mark" title="Unlike the comment!" style="color: #de1f1f;" onclick="unlikeComment(' + commentid + ');"><i class="fa fa-heart" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You liked the comment!', 'blue');

                    if (data['likers']['have_likers'] == 0) {
                        if ($('.comment-likes-line-' + commentid).length) {
                            $('.comment-likes-line-' + commentid).html(data['likers']['likers_strike']);
                            $('.comment-likes-line-' + commentid).fadeIn();
                        } else {
                            $('#comment_content_' + commentid).append('<div class="post_likes comment-likes-line-' + commentid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.comment-likes-line-' + commentid).fadeOut();
                    }
                }
            }
        });
    }

    var unlikeComment = function(commentid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/unlike',
            type: 'post',
            data: {
                commentid: commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('.comment_likes_area_' + commentid).html('<div class="tool-mark" title="Like the comment!" onclick="likeComment(' + commentid + ');"><i class="fa fa-heart-o" aria-hidden="true"></i></div>');

                    urlRoute.ohSnap('You unliked the comment!', 'blue');
                    if (data['likers']['have_likers'] == 0) {
                        if ($('.comment-likes-line-' + commentid).length) {
                            $('.comment-likes-line-' + commentid).html(data['likers']['likers_strike']);
                            $('.comment-likes-line-' + commentid).fadeIn();
                        } else {
                            $('#comment_content_' + commentid).append('<div class="post_likes comment-likes-line-' + commentid + '">' + data['likers']['likers_strike'] + '</div>');
                        }
                    } else {
                        $('.comment-likes-line-' + commentid).fadeOut();
                    }
                }
            }
        });
    }

    var completeArticle = function(id) {
        var articleid = {{ $art['articleid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/complete',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    urlRoute.ohSnap(data['message'], 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var uncompleteArticle = function(id) {
        var articleid = {{ $art['articleid'] }};
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/uncomplete',
            type: 'post',
            data: {
                articleid: articleid
            },
            success: function(data) {
                if (data['response'] == true) {
                    urlRoute.ohSnap(data['message'], 'green');
                    urlRoute.loadPage('/article/{{ $art["articleid"] }}');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var flagPressed = function() {
        $('#flag-dialog').foundation("open");
    }

    var flagArticle = function() {
        var articleid = {{ $art['articleid'] }};
        var type = $('#type').val();
        var reason = $('#reason').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/flag',
            type: 'post',
            data: {
                articleid: articleid,
                type: type,
                reason: reason
            },
            success: function(data) {
                urlRoute.ohSnap(data['message'], 'green');
                $('#flag-dialog').foundation("close");
            }
        });
    }

    @if($can_report_comment)
    var report_commentid = 0;

    var reportComment = function(postid) {
        report_commentid = postid;

        $('#report_comment').foundation('open');
    }

    var reportPoster = function() {
        var reason = $('#reason_for_report').val();
        var pagenumber = "{{ $current_page }}";

        $.ajax({
            url: urlRoute.getBaseUrl() + 'comment/report',
            type: 'post',
            data: {
                report_commentid: report_commentid,
                reason: reason,
                pagenumber:pagenumber
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#reason_for_report').val("");
                    $('#report_comment').foundation('close');
                    urlRoute.ohSnap('Report Sent!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
    @endif

    @if($can_edit_article)
    var changeAvailability = function() {
        var articleid = {{ $art['articleid'] }};
        var available = $('#article-availability').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/article/change/available',
            type: 'post',
            data: {
                articleid: articleid,
                available: available
            },
            success: function(data) {
                urlRoute.ohSnap('Availability updated!', 'green');
                urlRoute.loadPage('/article/{{ $art["articleid"] }}');
            }
        });
    }
    @endif
    @if($can_soft_delete_article_comments)
    var warning_commentid = 0;

    var issueWarnInf = function(commentid) {
        warning_commentid = commentid;
        $('#warning_comment').foundation('open');
    }

    var giveWarnInf = function() {
        var reason = $('#inputReasonif').val();
        var type = $('#inputTypeif').val();
        var pm = $('#inputPmif').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'article/moderation/inf/war',
            type: 'post',
            data: {
                reason: reason,
                type: type,
                pm: pm,
                warning_commentid: warning_commentid
            },
            success: function(data) {
                if (data['response'] == true) {
                    $('#warning_comment').foundation('close');
                    urlRoute.ohSnap('Infraction/Warning sent!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
    @endif
    @if($can_soft_delete_article_comments)
    var deleteComment = function(commentid) {
        if (confirm("You sure you want delete this comment?")) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'staff/mod/delete/comment',
                type: 'post',
                data: {
                    commentid: commentid
                },
                success: function(data) {
                    $('#commentid-' + commentid).fadeOut();
                    urlRoute.ohSnap('Commented deleted!', 'green');
                }
            });
        }
    }
    @endif

    @endif

    // FUNCTIONS THAT CAN BE USED BY ANYBODY

    var openReplies = function(id) {
        $('#' + id).toggle('fast');
        $('#article_comment_editor').attr('id', '');
        $('.editor_' + id).attr('id', 'article_comment_editor');
        $('#article_comment_editor').wysibb();
        $('#button'+id).attr('onclick','closeReplies('+id+')');
    }

    var closeReplies = function(id) {
      $('#' + id).toggle('fast');
      $('#article_comment_editor').attr('id', '');
      $('#editor_main').wysibb();
      $('#button'+id).attr('onclick','openReplies('+id+')');
    }

    var badgeError = function(image) {
        image.onerror = "";
        image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
        return true;
    };

    var sharelinkToTwitter = function() {
        window.open("https://twitter.com/intent/tweet?text={{ $tweetsharebody }}");
    }

    var shareToTwitter = function() {
        window.open("https://twitter.com/intent/tweet?text={{ $tweetsharebody }}");
    }

    var visitArticle = function(id) {
        urlRoute.loadPage('article/' + id);
    }

    var destroy = function() {
        postComment = null;
        likeArticle = null;
        unlikeArticle = null;
        likeComment = null;
        unlikeComment = null;
        completeArticle = null;
        uncompleteArticle = null;
        flagPressed = null;
        flagArticle = null;
        reportComment = null;
        reportPoster = null;
        changeAvailability = null;
        deleteComment = null;
        toggleReplies = null;
        badgeError = null;
        shareToTwitter = null;
        article_editor = null;
        sharelinkToTwitter = null;
        shareLink = null;
        visitArticle = null;
        giveWarnInf = null;
    }
</script>
