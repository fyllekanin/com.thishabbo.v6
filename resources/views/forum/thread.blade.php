<script>urlRoute.setTitle("TH - {{ $threadprefixclean }}{{ $thread->title }}");</script>

<?php $breadCrum = \App\Helpers\ForumHelper::getBreadCrum($thread->forumid); ?>
<?php $readers = \App\Helpers\ForumHelper::usersWatchingThread($thread->threadid); ?>

@if($can_open_close_thread OR $can_soft_delete OR $can_hard_delete OR $can_edit_post OR $can_view_unapproved_threads OR $can_move_threads OR $can_open_close_thread OR $can_merge_threads OR $can_change_owner)
    <div id="thread_tools" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
        <button id="post_edit_button" onclick="posttools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Post Tools</button>
        <button id="thread_edit_button" onclick="threadtools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Thread Tools</button>
    </div>

    <div id="thread_tools_options" style="display:none; position: fixed; bottom: 3px; left: 9px; z-index: 5000;">
        <!-- open/close thread -->
        @if($can_open_close_thread)
            @if($thread->open == 1)
                <button id="addstickerbutton" onclick="closeThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Close Thread</button>
            @else
                <button id="addstickerbutton" onclick="openThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Open Thread</button>
            @endif
        @endif
        <!-- delete thread -->
        @if($can_soft_delete OR $can_hard_delete)
            @if($thread->visible == 1)
                <button id="addstickerbutton" onclick="setType(1);" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Delete Thread</button>
            @else
                <button id="addstickerbutton" onclick="unDeleteSelected(1);" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Undelete Thread</button>
            @endif
        @endif
        <!-- change thread owner -->
        @if($can_change_owner)
            <a data-open="change_owner" onclick="setChangeType();"><button id="clearstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Change Owner</button></a><br />
        @endif
        <!-- edit thread -->
        @if($can_edit_post OR $can_view_unapproved_threads)
            <a href="/forum/edit/thread/{{ $thread->threadid }}" class="web-page"><button id="addstickerbutton" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Edit Thread</button></a><br />
        @endif
        <!-- move thread -->
        @if($can_move_threads)
            <a href="/forum/move/thread/{{ $thread->threadid }}" class="web-page"><button id="addstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Move Thread</button></a><br />
        @endif
        <!-- approve thread-->
        @if($can_approve_unapprove_threads)
            @if($thread->visible == 1)
                <button id="addstickerbutton" onclick="unapproveThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Unapprove Thread</button>
            @else
                <button id="addstickerbutton" onclick="approveThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Approve Thread</button>
            @endif
        @endif
        <!-- open/close -->
        @if($can_open_close_thread)
            @if($thread->sticky == 1)
                <button id="addstickerbutton" onclick="unstickyThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Unsticky Thread</button>
            @else
                <button id="addstickerbutton" onclick="stickyThread();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Sticky Thread</button>
            @endif
        @endif
        <!-- merge -->
        @if($can_merge_threads)
            <a data-toggle="merge_thread"><button id="clearstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Merge Thread</button></a><br />
        @endif
        <button id="stop_editing_thread" onclick="stopEditingThreadTools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Cancel</button>
    </div>

    <div id="post_tools_options" style="position: fixed; bottom: 3px; left: 9px; z-index: 5000; display: none;">
        <!-- delete posts -->
        @if($can_soft_delete OR $can_hard_delete)
            <button id="addstickerbutton" onclick="setType(0);" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Delete Post</button>
            <button id="addstickerbutton" onclick="unDeleteSelected(0);" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Undelete Post</button>
        @endif
        @if($can_change_owner)
            <a data-open="change_owner" onclick="setChangeType(0);"><button id="clearstickerbutton" onclick="text();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Change Owner</button></a>
        @endif
        <button id="stop_editing_post" onclick="stopEditingPostTools();" class="gradualfader pg-red headerRed profileMod"><i class="fa fa-sticky-note" aria-hidden="true"></i> Cancel</button>
    </div>
@endif

<!-- SYSTEM LOADED CSS FOR USERBARS -->
@if(count($userbars_css))
    <style type="text/css">
    @foreach($userbars_css as $userbars_css)
        {!! $userbars_css !!}
    @endforeach
    </style>
@endif

@if($can_report_post)
<!-- START OF REPORT POST -->
<div class="reveal" id="report_post" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
        <h4 class="modal-title">Report Post</h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning">
            <p><b>Your reason why you're reporting this post:</b><br />
            <input type="text" id="reason_for_report" placeholder="Reason..." class="login-form-input"/></p>
            <br />
            <p><b>Are you sure?</b></p>
            <p>Are you sure you wish to report <span id="moderationUsername" style="font-weight: bold;">Username</span>? Your account may get suspended on false reports. If you wish to continue the report, a Moderator will look into the situation. If a Moderator fails to find the problem, they may request further assistance from you.</p>
        </div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerAdminRed gradualfader shopbutton" onclick="reportPoster()">Report <i class="fa fa-check"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue gradualfader" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
    </div>
</div>
<!-- END OF REPORT POST -->

    <!-- START OF FLAG? -->
    <div class="reveal" id="flag-dialog" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</button>
            <h4 class="modal-title">Got a problem?</h4>
        </div>
        @if(Auth::check())
            <div class="modal-body">
                <select id="type" class="login-form-input">
                    <option value="1">Option 1: There is a problem with the guide!</option>
                    <option value="2">Option 2: The guide is offensive, help!</option>
                </select><br />
                <textarea class="login-form-input" id="reason" placeholder="Reason..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="pg-red headerAdminRed floatright gradualfader" name="button" onclick="reportPoster()" style="margin-left: 5px;">Flag</button>
                <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
            </div>
        @else
            <div class="modal-body">
                <h4 class="modal-title">You must be signed in to flag articles!</h4>
            </div>
            <div class="modal-footer">
                <button id="report" class="pg-red headerAdminRed floatright gradualfader" onclick="reportPoster();" style="margin-left: 5px;">Report <i class="fa fa-check"></i><i class="fa fa-check"></i></button>
                <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close <i class="fa fa-times"></i></button>
            </div>
        @endif
    </div>
    <!-- END OF FLAG POST? -->
@endif

