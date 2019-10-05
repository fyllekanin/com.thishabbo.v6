<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Helpers\ForumHelper;
use App\Helpers\UserHelper;
use App\Helpers\imageHelper;
use File;
use Image;

class PageController extends BaseController
{
    public function __construct()
    {
    }

    public function getSearch()
    {
        $userid = 0;
        if (Auth::check()) {
            $userid = Auth::user()->userid;
        }

        $pagenr = isset($_GET['pagenr']) ? $_GET['pagenr'] : 1;
        $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : 'thread';
        $searchforum = isset($_GET['searchforum']) ? $_GET['searchforum'] : -1;
        $from = isset($_GET['from']) ? $_GET['from'] : 'all';
        $newerolder = isset($_GET['newerolder']) ? $_GET['newerolder'] : 'newer';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
        $user = isset($_GET['user']) ? $_GET['user'] : '';

        if ($pagenr < 1) {
            $pagenr = 1;
        }
        $take = 30;
        $skip = 0;
        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($user == '') {
            $searchuserid = -1;
        } else {
            $searchuserid = DB::table('users')->where('username', 'LIKE', $user)->value('userid');
            if (!count($userid)) {
                $searchuserid = -1;
            }
        }

        if ($newerolder === "newer") {
            $dateto = time();
            if ($from==='all') {
                $datefrom = 0;
            } else {
                $datefrom = strtotime("-1 ".$from, $dateto);
            }
        } elseif ($newerolder === "older") {
            $datefrom = 0;
            if ($from==='all') {
                $dateto = time();
            } else {
                $dateto = strtotime("-1 ".$from, time());
            }
        }

        $userid = 0;
        if (Auth::check()) {
            $userid = Auth::user()->userid;
        }

        /* Code to populate dropdown with forums */
        $forums = ForumHelper::getForums($userid);
        /* end of code to populate dropdown with forums */


        $returnresults = array();
        $pagi = 0;
        if (strlen($criteria) > 0 || strlen($user) >0) {
            if ($type==='thread') {
                if ($searchuserid != -1) {
                    $pagesx = DB::table('threads')->where('title', 'LIKE', '%'.$criteria.'%')
                                                     ->where('dateline', '>=', $datefrom)
                                                     ->where('dateline', '<=', $dateto)
                                                     ->where('postuserid', $searchuserid)
                                                     ->orderBy('dateline', $sort)
                                                     ->count();
                    $queryresult = DB::table('threads')->where('title', 'LIKE', '%'.$criteria.'%')
                                                     ->where('dateline', '>=', $datefrom)
                                                     ->where('dateline', '<=', $dateto)
                                                     ->where('postuserid', $searchuserid)
                                                     ->orderBy('dateline', $sort)
                                                     ->take($take)
                                                     ->skip($skip)
                                                     ->get();
                } else {
                    $pagesx = DB::table('threads')->where('title', 'LIKE', '%'.$criteria.'%')
                                                     ->where('dateline', '>=', $datefrom)
                                                     ->where('dateline', '<=', $dateto)
                                                     ->orderBy('dateline', $sort)
                                                     ->count();
                    $queryresult = DB::table('threads')->where('title', 'LIKE', '%'.$criteria.'%')
                                                     ->where('dateline', '>=', $datefrom)
                                                     ->where('dateline', '<=', $dateto)
                                                     ->orderBy('dateline', $sort)
                                                     ->take($take)
                                                     ->skip($skip)
                                                     ->get();
                }

                $results = array();
                foreach ($queryresult as $row) {
                    $forumid = $row->forumid;
                    if ($searchforum != -1 && $forumid != $searchforum) {
                        continue;
                    }
                    if (!UserHelper::haveForumPerm($userid, $forumid, 32)) {
                        continue;
                    }
                    $result = array(
                        'title' => $row->title,
                        'poster' => UserHelper::getUsername($row->postuserid),
                        'date' => ForumHelper::getTimeInDate($row->dateline),
                        'threadid' => $row->threadid,
                        'posts' => DB::table('posts')->where('threadid', $row->threadid)->count()
                    );
                    $results[] = $result;
                }
                $returnresults['type'] = 'thread';
                $returnresults['results'] = $results;
            } elseif ($type==='post') {
                $queryresult = array();
                if ($searchuserid != -1) {
                    $pagesx = DB::table('posts')->where('content', 'LIKE', '%'.$criteria.'%')
                                                   ->where('dateline', '>=', $datefrom)
                                                   ->where('dateline', '<=', $dateto)
                                                   ->where('userid', $searchuserid)
                                                   ->orderBy('dateline', $sort)
                                                   ->count();
                    $queryresult = DB::table('posts')->where('content', 'LIKE', '%'.$criteria.'%')
                                                   ->where('dateline', '>=', $datefrom)
                                                   ->where('dateline', '<=', $dateto)
                                                   ->where('userid', $searchuserid)
                                                   ->orderBy('dateline', $sort)
                                                   ->skip($skip)
                                                   ->take($take)
                                                   ->get();
                } else {
                    $pagesx = DB::table('posts')->where('content', 'LIKE', '%'.$criteria.'%')
                                                   ->where('dateline', '>=', $datefrom)
                                                   ->where('dateline', '<=', $dateto)
                                                   ->orderBy('dateline', $sort)
                                                   ->count();
                    $queryresult = DB::table('posts')->where('content', 'LIKE', '%'.$criteria.'%')
                                                   ->where('dateline', '>=', $datefrom)
                                                   ->where('dateline', '<=', $dateto)
                                                   ->orderBy('dateline', $sort)
                                                   ->skip($skip)
                                                   ->take($take)
                                                   ->get();
                }


                $results = array();
                foreach ($queryresult as $row) {
                    $forumid = DB::table('threads')->where('threadid', $row->threadid)->value('forumid');
                    if ($searchforum != -1 && $forumid != $searchforum) {
                        continue;
                    }
                    if (!UserHelper::haveForumPerm($userid, $forumid, 32)) {
                        continue;
                    }

                    $result = array(
                        'threadid' => $row->threadid,
                        'threadurl' => '/forum/thread/'.$row->threadid.'/page/1',
                        'threadtitle' => DB::table('threads')->where('threadid', $row->threadid)->value('title'),
                        'content' => nl2br(ForumHelper::bbcodeParser($row->content)),
                        'poster' => $row->username,
                        'date' => ForumHelper::getTimeInDate($row->dateline)
                    );
                    $results[] = $result;
                }
                $returnresults['type'] = 'post';
                $returnresults['results'] = $results;
            } elseif ($type==='article') {
                $queryresult = array();
                $queryresult = DB::table('articles')->where('title', 'LIKE', '%'.$criteria.'%')
                                                    ->get();

                $pagesx = 0;
                $results = array();
                foreach ($queryresult as $row) {
                    $result = array(
                        'articleid' => $row->articleid,
                        'date' => ForumHelper::getTimeInDate($row->dateline),
                        'title' => $row->title,
                        'poster' => UserHelper::getUsername($row->userid),
                        'badgecode' => $row->badge_code
                    );
                    ;
                    $results[] = $result;
                }
                $returnresults['type'] = 'article';
                $returnresults['results'] = $results;
            } elseif ($type==='user') {
                $queryresult = array();
                $queryresult = DB::table('users')->where('username', 'LIKE', '%'.$criteria.'%')
                                                 ->get();

                $pagesx = 0;
                $results = array();
                foreach ($queryresult as $row) {
                    $result = array(
                        'username' => $row->username,
                        'avatar' => UserHelper::getAvatar($row->userid),
                        'last_online' => ForumHelper::timeAgo($row->lastactivity)
                    );
                    $results[] = $result;
                }
                $returnresults['type'] = 'user';
                $returnresults['results'] = $results;
            }

            if ($pagesx == 0) {
                $pages = 1;
            } else {
                $pages = ceil($pagesx/$take);
            }

            if ($pagenr > $pages) {
                $pagenr = $pages;
            }

            $paginator = array(
                'total' => $pages,
                'current' => $pagenr,
                'previous' => $pagenr-1 <= 0 ? 1 : $pagenr-1,
                'previous_exists' => $pagenr-1 < 1 ? false : true,
                'next' => $pagenr+1 >$pages ? $pages : $pagenr+1,
                'next_exists' => $pagenr+1 > $pages ? false : true,
                'gap_forward' => $pagenr+5 < $pages ? true : false,
                'gap_backward' => $pagenr-5 > 1 ? true : false
            );


            $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/search?criteria='.$criteria.'&type='.$type.'&searchforum='.$searchforum.'&from='.$from.'&newerolder='.$newerolder.'&user='.$user.'&sort='.$sort.'&pagenr=')->render();
        }


        $returnHTML = view('search')->with('forums', $forums)
                                    ->with('pagi', $pagi)
                                    ->with('results', $returnresults)
                                    ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRules()
    {
        $rules = "";
        if (File::exists('rules.txt')) {
            $rules = File::get('rules.txt');
        }
        $returnHTML = view('pages.rules')
        ->with('rules', nl2br(ForumHelper::bbcodeParser($rules)))
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getPartners()
    {
        $linkpartners = "";
        if (File::exists('linkpartners.txt')) {
            $linkpartners = File::get('linkpartners.txt');
        }
        $returnHTML = view('pages.partners')
        ->with('linkpartners', nl2br(ForumHelper::bbcodeParser($linkpartners)))
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getActivity($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = Auth::user()->userid;

        $take = 10;
        $skip = 0;

        $access_to_forums = [0];

        $temps = DB::table('forums')->where('parentid', '>', -1)->get();

        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $temp->forumid, 32)) {
                $access_to_forums[] = $temp->forumid;
            }
        }

        $pagesx = DB::table('livewall')->whereIn('forumid', $access_to_forums)->count();

        $pages = ceil($pagesx/$take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr-1 <= 0 ? 1 : $pagenr-1,
            'previous_exists' => $pagenr-1 < 1 ? false : true,
            'next' => $pagenr+1 >$pages ? $pages : $pagenr+1,
            'next_exists' => $pagenr+1 > $pages ? false : true,
            'gap_forward' => $pagenr+5 < $pages ? true : false,
            'gap_backward' => $pagenr-5 > 1 ? true : false
        );

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/livewall/page/')->render();

        $temps = DB::table('livewall')->whereIn('forumid', $access_to_forums)->take($take)->skip($skip)->get();

        $items = [];

        foreach ($temps as $temp) {
        }

        $returnHTML = view('pages.activity')
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getLeaderboard()
    {
        $top_posters = array();
        $top_threads = array();
        $top_forumloves = array();
        $top_creatives = array();
        $top_commenters = array();
        $top_djlikes = array();
        $top_collectors = array();
        $top_followed = array();

        $temps = DB::table('users')->take(10)->orderBy('threadcount', 'DESC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($temp->threadcount),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_threads[] = $array;
        }

        $temps = DB::table('users')->take(10)->orderBy('postcount', 'DESC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($temp->postcount),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_posters[] = $array;
        }

        $temps = DB::table('users')->take(10)->orderBy('likecount', 'DESC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($temp->likecount),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_forumloves[] = $array;
        }

        $temps = DB::select('SELECT userid FROM creations GROUP BY userid ORDER BY COUNT(*) DESC LIMIT 10');

        foreach ($temps as $temp) {
            $count = DB::table('creations')->where('userid', $temp->userid)->count();

            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($count),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_creatives[] = $array;
        }

        $temps = DB::select('SELECT djid FROM dj_likes GROUP BY djid ORDER BY COUNT(*) DESC LIMIT 10');

        foreach ($temps as $temp) {
            $count = DB::table('dj_likes')->where('djid', $temp->djid)->count();

            $array = array(
                'clean_username' => UserHelper::getUsername($temp->djid, true),
                'username' => UserHelper::getUsername($temp->djid),
                'amount' => number_format($count),
                'avatar' => UserHelper::getAvatar($temp->djid)
            );

            $top_djlikes[] = $array;
        }

        $temps = DB::select('SELECT userid FROM users_badges GROUP BY userid ORDER BY COUNT(*) DESC LIMIT 10');

        foreach ($temps as $temp) {
            $count = DB::table('users_badges')->where('userid', $temp->userid)->count();

            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($count),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_collectors[] = $array;
        }

        $temps = DB::select('SELECT userid FROM followers GROUP BY userid ORDER BY COUNT(*) DESC LIMIT 10');

        foreach ($temps as $temp) {
            $count = DB::table('followers')->where('userid', $temp->userid)->count();

            $array = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'username' => UserHelper::getUsername($temp->userid),
                'amount' => number_format($count),
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $top_followed[] = $array;
        }

        $returnHTML = view('pages.leaderboard')
        ->with('top_threads', $top_threads)
        ->with('top_posters', $top_posters)
        ->with('top_forumloves', $top_forumloves)
        ->with('top_creatives', $top_creatives)
        ->with('top_djlikes', $top_djlikes)
        ->with('top_collectors', $top_collectors)
        ->with('top_followed', $top_followed)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function downloadCreation($creationid)
    {
        $file = '_assets/img/creations/' . $creationid . '.gif';
        if (File::exists($file)) {
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Type: image/png");
            header("Content-Length:".File::size($file));
            header("Content-Disposition: attachment; filename=" . $creationid . '.gif');
            readfile($file);
            die();
        }
    }

    public function listEventTypes()
    {
        $temps = DB::table('event_types')->get();

        $event_types = array();

        foreach ($temps as $temp) {
            $description = str_replace(">", "&#62;", $temp->desc);
            $description = str_replace("<", "&#60;", $description);

            $description = ForumHelper::bbcodeParser($description);

            $description = nl2br($description);

            $array = array(
                'event' => $temp->event,
                'desc' => $description,
            );

            $event_types[] = $array;
        }

        $returnHTML = view('pages.eventTypes')
        ->with('event_types', $event_types)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postCreationComment(Request $request)
    {
        $creationid = $request->input('creationid');
        $content = $request->input('content');
        $response = false;
        $message = "Something went wrong!";

        $article = DB::table('creations')->where('creationid', $creationid)->first();

        $username = "";
        $clean_username = "";

        if (count($article)) {
            if (strlen($content) > 0) {
                if (strlen($content) > 100) {
                    $content = substr($content, 0, 100);
                }

                $check = DB::table('creation_comments')->where('userid', Auth::user()->userid)->where('visible', 1)->where('dateline', '>', time()-5)->count();

                if ($check == 0) {
                    $tagged_userids = array();

                    if (preg_match_all('/(?<=^|\s)@(\w+)/', $content, $match)) {
                        foreach ($match[1] as $usr) {
                            $user = DB::table('users')
                            ->whereRaw('lower(username) LIKE ?', [strtolower($usr)])
                            ->where('userid', '!=', $userid)
                            ->first();

                            if (count($user)) {
                                $content = str_replace("@".$usr, "[mention]" . $user->username . '[/mention]', $content);
                                if (!in_array($user->userid, $tagged_userids)) {
                                    $tagged_userids[] = array('userid' => $user->userid, 'content' => 12);
                                }
                            }
                        }
                    }

                    $commentid = DB::table('creation_comments')->insertGetId([
                        'creationid' => $creationid,
                        'userid' => Auth::user()->userid,
                        'content' => $content,
                        'dateline' => time()
                    ]);

                    DB::table('creations')->where('creationid', $creationid)->update([
                        'comments' => DB::raw('comments+1'),
                    ]);

                    DB::table('livewall')->insert([
                        'userid' => Auth::user()->userid,
                        'forum' => 0,
                        'forumid' =>  0,
                        'item_id' => $creationid,
                        'item_type' => 3,
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

                        $user = DB::table('users')->where('userid', $tagged['userid'])->first();
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
                        'commentcount' => DB::raw('commentcount+1')
                    ]);

                    $content = str_replace(">", "&#62;", $content);
                    $content = str_replace("<", "&#60;", $content);
                    $content = str_replace("\"", "&quot;", $content);

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
            $message = "Could not find creation!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'username' => $username, 'clean_username' => $clean_username, 'content' => $content));
    }

    public function postLikeCreation(Request $request)
    {
        $creationid = $request->input('creationid');

        $creation = DB::table('creations')->where('creationid', $creationid)->where('approved', 1)->first();

        if (count($creation)) {
            $check = DB::table('creation_likes')->where('creationid', $creationid)->where('userid', Auth::user()->userid)->count();

            if ($check == 0) {
                DB::table('creation_likes')->insert([
                    'creationid' => $creationid,
                    'userid' => Auth::user()->userid
                ]);

                DB::table('livewall')->insert([
                    'userid' => Auth::user()->userid,
                    'forum' => 0,
                    'forumid' => 0,
                    'item_id' => $creationid,
                    'item_type' => 3,
                    'message' => 'liked',
                    'dateline' => time()
                ]);

                DB::table('creations')->where('creationid', $creationid)->update([
                    'likes' => DB::raw('likes+1'),
                ]);
            }
        }

        return response()->json(array('success' => true));
    }

    public function postUnlikeCreation(Request $request)
    {
        $creationid = $request->input('creationid');

        $creation = DB::table('creations')->where('creationid', $creationid)->where('approved', 1)->first();

        if (count($creation)) {
            $check = DB::table('creation_likes')->where('creationid', $creationid)->where('userid', Auth::user()->userid)->count();

            if ($check > 0) {
                DB::table('creation_likes')->where('creationid', $creationid)->where('userid', Auth::user()->userid)->delete();

                DB::table('creations')->where('creationid', $creationid)->update([
                    'likes' => DB::raw('likes-1'),
                ]);
            }
        }

        return response()->json(array('success' => true));
    }

    public function getCreation($creationid, $pagenr = 1)
    {
        $creation = DB::table('creations')->where('creationid', $creationid)->where('approved', 1)->first();

        if (count($creation)) {
            $user = DB::table('users')->where('userid', $creation->userid)->first();
            if (count($user)) {
                $likes = number_format($creation->likes);
                $comments = array();
                $name = $creation->name;
                $avatar = UserHelper::getAvatar($creation->userid);
                $bio = ForumHelper::fixContent($user->bio);
                $bio = ForumHelper::bbcodeParser($bio);
                $username = UserHelper::getUsername($user->userid);
                $clean_username = UserHelper::getUsername($user->userid, true);
                $image = asset('_assets/img/creations/' . $creation->creationid . '.gif');

                $other_creations = array();
                $temps = DB::table('creations')->where('approved', 1)->where('userid', $user->userid)->where('creationid', '!=', $creationid)->take(8)->orderBy(DB::raw('RAND()'))->get();

                if (!isset($pagenr) ||$pagenr <= 0) {
                    $pagenr = 1;
                }

                $take = 10;
                $skip = 0;

                $pagesx = DB::table('creation_comments')->where('creationid', $creation->creationid)->where('visible', 1)->count();

                $pages = ceil($pagesx/$take);

                if ($pagenr > $pages) {
                    $pagenr = $pages;
                }

                $paginator = array(
                    'total' => $pages,
                    'current' => $pagenr,
                    'previous' => $pagenr-1 <= 0 ? 1 : $pagenr-1,
                    'previous_exists' => $pagenr-1 < 1 ? false : true,
                    'next' => $pagenr+1 >$pages ? $pages : $pagenr+1,
                    'next_exists' => $pagenr+1 > $pages ? false : true,
                    'gap_forward' => $pagenr+5 < $pages ? true : false,
                    'gap_backward' => $pagenr-5 > 1 ? true : false
                );

                $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/creation/' . $creation->creationid . '/page/')->render();

                if ($pagenr >= 2) {
                    $skip = $take*$pagenr - $take;
                }

                foreach ($temps as $temp) {
                    $array = array(
                        'creationid' => $temp->creationid,
                        'name' => str_replace("<", "&#62;", $temp->name),
                        'username' => UserHelper::getUsername($temp->userid),
                        'clean_username' => UserHelper::getUsername($temp->userid, true),
                        'time' => ForumHelper::timeAgo($temp->dateline),
                        'likes' => number_format($temp->likes),
                        'comments' => number_format($temp->comments),
                        'image' => asset('_assets/img/creations/' . $temp->creationid . '.gif')
                    );

                    $other_creations[] = $array;
                }

                if (Auth::check()) {
                    $userid = Auth::user()->userid;
                } else {
                    $userid = 0;
                }

                $can_manage_creations = UserHelper::haveGeneralModPerm($userid, 128);
                $can_delete_creation_comments = UserHelper::haveGeneralModPerm($userid, 1024);


                $liked = false;

                $check = DB::table('creation_likes')->where('userid', $userid)->where('creationid', $creation->creationid)->count();

                if ($check > 0) {
                    $liked = true;
                }

                $temps = DB::table('creation_comments')->where('creationid', $creation->creationid)->where('visible', 1)->orderBy('commentid', 'ASC')->take($take)->skip($skip)->get();

                foreach ($temps as $temp) {
                    $username = "noone";

                    $user = DB::table('users')->where('userid', $temp->userid)->first();

                    if (count($user)) {
                        $username = $user->username;
                    }

                    $content = str_replace(">", "&#62;", $temp->content);
                    $content = str_replace("<", "&#60;", $content);
                    $content = str_replace("\"", "&quot;", $content);

                    $array = array(
                        'username' => UserHelper::getUsername($temp->userid),
                        'userid' => $temp->userid,
                        'clean_username' => $username,
                        'content' => nl2br(ForumHelper::bbcodeParser($content)),
                        'commentid' => $temp->commentid,
                        'avatar' => UserHelper::getAvatar($temp->userid),
                        'dateline' => ForumHelper::timeago($temp->dateline)
                    );

                    $comments[] = $array;
                }

                $can_delete_creation_comments = false;
                if (Auth::check()) {
                    if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 1024)) {
                        $can_delete_creation_comments = true;
                    }
                }

                $returnHTML = view('pages.creation')
                ->with('likes', $likes)
                ->with('commentsAmount', number_format(count($comments)))
                ->with('comments', $comments)
                ->with('name', $name)
                ->with('avatar', $avatar)
                ->with('bio', $bio)
                ->with('username', $username)
                ->with('clean_username', $clean_username)
                ->with('image', $image)
                ->with('other_creations', $other_creations)
                ->with('can_manage_creations', $can_manage_creations)
                ->with('creationid', $creation->creationid)
                ->with('liked', $liked)
                ->with('can_delete_creation_comments', $can_delete_creation_comments)
                ->with('pagi', $pagi)
                ->render();

                return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
            }
        }

        return redirect()->route('getErrorPerm');
    }

    public function postUploadCreation(Request $request)
    {
        $tags = explode(",", $request->input('tags'));
        $response = false;
        $message = "Something went wrong!";
        $name = $request->input('name');

        if ($request->hasFile('creation')) {
            if (strlen($name) > 0) {
                $check = DB::table('creations')->where('userid', Auth::user()->userid)->where('dateline', '>', time()-15)->count();
                if ($check == 0) {
                    $ImageHelper = new ImageHelper($request->file('creation'));
                    $img = Image::make($request->file('creation'));
                    $approved = 0;
                    if ($ImageHelper->quantifyYCbCr() > 0.2) {
                        $message = "Creation uploaded, Waiting for approval!";
                        $approved = 0;
                    } else {
                        $message = "Creation uploaded, Waiting for approval!";
                    }

                    $tgs = "";
                    $first = true;

                    if (is_array($tags) and count($tags) > 0) {
                        foreach ($tags as $tag) {
                            if ($first) {
                                $tgs = $tag;
                                $first = false;
                            } else {
                                $tgs = $tgs . ',' . $tag;
                            }
                        }
                    }

                    $creationid = DB::table('creations')->insertGetId([
                        'name' => $name,
                        'tags' => $tgs,
                        'userid' => Auth::user()->userid,
                        'dateline' => time(),
                        'likes' => 0,
                        'comments' => 0,
                        'approved' => $approved
                    ]);

                    $img->save('_assets/img/creations/' . $creationid . '.gif', 60);
                    $response = true;
                } else {
                    $message = "You can't upload that quick!";
                }
            } else {
                $message = "You didn't fill any name";
            }
        } else {
            $message = "You din't pick an image to upload!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function getUploadCreation()
    {
        $returnHTML = view('pages.extras.creationUpload')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getCreations($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 12;
        $skip = 0;

        $pagesx = DB::table('creations')->where('approved', 1)->count();

        $pages = ceil($pagesx/$take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr-1 <= 0 ? 1 : $pagenr-1,
            'previous_exists' => $pagenr-1 < 1 ? false : true,
            'next' => $pagenr+1 >$pages ? $pages : $pagenr+1,
            'next_exists' => $pagenr+1 > $pages ? false : true,
            'gap_forward' => $pagenr+5 < $pages ? true : false,
            'gap_backward' => $pagenr-5 > 1 ? true : false
        );

        $temps = DB::table('creations')->where('approved', 1)->orderBy('creationid', 'DESC')->take($take)->skip($skip)->get();
        $creations = array();

        foreach ($temps as $temp) {
            $array = array(
                'creationid' => $temp->creationid,
                'name' => str_replace("<", "&#62;", $temp->name),
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'time' => ForumHelper::timeAgo($temp->dateline),
                'likes' => number_format($temp->likes),
                'comments' => number_format($temp->comments),
                'image' => asset('_assets/img/creations/' . $temp->creationid . '.gif')
            );

            $creations[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/creations/page/')->render();

        $returnHTML = view('pages.creations')
        ->with('pagi', $pagi)
        ->with('creations', $creations)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioTimetable()
    {
        $timetable = array();

        $ptime = 0;
        $mtime = 0;

        $adjtime = ForumHelper::returnTimeAfterTimezone(time());

        $actualDay = date("N", $adjtime);
        $actualHour = date("G", $adjtime);

        for ($x = 1; $x <= 7; $x++) {
            for ($y = 0; $y <= 23; $y++) {
                $timetable[$x . '-' . $y] = "<td id=\"" . $x . "-" . $y . "\" style=\"text-align:center;\"><i>Not Booked</i></td>";
            }
        }

        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->value != 0) {
                    if ($timezone->negative == 1) {
                        $mtime = $timezone->value;
                    } else {
                        $ptime = $timezone->value;
                    }
                }
            }
        }

        $temps = DB::table('timetable')->where('type', 0)->get();

        foreach ($temps as $temp) {
            $current_day = $temp->day;
            $current_time = $temp->time;
            if ($ptime != 0) {
                $current_time += $ptime;

                if ($current_time > 23) {
                    $current_day += 1;
                    $current_time -= 24;

                    if ($current_day > 7) {
                        $current_day -= 7;
                    }
                }
            } elseif ($mtime != 0) {
                $current_time -= $mtime;

                if ($current_time < 0) {
                    $current_day -= 1;
                    $current_time += 24;

                    if ($current_day < 1) {
                        $current_day += 7;
                    }
                }
            }

            if($current_day == $actualDay && $current_time == $actualHour){
                $timetable[$current_day . '-' . $current_time] = '<td class="web-page hover-box-info" title="Current DJ!" style="text-align:center; background: #c8ffc8;" id="' . $current_day . '-' . $current_time . '"><a href="/profile/' . UserHelper::getUsername($temp->userid, true) . '">' . UserHelper::getUsername($temp->userid) . '</a></td>';
            }else{
                $timetable[$current_day . '-' . $current_time] = '<td style="text-align:center;" id="' . $current_day . '-' . $current_time . '"><a href="/profile/' . UserHelper::getUsername($temp->userid, true) . '">' . UserHelper::getUsername($temp->userid) . '</a></td>';
            }
        }

        $returnHTML = view('pages.radiotimetable')->with('timetable', $timetable)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getLoveLeaderboard()
    {
        $top25 = array();
        $temps = DB::table('users')->where('likes', '>', 0)->take(25)->orderBy('likes', 'DESC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'joined' => ForumHelper::getTimeInDate($temp->joindate),
                'amount' => $temp->likes,
                'username' => UserHelper::getUsername($temp->userid)
            );

            $top25[] = $array;
        }

        $returnHTML = view('pages.djloveleaderboard')->with('top25', $top25)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRequestLine()
    {
        $returnHTML = view('pages.requests')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEvents()
    {
        $timetable = array();

        $ptime = 0;
        $mtime = 0;

        $adjtime = ForumHelper::returnTimeAfterTimezone(time());

        $actualDay = date("N", $adjtime);
        $actualHour = date("G", $adjtime);

        for ($x = 1; $x <= 7; $x++) {
            for ($y = 0; $y <= 23; $y++) {
                $timetable[$x . '-' . $y] = "<td id=\"" . $x . "-" . $y . "\" style=\"text-align:center;\"><i>Not Booked</i></td>";
            }
        }

        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->value != 0) {
                    if ($timezone->negative == 1) {
                        $mtime = $timezone->value;
                    } else {
                        $ptime = $timezone->value;
                    }
                }
            }
        }

        $temps = DB::table('timetable')->where('type', 1)->get();

        foreach ($temps as $temp) {
            $current_day = $temp->day;
            $current_time = $temp->time;
            if ($ptime != 0) {
                $current_time += $ptime;

                if ($current_time > 23) {
                    $current_day += 1;
                    $current_time -= 24;

                    if ($current_day > 7) {
                        $current_day -= 7;
                    }
                }
            } elseif ($mtime != 0) {
                $current_time -= $mtime;

                if ($current_time < 0) {
                    $current_day -= 1;
                    $current_time += 24;

                    if ($current_day < 1) {
                        $current_day += 7;
                    }
                }
            }
            $event_name = DB::table('event_types')->where('typeid', $temp->event)->value('event');

            if($current_day == $actualDay && $current_time == $actualHour){
                $timetable[$current_day . '-' . $current_time] = '<td class="hover-box-info" title="Current Event!" style="text-align:center; background: #c8ffc8;" id="' . $current_day . '-' . $current_time . '"><a href="/profile/' . UserHelper::getUsername($temp->userid, true) . '" class="web-page">' . $event_name . '</a></td>';
            }else{
                $timetable[$current_day . '-' . $current_time] = '<td class="hover-box-info" title="' . UserHelper::getHabbo($temp->userid, true) .'" style="text-align:center;" id="' . $current_day . '-' . $current_time . '"><a href="/profile/' . UserHelper::getUsername($temp->userid, true) . '">' . $event_name . '</a></td>';
            }
        }

        $returnHTML = view('pages.events')->with('timetable', $timetable)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getArticleSection($type, $pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 12;
        $skip = 0;

        $section = "";
        switch ($type) {
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

        $pagesx = DB::table('articles')->where('approved', 1)->where('type', $type)->count();

        $pages = ceil($pagesx/$take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        $paginator = array(
            'total' => $pages,
            'current' => $pagenr,
            'previous' => $pagenr-1 <= 0 ? 1 : $pagenr-1,
            'previous_exists' => $pagenr-1 < 1 ? false : true,
            'next' => $pagenr+1 >$pages ? $pages : $pagenr+1,
            'next_exists' => $pagenr+1 > $pages ? false : true,
            'gap_forward' => $pagenr+5 < $pages ? true : false,
            'gap_backward' => $pagenr-5 > 1 ? true : false
        );

        $articles = array();

        $temps = DB::table('articles')->where('approved', 1)->where('type', $type)->skip($skip)->take(12)->orderBy('dateline', 'DESC')->get();

        foreach ($temps as $temp) {
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

            $urltype = $temp->type;

            $title = str_replace(">", "&#62;", $temp->title);
            $title = strlen($title) >= 25 ? substr($title, 0, 25)."..." : $title;

            $completed = false;
            if (Auth::check()) {
                $userid = Auth::user()->userid;
                $completed = $temp->completed_userids;
                if ($completed=="") {
                    $completed = false;
                } else {
                    $completed_array = explode(',', $completed);
                    $completed = in_array($userid, $completed_array);
                }
            }

            $array = array(
                'title' => $title,
                'username' => $author,
                'completed' => $completed,
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'time' => ForumHelper::timeAgo($temp->dateline),
                'badge' => $badge,
                'badge_code' => $temp->badge_code,
                'availableID' => $temp->available,
                'type' => $temp->type,
                'difficulty' => $temp->difficulty,
                'paid' => $temp->paid,
                'articleid' => $temp->articleid
            );

            $articles[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/articles/' . $urltype . '/page/')->render();

        $returnHTML = view('pages.getArticleSection')
        ->with('articles', $articles)
        ->with('section', $section)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function loadMoreBadges($skip)
    {
        $returnHTML = view('pages.extras.extraBadges')->with('skip', $skip)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getSearchedBadges($badge)
    {
        $badge = $badge;

        $badges = array();
        $temps = DB::table('habbo_badges')->where('badge_name', 'LIKE', '%' . $badge . '%')->orWhere('badge_desc', 'LIKE', '%' . $badge . '%')->orderBy('dateline', 'DESC')->get();
        foreach ($temps as $temp) {
            $array = array(
                'name' => $temp->badge_name,
                'desc' => $temp->badge_desc,
                'new' => $temp->dateline > (time()-86400) ? true : false
            );
            $badges[] = $array;
        }

        $returnHTML = view('pages.badgessearch')
        ->with('badges', $badges)
        ->with('badge', $badge)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getScannedBadges()
    {
        $returnHTML = view('pages.badges')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function unsubscribeBadge(Request $request)
    {
        $badge = $request->input('badgeid');

        if (!Auth::check()) {
            return response()->json(array('success' => false));
        }

        $subscribers = DB::table('habbo_badges')->where('badge_name', $badge)
                                                ->value('subscribed_userids');


        $subscribers_array = explode(',', $subscribers);

        if (($user_index = array_search(Auth::user()->userid, $subscribers_array)) !== false) {
            unset($subscribers_array[$user_index]);
        } else {
            return response()->json(array('success' => false));
        }

        $subscribers = implode(',', $subscribers_array);

        DB::table('habbo_badges')->where('badge_name', $badge)
                                 ->update(['subscribed_userids' => $subscribers]);

        return response()->json(array('success' => true,));
    }

    public function subscribeBadge(Request $request)
    {
        $badge = $request->input('badgeid');

        if (!Auth::check()) {
            return response()->json(array('success' => false));
        }


        $checkGuide = DB::table('articles')->where('badge_code', $badge)->first();
        if (count($checkGuide)) {
            return response()->json(array('success' => false));
        }

        $subscribers = DB::table('habbo_badges')->where('badge_name', $badge)
                                                ->value('subscribed_userids');


        $subscribers_array = explode(',', $subscribers);
        if (in_array(Auth::user()->userid, $subscribers_array)) {
            return response()->json(array('success' => false));
        }

        if ($subscribers == "") {
            $subscribers = Auth::user()->userid;
        } else {
            array_push($subscribers_array, Auth::user()->userid);
            $subscribers = implode(',', $subscribers_array);
        }


        DB::table('habbo_badges')->where('badge_name', $badge)
                                 ->update(['subscribed_userids' => $subscribers]);

        return response()->json(array('success' => true));
    }

    public function getStaffGroup($usergroupid)
    {
        $staff = DB::table('staff_list')->where('usergroupid', $usergroupid)->first();

        if (!count($staff)) {
            return '';
        }

        $temps = DB::table('users')
            ->where('usergroups', 'LIKE', $staff->usergroupid)
            ->orWhere('usergroups', 'LIKE', $staff->usergroupid . ',%')
            ->orWhere('usergroups', 'LIKE', '%,' . $staff->usergroupid)
            ->orWhere('usergroups', 'LIKE', '%,' . $staff->usergroupid . ',%')
            ->orderBy('priority', 'DESC')
            ->orderBy('username')
            ->get();

        $users = array();
        $gp = DB::table('usergroups')->where('usergroupid', $staff->usergroupid)->first();
        $title = $gp ? $gp->title : '';

        foreach ($temps as $temp) {
            if (Cache::has('staff-list-user-' . $temp->userid)) {
                $users[] = Cache::get('staff-list-user-' . $temp->userid);
                continue;
            }

            $habbo = $temp->habbo_verified == 1 ? $temp->habbo : '';
            $ct = DB::table('countrys')->where('countryid', $temp->country)->first();
            $country = $ct ? $ct->name : '';

            $active = 'offline';

            if ($temp->lastactivity > (time() - 3600)) {
                $active = 'online';
            }

            $eventSlots = DB::table('timetable')->where('type', 1)->where('userid', $temp->userid)->where('activeWeek', 1)->count();
            $radioSlots = DB::table('timetable')->where('type', 0)->where('userid', $temp->userid)->where('activeWeek', 1)->count();

            $role = $staff->custom == 1 ? $temp->role : '';
            $user = [
                'clean_username' => $temp->username,
                'role' => $role,
                'avatar' => UserHelper::getAvatar($temp->userid),
                'username' => UserHelper::getUsername($temp->userid),
                'country' => $country,
                'habbo' => $habbo,
                'active' => $active,
                'eventSlots' => $eventSlots,
                'radioSlots' => $radioSlots
            ];
            Cache::add('staff-list-user-' . $temp->userid, $user, 5);
            $users[] = $user;
        }

        $userid = Auth::check() ? Auth::user()->userid : 0;
        return view('pages.extras.staffGroup')
            ->with('users', $users)
            ->with('color', $staff->color)
            ->with('title', $title)
            ->with('can_see_slots', (UserHelper::haveStaffPerm($userid, 2) || UserHelper::haveStaffPerm($userid, 1048576)))
            ->render();
    }

    public function getStaffList()
    {
        $groups = DB::table('staff_list')->orderBy('displayorder', 'ASC')->get();

        $grps = array();

        foreach ($groups as $group) {
            $grps[] = self::getStaffGroup($group->usergroupid);
        }

        $returnHTML = view('pages.stafflist')
            ->with('grps', $grps)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getCredits()
    {
        $returnHTML = view('pages.credits')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postContactUs(Request $request)
    {
        $habbo = $request->input('habbo');
        $reason = $request->input('reason');
        $why = $request->input('why');

        $check = DB::table('threads')->where('postuserid', Auth::user()->userid)->where('dateline', '>', time()-15)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'msg' => 'posting to fast'));
        }

        $threadid = DB::table('threads')->insertGetId([
            'title' => 'Contact Submission : ' . $reason,
            'forumid' => 439,
            'open' => 1,
            'visible' => 1,
            'replys' => 0,
            'postuserid' => Auth::user()->userid,
            'prefixid' => 0,
            'dateline' => time(),
            'firstpostid' => 0,
            'lastpost' => time(),
            'got_poll' => 0,
            'lastpostid' => 0,
            'sticky' => 0,
            'views' => 0,
            'force_read' => 0,
            'lastedited' => 0
        ]);

        $string = '[b]Forum Username:[/b]
' . Auth::user()->username . '

[b]Habbo Username:[/b]
' . $habbo . '

[b]Reason for Contacting Us:[/b]
' . $reason . '

[b]Why:[/b]
' . $why . ' ';

        $postid = DB::table('posts')->insertGetId([
            'threadid' => $threadid,
            'username' => Auth::user()->username,
            'userid' => Auth::user()->userid,
            'dateline' => time(),
            'lastedit' => 0,
            'content' => $string,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
            'visible' => 1
        ]);

        DB::table('forums')->where('forumid', '==', 439)->update([
            'lastpost' => time(),
            'lastposterid' => Auth::user()->userid,
            'lastpostid' => $postid,
            'lastthread' => time(),
            'lastthreadid' => $threadid
        ]);

        DB::table('threads')->where('threadid', $threadid)->update([
            'firstpostid' => $postid,
            'lastpostid' => $postid
        ]);

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'postcount' => DB::raw('postcount+1'),
            'threadcount' => DB::raw('threadcount+1')
        ]);

        return response()->json(array('success' => true, 'response' => true, 'threadid' => $threadid));
    }

    public function getContactUs()
    {
        $returnHTML = view('pages.contact')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postApplication(Request $request)
    {
        $habbo = $request->input('habbo');
        $discord = $request->input('discord');
        $job = $request->input('job');
        $country = $request->input('country');
        $why = $request->input('why');

        $check = DB::table('threads')->where('postuserid', Auth::user()->userid)->where('dateline', '>', time()-15)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'msg' => 'posting to fast'));
        }

        $threadid = DB::table('threads')->insertGetId([
            'title' => 'Application : ' . $job,
            'forumid' => 1016,
            'open' => 1,
            'visible' => 1,
            'replys' => 0,
            'postuserid' => Auth::user()->userid,
            'prefixid' => 0,
            'dateline' => time(),
            'firstpostid' => 0,
            'lastpost' => time(),
            'got_poll' => 0,
            'lastpostid' => 0,
            'sticky' => 0,
            'views' => 0,
            'force_read' => 0,
            'lastedited' => 0
        ]);

        $string = '[b]Forum Username:[/b]
' . Auth::user()->username . '

[b]Habbo Username:[/b]
' . $habbo . '

[b]Discord:[/b]
' . $discord . '

[b]Job Interested In:[/b]
' . $job . '

[b]Country:[/b]
' . $country . '

[b]Tell us why you\'d be good for this role:[/b]
' . $why;

        $postid = DB::table('posts')->insertGetId([
            'threadid' => $threadid,
            'username' => Auth::user()->username,
            'userid' => Auth::user()->userid,
            'dateline' => time(),
            'lastedit' => 0,
            'content' => $string,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
            'visible' => 1
        ]);

        DB::table('forums')->where('forumid', 1016)->update([
            'lastpost' => time(),
            'lastposterid' => Auth::user()->userid,
            'lastpostid' => $postid,
            'lastthread' => time(),
            'lastthreadid' => $threadid
        ]);

        DB::table('threads')->where('threadid', $threadid)->update([
            'firstpostid' => $postid,
            'lastpostid' => $postid
        ]);

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'postcount' => DB::raw('postcount+1'),
            'threadcount' => DB::raw('threadcount+1')
        ]);

        return response()->json(array('success' => true, 'response' => true, 'threadid' => $threadid));
    }

    public function getJobs()
    {
        $returnHTML = view('pages.jobs')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function aaronsPage()
    {
        $returnHTML = view('pages.pageforaaron')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getMarket()
    {
        $returnHTML = view('pages.market')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAboutUs()
    {
        $returnHTML = view('pages.about')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBadgeHub()
    {
        $returnHTML = view('pages.badgehub')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventsEU()
    {
        $returnHTML = view('pages.eventseu')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventsNA()
    {
        $returnHTML = view('pages.eventsna')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventsOC()
    {
        $returnHTML = view('pages.eventsoc')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioEU()
    {
        $returnHTML = view('pages.radioeu')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioNA()
    {
        $returnHTML = view('pages.radiona')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioOC()
    {
        $returnHTML = view('pages.radiooc')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBettingHub()
    {
        $bets = DB::table('betting_bets')->where('finished', 0)->orderBy('displayorder', 'ASC')->orderBy('bet', 'ASC')->get();
        $activeBets = array();

        foreach($bets as $temp) {
            $suspended = DB::table('betting_bets')->where('betid', $temp->betid)->where('suspended', 0)->count();
            $backers = DB::table('betting_user')->where('betid', $temp->betid)->count();
            $mybets = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('betid', $temp->betid)->count();

            $array = array(
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'odds' => $temp->odds,
                'suspended' => $suspended,
                'backers' => $backers,
                'mybets' => $mybets,
                'finished' => $temp->finished,
            );
            $activeBets[] = $array;
        }

        $topBets = DB::table('betting_user')->join('betting_bets', 'betting_bets.betid', '=', 'betting_user.betid')->where('betting_bets.finished', '0')->groupBy('betting_user.betid')->orderByRaw('COUNT(*) DESC')->limit(5)->get();
        $top5Bets = array();

        foreach($topBets as $bet) {
            $temp = DB::table('betting_bets')->where('betid', $bet->betid)->first();
            $backers = DB::table('betting_user')->where('betid', $temp->betid)->count();
            $mybets = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('betid', $temp->betid)->count();
            $suspended = DB::table('betting_bets')->where('betid', $temp->betid)->where('suspended', 0)->count();

            $array = array(
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'odds' => $temp->odds,
                'suspended' => $suspended,
                'backers' => $backers,
                'mybets' => $mybets,
                'finished' => $temp->finished,
            );
            $top5Bets[] = $array;
        }

        $totalWin = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 1)->count();
        $totalLoss = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 2)->count();

        $returnHTML = view('betting.index')
        ->with('bets', $activeBets)
        ->with('totalWin', $totalWin)
        ->with('totalLoss', $totalLoss)
        ->with('top5Bets', $top5Bets)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBettingHistory()
    {
        $bets = DB::table('betting_bets')->orderBy('betid', 'DESC')->get();
        $activeBets = array();
        foreach($bets as $temp) {
            $backers = DB::table('betting_user')->where('betid', $temp->betid)->count();
            $array = array(
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'odds' => $temp->odds,
                'backers' => $backers
            );
            $activeBets[] = $array;
        }

        $totalWin = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 1)->count();
        $totalLoss = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 2)->count();

        $returnHTML = view('betting.history')
        ->with('bets', $activeBets)
        ->with('totalWin', $totalWin)
        ->with('totalLoss', $totalLoss)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getOwnBets()
    {
        $bets = DB::table('betting_bets')->join('betting_user', 'betting_user.betid', '=', 'betting_bets.betid')->where('betting_user.userid', '=', Auth::user()->userid)->orderBy('betting_user.id', 'DESC')->get();
        $activeBets = array();
        $settledBets = array();

        foreach($bets as $temp) {
            $return = $temp->amount + $temp->amount * explode("/", $temp->odds)[0] / explode("/", $temp->odds)[1];
            $verdict  = '<b>Not Complete</b>';
            switch ($temp->result) {
                case '1':
                    $verdict = '<span style="color:#79b14e; font-weight:bold;">Win</span>';
                break;
                case '2':
                    $verdict = '<span style="color:#c66464; font-weight:bold;">Loss</span>';
                break;
            }
            if($temp->suspended == 1){
                $verdict = '<b>Suspended</b>';
            }
            $can_cancel = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('id', $temp->id)->where('dateline', '<', strtotime('-1 hour'))->count();
            $suspended = DB::table('betting_bets')->where('betid', $temp->betid)->where('suspended', 0)->count();

            $array = array(
                'id' => $temp->id,
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'odds' => $temp->odds,
                'amount' => $temp->amount,
                'verdict' => $verdict,
                'return' => $return,
                'can_cancel' => $can_cancel,
                'suspended' => $suspended
            );

            if($temp->result == 0){
                $activeBets[] = $array;
            }else{
                $settledBets[] = $array;
            }
        }

        $totalWin = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 1)->count();
        $totalLoss = DB::table('betting_user')->where('userid', Auth::user()->userid)->where('result', 2)->count();

        $returnHTML = view('betting.ownBets')
        ->with('bets', $activeBets)
        ->with('settledBets', $settledBets)
        ->with('totalWin', $totalWin)
        ->with('totalLoss', $totalLoss)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function placeBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $amount = $request->input('amount');
        $response = false;

        if($amount != ''){
            if(Auth::user()->credits >= $amount){
                $temp = DB::table('betting_bets')->where('betid', $betid)->first();

                DB::table('betting_user')->insert([
                    'betid' => $betid,
                    'userid' => Auth::user()->userid,
                    'odds' => $temp->odds,
                    'amount' => $amount,
                    'dateline' => time()
                ]);

                DB::table('users')->where('userid', Auth::user()->userid)->update([
                    'credits' => Auth::user()->credits - $amount
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function cancelBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $response = false;

        $temp = DB::table('betting_user')->where('id', $betid)->where('userid', Auth::user()->userid)->first();
        $check = DB::table('betting_user')->where('id', $betid)->where('userid', Auth::user()->userid)->count();

        if ($check > 0) {
            DB::table('betting_user')->where('id', $betid)->where('userid', Auth::user()->userid)->delete();

            $lose = $temp->amount * 0.2;

            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'credits' => Auth::user()->credits + $lose
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }
}
