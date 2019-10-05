<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\UserHelper;
use App\Helpers\ForumHelper;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use File;
use Image;

class ModerationController extends BaseController
{
    public function __construct()
    {
    }

    public function deleteWarnInfraction(Request $request, $id)
    {
        $warninginfraction = DB::table('infraction')->where('infractionid', $id)->first();

        switch ($warninginfraction->type) {
            case 0:
                $type = 'Warning';
            break;
            case 1:
                $type = 'Infraction';
            break;
            case 2:
                $type = 'Verbal Warning';
            break;
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => "Deleted ${type}",
            'content' => 3,
            'contentid' => $warninginfraction->userid,
            'affected_userid' => $warninginfraction->userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        DB::table('infraction')->where('infractionid', $id)->delete();

        return response()->json(array('success' => true, 'response' => true));
    }

    public function unbanfromThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $thread = DB::table("threads")->where('threadid', $threadid)->first();
        $response = false;
        if (count($thread)) {
            $userid = $request->input('thread_unbanuserid');
            $unbanned = $thread->banned_userids;
            $unbanned_array = explode(',', $unbanned);
            if (($user_index = array_search($userid, $unbanned_array)) !== false) {
                unset($unbanned_array[$user_index]);
            }
            $unbanned = implode(',', $unbanned_array);
            DB::table('threads')->where('threadid', $threadid)
                ->update(['banned_userids' => $unbanned]);
            $response = true;
            $message = "User unbanned from thread!";
        } else {
            $message = "Article no longer exists";
        }

        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Unbanned user from thread',
            'content' => 1,
            'contentid' => $thread->threadid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function banfromThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $thread = DB::table("threads")->where('threadid', $threadid)->first();
        $response = false;

        if (count($thread)) {
            $userid = $request->input('thread_banuserid');
            $banned = $thread->banned_userids;
            if ($banned == "") {
                $banned = $userid;
                DB::table('threads')->where('threadid', $threadid)
                    ->update(['banned_userids' => $banned]);
                $response = true;
                $message = "User banned from thread!";
            } else {
                $banned_array = explode(',', $banned);
                if (!in_array($userid, $banned_array)) {
                    $banned_array[] = $userid;
                    $banned = implode(',', $banned_array);
                    DB::table('threads')->where('threadid', $threadid)
                        ->update(['banned_userids' => $banned]);
                    $response = true;
                    $message = "User banned from thread!";
                } else {
                    $message = "You have already banned this user from this thread!";
                }
            }
        } else {
            $message = "Thread no longer exists";
        }

        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Banned user from thread',
            'content' => 1,
            'contentid' => $thread->threadid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function givevmInfWar(Request $request)
    {
        $vmid = $request->input('vmid');
        $reason = $request->input('reason');
        $type = $request->input('type');
        $pm = $request->input('pm');
        $response = false;
        $vm = DB::table('visitor_messages')->where('vmid', $vmid)->first();
        $time = time();
        $profile = DB::table('visitor_messages')->where('vmid', $vm->vmid)->first();

        if (count($vm)) {
            $user = DB::table('users')->where('userid', $vm->postuserid)->first();
            if (count($user)) {
                if ($user->userid == Auth::user()->userid) {
                    return response()->json(array('success' => true, 'response' => $response));
                }

                $infraction = DB::table('infraction_reasons')->where('infractionrsnid', $reason)->first();

                $count = DB::table('visitor_messages')->where('vmid', '<', $vm->vmid)->where('vmid', $vmid)->count();
                $count += 1;
                $count = ceil($count / 10);

                if ($count < 1) {
                    $page = 1;
                } else {
                    $page = $count;
                }

                $pm = str_replace("{USER}", $user->username, $pm);
                $pm = str_replace("{INFRACTION/WARNING HERE}", $infraction->text, $pm);
                $pm = str_replace("{EVIDENCE}", $vm->message, $pm);

                if ($type == 0) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'warning', $pm);
                } elseif ($type == 1) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'infraction', $pm);
                } elseif ($type == 2) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'verbal warning', $pm);
                }

                $expire = $time + $infraction->expires;

                DB::table('infraction')->insert([
                    'infractionlevelid' => $infraction->infractionrsnid,
                    'vmreceiver' => $vm->reciveuserid,
                    'vmid' => $vm->vmid,
                    'userid' => $vm->postuserid,
                    'whoadded' => Auth::user()->userid,
                    'points' => $infraction->points,
                    'dateline' => $time,
                    'action' => 0,
                    'actiondateline' => 0,
                    'actionuserid' => 0,
                    'actionreason' => 0,
                    'expires' => $expire,
                    'v5' => 0,
                    'type' => $type
                ]);

                $pmid = DB::table('private_messages')->insertGetId([
                    'recive_userid' => $user->userid,
                    'post_userid' => Auth::user()->userid,
                    'content' => $pm,
                    'dateline' => $time,
                    'read_at' => 0
                ]);

                DB::table('notifications')->insert([
                    'postuserid' => Auth::user()->userid,
                    'reciveuserid' => $user->userid,
                    'content' => 5,
                    'contentid' => $pmid,
                    'dateline' => time(),
                    'read_at' => 0
                ]);

                if ($type == 1) {
                    DB::table('users')->where('userid', $user->userid)->update(['credits' => DB::raw('credits-5')]);

                    $inf = DB::table('infraction')->where('userid', $user->userid)->where('expires', '>', $time)->get();

                    $points = 0;

                    foreach ($inf as $in) {
                        $points = $points + $in->points;
                    }

                    $ban = 1;

                    if ($points > 7) {
                        $ban = 86400;
                    }

                    if ($points > 9) {
                        $ban = 172800;
                    }

                    if ($points > 11) {
                        $ban = 259200;
                    }

                    if ($points > 19) {
                        $ban = 604800;
                    }

                    if ($points > 499) {
                        $ban = 0;
                    }

                    $lift = $ban + $time;

                    if ($ban != 1) {
                        DB::table('users_banned')->insert([
                            'userid' => $user->userid,
                            'adminid' => 0,
                            'banned_at' => $time,
                            'banned_until' => $lift,
                            'reason' => 'Infraction Ban'
                        ]);
                    }
                }

                $response = true;
            } else {
                $response = false;
            }
            return response()->json(array('success' => true, 'response' => $response));
        }
    }

    public function giveartInfWar(Request $request)
    {
        $commentid = $request->input('warning_commentid');
        $reason = $request->input('reason');
        $type = $request->input('type');
        $pm = $request->input('pm');
        $response = false;
        $comment = DB::table('article_comments')->where('commentid', $commentid)->first();
        $time = time();
        $article = DB::table('articles')->where('articleid', $comment->articleid)->first();

        if (count($article)) {
            $user = DB::table('users')->where('userid', $comment->userid)->first();
            if (count($user)) {
                if ($user->userid == Auth::user()->userid) {
                    return response()->json(array('success' => true, 'response' => $response));
                }

                $infraction = DB::table('infraction_reasons')->where('infractionrsnid', $reason)->first();

                $count = DB::table('article_comments')->where('articleid', '<', $comment->articleid)->where('commentid', $commentid)->count();
                $count += 1;
                $count = ceil($count / 10);

                if ($count < 1) {
                    $page = 1;
                } else {
                    $page = $count;
                }

                $pm = str_replace("{USER}", $user->username, $pm);
                $pm = str_replace("{INFRACTION/WARNING HERE}", $infraction->text, $pm);
                $pm = str_replace("{EVIDENCE}", $comment->content, $pm);

                if ($type == 0) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'warning', $pm);
                } elseif ($type == 1) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'infraction', $pm);
                } elseif ($type == 2) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'verbal warning', $pm);
                }

                $expire = $time + $infraction->expires;

                DB::table('infraction')->insert([
                    'infractionlevelid' => $infraction->infractionrsnid,
                    'articleid' => $comment->articleid,
                    'commentid' => $comment->commentid,
                    'userid' => $comment->userid,
                    'whoadded' => Auth::user()->userid,
                    'points' => $infraction->points,
                    'dateline' => $time,
                    'action' => 0,
                    'actiondateline' => 0,
                    'actionuserid' => 0,
                    'actionreason' => 0,
                    'expires' => $expire,
                    'v5' => 0,
                    'type' => $type
                ]);

                $pmid = DB::table('private_messages')->insertGetId([
                    'recive_userid' => $user->userid,
                    'post_userid' => Auth::user()->userid,
                    'content' => $pm,
                    'dateline' => $time,
                    'read_at' => 0
                ]);

                DB::table('notifications')->insert([
                    'postuserid' => Auth::user()->userid,
                    'reciveuserid' => $user->userid,
                    'content' => 5,
                    'contentid' => $pmid,
                    'dateline' => time(),
                    'read_at' => 0
                ]);

                if ($type == 1) {
                    DB::table('users')->where('userid', $user->userid)->update(['credits' => DB::raw('credits-5')]);

                    $inf = DB::table('infraction')->where('userid', $user->userid)->where('expires', '>', $time)->get();

                    $points = 0;

                    foreach ($inf as $in) {
                        $points = $points + $in->points;
                    }

                    $ban = 1;

                    if ($points > 7) {
                        $ban = 86400;
                    }

                    if ($points > 9) {
                        $ban = 172800;
                    }

                    if ($points > 11) {
                        $ban = 259200;
                    }

                    if ($points > 19) {
                        $ban = 604800;
                    }

                    if ($points > 499) {
                        $ban = 0;
                    }

                    $lift = $ban + $time;

                    if ($ban != 1) {
                        DB::table('users_banned')->insert([
                            'userid' => $user->userid,
                            'adminid' => 0,
                            'banned_at' => $time,
                            'banned_until' => $lift,
                            'reason' => 'Infraction Ban'
                        ]);
                    }
                }

                $response = true;
            } else {
                $response = false;
            }
            return response()->json(array('success' => true, 'response' => $response));
        }
    }

    public function giveInfWar(Request $request)
    {
        $postid = $request->input('warning_postid');
        $reason = $request->input('reason');
        $type = $request->input('type');
        $pm = $request->input('pm');
        $response = false;
        $post = DB::table('posts')->where('postid', $postid)->first();
        $time = time();
        $thread = DB::table('threads')->where('threadid', $post->threadid)->first();

        if (count($thread)) {
            $user = DB::table('users')->where('userid', $post->userid)->first();
            if (count($user)) {
                if ($user->userid == Auth::user()->userid) {
                    return response()->json(array('success' => true, 'response' => $response));
                }

                $infraction = DB::table('infraction_reasons')->where('infractionrsnid', $reason)->first();

                $count = DB::table('posts')->where('postid', '<', $postid)->where('threadid', $thread->threadid)->count();
                $count += 1;
                $count = ceil($count / 10);

                if ($count < 1) {
                    $page = 1;
                } else {
                    $page = $count;
                }

                $pm = str_replace("{USER}", $user->username, $pm);
                $pm = str_replace("{INFRACTION/WARNING HERE}", $infraction->text, $pm);
                $pm = str_replace("{EVIDENCE}", $post->content, $pm);

                if ($type == 0) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'warning', $pm);
                } elseif ($type == 1) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'infraction', $pm);
                } elseif ($type == 2) {
                    $pm = str_replace("{INFRACTION/WARNING}", 'verbal warning', $pm);
                }

                $expire = $time + $infraction->expires;

                DB::table('infraction')->insert([
                    'infractionlevelid' => $infraction->infractionrsnid,
                    'threadid' => $post->threadid,
                    'postid' => $post->postid,
                    'userid' => $post->userid,
                    'whoadded' => Auth::user()->userid,
                    'points' => $infraction->points,
                    'dateline' => $time,
                    'action' => 0,
                    'actiondateline' => 0,
                    'actionuserid' => 0,
                    'actionreason' => 0,
                    'expires' => $expire,
                    'v5' => 0,
                    'type' => $type
                ]);

                $pmid = DB::table('private_messages')->insertGetId([
                    'recive_userid' => $user->userid,
                    'post_userid' => Auth::user()->userid,
                    'content' => $pm,
                    'dateline' => $time,
                    'read_at' => 0
                ]);

                DB::table('notifications')->insert([
                    'postuserid' => Auth::user()->userid,
                    'reciveuserid' => $user->userid,
                    'content' => 5,
                    'contentid' => $pmid,
                    'dateline' => time(),
                    'read_at' => 0
                ]);

                if ($type == 1) {
                    DB::table('users')->where('userid', $user->userid)->update(['credits' => DB::raw('credits-5')]);

                    $inf = DB::table('infraction')->where('userid', $user->userid)->where('expires', '>', $time)->get();

                    $points = 0;

                    foreach ($inf as $in) {
                        $points = $points + $in->points;
                    }

                    $ban = 1;

                    if ($points > 7) {
                        $ban = 86400;
                    }

                    if ($points > 9) {
                        $ban = 172800;
                    }

                    if ($points > 11) {
                        $ban = 259200;
                    }

                    if ($points > 19) {
                        $ban = 604800;
                    }

                    if ($points > 499) {
                        $ban = 0;
                    }

                    $lift = $ban + $time;

                    if ($ban != 1) {
                        DB::table('users_banned')->insert([
                            'userid' => $user->userid,
                            'adminid' => 0,
                            'banned_at' => $time,
                            'banned_until' => $lift,
                            'reason' => 'Infraction Ban'
                        ]);
                    }
                }

                $response = true;
            } else {
                $response = false;
            }
            return response()->json(array('success' => true, 'response' => $response));
        }
    }

    public function postDeleteVms(Request $request)
    {
        $vmids = $request->input('vmids');

        foreach ($vmids as $vmid) {
            DB::table('visitor_messages')->where('vmid', $vmid)->update(['visible' => 0]);
        }

        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted visitor messages',
            'content' => 4,
            'contentid' => 0,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postApproveCreation(Request $request)
    {
        $creation = DB::table('creations')->where('creationid', $request->input('creationid'))->first();

        DB::table('creations')->where('creationid', $creation->creationid)->update(['approved' => 1]);
        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Approved creation',
            'content' => 6,
            'contentid' => $creation->creationid,
            'affected_userid' => $creation->userid,
            'extra_info' => $creation->name,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postDeleteCreation(Request $request)
    {
        $creation = DB::table('creations')->where('creationid', $request->input('creationid'))->first();

        DB::table('creations')->where('creationid', $creation->creationid)->delete();
        File::delete('_assets/img/creations/' . $creation->creationid . '.png');
        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted creation',
            'content' => 6,
            'contentid' => $creation->creationid,
            'affected_userid' => $creation->userid,
            'extra_info' => $creation->name,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postDeleteComment(Request $request)
    {
        $comment = DB::table('article_comments')->where('commentid', $request->input('commentid'))->first();

        if (count($comment)) {
            DB::table('article_comments')->where('commentid', $request->input('commentid'))->update(['visible' => 0]);
            DB::table('mod_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Deleted comment',
                'content' => 3,
                'contentid' => $comment->articleid,
                'affected_userid' => $comment->userid,
                'extra_info' => $comment->content,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'commentcount' => DB::raw('commentcount-1')
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function postDeleteCcomment(Request $request)
    {
        $comment = DB::table('creation_comments')->where('commentid', $request->input('commentid'))->first();

        if (count($comment)) {
            DB::table('creation_comments')->where('commentid', $request->input('commentid'))->update(['visible' => 0]);
            DB::table('mod_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Deleted comment',
                'content' => 6,
                'contentid' => $comment->creationid,
                'affected_userid' => $comment->userid,
                'extra_info' => $comment->content,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
            DB::table('creations')->where('creationid', $comment->creationid)->update([
                'comments' => DB::raw('comments-1'),
            ]);
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'commentcount' => DB::raw('commentcount-1')
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function postAvatarUpdate(Request $request)
    {
        $response = false;
        $userid = $request->input('temp_userid');
        $message = "";

        $user = DB::table('users')->where('userid', $userid)->first();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($user)) {
            if ($myImmunity > UserHelper::getImmunity($userid)) {
                if ($request->hasFile('avatar') and $request->file('avatar')->isValid()) {
                    $img = Image::make($request->file('avatar'));
                    $avid = time() . Auth::user()->userid;

                    $groups = explode(",", $user->usergroups);
                    $max_height = 200;
                    $max_width = 200;

                    foreach ($groups as $group) {
                        $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                        if (count($grp)) {
                            if ($grp->avatar_height >= $max_height and $grp->avatar_width >= $max_width) {
                                $max_width = $grp->avatar_width;
                                $max_height = $grp->avatar_height;
                            }
                        }
                    }

                    if ($img->height() > $max_height) {
                        $img->resize(null, $max_height);
                    }
                    if ($img->width() > $max_width) {
                        $img->resize($max_width, null);
                    }

                    $img->save('_assets/img/avatars/' . $avid . '.gif', 60);
                    DB::table('users')->where('userid', $user->userid)->update([
                        'lastavataredit' => time(),
                        'avatar' => $avid
                    ]);

                    DB::table('mod_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Changed avatar',
                        'content' => 5,
                        'contentid' => $user->userid,
                        'affected_userid' => $user->userid,
                        'ip' => Auth::user()->lastip,
                        'dateline' => time()
                    ]);

                    $response = true;
                } else {
                    $message = "Not a valid image!";
                }
            } else {
                $mesage = "Can't edit admin!";
            }
        } else {
            $message = "Can't find user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postHeaderUpdate(Request $request)
    {
        $response = false;
        $userid = $request->input('temp_userid');
        $message = "";

        $user = DB::table('users')->where('userid', $userid)->first();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($user)) {
            if ($myImmunity > UserHelper::getImmunity($userid)) {
                if ($request->hasFile('header') and $request->file('header')->isValid()) {
                    $img = Image::make($request->file('header'));

                    $img->save('_assets/img/headers/' . $user->userid . '.gif', 60);

                    DB::table('users')->where('userid', $user->userid)->update(['profile_header' => 0]);

                    DB::table('mod_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Changed header',
                        'content' => 5,
                        'contentid' => $user->userid,
                        'affected_userid' => $user->userid,
                        'ip' => Auth::user()->lastip,
                        'dateline' => time()
                    ]);

                    $response = true;
                } else {
                    $message = "Not a valid image!";
                }
            } else {
                $mesage = "Can't edit admin!";
            }
        } else {
            $message = "Can't find user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postSignatureUpdate(Request $request)
    {
        $userid = $request->input('temp_userid');
        $signature = $request->input('signature');

        $response = false;
        $message = "";

        $user = DB::table('users')->where('userid', $userid)->first();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($user)) {
            if ($myImmunity > UserHelper::getImmunity($userid)) {
                DB::table('users')->where('userid', $userid)->update(['signature' => $signature]);
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Changed signature',
                    'content' => 5,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);

                $response = true;
            } else {
                $message = "Can't edit admin!";
            }
        } else {
            $message = "Can't find the user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postBioUpdate(Request $request)
    {
        $userid = $request->input('temp_userid');
        $bio = $request->input('bio');

        $response = false;
        $message = "";

        $user = DB::table('users')->where('userid', $userid)->first();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($user)) {
            if ($myImmunity > UserHelper::getImmunity($userid)) {
                if (strlen($bio) > 250) {
                    $bio = substr($bio, 0, 250);
                }

                DB::table('users')->where('userid', $userid)->update(['bio' => $bio]);
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Changed bio',
                    'content' => 5,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);

                $response = true;
            } else {
                $message = "Can't edit admin!";
            }
        } else {
            $message = "Can't find the user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postUnbanUser(Request $request)
    {
        $userid = $request->input('temp_userid');

        DB::table('users_banned')->where('userid', $userid)->delete();
        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Unbanned user',
            'content' => 5,
            'contentid' => $userid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postBanUser(Request $request)
    {
        $userid = $request->input('temp_userid');
        $reason = $request->input('reason');
        $time = $request->input('time');

        if ($time == '0') {
            $time = '0';
        } else {
            $time = time() + $request->input('time');
        }

        $response = false;
        $message = "";
        $user = DB::table('users')->where('userid', $userid)->first();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($user)) {
            if ($myImmunity > UserHelper::getImmunity($userid)) {
                DB::table('users_banned')->where('userid', $userid)->delete();

                DB::table('users_banned')->insert([
                    'userid' => $userid,
                    'adminid' => Auth::user()->userid,
                    'banned_at' => time(),
                    'banned_until' => $time,
                    'reason' => $reason
                ]);

                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Banned user',
                    'content' => 5,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);

                DB::table('sessions')->where('user_id', $userid)->delete();

                $response = true;
            } else {
                $message = "You can't ban a admin!";
            }
        } else {
            $message = "Can't find user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postMergeThreads(Request $request)
    {
        $threadid = $request->input('threadid');
        $mergeid = $request->input('mergeid');
        $content = $request->input('content');

        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        $merge = DB::table('threads')->where('threadid', $mergeid)->first();
        $message = "";

        if (count($thread) and count($merge) and $threadid != $mergeid) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1) and UserHelper::haveForumPerm(Auth::user()->userid, $merge->forumid, 1)) {
                if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 32) and UserHelper::haveModPerm(Auth::user()->userid, $merge->forumid, 32)) {
                    DB::table('posts')->where('postid', $merge->firstpostid)->update([
                        'content' => $content
                    ]);

                    $posts = DB::table('posts')->where('threadid', $threadid)->get();

                    foreach ($posts as $post) {
                        DB::table('posts')->insert([
                            'threadid' => $merge->threadid,
                            'username' => $post->username,
                            'userid' => $post->userid,
                            'dateline' => $post->dateline,
                            'lastedit' => $post->lastedit,
                            'content' => $post->content,
                            'ipaddress' => $post->ipaddress,
                            'visible' => $post->visible
                        ]);

                        DB::table('posts')->where('postid', $post->postid)->delete();
                    }

                    DB::table('threads')->where('threadid', $threadid)->delete();

                    DB::table('users')->where('userid', $thread->postuserid)->update([
                        'threadcount' => DB::raw('threadcount-1')
                    ]);

                    DB::table('mod_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Merged threads with ' . $merge->title,
                        'content' => 1,
                        'contentid' => $thread->threadid,
                        'affected_userid' => $thread->postuserid,
                        'ip' => Auth::user()->lastip,
                        'dateline' => time()
                    ]);

                    if ($thread->forumid != $merge->forumid) {
                        DB::table('forums')->where('forumid', $thread->forumid)->update([
                            'threads' => DB::raw('threads-1'),
                            'posts' => DB::raw('posts-' . $thread->replys)
                        ]);

                        DB::table('forums')->where('forumid', $merge->forumid)->update([
                            'posts' => DB::raw('posts+' . $thread->replys)
                        ]);

                        $last_thread = DB::table('threads')->where('forumid', $thread->forumid)->where('visible', 1)->orderBy('threadid', 'DESC')->first();

                        if (count($last_thread)) {
                            $lastthread = $last_thread->dateline;
                            $lastthreadid = $last_thread->threadid;
                        } else {
                            $lastthread = 0;
                            $lastthreadid = 0;
                        }

                        $last_thread_post = DB::table('threads')->where('forumid', $thread->forumid)->where('visible', 1)->where('threadid', '!=', $thread->threadid)->orderBy('lastpost', 'DESC')->first();

                        if (count($last_thread_post)) {
                            $ps = DB::table('posts')->where('postid', $last_thread_post->lastpostid)->first();

                            if (count($ps)) {
                                $lastpost = $ps->dateline;
                                $lastpostid = $ps->postid;
                                $lastposterid = $ps->userid;
                            } else {
                                $lastpost = 0;
                                $lastpostid = 0;
                                $lastposterid = 0;
                            }
                        } else {
                            $lastpost = 0;
                            $lastpostid = 0;
                            $lastposterid = 0;
                        }

                        DB::table('forums')->where('forumid', $thread->forumid)->update([
                            'lastpost' => $lastpost,
                            'lastpostid' => $lastpostid,
                            'lastposterid' => $lastposterid,
                            'lastthread' => $lastthread,
                            'lastthreadid' => $lastthreadid
                        ]);
                    }
                    $amount_posts = DB::table('posts')->where('threadid', $merge->threadid)->where('visible', 1)->count();
                    DB::table('threads')->where('threadid', $merge->threadid)->update(['replys' => ($amount_posts - 1)]);

                    return response()->json(array('success' => true, 'response' => true));
                }
            }
        }

        return response()->json(array('success' => true, 'response' => false));
    }

    public function getMergeThreads($threadid, $mergeid)
    {
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        $merge = DB::table('threads')->where('threadid', $mergeid)->first();

        if (count($thread) and count($merge) and $threadid != $mergeid) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1) and UserHelper::haveForumPerm(Auth::user()->userid, $merge->forumid, 1)) {
                if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 32) and UserHelper::haveModPerm(Auth::user()->userid, $merge->forumid, 32)) {
                    $post1 = DB::table('posts')->where('postid', $thread->firstpostid)->first();
                    $post2 = DB::table('posts')->where('postid', $merge->firstpostid)->first();

                    $content2 = $post1->content;
                    $content1 = $post2->content;

                    $content = $content1 . '
                    ----------------------------------------------------------
                    ' . $content2;

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


                    $returnHTML = view('forum.extras.mergeThread')
                        ->with('content', $content)
                        ->with('thread', $thread)
                        ->with('merge', $merge)
                        ->render();

                    return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
                }
            }
        }

        return redirect()->route('getErrorPerm');
    }

    public function postMovePosts(Request $request)
    {
        $postids = $request->input('postids');
        $targetId = $request->input('targetId');
        $currentId = $request->input('currentId');

        $currentThread = DB::table('threads')->where('threadid', $currentId)->first();
        $thread = DB::table('threads')->where('threadid', $targetId)->first();

        if (!count($currentThread)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Something went wrong!'));
        }

        if (!UserHelper::haveModPerm(Auth::user()->userid, $currentThread->threadid, 128)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You don\'t have permission for this!'));
        }

        $time = time();
        $temps = DB::table('posts')->whereIn('postid', $postids)->orderBy('dateline', 'ASC')->get();
        $lastPostId = 0;
        foreach ($temps as $temp) {
            DB::table('posts')->where('postid', $temp->postid)->update([
                'threadid' => $targetId,
                'dateline' => $time
            ]);
            $time += 5;
            $lastPostId = $temp->postid;
        }

        $last_post1 = DB::table('posts')->where('threadid', $currentId)->orderBy('dateline', 'DESC')->first();

        $currentAmountOfPosts = DB::table('posts')->where('threadid', $currentId)->where('visible', 1)->count();
        DB::table('threads')->where('threadid', $currentId)->update([
            'replys' => $currentThread->replys - count($postids),
            'lastpostid' => $last_post1->postid,
            'lastpost' => $last_post1->dateline
        ]);

        $frm1 = DB::table('forums')->where('forumid', $currentThread->forumid)->first();

        if ($frm1->lastpost < $last_post1->dateline) {
            DB::table('forums')->where('forumid', $currentThread->forumid)->update([
                'lastpost' => $last_post1->dateline,
                'lastpostid' => $last_post1->postid,
                'lastposterid' => $last_post1->userid
            ]);
        }

        $run = true;
        $fmrid = $currentThread->forumid;
        while ($run) {
            $forum = DB::table('forums')->where('forumid', $fmrid)->first();

            if (count($forum)) {
                DB::table('forums')->where('forumid', $fmrid)->update([
                    'posts' => $forum->posts - count($postids)
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

        $run = true;
        $fmrid = $threadid->forumid;
        while ($run) {
            $forum = DB::table('forums')->where('forumid', $fmrid)->first();

            if (count($forum)) {
                DB::table('forums')->where('forumid', $fmrid)->update([
                    'posts' => $forum->posts + count($postids)
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

        $last_post2 = DB::table('posts')->where('threadid', $targetId)->orderBy('dateline', 'DESC')->first();

        $targetAmountOfPosts = DB::table('posts')->where('threadid', $targetId)->where('visible', 1)->count();
        DB::table('threads')->where('threadid', $targetId)->update([
            'replys' => $thread->replys + count($postids),
            'lastpostid' => $lastPostId,
            'lastpost' => $time
        ]);

        $frm2 = DB::table('forums')->where('forumid', $thread->forumid)->first();
        if ($frm2->lastpost < $last_post2->dateline) {
            DB::table('forums')->where('forumid', $currentThread->forumid)->update([
                'lastpost' => $last_post2->dateline,
                'lastpostid' => $last_post2->postid,
                'lastposterid' => $last_post2->userid
            ]);
        }

        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Moved posts',
            'content' => 1,
            'contentid' => $currentId,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postMoveThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $forumid = $request->input('forumid');
        $response = false;

        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        $forumTarget = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($thread) and count($forumTarget)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 16)) {
                $run = true;
                $fmrid = $thread->forumid;
                while ($run) {
                    $forum = DB::table('forums')->where('forumid', $fmrid)->first();

                    if (count($forum)) {
                        DB::table('forums')->where('forumid', $fmrid)->update([
                            'posts' => $forum->posts - $thread->replys,
                            'threads' => $forum->threads - 1
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

                $run = true;
                $fmrid = $forumTarget->forumid;
                while ($run) {
                    $forum = DB::table('forums')->where('forumid', $fmrid)->first();

                    if (count($forum)) {
                        DB::table('forums')->where('forumid', $fmrid)->update([
                            'posts' => $forum->posts + $thread->replys,
                            'threads' => $forum->threads + 1
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

                if ($forumTarget->lastthread < $thread->dateline) {
                    DB::table('forums')->where('forumid', $forumTarget->forumid)->update([
                        'lastthread' => $thread->dateline,
                        'lastthreadid' => $thread->threadid,
                    ]);
                }

                if ($forumTarget->lastpost < $thread->lastpost) {
                    $post = DB::table('posts')->where('postid', $thread->lastpostid)->first();

                    if (count($post)) {
                        DB::table('forums')->where('forumid', $forumTarget->forumid)->update([
                            'lastpost' => $thread->lastpost,
                            'lastpostid' => $thread->lastpostid,
                            'lastposterid' => $post->userid
                        ]);
                    }
                }

                DB::table('threads')->where('threadid', $thread->threadid)->update([
                    'forumid' => $forumTarget->forumid
                ]);

                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Moved thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);

                /* CHANGE OLD FORUM AND UPDATE LATEAST THREAD/POSTS */
                $last_thread = DB::table('threads')->where('forumid', $thread->forumid)->where('visible', 1)->orderBy('threadid', 'DESC')->first();

                if (count($last_thread)) {
                    $lastthread = $last_thread->dateline;
                    $lastthreadid = $last_thread->threadid;
                } else {
                    $lastthread = 0;
                    $lastthreadid = 0;
                }

                $last_post = DB::table('threads')->where('forumid', $thread->forumid)->where('visible', 1)->where('threadid', '!=', $thread->threadid)->orderBy('lastpost', 'DESC')->first();

                if (count($last_post)) {
                    $ps = DB::table('posts')->where('postid', $last_post->lastpostid)->first();

                    if (count($ps)) {
                        $lastpost = $ps->dateline;
                        $lastpostid = $ps->postid;
                        $lastposterid = $ps->userid;
                    } else {
                        $lastpost = 0;
                        $lastpostid = 0;
                        $lastposterid = 0;
                    }
                } else {
                    $lastpost = 0;
                    $lastpostid = 0;
                    $lastposterid = 0;
                }

                DB::table('forums')->where('forumid', $thread->forumid)->update([
                    'lastpost' => $lastpost,
                    'lastpostid' => $lastpostid,
                    'lastposterid' => $lastposterid,
                    'lastthread' => $lastthread,
                    'lastthreadid' => $lastthreadid
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getMoveThread($threadids)
    {
        $ids = explode('-', $threadids);

        $modperms = true;

        $threads = array();
        $threadcount = 0;
        foreach ($ids as $threadid) {
            $thread = DB::table('threads')->where('threadid', $threadid)->first();

            if (!count($thread) || !UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 16)) {
                $modperms = false;
            }
            $threads[] = $thread;
            $threadcount++;
        }

        if ($modperms) {
            $forums = array();
            $userid = Auth::user()->userid;
            $parents = DB::table('forums')->where('parentid', -1)->get();

            foreach ($parents as $parent) {
                if (UserHelper::haveForumPerm($userid, $parent->forumid, 1)) {
                    $array = array(
                        'forumid' => $parent->forumid,
                        'title' => $parent->title,
                        'displayorder' => $parent->displayorder,
                        'childs' => array()
                    );

                    $temps = DB::table('forums')->where('parentid', $parent->forumid)->get();

                    foreach ($temps as $temp) {
                        if (UserHelper::haveForumPerm($userid, $temp->forumid, 1)) {
                            $ar = array(
                                'forumid' => $temp->forumid,
                                'title' => '-' . $temp->title,
                                'displayorder' => $temp->displayorder,
                                'childs' => 0
                            );

                            $chi = self::getForumChilds($userid, $temp->forumid, '--');

                            $ar['childs'] = $chi;

                            $array['childs'][] = $ar;
                        }
                    }

                    $forums[] = $array;
                }
            }

            $returnHTML = view('forum.extras.moveThread')
                ->with('forums', $forums)
                ->with('threadcount', $threadcount)
                ->with('threads', $threads)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
        return redirect()->route('getErrorPerm');
    }

    /* RETURN ARRAY OF CHILDS */
    private function getForumChilds($userid, $forumid, $add)
    {
        $forums = array();

        $temps = DB::table('forums')->where('parentid', $forumid)->get();

        foreach ($temps as $temp) {
            $array = array(
                'forumid' => $temp->forumid,
                'title' => $add . $temp->title,
                'displayorder' => $temp->displayorder,
                'childs' => 0
            );

            $chi = self::getForumChilds($userid, $temp->forumid, $add . '-');

            $array['childs'] = $chi;

            $forums[] = $array;
        }

        return $forums;
    }

    public function opencloseThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $type = $request->input('type');
        $response = false;
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 8)) {
                if ($type == "close") {
                    DB::table('threads')->where('threadid', $threadid)->update(['open' => 0]);
                    $response = true;
                } elseif ($type == "open") {
                    DB::table('threads')->where('threadid', $threadid)->update(['open' => 1]);
                    $response = true;
                }
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => $type == "close" ? 'Closed thread' : 'Opened thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
            }
        }
        return response()->json(array('success' => true, 'response' => $response));
    }

    public function approveThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $response = false;
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 512)) {
                DB::table('posts')->where('postid', $thread->firstpostid)->update([
                    'visible' => 1
                ]);

                DB::table('threads')->where('threadid', $threadid)->update(['visible' => 1, 'soft_deleted' => 0]);

                DB::table('forums')->where('forumid', $thread->forumid)->update([
                    'lastpost' => $thread->lastpost,
                    'lastpostid' => $thread->lastpostid,
                    'lastposterid' => $thread->lastpostuserid,
                    'lastpost' => time(),
                ]);

                $forum = DB::table('forums')->where('forumid', $thread->forumid)->first();

                DB::table('forums')->where('forumid', $forum->parentid)->update([
                    'lastpost' => $thread->lastpost,
                    'lastpostid' => $thread->lastpostid,
                    'lastposterid' => $thread->postuserid
                ]);

                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Approved Thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
                $response = true;
            }
        }
        return response()->json(array('success' => true, 'response' => $response));
    }

    public function unapproveThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $response = false;
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 512)) {
                DB::table('posts')->where('postid', $thread->firstpostid)->update([
                    'visible' => 0
                ]);

                DB::table('threads')->where('threadid', $threadid)->update(['visible' => 0, 'soft_deleted' => 0]);

                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Unapproved Thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
                $response = true;
            }
        }
        return response()->json(array('success' => true, 'response' => $response));
    }

    public function stickyThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $response = false;
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 8)) {
                DB::table('threads')->where('threadid', $threadid)->update(['sticky' => 1]);
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Stickied thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
            }
        }
        return response()->json(array('success' => true, 'response' => $response));
    }

    public function unstickyThread(Request $request)
    {
        $threadid = $request->input('threadid');
        $response = false;
        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 8)) {
                DB::table('threads')->where('threadid', $threadid)->update(['sticky' => 0]);
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Unstickied thread',
                    'content' => 1,
                    'contentid' => $thread->threadid,
                    'affected_userid' => $thread->postuserid,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
            }
        }
        return response()->json(array('success' => true, 'response' => $response));
    }

    public function changePostOwner(Request $request)
    {
        $postids = $request->input('postids');
        $threadid = $request->input('threadid');
        $new_owner = $request->input('username');
        $response = false;
        $message = "Something went wrong!";

        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        if (count($thread)) {
            if ($postids == null) {
                $postids = $thread->firstpostid;
            }

            $forum = DB::table('forums')->where('forumid', $thread->forumid)->first();
            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($new_owner)])->first();
            if (count($forum) and count($user)) {
                if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 64)) {
                    $postids = explode(",", $postids);
                    $new_thread_owner = false;

                    foreach ($postids as $postid) {
                        $current_post = DB::table('posts')->where('postid', $postid)->first();

                        if (count($current_post)) {
                            if ($postid == $thread->firstpostid and $thread->postuserid != $user->userid) {
                                $new_thread_owner = true;
                            }

                            if ($forum->lastpostid == $postid and $thread->postuserid != $user->userid) {
                                DB::table('forums')->where('forumid', $forum->forumid)->update([
                                    'lastposterid' => $user->userid
                                ]);
                            }

                            if ($current_post->userid != $user->userid) {
                                DB::table('posts')->where('postid', $postid)->update([
                                    'username' => $user->username,
                                    'userid' => $user->userid
                                ]);

                                DB::table('users')->where('userid', $current_post->userid)->update([
                                    'postcount' => DB::raw('postcount-1')
                                ]);

                                DB::table('users')->where('userid', $user->userid)->update([
                                    'postcount' => DB::raw('postcount+1')
                                ]);
                            }

                            DB::table('mod_log')->insert([
                                'userid' => Auth::user()->userid,
                                'description' => $new_thread_owner ? 'Changed thread owner' : 'Changed post owner',
                                'content' => $new_thread_owner ? 1 : 2,
                                'contentid' => $new_thread_owner ? $thread->threadid : $current_post->postid,
                                'affected_userid' => $current_post->userid,
                                'ip' => Auth::user()->lastip,
                                'dateline' => time()
                            ]);
                        }
                    }

                    if ($new_thread_owner) {
                        DB::table('threads')->where('threadid', $threadid)->update([
                            'postuserid' => $user->userid
                        ]);

                        DB::table('users')->where('userid', $thread->postuserid)->update([
                            'threadcount' => DB::raw('threadcount-1')
                        ]);

                        DB::table('users')->where('userid', $user->userid)->update([
                            'threadcount' => DB::raw('threadcount+1')
                        ]);
                    }

                    $response = true;
                }
            } else {
                $message = "Could not find a user with that name!";
            }
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function undeletePosts(Request $request)
    {
        $postids = $request->input('postids');
        $threadid = $request->input('threadid');
        $response = false;
        $stay = 1;

        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (count($thread)) {
            if ($postids == null) {
                $postids = $threadid;
            }
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 2)) {
                $postids = explode(",", $postids);
                $undelete_thread = false;

                foreach ($postids as $postid) {
                    $current_post = DB::table('posts')->where('postid', $postid)->first();

                    if (count($current_post)) {
                        if ($postid == $thread->firstpostid) {
                            $undelete_thread = true;
                        }

                        if ($thread->lastpostid < $postid) {
                            DB::table('threads')->where('threadid', $threadid)->update([
                                'lastpost' => $current_post->dateline,
                                'lastpostid' => $current_post->postid
                            ]);
                        }

                        $check_forum = DB::table('forums')->where('forumid', $thread->forumid)->where('lastpostid', '<', $current_post->postid)->count();

                        if ($check_forum == 1) {
                            DB::table('forums')->where('forumid', $thread->forumid)->update([
                                'lastpost' => $current_post->dateline,
                                'lastpostid' => $current_post->postid,
                                'lastposterid' => $current_post->userid
                            ]);
                        }

                        DB::table('posts')->where('postid', $current_post->postid)->update([
                            'visible' => 1
                        ]);


                        DB::table('users')->where('userid', $current_post->userid)->update([
                            'postcount' => DB::raw('postcount+1')
                        ]);

                        DB::table('threads')->where('threadid', $threadid)->update([
                            'replys' => DB::raw('replys+1')
                        ]);

                        DB::table('mod_log')->insert([
                            'userid' => Auth::user()->userid,
                            'description' => 'Undelete',
                            'content' => $undelete_thread ? 1 : 2,
                            'contentid' => $undelete_thread ? $thread->threadid : $current_post->postid,
                            'affected_userid' => $current_post->userid,
                            'ip' => Auth::user()->lastip,
                            'dateline' => time()
                        ]);
                    }
                }

                $run = true;
                $fmrid = $thread->forumid;
                $threads_undeleted = $undelete_thread ? 1 : 0;
                while ($run) {
                    $forum = DB::table('forums')->where('forumid', $fmrid)->first();

                    if (count($forum)) {
                        DB::table('forums')->where('forumid', $fmrid)->update([
                            'posts' => $forum->posts + count($postids),
                            'threads' => $forum->threads + $threads_undeleted
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

                if ($undelete_thread) {
                    DB::table('threads')->where('threadid', $thread->threadid)->update(['visible' => 1, 'soft_deleted' => 0]);
                    $posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 0)->get();

                    foreach ($posts as $post) {
                        DB::table('users')->where('userid', $post->userid)->update([
                            'postcount' => DB::raw('postcount+1')
                        ]);

                        DB::table('threads')->where('threadid', $threadid)->update([
                            'replys' => DB::raw('replys+1')
                        ]);
                    }

                    DB::table('users')->where('userid', $thread->postuserid)->update([
                        'threadcount' => DB::raw('threadcount+1')
                    ]);

                    DB::table('posts')->where('threadid', $thread->threadid)->update([
                        'visible' => 1
                    ]);
                }

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response, 'stay' => $stay));
    }

    public function deletePosts(Request $request)
    {
        $postids = $request->input('postids');
        $type = $request->input('type');
        $threadid = $request->input('threadid');
        $response = false;
        $stay = 1;

        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (count($thread)) {
            if ($postids == null) {
                $postids = $thread->firstpostid;
            }
            if (UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 2)) {
                if ($type == 1 and !UserHelper::haveModPerm(Auth::user()->userid, $thread->forumid, 4)) {
                    $type = 0;
                }

                $thread_deleted = false;
                $delete_reply = 1;

                $postids = explode(",", $postids);

                foreach ($postids as $postid) {
                    $current_post = DB::table('posts')->where('postid', $postid)->where('visible', 1)->first();

                    if (count($current_post)) {
                        if ($postid == $thread->firstpostid) {
                            $thread_deleted = true;
                            $delete_reply = 0;
                            $stay = 0;
                        }

                        $check_thread = DB::table('threads')->where('threadid', $threadid)->where('lastpostid', $postid)->count();

                        if ($check_thread == 1) {
                            //This post is the last post in the thread, we need to change it!

                            $post = DB::table('posts')->where('threadid', $threadid)->where('visible', 1)->where('postid', '!=', $postid)->orderBy('postid', 'DESC')->first();

                            if (count($post)) {
                                $lastpost = $post->dateline;
                                $lastpostid = $post->postid;
                            } else {
                                $lastpost = 0;
                                $lastpostid = 0;
                            }

                            DB::table('threads')->where('threadid', $threadid)->update([
                                'lastpost' => $lastpost,
                                'lastpostid' => $lastpostid,
                                'replys' => DB::raw('replys-' . $delete_reply)
                            ]);
                        }

                        $check_forum = DB::table('forums')->where('forumid', $thread->forumid)->where('lastpostid', $postid)->count();

                        if ($check_forum == 1) {
                            //This post is the last post in the forum! gotta change it!

                            if ($thread_deleted) {
                                $ts = DB::table('threads')->where('forumid', $thread->forumid)->where('threadid', '!=', $thread->threadid)->where('visible', 1)->orderBy('lastpost', 'DESC')->first();
                            } else {
                                $ts = DB::table('threads')->where('forumid', $thread->forumid)->where('visible', 1)->orderBy('lastpost', 'DESC')->first();
                            }

                            if (count($ts)) {
                                $post = DB::table('posts')->where('postid', $ts->lastpostid)->first();
                                $lastpost = $ts->lastpost;
                                $lastpostid = $ts->lastpostid;
                                $lastposterid = 0;
                                if (count($post)) {
                                    $lastposterid = $post->userid;
                                }
                            } else {
                                $lastpost = 0;
                                $lastpostid = 0;
                                $lastposterid = 0;
                            }

                            DB::table('forums')->where('forumid', $thread->forumid)->update([
                                'lastpost' => $lastpost,
                                'lastpostid' => $lastpostid,
                                'lastposterid' => $lastposterid
                            ]);
                        }

                        if ($thread_deleted) {
                            $check_f = DB::table('forums')->where('forumid', $thread->forumid)->where('lastthreadid', $thread->threadid)->count();

                            if (count($check_f)) {
                                $ts = DB::table('threads')->where('threadid', '!=', $thread->threadid)->where('visible', 1)->where('forumid', $thread->forumid)->orderBy('threadid', 'DESC')->first();

                                if (count($ts)) {
                                    $lastthread = $ts->dateline;
                                    $lastthreadid = $ts->threadid;
                                } else {
                                    $lastthreadid = 0;
                                    $lastthread = 0;
                                }

                                DB::table('forums')->where('forumid', $thread->forumid)->update([
                                    'lastthread' => $lastthread,
                                    'lastthreadid' => $lastthreadid
                                ]);
                            }
                        }

                        /* Okey so we have found the thread + forum if needed new latest post! And removed one reply/post count from them */
                        if ($type == 0) {
                            DB::table('posts')->where('postid', $postid)->update([
                                'visible' => 0
                            ]);

                            if ($thread_deleted) {
                                DB::table('threads')->where('threadid', $thread->threadid)->update([
                                    'visible' => 0,
                                    'soft_deleted' => 1
                                ]);
                            }
                        } else {
                            DB::table('posts')->where('postid', $postid)->delete();

                            if ($thread_deleted) {
                                DB::table('threads')->where('threadid', $thread->threadid)->delete();
                            }
                        }

                        DB::table('users')->where('userid', $current_post->userid)->update([
                            'postcount' => DB::raw('postcount-1')
                        ]);

                        DB::table('mod_log')->insert([
                            'userid' => Auth::user()->userid,
                            'description' => $type == 0 ? "Softed deleted" : "Hard deleted",
                            'content' => $thread_deleted ? 1 : 2,
                            'contentid' => $thread_deleted ? $thread->threadid : $current_post->postid,
                            'affected_userid' => $current_post->userid,
                            'ip' => Auth::user()->lastip,
                            'dateline' => time()
                        ]);
                    }
                }

                $run = true;
                $fmrid = $thread->forumid;
                $threads_deleted = $thread_deleted ? 1 : 0;
                while ($run) {
                    $forum = DB::table('forums')->where('forumid', $fmrid)->first();

                    if (count($forum)) {
                        DB::table('forums')->where('forumid', $fmrid)->update([
                            'posts' => $forum->posts - count($postids),
                            'threads' => $forum->threads - $threads_deleted
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

                if ($thread_deleted) {
                    $posts = DB::table('posts')->where('threadid', $thread->threadid)->where('visible', 1)->get();

                    foreach ($posts as $post) {
                        DB::table('users')->where('userid', $post->userid)->update([
                            'postcount' => DB::raw('postcount-1')
                        ]);
                    }

                    DB::table('users')->where('userid', $thread->postuserid)->update([
                        'threadcount' => DB::raw('threadcount-1')
                    ]);

                    if ($type == 0) {
                        DB::table('posts')->where('threadid', $thread->threadid)->update([
                            'visible' => 0
                        ]);
                    }
                }

                $response = true;
            }
        }

        DB::table('users')->where('threadcount', '<', 0)->update([
            'threadcount' => 0
        ]);

        DB::table('users')->where('postcount', '<', 0)->update([
            'postcount' => 0
        ]);

        return response()->json(array('success' => true, 'response' => $response, 'stay' => $stay));
    }
}
