<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\UserHelper;
use App\Helpers\ForumHelper;
use App\Helpers\StaffHelper;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;

class LongpullController extends BaseController
{

    public function likeDj()
    {
        if (!Auth::check()) {
            return response()->json(array('success' => true, 'response' => false, 'login' => true));
        }

        $check = DB::table('dj_likes')->where('userid', Auth::user()->userid)->orderBy('likeid', 'DESC')->first();

        if (count($check)) {
            if ($check->dateline+1800 > time()) {
                $timeout = ($check->dateline+1800) - time();

                $min = ceil($timeout/60);

                return response()->json(array('success' => true, 'response' => false, 'timeout' => $min));
            }
        }

        $radio_stats = DB::table('radio_stats')->first();

        if (count($radio_stats)) {
            DB::table('dj_likes')->insert([
                'djid' => $radio_stats->djid,
                'userid' => Auth::user()->userid,
                'dateline' => time()
            ]);

            $user = DB::table('users')->where('userid', $radio_stats->djid)->first();

            if (count($user)) {
                DB::table('users')->where('userid', $user->userid)->update([
                    'likes' => $user->likes+1
                ]);
            }

            DB::table('notifications')->insert([
                'postuserid' => Auth::user()->userid,
                'reciveuserid' => $radio_stats->djid,
                'content' => 18,
                'contentid' => 0,
                'dateline' => time(),
                'read_at' => 0
            ]);

            return response()->json(array('success' => true, 'response' => true, 'djname' => $radio_stats->dj));
        }
    }

    public function requestRadio(Request $request)
    {
        $name = $request->input('name');
        $message_user = $request->input('message');
        $stats = DB::table('radio_stats')->first();
        $djid = $stats->djid;
        $response = false;
        
        if (DB::table('requests')->where('dateline', '>', time()-300)->where('ip_address', 'LIKE', $request->ip())->count() > 0) {
            return response()->json(array('success' => true, 'response' => $response, 'message' => 'You can only request once every 5min'));
        }
        
        if (!empty($message_user)) {
            if (Auth::check()) {
                $name = Auth::user()->username;
                $real_account = Auth::user()->userid;
            } else {
                $name = $request->input('name');
                $real_account = 0;
            }

            DB::table('requests')->insert([
                'userid' => $djid,
                'message' => $message_user,
                'real_account' => $real_account,
                'username' => $name,
                'ip_address' => $request->ip(),
                'dateline' => time()
            ]);

            if (Auth::check()) {
                $postuserid = Auth::user()->userid;
            } else {
                $postuserid = 0;
            }

            if ($djid != '0') {
                DB::table('notifications')->insert([
                    'postuserid' => $postuserid,
                    'reciveuserid' => $djid,
                    'content' => 17,
                    'contentid' => 0,
                    'dateline' => time(),
                    'read_at' => 0
                ]);
            }

            $response = true;
        } else {
            $message = "Can't request with a empty message!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => 'Request sent!'));
    }

    public function muteRadio()
    {
        return response()->json(array('success' => true))->withCookie('radioMuted', 1, 604800);
    }

    public function unMuteRadio()
    {
        return response()->json(array('success' => true))->withCookie('radioMuted', 0, 604800);
    }

    public function clearNotifications()
    {
        DB::table('notifications')->where('reciveuserid', Auth::user()->userid)->update(['read_at' => time()]);
        return response()->json(array('success' => true, 'response' => true));
    }

    public function getRadioStats($radioVisible)
    {
        if ($radioVisible) {
            $time = time();
            $listeners = 0;
            $dj = "ThisHabbo";
            $djid = 0;
            $djhabbo = "";
            $djhabboname = "irDez";
            $song = "Statistics are loading, please wait!";
            $album_art = "https://i.imgur.com/xd2NHuO.png";
            $next_on_air = "Not Booked";
            $stats = null;
            $stats = DB::table('radio_stats')->where('dateline', '>', $time-60)->first();
            $djsays = DB::table('djsays')->orderBy('dateline', 'DESC')->first();
            $djlikes = 0;
            $djsaysname = UserHelper::getUsername($djsays->djid, true);
            $djsaysmessage = $djsays->message;

            if (count($stats)) {
                $listeners = $stats->listeners;
                $song = $stats->song;
                $dj = $stats->dj;
                $djid = $stats->djid;
                $album_art = $stats->album_art;
                $next_on_air = $stats->next_on_air;
                if(UserHelper::getHabbo($stats->djid, true) == ''){
                    $djhabboname = UserHelper::getHabbo(96, true);
                }else{
                    $djhabboname = UserHelper::getHabbo($stats->djid, true);
                }
                $djhabbo = "<img src='https://www.habbo.com/habbo-imaging/avatarimage?user=". $djhabboname ."&amp;direction=2&amp;head_direction=3&amp;action=0&amp;gesture=sml&amp;size=m'>";
                $djlikes = DB::table('dj_likes')->where('djid',$djid)->count();
            } else {
                $dnas_data = StaffHelper::getRadioStats();

                if ($dnas_data != null) {
                    $genre = $dnas_data['SERVERGENRE'];
                    $listeners = $dnas_data['UNIQUELISTENERS'];
                    $dj = $dnas_data['SERVERTITLE'];
                    $song = $dnas_data['SONGTITLE'];
                    $album_art = "https://i.imgur.com/xd2NHuO.png";
                    $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($genre)])->first();

                    if (count($user)) {
                        $djid = $user->userid;
                    }
                    $song_temp = explode('-', $song);
                    if (is_array($song_temp)) {
                        $song_temp = trim($song_temp[0]);
                    }

                    $album_art = StaffHelper::getRadioAlbum($song_temp);

                    $day = date('N');
                    $time = date('G');

                    $time += 1;

                    if ($time > 23) {
                        $time -= 24;
                        $day += 1;
                    }

                    if ($day > 7) {
                        $day -= 7;
                    }

                    $check = DB::table('timetable')->where('day', $day)->where('time', $time)->where('type', 0)->first();

                    if (count($check)) {
                        $nxt = DB::table('users')->where('userid', $check->userid)->first();
                        if (count($nxt)) {
                            $next_on_air = '<span style="font-weight: bold;"><a href="/profile/'.UserHelper::getUsername($check->userid, true).'" style="color: #ffffff !important;" class="web-page">' .UserHelper::getUsername($check->userid, true) . '</a></span>';
                        }
                    }

                    $check = DB::table('radio_stats')->count();

                    if ($check == 0) {
                        DB::table('radio_stats')->insert([
                            'dj' => $dj,
                            'song' => $song,
                            'album_art' => $album_art,
                            'listeners' => $listeners,
                            'djid' => $djid,
                            'next_on_air' => $next_on_air,
                            'dateline' => time()
                        ]);
                    } else {
                        DB::table('radio_stats')->update([
                            'dj' => $dj,
                            'song' => $song,
                            'album_art' => $album_art,
                            'listeners' => $listeners,
                            'djid' => $djid,
                            'next_on_air' => $next_on_air,
                            'dateline' => time()
                        ]);
                    }
                }
            }
        }


