<?php namespace App\Helpers;

use Cache;
use App\User;
use DB;
use Auth;
use App\Helpers\UserHelper;
use Illuminate\Support\Facades\Log;

class ForumHelper
{
    public static function usersWatchingThread($threadid) {
        $time = time() - 300;
        $temp = DB::table('thread_read')->where('threadid', $threadid)->where('read', '>', $time)->get();
        $users = array();
        foreach ($temp as $user) {
            $user = UserHelper::getUser($user->userid);
            $username = UserHelper::getUsername($user->userid, true);
            $avatar = UserHelper::getAvatar($user->userid, true);
            $usernamecolour = UserHelper::getUsername($user->userid);
            $temp = DB::table('thread_read')->where('threadid', $threadid)->where('userid', $user->userid)->where('read', '>', $time)->first();
            $timeago = self::timeAgo($temp->read);

            $array = array(
                'username' => $username,
                'usernamecolour' => $usernamecolour,
                'userid' => $user->userid,
                'timeago' => $timeago,
            );
            $users[] = $array;
        }

        return $users;
    }

    public static function welcomeBot($threadid)
    {
        $content = DB::table('welcomebot')->value('text');

        $user = DB::table('users')->where('userid', '1')->first();

        $postid = DB::table('posts')->insertGetId([
            'threadid' => $threadid,
            'username' => $user->username,
            'userid' => $user->userid,
            'dateline' => time(),
            'content' => $content,
            'ipaddress' => '0.0.0.0',
            'visible' => '1'
        ]);

        DB::table('forums')->where('forumid', '56')->update([
            'lastpost' => time(),
            'lastpostid' => $postid,
            'lastposterid' => $user->userid
        ]);
    }

    public static function replaceEmojis($text)
    {
        $emojis = DB::table('emoji')->get();

        foreach ($emojis as $emoji) {
            $text = str_replace($emoji->find, $emoji->replace, $text);
        }

        return $text;
    }

    public static function fixContent($content)
    {
        $content = str_replace(">", "&#62;", $content);
        $content = str_replace("\"", "&quot;", $content);
        $content = str_replace("<", "&#60;", $content);
        $content = str_replace("❝", "&quot;", $content);
        $content = str_replace("❞", "&quot;", $content);
        $content = str_replace("‘", "&#39;", $content);
        $content = str_replace("’", "&#39;", $content);
        return str_replace("`", "&#39;", $content);
    }

    public static function isForumCollapsed($forumid, $userid)
    {
        $collapsed_forums = DB::table('users')->where('userid', $userid)->value('collapsed_forums');
        if(in_array($forumid, explode(',', $collapsed_forums))){
            return true;
        }else{
            return false;
        }
    }

    public static function getForums($userid)
    {
        $forums = array();
        $main_forums = DB::table('forums')->where('parentid', -1)->orderBy('displayorder', 'ASC')->get();

        foreach ($main_forums as $main_forum) {
            if (UserHelper::haveForumPerm($userid, $main_forum->forumid, 1)) {
                $mf = array(
                    'title' => $main_forum->title,
                    'forumid' => $main_forum->forumid,
                    'thumbnail' => asset('_assets/img/forumthumbnails/'.$main_forum->forumid.'.gif'),
                    'sub_forums' => 0,
                    'collapsed' => self::isForumCollapsed($main_forum->forumid, $userid)
                );
                $mf['sub_forums'] = self::getSubForums($userid, $main_forum->forumid);
                $forums[] = $mf;
            }
        }
        return $forums;
    }

    public static function getLatestBages($take = 12, $skip = false)
    {
        $badges = array();

        if ($skip) {
            $temps = DB::table('habbo_badges')->skip($skip)->take($take)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('habbo_badges')->take($take)->orderBy('dateline', 'DESC')->get();
        }

        foreach ($temps as $temp) {
            $array = array(
                'name' => $temp->badge_name,
                'desc' => $temp->badge_desc,
                'new' => $temp->dateline > (time()-86400) ? true : false
            );

            $badges[] = $array;
        }

        return $badges;
    }

    // public static function getlatestNotices() {
    // 	$notices = array();
    // 	$temps = DB::table('site_notices')->where('enabled', '1')->orWhere('enddate', '>', time())->orWhere('enddate', '0')->orderBy('noticeid', 'DESC')->get();

    // 	foreach($temps as $temp) {
    // 		$array = array(
    // 			'type' => $temp->badge_name,
    // 			'notice' => $temp->badge_desc,
    // 		);

    // 		$notices[] = $array;
    // 	}

    // 	return $notices;
    // }