@if($can_warninf_users)
    <!-- START OF THREAD BAN -->
    <div class="reveal" id="ban_thread" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Moderation</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <p><b>Are you sure?</b></p>
                <p>Are you sure you wish to ban <span id="banUsername" style="font-weight: bold;">Username</span> from viewing this thread?</p>
            </div>
        </div>
        <div class="modal-footer">
            <button id="report" class="pg-red headerAdminRed floatright gradualfader" onclick="banuserfromthread();" style="margin-left: 5px;">Yes <i class="fa fa-check"></i></button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">No</button>
        </div>
    </div>
    <!-- END OF THREAD BAN -->
    <!-- START OF THREAD UNBAN -->
    <div class="reveal" id="unban_thread" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Moderation</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <p><b>Are you sure?</b></p>
                <p>Are you sure you wish to <b>unban</b> <span id="unbanUsername" style="font-weight: bold;">Username</span> from this thread? They will be able to access the thread once you do this.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button id="report" class="pg-red headerAdminRed floatright gradualfader" onclick="unbanuserfromthread();" style="margin-left: 5px;">Yes <i class="fa fa-check"></i></button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">No</button>
        </div>
    </div>
    <!-- END OF THREAD UNBAN -->
    <!-- START OF ISSUE WARNING AND INFRACTION -->
    <div class="reveal" id="warning_post" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Moderation</h4>
        </div>
        <div class="modal-body">
            <p><b>Reason:</b></p>
            <p>
                <select id="inputReasonif" class="login-form-input">
                    @foreach($infraction_reasons as $reason)
                        <option value="{{ $reason['infractionrsnid'] }}">{{ $reason['reason'] }} - {{ $reason['points'] }} Point(s)</option>
                    @endforeach
                </select>
            </p>
            <br />
            <p><b>Infraction/Warning:</b></p>
            <p>
                <select id="inputTypeif" class="login-form-input">
                    <option value="1" selected="">Infraction</option>
                    <option value="0">Warning</option>
                    <option value="2">Verbal Warning</option>
                </select>
            </p>
            <br />
            <div class="form-group">
                <label for="comment">Prewritten PM: <i>(Can be edited)</i></label>
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
            <button id="report" class="pg-red headerAdminRed floatright gradualfader" onclick="giveWarnInf();" style="margin-left: 5px;">Add <i class="fa fa-check"></i></button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
@endif
<!-- END OF ISSUE WARNING AND INFRACTION -->

@if($can_soft_delete OR $can_hard_delete)
    <!-- START OF DELETE POSTS -->
    <div class="reveal" id="delete_posts" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Delete</h4>
        </div>
        <div class="modal-body">
            <p>If first post (main thread post) is selected you are also deleting the thread</p>
            <fieldset>
                <legend>Select delete type:</legend>
                <input type="radio" name="delete_type" value="0" id="softDelete" checked="" /> Soft Delete <i>(Still in the database)</i><br />
                @if($can_hard_delete == 1)
                    <input type="radio" name="delete_type" value="1" id="hardDelete" /> Hard Delete <i>(Removed from database)</i> <br />
                @endif
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerAdminRed floatright gradualfader" onclick="deleteSelected();" style="margin-left: 5px;">Delete</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
    <!-- END OF DELETE POSTS -->
@endif
@if($can_move_posts)
    <!-- START OF MOVE POSTS -->
    <div class="reveal" id="move_posts" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Move Post(s)</h4>
        </div>
        <div class="modal-body">
            <p>Move post(s) to another thread!</p>
            <fieldset>
                <div class="row">
                    <div class="small-6 column">
                        <legend>Thread ID <em>(Thread id to move the posts into)</em>: </legend>
                        <input type="text" name="thread_name_search" id="threadList" checked="" placeholder="Thread ID"  class="login-form-input"/><br />
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerAdminRed floatright gradualfader" onclick="movePosts();" style="margin-left: 5px;">Move</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
    <!-- END OF MOVE POSTS -->
@endif

@if($can_change_owner)
    <!-- START OF CHANGE THREAD OWNER -->
    <div class="reveal" id="change_owner" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Change Post Owner</h4>
        </div>
        <div class="modal-body">
            <p>If first post (main post) of the thread is selected, the thread owner will also change</p>
            <fieldset>
                <legend>New owner's name: </legend>
                <input type="text" id="change_owner_new_name" placeholder="Username..." class="login-form-input"/>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerAdminRed floatright gradualfader" onclick="changeOwner();" style="margin-left: 5px;">Change Owner</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
    <!-- END OF CHANGE THREAD OWNER -->
@endif

@if($can_merge_threads)
    <!-- START OF MERGE THREADS -->
    <div class="reveal" id="merge_thread" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
        <div class="modal-header">
            <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
            <h4 class="modal-title">Merge Threads</h4>
        </div>
        <div class="modal-body">
            <p>All posts in this thread will be moved to the merged thread</p>
            <fieldset>
                <div class="row">
                    <div class="small-6 column">
                        <legend>Thread ID <em>(Thread id to merge into)</em>: </legend>
                        <input type="text" name="thread_name_search" id="marge_with_threadid" checked="" placeholder="Thread ID"  class="login-form-input"/><br />
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="pg-red headerAdminRed floatright gradualfader" onclick="mergeThreads();" style="margin-left: 5px;">Merge</button>
            <button id="close" class="pg-red headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
        </div>
    </div>
    <!-- END OF MERGE THREADS -->
@endif

<div class="small-12 column">
    <div class="content-holder">
        <div class="content subNav">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span style="font-weight: bold;">{!! $breadCrum !!}</span>
            <span>{!! $threadprefix !!}{{ $thread->title }}</span>
        </div>
    </div>
</div>

@if($thread->open == 0)
    <div class="small-12 column">
        <div class="content-holder">
            <div class="content subNav">
                <div style="text-align:center;">
                    Sorry this thread is closed. You may still view it, but you will be unable to post.
                </div>
            </div>
        </div>
    </div>
@endif

<div class="small-12 medium-12 large-12 column">
    <div class="content-holder">
        <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>