        $logged_out = false;

        if (!Auth::check()) {
            $logged_out = true;
        }

        $latest_article = null;

        if (isset($_GET['latest_questid'])) {
            $temp = DB::table('articles')->where('approved', 1)->where('type', 0)->orderBy('dateline', 'DESC')->first();
            if (count($temp)) {
                $latest_article = new \stdClass();
                $latest_article->username = UserHelper::getUsername($temp->userid);
                $latest_article->clean_username = UserHelper::getUsername($temp->userid, true);
                $latest_article->dateline = ForumHelper::getTimeInDate($temp->dateline, true, false, 'd/m/Y');
                $latest_article->title = $temp->title;
                $latest_article->available = $temp->available;
                $latest_article->badge_code =  explode(',', $temp->badge_code)[0];
                $latest_article->image = asset('_assets/img/thumbnails/'.$temp->articleid.'.gif');
                $latest_article->articleid = $temp->articleid;
            }
        }

        if ($radioVisible) {
            return response()->json(array(
                'radio_details' => true,
                'success' => true,
                'response' => true,
                'dj' => $dj,
                'song' => $song,
                'album_art' => $album_art,
                'djid' => $djid,
                'djhabbo' => $djhabbo,
                'djsaysname' => $djsaysname,
                'djsaysmessage' => $djsaysmessage,
                'djlikes' => $djlikes,
                'next_on_air' => $next_on_air,
                'listeners' => $listeners,
                'logged_out' => $logged_out,
                'latest_article' => $latest_article
            ));
        } else {
            return response()->json(array(
                'radio_details' => false,
                'success' => true,
                'response' => true,
                'logged_out' => $logged_out,
                'latest_article' => $latest_article
            ));
        }
    }

    public function loadNotification($lastId)
    {
        $notifications = array();
        $userid = Auth::user()->userid;
        //$amt = DB::table('notifications')->where('reciveuserid', $userid)->where('read_at', 0)->where('notificationid', '>', $lastId)->count();

        $notis = DB::table('notifications')->where('reciveuserid', $userid)->where('read_at', 0)->where('notificationid', '>', $lastId)->get();

        $new_lastId = $lastId;

        $amt = 0;
        foreach ($notis as $noti) {
            $run = false;
            $message = "";
            $avatar = UserHelper::getAvatar($noti->postuserid);

            switch ($noti->content) {
                case 1:
                    //Mentioned
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;
                            $postid = $noti->contentid;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $postid;
                            $message = UserHelper::getUsername($noti->postuserid) . ' mentioned you in a post in the thread ' . $thread->title;
                            $run = true;

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32) and $thread->postuserid != $userid) {
                                $run = false;
                            }
                        }
                    }
                    break;
                case 2:
                    //Quote
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;
                            $postid = $noti->contentid;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $postid;
                            $message = UserHelper::getUsername($noti->postuserid) . ' quoted your post in the thread ' . $thread->title;
                            $run = true;

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32) and $thread->postuserid != $userid) {
                                $run = false;
                            }
                        }
                    }
                    break;
                case 3:
                    //Like
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;
                            $postid = $noti->contentid;
                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $postid;
                            $message = UserHelper::getUsername($noti->postuserid) . ' liked your post in the thread ' . $thread->title;
                            $run = true;

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32) and $thread->postuserid != $userid) {
                                $run = false;
                            }
                        }
                    }
                    break;
                case 4:
                    //Someone posted in thread they subscribe in
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;
                            $postid = $noti->contentid;
                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $postid;
                            $message = UserHelper::getUsername($noti->postuserid) . ' posted in the thread ' . $thread->title;
                            $run = true;

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32) and $thread->postuserid != $userid) {
                                $run = false;
                            }
                        }
                    }
                    break;
                case 5:
                    // private message
                    $user = DB::table('users')->where('userid', $noti->postuserid)->first();
                    if (count($user)) {
                        $link = '/usercp/pm?userid=' . $user->userid;
                        $message =  UserHelper::getUsername($user->userid) . ' sent a private message!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($user->userid);
                    }
                    break;
                case 6:
                    // visitor message
                    $vm = DB::table('visitor_messages')->where('vmid', $noti->contentid)->first();
                    if (count($vm)) {
                        $user1 = DB::table('users')->where('userid', $vm->postuserid)->first();
                        $user2 = DB::table('users')->where('userid', $vm->reciveuserid)->first();

                        if (count($user1) and count($user2)) {
                            $link = '/conversation/' . $user1->username . '/' . $user2->username . '/page/1';
                            $message = UserHelper::getUsername($noti->postuserid) . ' sent you a visitor message!';
                            $run = true;
                        }
                    }
                    break;
                case 7:
                    // badge award
                    $badge = DB::table('badges')->where('badgeid', $noti->contentid)->first();
                    if (count($badge)) {
                        $link = '/usercp/settings/profile';
                        $message =  'You have received a new badge!';
                        $run = true;

                        $avatar = asset('_assets/img/website/badges/' . $badge->badgeid . '.gif');
                    }
                    break;
                case 8:
                    // new quest guide published
                    $article = DB::table('articles')->where('articleid', $noti->contentid)->first();
                    if (count($article)) {
                        $link = '/article/' . $article->articleid . '-' . $article->title;
                        $message = 'A new quest guide has been published!';
                        $run = true;

                        $badge = 'https://habboo-a.akamaihd.net/c_images/album1584/' . $article->badge_code . '.gif';

                        if (@getimagesize($badge)) {
                            $avatar = $badge;
                        } else {
                            $avatar = asset('_assets/img/website/badge_error.gif');
                        }
                    }
                    break;
                case 9:
                    // someone followed you
                    $user = DB::table('users')->where('userid', $noti->contentid)->first();
                    if (count($user)) {
                        $link = '/profile/' . $user->username . '/page/1';
                        $message =  UserHelper::getUsername($user->userid) . ' followed you! Check them out!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($user->userid);
                    }
                    break;
                case 10:
                    $user = DB::table('users')->where('userid', $noti->contentid)->first();
                    if (count($user)) {
                        $link = '/profile/' . $user->username;
                        $message =  UserHelper::getUsername($user->userid) . ' referred you! Check them out!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($user->userid);
                    }
                    break;
                case 11:
                    $comment = DB::table('article_comments')->where('commentid', $noti->contentid)->first();
                    if (count($comment)) {
                        $pagex = DB::table('article_comments')->where('articleid', $comment->articleid)->where('commentid', '<', $comment->commentid)->count();
                        $page = ceil($pagex/10);

                        if ($page < 1) {
                            $page = 1;
                        }
                        $link = '/article/' . $comment->articleid . '/page/' . $page;
                        $message =  UserHelper::getUsername($comment->userid) . ' mentioned you in an article!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($comment->userid);
                    }
                    break;
                case 12:
                    $comment = DB::table('creation_comments')->where('commentid', $noti->contentid)->first();
                    if (count($comment)) {
                        $pagex = DB::table('creation_comments')->where('creationid', $comment->creationid)->where('commentid', '<', $comment->commentid)->count();
                        $page = ceil($pagex/10);

                        if ($page < 1) {
                            $page = 1;
                        }
                        $link = '/creation/' . $comment->creationid . '/page/' . $page;
                        $message =  UserHelper::getUsername($comment->userid) . ' mentioned you on an creation!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($comment->userid);
                    }
                    break;
                case 13:
                    $user = DB::table('users')->where('userid', $noti->postuserid)->first();
                    if (count($user)) {
                        $link = '/profile/' . $user->username . '/page/1';
                        $message =  UserHelper::getUsername($user->userid) . ' gave you ' . number_format($noti->contentid) . ' points for the shop!';
                        $run = true;

                        $avatar = UserHelper::getAvatar($user->userid);
                    }
                    break;
                case 14:
                    $user1 = DB::table('users')->where('userid', $noti->postuserid)->first();
                    $user2 = DB::table('users')->where('userid', $noti->reciveuserid)->first();
                    $quest = DB::table('articles')->where('articleid', $noti->contentid)->first();
                    if (count($quest)) {
                        $link = '/article/' . $quest->articleid;
                        $message =  $user1->username . ' replied to your comment!';
                        $run = true;
                    }
                    break;
                case 15:
                    $link = '/usercp/shop/box/open/'. $noti->contentid;
                    $message =  'You won a mystery box from a daily quest! Click to open!';
                    $run = true;
                    break;
                case 16:
                    $comment = DB::table('article_comments')->where('commentid', $noti->contentid)->first();
                    if (count($comment)) {
                        $article = DB::table('articles')->where('articleid', $comment->articleid)->where('approved', 1)->first();
                        if (count($article)) {
                            $amountComments = DB::table('article_comments')->where('commentid', '<', $comment->commentid)->where('articleid', $article->articleid)->count();

                            $page = ceil($amountComments/10);

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '/article/' . $article->articleid;
                            $message = UserHelper::getUsername($noti->postuserid) . 'liked your comment in the article ' . $article->title;
                            $run = true;
                        }
                    }
                    break;
                case 17:
                    // request alert
                    $request = DB::table('requests')->where('userid', $noti->reciveuserid)->first();
                    if (count($request)) {
                        $link = '/staff/radio/request/page/1';
                        $message =  'You have received a new request or shoutout!';
                        $run = true;

                        $avatar = asset('_assets/img/website/badges/67.gif');
                    }
                    break;
                case 18:
                    $liker = UserHelper::getUsername($noti->postuserid);
                    $likerClean = UserHelper::getUsername($noti->postuserid, true);
                    $link = "/profile/" . $likerClean;
                    $message = $liker . " liked you! Keep it up!";
                    $run = true;
                break;
                case 19:
                    // Someone Invited you to a clan

                    $inviter = UserHelper::getUsername($noti->postuserid);
                    $clan = DB::table('clans')->where('groupid', $noti->contentid)->first();

                    $link = '/clans/' . $clan->groupname;

                    $message = $inviter . ' invited you to join team ' . $clan->groupname . '!';
                    $run = true;
                break;
                case 20:
                    // You earnt a key

                    $link = '/usercp';
                    $message =  'You have earnt a key!';
                    $run = true;

                break;

            }

            if ($run) {
                $view = view('layout.notif.notif-html')
                    ->with('time', ForumHelper::timeAgo($noti->dateline))
                    ->with('message', $message)
                    ->with('avatar', $avatar)
                    ->with('link', $link)
                    ->with('notificationid', $noti->notificationid)
                    ->render();

                if ($noti->notificationid > $new_lastId) {
                    $new_lastId = $noti->notificationid;
                }

                $notifications[] = $view;
                $amt += 1;
            }
        }

        return response()->json(array('success' => true, 'response' => true, 'amount' => count($notifications), 'amt' => $amt, 'notifications' => $notifications, 'new_lastId' => $new_lastId));
    }
}