    public static function getLatestFurnis($take = 12, $skip = false)
    {
        $furnis = array();

        if ($skip) {
            $temps = DB::table('furnis')->skip($skip)->take($take)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('furnis')->take($take)->orderBy('dateline', 'DESC')->get();
        }

        foreach ($temps as $temp) {
            $array = array(
                'name' => $temp->name,
                'desc' => $temp->description,
                'revision' => $temp->revision,
                'classname' => $temp->classname,
                'new' => $temp->dateline > (time()-86400) ? true : false
            );

            $furnis[] = $array;
        }

        return $furnis;
    }

    public static function updateForumRead($forumid)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            $exist = DB::table('forum_read')->where('forumid', $forumid)->where('userid', Auth::user()->userid)->count();
            if ($exist > 0) {
                DB::table('forum_read')->where('forumid', $forumid)->where('userid', Auth::user()->userid)->update(['dateline' => time()]);
            } else {
                DB::table('forum_read')->where('forumid', $forumid)->where('userid', Auth::user()->userid)->insert([
                    'userid' => Auth::user()->userid,
                    'forumid' => $forumid,
                    'dateline' => time()
                ]);
            }
            if ($forum->parentid > -1) {
                self::updateForumRead($forum->parentid);
            }
        }
    }

    private static function txt2link($text)
    {
        //return preg_replace('/(?<!src=[\"\'])((http(s)?:\/\/(www\.)?|(www\.))[\/a-zA-Z0-9%\?\.\-]*)(?=$|<|\s)/','<a href="$1" target="_blank">$1</a>', $text);
        //return preg_replace('!((((f|ht)tp(s)?://)|www.)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
    }

    public static function getLatestActivity()
    {
        $temps1 = DB::table('article_comments')->orderBy('commentid', 'DESC')->take(5)->get();
        $temps2 = DB::table('posts')->where('visible', 1)->orderBy('postid', 'DESC')->take(5)->get();
        $temps3 = DB::table('visitor_messages')->orderBy('vmid', 'DESC')->take(5)->get();
        $temps4 = DB::table('creations')->orderBy('creationid', 'DESC')->take(5)->get();
        $temps5 = DB::table('creation_comments')->orderBy('commentid', 'DESC')->take(5)->get();

        $latestActivity = array();
        if (Auth::check()) {
            $userid = Auth::user()->userid;
        } else {
            $userid = 0;
        }

        foreach ($temps1 as $temp) {
            $article = DB::table('articles')->where('articleid', $temp->articleid)->where('approved', 1)->first();

            if (count($article)) {
                $array = array(
                    'link' => '/article/' . $temp->articleid,
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => UserHelper::getUsername($temp->userid, true),
                    'time' => self::timeAgo($temp->dateline),
                    'dateline' => $temp->dateline,
                    'text' => '<a href="/article/' . $article->articleid . '-' . str_replace(" ", "-", $article->title) . '" class="web-page">' . $article->title . '</a>',
                    'avatar' => UserHelper::getAvatar($temp->userid),
                    'action' => 'article comment'
                );

                $latestActivity[] = $array;
            }
        }

        foreach ($temps5 as $temp) {
            $creation = DB::table('creations')->where('creationid', $temp->creationid)->where('approved', 1)->first();

            if (count($creation)) {
                $name = $creation->name;
                $array = array(
                    'link' => '/creation/' . $temp->creationid,
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => UserHelper::getUsername($temp->userid, true),
                    'time' => self::timeAgo($temp->dateline),
                    'dateline' => $temp->dateline,
                    'text' => '<a href="/creation/' .$temp->creationid . '-' . $name . '" class="web-page">' . str_replace('<', '&#62;', $name) . '</a>',
                    'avatar' => UserHelper::getAvatar($temp->userid),
                    'action' => 'creation comment'
                );

                $latestActivity[] = $array;
            }
        }

        foreach ($temps2 as $temp) {
            $thread = DB::table('threads')->where('threadid', $temp->threadid)->where('visible', 1)->first();

            if (count($thread)) {
                if (UserHelper::haveForumPerm($userid, $thread->forumid, 1)) {
                    $run = true;

                    if (!UserHelper::haveForumPerm($userid, $thread->forumid, 32) and $thread->postuserid != $userid) {
                        $run = false;
                    }

                    if ($run) {
                        $page = 1;

                        if (Auth::check() and UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 2)) {
                            $count = DB::table('posts')->where('threadid', $thread->threadid)->where('postid', '<', $temp->postid)->count();
                        } else {
                            $count = DB::table('posts')->where('visible', 1)->where('threadid', $thread->threadid)->where('postid', '<', $temp->postid)->count();
                        }

                        $page = ceil($count / 10);
                        if ($page == 0) {
                            $page = 1;
                        }

                        $title = self::fixContent($thread->title);

                        $array = array(
                            'username' => UserHelper::getUsername($temp->userid),
                            'clean_username' => UserHelper::getUsername($temp->userid, true),
                            'time' => self::timeAgo($temp->dateline),
                            'dateline' => $temp->dateline,
                            'text' => ' <a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '" class="web-page">' . $title . '</a>',
                            'avatar' => UserHelper::getAvatar($temp->userid),
                            'action' => 'post'
                        );

                        $latestActivity[] = $array;
                    }
                }
            }
        }

        foreach ($temps3 as $temp) {
            $user = UserHelper::getUser($temp->reciveuserid);

            if (count($user)) {
                $array = array(
                    'link' => '/profile/' . $user->username . '/page/1',
                    'username' => UserHelper::getUsername($temp->postuserid),
                    'clean_username' => UserHelper::getUsername($temp->postuserid, true),
                    'time' => self::timeAgo($temp->dateline),
                    'dateline' => $temp->dateline,
                    'text' => '<a href="/profile/' . $user->username . '/page/1" class="web-page">' . UserHelper::getUsername($user->userid) . '\'s</a> profile',
                    'avatar' => UserHelper::getAvatar($temp->postuserid),
                    'action' => 'visitor message'
                );

                $latestActivity[] = $array;
            }
        }

        foreach ($temps4 as $temp) {
            $user = UserHelper::getUser($temp->userid);

            if (count($user)) {
                $name = $temp->name;
                $array = array(
                    'link' => '/creation/' . $temp->creationid,
                    'username' => UserHelper::getUsername($temp->userid),
                    'clean_username' => UserHelper::getUsername($temp->userid, true),
                    'time' => self::timeAgo($temp->dateline),
                    'dateline' => $temp->dateline,
                    'text' => '<a href="/creation/' . $temp->creationid . '" class="web-page">' . str_replace('<', '&#62;', $name) . '</a>',
                    'avatar' => UserHelper::getAvatar($temp->userid),
                    'action' => 'creation'
                );

                $latestActivity[] = $array;
            }
        }

        usort($latestActivity, array('self', 'descDateline'));

        return $latestActivity;
    }

    public static function descDateline($a, $b)
    {
        if ($a['dateline'] == $b['dateline']) {
            return 0;
        }
        if ($a['dateline'] > $b['dateline']) {
            return -1;
        }
        return 1;
    }

    public static function fixQuotePosts($content)
    {
        if (preg_match_all('/\[quotepost=(.+?);(.+?)\](.*)\[\/quotepost\]/s', $content, $match)) {
            for ($i = 0; $i < count($match[0]); $i++) {
                $postid = $match[1][$i];
                $username = $match[2][$i];
                $middle_content = $match[3][$i];

                $post_quote = DB::table('posts')->where('postid', $postid)->first();
                if (count($post_quote)) {
                    $quoted_content = str_replace(">", "&#62;", $post_quote->content);
                    $quoted_content = str_replace("<", "&#60;", $quoted_content);

                    $quoted_content = ForumHelper::bbcodeParser($quoted_content);

                    $stg = '<div class="quotePost"><small>Originally Posted by <b>$2</b></small><br /><br />$3</div>';
                    $content = preg_replace('/\[quotepost=(.+?);(.+?)\](.*)\[\/quotepost\]/s', $stg, $content);
                } else {
                    $quoted_content = str_replace(">", "&#62;", $content);
                    $quoted_content = str_replace("<", "&#60;", $quoted_content);

                    $quoted_content = ForumHelper::bbcodeParser($quoted_content);

                    $stg = '<div class="quotePost"><small>Originally Posted by <b>$2</b></small><br /><br />$3</div>';
                    $content = preg_replace('/\[quotepost=(.+?);(.+?)\](.*)\[\/quotepost\]/s', $stg, $content);
                }
            }
        }

        return $content;
    }

    public static function bbcodeParser($content, $bbcodes = "")
    {
        if ($bbcodes === "") {
            $bbcodes = DB::table('bbcodes')->get();
        }
        $find = array();
        $replace = array();

        foreach ($bbcodes as $bbcode) {
            $match = @preg_match($bbcode->pattern, "gg");
            if ($match !== false) {
                $find[] = $bbcode->pattern;
                $replace[] = $bbcode->replace;
            }
        }

        $content = preg_replace($find, $replace, $content);
        $content = self::fixQuotePosts($content);
        if (preg_match_all('/\[mention\](.+?)\[\/mention\]/s', $content, $match)) {
            foreach ($match[1] as $mh) {
                $content = str_replace("[mention]" . $mh . "[/mention]", '<a href="/profile/' . $mh . '/page/1" class="web-page">@' . $mh . '</a>', $content);
            }
        }
        return $content;
    }

    public static function getBreadCrum($forumid)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();
        $response = "";

        if (count($forum)) {
            if ($forum->parentid > 0) {
                $response = self::getBreadCrum($forum->parentid) . '<span><a href="/forum/category/' . $forum->forumid . '/page/1" class="web-page">' . $forum->title . '</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> ' . $response;
            } else {
                $response = '<span><a href="/forum/category/' . $forum->forumid . '/page/1" class="web-page">' . $forum->title . '</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> ' . $response;
            }
        }
        return $response;
    }

    public static function getOnlineUsers()
    {
        $last_online = array();
        $time = time() - 3600;

        $temps = DB::table('users')->where('lastactivity', '>', $time)->orderBy('lastactivity', 'DESC')->take(15)->get();

        foreach ($temps as $temp) {
            $array = array(
                'username' => $temp->username,
                'avatar' => UserHelper::getAvatar($temp->userid)
            );

            $last_online[] = $array;
        }

        return $last_online;
    }

    public static function getPostbitBadges($userid)
    {
        $user = UserHelper::getUser($userid);

        if ($user && $user->postbit_badges != '') {
            return DB::table('badges')->whereIn('badgeid', explode(',', $user->postbit_badges))->select('name', 'badgeid', 'description')->get();
        }

        return [];
    }

    public static function getOnlineAdmins()
    {
        $last_online = array();
        $time = time() - 3600;

        $temps = DB::table('users')->where('lastactivity', '>', $time)->orderBy('lastactivity', 'DESC')->take(15)->get();

        foreach ($temps as $temp) {
            if(UserHelper::haveAdminPerm($temp->userid, 16) OR UserHelper::haveAdminPerm($temp->userid, 8388608) OR UserHelper::haveAdminPerm($temp->userid, 16777216) OR UserHelper::haveAdminPerm($temp->userid, 33554432) OR UserHelper::haveAdminPerm($temp->userid, 67108864) OR UserHelper::haveAdminPerm($temp->userid, 131072) OR UserHelper::haveAdminPerm($temp->userid, 134217728) OR UserHelper::haveAdminPerm($temp->userid, 268435456) OR UserHelper::haveAdminPerm($temp->userid, 1) OR UserHelper::haveAdminPerm($temp->userid, 536870912)){
                $array = array(
                    'username' => $temp->username,
                    'avatar' => UserHelper::getAvatar($temp->userid)
                );

                $last_online_admin[] = $array;
            }
        }

        return $last_online_admin;
    }

    public static function getNewestUsers()
    {
        $temps = DB::table('users')->orderBy('userid', 'desc')->limit(10)->get();
        foreach ($temps as $temp){
            $array = array(
                'userid' => $temp->userid,
                'username' => $temp->username,
                'habbo' => $temp->habbo
            );

            $ten_newest_users[] = $array;
        }

        return $ten_newest_users;
    }

    public static function getTimeInDate($timeY, $forward = false, $long = true, $format = false)
    {
        $timestamp = self::findMidnight();
        $response = "";

        $time = self::returnTimeAfterTimezone($timeY);
        if (!$forward) {
            if ($timeY > $timestamp) {
                //It is today
                $response = 'Today, ' . date('g:i A', $time);
            } elseif ($timeY > $timestamp-86400) {
                $response = 'Yesterday, ' . date('g:i A', $time);
            } else {
                if ($long) {
                    $response = date('d-M-y g:i A', $time);
                } else {
                    $response = date(($format ? $format : 'M Y'), $time);
                }
            }
        } else {
            $response = date(($format ? $format : 'd-M-y g:i A'), $time);
        }

        return $response;
    }

    public static function findMidnight()
    {
        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();
        } else {
            $timezone = DB::table('timezones')->where('timezoneid', 13)->first();
        }
        $hour = date('H', time());
        if (count($timezone)) {
            if ($timezone->negative == 1 && $hour < $timezone->value) {
                return self::returnTimeAfterTimezone(strtotime('yesterday'), true);
            } elseif (($timezone->negative == 0) && ($hour + $timezone->value) > 24) {
                return self::returnTimeAfterTimezone(strtotime('tomorrow'), true);
            } else {
                return self::returnTimeAfterTimezone(strtotime('today'), true);
            }
        }
    }

    public static function getTimeInDateLong($timeY, $forward = false, $long = true, $format = false)
    {
        $timestamp = self::findMidnight();
        $response = "";

        $time = self::returnTimeAfterTimezone($timeY);

        if (!$forward) {
            if ($timeY > $timestamp) {
                //It is today
                $response = 'Today, ' . date('g:i A', $time);
            } elseif ($timeY > $timestamp-86400) {
                $response = 'Yesterday, ' . date('g:i A', $time);
            } else {
                if ($long) {
                    $response = date('jS F Y - g:i A', $time);
                } else {
                    $response = date(($format ? $format : 'M Y'), $time);
                }
            }
        } else {
            $response = date(($format ? $format : 'jS F Y - g:i A'), $time);
        }

        return $response;
    }

    public static function getLikers($postid)
    {
        $likers = DB::table('notifications')->where('content', 3)->where('contentid', $postid)->orderBy('notificationid', 'ASC')->get();

        $likers_strike = "";
        $others = 0;
        $first = 1;

        if (count($likers)) {
            foreach ($likers as $liker) {
                if ($others < 4) {
                    if ($first == 1) {
                        $likers_strike = '<a href="/profile/' . UserHelper::getUsername($liker->postuserid, true) . '">' . UserHelper::getUsername($liker->postuserid) . '</a>';
                        $first = 0;
                    } else {
                        $likers_strike = $likers_strike . ', <a href="/profile/' . UserHelper::getUsername($liker->postuserid, true) . '/">' . UserHelper::getUsername($liker->postuserid) . '</a>';
                    }
                }
                $others++;
            }
        }

        if ($others > 4) {
            $others -= 4;
            $other_likers = "";

            $otherlikers = DB::table('notifications')->where('content', 3)->where('contentid', $postid)->orderBy('notificationid', 'ASC')->limit(100000)->skip('4')->get();

            foreach ($otherlikers as $other){
                $other_likers = $other_likers . UserHelper::getUsername($other->postuserid, true) . "<br />";
            }

            $likers_strike = $likers_strike . ' and <a style="color: #8a8787;" class="hover-box-info" title="'. $other_likers .'" aria-hidden="true">'. $others .' more like this post! </a>';

        } else {
            $likers_strike = $likers_strike . ' likes this post!';
        }

        return array('have_likers' => $first, 'likers_strike' => $likers_strike);
    }

    /* GET CHILD FORUM FOR FORUMLIST */
    public static function getChilds($forums)
    {
        $response = "";

        $childForums = collect($forums)->sortBy('displayorder')->toArray();

        foreach ($childForums as $forum) {
            $response = $response . "<tr>
              <td style='font-weight: bold;'>" . $forum['title'] . "</td>
              <td><center>" . $forum['displayorder'] . "</center></td>
              <td>" . self::forumActions($forum['forumid']) . "</td>
            </tr>";

            if (count($forum['childs'])) {
                $response = $response . self::childChilds($forum['childs']);
            }
        }


        return $response;
    }

    public static function getChildsDefault($forums)
    {
        $response = "";

        foreach ($forums as $forum) {
            $can_see = $forum['can_see'] ? '<i class="fa fa-check-circle-o" aria-hidden="true"></i> Yes' : '<i class="fa fa-circle-o" aria-hidden="true"></i> No';
            $response = $response . "<tr>
              <td>" . $forum['title'] . "</td>
              <td>" . $can_see . "</td>
              <td><a href=\"/admincp/default/forum/perms/" . $forum['forumid'] . "\" class=\"web-page\">Edit Permissions</a></td>
            </tr>";

            if (count($forum['childs'])) {
                $response = $response . self::getChildsDefault($forum['childs']);
            }
        }

        return $response;
    }

    private static function childChilds($child)
    {
        $response = "";

        foreach ($child as $ch) {
            $response = $response . "<tr>
              <td>" . $ch['title'] . "</td>
              <td><center>" . $ch['displayorder'] . "</center></td>
              <td>" . self::forumActions($ch['forumid']) . "</td>
            </tr>";

            if (count($ch['childs'])) {
                $response = $response . self::childChilds($ch['childs']);
            }
        }

        return $response;
    }

    /* GET CHILD FORUM FOR FORUM ADD */
    public static function getChildsSelect($parent, $forums)
    {
        $response = "";

        foreach ($forums as $forum) {
            if ($forum['forumid'] == $parent) {
                $selected = 'selected=""';
            } else {
                $selected = "";
            }

            $response = $response . '<option value="' . $forum['forumid'] . '" ' . $selected . '>' . $forum['title'] . '</option>';

            if (count($forum['childs'])) {
                $response = $response . self::childChildsSelect($parent, $forum['childs']);
            }
        }

        return $response;
    }

    private static function childChildsSelect($parent, $child)
    {
        $response = "";

        foreach ($child as $ch) {
            if ($ch['forumid'] == $parent) {
                $selected = 'selected=""';
            } else {
                $selected = "";
            }

            $response = $response . '<option value="' . $ch['forumid'] . '" ' . $selected . '>' . $ch['title'] . '</option>';

            if (count($ch['childs'])) {
                $response = $response . self::childChildsSelect($parent, $ch['childs']);
            }
        }

        return $response;
    }

    public static function forumActions($forumid)
    {
        $response = '<select id="forumid-' . $forumid . '">
		<option value="1">Edit Forum</option>
		<option value="3">Add Child Forum</option>
		<option value="2">Delete Forum</option>
		</select>

		<td style="text-align: center;"><a onclick="forumAction(' . $forumid . ');"><i class="fa fa-cog editcog4" aria-hidden="true" style="color: #000;"></i></a></td>';

        return $response;
    }

    public static function returnTimeAfterTimezone($timeX, $reverse = false)
    {
        if (Auth::check()) {
            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $minus = 3600*$timezone->value;
                    if ($reverse) {
                        $timeX += $minus;
                    } else {
                        $timeX -= $minus;
                    }
                } else {
                    $plus = 3600*$timezone->value;
                    if ($reverse) {
                        $timeX -= $plus;
                    } else {
                        $timeX += $plus;
                    }
                }
            }
        }

        return $timeX;
    }

    public static function forumHaveOption($forumid, $permission)
    {
        $response = false;

        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            if ($forum->options & $permission) {
                $response = true;
            }
        }

        return $response;
    }

    private static function getForumIds($forumid)
    {
        $forumids = array();

        $subs = DB::table('forums')->where('parentid', $forumid)->get();

        foreach ($subs as $sub) {
            $forumids[] = $sub->forumid;

            $subIds = self::getForumIds($sub->forumid);

            foreach ($subIds as $subId) {
                $forumids[] = $subId;
            }
        }

        return $forumids;
    }

    private static function getChildForumIds($forumid)
    {
        $ids = array();
        $temps = DB::table('forums')->where('parentid', $forumid)->pluck('forumid');
        foreach ($temps as $temp) {
            $childs = self::getChildForumIds($temp);
            $childs[] = $temp;
            $ids = array_merge($ids, $childs);
        }
        return $ids;
    }

    private static function getForumStats($forumid)
    {
        $forumids = array($forumid);
        //		$forumids = array_merge(self::getChildForumIds($forumid), $forumids);

        $threads = DB::table('threads')->whereIn('forumid', $forumids)->where('visible', 1)->pluck('threadid');
        $posts = DB::table('posts')->whereIn('threadid', $threads)->where('visible', 1)->count();
        return array('posts' => $posts, 'threads' => count($threads));
    }

    public static function getSubForums($userid, $parentid) {
        $sub_forums = DB::table('forums')->where('parentid', $parentid)->orderBy('displayorder', 'ASC')->get();
        $forums = array();

        foreach ($sub_forums as $sub_forum) {
            if (UserHelper::haveForumPerm($userid, $sub_forum->forumid, 1)) {
                $forum_read = DB::table('forum_read')->where('userid', $userid)->where('forumid', $sub_forum->forumid)->first();

                $posts = "Empty";
                $threads = "Empty";

                $can_see_last_post = false;
                $last_post_avatar = "";
                $last_post_title = "";
                $last_post_full_title = "";
                $last_post_time = "";
                $last_post_poster = "";
                $last_post_postername = "";
                $last_post_threadid = "";
                $last_post_page = "";
                $have_read_forum = true;
                $subForums = array();

                $forumids = self::getForumIds($sub_forum->forumid);
                $forumids[] = $sub_forum->forumid;

                $postid = 0;
                $last_post = 0;
                $private = 1;

                foreach ($forumids as $forumid) {
                    if (UserHelper::haveForumPerm($userid, $forumid, 32)) {
                        $fm = DB::table('forums')->where('forumid', $forumid)->first();

                        if ($fm) {
                            if ($fm->lastpost > $last_post) {
                                $last_post = $fm->lastpost;
                                $postid = $fm->lastpostid;
                                $private = 0;
                            }
                        }
                    } else {
                        $thread = DB::table('threads')->where('forumid', $forumid)->where('postuserid', $userid)->orderBy('lastpost', 'DESC')->first();

                        if ($thread) {
                            if ($thread->lastpost > $last_post) {
                                $last_post = $thread->lastpost;
                                $postid = $thread->lastpostid;
                                $private = 1;
                            }
                        }
                    }
                    unset($forumid);
                }

                if ($private == 0) {
                    $posts = number_format($sub_forum->posts);
                    $threads = number_format($sub_forum->threads);
                    $posts = $posts . '<br />Posts';
                    $threads = $threads . '<br />Threads';
                }

                if ($postid > 0) {
                    $post = DB::table('posts')->where('postid', $postid)->first();
                    if (count($post)) {
                        if (count($forum_read)) {
                            if ($forum_read->dateline < $post->dateline) {
                                $have_read_forum = false;
                            }
                        } else {
                            $have_read_forum = false;
                        }

                        $thread = DB::table('threads')->where('threadid', $post->threadid)->first();
                        if (count($thread)) {
                            $prefix = DB::table('prefixes')->where('prefixid', $thread->prefixid)->first();
                            $title = str_replace(">", "&#62;", $thread->title);
                            $title = str_replace("<", "&#60;", $title);
                            $last_post_full_title = $title;
                            if (count($prefix)) {
                                $title = strlen($title) >= 18 ? substr($title, 0, 18)."..." : $title;
                                $title = '<div style="' . $prefix->style . '">' . $prefix->text . ' &#187;</div> ' . $title;
                            } else {
                                $title = strlen($title) >= 25 ? substr($title, 0, 25)."..." : $title;
                            }

                            $can_see_last_post = true;
                            $last_post_avatar = UserHelper::getAvatar($post->userid);
                            $last_post_title = $title;
                            $last_post_time = ForumHelper::timeAgo($post->dateline);
                            $last_post_poster = UserHelper::getUsername($post->userid);
                            $last_post_postername = UserHelper::getUsername($post->userid, true);
                            $last_post_threadid = $thread->threadid;
                            $last_post_page = 1;

                            /* CALCULATE WHERE FIRST POST THAT IS NOT READ IS */
                            $last_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $thread->threadid)->first();

                            if (count($last_read)) {
                                $last_post = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('dateline', '>', $last_read->read)->orderBy('postid', 'DESC')->first();

                                if (count($last_post)) {
                                    /* HOW MANY MORE POSTS ARE THERE BEFORE THIS ONE */
                                    $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('postid', '<', $last_post->postid)->count();
                                    $pages = ceil($amount_posts/10);

                                    $last_post_page = $pages;
                                } else {
                                    /* GET THE LAST PAGE */
                                    $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->count();
                                    $pages = ceil($amount_posts/10);

                                    $last_post_page = $pages;
                                }
                            }
                        }
                    }
                }

                $subForums = self::getSlimSubForums($userid, $sub_forum->forumid);

                $sf = array(
                    'thumbnail' => asset('_assets/img/forumthumbnails/'.$sub_forum->forumid.'.gif'),
                    'forumid' => $sub_forum->forumid,
                    'title' => $sub_forum->title,
                    'desc' => strlen($sub_forum->description) >= 60 ? substr($sub_forum->description, 0, 60)."..." : $sub_forum->description,
                    'posts' => $posts,
                    'threads' => $threads,
                    'can_see_last_post' => $can_see_last_post,
                    'last_post_avatar' => $last_post_avatar,
                    'last_post_title' => $last_post_title,
                    'last_post_full_title' => $last_post_full_title,
                    'last_post_time' => $last_post_time,
                    'last_post_poster' => $last_post_poster,
                    'last_post_postername' => $last_post_postername,
                    'last_post_threadid' => $last_post_threadid,
                    'last_post_page' => $last_post_page,
                    'private' => $private,
                    'have_read_forum' => $have_read_forum,
                    'subForums' => $subForums
                );

                $forums[] = $sf;
            }
        }

        return $forums;
    }

    public static function getSubSubForums($userid, $parentid)
    {
        $sub_forums = DB::table('forums')->where('parentid', $parentid)->orderBy('displayorder', 'ASC')->get();
        $forums = array();

        foreach ($sub_forums as $sub_forum) {
            if (UserHelper::haveForumPerm($userid, $sub_forum->forumid, 1)) {
                $forum_read = DB::table('forum_read')->where('userid', $userid)->where('forumid', $sub_forum->forumid)->first();

                $posts = "Empty";
                $threads = "Empty";

                $can_see_last_post = false;
                $last_post_avatar = "";
                $last_post_title = "";
                $last_post_time = "";
                $last_post_poster = "";
                $last_post_postername = "";
                $last_post_threadid = "";
                $last_post_page = "";
                $have_read_forum = true;
                $subForums = array();

                $forumids = self::getForumIds($sub_forum->forumid);
                $forumids[] = $sub_forum->forumid;

                $postid = 0;
                $last_post = 0;
                $private = 1;

                foreach ($forumids as $forumid) {
                    if (UserHelper::haveForumPerm($userid, $forumid, 32)) {
                        $fm = DB::table('forums')->where('forumid', $forumid)->first();

                        if ($fm) {
                            if ($fm->lastpost > $last_post) {
                                $last_post = $fm->lastpost;
                                $postid = $fm->lastpostid;
                                $private = 0;
                            }
                        }
                    } else {
                        $thread = DB::table('threads')->where('forumid', $forumid)->where('postuserid', $userid)->orderBy('lastpost', 'DESC')->first();

                        if ($thread) {
                            if ($thread->lastpost > $last_post) {
                                $last_post = $thread->lastpost;
                                $postid = $thread->lastpostid;
                                $private = 1;
                            }
                        }
                    }
                    unset($forumid);
                }

                if ($private == 0) {
                    $posts = number_format($sub_forum->posts);
                    $threads = number_format($sub_forum->threads);
                    $posts = $posts . '<br />Posts';
                    $threads = $threads . '<br />Threads';
                }

                if ($postid > 0) {
                    $post = DB::table('posts')->where('postid', $postid)->first();
                    if (count($post)) {
                        if (count($forum_read)) {
                            if ($forum_read->dateline < $post->dateline) {
                                $have_read_forum = false;
                            }
                        } else {
                            $have_read_forum = false;
                        }

                        $thread = DB::table('threads')->where('threadid', $post->threadid)->first();
                        if (count($thread)) {
                            $prefix = DB::table('prefixes')->where('prefixid', $thread->prefixid)->first();
                            $title = str_replace(">", "&#62;", $thread->title);
                            $title = str_replace("<", "&#60;", $title);
                            if (count($prefix)) {
                                $title = strlen($title) >= 13 ? substr($title, 0, 13)."..." : $title;
                                $title = '<span style="' . $prefix->style . '">' . $prefix->text . ' &#187;</span> ' . $title;
                            } else {
                                $title = strlen($title) >= 25 ? substr($title, 0, 25)."..." : $title;
                            }

                            $can_see_last_post = true;
                            $last_post_avatar = UserHelper::getAvatar($sub_forum->lastposterid);
                            $last_post_title = $title;
                            $last_post_time = ForumHelper::timeAgo($post->dateline);
                            $last_post_poster = UserHelper::getUsername($post->userid);
                            $last_post_postername = UserHelper::getUsername($post->userid, true);
                            $last_post_threadid = $thread->threadid;
                            $last_post_page = 1;

                            /* CALCULATE WHERE FIRST POST THAT IS NOT READ IS */
                            $last_read = DB::table('thread_read')->where('userid', $userid)->where('threadid', $thread->threadid)->first();

                            if (count($last_read)) {
                                $last_post = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('dateline', '>', $last_read->read)->orderBy('postid', 'DESC')->first();

                                if (count($last_post)) {
                                    /* HOW MANY MORE POSTS ARE THERE BEFORE THIS ONE */
                                    $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->where('postid', '<', $last_post->postid)->count();
                                    $pages = ceil($amount_posts/10);

                                    $last_post_page = $pages;
                                } else {
                                    /* GET THE LAST PAGE */
                                    $amount_posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->count();
                                    $pages = ceil($amount_posts/10);

                                    $last_post_page = $pages;
                                }
                            }
                        }
                    }
                }

                $subForums = self::getSlimSubForums($userid, $sub_forum->forumid);

                $sf = array(
                    'thumbnail' => asset('_assets/img/forumthumbnails/'.$sub_forum->parentid.'.gif'),
                    'forumid' => $sub_forum->forumid,
                    'title' => $sub_forum->title,
                    'desc' => strlen($sub_forum->description) >= 60 ? substr($sub_forum->description, 0, 60)."..." : $sub_forum->description,
                    'posts' => $posts,
                    'threads' => $threads,
                    'can_see_last_post' => $can_see_last_post,
                    'last_post_avatar' => $last_post_avatar,
                    'last_post_title' => $last_post_title,
                    'last_post_time' => $last_post_time,
                    'last_post_poster' => $last_post_poster,
                    'last_post_postername' => $last_post_postername,
                    'last_post_threadid' => $last_post_threadid,
                    'last_post_page' => $last_post_page,
                    'private' => $private,
                    'have_read_forum' => $have_read_forum,
                    'subForums' => $subForums
                );

                $forums[] = $sf;
            }
        }

        return $forums;
    }

    private static function getSlimSubForums($userid, $forumid)
    {
        $temps = DB::table('forums')->where('parentid', $forumid)->orderBy('displayorder', 'ASC')->get();
        $result = array();
        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm($userid, $temp->forumid, 1)) {
                $title = str_replace(">", "&#62;", $temp->title);
                $title = str_replace("<", "&#60;", $title);
                $result[] = '<a href="/forum/category/' . $temp->forumid . '/page/1" class="web-page forum-sub-link">' . $title . '</a>';
            }
        }

        return $result;
    }

    public static function timeAgo($time_ago)
    {
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60);
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400);
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640);
        $years      = round($time_elapsed / 31207680);
        // Seconds
        if ($seconds <= 60) {
            return "Just now";
        }
        //Minutes
        elseif ($minutes <=60) {
            if ($minutes==1) {
                return "One minute ago";
            } else {
                return "$minutes mins ago";
            }
        }
        //Hours
        elseif ($hours <=24) {
            if ($hours==1) {
                return "An hour ago";
            } else {
                return "$hours hrs ago";
            }
        }
        //Days
        elseif ($days <= 7) {
            if ($days==1) {
                return "Yesterday";
            } else {
                return "$days days ago";
            }
        }
        //Weeks
        elseif ($weeks <= 4.3) {
            if ($weeks==1) {
                return "A week ago";
            } else {
                return "$weeks weeks ago";
            }
        }
        //Months
        elseif ($months < 12) {
            if ($months==1) {
                return "A month ago";
            } else {
                return "$months months ago";
            }
        }
        //Years
        else {
            if ($years==1) {
                return "One year ago";
            } else {
                return "$years years ago";
            }
        }
    }
}