@if($thread_have_poll)
    <div class="small-12 column">
        <div class="inner-content-holder">
            <div class="content">
                <div class="contentHeader headerRed">
                    Poll
                </div>
                <div class="content-ct">
                    @if($have_voted)
                        @if($votes_visible OR $votes_creator)
                            @foreach($answers as $answer)
                                <div style="width: 100%; float: left; padding: 0.4rem;">
                                    <div class="answers_to_see" style="width: {{ $answer['procent'] }}%;"></div>
                                    <span>{{ $answer['text'] }} ({{ $answer['procent'] }}%)</span>
                                </div>
                            @endforeach
                            <span style="padding-left: 0.3rem; padding-top: 0.8rem; float: right; color: #ababab;">{{ $total_amount }} vote(s)</span>
                        @else
                            Answers to this poll have been hidden, only the thread owner can see them.
                        @endif
                    @else
                        @foreach($answers as $answer)
                        <div class="answer_to_choose">
                            <input type="radio" name="poll_ans" class="poll_answer" value="{{ $answer['pollanswerid'] }}" id="{{ $answer['pollanswerid'] }}" />
                            <label for="{{ $answer['pollanswerid'] }}">{{ $answer['text'] }}</label>
                        </div>
                        @endforeach
                        <button class="pg-left pg-grey pg-right" style="margin-top: 0.5rem;" onclick="voteOnPoll();">Vote</button>
                        <span style="padding-left: 1rem; padding-top: 0.8rem; float: left; color: #ababab; ">{{ $total_amount }} vote(s)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<?php $first = 1; ?>
@foreach($posts as $post)
<!-- POST START -->
<div class="small-12 column">
    <div class="post" id="post-{{ $post['postid'] }}" style="opacity: {{ $post['opacity'] }};">
        <div class="content-holder">
            <div class="content">
                <div class="time">
                    <span>
                        {{ $post['time'] }} @if($first == 1) - @if($subscribed) <a onclick="unsubscribe();">Unsubscribe to this thread</a> @else <a onclick="subscribe();">Subscribe to this thread</a> @endif @endif
                    </span>
                    <div style="float: right;margin-top: -10px;">
                        @if($have_mod)
                            <div class="post_number post-checkbox">
                                <input class="post_check" type="checkbox" value="{{ $post['postid'] }}" style="´margin-top: -2px;" />
                            </div>
                        @endif
                        <div class="post_number"><a href="/forum/thread/{{ $thread->threadid }}/page/{{ $current_page }}?postid={{ $post['postid'] }}">#{{ $post['postnr'] }}</a></div>
                    </div>
                </div>
                <!-- POST AVATAR/POSTBIT STARTS -->
                <div class="post_user">
                    @if($post['postAvatarStyle'] == 1)
                        @include('forum.postAvatarStyles.name-top')
                    @elseif($post['postAvatarStyle'] == 2)
                        @include('forum.postAvatarStyles.name-top-inside')
                    @elseif($post['postAvatarStyle'] == 3)
                        @include('forum.postAvatarStyles.name-bottom-inside')
                    @elseif($post['postAvatarStyle'] == 4)
                        @include('forum.postAvatarStyles.name-bottom')
                    @elseif($post['postAvatarStyle'] == 5)
                        @include('forum.postAvatarStyles.name-top-bars-bottom')
                    @else
                        @include('forum.postAvatarStyles.name-top')
                    @endif
                    <div class="post_postbit">
                        <div class="postbit_topic">
                            @if($post['jn'] == false)
                                <div id="datafield">
                                    <b>Joined:</b>
                                    <div id="floatright"> {{ $post['joined'] }} <br /></div>
                                </div>
                            @endif
                            @if($post['ps'] == false)
                                <div id="datafield">
                                    <b>Posts:  </b>
                                    <div id="floatright">{{ number_format($post['posts']) }} <br /></div>
                                </div>
                            @endif
                            @if($post['lk'] == false)
                                <div id="datafield">
                                    <b>Likes:   </b>
                                    <div id="floatright">{{ number_format($post['likes']) }} <br /></div>
                                </div>
                            @endif
                            @if($post['habbo'] != "" AND $post['hh'] == false)
                                <div id="datafield">
                                    <b>Habbo:</b>
                                    <div id="floatright"><a href="https://www.habbo.com/home/{{ $post['habbo'] }}" target="_blank">{{ $post['habbo'] }}</a> <br /></div>
                                </div>
                            @endif
                            @if($post['lb'] == false)
                                <div id="datafield">
                                    <b>XP Level:</b>
                                    <div id="floatright">{{ $post['level_name'] }} ({{ $post['level_pro'] }}%)<br /></div>
                                </div>
                            @endif
                            @if($post['post_badges'] AND count($post['post_badges']) > 0)
                            <?php $getPostbitBadges = \App\Helpers\ForumHelper::getPostbitBadges($post['userid']); ?>
                            <div style="text-align:center;margin-bottom:10px;">
                              @if(!empty($getPostbitBadges))
                                    @foreach($getPostbitBadges as $badge)
                                        @if($badge->name && $badge->name != '')
                                            <img title="<b>{{ $badge->name }}</b> - {{ $badge->description }}" aria-hidden="true" class="hover-box-info" src="{{ asset('_assets/img/website/badges/' . $badge->badgeid . '.gif') }}" style="margin-left: 2px; margin-right: 2px;" />
                                        @endif
                                    @endforeach
                              @endif
                            </div>
                            @endif
                        </div>
                        @if($post['sa'] == false)
                        <div id="iconpadding">
                            @if($post['discord'] != "")
                                <img src="/_assets/img/website/icons/discord.png" class="socialIcon hover-box-info" title="{{ $post['discord'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['discord'] }}"/>
                            @endif
                            @if($post['twitter'] != "")
                                <a href="http://twitter.com/{{ $post['twitter'] }}" target="_blank"><img src="/_assets/img/website/icons/twitter.png" class="socialIcon hover-box-info" title="{{ $post['twitter'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['twitter'] }}"/></a>
                            @endif
                            @if($post['instagram'] != "")
                                <a href="http://instagram.com/{{ $post['instagram'] }}" target="_blank"><img src="/_assets/img/website/icons/instagram.png" class="socialIcon hover-box-info" title="{{ $post['instagram'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['instagram'] }}"/></a>
                            @endif
                            @if($post['snapchat'] != "")
                                <img src="/_assets/img/website/icons/snapchat.png" class="socialIcon hover-box-info" title="{{ $post['snapchat'] }}"data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['snapchat'] }}" />
                            @endif
                            @if($post['lastfm'] != "")
                                <a href="http://last.fm/user/{{ $post['lastfm'] }}" target="_blank"><img src="/_assets/img/website/icons/lastfm.png" class="socialIcon hover-box-info" title="{{ $post['lastfm'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['lastfm'] }}"/></a>
                            @endif
                            @if($post['tumblr'] != "")
                                <a href="http://{{ $post['tumblr'] }}.tumblr.com" target="_blank"><img src="/_assets/img/website/icons/tumblr.png" class="socialIcon hover-box-info" title="{{ $post['tumblr'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['tumblr'] }}"/></a>
                            @endif
                            @if($post['kik'] != "")
                                <img src="/_assets/img/website/icons/kik.png" class="socialIcon hover-box-info" title="{{ $post['kik'] }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ $post['kik'] }}"/>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <!-- POST AVATAR/POSTBIT ENDS -->

                <!-- POST CONTENT START -->
                <div class="post_content">
                    <div class="post_content_text" id="post_content_text_{{ $post['postid'] }}">
                        {!! $post['content'] !!}
                    </div>
                    @if($post['lastedit_clean'] != 0)
                        <div class="post_content_text" id="post_context_edited_{{ $post['postid'] }}" style="margin-top: 20px;">
                            Last @if($post['lasteditby_clean'] == '__blank__') @else edited by <a href="/profile/{!! $post['lasteditby_clean'] !!}" class="web-page">{!! $post['lasteditby'] !!}</a>@endif at {{ $post['lastedit'] }}
                        </div>
                    @endif
                    @if($post['have_likers'] == 0)
                        <div class="post_likes post-likes-line-{{ $post['postid'] }}">
                            {!! $post['likers_strike'] !!}
                        </div>
                    @endif
                    @if($post['signature'] != "")
                        <!-- START SIGNATURE -->
                        <div class="post_content_signature">
                            {!! $post['signature'] !!}
                        </div>
                        <!-- END SIGNATURE -->
                    @endif
                </div>
                <!-- POST CONTENT ENDS -->

                <!-- POST TOOLS (EDIT, LIKE ETC) STARTS -->
                <div class="post_tools">
                    <div class="time">
                        <div class="post-tools-left">
                            <div class="threadTime" style="float:left">
                                @if($have_mod)
                                    @if($post['threadbanned'] == false)
                                        <div class="tool-mark hover-box-info" title="Ban User from Thread" onclick="banfromThread({{ $post['userid'] }}, '{{ $post['username'] }}');">
                                            <i class="fa fa-ban" aria-hidden="true"></i> <!-- BAN FROM THRREAD -->
                                        </div>
                                    @else
                                        <div class="tool-mark hover-box-info" title="Unban User from Thread" onclick="unbanfromThread({{ $post['userid'] }}, '{{ $post['username'] }}');">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i> <!-- UNBAN FROM THREAD -->
                                        </div>
                                    @endif
                                    @if($post['userid'] != Auth::user()->userid)
                                        @if($can_warninf_users)
                                            <div class="tool-mark hover-box-info" title="Give Warning/Infraction" onclick="issueWarnInf({{ $post['postid'] }});">
                                                <i class="fa fa-bell" aria-hidden="true"></i> <!-- WARNINGS OR INFRACTIONS -->
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if($post['userid'] != Auth::user()->userid)
                                    @if($can_report_post)
                                        <div class="tool-mark hover-box-info" title="Report this post!" onclick="reportPost({{ $post['postid'] }}, '{{ $post['username'] }}');">
                                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <!-- REPORT POST -->
                                        </div>
                                    @endif
                                @endif
                                @if($post['userid'] != Auth::user()->userid)
                                    <div class="tool-mark hover-box-info" title="PM" onclick="sendPM({{ $post['userid'] }})">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="post-tools-right">
                            @if($post['can_edit_post'] == true)
                                <div class="tool-mark hover-box-info" title="Edit the post" onclick="editPost({{ $post['postid'] }});">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <!-- EDIT POST -->
                                </div>
                            @endif
                            @if(Auth::user()->userid != $post['userid'])
                                <span class="likes_area_{{ $post['postid'] }}">
                                    @if($post['likes_post'])
                                        <div class="tool-mark hover-box-info" title="Unlike the post!" onclick="unlikePost({{ $post['postid'] }});">
                                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i> <!-- UNLIKE POST -->
                                        </div>
                                    @else
                                        <div class="tool-mark hover-box-info" title="Like the post!" onclick="likePost({{ $post['postid'] }});">
                                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <!-- LIKE POST -->
                                        </div>
                                    @endif
                                </span>
                            @endif
                            @if($thread->open == 1 AND $can_post AND $verified ===1)
                                <div class="tool-mark hover-box-info" title="Quote and reply!" onclick="quotePost({{ $post['postid'] }});">
                                    <i class="fa fa-reply-all" aria-hidden="true"></i> <!-- QUOTE POST -->
                                </div>
                                <div id="multi-{{ $post['postid'] }}" class="tool-mark hover-box-info">
                                    <i title="Multiquote this post!" onclick="addMultiquote({{ $post['postid'] }});" class="fa fa-comment-o" aria-hidden="true"></i> <!-- QUOTE POST -->
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- POST TOOLS ENDS -->
            </div>
        </div>
    </div>
