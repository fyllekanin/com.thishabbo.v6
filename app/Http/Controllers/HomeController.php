<?php
namespace App\Http\Controllers;

use Cache;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\UserHelper;
use App\Helpers\QuestsHelper;
use App\Helpers\ForumHelper;
use App\Helpers\StaffHelper;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use Image;
use File;

class HomeController extends BaseController {

    public function getLeague() {
        $returnHTML = view('league')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getMegarate() {
        $bbcodes = DB::table('bbcodes')->get();
        $posts = DB::table('posts')->where('threadid', 590256)->where('userid', 5165)->orderBy('postid', 'DESC')->get();

        $items = [];
        foreach ($posts as $post) {
            if (preg_match('/#[0-9]+/', $post->content)) {
                $content = ForumHelper::fixContent($post->content);
                $content = ForumHelper::replaceEmojis($content);
                $content = ForumHelper::bbcodeParser($content, $bbcodes);
                $content = nl2br($content);
                $content = ForumHelper::replaceEmojis($content);
                
                $items[] = [
                    'content' => $content,
                    'time' => ForumHelper::getTimeInDateLong($post->dateline)
                ];
            }
        }

        $returnHTML = view('megarate')
            ->with('items', $items)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getVxProgress() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://54.37.17.105:8080/a/changes/?q=project:com.thishabbo.web+status:merged&n=25&O=81');
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
        curl_setopt($curl, CURLOPT_USERPWD, 'tovven:' . 'RZaQ0BzqqOlyYiCJefFbXwXApnQxkfJ2lpMWpNIGMw');
        $result = json_decode(str_replace(")]}'", "", curl_exec($curl)));

        $list = [];

        foreach($result as $item) {
            $list[] = [
                'subject' => $item->subject,
                'submitted' => $item->submitted,
                'insertions' => $item->insertions,
                'deletions' => $item->deletions,
                'user' => $item->owner->name
            ];
        }

        $returnHTML = view('vx-progress')
            ->with('list', $list)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRawr() {
        // To be determined
    }

    public function flagArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $type = $request->input('type');
        $reason = $request->input('reason');

        $article = DB::table('articles')->where('articleid', $articleid)->first();
        if (count($article) == 0) {
            return response()->json(array('success' => false, 'message' => 'This article does not exist'));
        }

        if ($type == '1') {
            DB::table('flaged_articles')->insert([
                'userid' => Auth::user()->userid,
                'articleid' => $articleid,
                'reason' => $reason,
                'type' => $article->available,
                'dateline' => time()
            ]);
            return response()->json(['success' => true, 'message' => 'Success']);
        } elseif ($type == '2') {
            if (DB::table('posts')->where('userid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count() > 0) {
                return response()->json(['success' => true, 'message' => 'You are flagging to quick']);
            }
            if (!isset($reason) || strlen($reason) == 0) {
                return response()->json(['success' => true, 'message' => 'Reason needs to be present']);
            }
            if ($article->userid == Auth::user()->userid) {
                return response()->json(['success' => true, 'message' => 'You can\'t report yourself!']);
            }

            $modforums = DB::table('moderation_forums')->orderBy('mfid', 'DESC')->get();
            $user = UserHelper::getUser($article->userid);
            $time = time();

            if (count($user)) {
                $date = date('d/m/y', $time);
                $article_name = $article->title;
                $reporter = Auth::user()->username;
                $reported = $user->username;
                $type = "article";

                $content = '[intern_link=/profile/' . $reporter . '/page/1]' . $reporter . '[/intern_link] has reported an article in ' . $article_name . '

                [b]User reported:[/b] [intern_link=/profile/' . $reported . '/page/1]' . $reported . '[/intern_link]
                [b]Thread:[/b] [intern_link]/article/' . $article->articleid . '[/intern_link]

                [b]Reason[/b]:
                [quote]' . $reason . '[/quote]';

                foreach ($modforums as $modforum) {
                    $title = $modforum->title;
                    $forumid = $modforum->forumid;

                    $title = str_replace("{date}", $date, $title);
                    $title = str_replace("{thread}", $article_name, $title);
                    $title = str_replace("{reporter}", $reporter, $title);
                    $title = str_replace("{reported}", $reported, $title);
                    $title = str_replace("{type}", $type, $title);

                    $postid = DB::table('posts')->insertGetId([
                        'threadid' => 0,
                        'username' => $reporter,
                        'userid' => Auth::user()->userid,
                        'dateline' => $time,
                        'lastedit' => 0,
                        'lastedituser' => 0,
                        'content' => $content,
                        'ipaddress' => $request->ip(),
                        'visible' => 1
                    ]);

                    $threadid = DB::table('threads')->insertGetId([
                        'title' => $title,
                        'forumid' => $modforum->forumid,
                        'postuserid' => Auth::user()->userid,
                        'dateline' => $time,
                        'firstpostid' => $postid,
                        'lastpost' => $time,
                        'lastpostid' => $postid,
                        'lastedited' => 0,
                    ]);

                    DB::table('forums')->where('forumid', $modforum->forumid)->update([
                        'posts' => DB::raw('posts+1'),
                        'threads' => DB::raw('threads+1'),
                        'lastpost' => $time,
                        'lastpostid' => $postid,
                        'lastposterid' => Auth::user()->userid,
                        'lastthread' => $time,
                        'lastthreadid' => $threadid
                    ]);

                    DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);
                    $time += 10;
                }

                $message = "Success!";
                $response = true;
            } else {
                $message = "This user no longer exists!";
            }
        }
        return response()->json(array('success' => true, 'message' => $message));
    }

    public function getMaintenance()
    {
        $mt = DB::table('maintenances')->where('active', 1)->first();
        $reason = count($mt) ? $mt->reason : '';
        $returnHTML = view('errors.maintenance')->with('reason', $reason)->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    /**
     * Returns the visitor messages converstation between two people
     * @param {String} username1        - First user
     * @param {String} username2        - Second user
     * @param {Integer} pagenr          - Page number
     */
    public function getConverstation($username1, $username2, $pagenr)
    {
        $bbcodes = DB::table('bbcodes')->get();
        $user1 = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username1)])->first();
        $user2 = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username2)])->first();

        if (count($user1) and count($user2)) {
            $pagesx = DB::table('visitor_messages')->whereRaw('(postuserid = ? AND reciveuserid = ? AND visible = 1) OR (postuserid = ? AND reciveuserid = ? AND visible = 1)', [$user1->userid, $user2->userid, $user2->userid, $user1->userid])->count();

            if ($pagenr <= 0) {
                $pagenr = 1;
            }

            $userid = Auth::check() ? Auth::user()->userid : 0;
            $take = 20;
            $skip = 0;

            $pagesx = DB::table('visitor_messages')->whereRaw('(postuserid = ? AND reciveuserid = ? AND visible = 1) OR (postuserid = ? AND reciveuserid = ? AND visible = 1)', [$user1->userid, $user2->userid, $user2->userid, $user1->userid])->count();

            $pages = ceil($pagesx / $take);

            if ($pagenr > $pages) {
                $pagenr = $pages;
            }

            $paginator = array(
                'total' => $pages,
                'current' => $pagenr,
                'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
                'previous_exists' => $pagenr - 1 < 1 ? false : true,
                'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
                'next_exists' => $pagenr + 1 > $pages ? false : true,
                'gap_forward' => $pagenr + 5 < $pages ? true : false,
                'gap_backward' => $pagenr - 5 > 1 ? true : false
            );

            if ($pagenr >= 2) {
                $skip = $take * $pagenr - $take;
            }

            $temps = DB::table('visitor_messages')->whereRaw('(postuserid = ? AND reciveuserid = ? AND visible = 1) OR (postuserid = ? AND reciveuserid = ? AND visible = 1)', [$user1->userid, $user2->userid, $user2->userid, $user1->userid])->take($take)->skip($skip)->orderBy('vmid', 'DESC')->get();

            $visitormessages = [];
            $can_report_post = false;

            $check = DB::table('moderation_forums')->count();

            if ($check > 0) {
                $can_report_post = true;
            }
            foreach ($temps as $temp) {
                $message = ForumHelper::fixContent($temp->message);
                $message = ForumHelper::bbcodeParser($message, $bbcodes);
                $message = ForumHelper::replaceEmojis($message);

                $message = view('layout.vms.vms-html')
                    ->with('avatar', UserHelper::getAvatar($temp->postuserid))
                    ->with('userid', $temp->postuserid)
                    ->with('username', UserHelper::getUsername($temp->postuserid))
                    ->with('clean_username', UserHelper::getUsername($temp->postuserid, true))
                    ->with('time', ForumHelper::timeAgo($temp->dateline))
                    ->with('message', $message)
                    ->with('vmid', $temp->vmid)
                    ->with('can_delete_vm', UserHelper::haveGeneralModPerm($userid, 8))
                    ->with('can_infract_vm', UserHelper::haveGeneralModPerm($userid, 512))
                    ->with('can_report_post', $can_report_post)
                    ->render();

                $visitormessages[] = $message;
            }

            $user = [
                'userid1' => $user1->userid,
                'username1' => $user1->username,
                'userid2' => $user2->userid,
                'username2' => $user2->username,
                'userid' => $userid == $user1->userid ? $user2->userid : $user1->userid
            ];

            $is_mod = UserHelper::haveGeneralModPerm($userid, 1);

            $tms = DB::table('infraction_reasons')->orderBy('text', 'ASC')->get();
            $infraction_reasons = [];
            foreach ($tms as $tm) {
                $array = [
                    'infractionrsnid' => $tm->infractionrsnid,
                    'reason' => $tm->text,
                    'points' => $tm->points
                ];
                $infraction_reasons[] = $array;
            }

            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/conversation/' . $user1->username . '/' . $user2->username . '/page/')->render();
            $returnHTML = view('conversation')
                ->with('pagi', $pagi)
                ->with('visitormessages', $visitormessages)
                ->with('can_infract_vm', UserHelper::haveGeneralModPerm($userid, 512))
                ->with('can_report_post', $can_report_post)
                ->with('infraction_reasons', $infraction_reasons)
                ->with('user', $user)
                ->with('is_mod', $is_mod)
                ->render();

            return response()->json(['success' => true, 'returnHTML' => $returnHTML]);
        }
        return redirect()->route('getErrorPerm');
    }


    /*
     * Post an comment on an article
     * @param {Object} Request
     * Request Object
     * - article id
     * - content
     */
    public function postArticleComment(Request $request)
    {
        if (Auth::user()->habbo_verified == 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => "You must verify your habbo to comment!"));
        }

        if (Auth::user()->gdpr == 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => "You must accept our GDPR Policy on the homepage to post a comment!"));
        }

        $articleid = $request->input('articleid');
        $content = $request->input('content');
        $parent = $request->input('parent');

        $response = false;
        $message = "Something went wrong!";

        $article = DB::table('articles')->where('articleid', $articleid)->first();

        $username = "";
        $clean_username = "";

        if (count($article)) {
            $message = "counted article";
            if (strlen($content) > 0) {
                $check = DB::table('article_comments')->where('userid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count();
                $userid = Auth::user()->userid;
                if ($check == 0) {
                    $tagged_userids = array();

                    if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_]{1,}))/', $content, $match)) {
                        foreach ($match['UsernameOnly'] as $usr) {
                            $user = DB::table('users')
                                ->whereRaw('lower(username) LIKE ?', [strtolower($usr)])
                                ->where('userid', '!=', $userid)
                                ->first();

                            if (count($user)) {
                                $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                                if (!in_array($user->userid, $tagged_userids)) {
                                    $tagged_userids[] = array('userid' => $user->userid, 'content' => 11);
                                }
                            }
                        }
                    }

                    $parentUser = DB::table('article_comments')->where('commentid', $parent)->value('userid');
                    if ($parent != 0 && $parentUser != Auth::user()->userid) {
                        DB::table('notifications')->insert([
                            'postuserid' => Auth::user()->userid,
                            'reciveuserid' => $parentUser,
                            'content' => 14,
                            'contentid' => $articleid,
                            'dateline' => time(),
                            'read_at' => 0
                        ]);
                    }

                    $commentid = DB::table('article_comments')->insertGetId([
                        'articleid' => $articleid,
                        'parentid' => $parent,
                        'userid' => Auth::user()->userid,
                        'content' => $content,
                        'dateline' => time()
                    ]);

                    DB::table('livewall')->insert([
                        'userid' => Auth::user()->userid,
                        'forum' => 0,
                        'forumid' => 0,
                        'item_id' => $articleid,
                        'item_type' => 4,
                        'message' => 'posted',
                        'dateline' => time()
                    ]);

                    /* SEND NOTIFICATION TO ALL TAGGED USERS */
                    foreach ($tagged_userids as $tagged) {
                        $check = DB::table('notifications')
                            ->where('reciveuserid', $tagged['userid'])
                            ->where('content', $tagged['content'])
                            ->where('contentid', $commentid)
                            ->count();

                        $user = UserHelper::getUser($tagget['userid']);
                        if ($check == 0 && count($user)) {
                            DB::table('notifications')->insert([
                                'postuserid' => $userid,
                                'reciveuserid' => $tagged['userid'],
                                'content' => $tagged['content'],
                                'contentid' => $commentid,
                                'dateline' => time(),
                                'read_at' => 0
                            ]);
                        }
                    }

                    DB::table('users')->where('userid', Auth::user()->userid)->update([
                        'commentcount' => DB::raw('commentcount+1'),
                        'xpcount' => DB::raw('xpcount+3'),
                        'credits' => DB::raw('credits+3')
                    ]);

                    $message = "Comment Posted!";

                    $content = ForumHelper::fixContent($content);

                    $username = UserHelper::getUsername(Auth::user()->userid);
                    $clean_username = Auth::user()->username;

                    $response = true;
                } else {
                    $message = "Whoa! Slow down, jeez!";
                }
            } else {
                $message = "Can't post empty comment!";
            }
        } else {
            $message = "Could not find article!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'commentid' => $commentid, 'username' => $username, 'clean_username' => $clean_username, 'content' => $content));
    }

    public function voteOnPoll(Request $request)
    {
        $threadid = $request->input('threadid');
        $pollanswerid = $request->input('pollanswerid');
        $response = false;

        $check = DB::table('thread_poll_votes')->where('userid', Auth::user()->userid)->where('threadid', $threadid)->count();
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if ($check == 0 && count($thread)) {
            DB::table('thread_poll_votes')->insert([
                'pollanswerid' => $pollanswerid,
                'threadid' => $threadid,
                'userid' => Auth::user()->userid
            ]);

            DB::table('livewall')->insert([
                'userid' => Auth::user()->userid,
                'forum' => 1,
                'forumid' => $thread->forumid,
                'item_id' => $thread->threadid,
                'item_type' => 1,
                'message' => 'voted',
                'dateline' => time()
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function markArticleAsComplete(Request $request)
    {
        $articleid = $request->input('articleid');
        $article = DB::table("articles")->where('articleid', $articleid)->first();
        $response = false;
        if (count($article)) {
            $userid = Auth::user()->userid;
            $completed = $article->completed_userids;
            if ($completed == "") {
                $completed = Auth::user()->userid;
                DB::table('articles')->where('articleid', $articleid)
                    ->update(['completed_userids' => $completed]);
                $response = true;
                $message = "Article completed!";
            } else {
                $completed_array = explode(',', $completed);
                if (!in_array($userid, $completed_array)) {
                    $completed_array[] = $userid;
                    $completed = implode(',', $completed_array);
                    DB::table('articles')->where('articleid', $articleid)
                        ->update(['completed_userids' => $completed]);
                    $response = true;
                    $message = "Article completed!";
                } else {
                    $message = "You have already completed this!";
                }
            }
        } else {
            $message = "Article no longer exists";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function markArticleAsUncomplete(Request $request)
    {
        $articleid = $request->input('articleid');
        $article = DB::table("articles")->where('articleid', $articleid)->first();
        $response = false;
        if (count($article)) {
            $userid = Auth::user()->userid;
            $completed = $article->completed_userids;
            $completed_array = explode(',', $completed);
            if (($user_index = array_search(Auth::user()->userid, $completed_array)) !== false) {
                unset($completed_array[$user_index]);
            }
            $completed = implode(',', $completed_array);
            DB::table('articles')->where('articleid', $articleid)
                ->update(['completed_userids' => $completed]);
            $response = true;
            $message = "Uncompleted!";
        } else {
            $message = "Article no longer exists";
        }
        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }


    public function addShare(Request $request)
    {
        $referrerid = $request->input('referrerid');
        $articleid = $request->input('articleid');
        if (Auth::check()) {
            $check = DB::table('guide_shares')->where('articleid', $articleid)->where('userid', Auth::user()->userid)->first();
            if (!count($check)) {
                DB::table('guide_shares')->insert([
                    'userid' => Auth::user()->userid,
                    'referrerid' => $referrerid,
                    'articleid' => $articleid,
                    'dateline' => time(),
                    'ipaddress' => $_SERVER['REMOTE_ADDR']
                ]);
            }
        } else {
            $check = DB::table('guide_shares')->where('articleid', $articleid)->where('ipaddress', $_SERVER['REMOTE_ADDR'])->first();
            if (!count($check)) {
                DB::table('guide_shares')->insert([
                    'referrerid' => $referrerid,
                    'articleid' => $articleid,
                    'dateline' => time(),
                    'ipaddress' => $_SERVER['REMOTE_ADDR']
                ]);
            }
        }
    }

    public function getArticle($articleid, $pagenr = 1)
    {
        $bbcodes = DB::table('bbcodes')->get();
        $article = DB::table('articles')->where('articleid', $articleid)->where('approved', 1)->first();

        if (count($article)) {
            $completed = QuestsHelper::haveCompletedQuest($article->articleid);
            $can_report_comment = DB::table('moderation_forums')->count() > 0;

            if (!isset($pagenr) || $pagenr <= 0) {
                $pagenr = 1;
            }

            $take = 10;
            $skip = 0;

            $pagesx = DB::table('article_comments')->where('articleid', $articleid)->where('visible', 1)->count();
            $pages = ceil($pagesx / $take);

            if ($pagenr > $pages) {
                $pagenr = $pages;
            }

            $paginator = array(
                'total' => $pages,
                'current' => $pagenr,
                'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
                'previous_exists' => $pagenr - 1 < 1 ? false : true,
                'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
                'next_exists' => $pagenr + 1 > $pages ? false : true,
                'gap_forward' => $pagenr + 5 < $pages ? true : false,
                'gap_backward' => $pagenr - 5 > 1 ? true : false
            );

            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/article/' . $articleid . '/page/')->render();

            if ($pagenr >= 2) {
                $skip = $take * $pagenr - $take;
            }

            $title = ForumHelper::fixContent($article->title);

            $content = ForumHelper::fixContent($article->content);

            $content = ForumHelper::bbcodeParser($content, $bbcodes);

            $content = nl2br($content);
            $content = ForumHelper::replaceEmojis($content);

            $user = UserHelper::getUser($article->userid);

            $userid = 0;
            $bio = "";
            $username = "";
            $followers = 0;
            $avatar = "";
            $follows = false;

            if (count($user)) {
                $userid = $user->userid;
                $bio = ForumHelper::fixContent($user->bio);
                $bio = ForumHelper::replaceEmojis($bio);
                $bio = ForumHelper::bbcodeParser($bio, $bbcodes);
                $bio = nl2br($bio);
                $username = $user->username;
                $followers = DB::table('followers')->where('userid', $user->userid)->count();
                $avatar = UserHelper::getAvatar($user->userid);

                if (Auth::check()) {
                    $followsquery = DB::table('followers')->where('userid', $userid)->where('follower', Auth::user()->userid)->first();
                    if (count($followsquery)) {
                        $follows = true;
                    } else {
                        $follows = false;
                    }
                }
            }

            $author = array(
                'userid' => $userid,
                'username' => $username,
                'clean_username' => UserHelper::getUsername($userid, true),
                'username' => UserHelper::getUsername($userid),
                'bio' => $bio,
                'followers' => $followers,
                'avatar' => $avatar,
                'follows' => $follows
            );

            if (empty($bio)) {
                $bio = "<i>Author have no bio..</i>";
            }

            $likers = QuestsHelper::getQuestLikers($articleid);

            $likes_article = Auth::check() && DB::table('guide_rates')->where('articleid', $articleid)->where('userid', Auth::user()->userid)->count() > 0;
            $rates_article = Auth::check() && DB::table('notifications')->where('postuserid', $userid)->where('content', 15)->where('contentid', $article->articleid)->count() == 1;

            $ratecount = DB::table('guide_rates')->where('articleid', $articleid)->count();
            $helpful = DB::table('guide_rates')->where('articleid', $articleid)->sum('helpful');
            $badge = DB::table('guide_rates')->where('articleid', $articleid)->sum('badge');
            $recommend = DB::table('guide_rates')->where('articleid', $articleid)->sum('recommended');
            $avgrate = DB::table('guide_rates')->where('articleid', $articleid)->avg('rate');
            $above8 = DB::table('guide_rates')->where('articleid', $articleid)->where('rate', '>', 7)->count();


            $rate = array(
                'count' => $ratecount,
                'helpful' => $helpful,
                'helpfulperc' => $ratecount > 0 ? $helpful / $ratecount * 100 : 0,
                'badge' => $badge,
                'badgeperc' => $ratecount > 0 ? $badge / $ratecount * 100 : 0,
                'recommended' => $recommend,
                'recommendedperc' => $ratecount > 0 ? $recommend / $ratecount * 100 : 0,
                'rate' => number_format($avgrate, 2),
                'rateperc' => $avgrate * 10,
                'over8' => $above8
            );

            $sharetemps = DB::table('guide_shares')->where('articleid', $article->articleid)->get();
            $shares = array();
            foreach ($sharetemps as $temp) {
                if (isset($shares[$temp->referrerid])) {
                    $shares[$temp->referrerid]['count']++;
                } else {
                    $shares[$temp->referrerid] = array(
                        'name' => UserHelper::getUsername($temp->referrerid),
                        'count' => 1
                    );
                }
            }
            usort($shares, function ($item1, $item2) {
                if ($item1 == $item2) {
                    return 0;
                } elseif ($item1 > $item2) {
                    return 1;
                } elseif ($item2 > $item1) {
                    return -1;
                }
                //return $item1['count'] <=> $item2['count'];
            });

            $badges = array();
            $badgestring = $article->badge_code;
            $badges = explode(',', $badgestring);

            $art = array(
                'title' => $title,
                'type' => $article->type,
                'content' => $content,
                'time' => ForumHelper::timeAgo($article->dateline),
                'avatar' => UserHelper::getAvatar($article->userid),
                'username' => UserHelper::getUsername($article->userid),
                'clean_username' => UserHelper::getUsername($article->userid, true),
                'likers_strike' => $likers['likers_strike'],
                'have_likers' => $likers['have_likers'],
                'likes_article' => $likes_article,
                'rates_article' => $rates_article,
                'articleid' => $articleid,
                'completed' => $completed,
                'difficulty' => $article->difficulty,
                'paid' => $article->paid,
                'room_link' => $article->room_link,
                'available' => $article->available,
                'badges' => $badges,
                'bio' => $user->bio,
                'shares' => $shares
            );

            $can_edit_article = Auth::check() && ($article->userid == Auth::user()->userid || UserHelper::haveStaffPerm(Auth::user()->userid, 256));
            $comments = [];

            $temps = DB::table('article_comments')
                ->where('articleid', $article->articleid)
                ->where('visible', 1)
                ->where('parentid', 0)
                ->orderBy('commentid', 'ASC')
                ->take($take)
                ->skip($skip)
                ->get();

            foreach ($temps as $temp) {
                $username = "noone";

                $user = DB::table('users')->where('userid', $temp->userid)->first();

                if (count($user)) {
                    $username = $user->username;
                }

                $content = ForumHelper::fixContent($temp->content);
                $content = ForumHelper::replaceEmojis($content);

                $likers = QuestsHelper::getQuestLikers($temp->commentid);

                $likes_comment = Auth::check() && DB::table('notifications')->where('postuserid', $userid)->where('content', 16)->where('contentid', $temp->commentid)->count() == 1;
                $can_edit_comment = Auth::check() && $temp->userid == Auth::user()->userid;
                $replies = array();
                $repliesobj = DB::table('article_comments')
                    ->where('visible', 1)
                    ->where('parentid', $temp->commentid)
                    ->get();

                $replyCount = 0;
                foreach ($repliesobj as $replyobj) {
                    $replyusername = "noone";
                    $replyuser = DB::table('users')->where('userid', $replyobj->userid)->first();

                    if (count($replyuser)) {
                        $replyusername = $replyuser->username;
                    }

                    $replycontent = ForumHelper::fixContent($replyobj->content);
                    $likers = QuestsHelper::getCommentLikers($replyobj->commentid);
                    $can_edit_reply = Auth::check() && $replyobj->userid == Auth::user()->userid;
                    $likes_reply = Auth::check() && DB::table('notifications')->where('postuserid', $userid)->where('content', 16)->where('contentid', $replyobj->commentid)->count() == 1;

                    $replyarray = array(
                        'userid' => $replyobj->userid,
                        'username' => UserHelper::getUsername($replyobj->userid),
                        'clean_username' => $replyusername,
                        'content' => nl2br(ForumHelper::bbcodeParser($replycontent)),
                        'likers_strike' => $likers['likers_strike'],
                        'have_likers' => $likers['have_likers'],
                        'likes_reply' => $likes_reply,
                        'commentid' => $replyobj->commentid,
                        'can_edit_reply' => $can_edit_reply,
                        'avatar' => UserHelper::getAvatar($replyobj->userid),
                        'date' => ForumHelper::getTimeInDate($replyobj->dateline),
                    );

                    $replies[] = $replyarray;
                    $replyCount++;
                }
                $array = array(
                    'userid' => $temp->userid,
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => $username,
                    'content' => nl2br(ForumHelper::bbcodeParser($content)),
                    'likers_strike' => $likers['likers_strike'],
                    'have_likers' => $likers['have_likers'],
                    'likes_comment' => $likes_comment,
                    'can_edit_comment' => $can_edit_comment,
                    'commentid' => $temp->commentid,
                    'avatar' => UserHelper::getAvatar($temp->userid),
                    'date' => ForumHelper::getTimeInDate($temp->dateline),
                    'replies' => $replies,
                    'repliescount' => $replyCount
                );

                $comments[] = $array;
            }

            $can_soft_delete_article_comments = Auth::check() && UserHelper::haveGeneralModPerm(Auth::user()->userid, 32);
            $can_infract_article_comments = Auth::check() && UserHelper::haveGeneralModPerm(Auth::user()->userid, 256);
            $section = "";

            switch ($article->type) {
                case 0:
                    $section = "Quest Guides";
                    break;
                case 1:
                    $section = "News Articles";
                    break;
                case 2:
                    $section = "Wired Guides";
                    break;
                case 3:
                    $section = "Tips & Tricks";
                    break;
                default:
                    $section = "Quest Guides";
                    break;
            }

            $have_flagged = false;
            $temps = DB::table('articles')->take(12)->get();
            $other_articles = array();
            foreach ($temps as $temp) {
                $article = array(
                    'badgecode' => $temp->badge_code,
                    'articleid' => $temp->articleid,
                    'title' => $temp->title
                );
                $other_articles[] = $article;
            }

            $temps = DB::table('articles')->where('userid', $author['userid'])->take(12)->get();
            $other_articles_author = array();
            foreach ($temps as $temp) {
                $article = array(
                    'badgecode' => $temp->badge_code,
                    'articleid' => $temp->articleid,
                    'title' => $temp->title
                );
                $other_articles_author[] = $article;
            }

            $tms = DB::table('infraction_reasons')->orderBy('text', 'ASC')->get();
            $infraction_reasons = array();
            foreach ($tms as $tm) {
                $array = array(
                    'infractionrsnid' => $tm->infractionrsnid,
                    'reason' => $tm->text,
                    'points' => $tm->points
                );
                $infraction_reasons[] = $array;
            }

            $sharelink = "";
            if (Auth::check()) {
                $sharelink = "https://www.thishabbo.com/article/" . $art['articleid'] . "?userid=" . Auth::user()->userid;
            }

            if (Auth::check()) {
                $tweetsharebody = rawurlencode("I just completed the '" . $art['title'] . "' guide on @ThisHabbo #THV6 - https://www.thishabbo.com/article/" . $art['articleid'] . "?userid=" . Auth::user()->userid);
            } else {
                $tweetsharebody = rawurlencode("I just completed the '" . $art['title'] . "' guide on @ThisHabbo #THV6 - https://www.thishabbo.com/article/" . $art['articleid']);
            }

            $totalshares = DB::table('guide_shares')->where('articleid', $art['articleid'])->count();

            $verified = Auth::check() ? Auth::user()->habbo_verified : 0;
            $gdpr = Auth::check() ? Auth::user()->gdpr : 0;
            DB::table('articles')->where('articleid', $articleid)->increment('views');
            $views = DB::table('articles')->where('articleid', $articleid)->value('views');

            $returnHTML = view('article')
                ->with('art', $art)
                ->with('totalshares', $totalshares)
                ->with('sharelink', $sharelink)
                ->with('tweetsharebody', $tweetsharebody)
                ->with('other_articles', $other_articles)
                ->with('other_articles_author', $other_articles_author)
                ->with('can_edit_article', $can_edit_article)
                ->with('can_report_comment', $can_report_comment)
                ->with('author', $author)
                ->with('comments', $comments)
                ->with('can_soft_delete_article_comments', $can_soft_delete_article_comments)
                ->with('can_infract_article_comments', $can_infract_article_comments)
                ->with('infraction_reasons', $infraction_reasons)
                ->with('section', $section)
                ->with('pagi', $pagi)
                ->with('rate', $rate)
                ->with('verified', $verified)
                ->with('gdpr', $gdpr)
                ->with('views', $views)
                ->with('current_page', $pagenr)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function getNewPosts($pagenr)
    {
        $forums = array();
        $temps = DB::table('forums')->where('parentid', '>', 0)->get();
        $userid = Auth::user()->userid;

        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm($userid, $temp->forumid, 32)) {
                $forums[] = $temp->forumid;
            }
        }

        $ignored = explode(',', Auth::user()->ignored_forums);
        $forums = array_diff($forums, $ignored);

        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 20;
        $skip = 0;

        $midnight = ForumHelper::findMidnight();

        $temps = DB::table('threads')->whereIn('forumid', $forums)->where('visible', 1)->where('lastpostuserid', '!=', $userid)->where('lastpost', '>', $midnight)->get();
        $threads_count = array();

        foreach ($temps as $temp) {
            $check = DB::table('thread_read')->where('userid', $userid)->where('threadid', $temp->threadid)->where('read', '>', $temp->lastpost)->count();

            if ($check == 0) {
                $threads_count[] = $temp->threadid;
            }
        }

        $pagesx = count($threads_count);

        $pages = ceil($pagesx / $take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
            'previous_exists' => $pagenr - 1 < 1 ? false : true,
            'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
            'next_exists' => $pagenr + 1 > $pages ? false : true,
            'gap_forward' => $pagenr + 5 < $pages ? true : false,
            'gap_backward' => $pagenr - 5 > 1 ? true : false
        );

        if ($pagenr >= 2) {
            $skip = $take * $pagenr - $take;
        }

        $threads = array();
        if ($take > 0) {
            $threads_obj = DB::table('threads')->whereIn('threadid', $threads_count)->where('visible', 1)->orderBy('lastpost', 'DESC')->skip($skip)->take($take)->get();

            foreach ($threads_obj as $thread) {
                if (!($thread->postuserid != $userid and !UserHelper::haveForumPerm($userid, $thread->forumid, 32))) {
                    $post = DB::table('posts')->where('postid', $thread->lastpostid)->first();

                    $last_poster_username = "";
                    $last_poster_userid = 0;
                    $last_poster_time = 0;

                    if (count($post)) {
                        $last_poster_username = UserHelper::getUsername($post->userid);
                        $last_poster_userid = $post->userid;
                        $last_poster_time = ForumHelper::getTimeInDate($post->dateline);
                    }

                    $last_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $thread->threadid)->first();

                    $last_post_page = 1;

                    if (count($last_read)) {
                        $last_post = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('dateline', '>', $last_read->read)->orderBy('postid', 'DESC')->first();

                        if (count($last_post)) {
                            /* HOW MANY MORE POSTS ARE THERE BEFORE THIS ONE */
                            $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('postid', '<', $last_post->postid)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        } else {
                            /* GET THE LAST PAGE */
                            $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        }
                    }

                    $prefix = DB::table('prefixes')->where('prefixid', $thread->prefixid)->first();
                    $title = ForumHelper::fixContent($thread->title);
                    if (count($prefix)) {
                        $title = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ' . $title;
                    }

                    $tsm = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->count();

                    $array = array(
                        'threadid' => $thread->threadid,
                        'page' => $last_post_page,
                        'title' => $title,
                        'sticky' => $thread->sticky,
                        'views' => number_format($thread->views) . '<br /><i>Views</i>',
                        'replys' => number_format($tsm) . '<br /><i>Replies</i>',
                        'open' => $thread->open,
                        'time' => ForumHelper::getTimeInDate($thread->dateline),
                        'userid' => $thread->postuserid,
                        'username' => UserHelper::getUsername($thread->postuserid),
                        'last_poster_username' => $last_poster_username,
                        'last_poster_userid' => $last_poster_userid,
                        'last_poster_time' => $last_poster_time,
                        'last_poster_avatar' => UserHelper::getAvatar($last_poster_userid),
                        'visible' => $thread->visible
                    );

                    $threads[] = $array;
                }
            }
        }

        $quests = array();
        $temps = DB::table('active_quests')->where('userid', Auth::user()->userid)->get();
        foreach ($temps as $temp) {
            $array = array(
                'text' => $temp->text,
                'box' => DB::table('boxes')->where('boxid', $temp->boxid)->value('name')
            );
            $quests[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/forum/newposts/page/')->render();
        $returnHTML = view('forum.newposts')
            ->with('quests', $quests)
            ->with('threads', $threads)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function reportPost(Request $request)
    {
        $postid = $request->input('report_postid');
        $reason = $request->input('reason');
        $pagenumber = $request->input('pagenumber');
        $message = "";
        $response = false;

        $check = DB::table('posts')->where('userid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count();

        if ($check == 0) {
            if ($reason != "") {
                $post = DB::table('posts')->where('postid', $postid)->first();

                if (count($post)) {
                    $thread = DB::table('threads')->where('threadid', $post->threadid)->first();
                    if (count($thread)) {
                        if ($post->userid != Auth::user()->userid) {
                            $modforums = DB::table('moderation_forums')->orderBy('mfid', 'DESC')->get();
                            $user = DB::table('users')->where('userid', $post->userid)->first();
                            $time = time();

                            if (count($user)) {
                                $date = date('d/m/y', $time);
                                $thread_name = $thread->title;
                                $reporter = Auth::user()->username;
                                $reported = $user->username;
                                $type = "post";

                                $content = '[intern_link=/profile/' . $reporter . '/page/1]' . $reporter . '[/intern_link] has reported a post in ' . $thread_name . '

                                [b]User reported:[/b] [intern_link=/profile/' . $reported . '/page/1]' . $reported . '[/intern_link]
                                [b]Thread:[/b] [intern_link=/forum/thread/' . $thread->threadid . '/page/' . $pagenumber . '?postid=' . $postid . ']Click here to go to thread.[/intern_link]

                                [b]Reason[/b]:
                                [quote]' . $reason . '[/quote]

                                [b]Original post:[/b]
                                [quote]' . $post->content . '[/quote]';

                                foreach ($modforums as $modforum) {
                                    $title = $modforum->title;
                                    $forumid = $modforum->forumid;

                                    $title = str_replace("{date}", $date, $title);
                                    $title = str_replace("{thread}", $thread_name, $title);
                                    $title = str_replace("{reporter}", $reporter, $title);
                                    $title = str_replace("{reported}", $reported, $title);
                                    $title = str_replace("{type}", $type, $title);

                                    $postid = DB::table('posts')->insertGetId([
                                        'threadid' => 0,
                                        'username' => $reporter,
                                        'userid' => Auth::user()->userid,
                                        'dateline' => $time,
                                        'lastedit' => 0,
                                        'lastedituser' => 0,
                                        'content' => $content,
                                        'ipaddress' => $request->ip(),
                                        'visible' => 1
                                    ]);

                                    $threadid = DB::table('threads')->insertGetId([
                                        'title' => $title,
                                        'forumid' => $modforum->forumid,
                                        'postuserid' => Auth::user()->userid,
                                        'dateline' => $time,
                                        'firstpostid' => $postid,
                                        'lastpost' => $time,
                                        'lastpostid' => $postid,
                                        'lastedited' => 0,
                                    ]);

                                    DB::table('forums')->where('forumid', $modforum->forumid)->update([
                                        'posts' => DB::raw('posts+1'),
                                        'threads' => DB::raw('threads+1'),
                                        'lastpost' => $time,
                                        'lastpostid' => $postid,
                                        'lastposterid' => Auth::user()->userid,
                                        'lastthread' => $time,
                                        'lastthreadid' => $threadid
                                    ]);

                                    DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);
                                    $time += 10;
                                }

                                $response = true;
                            } else {
                                $message = "This user no longer exists!";
                            }
                        } else {
                            $message = "You can't report yourself!";
                        }
                    } else {
                        $message = "This thread no longer exists!";
                    }
                } else {
                    $message = "This post no longer exists";
                }
            } else {
                $message = "Reason can't be empty!";
            }
        } else {
            $message = "You are reporting too fast!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function reportVm(Request $request)
    {
        $vmid = $request->input('vmid');
        $reason = $request->input('reason');
        $pagenumber = $request->input('pagenumber');
        $message = "";
        $response = false;

        $check = DB::table('posts')->where('userid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count();

        if ($check == 0) {
            if ($reason != "") {
                $vm = DB::table('visitor_messages')->where('vmid', $vmid)->first();

                if (count($vm)) {
                    if ($vm->postuserid != Auth::user()->userid) {
                        $modforums = DB::table('moderation_forums')->orderBy('mfid', 'DESC')->get();
                        $user = DB::table('users')->where('userid', $vm->postuserid)->first();
                        $time = time();

                        if (count($user)) {
                            $date = date('d/m/y', $time);
                            $reporter = Auth::user()->username;
                            $reported = $user->username;
                            $type = "visitor message";

                            $recive_username = UserHelper::getUsername($vm->reciveuserid, 1);

                            $content = '[intern_link=/profile/' . $reporter . '/page/1]' . $reporter . '[/intern_link] have reported a visitor message on ' . $recive_username . ' profile

                            [b]User reported:[/b] [intern_link=/profile/' . $reported . '/page/1]' . $reported . '[/intern_link]
                            [b]Profile:[/b] [intern_link]/profile/' . $recive_username . '/page/' . $pagenumber . '[/intern_link]

                            [b]Reason[/b]:
                            [quote]' . $reason . '[/quote]

                            [b]Original post:[/b]
                            [quote]' . $vm->message . '[/quote]';

                            foreach ($modforums as $modforum) {
                                $title = $modforum->title;
                                $forumid = $modforum->forumid;

                                $title = str_replace("{date}", $date, $title);
                                $title = str_replace("{thread}", $recive_username, $title);
                                $title = str_replace("{reporter}", $reporter, $title);
                                $title = str_replace("{reported}", $reported, $title);
                                $title = str_replace("{type}", $type, $title);

                                $postid = DB::table('posts')->insertGetId([
                                    'threadid' => 0,
                                    'username' => $reporter,
                                    'userid' => Auth::user()->userid,
                                    'dateline' => $time,
                                    'lastedit' => 0,
                                    'lastedituser' => 0,
                                    'content' => $content,
                                    'ipaddress' => $request->ip(),
                                    'visible' => 1
                                ]);

                                $threadid = DB::table('threads')->insertGetId([
                                    'title' => $title,
                                    'forumid' => $modforum->forumid,
                                    'postuserid' => Auth::user()->userid,
                                    'dateline' => $time,
                                    'firstpostid' => $postid,
                                    'lastpost' => $time,
                                    'lastpostid' => $postid,
                                    'lastedited' => 0,
                                ]);

                                DB::table('forums')->where('forumid', $modforum->forumid)->update([
                                    'posts' => DB::raw('posts+1'),
                                    'threads' => DB::raw('threads+1'),
                                    'lastpost' => $time,
                                    'lastpostid' => $postid,
                                    'lastposterid' => Auth::user()->userid,
                                    'lastthread' => $time,
                                    'lastthreadid' => $threadid
                                ]);

                                DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);
                                $time += 10;
                            }

                            $response = true;
                        } else {
                            $message = "This user no longer exists!";
                        }
                    } else {
                        $message = "You can't report yourself!";
                    }
                } else {
                    $message = "This visitor message no longer exists";
                }
            } else {
                $message = "Reason can't be empty!";
            }
        } else {
            $message = "You are reporting too fast!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postVisitorMessage(Request $request)
    {
        $userid = $request->input('userid');
        $post_message = $request->input('message');
        $response = false;
        $message = "";
        $new_visitor = "";

        if (Auth::user()->habbo_verified == 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => "You must verify your habbo to post a VM!"));
        }

        if (Auth::user()->gdpr == 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => "You must accept our GDPR Policy on the homepage to post a VM!"));
        }


        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user)) {
            $check_double = DB::table('visitor_messages')->where('postuserid', Auth::user()->userid)->where('reciveuserid', $userid)->where('message', 'LIKE', $post_message)->count();

            if ($check_double == 0) {
                $check_speed = DB::table('visitor_messages')->where('postuserid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count();

                if ($check_speed == 0) {
                    $vmid = DB::table('visitor_messages')->insertGetId([
                        'postuserid' => Auth::user()->userid,
                        'reciveuserid' => $userid,
                        'message' => $post_message,
                        'dateline' => time()
                    ]);

                    DB::table('users')->where('userid', Auth::user()->userid)->update([
                        'xpcount' => DB::raw('xpcount+1')
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => Auth::user()->userid,
                        'reciveuserid' => $userid,
                        'content' => 6,
                        'contentid' => $vmid,
                        'dateline' => time(),
                        'read_at' => 0,
                        'page' => 1
                    ]);

                    $post_message = ForumHelper::fixContent($post_message);
                    $post_message = ForumHelper::bbcodeParser($post_message);
                    $post_message = ForumHelper::replaceEmojis($post_message);


                    DB::table('livewall')->insert([
                        'userid' => Auth::user()->userid,
                        'forum' => 0,
                        'forumid' => 0,
                        'item_id' => $vmid,
                        'item_type' => 5,
                        'message' => 'posted',
                        'dateline' => time()
                    ]);

                    $response = true;
                    $new_visitor = view('layout.vms.vms-html')
                        ->with('avatar', UserHelper::getAvatar(Auth::user()->userid))
                        ->with('userid', DB::table('visitor_messages')->where('vmid', $vmid)->value('postuserid'))
                        ->with('username', UserHelper::getUsername(Auth::user()->userid))
                        ->with('clean_username', UserHelper::getUsername(Auth::user()->userid, true))
                        ->with('username2', UserHelper::getUsername($userid, true))
                        ->with('show_link', true)
                        ->with('time', "Just now")
                        ->with('message', $post_message)
                        ->with('can_delete_vm', UserHelper::haveGeneralModPerm(Auth::user()->userid, 8))
                        ->with('vmid', $vmid)
                        ->render();
                } else {
                    $message = "You are posting a little quick! give atleast 5 seconds between each message!";
                }
            } else {
                $message = "Can't double post the same message!";
            }
        } else {
            $message = "Can't find user!";
        }


        return response()->json(array('success' => true, 'response' => $response, 'new_visitor' => $new_visitor, 'message' => $message));
    }

    public function getProfile($username, $pagenr = 1)
    {
        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();


        if (count($user)) {
            if (Auth::user()->username != $user->username) {
                DB::table('users')->where('userid', $user->userid)->increment('profilevisits');
            }

            $following = 0;

            $country_name = "N/A";

            if ($user->country === 0) {
                $country_name = "Not Set";
            } else {
                $country = DB::table('countrys')->where('countryid', $user->country)->first();
            }

            if ($user->country != 0) {
                $country = DB::table('countrys')->where('countryid', $user->country)->first();
                $country_name = $country->name;
            }

            $referrals = DB::table('users')->where('referdby', $user->userid)->count();

            $joindate = date('jS F Y', $user->joindate);
            $radioLikes = DB::table('dj_likes')->where('djid', $user->userid)->count();

            $habbo = "";

            $userbars_html = array();
            $userbars_css = array();

            $groups = explode(",", $user->usergroups);
            foreach ($groups as $group) {
                $staff = DB::table('staff_list')->where('usergroupid', $group)->first();

                if (count($staff)) {
                    $bar = UserHelper::getUserbar($user->userid, $group);
                    if (count($bar)) {
                        $userbars_html[] = $bar['html'];
                        $userbars_css[] = $bar['css'];
                    }
                }
            }

            if ($user->habbo_verified == 1) {
                $habbo = $user->habbo;
            }

            $bbcodes = DB::table('bbcodes')->get();
            $bio = ForumHelper::fixContent($user->bio);
            $bio = ForumHelper::replaceEmojis($bio);
            $bio = ForumHelper::bbcodeParser($bio, $bbcodes);
            $bio = nl2br($bio);

            if ($user->profile_header > 0) {
                //user have static header
                $header = asset('_assets/img/website/headers/' . $user->profile_header . '.png');
            } else {
                //user have custom header
                if (File::exists('_assets/img/headers/' . $user->userid . '.gif')) {
                    $header = asset('_assets/img/headers/' . $user->userid . '.gif?' . time());
                } else {
                    $header = asset('_assets/img/website/headers/6.png');
                }
            }
            $background = "";
            if ($user->background >= 0) {
                $background = asset('_assets/img/backgrounds/' . $user->background . '.gif');
            }

            $stickertemp = DB::table('sticker_users')->where('userid', $user->userid)->get();
            $stickers = array();
            foreach ($stickertemp as $stick) {
                $sticker = array(
                    'transactionid' => $stick->transactionid,
                    'stickerid' => $stick->stickerid,
                    'stickername' => DB::table('stickers')->where('stickerid', $stick->stickerid)->value('name'),
                    'image' => asset('_assets/img/stickers/' . $stick->stickerid . '.gif'),
                    'visible' => $stick->visible,
                    'top' => $stick->top,
                    'left' => $stick->left
                );
                $stickers[] = $sticker;
            }

            $visiblestickerstemp = DB::table('sticker_users')->where('userid', $user->userid)->where('visible', 1)->get();
            $visibles = array();
            foreach ($visiblestickerstemp as $stick) {
                $sticker = array(
                    'transactionid' => $stick->transactionid,
                    'stickerid' => $stick->stickerid,
                    'top' => $stick->top,
                    'left' => $stick->left,
                    'name' => DB::table('stickers')->where('stickerid', $stick->stickerid)->value('name')
                );
                $visibles[] = $sticker;
            }

            $invisiblestickerstemp = DB::table('sticker_users')->where('userid', $user->userid)->where('visible', 0)->get();
            $invisibles = array();
            foreach ($invisiblestickerstemp as $stick) {
                if (isset($invisibles[$stick->stickerid])) {
                    $invisibles[$stick->stickerid]['count']++;
                    $invisibles[$stick->stickerid]['transactionids'] .= "-" . $stick->transactionid;
                } else {
                    $invisibles[$stick->stickerid] = array(
                        'count' => 1,
                        'transactionids' => $stick->transactionid,
                        'picture' => asset('_assets/img/stickers/' . $stick->stickerid . '.gif'),
                        'name' => DB::table('stickers')->where('stickerid', $stick->stickerid)->value('name')
                    );
                }
            }

            $recentactivity_temp = DB::table('livewall')->where('userid', $user->userid)->orderBy('itemid', 'DESC')->take(5)->get();
            $recentactivity = array();

            foreach ($recentactivity_temp as $recent) {
                $run = false;
                if ($recent->item_type == 1) { // Posted a Thread
                    $temp1 = DB::table('threads')->where('threadid', $recent->item_id)->where('visible', 1)->first();

                    if (count($temp1)) {

                        if (UserHelper::haveForumPerm(Auth::user()->userid, $temp1->forumid, 32)) {

                            $message = 'posted the thread <a href="/forum/thread/' . $recent->item_id . '/page/1">' . $temp1->title . '</a>.';

                            $run = true;
                        }
                    }
                }

                if ($recent->item_type == 2 && $recent->message == 'posted') { // Posted a Reply
                    $temp1 = DB::table('posts')->where('postid', $recent->item_id)->where('visible', 1)->first();

                    if (count($temp1)) {
                        $temp2 = DB::table('threads')->where('threadid', $temp1->threadid)->where('visible', 1)->first();

                        if (count($temp2)) {

                            if (UserHelper::haveForumPerm(Auth::user()->userid, $temp2->forumid, 32)) {

                                $message = 'posted a reply to the thread <a href="/forum/thread/' . $temp1->threadid . '/page/1?postid=' . $recent->item_id . '">' . $temp2->title . '</a>';

                                $run = true;
                            }
                        }
                    }
                }

                if ($recent->item_type == 2 && $recent->message == 'liked') { // Liked a Post
                    $temp1 = DB::table('posts')->where('postid', $recent->item_id)->where('visible', 1)->first();
                    if (count($temp1)) {
                        $temp2 = DB::table('threads')->where('threadid', $temp1->threadid)->where('visible', 1)->where('visible', 1)->first();
                        $temp3 = UserHelper::getUsername($temp1->userid);
                        $temp4 = UserHelper::getUsername($temp1->userid, true);

                        if (UserHelper::haveForumPerm(Auth::user()->userid, $recent->forumid, 32)) {

                            $message = 'liked a post by <a href="/profile/' . $temp4 . '">' . $temp3 . '</a> in thread <a href="/forum/thread/' . $temp2->threadid . '/page/1">' . $temp2->title . '</a>';

                            $run = true;
                        }
                    }
                }

                if ($recent->item_type == 4 && $recent->message == 'posted') { // Replied to an Article
                    $temp1 = DB::table('articles')->where('articleid', $recent->item_id)->first();

                    $message = 'commented on the article <a href="/article/' . $recent->item_id . '">' . $temp1->title . '</a>';

                    $run = true;
                }

                if ($recent->item_type == 5 && $recent->message == 'posted') { // Posted a Visitor Message
                    $temp1 = DB::table('visitor_messages')->where('vmid', $recent->item_id)->where('visible', 1)->first();
                    $temp2 = UserHelper::getUsername($temp1->reciveuserid);
                    $temp3 = UserHelper::getUsername($temp1->reciveuserid, true);

                    if (count($temp1)) {
                        $message = 'Posted a Message on <a href="/profile/' . $temp3 . '">' . $temp2 . '</a>\'s Profile.';

                        $run = true;
                    }
                }

                if ($recent->item_type == 5 && $recent->message == 'followed') { // Followed a User
                    $temp1 = UserHelper::getUsername($recent->item_id);
                    $temp2 = UserHelper::getUsername($recent->item_id, true);

                    $message = 'follower the user <a href="/profile/' . $temp2 . '/">' . $temp1 . '</a>';

                    $run = true;
                }

                if ($run == true) {
                    $array = array(
                        'time' => ForumHelper::timeAgo($recent->dateline),
                        'message' => $message
                    );
                    $recentactivity[] = $array;
                }
            }

            $temps = DB::table('background_users')->where('userid', $user->userid)->count();
            $temps2 = DB::table('name_effect_users')->where('userid', $user->userid)->count();
            $temps3 = DB::table('name_icon_users')->where('userid', $user->userid)->count();
            $temps4 = DB::table('sticker_users')->where('userid', $user->userid)->count();
            $temps5 = DB::table('theme_users')->where('userid', $user->userid)->count();

            $shop_owned = $temps + $temps2 + $temps3 + $temps4 + $temps5;

            $following = DB::table('followers')->where('follower', $user->userid)->count();
            $followers = DB::table('followers')->where('userid', $user->userid)->count();

            $users_theme = "N/A";

            if ($user->theme === '0') {
                $users_theme = "Not Set";
            } else {
                $theme = DB::table('themes')->where('themeid', $user->theme)->first();
            }

            if ($user->theme != '0') {
                $theme_exist = DB::table('themes')->where('themeid', $user->theme)->count();
                if ($theme_exist != 0) {
                    $theme = DB::table('themes')->where('themeid', $user->theme)->first();
                    $users_theme = $theme->name;
                } else {
                    $users_theme = "Not Set";
                }
            }


            $accolades = DB::table('accolades')->where('userid', $user->userid)->orderBy('display_order', 'DESC')->get();
            $accolade_count = DB::table('accolades')->where('userid', $user->userid)->count();
            $users_accolades = array();

            foreach ($accolades as $accolade) {
                $users_accolades[] = $accolade->description;
            }

            $levels = DB::Table('xp_levels')->orderBy('posts', 'DESC')->get();
            $found = false;
            $level_pro = 0;
            $nextlevel_posts = 0;
            $nextlevel_id = 0;
            foreach ($levels as $level) {
                if ($found == false) {
                    if ($user->xpcount >= $level->posts) {
                        $level_id = $level->levelid;
                        $level_name = $level->name;
                        $found = true;
                    } else {
                        $nextlevel_posts = $level->posts;
                        $nextlevel_id = $level->levelid;
                    }
                }
            }
            $level_until = $nextlevel_posts - $user->xpcount;
            if ($nextlevel_id == 0) {
                $level_pro = 100;
            } else {
                $pro = ($user->xpcount / $nextlevel_posts) * 100;
                $level_pro = round($pro);
            }

            $array = array(
                'username' => UserHelper::getUsername($user->userid),
                'stickers' => $stickers,
                'visibles' => $visibles,
                'shop_owned' => $shop_owned,
                'theme' => $users_theme,
                'clean_username' => UserHelper::getUsername($user->userid, true),
                'avatar' => UserHelper::getAvatar($user->userid),
                'header' => $header,
                'threads' => $user->threadcount,
                'likes' => $user->likecount,
                'djlikes' => $radioLikes,
                'posts' => $user->postcount,
                'comments' => $user->commentcount,
                'level_name' => $level_name,
                'level_pro' => $level_pro,
                'lastactivity' => ForumHelper::timeAgo($user->lastactivity),
                'joined' => $joindate,
                'country' => $country_name,
                'habbo' => $habbo,
                'bio' => $bio,
                'userid' => $user->userid,
                'referrals' => $referrals,
                'visitors' => $user->profilevisits,
                'followers' => $followers,
                'following' => $following,
            );

            $visitormessages = array();

            if ($pagenr <= 0) {
                $pagenr = 1;
            }

            $take = 10;
            $skip = 0;

            $pagesx = DB::table('visitor_messages')->where('reciveuserid', $user->userid)->where('visible', 1)->count();

            $pages = ceil($pagesx / $take);

            if ($pagenr > $pages) {
                $pagenr = $pages;
            }

            $paginator = array(
                'total' => $pages,
                'current' => $pagenr,
                'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
                'previous_exists' => $pagenr - 1 < 1 ? false : true,
                'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
                'next_exists' => $pagenr + 1 > $pages ? false : true,
                'gap_forward' => $pagenr + 5 < $pages ? true : false,
                'gap_backward' => $pagenr - 5 > 1 ? true : false
            );

            if ($pagenr >= 2) {
                $skip = $take * $pagenr - $take;
            }

            $temps = DB::table('visitor_messages')->where('reciveuserid', $user->userid)->where('visible', 1)->orderBy('vmid', 'DESC')->take($take)->skip($skip)->get();
            $userid = Auth::check() ? Auth::user()->userid : 0;
            /* REPORT STUFF */
            $can_report_post = false;

            $check = DB::table('moderation_forums')->count();

            if ($check > 0) {
                $can_report_post = true;
            }
            foreach ($temps as $temp) {
                $message = ForumHelper::fixContent($temp->message);
                $message = ForumHelper::replaceEmojis($message);
                $message = ForumHelper::bbcodeParser($message);

                $message = view('layout.vms.vms-html')
                    ->with('userid', $temp->postuserid)
                    ->with('username', UserHelper::getUsername($temp->postuserid))
                    ->with('clean_username', UserHelper::getUsername($temp->postuserid, true))
                    ->with('username2', UserHelper::getUsername($user->userid, true))
                    ->with('time', ForumHelper::timeAgo($temp->dateline))
                    ->with('message', $message)
                    ->with('show_link', true)
                    ->with('can_delete_vm', UserHelper::haveGeneralModPerm($userid, 8))
                    ->with('vmid', $temp->vmid)
                    ->with('can_report_post', $can_report_post)
                    ->render();

                $visitormessages[] = $message;
            }

            if (Auth::check()) {
                if (Auth::user()->userid != $user->userid) {
                    $check = DB::table('profile_visitors')->where('userid', $user->userid)->where('visitorid', Auth::user()->userid)->count();

                    if ($check > 0) {
                        DB::table('profile_visitors')->where('userid', $user->userid)->where('visitorid', Auth::user()->userid)->update([
                            'userid' => $user->userid,
                            'visitorid' => Auth::user()->userid,
                            'dateline' => time()
                        ]);
                    } else {
                        DB::table('profile_visitors')->insert([
                            'userid' => $user->userid,
                            'visitorid' => Auth::user()->userid,
                            'dateline' => time()
                        ]);
                    }
                }
            }

            $latest_visitors = array();

            $temps = DB::table('profile_visitors')->where('userid', $user->userid)->orderBy('dateline', 'DESC')->take(12)->get();

            foreach ($temps as $temp) {
                $visitor = DB::table('users')->where('userid', $temp->visitorid)->first();

                if (count($visitor)) {
                    $tmparr = array(
                        'username' => $visitor->username,
                        'avatar' => UserHelper::getAvatar($visitor->userid),
                        'time' => ForumHelper::timeAgo($temp->dateline)
                    );

                    $latest_visitors[] = $tmparr;
                }
            }

            $twitter = $user->twitter;

            $temps = DB::table('users_badges')->where('userid', $user->userid)->where('selected', 1)->get();
            $badges = array();

            foreach ($temps as $temp) {
                $bg = DB::table('badges')->where('badgeid', $temp->badgeid)->first();

                if (count($bg)) {
                    $bgArray = array(
                        'badgeid' => $temp->badgeid,
                        'name' => $bg->name,
                        'description' => $bg->description
                    );

                    $badges[] = $bgArray;
                }
            }

            $can_delete_vm = UserHelper::haveGeneralModPerm($userid, 8);

            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/profile/' . $array['clean_username'] . '/page/')->render();

            $can_ban_user = false;
            $user_is_banned = false;

            $isBanned = DB::table('users_banned')->where('userid', $array['userid'])->where('banned_until', '>', time())->orWhere('banned_until', 0)->count() > 0;
            $can_edit_user = false;
            $edit_url = '';

            if (UserHelper::haveAdminPerm($userid, 4)) {
                $edit_url = '/admincp/users/edit/' . $array['userid'];
                $can_ban_user = true;
                $can_edit_user = true;
            } elseif (UserHelper::haveGeneralModPerm($userid, 2)) {
                $can_ban_user = true;
            }

            if (UserHelper::haveGeneralModPerm($userid, 1) && !$can_edit_user) {
                $edit_url = '/staff/mod/users/page/1/search/' . $array['clean_username'];
                $can_edit_user = true;
            }

            if (Auth::user()->userid == $array['userid']) {
                $can_ban_user = false;
            }

            if (UserHelper::isSuperAdmin($array['userid'])) {
                $can_edit_user = false;
                $can_ban_user = false;
            }

            $isFollowing = DB::table('followers')->where('userid', $array['userid'])->where('follower', $userid)->count();

            $isFollowing = $isFollowing > 0 ? 1 : 0;

            $returnHTML = view('profile')
                ->with('user', $array)
                ->with('visitormessages', $visitormessages)
                ->with('latest_visitors', $latest_visitors)
                ->with('twitter', $twitter)
                ->with('badges', $badges)
                ->with('background', $background)
                ->with('pagi', $pagi)
                ->with('can_delete_vm', $can_delete_vm)
                ->with('can_ban_user', $can_ban_user)
                ->with('can_edit_user', $can_edit_user)
                ->with('edit_url', $edit_url)
                ->with('verified', Auth::user()->habbo_verified)
                ->with('isFollowing', $isFollowing)
                ->with('user_is_banned', $user_is_banned)
                ->with('current_page', $pagenr)
                ->with('userbars_css', $userbars_css)
                ->with('userbars_html', $userbars_html)
                ->with('users_accolades', $users_accolades)
                ->with('accolade_count', $accolade_count)
                ->with('recentactivity', $recentactivity)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function toggleFollow(Request $request)
    {
        $response = false;
        $userid = $request->input('userid');

        $user = DB::table('users')->where('userid', $userid)->first();

        if (!count($user)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Something went wrong'));
        }
        if ($userid == Auth::user()->userid) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can not follow yourself'));
        }

        $isFollowing = DB::table('followers')->where('userid', $userid)->where('follower', Auth::user()->userid)->count();

        $isFollowing = $isFollowing > 0 ? 1 : 0;

        $noticeText = '';
        $btnText = '';

        if ($isFollowing) {
            $noticeText = 'Unfollowed the user!';
            $btnText = 'Follow';
            $response = true;

            DB::table('followers')->where('userid', $userid)->where('follower', Auth::user()->userid)->delete();
        } else {
            $btnText = 'Unfollow';
            $noticeText = 'Followed the user!';

            $spammer = DB::table('livewall')->where('userid', Auth::user()->userid)->where('item_id', $userid)->count();


            if ($spammer == 0) {
                $btnText = 'Unfollow';
                $noticeText = 'Followed the user!';
                DB::table('livewall')->insert([
                    'userid' => Auth::user()->userid,
                    'forum' => 0,
                    'forumid' => 0,
                    'item_id' => $userid,
                    'item_type' => 5,
                    'message' => 'followed',
                    'dateline' => time()
                ]);
            }
            DB::table('followers')->insert([
                'userid' => $userid,
                'follower' => Auth::user()->userid,
                'dateline' => time()
            ]);

            $response = true;

            $user = DB::table('users')->where('userid', $userid)->first();
            if ($user->extras & 2) {
                DB::table('notifications')->insert([
                    'postuserid' => Auth::user()->userid,
                    'reciveuserid' => $userid,
                    'content' => 9,
                    'contentid' => Auth::user()->userid,
                    'dateline' => time(),
                    'read_at' => 0
                ]);
            }

            DB::table('users')->where('userid', $userid)->update([
                'xpcount' => DB::raw('xpcount+2')
            ]);
        }

        return response()->json(array('success' => true, 'response' => $response, 'noticeText' => $noticeText, 'btnText' => $btnText));
    }

    public function postEditThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $content = $request->input('content');
        $title = $request->input('title');
        $userid = Auth::user()->userid;
        $prefixid = $request->input('prefixid');
        $poll_results_visible = $request->input('poll_results_visible');

        $poll_enabled = $request->input('poll_enabled');
        $poll_edit = $request->input('poll_edit');
        $answers = $request->input('answers');

        if ($title != "") {
            $thread = DB::table('threads')->where('threadid', $threadid)->first();

            $forum = DB::table('forums')->where('forumid', $thread->forumid)->first();

            if ($thread->postuserid == $userid or UserHelper::haveModPerm($userid, $thread->forumid, 1)) {
                /* NOW LETS TAG PEOPLE ! */
                $tagged_userids = array();

                if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_]{1,}))/', $content, $match)) {
                    foreach ($match['UsernameOnly'] as $usr) {
                        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->where('userid', '!=', $userid)->first();

                        if (count($user)) {
                            $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                            if (!in_array($user->userid, $tagged_userids)) {
                                $tagged_userids[] = array('userid' => $user->userid, 'content' => 1);
                            }
                        }
                    }
                }

                /* SEND NOTIFICATION TO ALL TAGGED USERS */
                foreach ($tagged_userids as $tagged) {
                    $check = DB::table('notifications')->where('content', 1)->where('contentid', $thread->firstpostid)->count();

                    if ($check == 0) {
                        DB::table('notifications')->insert([
                            'postuserid' => $userid,
                            'reciveuserid' => $tagged['userid'],
                            'content' => $tagged['content'],
                            'contentid' => $thread->firstpostid,
                            'dateline' => time(),
                            'read_at' => 0
                        ]);
                    }
                }

                if ($prefixid > 0) {
                    $prefix = DB::table('prefixes')->where('prefixid', $prefixid)->first();

                    if (count($prefix)) {
                        $forumids = array($thread->forumid, 0);

                        $ts = $forum;
                        $run = true;

                        while ($run) {
                            if ($ts->parentid > 0) {
                                $ts = DB::table('forums')->where('forumid', $ts->parentid)->first();

                                if (count($ts)) {
                                    $forumids[] = $ts->forumid;
                                } else {
                                    break;
                                }
                            } else {
                                $run = false;
                            }
                        }

                        if (!in_array($prefix->forumid, $forumids)) {
                            $prefixid = 0;
                        }
                    } else {
                        $prefixid = 0;
                    }
                }

                DB::table('threads')->where('threadid', $threadid)->update([
                    'title' => $title,
                    'got_poll' => $poll_enabled,
                    'prefixid' => $prefixid,
                    'lastedited' => time()
                ]);

                DB::table('posts')->where('postid', $thread->firstpostid)->update([
                    'content' => $content,
                    'lastedit' => time(),
                    'lastedituser' => Auth::user()->userid
                ]);

                if ($poll_edit == 1) {
                    //Edit the answers
                    foreach ($answers as $answer) {
                        $ans = explode('.:.', $answer);
                        DB::table('thread_poll_answers')->where('pollanswerid', $ans[0])->update([
                            'answer' => $ans[1],
                            'visible' => $poll_results_visible
                        ]);
                    }
                } else {
                    if ($poll_enabled == 1) {
                        foreach ($answers as $answer) {
                            DB::table('thread_poll_answers')->insert([
                                'threadid' => $threadid,
                                'answer' => $answer,
                                'visible' => $poll_results_visible
                            ]);
                        }
                    }
                }

                return response()->json(array('success' => true, 'response' => true));
            } else {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'You don\'t have permission to edit this!'));
            }
        }

        return response()->json(array('success' => true, 'response' => false, 'message' => 'Can\'t leave title empty!'));
    }

    public function getEditThread($threadid)
    {
        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (count($thread)) {
            if ($thread->postuserid == Auth::user()->userid or UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 1)) {
                $post = DB::table('posts')->where('postid', $thread->firstpostid)->first();
                if (count($post)) {
                    $content = $post->content;

                    $findTags = true;
                    while ($findTags) {
                        if (preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                            foreach ($match[1] as $mh) {
                                $content = str_replace("[mention]" . $mh . "[/mention]", '@' . $mh, $content);
                            }
                        }

                        if (!preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                            $findTags = false;
                        }
                    }

                    $answers = array();

                    $temps = DB::table('thread_poll_answers')->where('threadid', $thread->threadid)->orderBy('pollanswerid', 'ASC')->get();

                    foreach ($temps as $temp) {
                        $array = array(
                            'pollanswerid' => $temp->pollanswerid,
                            'answer' => $temp->answer
                        );

                        $answers[] = $array;
                    }

                    $ts = DB::table('forums')->where('forumid', $thread->forumid)->first();
                    if (count($ts)) {
                        $forumids = array($ts->forumid, 0);
                        $run = true;
                        while ($run) {
                            if ($ts->parentid > 0) {
                                $ts = DB::table('forums')->where('forumid', $ts->parentid)->first();

                                if (count($ts)) {
                                    $forumids[] = $ts->forumid;
                                } else {
                                    break;
                                }
                            } else {
                                $run = false;
                            }
                        }
                        $prefixes = DB::table('prefixes')->whereIn('forumid', $forumids)->get();
                    } else {
                        $prefixes = array();
                    }


                    $returnHTML = view('forum.extras.editThread')
                        ->with('content', $content)
                        ->with('thread', $thread)
                        ->with('answers', $answers)
                        ->with('prefixes', $prefixes)
                        ->render();

                    return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
                }
            }
        }

        return redirect()->route('getErrorPerm');
    }

    public function rateArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $userid = Auth::user()->userid;
        $helpful = $request->input('helpful');
        $badge = $request->input('badge');
        $recommended = $request->input('recommended');
        $rate = $request->input('rate');
        $response = false;

        $check = DB::table('guide_rates')->where('articleid', $articleid)->where('userid', $userid)->first();
        if (!count($check)) {
            if (is_numeric($articleid) && is_numeric($rate)) {
                if ($helpful == 1 || $helpful == 0) {
                    if ($badge == 1 || $badge == 0) {
                        if ($recommended == 1 || $recommended == 0) {
                            DB::table('guide_rates')->insert(
                                ['userid' => $userid, 'articleid' => $articleid, 'helpful' => $helpful, 'badge' => $badge, 'recommended' => $recommended, 'rate' => $rate]
                            );
                            $response = true;
                            $message = "Success!";
                        } else {
                            $message = "rec empty";
                        }
                    } else {
                        $message = "badge empty";
                    }
                } else {
                    $message = "helpful empty";
                }
            } else {
                $message = "not numbers";
            }
        } else {
            $message = "Already rated!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function likeArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $userid = Auth::user()->userid;
        $response = false;

        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if (count($article)) {
            if ($article->userid != $userid) {
                $check = DB::table('notifications')->where('postuserid', $userid)->where('content', 3)->where('contentid', $articleid)->count();

                if ($check == 0) {
                    DB::table('notifications')->insert([
                        'postuserid' => $userid,
                        'reciveuserid' => $article->userid,
                        'content' => 16,
                        'contentid' => $articleid,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);

                    DB::table('users')->where('userid', $article->userid)->update([
                        'likecount' => DB::raw('likecount+1')
                    ]);

                    $response = true;
                }
            }
        }

        $likers = QuestsHelper::getQuestLikers($articleid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function unlikeArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $userid = Auth::user()->userid;
        $response = false;

        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if (count($article)) {
            if ($article->userid != $userid) {
                DB::table('notifications')->where('content', 15)->where('postuserid', $userid)->where('contentid', $article->articleid)->delete();
                $response = true;

                DB::table('users')->where('userid', $article->userid)->update([
                    'likecount' => DB::raw('likecount-1')
                ]);
            }
        }

        $likers = QuestsHelper::getQuestLikers($articleid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function likePost(Request $request)
    {
        $postid = $request->input('postid');
        $userid = Auth::user()->userid;
        $response = false;

        $post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();
        $amountPosts = DB::table('posts')->where('postid', '<', $post->postid)->where('threadid', $post->threadid)->where('visible', 1)->count();

        $page = ceil($amountPosts / 10);

        if ($page < 1) {
            $page = 1;
        }
        if (count($post)) {
            if ($post->userid != $userid) {
                $check = DB::table('notifications')->where('postuserid', $userid)->where('content', 3)->where('contentid', $postid)->count();

                if ($check == 0) {
                    DB::table('notifications')->insert([
                        'postuserid' => $userid,
                        'reciveuserid' => $post->userid,
                        'content' => 3,
                        'contentid' => $postid,
                        'dateline' => time(),
                        'read_at' => 0,
                        'page' => $page
                    ]);

                    DB::table('users')->where('userid', $post->userid)->update([
                        'likecount' => DB::raw('likecount+1'),
                        'xpcount' => DB::raw('xpcount+1'),
                        'credits' => DB::raw('credits+1')
                    ]);

                    $thread = DB::table('threads')->where('threadid', $post->threadid)->first();

                    if (count($thread)) {
                        DB::table('livewall')->insert([
                            'userid' => Auth::user()->userid,
                            'forum' => 1,
                            'forumid' => $thread->forumid,
                            'item_id' => $post->postid,
                            'item_type' => 2,
                            'message' => 'liked',
                            'dateline' => time()
                        ]);
                    }

                    $response = true;
                }
            }
        }

        $likers = ForumHelper::getLikers($postid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function unlikePost(Request $request)
    {
        $postid = $request->input('postid');
        $userid = Auth::user()->userid;
        $response = false;

        $post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();

        if (count($post)) {
            if ($post->userid != $userid) {
                DB::table('notifications')->where('content', 3)->where('postuserid', $userid)->where('contentid', $post->postid)->delete();
                $response = true;

                DB::table('users')->where('userid', $post->userid)->update([
                    'likecount' => DB::raw('likecount-1')
                ]);
            }
        }

        $likers = ForumHelper::getLikers($postid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function reportComment(Request $request)
    {
        $commentid = $request->input('report_commentid');
        $reason = $request->input('reason');
        $pagenumber = $request->input('pagenumber');
        $message = "";
        $response = false;

        $check = DB::table('posts')->where('userid', Auth::user()->userid)->where('dateline', '>', time() - 5)->count();

        if ($check == 0) {
            if ($reason != "") {
                $comment = DB::table('article_comments')->where('commentid', $commentid)->first();

                if (count($comment)) {
                    $article = DB::table('articles')->where('articleid', $comment->articleid)->first();
                    if (count($article)) {
                        if ($comment->userid != Auth::user()->userid) {
                            $modforums = DB::table('moderation_forums')->orderBy('mfid', 'DESC')->get();
                            $user = DB::table('users')->where('userid', $comment->userid)->first();
                            $time = time();

                            if (count($user)) {
                                $date = date('d/m/y', $time);
                                $article_name = $article->title;
                                $reporter = Auth::user()->username;
                                $reported = $user->username;
                                $type = "comment";

                                $content = '[intern_link=/profile/' . $reporter . '/page/1]' . $reporter . '[/intern_link] has reported a comment in ' . $article_name . '

                                [b]User reported:[/b] [intern_link=/profile/' . $reported . '/page/1]' . $reported . '[/intern_link]
                                [b]Thread:[/b] [intern_link]/article/' . $article->articleid . '/page/' . $pagenumber . '[/intern_link]

                                [b]Reason[/b]:
                                [quote]' . $reason . '[/quote]

                                [b]Original post:[/b]
                                [quote]' . $comment->content . '[/quote]';

                                foreach ($modforums as $modforum) {
                                    $title = $modforum->title;
                                    $forumid = $modforum->forumid;

                                    $title = str_replace("{date}", $date, $title);
                                    $title = str_replace("{thread}", $article_name, $title);
                                    $title = str_replace("{reporter}", $reporter, $title);
                                    $title = str_replace("{reported}", $reported, $title);
                                    $title = str_replace("{type}", $type, $title);

                                    $postid = DB::table('posts')->insertGetId([
                                        'threadid' => 0,
                                        'username' => $reporter,
                                        'userid' => Auth::user()->userid,
                                        'dateline' => $time,
                                        'lastedit' => 0,
                                        'lastedituser' => 0,
                                        'content' => $content,
                                        'ipaddress' => $request->ip(),
                                        'visible' => 1
                                    ]);

                                    $threadid = DB::table('threads')->insertGetId([
                                        'title' => $title,
                                        'forumid' => $modforum->forumid,
                                        'postuserid' => Auth::user()->userid,
                                        'dateline' => $time,
                                        'firstpostid' => $postid,
                                        'lastpost' => $time,
                                        'lastpostid' => $postid,
                                        'lastedited' => 0,
                                    ]);

                                    DB::table('forums')->where('forumid', $modforum->forumid)->update([
                                        'posts' => DB::raw('posts+1'),
                                        'threads' => DB::raw('threads+1'),
                                        'lastpost' => $time,
                                        'lastpostid' => $postid,
                                        'lastposterid' => Auth::user()->userid,
                                        'lastthread' => $time,
                                        'lastthreadid' => $threadid
                                    ]);

                                    DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);
                                    $time += 10;
                                }

                                $response = true;
                            } else {
                                $message = "This user no longer exists!";
                            }
                        } else {
                            $message = "You can't report yourself!";
                        }
                    } else {
                        $message = "This article no longer exists!";
                    }
                } else {
                    $message = "This comment no longer exists";
                }
            } else {
                $message = "Reason can't be empty!";
            }
        } else {
            $message = "You are reporting too fast!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function likeComment(Request $request)
    {
        $commentid = $request->input('commentid');
        $userid = Auth::user()->userid;
        $response = false;

        $comment = DB::table('article_comments')->where('commentid', $commentid)->first();

        if (count($comment)) {
            if ($comment->userid != $userid) {
                $check = DB::table('notifications')->where('postuserid', $userid)->where('content', 16)->where('contentid', $commentid)->count();

                if ($check == 0) {
                    DB::table('notifications')->insert([
                        'postuserid' => $userid,
                        'reciveuserid' => $comment->userid,
                        'content' => 16,
                        'contentid' => $comment->commentid,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);

                    DB::table('users')->where('userid', $comment->userid)->update([
                        'likecount' => DB::raw('likecount+1')
                    ]);

                    $response = true;
                }
            }
        }

        $likers = QuestsHelper::getCommentLikers($commentid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function unlikeComment(Request $request)
    {
        $commentid = $request->input('commentid');
        $userid = Auth::user()->userid;
        $response = false;

        $comment = DB::table('article_comments')->where('commentid', $commentid)->first();

        if (count($comment)) {
            if ($comment->userid != $userid) {
                DB::table('notifications')->where('content', 16)->where('postuserid', $userid)->where('contentid', $comment->commentid)->delete();
                $response = true;

                DB::table('users')->where('userid', $comment->userid)->update([
                    'likecount' => DB::raw('likecount-1')
                ]);
            }
        }

        $likers = QuestsHelper::getCommentLikers($commentid);

        return response()->json(array('success' => true, 'response' => $response, 'likers' => $likers));
    }

    public function getPermError()
    {
        $returnHTML = view('errors.perm')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML, 'permError' => !Auth::check()));
    }

    public function getthreadBanned()
    {
        $returnHTML = view('errors.threadban')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML, 'permError' => !Auth::check()));
    }

    public function getNotFound()
    {
        $returnHTML = view('errors.notfound')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBrowser($user_agent) {
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
        elseif (strpos($user_agent, 'Edge')) return 'Edge';
        elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
        elseif (strpos($user_agent, 'Safari')) return 'Safari';
        elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

        return 'Other';
    }

    //Main function to get the application + layout
    public function getApp(Request $request)
    {
        $avatar = 0;

        $daytime = false;
        $adjtime = ForumHelper::returnTimeAfterTimezone(time());
        if (date('H', $adjtime) > 9 && date('H', $adjtime) < 19) {
            $daytime = true;
        }

        if (DB::table('visitor_browsers')->where('ip', $request->ip())->where('dateline', '>', (time() - 3600))->count() == 0) {
            DB::table('visitor_browsers')->insert([
                'browser' => $this->getBrowser($_SERVER['HTTP_USER_AGENT']),
                'ip' => $request->ip(),
                'dateline' => time()
            ]);
        }

        $bbmode = false;
        if (Auth::check()) {
            $bbmode = UserHelper::haveExtraOn(Auth::user()->userid, 8);
            $avatar = UserHelper::getAvatar(Auth::user()->userid);
            $questcheck = DB::table('active_quests')->where('userid', Auth::user()->userid)->get();
            if (!count($questcheck)) {
                $queststemp = DB::table('daily_quests')->get();
                if (count($queststemp)) {
                    $quests = $queststemp->random(3);
                    foreach ($quests as $quest) {
                        switch ($quest->type) {
                            case 1:
                                $current = Auth::user()->postcount;
                                $target = $current + $quest->amount;
                                break;
                            case 2:
                                $current = DB::table('guide_shares')->where('referrerid', Auth::user()->userid)->count();
                                $target = $current + $quest->amount;
                                break;
                            case 3:
                                $current = Auth::user()->commentcount;
                                $target = $current + $quest->amount;
                                break;
                            case 4:
                                $current = DB::table('users')->where('referdby', Auth::user()->userid)->count();
                                $target = $current + $quest->amount;
                                break;
                            case 5:
                                $current = Auth::user()->likecount;
                                $target = $current + $quest->amount;
                                break;
                            case 6:
                                $current = DB::table('threads')->where('postuserid', Auth::user()->userid)->count();
                                $target = $current + $quest->amount;
                                break;
                            case 7:
                                $current = DB::table('visitor_messages')->where('postuserid', Auth::user()->userid)->count();
                                $target = $current + $quest->amount;
                                break;
                        }

                        DB::table('active_quests')->insert([
                            'userid' => Auth::user()->userid,
                            'questid' => $quest->questid,
                            'type' => $quest->type,
                            'boxid' => $quest->boxid,
                            'text' => $quest->text,
                            'current' => $current,
                            'target' => $target

                        ]);
                    }
                }
            }
        }

        $day = date('N');
        $hour = date('H');
        $currenttime = $hour;
        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();
            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $currenttime = ($hour - $timezone->value) % 24;
                } else {
                    $currenttime = ($hour + $timezone->value) % 24;
                }
            }
        }

        $currentEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour)->where('type', 1)->first();
        $currentEvent = array(
            'id' => count($currentEventTemp) ? $currentEventTemp->userid : "0",
            'event' => count($currentEventTemp) ? DB::table('event_types')->where('typeid', $currentEventTemp->event)->value('event') : "Unbooked",
            'name' => count($currentEventTemp) ? UserHelper::getHabbo($currentEventTemp->userid, true) : 'Unbooked',
            'image' => count($currentEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $currentEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $currentEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')

        );
        $nextEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour + 1)->where('type', 1)->first();
        $nextEvent = array(
            'id' => count($nextEventTemp) ? $nextEventTemp->userid : "0",
            'name' => count($nextEventTemp) ? UserHelper::getHabbo($nextEventTemp->userid, true) : 'Unbooked',
            'event' => count($nextEventTemp) ? DB::table('event_types')->where('typeid', $nextEventTemp->event)->value('event') : "Unbooked",
            'image' => count($nextEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')
        );
        $laterEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour + 2)->where('type', 1)->first();
        $laterEvent = array(
            'id' => count($laterEventTemp) ? $laterEventTemp->userid : "0",
            'name' => count($laterEventTemp) ? UserHelper::getHabbo($laterEventTemp->userid, true) : 'Unbooked',
            'event' => count($laterEventTemp) ? DB::table('event_types')->where('typeid', $laterEventTemp->event)->value('event') : "Unbooked",
            'image' => count($nextEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')
        );

        $nextTime = ($currenttime + 1) % 24;
        $laterTime = ($currenttime + 2) % 24;

        $ip = "";
        $port = "";

        $radio_details = DB::table('radio_details')->orderBy('infoid', 'desc')->first();

        if (count($radio_details)) {
            $ip = $radio_details->ip;
            $port = $radio_details->port;
        }

        $cookie = $request->cookie('radioMuted');
        $radio_muted = false;

        if (isset($cookie)) {
            if ($cookie == 1) {
                $radio_muted = true;
            }
        }

        $ad1temp = DB::table('carousel')->where('adverts', 1)->first();
        $ad2temp = DB::table('carousel')->where('adverts', 2)->first();

        if (!count($ad1temp)) {
            $ad1temp = DB::table('carousel')->first();
        }
        if (!count($ad2temp)) {
            $ad2temp = DB::table('carousel')->first();
        }

        $ad1 = array(
            'image' => asset('_assets/img/carousel/' . $ad1temp->carouselid . '.gif'),
            'text' => $ad1temp->text,
            'link' => $ad1temp->link
        );

        $ad2 = array(
            'image' => asset('_assets/img/carousel/' . $ad2temp->carouselid . '.gif'),
            'text' => $ad2temp->text,
            'link' => $ad2temp->link
        );

        $temp = DB::table('articles')->where('approved', 1)->where('type', 0)->orderBy('articleid', 'DESC')->first();
        $latest_id = 0;
        $completed = QuestsHelper::haveCompletedQuest($temp->articleid);
        $latest_article = array(
            'username' => UserHelper::getUsername($temp->userid),
            'clean_username' => UserHelper::getUsername($temp->userid, true),
            'dateline' => ForumHelper::getTimeInDate($temp->dateline, true, false, 'd/m/Y'),
            'title' => $temp->title,
            'type' => $temp->type,
            'difficulty' => $temp->difficulty,
            'paid' => $temp->paid,
            'available' => $temp->available,
            'badge_code' => explode(',', $temp->badge_code)[0],
            'image' => asset('_assets/img/thumbnails/' . $temp->articleid . '.gif'),
            'articleid' => $temp->articleid,
            'completed' => $completed
        );
        $latest_id = $latest_id < $temp->articleid ? $temp->articleid : $latest_id;


        $active_users = array();
        $time = time() - 3600;
        $temps = DB::table('users')->where('lastactivity', '>', $time)->orderBy('lastactivity', 'DESC')->take(28)->get();
        foreach ($temps as $temp) {
            $active_users[] = array(
                'username' => $temp->username,
                'avatar' => UserHelper::getAvatar($temp->userid),
                'lastactivity' => ForumHelper::timeAgo($temp->lastactivity),
                'habbo' => UserHelper::getHabbo($temp->userid, true)

            );
        }

        $homePage = '';

        if (Auth::check()) {
            $homePage = Auth::user()->homePage;
        }

        $default_css = "";
        $themeid = 0;
        if (Auth::check()) {
            $themeid = Auth::user()->theme;
        }
        if ($themeid == 0) {
            $theme = DB::table('themes')->where('default_theme', 1)->first();
        } else {
            $theme = DB::table('themes')->where('themeid', $themeid)->first();
        }

        if (count($theme)) {
            $default_css = $theme->style;
        }

        $dnas_data = StaffHelper::getRadioStats();
        $song = "";
        $listeners = "";
        $dj = "";
        $album_art = "";
        if ($dnas_data != null) {
            $song = $dnas_data['SONGTITLE'];
            $listeners = $dnas_data['UNIQUELISTENERS'];
            $dj = $dnas_data['SERVERTITLE'];

            $song_temp = explode('-', $song);
            if (is_array($song_temp)) {
                $song_temp = trim($song_temp[0]);
            }

            $album_art = StaffHelper::getRadioAlbum($song_temp);
        }

        $onlinetime = time() - 3600;
        $onlinetimeguests = time() - 1800;
        $onlinetodaytime = time() - 86400;
        $online_users = DB::table('users')->where('lastactivity', '>', $onlinetime)->orderBy('lastactivity', 'DESC')->count();
        $online_guests = DB::table('sessions')->where('user_id', null)->where('last_activity', '>', $onlinetimeguests)->orderBy('last_activity', 'DESC')->count();
        $online_users_today = DB::table('users')->where('lastactivity', '>', $onlinetodaytime)->orderBy('lastactivity', 'DESC')->count();

        $motm = array();
        $temps = DB::table('motm')->orderBy('dateline', 'DESC')->take(2)->get();
        foreach ($temps as $temp) {
            $motm[] = array(
                'username' => UserHelper::getUsername($temp->motmuserid),
                'clean_username' => UserHelper::getUsername($temp->motmuserid, true),
                'avatar' => UserHelper::getAvatar($temp->motmuserid),
                'comment' => $temp->comment,
            );
        }

        $pcmw = array();
        $temps = DB::table('photo_comp')->orderBy('dateline', 'DESC')->take(2)->get();
        foreach ($temps as $temp) {
            $pcmw[] = array(
                'username' => UserHelper::getUsername($temp->pcuserid),
                'clean_username' => UserHelper::getUsername($temp->pcuserid, true),
                'avatar' => UserHelper::getAvatar($temp->pcuserid),
                'comment' => $temp->comment,
            );
        }

        $notices = array();
        $temps = DB::table('site_notices')->where('expiry', '>', time())->orWhere('expiry', '0')->orderBy('noticeid', 'DESC')->get();

        foreach ($temps as $temp) {
            if ($temp->enabled === 1) {
                $bbcodes = DB::table('bbcodes')->get();
                $title = ForumHelper::fixContent($temp->title);
                $body = ForumHelper::fixContent($temp->body);
                $body = ForumHelper::replaceEmojis($body);
                $body = ForumHelper::bbcodeParser($body, $bbcodes);

                $notices[] = array(
                    'noticeid' => $temp->noticeid,
                    'type' => $temp->type,
                    'title' => $title,
                    'body' => $body,
                );
            }
        }

        $stats = DB::table('radio_stats')->where('dateline', '>', $time - 60)->first();
        if ($stats) {
            $djhabbo = UserHelper::getHabbo($stats->djid, true);
        } else {
            $djhabbo = UserHelper::getHabbo(96, true);
        }

        return view('layout.layout')
            ->with('ip', $ip)
            ->with('port', $port)
            ->with('djhabbo', $djhabbo)
            ->with('avatar', $avatar)
            ->with('radio_muted', $radio_muted)
            ->with('latest_article', $latest_article)
            ->with('ad1', $ad1)
            ->with('ad2', $ad2)
            ->with('active_users', $active_users)
            ->with('daytime', $daytime)
            ->with('latest_id', $latest_id)
            ->with('notices', $notices)
            ->with('homePage', $homePage)
            ->with('default_css', $default_css)
            ->with('song', $song)
            ->with('listeners', $listeners)
            ->with('dj', $dj)
            ->with('album_art', $album_art)
            ->with('online_users', $online_users)
            ->with('online_guests', $online_guests)
            ->with('online_users_today', $online_users_today)
            ->with('motm', $motm)
            ->with('pcmw', $pcmw)
            ->with('bbmode', $bbmode)
            ->with('currentEvent', $currentEvent)
            ->with('nextEvent', $nextEvent)
            ->with('laterEvent', $laterEvent)
            ->with('currenttime', $currenttime)
            ->with('nextTime', $nextTime)
            ->with('laterTime', $laterTime);
    }

    public function getActiveUsers()
    {
        $active_users = array();
        $time = time() - 3600;
        $temps = DB::table('users')->where('lastactivity', '>', $time)->orderBy('lastactivity', 'DESC')->take(28)->get();
        foreach ($temps as $temp) {
            $active_users[] = array(
                'username' => $temp->username,
                'avatar' => UserHelper::getAvatar($temp->userid),
                'lastactivity' => ForumHelper::timeAgo($temp->lastactivity),
                'habbo' => UserHelper::getHabbo($temp->userid, true)

            );
        }

        return $active_users;
    }

    public function getSiteNotices()
    {
        $bbcodes = DB::table('bbcodes')->get();
        $site_notices = array();
        $time = time() - 3600;
        $temps = DB::table('site_notices')->where('enabled', 1)->orderBy('noticeid', 'DESC')->get();
        foreach ($temps as $temp) {
            $title = ForumHelper::fixContent($temp->title);
            $body = ForumHelper::fixContent($temp->body);
            $body = ForumHelper::replaceEmojis($body);
            $body = ForumHelper::bbcodeParser($body, $bbcodes);

            $site_notices[] = array(
                'noticeid' => $temp->noticeid,
                'title' => $title,
                'body' => $body,
                'type' => $temp->type
            );
        }

        return $site_notices;
    }


    public function getHome($local = false)
    {
        $skip = 0;
        $gdpr = false;

        if (Auth::check()) {
            $userid = Auth::user()->userid;
            if (Auth::user()->gdpr == 1) {
                $gdpr = true;
            }
        } else {
            $gdpr = false;
            $userid = 0;
        }

        $day = date('N');
        $hour = date('H');

        $time = $hour;
        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();
            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $time = ($hour - $timezone->value) % 24;
                } else {
                    $time = ($hour + $timezone->value) % 24;
                }
            }
        }

        $userid = 0;
        if (Auth::check()) {
            $userid = Auth::user()->userid;
        }
        $forumtemps = DB::table('forums')->where('parentid', '>', 0)->where('forumid', '!=', '1049')->where('forumid', '!=', '1050')->get();

        foreach ($forumtemps as $temp) {
            if (UserHelper::haveForumPerm($userid, $temp->forumid, 32)) {
                $forums[] = $temp->forumid;
            }
        }

        $threadtemps = DB::table('threads')->whereIn('forumid', $forums)->where('visible', 1)->orderBy('dateline', 'DESC')->take(5)->get();
        foreach ($threadtemps as $temp) {
            $prefix = DB::table('prefixes')->where('prefixid', $temp->prefixid)->first();
            $threadprefix = '';
            if (count($prefix)) {
                $threadprefix = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ';
            }

            $title = $temp->title;
            if (strlen($title) > 84) {
                $title = substr($title, 0, 42) . "...";
            }

            $threads[] = array(
                'id' => $temp->threadid,
                'title' => $title,
                'username' => UserHelper::getUsername($temp->postuserid),
                'threadprefix' => $threadprefix,
                'posted' => ForumHelper::timeAgo($temp->dateline)
            );
        }

        $radioTimetable = array();
        for ($i = 0; $i < 3; $i++) {
            $dj = DB::table('timetable')->where('day', $day)->where('time', $hour + $i)->where('type', 0)->value('userid');
            $radioTimetable[] = array(
                'time' => ($time + $i) % 24,
                'name' => isset($dj) ? UserHelper::getUsername($dj) : 'Unbooked',
                'habbo' => isset($dj) ? UserHelper::getHabbo($dj, true) : 'null'
            );
        }

        $currentEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour)->where('type', 1)->first();
        $currentEvent = array(
            'id' => count($currentEventTemp) ? $currentEventTemp->userid : "0",
            'event' => count($currentEventTemp) ? DB::table('event_types')->where('typeid', $currentEventTemp->event)->value('event') : "Unbooked",
            'name' => count($currentEventTemp) ? UserHelper::getHabbo($currentEventTemp->userid, true) : 'Unbooked',
            'image' => count($currentEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $currentEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $currentEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')

        );
        $nextEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour + 1)->where('type', 1)->first();
        $nextEvent = array(
            'id' => count($nextEventTemp) ? $nextEventTemp->userid : "0",
            'name' => count($nextEventTemp) ? UserHelper::getHabbo($nextEventTemp->userid, true) : 'Unbooked',
            'event' => count($nextEventTemp) ? DB::table('event_types')->where('typeid', $nextEventTemp->event)->value('event') : "Unbooked",
            'image' => count($nextEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')
        );
        $laterEventTemp = DB::table('timetable')->where('day', $day)->where('time', $hour + 2)->where('type', 1)->first();
        $laterEvent = array(
            'id' => count($laterEventTemp) ? $laterEventTemp->userid : "0",
            'name' => count($laterEventTemp) ? UserHelper::getHabbo($laterEventTemp->userid, true) : 'Unbooked',
            'event' => count($laterEventTemp) ? DB::table('event_types')->where('typeid', $laterEventTemp->event)->value('event') : "Unbooked",
            'image' => count($nextEventTemp) ? (file_exists(asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif')) ? asset('_assets/img/eventthumbnails/' . $nextEventTemp->event . '.gif') : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')) : asset('_assets/img/eventimages/' . rand(1, 10) . '.png')
        );

        $nextTime = ($time + 1) % 24;
        $laterTime = ($time + 2) % 24;

        $temps = DB::table('sotw')->orderBy('sotwID', 'DESC')->first();
        $sotw = array(
            'global_management' => array(
                'text' => 'Global Management',
                'name' => $temps->global_management != 0 ? UserHelper::getUsername($temps->global_management, true) : null,
                'habbo' => $temps->global_management != 0 ? UserHelper::getHabbo($temps->global_management, true) : null,
                'alt' => rand(1, 17)
            ),
            'eu_management' => array(
                'text' => 'EU Management',
                'name' => $temps->eu_management != 0 ? UserHelper::getUsername($temps->eu_management, true) : null,
                'habbo' => $temps->eu_management != 0 ? UserHelper::getHabbo($temps->eu_management, true) : null,
                'alt' => rand(1, 17)
            ),
            'na_management' => array(
                'text' => 'NA Management',
                'name' => $temps->na_management != 0 ? UserHelper::getUsername($temps->na_management, true) : null,
                'habbo' => $temps->na_management != 0 ? UserHelper::getHabbo($temps->na_management, true) : null,
                'alt' => rand(1, 17)
            ),
            'oc_management' => array(
                'text' => 'OC Management',
                'name' => $temps->oc_management != 0 ? UserHelper::getUsername($temps->oc_management, true) : null,
                'habbo' => $temps->oc_management != 0 ? UserHelper::getHabbo($temps->oc_management, true) : null,
                'alt' => rand(1, 17)
            ),
            'moderation' => array(
                'text' => 'Moderation',
                'name' => $temps->moderation != 0 ? UserHelper::getUsername($temps->moderation, true) : null,
                'habbo' => $temps->moderation != 0 ? UserHelper::getHabbo($temps->moderation, true) : null,
                'alt' => rand(1, 17)
            ),
            'eu_radio' => array(
                'text' => 'EU Radio',
                'name' => $temps->eu_radio != 0 ? UserHelper::getUsername($temps->eu_radio, true) : null,
                'habbo' => $temps->eu_radio != 0 ? UserHelper::getHabbo($temps->eu_radio, true) : null,
                'alt' => rand(1, 17)
            ),
            'eu_events' => array(
                'text' => 'EU Events',
                'name' => $temps->eu_events != 0 ? UserHelper::getUsername($temps->eu_events, true) : null,
                'habbo' => $temps->eu_events != 0 ? UserHelper::getHabbo($temps->eu_events, true) : null,
                'alt' => rand(1, 17)
            ),
            'na_radio' => array(
                'text' => 'NA Radio',
                'name' => $temps->na_radio != 0 ? UserHelper::getUsername($temps->na_radio, true) : null,
                'habbo' => $temps->na_radio != 0 ? UserHelper::getHabbo($temps->na_radio, true) : null,
                'alt' => rand(1, 17)
            ),
            'na_events' => array(
                'text' => 'NA Events',
                'name' => $temps->na_events != 0 ? UserHelper::getUsername($temps->na_events, true) : null,
                'habbo' => $temps->na_events != 0 ? UserHelper::getHabbo($temps->eu_events, true) : null,
                'alt' => rand(1, 17)
            ),
            'oc_radio' => array(
                'text' => 'OC Radio',
                'name' => $temps->oc_radio != 0 ? UserHelper::getUsername($temps->oc_radio, true) : null,
                'habbo' => $temps->oc_radio != 0 ? UserHelper::getHabbo($temps->oc_radio, true) : null,
                'alt' => rand(1, 17)
            ),
            'oc_events' => array(
                'text' => 'OC Events',
                'name' => $temps->oc_events != 0 ? UserHelper::getUsername($temps->oc_events, true) : null,
                'habbo' => $temps->oc_events != 0 ? UserHelper::getHabbo($temps->oc_events, true) : null,
                'alt' => rand(1, 17)
            ),
            'media' => array(
                'text' => 'Media',
                'name' => $temps->media != 0 ? UserHelper::getUsername($temps->media, true) : null,
                'habbo' => $temps->media != 0 ? UserHelper::getHabbo($temps->media, true) : null,
                'alt' => rand(1, 17)
            ),
            'quests' => array(
                'text' => 'Quests',
                'name' => $temps->quests != 0 ? UserHelper::getUsername($temps->quests, true) : null,
                'habbo' => $temps->quests != 0 ? UserHelper::getHabbo($temps->quests, true) : null,
                'alt' => rand(1, 17)
            ),
            'graphics' => array(
                'text' => 'Graphics',
                'name' => $temps->graphics != 0 ? UserHelper::getUsername($temps->graphics, true) : null,
                'habbo' => $temps->graphics != 0 ? UserHelper::getHabbo($temps->graphics, true) : null,
                'alt' => rand(1, 17)
            ),
            'audioprod' => array(
                'text' => 'Audio Producer',
                'name' => $temps->audioprod != 0 ? UserHelper::getUsername($temps->audioprod, true) : null,
                'habbo' => $temps->audioprod != 0 ? UserHelper::getHabbo($temps->audioprod, true) : null,
                'alt' => rand(1, 17)
            ),
            'recruitment' => array(
                'text' => 'Recruitment',
                'name' => $temps->recruitment != 0 ? UserHelper::getUsername($temps->recruitment, true) : null,
                'habbo' => $temps->recruitment != 0 ? UserHelper::getHabbo($temps->recruitment, true) : null,
                'alt' => rand(1, 17)
            )
        );

        $articles = array();
        $temps = DB::table('articles')->where('available', '!=', 0)->where('approved', 1)->take(6)->orderBy('dateline', 'DESC')->get();

        foreach ($temps as $temp) {
            $completed = QuestsHelper::haveCompletedQuest($temp->articleid);

            $author = "";

            $user = DB::table('users')->where('userid', $temp->userid)->first();

            if (count($user)) {
                $author = $user->username;
            }

            $badge = 0;
            $type = "";

            switch ($temp->type) {
                case 0:
                    $badge = 1;
                    $type = "Quest Guide";
                    break;
                case 1:
                    $type = "News Article";
                    break;
                case 2:
                    $type = "Wired Guide";
                    break;
                case 3:
                    $type = "Tips & Tricks";
                    break;
            }

            $title = str_replace(">", "&#62;", $temp->title);
            $title = strlen($title) >= 25 ? substr($title, 0, 25) . "..." : $title;

            $array = array(
                'title' => $title,
                'completed' => $completed,
                'username' => DB::table('users')->where('userid', $temp->userid)->value('username'),
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'availableID' => $temp->available,
                'snippet' => substr(preg_replace("/\[.+\]/", "", preg_replace("/\[atitle\].*\[\/atitle\]/", "", $temp->content)), 0, 85) . '...',
                'time' => ForumHelper::timeAgo($temp->dateline),
                'badge' => $badge,
                'badge_code' => explode(',', $temp->badge_code)[0],
                'type' => $temp->type,
                'difficulty' => $temp->difficulty,
                'paid' => $temp->paid,
                'articleid' => $temp->articleid
            );

            $articles[] = $array;
        }

        $returnHTML = view('home')
            // ->with('carousel', $carousel)
            ->with('articles', $articles)
            ->with('sotw', $sotw)
            ->with('gdpr', $gdpr)
            // ->with('currentDJ', $currentDJ)
            // ->with('nextDJ', $nextDJ)
            // ->with('laterDJ', $laterDJ)
            ->with('currentEvent', $currentEvent)
            ->with('nextEvent', $nextEvent)
            ->with('laterEvent', $laterEvent)
            ->with('time', $time)
            ->with('nextTime', $nextTime)
            ->with('laterTime', $laterTime)
            ->with('threads', $threads)
            ->with('radioTimetable', $radioTimetable)
            // ->with('likesLeaderboard', $likesLeaderboard)
            ->render();

        if ($local == true) {
            return $returnHTML;
        }
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postGetEditPost(Request $request)
    {
        $postid = $request->input('postid');

        $post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();
        if (count($post)) {
            $thread = DB::table('threads')->where('threadid', $post->threadid)->first();
            if (count($thread)) {
                if ($post->userid == Auth::user()->userid or UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 1)) {
                    $content = $post->content;

                    if (preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                        foreach ($match[1] as $mh) {
                            $content = str_replace("[mention]" . $mh . "[/mention]", '@' . $mh, $content);
                        }
                    }

                    if (!preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                        $findTags = false;
                    }

                    return response()->json(array('success' => true, 'response' => true, 'content' => $content));
                }
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function loadPosters($threadid)
    {
        ini_set('memory_limit', '-1');
        $response = false;

        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (count($thread)) {
            $counts = DB::select('SELECT count(*) AS number, userid FROM posts WHERE threadid = ' . $thread->threadid . ' GROUP BY userid ORDER BY count(*) DESC LIMIT 20');
            $users = array();
            foreach ($counts as $count) {
                $users[] = array(
                    'userid' => $count->userid,
                    'username' => UserHelper::getUsername($count->userid),
                    'clean_username' => UserHelper::getUsername($count->userid, true),
                    'posts' => $count->number
                );
            }

            return response()->json(array('success' => true, 'response' => true, 'users' => $users, 'count' => count($users)));
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function postGetPost(Request $request)
    {
        $postid = $request->input('postid');

        $post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();

        if (count($post)) {
            $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();

            if (count($thread)) {
                if (UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                    //remove quotes from post
                    $post_content = trim(preg_replace('/\[quotepost=(.+?);(.+?)\](.*)\[\/quotepost\]/s', '', $post->content));

                    return response()->json(array('success' => true, 'response' => true, 'postbinding' => $post->postid . ';' . $post->username, 'postData' => $post_content));
                }
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function postGetEditComment(Request $request)
    {
        $commentid = $request->input('commentid');

        $comment = DB::table('article_comments')->where('commentid', $commentid)->first();
        if (count($comment)) {
            if ($comment->userid == Auth::user()->userid) {
                $content = $comment->content;

                if (preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                    foreach ($match[1] as $mh) {
                        $content = str_replace("[mention]" . $mh . "[/mention]", '@' . $mh, $content);
                    }
                }

                if (!preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                    $findTags = false;
                }

                return response()->json(array('success' => true, 'response' => true, 'content' => $content));
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function postEditComment(Request $request)
    {
        $commentid = $request->input('commentid');
        $content = $request->input('content');

        $userid = Auth::user()->userid;

        $comment = DB::table('article_comments')->where('commentid', $commentid)->first();

        if (count($comment)) {
            if ($comment->userid == $userid) {
                /* NOW LETS TAG PEOPLE ! */
                $tagged_userids = array();

                if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_]{1,}))/', $content, $match)) {
                    foreach ($match['UsernameOnly'] as $usr) {
                        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->where('userid', '!=', $userid)->first();

                        if (count($user)) {
                            $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                            if (!in_array($user->userid, $tagged_userids)) {
                                $tagged_userids[] = array('userid' => $user->userid, 'content' => 1);
                            }
                        }
                    }
                }

                $time = time();

                DB::table('article_comments')->where('commentid', $commentid)->update([
                    'content' => $content
                ]);

                foreach ($tagged_userids as $tagged) {
                    $check = DB::table('notifications')->where('reciveuserid', $tagged['userid'])->where('content', $tagged['content'])->where('contentid', $postid)->count();

                    if ($check == 0) {
                        DB::table('notifications')->insert([
                            'postuserid' => $userid,
                            'reciveuserid' => $tagged['userid'],
                            'content' => $tagged['content'],
                            'contentid' => $postid,
                            'dateline' => $time,
                            'read_at' => 0
                        ]);
                    }
                }

                $content = ForumHelper::fixContent($content);

                $content = ForumHelper::bbcodeParser($content);

                $content = ForumHelper::fixQuotePosts($content);

                if (!preg_match_all('/\[quotepost=(.+?);(.+?)\](.*)\[\/quotepost\]/i', $content, $match)) {
                    $findQuotes = false;
                }

                if (preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                    foreach ($match[1] as $mh) {
                        $content = str_replace("[mention]" . $mh . "[/mention]", '<a href="/profile/' . $mh . '" class="web-page">@' . $mh . '</a>', $content);
                    }
                }

                if (!preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                    $findTags = false;
                }

                $content = nl2br($content);

                return response()->json(array('success' => true, 'response' => true, 'content' => $content));
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    private function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function postThread(Request $request)
    {
        $forumid = $request->input('forumid');
        $title = $request->input('title');
        $content = $request->input('content');
        $poll_enabled = $request->input('poll_enabled');
        $answers = $request->input('answers');
        $prefixid = $request->input('prefixid');
        $poll_results_visible = $request->input('poll_results_visible');

        if (count($answers) <= 0) {
            $poll_enabled = 0;
        }

        $response = true;
        $message = "";
        $time = time();

        $userid = Auth::useR()->userid;

        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (!count($forum)) {
            $message = "Forum does not exist!";
            $response = false;
        }

        if ($forumid < 1) {
            $message = "Can't post in parent forums!";
            $response = false;
        }

        if ($content == "" or $title == "") {
            $message = "Can't post empty content!";
            $response = false;
        }
        if (Auth::user()->habbo_verified == 0) {
            $message = "You must verify your habbo to post thread!";
            $response = false;
        }

        if (Auth::user()->gdpr == 0) {
            $message = "You must accept our GDPR Policy on the homepage to post a thread!";
            $response = false;
        }

        if (!UserHelper::haveForumPerm($userid, $forumid, 2) and !UserHelper::haveForumPerm($userid, $forumid, 1)) {
            $message = "You don't have permission to post in here!";
            $response = false;
        }

        $check_posting_speed = DB::table('threads')->where('postuserid', $userid)->where('dateline', '>', $time - 5)->count();

        if ($check_posting_speed != 0) {
            $message = "Whoa! Slow down, jeez!";
            $response = false;
        }

        if (!$response) {
            return response()->json(array('success' => true, 'reponse' => $response, 'message' => $message));
        }

        /* EVERYTHING IS CLEAR! NOW WE CAN ADD THE THREAD */

        $visible = 1;

        if (ForumHelper::forumHaveOption($forum->forumid, 1)) {
            $visible = 0;
        }

        /* NOW LETS TAG PEOPLE ! */
        $tagged_userids = array();

        if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_]{1,}))/', $content, $match)) {
            foreach ($match['UsernameOnly'] as $usr) {
                $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->where('userid', '!=', $userid)->first();

                if (count($user)) {
                    $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                    if (!in_array($user->userid, $tagged_userids)) {
                        $tagged_userids[] = $user->userid;
                    }
                }
            }
        }

        if ($prefixid > 0) {
            $prefix = DB::table('prefixes')->where('prefixid', $prefixid)->first();

            if (count($prefix)) {
                $forumids = array($forumid, 0);

                $ts = $forum;
                $run = true;

                while ($run) {
                    if ($ts->parentid > 0) {
                        $ts = DB::table('forums')->where('forumid', $ts->parentid)->first();

                        if (count($ts)) {
                            $forumids[] = $ts->forumid;
                        } else {
                            break;
                        }
                    } else {
                        $run = false;
                    }
                }

                if (!in_array($prefix->forumid, $forumids)) {
                    $prefixid = 0;
                }
            } else {
                $prefixid = 0;
            }
        }

        $title = ForumHelper::fixContent($title);
        $title = str_replace("’", "'", $title);

        $postid = DB::table('posts')->insertGetId([
            'threadid' => 0,
            'username' => Auth::user()->username,
            'userid' => $userid,
            'dateline' => $time,
            'content' => $content,
            'ipaddress' => $request->ip(),
            'visible' => $visible
        ]);

        $threadid = DB::table('threads')->insertGetId([
            'title' => $title,
            'forumid' => $forumid,
            'open' => 1,
            'visible' => $visible,
            'prefixid' => $prefixid,
            'replys' => 0,
            'postuserid' => $userid,
            'dateline' => $time,
            'firstpostid' => $postid,
            'lastpost' => $time,
            'lastpostuserid' => $userid,
            'got_poll' => $poll_enabled,
            'lastpostid' => $postid,
            'lastedited' => 0
        ]);

        if (Auth::user()->auto_subscribe == 1) {
            DB::table('subscription_threads')->insert([
                'userid' => Auth::user()->userid,
                'threadid' => $threadid
            ]);
        }

        if ($poll_enabled == 1) {
            foreach ($answers as $answer) {
                DB::table('thread_poll_answers')->insert([
                    'threadid' => $threadid,
                    'answer' => $answer,
                    'visible' => $poll_results_visible,
                ]);
            }
        }

        /* SEND NOTIFICATION TO ALL TAGGED USERS */
        foreach ($tagged_userids as $tagged) {
            $check = DB::table('notifications')->where('reciveuserid', $tagged)->where('content', 1)->where('contentid', $postid)->count();


            if ($check == 0) {
                DB::table('notifications')->insert([
                    'postuserid' => $userid,
                    'reciveuserid' => $tagged,
                    'content' => 1,
                    'contentid' => $postid,
                    'dateline' => $time,
                    'read_at' => 0,
                    'page' => 0
                ]);
            }
        }

        DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);


        if ($visible == 1) {
            DB::table('forums')->where('forumid', $forumid)->update([
                'lastpost' => $time,
                'lastpostid' => $postid,
                'lastposterid' => $userid,
                'lastthread' => $time,
                'lastthreadid' => $threadid
            ]);
        }

        $run = true;
        $fmrid = $forumid;
        while ($run) {
            $forum = DB::table('forums')->where('forumid', $fmrid)->first();

            if (count($forum)) {
                DB::table('forums')->where('forumid', $fmrid)->update([
                    'posts' => $forum->posts + 1,
                    'threads' => $forum->threads + 1,
                ]);

                if ($forum->parentid > 0) {
                    $fmrid = $forum->parentid;
                } else {
                    $run = false;
                }
            } else {
                $run = false;
            }
        }

        if (!ForumHelper::forumHaveOption($forumid, 2)) {
            DB::table('users')->where('userid', $userid)->update([
                'postcount' => DB::raw('postcount+1'),
                'threadcount' => DB::raw('threadcount+1'),
                'xpcount' => DB::raw('xpcount+2')
            ]);
        }

        UserHelper::checkBadge(1);

        DB::table('livewall')->insert([
            'userid' => $userid,
            'forum' => 1,
            'forumid' => $forum->forumid,
            'item_id' => $threadid,
            'item_type' => 1,
            'message' => 'posted',
            'dateline' => time()
        ]);

        DB::table('users')->where(['userid' => Auth::user()->userid])->update([
            'credits' => DB::raw('credits+2')
        ]);

        if(rand(1,100) <= 2){
            DB::table('users')->where(['userid' => Auth::user()->userid])->update([
                'owned_keys' => DB::raw('owned_keys+1')
            ]);

            DB::table('notifications')->insert([
                'postuserid' => 1,
                'reciveuserid' => Auth::user()->userid,
                'content' => 20,
                'contentid' => 0,
                'dateline' => $time,
                'read_at' => 0,
                'page' => 0
            ]);
        }

        if ($forumid != '90') {
            DB::table('clans')->where(['memberid_owner' => Auth::user()->userid])->orWhere(['memberid_2' => Auth::user()->userid])->orWhere(['memberid_3' => Auth::user()->userid])->update([
                'xpcount' => DB::raw('xpcount+3')
            ]);

            $clans = DB::table('clans')->where(['memberid_owner' => Auth::user()->userid])->orWhere(['memberid_2' => Auth::user()->userid])->orWhere(['memberid_3' => Auth::user()->userid])->get();

            foreach($clans as $temp){

                DB::table('clan_activity')->insert([
                    'userid' => Auth::user()->userid,
                    'clanid' => $temp->groupid,
                    'action' => 4,
                    'dateline' => time()
                ]);

            }
        }

        if ($forumid == '56') {
            ForumHelper::welcomeBot($threadid);
        }

        if ($visible == 0) {
            return response()->json(array('success' => true, 'response' => $response, 'path' => '/forum/category/' . $forumid . '/page/1'));
        }
        if ($visible == 1) {
            return response()->json(array('success' => true, 'response' => $response, 'path' => '/forum/thread/' . $threadid . '/page/1?postid=' . $postid));
        }
    }

    public function postEditPost(Request $request)
    {
        $postid = $request->input('postid');
        $content = $request->input('content');

        $userid = Auth::user()->userid;

        $post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();

        if (count($post)) {
            $thread = DB::table('threads')->where('threadid', $post->threadid)->first();
            if (count($thread)) {
                if ($post->userid == $userid or UserHelper::haveModPerm($userid, $thread->forumid, 1)) {
                    /* NOW LETS TAG PEOPLE ! */
                    $tagged_userids = array();

                    if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_]{1,}))/', $content, $match)) {
                        foreach ($match['UsernameOnly'] as $usr) {
                            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->where('userid', '!=', $userid)->first();

                            if (count($user)) {
                                $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                                if (!in_array($user->userid, $tagged_userids)) {
                                    $tagged_userids[] = array('userid' => $user->userid, 'content' => 1);
                                }
                            }
                        }
                    }

                    $time = time();

                    DB::table('posts')->where('postid', $postid)->update([
                        'lastedit' => $time,
                        'lastedituser' => $userid,
                        'content' => $content
                    ]);

                    $amountPosts = DB::table('posts')->where('postid', '<', $postid)->where('threadid', $post->threadid)->where('visible', 1)->count();

                    $page = ceil($amountPosts / 10);

                    if ($page < 1) {
                        $page = 1;
                    }

                    foreach ($tagged_userids as $tagged) {
                        $check = DB::table('notifications')->where('reciveuserid', $tagged['userid'])->where('content', $tagged['content'])->where('contentid', $postid)->count();

                        if ($check == 0) {
                            DB::table('notifications')->insert([
                                'postuserid' => $userid,
                                'reciveuserid' => $tagged['userid'],
                                'content' => $tagged['content'],
                                'contentid' => $postid,
                                'dateline' => $time,
                                'read_at' => 0,
                                'page' => $page
                            ]);
                        }
                    }

                    $content = ForumHelper::fixContent($content);

                    $content = ForumHelper::bbcodeParser($content);

                    $findQuotes = true;
                    while ($findQuotes) {
                        $content = ForumHelper::fixQuotePosts($content);

                        if (!preg_match_all('/\[quotepost=(.*?);(.*?)\](.*)\[\/quotepost\]/s', $content, $match)) {
                            $findQuotes = false;
                        }
                    }

                    $findTags = true;
                    while ($findTags) {
                        if (preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                            foreach ($match[1] as $mh) {
                                $content = str_replace("[mention]" . $mh . "[/mention]", '<a href="/profile/' . $mh . '" class="web-page">@' . $mh . '</a>', $content);
                            }
                        }

                        if (!preg_match_all('/\[mention\](.+?)\[\/mention\]/i', $content, $match)) {
                            $findTags = false;
                        }
                    }

                    $content = nl2br($content);

                    return response()->json(array('success' => true, 'response' => true, 'content' => $content));
                }
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function markAllRead()
    {
        $forums = array();

        $temps = DB::table('forums')->where('parentid', '>', 0)->get();
        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $temp->forumid, 1)) {
                $forums[] = $temp->forumid;
            }
        }
        $midnight = ForumHelper::findMidnight();
        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $mn = 3600 * $timezone->value;
                $midnight -= $mn;
            } else {
                $pl = 3600 * $timezone->value;
                $midnight += $pl;
            }
        }

        $temps = DB::table('threads')->whereIn('forumid', $forums)->where('lastpost', '>', $midnight)->get();
        foreach ($temps as $temp) {
            $check = DB::table('thread_read')->where('threadid', $temp->threadid)->where('userid', Auth::user()->userid)->first();
            if (count($check)) {
                DB::table('thread_read')->where('threadid', $temp->threadid)->where('userid', Auth::user()->userid)->update([
                    'read' => time()
                ]);
            } else {
                DB::table('thread_read')->insert([
                    'threadid' => $temp->threadid,
                    'userid' => Auth::user()->userid,
                    'read' => time()
                ]);
            }
        }

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postPost(Request $request)
    {
        $threadid = $request->input('threadid');
        $content = $request->input('content');
        $hidesig = $request->input('hideSig');

        $response = true;
        $message = "";
        $time = time();

        $userid = Auth::user()->userid;

        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (!count($thread)) {
            $message = "Thread does not exist!";
            $response = false;
        }

        if (Auth::user()->habbo_verified == 0) {
            $message = "You must verify your habbo to post!";
            $response = false;
        }

        if (Auth::user()->gdpr == 0) {
            $message = "You must accept our GDPR Policy on the homepage to post!";
            $response = false;
        }

        if (($thread->open == 0 or $thread->visible == 0) and !UserHelper::haveModPerm($userid, $thread->forumid, 8)) {
            $message = "Thread is not open!";
            $response = false;
        }

        if ($content == "") {
            $message = "Can't post empty content!";
            $response = false;
        }

        if (!UserHelper::haveForumPerm($userid, $thread->forumid, 4) and !UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
            $message = "You don't have permission to post in here!";
            $response = false;
        }

        if (!UserHelper::haveForumPerm($userid, $thread->forumid, 64)) {
            $check_double_post = DB::table('posts')->where('userid', $userid)->where('content', 'LIKE', $content)->where('dateline', '>', $time - 900)->count();

            if ($check_double_post != 0) {
                $message = "No double posting!";
                $response = false;
            }
        }

        $check_posting_speed = DB::table('posts')->where('userid', $userid)->where('dateline', '>', $time - 5)->count();

        if ($check_posting_speed != 0) {
            $message = "Whoa! Slow down, jeez!";
            $response = false;
        }

        if (!$response) {
            return response()->json(array('success' => true, 'reponse' => $response, 'message' => $message));
        }


        /* EVERYTHING IS CLEAR - WE CAN NOW ADD THE POST TO THE SYSTEM */

        /* NOW LETS TAG PEOPLE ! */
        $tagged_userids = array();

        if (preg_match_all('/(?<FullMention>@(?<UsernameOnly>[a-zA-Z0-9,\-_.]+))/', $content, $match)) {
            foreach ($match['UsernameOnly'] as $usr) {
                $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->where('userid', '!=', $userid)->first();

                if (count($user)) {
                    $content = str_replace("@" . $usr, "[mention]" . $user->username . '[/mention]', $content);
                    if (!in_array($user->userid, $tagged_userids)) {
                        $tagged_userids[] = array('userid' => $user->userid, 'content' => 1);
                    }
                } else {
                    $trimmed = rtrim($usr, ',.');
                    $trimmeduser = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($trimmed)])->where('userid', '!=', $userid)->first();
                    if (count($trimmeduser)) {
                        $content = str_replace("@" . $trimmed, "[mention]" . $trimmeduser->username . '[/mention]', $content);
                        if (!in_array($trimmeduser->userid, $tagged_userids)) {
                            $tagged_userids[] = array('userid' => $trimmeduser->userid, 'content' => 1);
                        }
                    }
                }
            }
        }

        if (preg_match_all('/\[quotepost=(.*?);(.*?)\](.*?)\[\/quotepost\]/si', $content, $match)) {
            for ($i = 0; $i < count($match[0]); $i++) {
                $postid = $match[1][$i];
                $post = DB::table('posts')->select('userid')->where('postid', $postid)->first();
                if (count($post) && $post->userid != Auth::user()->userid) {
                    $tagged_userids[] = array('userid' => $post->userid, 'content' => 2);
                }
            }
        }

        $postid = DB::table('posts')->insertGetId([
            'threadid' => $threadid,
            'username' => Auth::user()->username,
            'userid' => $userid,
            'dateline' => $time,
            'content' => $content,
            'ipaddress' => $request->ip(),
            'signature' => $hidesig
        ]);

        $have_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $threadid)->count();
        $time = time();

        if ($have_read == 0) {
            DB::table('thread_read')->insert([
                'userid' => $userid,
                'threadid' => $threadid,

                'read' => $time
            ]);
        } else {
            DB::table('thread_read')->where('userid', $userid)->where('threadid', $threadid)->update([
                'read' => $time
            ]);
        }

        /* SEND NOTIFICATION TO ALL TAGGED USERS */
        $inserts = [];
        foreach ($tagged_userids as $tagged) {
            $check = DB::table('notifications')->where('reciveuserid', $tagged['userid'])->where('content', $tagged['content'])->where('contentid', $postid)->count();
            $user = DB::table('users')->select('extras')->where('userid', $tagged['userid'])->first();
            $amtPosts = DB::table('posts')->where('postid', '<', $postid)->where('threadid', $threadid)->count();
            $page = ceil($amtPosts / 10);
            if ($page < 1) {
                $page = 1;
            }

            if ($check == 0 && count($user) && $user->extras & 4) {
                $inserts[] = [
                    'postuserid' => $userid,
                    'reciveuserid' => $tagged['userid'],
                    'content' => $tagged['content'],
                    'contentid' => $postid,
                    'dateline' => $time,
                    'read_at' => 0,
                    'page' => $page
                ];
            }
        }
        DB::table('notifications')->insert($inserts);

        if (!ForumHelper::forumHaveOption($thread->forumid, 2)) {
            DB::table('users')->where('userid', $userid)->update([
                'postcount' => DB::raw('postcount+1'),
                'xpcount' => DB::raw('xpcount+2')
            ]);

            DB::table('clans')->where(['memberid_owner' => Auth::user()->userid])->orWhere(['memberid_2' => Auth::user()->userid])->orWhere(['memberid_3' => Auth::user()->userid])->update([
                'xpcount' => DB::raw('xpcount+3')
            ]);

            $clans = DB::table('clans')->where(['memberid_owner' => Auth::user()->userid])->orWhere(['memberid_2' => Auth::user()->userid])->orWhere(['memberid_3' => Auth::user()->userid])->get();

            foreach($clans as $temp){

                DB::table('clan_activity')->insert([
                    'userid' => Auth::user()->userid,
                    'clanid' => $temp->groupid,
                    'action' => 4,
                    'dateline' => time()
                ]);

            }
        }

        DB::table('threads')->where('threadid', $threadid)->update([
            'lastpost' => $time,
            'lastpostid' => $postid,
            'lastpostuserid' => $userid,
            'replys' => DB::raw('replys+1')
        ]);

        DB::table('forums')->where('forumid', $thread->forumid)->update([
            'lastpost' => $time,
            'lastpostid' => $postid,
            'lastposterid' => $userid
        ]);

        $run = true;
        $fmrid = $thread->forumid;
        while ($run) {
            $forum = DB::table('forums')->select('parentid')->where('forumid', $fmrid)->first();

            if (count($forum)) {
                DB::table('forums')->where('forumid', $fmrid)->update([
                    'posts' => DB::raw('posts+1')
                ]);

                if ($forum->parentid > 0) {
                    $fmrid = $forum->parentid;
                } else {
                    $run = false;
                }
            } else {
                $run = false;
            }
        }

        $temps = DB::table('subscription_threads')->select('userid')->where('threadid', $threadid)->where('userid', '!=', Auth::user()->userid)->whereNotIn('userid', $tagged_userids)->get();


        $amount_posts = DB::table('posts')->where('threadid', $threadid)->where('postid', '<', $postid)->where('visible', 1)->count();
        $page = ceil(($amount_posts + 1) / 10);
        if ($page < 1) {
            $page = 1;
        }

        foreach ($temps as $temp) {
            DB::table('notifications')->insert([
                'postuserid' => Auth::user()->userid,
                'reciveuserid' => $temp->userid,
                'content' => 4,
                'contentid' => $postid,
                'dateline' => time(),
                'read_at' => 0,
                'page' => $page
            ]);
        }
        UserHelper::checkBadge(1);

        DB::table('livewall')->insert([
            'userid' => Auth::user()->userid,
            'forum' => 1,
            'forumid' => $fmrid,
            'item_id' => $postid,
            'item_type' => 2,
            'message' => 'posted',
            'dateline' => time()
        ]);

        DB::table('users')->where(['userid' => Auth::user()->userid])->update([
            'credits' => DB::raw('credits+2')
        ]);

        if(rand(1,100) <= 2){
            DB::table('users')->where(['userid' => Auth::user()->userid])->update([
                'owned_keys' => DB::raw('owned_keys+1')
            ]);

            DB::table('notifications')->insert([
                'postuserid' => 1,
                'reciveuserid' => Auth::user()->userid,
                'content' => 20,
                'contentid' => 0,
                'dateline' => $time,
                'read_at' => 0,
                'page' => 0
            ]);
        }

        return response()->json(array('success' => true, 'response' => $response, 'path' => '/forum/thread/' . $threadid . '/page/' . $page . '?postid=' . $postid));
    }

    public function getNewThread($forumid)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (Auth::user()->habbo_verified == 0) {
            return redirect()->route('getErrorPerms');
        }

        if (!count($forum) or !UserHelper::haveForumPerm(Auth::user()->userid, $forum->forumid, 2)) {
            return redirect()->route('getErrorPerm');
        }

        $forumids = array($forum->forumid, 0);
        $run = true;
        $ts = $forum;
        while ($run) {
            if ($ts->parentid > 0) {
                $ts = DB::table('forums')->where('forumid', $ts->parentid)->first();

                if (count($ts)) {
                    $forumids[] = $ts->forumid;
                } else {
                    break;
                }
            } else {
                $run = false;
            }
        }

        $prefixes = DB::table('prefixes')->whereIn('forumid', $forumids)->get();

        $returnHTML = view('forum.newThread')
            ->with('forum', $forum)
            ->with('prefixes', $prefixes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getThread($threadid, $pagenr, $userSearch = null)
    {
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        $userid = Auth::user()->userid;

        if (!count($thread) || $thread->visible == 0 and !UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
            return redirect()->route('getErrorPerm');
        }

        if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32)) {
            if ($thread->visible == 1 and $thread->postuserid != $userid) {
                return redirect()->route('getErrorPerm');
            }
        }

        if ($thread->visible == 1 and UserHelper::isThreadBanned($thread->threadid)) {
            return redirect()->route('getthreadBanned');
        }

        $forum = DB::table('forums')->where('forumid', $thread->forumid)->first();

        if (!count($forum) || !UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
            return redirect()->route('getErrorPerm');
        }

        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32)) {
            if ($thread->visible == 1 and $thread->postuserid != $userid) {
                return redirect()->route('getErrorPerm');
            }
        }

        ForumHelper::updateForumRead($thread->forumid);

        $take = 10;
        $skip = 0;

        if ($userSearch == null) {
            if (UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
                $pagesx = DB::table('posts')->where('threadid', $threadid)->count();
            } else {
                $pagesx = DB::table('posts')->where('threadid', $threadid)->where('visible', 1)->count();
            }
        } else {
            if (UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
                $pagesx = DB::table('posts')->where('threadid', $threadid)->whereRaw('lower(username) = ?', [$userSearch])->count();
            } else {
                $pagesx = DB::table('posts')->where('threadid', $threadid)->whereRaw('lower(username) = ?', [$userSearch])->where('visible', 1)->count();
            }
        }

        $pages = ceil($pagesx / $take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
            'previous_exists' => $pagenr - 1 < 1 ? false : true,
            'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
            'next_exists' => $pagenr + 1 > $pages ? false : true,
            'gap_forward' => $pagenr + 5 < $pages ? true : false,
            'gap_backward' => $pagenr - 5 > 1 ? true : false
        );

        if ($pagenr >= 2) {
            $skip = $take * $pagenr - $take;
        }

        $userbars_css = array();

        if ($userSearch == null) {
            if (UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
                $posts_obj = DB::table('posts')->where('threadid', $threadid)->orderBy('dateline', 'ASC')->skip($skip)->take($take)->get();
            } else {
                $posts_obj = DB::table('posts')->where('threadid', $threadid)->where('visible', 1)->orderBy('dateline', 'ASC')->skip($skip)->take($take)->get();
            }
        } else {
            if (UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
                $posts_obj = DB::table('posts')->where('threadid', $threadid)->whereRaw('lower(username) = ?', [$userSearch])->orderBy('dateline', 'ASC')->skip($skip)->take($take)->get();
            } else {
                $posts_obj = DB::table('posts')->where('threadid', $threadid)->whereRaw('lower(username) = ?', [$userSearch])->where('visible', 1)->orderBy('dateline', 'ASC')->skip($skip)->take($take)->get();
            }
        }

        $posts = array();

        $postnr = $pagenr;
        $x = 1;

        $last_read = DB::table('thread_read')->where('threadid', $thread->threadid)->where('userid', Auth::user()->userid)->value('read');
        if (!$last_read) {
            $last_read = 0;
        }

        $bbcodes = DB::table('bbcodes')->get();
        $skipSignatures = UserHelper::haveExtraOn(Auth::user()->userid, 32);
        foreach ($posts_obj as $post) {
            if ($pagenr != 1) {
                if ($x == 10) {
                    $postnr = $pagenr . '0';
                } else {
                    $postnr = ($pagenr . $x) - 10;
                }
            } else {
                $postnr = $x;
            }

            $content = ForumHelper::fixContent($post->content);
            $content = ForumHelper::replaceEmojis($content);
            $content = ForumHelper::bbcodeParser($content, $bbcodes);

            $signature = "";

            $user = UserHelper::getUser($post->userid);
            $likecount = 0;
            $postcount = 0;
            $joined = 0;
            $userbars_html = array();
            $postAvatarStyle = 1;
            $post_badges = "";

            if (count($user)) {
                $likecount = $user->likecount;
                $postcount = $user->postcount;
                $xpcount = $user->xpcount;
                $habbo = $user->habbo;
                $instagram = $user->instagram;
                $kik = $user->kik;
                $lastfm = $user->lastfm;
                $discord = $user->discord;
                $jn = false;
                $ps = false;
                $lk = false;
                $sa = false;
                $lb = false;
                $hh = false;
                $stats = explode(',', $user->postbit);
                foreach ($stats as $stat) {
                    if ($stat == 1) {
                        $jn = true;
                    } elseif ($stat == 2) {
                        $ps = true;
                    } elseif ($stat == 3) {
                        $lk = true;
                    } elseif ($stat == 4) {
                        $sa = true;
                    } elseif ($stat == 5) {
                        $lb = true;
                    } elseif ($stat == 6) {
                        $hh = true;
                    }
                }
                $snapchat = $user->snapchat;
                $tumblr = $user->tumblr;
                $twitter = $user->twitter;
                $soundcloud = $user->soundcloud;
                $postAvatarStyle = $user->post_avatar;
                $joined = date('jS F Y', $user->joindate);
                $username = $user->username;

                $post_badges = explode(',', $user->postbit_badges);

                $groups = explode(",", $user->usergroups);
                $hide_groups = explode(",", $user->hidebars);
                foreach ($groups as $group) {
                    if (!in_array($group, $hide_groups)) {
                        $bar = UserHelper::getUserbar($user->userid, $group);

                        if (count($bar)) {
                            $userbars_html[] = $bar['html'];
                            $userbars_css[] = $bar['css'];
                        }
                    }
                }
                if ($post->signature == 1 && !$skipSignatures) {
                    $signature = ForumHelper::fixContent($user->signature);
                    $signature = ForumHelper::replaceEmojis($signature);
                    $signature = ForumHelper::bbcodeParser($signature, $bbcodes);
                    $signature = nl2br($signature);
                }
            }

            $can_edit_post = false;

            if ($post->userid == $userid or UserHelper::haveModPerm($userid, $thread->forumid, 1)) {
                $can_edit_post = true;
            }

            $content = nl2br($content);

            $content = ForumHelper::replaceEmojis($content);

            $likers = ForumHelper::getLikers($post->postid);

            $likes_post = false;

            $check = DB::table('notifications')->where('postuserid', $userid)->where('content', 3)->where('contentid', $post->postid)->count();

            if ($check == 1) {
                $likes_post = true;
            }

            $opacity = 1;

            if ($post->visible == 0) {
                $opacity = 0.5;
            }

            $levels = DB::Table('xp_levels')->orderBy('posts', 'DESC')->get();
            $nextlevel_id = 0;
            $nextlevel_posts = 0;
            $level_id = 0;
            $level_desc = 0;
            $level_name = 0;
            $level_pro = 0;
            $found = false;

            foreach ($levels as $level) {
                if ($found == false) {
                    if ($xpcount >= $level->posts) {
                        $level_id = $level->levelid;
                        $level_name = $level->name;
                        $found = true;
                    } else {
                        $nextlevel_posts = $level->posts;
                        $nextlevel_id = $level->levelid;
                    }
                }
            }

            $level_until = $nextlevel_posts - $xpcount;

            if ($nextlevel_id == 0) {
                $level_pro = 100;
            } else {
                $pro = ($xpcount / $nextlevel_posts) * 100;
                $level_pro = round($pro);
            }


            $array = array(
                'postid' => $post->postid,
                'postnr' => $postnr,
                'content' => $content,
                'username' => UserHelper::getUsername($post->userid),
                'avatar' => UserHelper::getAvatar($post->userid),
                'avatarSize' => UserHelper::getAvatarSize($user->usergroups),
                'userid' => $post->userid,
                'post_badges' => $post_badges,
                'clean_username' => $username,
                'joined' => $joined,
                'posts' => $postcount,
                'likes' => $likecount,
                'ipaddress' => '0.0.0.0',
                'postAvatarStyle' => $postAvatarStyle,
                'habbo' => $habbo,
                'discord' => $discord,
                'instagram' => $instagram,
                'kik' => $kik,
                'lastfm' => $lastfm,
                'snapchat' => $snapchat,
                'soundcloud' => $soundcloud,
                'tumblr' => $tumblr,
                'twitter' => $twitter,
                'threadbanned' => UserHelper::isuserThreadBanned($post->threadid, $post->userid),
                'jn' => $jn,
                'ps' => $ps,
                'lk' => $lk,
                'sa' => $sa,
                'lb' => $lb,
                'hh' => $hh,
                'level_pro' => $level_pro,
                'level_until' => $level_until,
                'level_name' => $level_name,
                'userbars_html' => $userbars_html,
                'can_edit_post' => $can_edit_post,
                'likers_strike' => $likers['likers_strike'],
                'have_likers' => $likers['have_likers'],
                'likes_post' => $likes_post,
                'opacity' => $opacity,
                'signature' => $signature,
                'lastedit_clean' => $post->lastedit,
                'lastedit' => ForumHelper::getTimeInDateLong($post->lastedit),
                'lasteditby' => UserHelper::getUsername($post->lastedituser),
                'lasteditby_clean' => UserHelper::getUsername($post->lastedituser, true),
                'time' => ForumHelper::getTimeInDateLong($post->dateline)
            );

            $posts[] = $array;
            $x++;
        }

        $thread_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $thread->threadid)->first();

        if (count($thread_read)) {
            $last_post_temp = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('dateline', '>', $thread_read->read)->first();

            if (!count($last_post_temp)) {
                $last_post = DB::table('posts')->where('threadid', $thread->threadid)->orderBy('dateline', 'desc')->where('visible', 1)->value('postid');
            } else {
                $last_post = $last_post_temp->postid;
            }
        } else {
            $last_post = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->value('postid');
        }

        /* UPDATE WHEN USER READ THE THREAD */
        $have_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $threadid)->count();
        $time = time();

        if ($have_read == 0) {
            DB::table('thread_read')->insert([
                'userid' => $userid,
                'threadid' => $threadid,
                'read' => $time,
            ]);
        } else {
            DB::table('thread_read')->where('userid', $userid)->where('threadid', $threadid)->update([
                'read' => $time,
            ]);
        }

        $can_post = ($thread->postuserid == Auth::user()->userid && UserHelper::haveForumPerm($userid, $thread->forumid, 128)) ||
            ($thread->postuserid != Auth::user()->userid && UserHelper::haveForumPerm($userid, $thread->forumid, 4));

        /* POLL STUFF */
        $have_voted = DB::table('thread_poll_votes')->where('threadid', $thread->threadid)->where('userid', Auth::user()->userid)->count() > 0;
        $votes_visible = false;
        $votes_creator = $thread->postuserid == Auth::user()->userid;
        $answers = [];
        $total_amount = DB::table('thread_poll_votes')->where('threadid', $thread->threadid)->count();

        if ($thread->got_poll == 1) {
            $ans = DB::table('thread_poll_answers')->where('threadid', $thread->threadid)->get();

            foreach ($ans as $an) {
                $amount = DB::table('thread_poll_votes')->where('pollanswerid', $an->pollanswerid)->count();
                $procent = ($amount > 0 & $total_amount > 0) ? ceil(($amount / $total_amount) * 100) : 0;

                $answers[] = [
                    'pollanswerid' => $an->pollanswerid,
                    'text' => $an->answer,
                    'amount' => $amount,
                    'procent' => $procent
                ];
            }
        }

        /* REPORT STUFF */
        $can_report_post = DB::table('moderation_forums')->count() > 0;

        /* MOD STUFF */
        $tms = DB::table('infraction_reasons')->orderBy('text', 'ASC')->get();

        $infraction_reasons = [];

        foreach ($tms as $tm) {
            $infraction_reasons[] = [
                'infractionrsnid' => $tm->infractionrsnid,
                'reason' => $tm->text,
                'points' => $tm->points
            ];
        }

        $have_mod = false;
        $can_soft_delete = false;
        $can_hard_delete = false;
        $can_edit_post = false;
        $can_open_close_thread = false;
        $can_change_owner = false;
        $can_move_threads = false;
        $can_merge_threads = false;
        $can_move_posts = false;
        $can_warninf_users = false;
        $can_approve_unapprove_threads = false;
        $can_view_unapproved_threads = false;

        if (UserHelper::haveModPerm($userid, $thread->forumid, 4)) {
            $can_hard_delete = true;
            $can_soft_delete = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 2)) {
            $can_soft_delete = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 1)) {
            $can_edit_post = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 8)) {
            $can_open_close_thread = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 64)) {
            $can_change_owner = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 16)) {
            $can_move_threads = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 32)) {
            $can_merge_threads = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 128)) {
            $can_move_posts = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 256)) {
            $can_warninf_users = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $thread->forumid, 512)) {
            $can_approve_unapprove_threads = true;
            $have_mod = true;
        }

        if ($thread->dateline > time() - 172800 and $thread->visible == "0" and UserHelper::haveModPerm($userid, $thread->forumid, 1024)) {
            $can_view_unapproved_threads = true;
            $can_edit_post = true;
        }

        $subscribed = DB::table('subscription_threads')->where('threadid', $thread->threadid)->where('userid', Auth::user()->userid)->count() > 0;
        $temps = DB::table('thread_read')->where('threadid', $thread->threadid)->orderBy('read', 'DESC')->take(20)->get();
        $have_read = [];

        foreach ($temps as $temp) {
            $have_read[] = [
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'time' => ForumHelper::timeAgo($temp->read)
            ];
        }

        $prefix = DB::table('prefixes')->where('prefixid', $thread->prefixid)->first();
        $threadprefix = '';
        $threadprefixclean = '';
        if (count($prefix)) {
            $threadprefix = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ';
            $threadprefixclean = '' . $prefix->text . ' » ';
        }

        DB::table('threads')->where('threadid', $thread->threadid)->update([
            'views' => DB::raw('views+1'),
        ]);

        if ($userSearch == null) {
            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/forum/thread/' . $thread->threadid . '/page/')->render();
        } else {
            $extra = '/' . $userSearch;
            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/forum/thread/' . $thread->threadid . '/page/')->with('extra', $extra)->render();
        }
        $returnHTML = view('forum.thread')
            ->with('bbmode', UserHelper::haveExtraOn(Auth::user()->userid, 8))
            ->with('thread', $thread)
            ->with('posts', $posts)
            ->with('pagi', $pagi)
            ->with('can_post', $can_post)
            ->with('userbars_css', $userbars_css)
            ->with('can_report_post', $can_report_post)
            ->with('thread_have_poll', $thread->got_poll == 1)
            ->with('have_voted', $have_voted)
            ->with('votes_visible', $votes_visible)
            ->with('votes_creator', $votes_creator)
            ->with('last_post', $last_post)
            ->with('answers', $answers)
            ->with('total_amount', $total_amount)
            ->with('threadprefix', $threadprefix)
            ->with('threadprefixclean', $threadprefixclean)
            ->with('verified', Auth::user()->habbo_verified)
            ->with('gdpr', Auth::user()->gdpr)
            ->with('have_read', $have_read)
            ->with('current_page', $pagenr)
            /* MOD WITH'S */
            ->with('have_mod', $have_mod)
            ->with('infraction_reasons', $infraction_reasons)
            ->with('can_soft_delete', $can_soft_delete)
            ->with('can_hard_delete', $can_hard_delete)
            ->with('can_edit_post', $can_edit_post)
            ->with('can_open_close_thread', $can_open_close_thread)
            ->with('can_change_owner', $can_change_owner)
            ->with('can_move_threads', $can_move_threads)
            ->with('can_merge_threads', $can_merge_threads)
            ->with('subscribed', $subscribed)
            ->with('can_move_posts', $can_move_posts)
            ->with('can_warninf_users', $can_warninf_users)
            ->with('can_approve_unapprove_threads', $can_approve_unapprove_threads)
            ->with('can_view_unapproved_threads', $can_view_unapproved_threads)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }


    public function getCategory($forumid, $pagenr)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        //Check if forum exists and if user have perms to access it
        if (!count($forum) or !UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
            return redirect()->route('getErrorPerm');
        }

        // Check if user has forum ignored
        $userid = Auth::user()->userid;
        $ignored_forums = DB::table('users')->where('userid', $userid)->value('ignored_forums');
        $ignored = in_array($forumid, explode(',', $ignored_forums), $forumid);

        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = Auth::user()->userid;

        ForumHelper::updateForumRead($forumid);

        $take = 20;
        $skip = 0;

        if (UserHelper::haveModPerm($userid, $forum->forumid, 1024)) {
            $pagesx = DB::table('threads')->where('forumid', $forumid)->count();
        } else {
            $pagesx = DB::table('threads')->where('forumid', $forumid)->where('visible', 1)->count();
        }

        if (!UserHelper::haveForumPerm($userid, $forum->forumid, 32)) {
            $pagesx = DB::table('threads')->where('forumid', $forumid)->where('visible', 1)->where('postuserid', $userid)->count();
        }

        $pages = ceil($pagesx / $take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr - 1 <= 0 ? 1 : $pagenr - 1,
            'previous_exists' => $pagenr - 1 < 1 ? false : true,
            'next' => $pagenr + 1 > $pages ? $pages : $pagenr + 1,
            'next_exists' => $pagenr + 1 > $pages ? false : true,
            'gap_forward' => $pagenr + 5 < $pages ? true : false,
            'gap_backward' => $pagenr - 5 > 1 ? true : false
        );


        if ($pagenr >= 2) {
            $skip = $take * $pagenr - $take;
        }

        $stickys = array();
        if ($pagenr == 1) {
            $stickys_obj = DB::table('threads')->where('forumid', $forumid)->where('visible', 1)->where('sticky', 1)->orderBy('lastpost', 'DESC')->skip($skip)->take($take)->get();
            $take -= count($stickys_obj);

            foreach ($stickys_obj as $sticky) {
                $run = true;

                if ($sticky->postuserid != $userid and !UserHelper::haveForumPerm($userid, $sticky->forumid, 32)) {
                    $run = false;
                }

                if ($run) {
                    $post = DB::table('posts')->where('postid', $sticky->lastpostid)->first();

                    $last_poster_username = "";
                    $last_poster_clean_username = "";
                    $last_poster_userid = 0;
                    $last_poster_time = 0;

                    if (count($post)) {
                        $last_poster_username = UserHelper::getUsername($post->userid);
                        $last_poster_clean_username = UserHelper::getUsername($post->userid, true);
                        $last_poster_userid = $post->userid;
                        $last_poster_time = ForumHelper::timeAgo($post->dateline);
                    }

                    $last_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $sticky->threadid)->first();
                    $last_post_page = 1;

                    $have_already_seen = false;

                    if (count($last_read)) {
                        $last_post = DB::table('posts')->where('threadid', $sticky->threadid)->where('visible', 1)->where('dateline', '>', $last_read->read)->orderBy('postid', 'DESC')->first();

                        if ($last_read->read > $post->dateline) {
                            $have_already_seen = true;
                        }

                        if (count($last_post)) {
                            /* HOW MANY MORE POSTS ARE THERE BEFORE THIS ONE */
                            $amount_posts = DB::table('posts')->where('threadid', $sticky->threadid)->where('visible', 1)->where('postid', '<', $last_post->postid)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        } else {
                            /* GET THE LAST PAGE */
                            $amount_posts = DB::table('posts')->where('threadid', $sticky->threadid)->where('visible', 1)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        }
                    }

                    $prefix = DB::table('prefixes')->where('prefixid', $sticky->prefixid)->first();
                    $title = ForumHelper::fixContent($sticky->title);
                    if (count($prefix)) {
                        $title = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ' . $title;
                    }

                    $array = array(
                        'threadid' => $sticky->threadid,
                        'page' => $last_post_page,
                        'title' => $title,
                        'sticky' => $sticky->sticky,
                        'views' => number_format($sticky->views) . '<br /><i>Views</i>',
                        'replys' => number_format($sticky->replys) . '<br /><i>Replies</i>',
                        'open' => $sticky->open,
                        'time' => ForumHelper::getTimeInDate($sticky->dateline),
                        'userid' => $sticky->postuserid,
                        'username' => UserHelper::getUsername($sticky->postuserid),
                        'clean_username' => UserHelper::getUsername($sticky->postuserid, true),
                        'last_poster_username' => $last_poster_username,
                        'last_poster_clean_username' => $last_poster_clean_username,
                        'last_poster_userid' => $last_poster_userid,
                        'last_poster_time' => $last_poster_time,
                        'last_poster_avatar' => UserHelper::getAvatar($last_poster_userid),
                        'have_already_seen' => $have_already_seen
                    );

                    $stickys[] = $array;
                }
            }
        }

        $threads = array();
        if ($take > 0) {
            if (UserHelper::haveModPerm($userid, $forum->forumid, 1024)) {
                $threads_obj = DB::table('threads')->where('forumid', $forumid)->where('sticky', 0)->orderBy('lastpost', 'DESC')->skip($skip)->take($take)->get();
            } else {
                $threads_obj = DB::table('threads')->where('forumid', $forumid)->where('visible', 1)->where('sticky', 0)->orderBy('lastpost', 'DESC')->skip($skip)->take($take)->get();
            }

            foreach ($threads_obj as $thread) {
                $run = true;

                if ($thread->postuserid != $userid and !UserHelper::haveForumPerm($userid, $thread->forumid, 32)) {
                    $run = false;
                }

                if ($run) {
                    $post = DB::table('posts')->where('postid', $thread->lastpostid)->first();

                    $last_poster_username = "";
                    $last_poster_clean_username = "";
                    $last_poster_userid = 0;
                    $last_poster_time = 0;

                    if (count($post)) {
                        $last_poster_username = UserHelper::getUsername($post->userid);
                        $last_poster_clean_username = UserHelper::getUsername($post->userid, true);
                        $last_poster_userid = $post->userid;
                        $last_poster_time = ForumHelper::timeAgo($post->dateline);
                    }

                    $last_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $thread->threadid)->first();
                    $last_post_page = 1;
                    $have_already_seen = false;

                    if (count($last_read)) {
                        $last_post = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('dateline', '>', $last_read->read)->orderBy('postid', 'DESC')->first();

                        if ($last_read->read > $post->dateline) {
                            $have_already_seen = true;
                        }

                        if (count($last_post)) {
                            /* HOW MANY MORE POSTS ARE THERE BEFORE THIS ONE */
                            $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('postid', '<', $last_post->postid)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        } else {
                            /* GET THE LAST PAGE */
                            $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->count();
                            $pages = ceil($amount_posts / 10);

                            $last_post_page = $pages;
                        }
                    }

                    $prefix = DB::table('prefixes')->where('prefixid', $thread->prefixid)->first();
                    $title = ForumHelper::fixContent($thread->title);
                    if (count($prefix)) {
                        $title = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ' . $title;
                    }

                    $array = array(
                        'threadid' => $thread->threadid,
                        'page' => $last_post_page,
                        'title' => $title,
                        'views' => number_format($thread->views) . '<br /><i>Views</i>',
                        'replys' => number_format($thread->replys) . '<br /><i>Replies</i>',
                        'open' => $thread->open,
                        'time' => ForumHelper::getTimeInDate($thread->dateline),
                        'userid' => $thread->postuserid,
                        'username' => UserHelper::getUsername($thread->postuserid),
                        'clean_username' => UserHelper::getUsername($thread->postuserid, true),
                        'last_poster_username' => $last_poster_username,
                        'last_poster_clean_username' => $last_poster_clean_username,
                        'last_poster_userid' => $last_poster_userid,
                        'last_poster_time' => $last_poster_time,
                        'last_poster_avatar' => UserHelper::getAvatar($last_poster_userid),
                        'visible' => $thread->visible,
                        'soft_deleted' => $thread->soft_deleted,
                        'have_already_seen' => $have_already_seen
                    );

                    $threads[] = $array;
                }
            }
        }

        $sub_forums = ForumHelper::getSubSubForums($userid, $forum->forumid);
        $can_post_thread = false;

        if (UserHelper::haveForumPerm($userid, $forum->forumid, 2) and $forum->parentid > 0) {
            $can_post_thread = true;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/forum/category/' . $forum->forumid . '/page/')->render();
        // needs $can_open_close_thread, $can_soft_delete, $can_hard_delete, $can_change_owner, $can_approve_unapprove-->

        $have_mod = false;
        $can_soft_delete = false;
        $can_hard_delete = false;
        $can_edit_post = false;
        $can_open_close_thread = false;
        $can_change_owner = false;
        $can_move_threads = false;
        $can_merge_threads = false;
        $can_move_posts = false;
        $can_warninf_users = false;
        $can_approve_unapprove_threads = false;
        $can_see_unapproved_threads = false;

        $userid = Auth::user()->userid;

        if (UserHelper::haveModPerm($userid, $forumid, 4)) {
            $can_hard_delete = true;
            $can_soft_delete = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 16)) {
            $can_move_threads = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 2)) {
            $can_soft_delete = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 8)) {
            $can_open_close_thread = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 64)) {
            $can_change_owner = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 512)) {
            $can_approve_unapprove_threads = true;
            $have_mod = true;
        }

        if (UserHelper::haveModPerm($userid, $forumid, 1024)) {
            $can_see_unapproved_threads = true;
        }

        $returnHTML = view('forum.category')
            ->with('ignored', $ignored)
            ->with('have_mod', $have_mod)
            ->with('can_merge_threads', $can_merge_threads)
            ->with('can_warninf_users', $can_warninf_users)
            ->with('can_soft_delete', $can_soft_delete)
            ->with('can_hard_delete', $can_hard_delete)
            ->with('can_edit_post', $can_edit_post)
            ->with('can_move_threads', $can_move_threads)
            ->with('can_open_close_thread', $can_open_close_thread)
            ->with('can_change_owner', $can_change_owner)
            ->with('can_approve_unapprove_threads', $can_approve_unapprove_threads)
            ->with('can_see_unapproved_threads', $can_see_unapproved_threads)
            ->with('forum', $forum)
            ->with('threads', $threads)
            ->with('verified', Auth::user()->habbo_verified)
            ->with('gdpr', Auth::user()->gdpr)
            ->with('stickys', $stickys)
            ->with('pagi', $pagi)
            ->with('can_post_thread', $can_post_thread)
            ->with('sub_forums', $sub_forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postShoutboxMessage(Request $request)
    {
        $bbcodes = DB::table('bbcodes')->get();
        $time = time();
        $timeout = $time - 5;
        $lastMessage = DB::table('shoutbox')->where('userid', Auth::user()->userid)->where('dateline', '>', $timeout)->count();
        $message = '';
        $response = false;
        if ($lastMessage == 0) {
            $message = $request->input('message');

            DB::table('shoutbox')->insert([
                'userid' => Auth::user()->userid,
                'shoutboxid' => 1,
                'message' => $message,
                'dateline' => $time
            ]);

            $message = ForumHelper::fixContent($message);
            $message = ForumHelper::bbcodeParser($message, $bbcodes);
            $response = true;
        }

        return response()->json(array('success' => true, 'lastTime' => $time, 'response' => $response, 'message' => $message, 'clean_username' => UserHelper::getUsername(Auth::user()->userid, true), 'username' => UserHelper::getUsername(Auth::user()->userid), 'dateline' => date('d-m, H:i', ForumHelper::returnTimeAfterTimezone(time()))));
    }

    public function getShoutboxMessages($lastTime)
    {
        $bbcodes = DB::table('bbcodes')->get();
        $shoutboxid = 1;
        $temps = DB::table('shoutbox')->where('shoutboxid', $shoutboxid)->where('dateline', '>', $lastTime)->orderBy('dateline', 'DESC')->get();
        $messages = [];
        $new_lastTime = $lastTime;
        $gotten_it = false;
        foreach ($temps as $temp) {
            if (!$gotten_it) {
                $new_lastTime = $temp->dateline;
                $gotten_it = true;
            }
            $message = ForumHelper::fixContent($temp->message);
            $message = ForumHelper::bbcodeParser($message, $bbcodes);
            $messages[] = [
                'userid' => $temp->userid,
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'message' => $message,
                'dateline' => date('d-m, H:i', ForumHelper::returnTimeAfterTimezone($temp->dateline))
            ];
        }

        return response()->json(array('success' => true, 'new_lastTime' => $new_lastTime, 'messages' => $messages));
    }

    public function getForum()
    {
        $gdpr = Auth::check() && Auth::user()->gdpr == 1;
        $bbcodes = DB::table('bbcodes')->get();
        $main_forums = DB::table('forums')->where('parentid', -1)->orderBy('displayorder', 'ASC')->get();

        $forums = array();
        $userid = Auth::check() ? Auth::user()->userid : 0;

        foreach ($main_forums as $main_forum) {
            if (UserHelper::haveForumPerm($userid, $main_forum->forumid, 1)) {
                $mf = array(
                    'title' => $main_forum->title,
                    'thumbnail' => asset('_assets/img/forumthumbnails/' . $main_forum->forumid . '.gif'),
                    'forumid' => $main_forum->forumid,
                    'collapsed' => ForumHelper::isForumCollapsed($main_forum->forumid, $userid),
                    'sub_forums' => 0
                );

                $mf['sub_forums'] = ForumHelper::getSubForums($userid, $main_forum->forumid);

                $forums[] = $mf;
            }
        }

        $can_use_shoutbox = UserHelper::haveStaffPerm($userid, 4096);
        $messages = [];
        $shoutboxid = 1;
        $lastTime = 0;
        $gotten_it = false;

        if ($can_use_shoutbox) {
            $temps = DB::table('shoutbox')->where('shoutboxid', $shoutboxid)->take(15)->orderBy('dateline', 'DESC')->get();

            foreach ($temps as $temp) {
                if (!$gotten_it) {
                    $lastTime = $temp->dateline;
                    $gotten_it = true;
                }

                $message = ForumHelper::fixContent($temp->message);
                $message = ForumHelper::bbcodeParser($message, $bbcodes);
                $message = ForumHelper::replaceEmojis($message);
                $messages[] = [
                    'userid' => $temp->userid,
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => UserHelper::getUsername($temp->userid, true),
                    'message' => $message,
                    'dateline' => date('d-m, H:i', ForumHelper::returnTimeAfterTimezone($temp->dateline))
                ];
            }
        }

        // HIDE AVS FROM FORUMHOME
        $hideavs = UserHelper::haveExtraOn($userid, 16);

        // TOP POSTER
        $top_poster = DB::table('users')->orderBy('postcount', 'DESC')->first();
        $top_poster_clean = UserHelper::getUsername($top_poster->userid, true);
        $top_poster = UserHelper::getUsername($top_poster->userid);

        // TOP THREAD STARTER
        $top_thread_starter = DB::table('users')->orderBy('threadcount', 'DESC')->first();
        $top_thread_starter_clean = UserHelper::getUsername($top_thread_starter->userid, true);
        $top_thread_starter = UserHelper::getUsername($top_thread_starter->userid);

        // TOP QUESTS SHARER
        $top_quests_sharer = DB::table('guide_shares')->groupBy('referrerid')->orderByRaw('COUNT(*) DESC')->first();
        $top_quests_sharer_clean = UserHelper::getUsername($top_quests_sharer->referrerid, true);
        $top_quests_sharer = UserHelper::getUsername($top_quests_sharer->referrerid);

        // TOTAL REFERRALS
        $referrals = DB::table('users')->where('referdby', '!=', '0')->count();

        // TOTAL INFRACTION COUNT
        $infractionscount = DB::table('infraction')->where('type', '=', 1)->count();
        $infractionscount = $infractionscount;

        // TOTAL WARNINGS COUNT
        $warningscount = DB::table('infraction')->where('type', '==', 0)->count();
        $warningscount = $warningscount;

        $returnHTML = view('forum.index')
            ->with('gdpr', $gdpr)
            ->with('forums', $forums)
            ->with('can_use_shoutbox', $can_use_shoutbox)
            ->with('shoubox_messages', $messages)
            ->with('hideavs', $hideavs)
            ->with('lastTime', $lastTime)
            ->with('top_poster', $top_poster)
            ->with('top_poster_clean', $top_poster_clean)
            ->with('top_thread_starter', $top_thread_starter)
            ->with('top_thread_starter_clean', $top_thread_starter_clean)
            ->with('top_quests_sharer', $top_quests_sharer)
            ->with('top_quests_sharer_clean', $top_quests_sharer_clean)
            ->with('referrals', $referrals)
            ->with('infractionscount', $infractionscount)
            ->with('warningscount', $warningscount)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getForumStats()
    {
        $top15_posters = [];
        $top15_xp = [];
        $top15_lastest_posts = [];
        $top15_posters_today = [];
        $top15_trending = [];
        $trending_bets = [];
        $top10_keys = [];
        $top10_clans = [];

        $possible_forumids = [];
        $userid = Auth::check() ? Auth::user()->userid : 0;
        $temp_forums = DB::table('forums')->where('parentid', '!=', -1)->get();
        $ignored_forumids = Auth::check() ? explode(',', Auth::user()->ignored_forums) : [];

        foreach ($temp_forums as $forum) {
            if (UserHelper::haveForumPerm($userid, $forum->forumid, 1) && !in_array($forum->forumid, $ignored_forumids)) {
                $possible_forumids[] = $forum->forumid;
            }
        }

        $temp_posters = DB::table('users')->orderBy('postcount', 'DESC')->take(20)->get();
        $temp_xps = DB::table('users')->orderBy('xpcount', 'DESC')->where('userid', '!=', '5')->where('userid', '!=', '1')->take(20)->get();

        $temp_bets = DB::table('betting_user')->join('betting_bets', 'betting_bets.betid', '=', 'betting_user.betid')->where('betting_bets.finished', '0')->groupBy('betting_user.betid')->orderByRaw('COUNT(*) DESC')->limit(10)->get();

        $temp_posts = DB::table('threads')->whereIn('forumid', $possible_forumids)->orderBy('lastpost', 'DESC')->take(20)->get();

        $temp_keys = DB::table('users')->orderBy('owned_keys', 'DESC')->take(10)->get();
        $temp_clans = DB::table('clans')->orderBy('xpcount', 'DESC')->take(10)->get();

        $time_today = ForumHelper::findMidnight();
        $temp_posters_today = DB::select('SELECT count(*) AS number, userid FROM posts WHERE dateline > ' . $time_today . ' GROUP BY userid ORDER BY count(*) DESC LIMIT 15');
        $top15_posters_today = [];

        foreach($temp_bets as $temp_bet){
            $temp = DB::table('betting_bets')->where('betid', $temp_bet->betid)->first();

            $array = array(
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'shortbet' => mb_strimwidth($temp->bet, 0, 35, "..."),
                'odds' => $temp->odds
            );

            $trending_bets[] = $array;

        }

        foreach ($temp_posters_today as $temp_poster_today) {
            $top15_posters_today[] = [
                'userid' => $temp_poster_today->userid,
                'username' => UserHelper::getUsername($temp_poster_today->userid),
                'clean_username' => UserHelper::getUsername($temp_poster_today->userid, 1),
                'posts' => $temp_poster_today->number
            ];
        }

        foreach ($temp_posters as $temp_poster) {
            $top15_posters[] = [
                'userid' => $temp_poster->userid,
                'username' => UserHelper::getUsername($temp_poster->userid),
                'clean_username' => UserHelper::getUsername($temp_poster->userid, true),
                'posts' => number_format($temp_poster->postcount)
            ];
        }

        $levels = DB::Table('xp_levels')->orderBy('posts', 'DESC')->get();
        foreach ($temp_xps as $temp_xp) {
            $found = false;
            foreach ($levels as $level) {
                if ($found == false) {
                    if ($temp_xp->xpcount >= $level->posts) {
                        $level_id = $level->levelid;
                        $level_name = $level->name;
                        $found = true;
                    } else {
                        $nextlevel_posts = $level->posts;
                        $nextlevel_id = $level->levelid;
                    }
                }
            }
            $top15_xp[] = array(
                'userid' => $temp_xp->userid,
                'username' => UserHelper::getUsername($temp_xp->userid),
                'clean_username' => UserHelper::getUsername($temp_xp->userid, true),
                'level' => $level_name
            );
        }

        foreach ($temp_keys as $temp_key) {
            $top10_keys[] = array(
                'userid' => $temp_key->userid,
                'username' => UserHelper::getUsername($temp_key->userid),
                'clean_username' => UserHelper::getUsername($temp_key->userid, true),
                'keys' => $temp_key->owned_keys
            );
        }

        foreach ($temp_clans as $temp_clan) {
            $top10_clans[] = array(
                'groupname' => $temp_clan->groupname,
                'totalexp' => $temp_clan->xpcount
            );
        }

        foreach ($temp_posts as $temp_post) {
            if (Auth::check() && UserHelper::haveForumPerm(Auth::user()->userid, $temp_post->forumid, 32)) {
                $post = DB::table('posts')->where('postid', $temp_post->lastpostid)->first();
                if (!count($post)) {
                    $post = DB::table('posts')->where('threadid', $temp_post->threadid)->where('visible', 1)->orderBy('postid', 'DESC')->first();
                }
                if (count($post)) {
                    $prefix = DB::table('prefixes')->where('prefixid', $temp_post->prefixid)->first();
                    $count = ceil(DB::table('posts')->where('threadid', $post->threadid)->count() / 10);
                    $page = $count == 0 ? 1 : $count;

                    $title = ForumHelper::fixContent($temp_post->title);
                    $fulltitle = ForumHelper::fixContent($temp_post->title);
                    $title = mb_strimwidth($title, 0, 25, "...");
                    $title = str_replace(' ...', '...', $title);
                    if (count($prefix)) {
                        $title = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ' . $title;
                    }
                    $top15_lastest_posts[] = array(
                        'userid' => $post->userid,
                        'username' => UserHelper::getUsername($post->userid),
                        'clean_username' => UserHelper::getUsername($post->userid, true),
                        'title' => $title,
                        'fulltitle' => $fulltitle,
                        'threadid' => $temp_post->threadid,
                        'dateline' => ForumHelper::timeAgo($post->dateline),
                        'dateline_real' => $post->dateline,
                        'page' => $page
                    );
                }
            }
        }

        $tempTop15 = array();
        foreach ($top15_lastest_posts as $key => $row) {
            $tempTop15[$key] = $row['dateline_real'];
        }
        array_multisort($tempTop15, SORT_DESC, $top15_lastest_posts);

        $returnHTML = view('forum.extras.top-stats')
            ->with('top15_posters', $top15_posters)
            ->with('top15_xp', $top15_xp)
            ->with('top10_keys', $top10_keys)
            ->with('top10_clans', $top10_clans)
            ->with('top15_lastest_posts', $top15_lastest_posts)
            ->with('top15_posters_today', $top15_posters_today)
            ->with('trending_bets', $trending_bets)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBBCodes()
    {
        $bbcodes = array();
        $temps = DB::table('bbcodes')->where('staff_specific', '0')->get();

        foreach ($temps as $temp) {
            $array = array(
                'name' => $temp->name,
                'example' => $temp->example,
                'result' => ForumHelper::bbcodeParser($temp->example),
                'bbcodeid' => $temp->bbcodeid
            );

            $bbcodes[] = $array;
        }

        $returnHTML = view('forum.bbcodes')
            ->with('bbcodes', $bbcodes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getFollowers($username)
    {

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (count($user)) {
            $array = array(
                'username' => UserHelper::getUsername($user->userid),
                'clean_username' => UserHelper::getUsername($user->userid, true)
            );

            $temps = DB::table('followers')->where('userid', $user->userid)->orderBy('dateline', 'ASC')->get();
            $followers = array();

            foreach ($temps as $temp) {
                $tempArray = array(
                    'username' => UserHelper::getUsername($temp->follower),
                    'clean_username' => UserHelper::getUsername($temp->follower, true),
                    'habbo' => UserHelper::getHabbo($temp->follower, true)
                );

                $followers[] = $tempArray;
            }

            $returnHTML = view('profile.followers')
                ->with('user', $array)
                ->with('followers', $followers)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function getFollowing($username)
    {

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (count($user)) {
            $array = array(
                'username' => UserHelper::getUsername($user->userid),
                'clean_username' => UserHelper::getUsername($user->userid, true)
            );

            $temps = DB::table('followers')->where('follower', $user->userid)->orderBy('dateline', 'ASC')->get();
            $following = array();

            foreach ($temps as $temp) {
                $tempArray = array(
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => UserHelper::getUsername($temp->userid, true),
                    'habbo' => UserHelper::getHabbo($temp->userid, true)
                );

                $following[] = $tempArray;
            }

            $returnHTML = view('profile.following')
                ->with('user', $array)
                ->with('following', $following)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function getClanLeaderboard()
    {

        $clansTemp = DB::table('clans')->orderBy('xpcount', 'DESC')->get();
        $clans = array();

        foreach ($clansTemp as $temp) {

            $array = array(
                'groupid' => $temp->groupid,
                'groupname' => $temp->groupname,
                'owner' => UserHelper::getUsername($temp->memberid_owner),
                'owner_clean' => UserHelper::getUsername($temp->memberid_owner, true),
                'member2' => UserHelper::getUsername($temp->memberid_2),
                'member2_clean' => UserHelper::getUsername($temp->memberid_2, true),
                'member3' => UserHelper::getUsername($temp->memberid_3),
                'member3_clean' => UserHelper::getUsername($temp->memberid_3, true),
                'totalExp' => $temp->xpcount
            );

            $clans[] = $array;
        }

        $returnHTML = view('clans.leaderboard')
            ->with('clans', $clans)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getClan($name)
    {
        $clan = DB::table('clans')->whereRaw('lower(groupname) LIKE ?', [strtolower($name)])->first();


        if (count($clan)) {

            $members = array(
                'member1' => UserHelper::getUser($clan->memberid_owner),
                'member1_name' => UserHelper::getUsername($clan->memberid_owner),
                'member1_avatar' => UserHelper::getAvatar($clan->memberid_owner),
                'member1_xpcount' => DB::table('clan_activity')->where('action', 4)->where('clanid', $clan->groupid)->where('userid', $clan->memberid_owner)->count() * 3,
                'member2' => UserHelper::getUser($clan->memberid_2),
                'member2_name' => UserHelper::getUsername($clan->memberid_2),
                'member2_avatar' => UserHelper::getAvatar($clan->memberid_2),
                'member2_xpcount' => DB::table('clan_activity')->where('action', 4)->where('clanid', $clan->groupid)->where('userid', $clan->memberid_2)->count() * 3,
                'member3' => UserHelper::getUser($clan->memberid_3),
                'member3_name' => UserHelper::getUsername($clan->memberid_3),
                'member3_avatar' => UserHelper::getAvatar($clan->memberid_3),
                'member3_xpcount' => DB::table('clan_activity')->where('action', 4)->where('clanid', $clan->groupid)->where('userid', $clan->memberid_3)->count() * 3,
            );

            $inviteTemp = DB::table('clan_invites')->where('groupid', $clan->groupid)->where('userid', Auth::user()->userid)->where('response', 0)->first();

            $pendingInvite = false;
            if(count($inviteTemp)){ $pendingInvite = true; }

            $activityTemp = DB::table('clan_activity')->orderBy('dateline', 'DESC')->where('clanid', $clan->groupid)->where('action', '!=', '4')->get();
            $activity = array();

            foreach($activityTemp as $temp){

                switch ($temp->action){
                    case 1:
                        $message = 'created ' . $clan->groupname . '!';
                    break;

                    case 2:
                        $message = 'joined ' . $clan->groupname . '!';
                    break;

                    case 3:
                        $message = 'left ' . $clan->groupname . '!';
                    break;
                }

                $array = array(
                    'username' => UserHelper::getUsername($temp->userid),
                    'username_clean' => UserHelper::getUsername($temp->userid, true),
                    'action' => $message,
                    'dateline' => ForumHelper::timeAgo($temp->dateline)
                );

                $activity[] = $array;

            }

            $accolades = DB::table('clan_accolades')->where('groupid', $clan->groupid)->orderBy('display_order', 'DESC')->get();
            $accolade_count = DB::table('clan_accolades')->where('groupid', $clan->groupid)->count();
            $clan_accolades = array();

            foreach ($accolades as $accolade) {
                $clan_accolades[] = $accolade->description;
            }

            $returnHTML = view('clans.clan')
                ->with('clan', $clan)
                ->with('members', $members)
                ->with('pendingInvite', $pendingInvite)
                ->with('activity', $activity)
                ->with('accolades', $clan_accolades)
                ->with('accolade_count', $accolade_count)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function getCreateClan()
    {
        $returnHTML = view('clans.create')
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function inviteMember(Request $request)
    {
        $clanid = $request->input('clanid');
        $username = $request->input('username');
        $response = false;

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (count($user)) {

            $clanCheck = DB::table('clans')->where('memberid_owner',$user->userid)->count() + DB::table('clans')->where('memberid_2', $user->userid)->count() + DB::table('clans')->where('memberid_3', $user->userid)->count();

            if($clanCheck >= 3){

                $response = false;
                $message = 'The user is already in 3 clans.';

            }else{

                DB::table('notifications')->insert([
                    'postuserid' => Auth::user()->userid,
                    'reciveuserid' => $user->userid,
                    'content' => 19,
                    'contentid' => $clanid,
                    'dateline' => time(),
                    'read_at' => 0
                ]);

                DB::table('clan_invites')->insert([
                    'groupid' => $clanid,
                    'userid' => $user->userid,
                    'response' => 0
                ]);

                $response = true;
                $message = 'The user has been invited.';

            }

        }else{

            $response = false;
            $message = 'That user does not exist.';

        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postInviteResponse(Request $request)
    {
        $clanid = $request->input('clanid');
        $inviteResponse = $request->input('response');
        $response = false;
        $message = 'Success';

        $clanTemp = DB::table('clan_invites')->where('groupid', $clanid)->where('userid', Auth::user()->userid)->first();

        if(count($clanTemp)){

            $clanCheck = DB::table('clans')->where('memberid_owner', Auth::user()->userid)->count() + DB::table('clans')->where('memberid_2', Auth::user()->userid)->count() + DB::table('clans')->where('memberid_3', Auth::user()->userid)->count();

            if($clanCheck >= 3){

                $message = 'You are already in 3 clans.';

            }else{

                $clanInfo = DB::table('clans')->where('groupid', $clanid)->first();

                $response = true;

                DB::table('clan_invites')->where('groupid', $clanid)->where('userid', Auth::user()->userid)->update([
                    'response' => $inviteResponse,
                ]);

                if($inviteResponse == 1){
                    if($clanInfo->memberid_2 == 0 || $clanInfo->memberid_3 == 0){

                        DB::table('clan_activity')->insert([
                            'userid' => Auth::user()->userid,
                            'clanid' => $clanid,
                            'action' => 2,
                            'dateline' => time()
                        ]);

                        if($clanInfo->memberid_2 == 0){
                            DB::table('clans')->where('groupid', $clanid)->update([
                                'memberid_2' => Auth::user()->userid,
                            ]);
                        }

                        if($clanInfo->memberid_2 > 0 && $clanInfo->memberid_3 == 0){
                            DB::table('clans')->where('groupid', $clanid)->update([
                                'memberid_3' => Auth::user()->userid,
                            ]);
                        }
                    }else{
                        $message = 'There are no open spaces in that clan.';
                    }
                }

            }
        }else{
            $message = 'There is no active invite for that clan.';
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));

    }

    public function leaveClan(Request $request)
    {
        $clanid = $request->input('clanid');
        $response = false;
        $message = '';

        $clanTemp = DB::table('clans')->where('groupid', $clanid)->where('memberid_2', Auth::user()->userid)->orWhere('memberid_3', Auth::user()->userid)->first();

        if(count($clanTemp)){
            $response = true;

            if($clanTemp->memberid_2 == Auth::user()->userid){
                DB::table('clans')->where('groupid', $clanid)->update([
                    'memberid_2' => 0,
                ]);
            }

            if($clanTemp->memberid_3 == Auth::user()->userid){
                DB::table('clans')->where('groupid', $clanid)->update([
                    'memberid_3' => 0,
                ]);
            }
        }else{
            $mesage = 'You are not in the clan.';
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function disbandClan(Request $request)
    {
        $clanid = $request->input('clanid');
        $response = false;
        $message = '';

        $clanTemp = DB::table('clans')->where('groupid', $clanid)->where('memberid_owner', Auth::user()->userid)->first();

        if(count($clanTemp)){
            $response = true;

            DB::table('clans')->where('groupid', $clanid)->where('memberid_owner', Auth::user()->userid)->delete();
            DB::table('clan_invites')->where('groupid', $clanid)->delete();
            DB::table('notifications')->where('content', 19)->where('contentid', $clanid)->delete();

        }else{
            $mesage = 'You cannot disband the clan.';
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postCreateClan(Request $request)
    {

        $name = ForumHelper::fixContent($request->input('name'));
        $response = false;
        $message = '';

        if($name == ''){

            $message = 'You have not defined a name for the clan.';

        }else{

            $clanCheck = DB::table('clans')->where('memberid_owner', Auth::user()->userid)->count() + DB::table('clans')->where('memberid_2', Auth::user()->userid)->count() + DB::table('clans')->where('memberid_3', Auth::user()->userid)->count();

            if($clanCheck >= 3){

                $message = 'You can only be in up to 3 clans.';

            }else{

                $clanTemp = DB::table('clans')->whereRaw('lower(groupname) LIKE ?', [strtolower($name)])->first();

                if(count($clanTemp)){

                    $message = 'That clan already exists.';

                }else{

                    if(Auth::user()->credits >= 500){

                        DB::table('users')->where('userid', Auth::user()->userid)->update([
                            'credits' => Auth::user()->credits - 500
                        ]);

                        DB::table('clans')->insert([
                            'groupname' => $name,
                            'avatar' => 0,
                            'cover' => 0,
                            'memberid_owner' => Auth::user()->userid,
                            'memberid_2' => 0,
                            'memberid_3' => 0,
                            'xpcount' => 0
                        ]);

                        $tempInfo = DB::table('clans')->whereRaw('lower(groupname) LIKE ?', [strtolower($name)])->first();

                        DB::table('clan_activity')->insert([
                            'userid' => Auth::user()->userid,
                            'clanid' => $tempInfo->groupid,
                            'action' => 1,
                            'dateline' => time()
                        ]);

                        $response = true;

                    }else{

                        $message = 'You cannot afford to create a clan.';

                    }

                }

            }
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));

    }

    public function getEditClanAvatar($name)
    {
        $clan = DB::table('clans')->where('memberid_owner', Auth::user()->userid)->whereRaw('lower(groupname) LIKE ?', [strtolower($name)])->first();

        if(count($clan)){

            $returnHTML = view('clans.avatar')
                ->with('clan', $clan)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));

        }

    }

    public function getEditClanHeader($name)
    {
        $clan = DB::table('clans')->where('memberid_owner', Auth::user()->userid)->whereRaw('lower(groupname) LIKE ?', [strtolower($name)])->first();

        if(count($clan)){

            $returnHTML = view('clans.header')
                ->with('clan', $clan)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));

        }

    }

    public function postClanAvatar(Request $request)
    {
        $response = false;

        $clanid = $request->input('clanid');

        if ($request->hasFile('avatar') and $request->file('avatar')->isValid()) {

            $clan = DB::table('clans')->where('groupid', $clanid)->first();

            $img = Image::make($request->file('avatar'));
            $avid = time() . $clan->groupid;

            $max_height = 200;
            $max_width = 200;
            $resize = false;

            if ($img->height() > $max_height) {
                $resize = true;
                $img->resize(null, $max_height);
            }

            if ($img->width() > $max_width) {
                $resize = true;
                $img->resize($max_width, null);
            }

            if ($resize) {
                $img->save('_assets/img/clanAvatars/' . $avid . '.gif', 100);
            } else {
                UserHelper::saveAnimatedImage($request->file('avatar'), $avid, '_assets/img/clanAvatars/');
            }

            if ($clan->avatar != 0) {
                File::delete(asset('_assets/img/clanAvatars/'.$clan->avatar).'.gif');
            }

            DB::table('clans')->where('groupid', $clan->groupid)->update([
                'avatar' => $avid
            ]);
            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => ''));
    }

    public function postClanHeader(Request $request)
    {
        $response = false;

        $clanid = $request->input('clanid');

        if ($request->hasFile('header') and $request->file('header')->isValid()) {

            $clan = DB::table('clans')->where('groupid', $clanid)->first();

            $img = Image::make($request->file('header'));
            $avid = time() . $clan->groupid;

            UserHelper::saveAnimatedImage($request->file('header'), $avid, '_assets/img/clanHeaders/');

            if ($clan->cover != 0) {
                File::delete(asset('_assets/img/clanHeaders/'.$clan->cover).'.gif');
            }

            DB::table('clans')->where('groupid', $clan->groupid)->update([
                'cover' => $avid
            ]);
            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => ''));
    }
}