</div>
<!-- POST END -->
<?php $first++; ?>
@endforeach

<div class="small-12 column">
    <div class="content-holder">
        <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>

<div class="small-12 column">
    @if(($thread->open == 1 AND $can_post) OR ($thread->open == 0 AND $can_open_close_thread))
    <div class="content-holder">
      <div class="content">
        <div class="contentHeader headerRed">
            <a href="/forum/bbcodes/list" class="web-page headerLink white_link">
                Stuck Formatting?
            </a>
            <span>New Reply</span>
        </div>
      </div>
        <div class="mainEditor">
        @if($verified === 1 OR $gdpr === 1)
            <textarea id="thread_editor" style="height: 150px; font-size:12px !important;"></textarea>
            <div style="text-align: center;">
                <div id="post_buttons">
                @if($have_mod && $can_open_close_thread && $thread->open == 1) @endif
                    <button class="fullWidth pg-blue headerBlue gradualfader" style="margin-right: 0.5rem; margin-bottom: 0.5rem;" onclick="postPost();">Post</button>
                </div>
                <div id="post_button_settings">
                    @if($have_mod && $can_open_close_thread && $thread->open == 1)
                        Close Thread On Post
                        <input type="checkbox" class="close_thread_on_post" style="margin: 0 0 0 0;" />
                    @endif
                    <br />Hide Signature
                    <input id="hidesig" type="checkbox" style="margin: 0 0 0 0;" />
                </div>
            </div>
            @else
                @if($gdpr === 1) Testies @endif
                @if($verified === 1)<p>You must verify your habbo to use this function! <a href="/usercp/habbo" class="web-page">Click here to verify!</a></p>@endif
            @endif
        </div>
    </div>
    @else
    @endif

    @if($thread->open == 0)
        <div class="content-holder contentpadding">
           <div style="text-align:center;">
              Sorry this thread is closed. You may still view it, but you will be unable to post.
           </div>
        </div>
    @endif

    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
        Users Viewing This Thread
    </div>
            <div class="content-ct">
                @foreach($readers as $reader)
                    <a  href="/profile/{{ $reader['username'] }}/page/1" class="web-page hover-box-info" title="{{ $reader['timeago'] }}">{!! $reader['usernamecolour'] !!}</a>,
                @endforeach
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
        Last 20 users to read this thread
    </div>
            <div class="content-ct">
            <?php $firstone = true; ?>
            @foreach($have_read as $read)
                @if($firstone)
                    <a href="/profile/{{ $read['clean_username'] }}/page/1" class="web-page hover-box-info" title="{{ $read['time'] }}">{!! $read['username'] !!}</a>
                    <?php $firstone = false; ?>
                @else
                    , <a href="/profile/{{ $read['clean_username'] }}/page/1" class="web-page hover-box-info" title="{{ $read['time'] }}">{!! $read['username'] !!}</a>
                @endif
            @endforeach
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var threadtools = function() {
        $('#thread_tools').fadeOut();
        $('#thread_tools_options').fadeIn();
    }

    var posttools = function() {
        $('#thread_tools').fadeOut();
        $('#post_tools_options').fadeIn();
    }

    var stopEditingThreadTools = function() {
        $('#thread_tools').fadeIn();
        $('#thread_tools_options').fadeOut();
        $('.removes').remove();
   }

   var stopEditingPostTools = function() {
       $('#thread_tools').fadeIn();
       $('#post_tools_options').fadeOut();
       $('.removes').remove();
  }

    var grabSave = function () {
      console.log("grabbing");
      $("#thread_editor").htmlcode(urlRoute.getStorage('new-post-{{ $thread->threadid }}', 900));
      $('.mainEditor .mswitch').trigger('click', $('.mainEditor .mswitch').trigger('click'));
    }

  $(document).ready(function(){
      $(document).foundation();
      @if( isset($_GET['postid']) )
      if($("#post-{{ $_GET['postid'] }}").length){
          $('html,body').animate({
              scrollTop: $("#post-{{ $_GET['postid'] }}").offset().top - 50
          });
      } else {
          $('html,body').animate({
              scrollTop: $(".post").offset().top - 50
          });
      }

      @else
      if($("#post-{{ $last_post }}").length){
          $('html,body').animate({
              scrollTop: $("#post-{{ $last_post }}").offset().top - 50
          });
      } else {
          $('html,body').animate({
              scrollTop: $(".post").offset().top - 50
          });
      }
      @endif

      var multi = JSON.parse(urlRoute.getStorage('multiquote-{{ $thread->threadid }}',900));
      if(multi){

          for(var key in multi){
            var post = key.replace('post-','');
            $('#multi-'+post).html(`
                <i title="Multiquote this post!" onclick="deMultiquote(` + post + `);" class="fa fa-comment" aria-hidden="true"></i> <!-- QUOTE POST -->
              `);
          }
        }


  });

  var main_editor_event,
      edit_editor_event,
      editor;

  @if($verified===1)
  $(document).ready(function() {
    $(document).foundation();

    console.log(typeof(boringCallBack));

    var wbbOpt = {
  		allButtons: {
  			quote: {
  				transform: {
  					'<div class="quote">{SELTEXT}</div>':'[quote]{SELTEXT}[/quote]',
  					'<div class="quote postid-{POSTID}"><cite>{AUTHOR}</cite> wrote:</cite>{SELTEXT}</div>':'[quote={AUTHOR};{POSTID}]{SELTEXT}[/quote]'
  				}
  			}
  		},
      callBackFunc: function(e) {
        if(e.which == 83 && e.altKey) {
          e.preventDefault();
          postPost();
        }

        urlRoute.setStorage('new-post-{{ $thread->threadid }}', $("#thread_editor").getBBCode());

      },
      onlyBBmode: @if($bbmode) true @else false @endif
  	};

    var editor = $("#thread_editor").wysibb(wbbOpt);



    if(urlRoute.getStorage('new-post-{{ $thread->threadid }}',900) !== null){
        $('#post_buttons').append(`
            <button class="fullWidth pg-blue headerAdminRed gradualfader" style="margin-right: 0.5rem;" onclick="grabSave();">Grab Save</button><br /><br />

        `);
    }
  });
  @endif

  var old_post_id = 0;
  var old_post_content = "";
  var threadid = {{ $thread->threadid }};




  @if($subscribed)
    var unsubscribe = function() {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/thread/unsubscribe',
        type: 'post',
        data: {threadid:threadid},
        success: function(data) {
          urlRoute.ohSnap('<span class=\"alert-title\">Oh man!</span><br />You have unsubscribed to this thread!', 'blue');
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/{{ $current_page }}');
        }
      });
    }
  @else
    var subscribe = function() {
       $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/thread/subscribe',
        type: 'post',
        data: {threadid:threadid},
        success: function(data) {
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/{{ $current_page }}');
          urlRoute.ohSnap('You have subscribed to this thread!', 'blue');
        }
      });
    }
  @endif

  @if(!$have_voted)
    var voteOnPoll = function() {
      if ($('input[name=poll_ans]:checked').length > 0) {
        var pollanswerid = $('input[name=poll_ans]:checked').val();

        $.ajax({
          url: urlRoute.getBaseUrl() + 'forum/poll/vote',
          type: 'post',
          data: {threadid:threadid, pollanswerid:pollanswerid},
          success: function(data) {
            if(data['response'] == true) {
              urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
              urlRoute.ohSnap('<span class=\"alert-title\">Aww yeah!</span><br />You have successfully voted!', 'green');
            } else {
              urlRoute.ohSnap('<span class=\"alert-title\">Really?!</span><br />You have already voted!','red');
            }
          }
        });
      } else {
        urlRoute.ohSnap('You need to pick an answer before voting!', 'blue');
      }
    }
  @endif

  @if($can_report_post)
    var warning_postid = 0;
    var thread_banuserid = 0;

    var banfromThread = function(userid, username) {
      thread_banuserid = userid;

      $('#banUsername').html(username);
      $('#ban_thread').foundation('open');
    }

    var banuserfromthread = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'forum/moderation/banfromThread',
            type: 'post',
            data: {threadid:threadid, thread_banuserid:thread_banuserid},
            success: function(data) {
                if(data['response'] == true) {
                    $('#ban_thread').foundation('close');
                    urlRoute.ohSnap('User banned from thread!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var unbanfromThread = function(userid, username) {
      thread_unbanuserid = userid;

      $('#unbanUsername').html(username);
      $('#unban_thread').foundation('open');
    }

    var unbanuserfromthread = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'forum/moderation/unbanfromThread',
            type: 'post',
            data: {threadid:threadid, thread_unbanuserid:thread_unbanuserid},
            success: function(data) {
                if(data['response'] == true) {
                    $('#unban_thread').foundation('close');
                    urlRoute.ohSnap('User unbanned from thread!', 'green');
                    urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var issueWarnInf = function(postid) {
      warning_postid = postid;
      $('#warning_post').foundation('open');
    }

    var giveWarnInf = function() {
        var reason = $('#inputReasonif').val();
        var type = $('#inputTypeif').val();
        var pm = $('#inputPmif').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'forum/moderation/inf/war',
            type: 'post',
            data: {reason:reason, type:type, pm:pm, warning_postid:warning_postid},
            success: function(data) {
                if(data['response'] == true) {
                    $('#warning_post').foundation('close');
                    urlRoute.ohSnap('Infraction/Warning sent!', 'green');
                    } else {
                    urlRoute.ohSnap(data['message'], 'red');
                    }
                }
        });
    }
  @endif
  @if($can_report_post)
    var report_postid = 0;

    var reportPost = function(postid, username) {
      report_postid = postid;

      $('#moderationUsername').html(username);
      $('#report_post').foundation('open');

    }

    var reportPoster = function() {
      var reason = $('#reason_for_report').val();
      var pagenumber = "{{ $current_page }}";

      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/report/post',
        type: 'post',
        data: {report_postid:report_postid, reason:reason, pagenumber:pagenumber},
        success: function(data) {
          if(data['response'] == true) {
            $('#reason_for_report').val("");
            $('#report_post').foundation('close');
            urlRoute.ohSnap('<span class=\"alert-title\">Thanks!</span><br />Your report has been sent!', 'green');
          } else {
            urlRoute.ohSnap(data['message'], 'red');
          }
        }
      });
    }
  @endif

  @if($can_move_posts)

    var movePosts = function() {
        var postids = [];
        var fail = false;

        $('input:checkbox.post_check').each(function() {
          var value = parseInt((this.checked ? $(this).val() : ""));

          if(value === {{ $thread->firstpostid }}) {
              fail = true;
          } else {
              postids.push(value);
          }
        });

        var targetId = $('#threadList option:selected').val();
        if(targetId === '0') {
            urlRoute.ohSnap('Pick a thread!', 'red');
            fail = true;
        }
        if(fail || postids.length === 0) {
            urlRoute.ohSnap('You can\'t move the first post', 'red');
        } else {
            $.ajax({
              url: urlRoute.getBaseUrl() + 'forum/move/posts',
              type: 'post',
              data: {postids:postids, targetId:targetId, currentId:{{$thread->threadid}}},
              success: function(data) {
                if(data['response'] == true) {
                  urlRoute.ohSnap('<span class=\"alert-title\">Yeah Boy!</span><br />The posts have been moved moved!', 'green');
                  $('#move_posts').foundation('close');
                  urlRoute.loadPage('/forum/thread/'+targetId+'/page/1');
                } else {
                  urlRoute.ohSnap(data['message'], 'red');
                }
              }
            });
        }
    }
  @endif

  @if($can_merge_threads)
    var mergeThreads = function() {
      var mergeid = parseInt($('#marge_with_threadid').val());
      var threadid = {{ $thread->threadid }};

      $('#merge_thread').foundation('close');
      urlRoute.loadPage('/forum/merge/'+threadid+'/with/'+mergeid);
    }
  @endif

  @if($can_approve_unapprove_threads)
    var approveThread = function() {
        $.ajax({
          url: urlRoute.getBaseUrl() + 'forum/approve/thread',
          type: 'post',
          data: {threadid:threadid},
          success: function(data) {
            urlRoute.ohSnap('<span class=\"alert-title\">Ahoy Captain!</span><br />This thread has been approved!', 'green');
            urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
          }
        })
    }
    var unapproveThread = function() {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/unapprove/thread',
            type: 'post',
            data: {threadid:threadid},
            success: function(data) {
              urlRoute.ohSnap('<span class=\"alert-title\">Ahoy Captain!</span><br />This thread has been unapproved!', 'green');
              urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
            }
          })
        }
    @endif

    var stickyThread = function() {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/sticky/thread',
        type: 'post',
        data: {threadid:threadid},
        success: function(data) {
          urlRoute.ohSnap('<span class=\"alert-title\">Done and dusted!</span><br />This thread has been successfully stickied!', 'green');
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
        }
      })
    }

    var unstickyThread = function() {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/unsticky/thread',
        type: 'post',
        data: {threadid:threadid},
        success: function(data) {
          urlRoute.ohSnap('<span class=\"alert-title\">Done and dusted!</span><br />This thread has been successfully unstickied!', 'green');
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
        }
      })
    }

  @if($can_open_close_thread)
    var openThread = function() {
      var type = "open";
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/openclose/thread',
        type: 'post',
        data: {threadid:threadid, type:type},
        success: function(data) {
          urlRoute.ohSnap('Thread Open!', 'green');
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
        }
      })
    }

    var closeThread = function() {
      var type = "close";
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/openclose/thread',
        type: 'post',
        data: {threadid:threadid, type:type},
        success: function(data) {
          urlRoute.ohSnap('Thread Closed!','green');
          urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
        }
      })
    }
  @endif

  @if($can_change_owner)
    var change_type = 0; //0 = posts, 1 = thread
    var setChangeType = function(type) {
      change_type = type;
      $('#change_owner').foundation('open');
    }

    var changeOwner = function() {
      if(change_type == 0) {
        var postids = "";
        var first_id = 1;

        $('input:checkbox.post_check').each(function() {
          var value = (this.checked ? $(this).val() : "");

          if(first_id == 1) {
            postids = value;
            first_id = 0;
          } else {
            postids = postids + ',' + value;
          }

        });
      } else {
        var postids = {{ $thread->firstpostid }};
      }

      var username = $('#change_owner_new_name').val();

      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/change/post/owner',
        type: 'post',
        data: {postids:postids, threadid:threadid, username:username},
        success: function(data) {
          if(data['response'] == true) {
            $('#change_owner').foundation('close');
            urlRoute.ohSnap('Post Owner Changed!', 'green');
            urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
          } else {
            urlRoute.ohSnap(data['message'], 'red');
          }
        }
      });
    }
  @endif

  @if($can_soft_delete OR $can_hard_delete)
    var delete_type = 0; //0 = posts, 1 = thread

    var setType = function(type) {
      delete_type = type;
      $('#delete_posts').foundation('open');

    }

    var unDeleteSelected = function(type) {
      if(type == 0) {
        var postids = "";
        var first_id = 1;

        $('input:checkbox.post_check').each(function() {
          var value = (this.checked ? $(this).val() : "");

          if(first_id == 1) {
            postids = value;
            first_id = 0;
          } else {
            postids = postids + ',' + value;
          }

        });
      } else {
        var postids = {{ $thread->firstpostid }};
      }

      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/undelete/posts',
        type: 'post',
        data: {postids:postids, threadid:threadid},
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
            urlRoute.ohSnap('Undeleted Post!', 'green');
          } else {
            urlRoute.ohSnap('Something went wrong!', 'red');
          }
        }
      });
      $('#delete_posts').foundation('close');
    }

    var deleteSelected = function() {

      // If deleting a post
      if(delete_type == 0) {

        // Set deletion type to "post"
        var deletionType = "post";

        var postids = "";
        var first_id = 1;
        $('input:checkbox.post_check').each(function() {
          var value = (this.checked ? $(this).val() : "");
          if(first_id == 1) {
            postids = value;
            first_id = 0;
          } else {
            postids = postids + ',' + value;
          }
        });

      // If deleting a thread
      } else {

        // Set deletion type to "thread"
        var deletionType = "thread";

        var postids = {{ $thread->firstpostid }};
      }
      var type = $('input[name=delete_type]:checked').val();
      if(type != 0) {
        if(type != 1) {
          type = 0;
        }
      }
      if(type != 1) {
        if(type != 0) {
          type = 0;
        }
      }
      if(confirm('Are you sure you want to delete this ' + deletionType + '?')) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/delete/posts',
        type: 'post',
        data: {postids:postids, type:type, threadid:threadid},
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.ohSnap('Deleted Post!', 'green');
            if(data['stay'] == 1) {
              urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1');
            } else {
              urlRoute.loadPage('/forum/category/{{ $thread->forumid }}/page/1');
            }
          } else {
            urlRoute.ohSnap('Something went wrong!', 'red');
          }
        }
      });
      }
      $('#delete_posts').foundation('close');
    }

  @endif

  var sendPM = function(userid) {
    urlRoute.loadPage('/usercp/pm?userid='+ userid +'');
  }

  var likePost = function(postid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/like/post',
      type: 'post',
      data: {postid:postid},
      success: function(data) {
        if(data['response'] == true) {
          $('.likes_area_'+postid).html('<div class="tool-mark hover-box-info" title="Unlike the post!" onclick="unlikePost(' + postid +');"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></div>');

          urlRoute.ohSnap('You liked the post!', 'blue');

          if(data['likers']['have_likers'] == 0) {
            if($('.post-likes-line-' + postid).length) {
              $('.post-likes-line-' + postid).html(data['likers']['likers_strike']);
              $('.post-likes-line-' + postid).fadeIn();
            } else {
              $('#post_context_edited_'+ postid).append('<div class="post_likes post-likes-line-'+postid+'">'+data['likers']['likers_strike']+'</div>');
            }
          } else {
            $('.post-likes-line-' + postid).fadeOut();
          }
        }
      }
    });
  }

  var unlikePost = function(postid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/unlike/post',
      type: 'post',
      data: {postid:postid},
      success: function(data) {
        if(data['response'] == true) {
          $('.likes_area_'+postid).html('<div class="tool-mark hover-box-info" title="Like the post!" onclick="likePost('+postid+');"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></div>');

          urlRoute.ohSnap('You unliked the post!', 'blue');
          if(data['likers']['have_likers'] == 0) {
            if($('.post-likes-line-' + postid).length) {
              $('.post-likes-line-' + postid).html(data['likers']['likers_strike']);
              $('.post-likes-line-' + postid).fadeIn();
            } else {
              $('#post_context_edited_'+ postid).append('<div class="post_likes post-likes-line-'+postid+'">'+data['likers']['likers_strike']+'</div>');
            }
          } else {
            $('.post-likes-line-' + postid).fadeOut();
          }
        }
      }
    });
  }

  @if(($thread->open == 1 AND $can_post) OR ($thread->open == 0 AND $can_open_close_thread))
  @if($verified === 1)
    var quotePost = function(postid) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/get/post',
        type: 'post',
        data: {postid:postid},
        success: function(data) {
          if(data['response'] == true) {
            var current_data = $('#thread_editor').htmlcode();
            var new_data = "";
            var multi = JSON.parse(urlRoute.getStorage('multiquote-{{ $thread->threadid }}',900));

            if(multi){
              if(multi['post-'+postid]){
                for(var key in multi){
                  new_data += multi[key];
                }
              } else {
                  for(var key in multi){
                    new_data += multi[key];
                  }
                  new_data += `[quotepost=` + data['postbinding'] + `]` + data['postData'] + `[/quotepost]\n\n`;
              }
            } else {
              new_data += `[quotepost=` + data['postbinding'] + `]` + data['postData'] + `[/quotepost]\n\n`;
            }
            urlRoute.setStorage('multiquote-{{ $thread->threadid }}', null);
            @if($bbmode)
                $('#thread_editor').bbcode(new_data);
            @else
                $('#thread_editor').htmlcode(new_data);
            @endif
            $('.mainEditor .mswitch').trigger('click', $('.mainEditor .mswitch').trigger('click'));
            urlRoute.ohSnap('Quote is now in the editor!','blue');
          } else {
            urlRoute.ohSnap('Something went wrong!', 'red');
          }
        }
      });
    }
    var postPost = function() {
      var threadid = {{ $thread->threadid }};
      var content = $("#thread_editor").bbcode();
      var hideSig = $("#hidesig").is(":checked") ? 0 : 1;
      $('.post-button').css("display", "none");
      $.ajax({
        url: urlRoute.getBaseUrl() + 'forum/post/new',
        type: 'post',
        data: {threadid:threadid, content:content, hideSig:hideSig},
        success: function(data) {
          if(data['response'] == true) {
            @if($have_mod && $can_open_close_thread && $thread->open == 1)
                if($('.close_thread_on_post').length && $('.close_thread_on_post').is(":checked")) {
                    closeThread();
                } else {
                    urlRoute.loadPage(data['path']);
                    urlRoute.ohSnap('<span class=\"alert-title\">Great Success!</span><br />You have successfully posted!', 'green');
                }
            @else
                urlRoute.loadPage(data['path']);
                urlRoute.ohSnap('<span class=\"alert-title\">Great Success!</span><br />You have successfully posted!', 'green');
            @endif
          } else {
            urlRoute.ohSnap(data['message'], 'red');
          }

          $('.post-button').css("display", "block");
        },
        error: function() {
          urlRoute.ohSnap('<span class=\"alert-title\">Aww man!</span><br />Something went wrong!', 'red');
          $('.post-button').css("display", "block");
        }
      });
    }
  @endif
  @endif

  var cancelEdit = function() {
    $('#post_content_text_'+old_post_id).html(old_post_content);
      old_post_content = "";
      old_post_id = 0;
  }

  var postEdit = function() {
    var content = $('#post_editor').bbcode();
    var postid = old_post_id;

    $('#post_content_text_'+postid).html("");
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/post/edit',
      type: 'post',
      data: {postid:postid, content:content},
      success: function(data) {

        if(data['response'] == true) {
          $('#post_content_text_'+postid).html(data['content']);
        }
      }
    });
  }

  var editPost = function(postid) {
    if(old_post_id == 0) {
      old_post_content = $('#post_content_text_'+postid).html();
      old_post_id = postid;
    } else {
      $('#post_content_text_'+old_post_id).html(old_post_content);
      old_post_content = $('#post_content_text_'+postid).html();
      old_post_id = postid;
    }

    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/get/edit/post',
      type: 'post',
      data: {postid:postid},
      success: function(data) {
        if(data['response'] == true) {
          $('#post_content_text_'+postid).html('<textarea id="post_editor" style="font-size:12px !important;">' + data['content'] + '</textarea> <br><a><button class="pg-red headerBlue floatright gradualfader fullWidth topBottom barFix" onclick="postEdit();">Save Post</button></a><a><button class="pg-blue headerAdminRed gradualfader fullWidth topBottom" onclick="cancelEdit();">Cancel</button></a>');
          $('#post_editor').wysibb({onlyBBmode: @if($bbmode) true @else false @endif});
          edit_editor_event = $('.post_content .wysibb-body').keyup(function(e){
            if(e.key === 's' && e.altKey) {
              e.preventDefault();
              postEdit();
            }
          });
        }
      }
    });
  }

  var selectedUserPosts = function() {
    var username = $('#thread-form-specificposts').val();
    urlRoute.loadPage('/forum/thread/{{ $thread->threadid }}/page/1/'+username)
  }

  @if($verified === 1)
  var addMultiquote = function(postid) {
    var multisString;
    console.log('multiquote');
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/get/post',
      type: 'post',
      data: {postid:postid},
      success: function(data) {
        if(data['response'] == true) {
            if(multisString = urlRoute.getStorage('multiquote-{{ $thread->threadid }}',900)){
              multis = JSON.parse(multisString);
              multis['post-'+postid] = "[quotepost=" + data['postbinding'] + "]" + data['postData'] + "[/quotepost]\n\n";
              console.log('found');
            } else {
              console.log('not found');
              multis = {};
              multis['post-'+postid] = `[quotepost=` + data['postbinding'] + `]` + data['postData'] + `[/quotepost]\n\n`;
            }
            urlRoute.setStorage('multiquote-{{ $thread->threadid}}',JSON.stringify(multis));


          /*  var current_data = (urlRoute.getStorage('multiquote-{{ $thread->threadid }}') !== null) ? urlRoute.getStorage('multiquote-{{ $thread->threadid}}',900) : "";
            var new_data = current_data + `[quotepost=` + data['postbinding'] + `]` + data['postData'] + `[/quotepost]\n\n`;
          */

          $('#multi-'+postid).html(`
            <i title="Multiquote this post!" onclick="deMultiquote(` + postid + `);" class="fa fa-comment" aria-hidden="true"></i> <!-- QUOTE POST -->

            `);

        } else {
          urlRoute.ohSnap('<span class=\"alert-title\">Aww man!</span><br />Something went wrong!', 'red');
        }
      }
    });
  }

  var deMultiquote = function(postid) {
    console.log('dequote');
    $.ajax({
      url: urlRoute.getBaseUrl() + 'forum/get/post',
      type: 'post',
      data: {postid:postid},
      success: function(data) {
        if(data['response'] == true) {
          /*var current_data = (urlRoute.getStorage('multiquote-{{ $thread->threadid }}') !== null) ? urlRoute.getStorage('multiquote-{{ $thread->threadid}}',900) : "";
          var new_data = current_data.replace(`[quotepost=` + data['postbinding'] + `]` + data['postData'] + `[/quotepost]\n\n`,'');
          urlRoute.setStorage('multiquote-{{ $thread->threadid}}',new_data);
          */
          var multis = JSON.parse(urlRoute.getStorage('multiquote-{{ $thread->threadid }}',900));
          delete multis['post-'+postid];
          urlRoute.setStorage('multiquote-{{ $thread->threadid}}',JSON.stringify(multis));

          $('#multi-'+postid).html(`
            <i title="Multiquote this post!" onclick="addMultiquote(` + postid + `);" class="fa fa-comment-o" aria-hidden="true"></i> <!-- QUOTE POST -->

            `);

        } else {
          urlRoute.ohSnap('<span class=\"alert-title\">Aww man!</span><br />Something went wrong!', 'red');
        }
      }
    });
  }
  @endif

  var destroy = function() {
    admintools = null;
    unsubscribe = null;
    subscribe = null;
    voteOnPoll = null;
    reportPost = null;
    reportPoster = null;
    mergeThreads = null;
    openThread = null;
    closeThread = null;
    setChangeType = null;
    changeOwner = null;
    setType = null;
    unDeleteSelected = null;
    deleteSelected = null;
    likePost = null;
    unlikePost = null;
    quotePost = null;
    postPost = null;
    cancelEdit = null;
    postEdit = null;
    editPost = null;
    selectedUserPosts = null;
    stickyThread = null;
    unstickyThread = null;
    movePosts = null;
    altSsave = null;
    main_editor_event;
    edit_editor_event;
  }
</script>
