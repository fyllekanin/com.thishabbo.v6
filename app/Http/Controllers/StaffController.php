<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\UserHelper;
use App\Helpers\ForumHelper;
use App\Helpers\StaffHelper;
use Image;
use File;
use Twitter;

class StaffController extends BaseController
{
    public function kickDJ()
    {
        $info = DB::table('radio_details')->orderBy('infoid', 'DESC')->first();
        if (count($info)) {
            $url = "http://". $info->ip . ":" . $info->port . "/admin.cgi?sid=1&mode=kicksrc";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_USERPWD, "admin:" . $info->admin_password);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36");
            curl_exec($ch);
            curl_close($ch);
            $response = true;
        }

        $dnas_data = StaffHelper::getRadioStats();
        $currentdj = 'Not Available';
        if ($dnas_data != null) {
            $currentdj = $dnas_data['SERVERTITLE'];
        }

        DB::table('radiokick_logs')->insert([
        'dj_kicked' => $currentdj,
        'userid' => Auth::user()->userid,
        'ipaddress' => $_SERVER['REMOTE_ADDR'],
        'dateline' => time()
      ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getKickDJ()
    {
        $dnas_data = StaffHelper::getRadioStats();
        $currentdj = 'Not Available';
        $currentlisteners = 'Not Available';
        if ($dnas_data != null) {
            $currentdj = $dnas_data['SERVERTITLE'];
            $currentlisteners = $dnas_data['CURRENTLISTENERS'];
        }

        $radio = DB::table('radio_stats')->first();
        $currentdjid = $radio->djid;

        $kicklogs = DB::table('radiokick_logs')->orderBy('id', 'DESC')->take(10)->get();

        $returnHTML = view('staff.extras.kickDJ')
      ->with('currentdj', $currentdj)
      ->with('currentdjid', $currentdjid)
      ->with('currentlisteners', $currentlisteners)
      ->with('kicklogs', $kicklogs)
      ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getTrialRadio()
    {
        $returnHTML = view('staff.extras.trialradio')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }


    public function getSearchUsers()
    {
        $returnHTML = view('staff.searchUsers')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getUserList($username, $pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 50;
        $skip = 0;

        if ($username != "all") {
            $pagesx = DB::table('users')->where('username', 'LIKE', '%' . $username . '%')->count();
        } else {
            $pagesx = DB::table('users')->count();
        }

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


        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($username != "all") {
            $temps = DB::table('users')->where('username', 'LIKE', '%' . $username . '%')->take($take)->skip($skip)->orderBy('username', 'ASC')->get();
        } else {
            $temps = DB::table('users')->take($take)->skip($skip)->orderBy('username', 'ASC')->get();
        }

        $users = array();
        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        foreach ($temps as $temp) {
            if (!in_array($temp->userid, UserHelper::getSuperAdmins()) || $myImmunity > UserHelper::getImmunity($temp->userid)) {
                $banned = false;
                $check = DB::table('users_banned')->where('userid', $temp->userid)->where(function ($query) {
                    $query->where('banned_until', '>', time())->orWhere('banned_until', '0');
                })->count();

                if ($check > 0) {
                    $banned = true;
                }

                $array = array(
              'username' => $temp->username,
              'bio' => $temp->bio,
              'signature' => $temp->signature,
              'avatar' => UserHelper::getAvatar($temp->userid),
              'header' => $temp->profile_header > 0 ? asset('_assets/img/website/headers/' . $temp->profile_header . '.png') : asset('_assets/img/headers/' . $temp->userid . '.gif'),
              'userid' => $temp->userid,
              'lastactivity' => ForumHelper::timeAgo($temp->lastactivity),
              'banned' => $banned,
            );

                $users[] = $array;
            }
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/staff/mod/users/' . $username . '/page/')->render();

        $can_ban_user = false;
        $can_unban_user = false;

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 2)) {
            $can_ban_user = true;
        }

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 4)) {
            $can_unban_user = true;
        }

        $returnHTML = view('staff.userList')
    ->with('users', $users)
    ->with('pagi', $pagi)
    ->with('can_ban_user', $can_ban_user)
    ->with('can_unban_user', $can_unban_user)
    ->with('searched', $username)
    ->with('current_page', $pagenr)
    ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postUpdateAvailability(Request $request)
    {
        $articleid = $request->input('articleid');
        $available = $request->input('available');

        DB::table('articles')->where('articleid', $articleid)->update([
            'available' => $available
        ]);

        return response()->json(array('success' => true));
    }

    public function closeFlagged(Request $request)
    {
        $flagid = $request->input('flagid');
        DB::table('flaged_articles')->where('flagid', $flagid)->update(['handled' => 1]);

        DB::table('mod_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Closed flagged article',
            'content' => 7,
            'contentid' => $flagid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function handleFlagged(Request $request)
    {
        $flagid = $request->input('flagid');
        $flag = DB::table('flaged_articles')->where('flagid', $flagid)->first();
        if (count($flag)) {
            $article = DB::table('articles')->where('articleid', $flag->articleid)->first();
            if (count($article)) {
                DB::table('flaged_articles')->where('flagid', $flagid)->update(['handled' => 1]);
                $new_avil = $article->available == $flag->type ? ($article->available == 1 ? 2 : 1) : $article->available;
                DB::table('articles')->where('articleid', $flag->articleid)->update(['available' => $new_avil]);
                DB::table('mod_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Handled flagged article',
                    'content' => 7,
                    'contentid' => $flagid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip,
                    'dateline' => time()
                ]);
            }
        }

        return response()->json(array('success' => true));
    }

    public function getFlaggedArticles()
    {
        $temps = DB::table('flaged_articles')->where('handled', 0)->orderBy('flagid', 'ASC')->get();

        $flagged_articles = array();
        foreach ($temps as $temp) {
            $article = DB::table('articles')->where('articleid', $temp->articleid)->where('available', $temp->type)->first();
            if (count($article)) {
                $flagged_articles[] = array(
                    'articleid' => $temp->articleid,
                    'title' => $article->title,
                    'reason' => $temp->reason,
                    'type' => $temp->type,
                    'flagid' => $temp->flagid
                );
            } else {
                DB::table('flagged_articles')->where('articleid', $temp->articleid)->where('type', $temp->type)->update(['handled' => 1]);
            }
        }

        $returnHTML = view('staff.articles.flagged')
        ->with('flagged_articles', $flagged_articles)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getSearchSimUsers()
    {
        $returnHTML = view('staff.searchIPs')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getUsersUsingSameIp($search)
    {
        if ($search != "all") {
            $temps = DB::table('users')->where('lastip', 'LIKE', $search)->orderBy('username', 'ASC')->get();
        } else {
            $temps = DB::table('users')->where('lastip', 'LIKE', '::1')->orderBy('username', 'ASC')->get();
        }

        $users = array();
        $can_ban_user = false;
        $can_unban_user = false;

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 2)) {
            $can_ban_user = true;
        }

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 4)) {
            $can_unban_user = true;
        }

        foreach ($temps as $temp) {
            if (!UserHelper::haveAdminPerm($temp->userid, 1)) {
                $banned = false;
                $check = DB::table('users_banned')->where('banned_until', '>', time())->orWhere('banned_until', '=', '0')->where('userid', $temp->userid)->count();

                if ($check > 10) {
                    $banned = true;
                }

                $array = array(
            'username' => $temp->username,
            'lastactivity' => ForumHelper::timeAgo($temp->lastactivity),
            'banned' => $banned,
            'userid' => $temp->userid,
            'lastip' => $temp->lastip,
            'loginip' => $temp->loginip
          );

                $users[] = $array;
            }
        }

        $returnHTML = view('staff.usersip')
    ->with('users', $users)
    ->with('can_ban_user', $can_ban_user)
    ->with('can_unban_user', $can_unban_user)
    ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioAnalytics($days){
        $endtimestamp = strtotime('today 05:00:00');
        if($endtimestamp > time()){
            $endtimestamp = strtotime('yesterday 05:00:00');
        }
        $starttimestamp = $endtimestamp - (86400*$days);


        $skip = 0;

        $chartData = array();
        while($starttimestamp < $endtimestamp) {
            $OCAve = DB::table('radio_logs')->where('dateline','>',$starttimestamp)->where('dateline','<',$starttimestamp+28800)->avg('listeners');
            $starttimestamp += 28800;
            $EUAve = DB::table('radio_logs')->where('dateline','>',$starttimestamp)->where('dateline','<',$starttimestamp+28800)->avg('listeners');
            $starttimestamp += 28800;
            $NAAve = DB::table('radio_logs')->where('dateline','>',$starttimestamp)->where('dateline','<',$starttimestamp+28800)->avg('listeners');
            $starttimestamp += 28800;

            $chartData[] = [date('d/m',$starttimestamp-28800),$OCAve, $EUAve, $NAAve];
        }

        return response()->json(array('success' => true, 'response' => $chartData));
    }

    public function getRadioAnalyticsPage(){
        $returnHTML = view('staff.radioAnalytics')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postSaveRadioInfo(Request $request)
    {
        $ip = $request->input('ip');
        $port = $request->input('port');
        $password = $request->input('password');
        $admin_password = $request->input('admin_password');

        DB::table('radio_details')->insert([
      'ip' => $ip,
      'port' => $port,
      'password' => $password,
      'admin_password' => $admin_password,
      'userid' => Auth::user()->userid,
      'dateline' => time()
    ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getDjSays()
    {
        $time = date('G');
        $day = date('N');

        $after_time = $time+1;
        $after_day = $day;

        if ($after_time > 23) {
            $after_time -= 24;
            $after_day += 1;
            if ($after_day > 7) {
                $after_day -= 7;
            }
        }

        $check1 = DB::table('timetable')->where('userid', Auth::user()->userid)->where('day', $day)->where('time', $time)->count();
        $check2 = DB::table('timetable')->where('userid', Auth::user()->userid)->where('day', $after_day)->where('time', $after_time)->count();

        if ($check1 > 0 || $check2 > 0 || UserHelper::haveStaffPerm(Auth::user()->userid, 16)) {
            $can_access = true;
        } else {
            $can_access = false;
        }

        $djsays = DB::table('djsays')->orderBy('dateline', 'DESC')->first();
        $djname = UserHelper::getUsername($djsays->djid);
        $djmessage = $djsays->message;

        $returnHTML = view('staff.extras.djSays')
        ->with('djname', $djname)
        ->with('djmessage', $djmessage)
        ->with('can_access', $can_access)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postdjSays(Request $request)
    {
        $djid = Auth::user()->userid;
        $message = $request->input('message');

        $messagedit = ForumHelper::fixContent($message);

        DB::table('djsays')->insert([
            'djid' => $djid,
            'message' => $messagedit,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getLiveStats()
    {
        $dnas_data = StaffHelper::getRadioStats();
        $streamstatus = '<font color="#ff0000">Unable to connect to server!</font>';
        $bitrate = '0';
        $listeners = '0';
        $uniquelisteners = '0';
        $listenerpeak= '0';
        $dj = 'N/A';
        $genre = 'N/A';
        $song = 'N/A';

        if ($dnas_data != null) {
            $streamstatus = $dnas_data['STREAMSTATUS'];
            $bitrate = $dnas_data['BITRATE'];
            $listeners = $dnas_data['CURRENTLISTENERS'];
            $uniquelisteners = $dnas_data['UNIQUELISTENERS'];
            $listenerpeak = $dnas_data['PEAKLISTENERS'];
            $dj = $dnas_data['SERVERTITLE'];
            $genre = $dnas_data['SERVERGENRE'];
            $song = $dnas_data['SONGTITLE'];
        }

        if ($streamstatus == "1") {
            $streamstatus = '<font color="#008000">Stream is currently up and running!</font>';
        } elseif ($streamstatus == "0") {
            $streamstatus = '<font color="#ff0000">There is no DJ on air!</font>';
        }

        $returnHTML = view('staff.extras.liveStats')
        ->with('streamstatus', $streamstatus)
        ->with('bitrate', $bitrate)
        ->with('listeners', $listeners)
        ->with('uniquelisteners', $uniquelisteners)
        ->with('listenerpeak', $listenerpeak)
        ->with('dj', $dj)
        ->with('genre', $genre)
        ->with('song', $song)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getManageRadio()
    {
        $current_info = DB::table('radio_details')->orderBy('infoid', 'DESC')->first();

        $ip = $current_info->ip;
        $port = $current_info->port;
        $password = $current_info->password;
        $admin_password = $current_info->admin_password;

        $latest_changes = DB::table('radio_details')->orderBy('infoid', 'DESC')->take(5)->get();
        $returnHTML = view('staff.manageRadio')
    ->with('current_info', $current_info)
    ->with('ip', $ip)
    ->with('port', $port)
    ->with('password', $password)
    ->with('admin_password', $admin_password)
    ->with('latest_changes', $latest_changes)
    ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getApprovingCreations()
    {
        $temps = DB::table('creations')->where('approved', '0')->orderBy('creationid', 'ASC')->get();
        $creations = array();

        foreach ($temps as $temp) {
            $array = array(
                'username' => UserHelper::getUsername($temp->userid, true),
                'name' => $temp->name,
                'image' => asset('_assets/img/creations/' . $temp->creationid . '.gif'),
                'creationid' => $temp->creationid
            );

            $creations[] = $array;
        }

        $returnHTML = view('staff.creations')
        ->with('creations', $creations)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postAddEvent(Request $request)
    {
        $title = $request->input('title');

        $check = DB::table('event_types')->where('event', 'LIKE', $title)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Already a event with that name'));
        } elseif (strlen($title) <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Event name can\'t be empty!'));
        }

        $eventid = DB::table('event_types')->insertGetId([
            'event' => $title
        ]);

        if ($request->hasFile('thumbnail') and $request->file('thumbnail')->isValid()) {
            UserHelper::saveAnimatedImage($request->file('thumbnail'), $eventid, '_assets/img/eventthumbnails/');
        }

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveEvent(Request $request)
    {
        $typeid = $request->input('typeid');

        DB::table('event_types')->where('typeid', $typeid)->delete();

        return response()->json(array('success' => true, 'response' => true));
    }

    public function listBannedUsers()
    {
        $temps = DB::table('users_banned')->orderBy('banned_at', 'DESC')->get();

        $bannedUsers = array();

        foreach ($temps as $temp) {
            $array = array(
                'userid' => $temp->userid,
                'username' => UserHelper::getUsername($temp->userid, true),
                'admin_name' => UserHelper::getUsername($temp->adminid, true),
                'banned_at' => ForumHelper::getTimeInDate($temp->banned_at),
                'banned_until' => ForumHelper::getTimeInDate($temp->banned_until, true),
                'banned_untilraw' => $temp->banned_until,
                'reason' => $temp->reason
            );

            $bannedUsers[] = $array;
        }

        $can_unban_user = false;

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 4)) {
            $can_unban_user = true;
        }

        $returnHTML = view('staff.bannedUsers')
        ->with('bannedUsers', $bannedUsers)
        ->with('can_unban_user', $can_unban_user)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public static function cmp($a, $b)
	{
		if($a['points'] == $b['points'])
		{
			return 0;
		}
		if($a['points'] > $b['points'])
		{
			return -1;
		}

		return 1;
	}


    public function getIndex()
    {
        $userid = Auth::user()->userid;

        $events_booked = DB::table('timetable')->where('type', '=', '1')->count();
        $radio_booked = DB::table('timetable')->where('type', '=', '0')->count();
        $slots_booked = DB::table('timetable')->where('userid', $userid)->count();
        $quest_guides = DB::table('articles')->where('dateline', '>', '1546300800')->where('approved', '=', '1')->where('type', '=', '0')->count();

        $eu = 0;
        $na = 0;
        $oc = 0;
        $ts = DB::table('radiopoints')->get();
        foreach ($ts as $t) {
            switch ($t->region) {
                case 'EU':
                    $eu += $t->points;
                    break;
                case 'NA':
                    $na += $t->points;
                    break;
                case 'OC':
                    $oc += $t->points;
                    break;
            }
        }
        $eu = round($eu, 0);
        $na = round($na, 0);
        $oc = round($oc, 0);
        $radiopoints = [
            [ 'region' => 'EU', 'points' => $eu ],
            [ 'region' => 'NA', 'points' => $na ],
            [ 'region' => 'OC', 'points' => $oc ]
        ];
        usort($radiopoints, array('self', 'cmp'));

        $returnHTML = view('staff.index')
            ->with('events_booked', $events_booked)
            ->with('radio_booked', $radio_booked)
            ->with('slots_booked', $slots_booked)
            ->with('quest_guides', $quest_guides)
            ->with('radiopoints', $radiopoints)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function saveChangeRegion(Request $request)
    {
        $region = $request->input('region');

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'region' => $region
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getChangeRegion()
    {
        $returnHTML = view('staff.changeRegion')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function saveTHCRequest(Request $request)
    {
        $usernamereq = $request->input('username');
        $thcrequest = $request->input('thcrequest');
        $reason = $request->input('reason');
        $furtherreason = $request->input('furtherreason');
        $type = $request->input('reqtype');

        if($furtherreason != '') {
            $furtherreason = ' - ' . $furtherreason;
        }

        if ($type === 'Habbo') {
            $username = DB::table('users')->Where('habbo', $usernamereq)->first();
        } elseif ($type === 'Forum') {
            $username = DB::table('users')->where('username', $usernamereq)->first();
        }

        if ($thcrequest >= '1') {
            if (!count($username)) {
                return response()->json(array('success' => true, 'error' => 1, 'field' => "username", 'message' => 'Username or Habbo does not exists!'));
            } else {
                DB::table('thc_requests')->insert([
                    'userid' => $username->userid,
                    'thc' => $thcrequest,
                    'reason' => $reason . '' . $furtherreason,
                    'requestor' => Auth::user()->userid,
                    'dateline' => time(),
                ]);

                return response()->json(array('success' => true, 'response' => true));
            }
        } else {
            return response()->json(array('success' => true, 'error' => 1, 'field' => "thcrequest", 'message' => 'THC must be for 1 or more!'));
        }
    }

    public function getRequestTHC()
    {
        $returnHTML = view('staff.requestTHC')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioTimetableLog($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = false;
        $username = '';
        $action = '';
        if (isset($_GET['username']) && $_GET['username'] != '') {
            $username = $_GET['username'];
            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($user)) {
                $userid = $user->userid;
                $username = '?username=' . $username;
            }
        }
        if (isset($_GET['action']) && $_GET['action'] != '') {
            $action = 'action = ' . $_GET['action'];
        } else {
            $action = 'action > 0';
        }

        $take = 30;
        $skip = ($pagenr-1) * $take;

        if ($userid) {
            $pagesx = DB::table('timetable_logs')->where('userid', $userid)->whereRaw($action)->count();
        } else {
            $pagesx = DB::table('timetable_logs')->where('type', '=', '0')->whereRaw($action)->count();
        }

        $pages = ceil($pagesx/$take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
          'total' => $pages,
          'current' => $pagenr,
          'previous' => ($pagenr-1 <= 0 ? 1 : $pagenr-1) . $username,
          'previous_exists' => $pagenr-1 < 1 ? false : true,
          'next' => ($pagenr+1 >$pages ? $pages : $pagenr+1) . $username,
          'next_exists' => $pagenr+1 > $pages ? false : true,
          'gap_forward' => $pagenr+5 < $pages ? true : false,
          'gap_backward' => $pagenr-5 > 1 ? true : false
        );

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/staff/logs/radio/page/')->render();

        if ($userid) {
            $temps = DB::table('timetable_logs')->where('type', '=', '0')->take($take)->whereRaw($action)->where('userid', $userid)->skip($skip)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('timetable_logs')->where('type', '=', '0')->take($take)->whereRaw($action)->skip($skip)->orderBy('dateline', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $action = "No Idea";
            $contentid = "Not applicable";

            switch ($temp->action) {
            case 1:
              $action = "<font color=\"green\">Booked Slot</font>";
            break;
            case 2:
              $action = "<font color=\"red\">Unbooked Slot</font>";
            break;
          }

            $day = "No Idea";
            switch ($temp->day) {
            case 1:
              $day = "Monday";
            break;
            case 2:
              $day = "Tuesday";
            break;
            case 3:
              $day = "Wednesday";
            break;
            case 4:
              $day = "Thursday";
            break;
            case 5:
              $day = "Friday";
            break;
            case 6:
              $day = "Saturday";
            break;
            case 7:
              $day = "Sunday";
            break;
          }

            $time = $temp->time;


            $array = array(
            'action_username' => UserHelper::getUsername($temp->userid),
            'action' => $action,
            'day' => $day,
            'time' => $time,
            'affected_user' => UserHelper::getUsername($temp->affected_userid),
            'dateline' => ForumHelper::getTimeInDate($temp->dateline),
          );

            $logs[] = $array;
        }

        $returnHTML = view('staff.logs.radioTimetable')
        ->with('logs', $logs)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventsTimetableLog($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = false;
        $username = '';
        $action = '';
        if (isset($_GET['username']) && $_GET['username'] != '') {
            $username = $_GET['username'];
            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($user)) {
                $userid = $user->userid;
                $username = '?username=' . $username;
            }
        }
        if (isset($_GET['action']) && $_GET['action'] != '') {
            $action = 'action = ' . $_GET['action'];
        } else {
            $action = 'action > 0';
        }

        $take = 30;
        $skip = ($pagenr-1) * $take;

        if ($userid) {
            $pagesx = DB::table('timetable_logs')->where('userid', $userid)->whereRaw($action)->count();
        } else {
            $pagesx = DB::table('timetable_logs')->where('type', '=', '1')->whereRaw($action)->count();
        }

        $pages = ceil($pagesx/$take);

        if ($pagenr > $pages) {
            $pagenr = $pages;
        }

        $paginator = array(
          'total' => $pages,
          'current' => $pagenr,
          'previous' => ($pagenr-1 <= 0 ? 1 : $pagenr-1) . $username,
          'previous_exists' => $pagenr-1 < 1 ? false : true,
          'next' => ($pagenr+1 >$pages ? $pages : $pagenr+1) . $username,
          'next_exists' => $pagenr+1 > $pages ? false : true,
          'gap_forward' => $pagenr+5 < $pages ? true : false,
          'gap_backward' => $pagenr-5 > 1 ? true : false
        );

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/staff/logs/events/page/')->render();

        if ($userid) {
            $temps = DB::table('timetable_logs')->where('type', '=', '1')->take($take)->whereRaw($action)->where('userid', $userid)->skip($skip)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('timetable_logs')->where('type', '=', '1')->take($take)->whereRaw($action)->skip($skip)->orderBy('dateline', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $action = "No Idea";
            $contentid = "Not applicable";

            switch ($temp->action) {
            case 1:
              $action = "<font color=\"green\">Booked Slot</font>";
            break;
            case 2:
              $action = "<font color=\"red\">Unbooked Slot</font>";
            break;
          }

            $day = "No Idea";
            switch ($temp->day) {
            case 1:
              $day = "Monday";
            break;
            case 2:
              $day = "Tuesday";
            break;
            case 3:
              $day = "Wednesday";
            break;
            case 4:
              $day = "Thursday";
            break;
            case 5:
              $day = "Friday";
            break;
            case 6:
              $day = "Saturday";
            break;
            case 7:
              $day = "Sunday";
            break;
          }

            $time = $temp->time;


            $array = array(
            'action_username' => UserHelper::getUsername($temp->userid),
            'action' => $action,
            'day' => $day,
            'time' => $time,
            'affected_user' => UserHelper::getUsername($temp->affected_userid),
            'dateline' => ForumHelper::getTimeInDate($temp->dateline),
          );

            $logs[] = $array;
        }

        $returnHTML = view('staff.logs.eventsTimetable')
        ->with('logs', $logs)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventTypes()
    {
        $event_types = DB::table('event_types')->orderBy('event', 'ASC')->get();

        $returnHTML = view('staff.eventTypes')
        ->with('event_types', $event_types)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postCloseJob(Request $request)
    {
        $jobid = $request->input('jobid');
        DB::table('jobs')->where('jobid', $jobid)->update(['open' => 0]);

        return response()->json(array('success' => true));
    }

    public function postOpenJob(Request $request)
    {
        $jobid = $request->input('jobid');
        DB::table('jobs')->where('jobid', $jobid)->update(['open' => 1]);

        return response()->json(array('success' => true));
    }

    public function postDeleteJob(Request $request)
    {
        $jobid = $request->input('jobid');
        DB::table('jobs')->where('jobid', $jobid)->delete();

        return response()->json(array('success' => true));
    }

    public function postAddJob(Request $request)
    {
        $name = $request->input('name');
        $topic = $request->input('topic');
        $display = $request->input('display');
        $description = $request->input('description');

        $response = false;
        $message = "";

        if (strlen($name) > 0) {
            if (strlen($description) > 0) {
                if (!is_numeric($display)) {
                    $display = 1;
                }

                DB::table('jobs')->insert([
                    'name' => $name,
                    'display' => $display,
                    'topic' => $topic,
                    'description' => $description,
                    'open' => 1
                ]);

                $response = true;
            } else {
                $message = "Text can't be empty!";
            }
        } else {
            $message = "Can't have empty name!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function getJobs()
    {
        $temps = DB::table('jobs')->orderBy('display', 'ASC')->get();

        $jobs = array();

        foreach ($temps as $temp) {
            $name = str_replace(">", "&#62;", $temp->name);
            $name = str_replace("<", "&#60;", $name);

            $description = str_replace(">", "&#62;", $temp->description);
            $description = str_replace("<", "&#60;", $description);

            $description = ForumHelper::bbcodeParser($description);

            $description = nl2br($description);

            $array = array(
                'name' => $name,
                'jobid' => $temp->jobid,
                'description' => $description,
                'open' => $temp->open,
                'topic' => $temp->topic,
            );

            $jobs[] = $array;
        }

        $can_delete_add_jobs = false;

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 131072)) {
            $can_delete_add_jobs = true;
        }

        $returnHTML = view('staff.jobs')
        ->with('jobs', $jobs)
        ->with('can_delete_add_jobs', $can_delete_add_jobs)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function infractionLog($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 30;
        $skip = ($pagenr-1) * $take;

        $pagesx = DB::table('infraction')->count();
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

        $temps = DB::table('infraction')->take($take)->skip($skip)->orderBy('dateline', 'DESC')->get();

        $logs = array();

        foreach ($temps as $temp) {
            $type = "No Idea";
            $contentid = "Not applicable";

            switch ($temp->type) {
            case 0:
              $type = "Warning";
            break;
            case 1:
              $type = "Infraction";
            break;
            case 2:
              $type = "Verbal Warning";
            break;
          }

            $reason = $temp->infractionlevelid;
            $tms = DB::table('infraction_reasons')->where('infractionrsnid', $reason)->first();
            $reason = $tms->text;


            $array = array(
            'id' => $temp->infractionid,
            'action_username' => UserHelper::getUsername($temp->whoadded),
            'type' => $type,
            'points' => $temp->points,
            'reason' => $reason,
            'affected_user' => UserHelper::getUsername($temp->userid),
            'dateline' => ForumHelper::getTimeInDate($temp->dateline),
          );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/staff/mod/infractions/page/')->render();

        $returnHTML = view('staff.logs.infractionLog')
        ->with('logs', $logs)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function deleteImage($galleryid)
    {
        DB::table('gallery')->where('galleryid', $galleryid)->delete();

        File::delete('_assets/img/gallery/' . $galleryid . '.gif');

        return response()->json(array('success' => true));
    }

    public function getGallery($pagenr, $search = false)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 30;
        $skip = 0;

        if (!$search) {
            $pagesx = DB::table('gallery')->count();
        } else {
            $pagesx = DB::table('gallery')->where('tags', 'LIKE', '%' . strtolower($search) . '%')->count();
        }

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

        if (!$search) {
            $temps = DB::table('gallery')->take($take)->skip($skip)->orderBy('galleryid', 'DESC')->get();
        } else {
            $temps = DB::table('gallery')->where('tags', 'LIKE', '%' . strtolower($search) . '%')->take($take)->skip($skip)->orderBy('galleryid', 'DESC')->get();
        }

        $images = array();

        foreach ($temps as $temp) {
            $username = "System";

            $user = DB::table('users')->where('userid', $temp->userid)->first();

            if (count($user)) {
                $username = $user->username;
            }

            $array = array(
                'url' => asset('_assets/img/gallery/' . $temp->galleryid . '.gif'),
                'time' => ForumHelper::timeAgo($temp->dateline),
                'tags' => $temp->tags,
                'username' => $username,
                'galleryid' => $temp->galleryid
            );

            $images[] = $array;
        }

        $can_delete_others_grahics = false;

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 2048)) {
            $can_delete_others_grahics = true;
        }

        $add_after = "";
        $add_to_search = "";

        if ($search) {
            $add_after = "/search/" . $search;
            $add_to_search = $search;
        }

        $returnHTML = view('staff.gallery')
        ->with('images', $images)
        ->with('can_delete_others_grahics', $can_delete_others_grahics)
        ->with('add_after', $add_after)
        ->with('paginator', $paginator)
        ->with('add_to_search', $add_to_search)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postUploadImage(Request $request)
    {
        $response = false;

        if ($request->hasFile('image') and $request->file('image')->isValid()) {
            $img = Image::make($request->file('image'));

            $tags = explode(",", $request->input('tags'));

            $string_tags = "";
            $first = true;

            foreach ($tags as $tag) {
                if ($first) {
                    $string_tags = $tag;
                    $first = false;
                } else {
                    $string_tags = $string_tags . ',' . $tag;
                }
            }

            $galleryid = DB::table('gallery')->insertGetId([
                'userid' => Auth::user()->userid,
                'dateline' => time(),
                'tags' => $string_tags
            ]);

            $img->save('_assets/img/gallery/' . $galleryid . '.gif', 60);


            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getUploadImage()
    {
        $returnHTML = view('staff.uploadImage')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditArticle($articleid)
    {
        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if (count($article)) {
            if ($article->userid == Auth::user()->userid or UserHelper::haveStaffPerm(Auth::user()->userid, 256)) {
                $returnHTML = view('staff.extras.editArticle')->with('article', $article)->render();

                return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
            }
        }

        return redirect()->route('getErrorPerm');
    }

    public function postDeleteArticle(Request $request)
    {
        $articleid = $request->input('articleid');

        DB::table('articles')->where('articleid', $articleid)->delete();

        return response()->json(array('success' => true));
    }

    public function postApproveArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if ($article->type == 0) {
            $users = DB::table('users')->where('extras', '=>', 1)->get();

            foreach ($users as $user) {
                DB::table('notifications')->insert([
            'postuserid' => $article->userid,
            'reciveuserid' => $user->userid,
            'content' => 8,
            'contentid' => $article->articleid,
            'dateline' => time(),
            'read_at' => 0
          ]);
            }

            // $users = DB::table('habbo_badges')->where('badge_name', $article->badge_code)->value("subscribed_userids");
        // $users = explode(',',$users);

        // foreach($users as $user) {
        //   DB::table('notifications')->insert([
         //     'postuserid' => $article->userid,
          //   'reciveuserid' => $user->userid,
         //     'content' => 8,
         //     'contentid' => $article->articleid,
         //     'dateline' => time(),
         //     'read_at' => 0
         //  ]);
        // }
        }

        DB::table('articles')->where('articleid', $articleid)->update([
            'approved' => 1,
            'dateline' => time()
        ]);

        $type = "No type";

        switch ($article->type) {
        case 0:
          $type = 'Quest Guide';
        break;
        case 1:
          $type = 'News Article';
        break;
        case 2:
          $type = 'Wired Guide';
        break;
        case 3:
          $type = 'Tips & Tricks';
        break;
      }

        return Twitter::postTweet(['status' => "[$type] $article->title https://thishabbo.com/article/$article->articleid #Habbo", 'format' => 'json']);
        return response()->json(array('success' => true));
    }

    public function postSilentApproveArticle(Request $request)
    {
        $articleid = $request->input('articleid');
        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if ($article->type == 0) {
            $users = DB::table('users')->where('extras', '=>', 1)->get();

            foreach ($users as $user) {
                DB::table('notifications')->insert([
              'postuserid' => $article->userid,
              'reciveuserid' => $user->userid,
              'content' => 8,
              'contentid' => $article->articleid,
              'dateline' => time(),
              'read_at' => 0
            ]);
            }

            // $users = DB::table('habbo_badges')->where('badge_name', $article->badge_code)->value("subscribed_userids");
          // $users = explode(',',$users);

          // foreach($users as $user) {
          //   DB::table('notifications')->insert([
           //     'postuserid' => $article->userid,
            //   'reciveuserid' => $user->userid,
           //     'content' => 8,
           //     'contentid' => $article->articleid,
           //     'dateline' => time(),
           //     'read_at' => 0
           //  ]);
          // }
        }

        DB::table('articles')->where('articleid', $articleid)->update([
              'approved' => 1,
              'dateline' => time()
          ]);

        return response()->json(array('success' => true));
    }

    public function postDeproveArticle(Request $request)
    {
        $articleid = $request->input('articleid');

        DB::table('articles')->where('articleid', $articleid)->update(['approved' => 0]);

        return response()->json(array('success' => true));
    }

    public function getManageArticles($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 30;
        $skip = ($pagenr-1) * $take;

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 256)) {
            $pagesx = DB::table('articles')->count();
        } else {
            $pagesx = DB::table('articles')->where('userid', Auth::user()->userid)->count();
        }

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

        $can_manage_articles = false;

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 256)) {
            $can_manage_articles = true;
            $temps = DB::table('articles')->orderBy('dateline', 'DESC')->take($take)->skip($skip)->get();
        } else {
            $temps = DB::table('articles')->where('userid', Auth::user()->userid)->orderBy('dateline', 'DESC')->take($take)->skip($skip)->get();
        }

        $articles = array();


        foreach ($temps as $temp) {
            $type = "No type";

            switch ($temp->type) {
                case 0:
                    $type = 'Quest';
                break;
                case 1:
                    $type = 'News';
                break;
                case 2:
                    $type = 'Wired';
                break;
                case 3:
                    $type = 'Tips';
                break;
            }

            $thumbnail = asset('_assets/img/thumbnails/default.gif?3');
            $path = asset('_assets/img/thumbnails/' . $temp->articleid . '.gif');
            if (file_exists('_assets/img/thumbnails/' . $temp->articleid . '.gif')) {
                $thumbnail = asset('_assets/img/thumbnails/' . $temp->articleid . '.gif?3');
            }

            $title = str_replace(">", "&#62;", $temp->title);
            $title = str_replace("<", "&#60;", $title);

            $approved = "No";
            $badge = false;

            if ($temp->type == 0 && $temp->badge_code != '') {
                $badge = true;
            }

            if ($temp->approved == 1) {
                $approved = "Yes";
            }

            $array = array(
                'articleid' => $temp->articleid,
                'userid' => $temp->userid,
                'title' => $title,
                'type' => $type,
                'time' => ForumHelper::timeAgo($temp->dateline),
                'thumbnail' => $thumbnail,
                'author' => UserHelper::getUsername($temp->userid),
                'approved' => $approved,
                'badge' => $badge,
                'badge_code' => $temp->badge_code
            );

            $articles[] = $array;
        }

        $returnHTML = view('staff.listArticles')
        ->with('articles', $articles)
        ->with('paginator', $paginator)
        ->with('can_manage_articles', $can_manage_articles)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditArticle(Request $request)
    {
        $title = $request->input('title');
        $type = $request->input('type');
        $content = $request->input('content');
        $badge = $request->input('badge');
        $articleid = $request->input('articleid');
        $roomlink = $request->input('roomlink');
        $availability = $request->input('availability');
        $difficulty = $request->input('difficulty');
        $paid = $request->input('paid');

        $message = "";
        $field = "";
        $response = false;
        $gotfile = false;

        $article = DB::table('articles')->where('articleid', $articleid)->first();

        if ($article->userid == Auth::user()->userid or UserHelper::haveStaffPerm(Auth::user()->userid, 256)) {
            if ($request->hasFile('thumbnail') and $request->file('thumbnail')->isValid()) {
                $img = Image::make($request->file('thumbnail'));
                $gotfile = true;
            }


            if ($title != "") {
                if ($type < 4 or $type >= 0) {
                    if ($content != "") {
                        $roomlink = preg_replace('/[^\d]+/', '', $roomlink);

                        DB::table('articles')->where('articleid', $articleid)->update([
                            'title' => $title,
                            'content' => $content,
                            'type' => $type,
                            'badge_code' => $badge,
                            'available' => $availability,
                            'difficulty' => $difficulty,
                            'paid' => $paid,
                            'room_link' => $roomlink
                        ]);

                        if ($gotfile) {
                            $img->save('_assets/img/thumbnails/' . $articleid . '.gif', 60);
                        }
                        $response = true;
                    } else {
                        $message = "Content can't be empty!";
                    }
                } else {
                    $message = "Type is not valid!";
                    $field = "article-form-type";
                }
            } else {
                $message = "Title can't be empty!";
                $field = "article-form-title";
            }
        } else {
            $message = "Can't find article!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'field' => $field));
    }

    public function postAddArticle(Request $request)
    {
        $title = $request->input('title');
        $type = $request->input('type');
        $content = $request->input('content');
        $badge = $request->input('badge');
        $roomlink = $request->input('roomlink');
        $availability = $request->input('availability');
        $difficulty = $request->input('difficulty');
        $paid = $request->input('paid');

        $message = "";
        $field = "";
        $response = false;
        $gotfile = false;

        if (!$request->hasFile('thumbnail') or ($request->hasFile('thumbnail') and !$request->file('thumbnail')->isValid())) {
            $message = "Thumbnail is not a valid file!";
            return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'field' => $field));
        } else {
            $img = Image::make($request->file('thumbnail'));
            $gotfile = true;
        }


        if ($title != "") {
            if ($type < 4 or $type >= 0) {
                if ($content != "") {
                    $roomlink = preg_replace('/[^\d]+/', '', $roomlink);

                    $articleid = DB::table('articles')->insertGetId([
                        'title' => $title,
                        'content' => $content,
                        'userid' => Auth::user()->userid,
                        'dateline' => time(),
                        'type' => $type,
                        'badge_code' => $badge,
                        'available' => $availability,
                        'difficulty' => $difficulty,
                        'paid' => $paid,
                        'room_link' => $roomlink
                    ]);

                    if ($gotfile) {
                        $img->save('_assets/img/thumbnails/' . $articleid . '.gif', 60);
                    }
                    $response = true;
                } else {
                    $message = "Content can't be empty!";
                }
            } else {
                $message = "Type is not valid!";
                $field = "article-form-type";
            }
        } else {
            $message = "Title can't be empty!";
            $field = "article-form-title";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'field' => $field));
    }

    public function getAddArticle()
    {
        $returnHTML = view('staff.newArticle')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
    public function getAddBadge()
    {
        $returnHTML = view('staff.newBadgeArticle')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
    public function getAddBundle()
    {
        $returnHTML = view('staff.newBundleArticle')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
    public function getAddRare()
    {
        $returnHTML = view('staff.newRareArticle')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function editPerm(Request $request)
    {
        $username = $request->input('username');
        $type = $request->input('type');
        $day = $request->input('day');
        $time = $request->input('time');
        $timetableid = $request->input('timetableid');

        $message = "";
        $response = false;
        $field = "";

        $timetable = DB::table('timetable')->where('timetableid', $timetableid)->first();

        if (!count($timetable)) {
            $message = "Can't find slot!";
            $field = "all";
        }

        if ($type > 2 or $type < 0) {
            $message = "Invalid type!";
            $field = "perm-form-type";
        }

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (!count($user)) {
            $message = "User could not be found!";
            $field = "perm-form-username";
        }

        if ($day > 7 or $day < 1) {
            $message = "Invalid day";
            $field = "perm-form-day";
        }

        if ($time > 23 or $time < 0) {
            $message = "Invalid hour!";
            $field = "perm-form-hour";
        }

        if ($message != "" and $field != "") {
            return response()->json(array('success' => true, 'response' => $response, 'field' => $field, 'message' => $message));
        }

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $time += $timezone->value;
            } else {
                $time -= $timezone->value;
            }

            if ($time > 23) {
                $time -= 24;
                $day += 1;
            } elseif ($time < 0) {
                $time += 24;
                $day -= 1;
            }

            if ($day > 7) {
                $day -= 7;
            } elseif ($day < 1) {
                $day += 7;
            }
        }

        DB::table('timetable')->where('timetableid', $timetableid)->update([
            'userid' => $user->userid,
            'day' => $day,
            'time' => $time,
            'type' => $type,
            'perm' => 1,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditPerm($timetableid)
    {
        $timetable = DB::table('timetable')->where('timetableid', $timetableid)->first();

        if (count($timetable)) {
            $day = $timetable->day;
            $time = $timetable->time;
            $type = $timetable->type;
            $timetableid = $timetable->timetableid;

            $user = DB::table('users')->where('userid', $timetable->userid)->first();
            $username = "Not found";
            if (count($user)) {
                $username = $user->username;
            }

            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $time -= $timezone->value;
                } else {
                    $time += $timezone->value;
                }

                if ($time > 23) {
                    $time -= 24;
                    $day += 1;
                } elseif ($time < 0) {
                    $time += 24;
                    $day -= 1;
                }

                if ($day > 7) {
                    $day -= 7;
                } elseif ($day < 1) {
                    $day += 7;
                }
            }

            $returnHTML = view('staff.extras.editPerm')
            ->with('day', $day)
            ->with('time', $time)
            ->with('type', $type)
            ->with('timetableid', $timetableid)
            ->with('username', $username)
            ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function addPerm(Request $request)
    {
        $username = $request->input('username');
        $type = $request->input('type');
        $day = $request->input('day');
        $time = $request->input('time');

        $message = "";
        $response = false;
        $field = "";

        if ($type > 2 or $type < 0) {
            $message = "Invalid type!";
            $field = "perm-form-type";
        }

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (!count($user)) {
            $message = "User could not be found!";
            $field = "perm-form-username";
        }

        if ($day > 7 or $day < 1) {
            $message = "Invalid day";
            $field = "perm-form-day";
        }

        if ($time > 23 or $time < 0) {
            $message = "Invalid hour!";
            $field = "perm-form-hour";
        }

        if ($message != "" and $field != "") {
            return response()->json(array('success' => true, 'response' => $response, 'field' => $field, 'message' => $message));
        }

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $time += $timezone->value;
            } else {
                $time -= $timezone->value;
            }

            if ($time > 23) {
                $time -= 24;
                $day += 1;
            } elseif ($time < 0) {
                $time += 24;
                $day -= 1;
            }

            if ($day > 7) {
                $day -= 7;
            } elseif ($day < 1) {
                $day += 7;
            }
        }

        $check = DB::table('timetable')->where('type', $type)->where('time', $time)->where('day', $day)->count();

        if ($check > 0) {
            DB::table('timetable')->where('type', $type)->where('time', $time)->where('day', $day)->delete();
        }

        DB::table('timetable')->insert([
            'userid' => $user->userid,
            'day' => $day,
            'time' => $time,
            'type' => $type,
            'perm' => 1,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getAddPerm()
    {
        $returnHTML = view('staff.extras.addPerm')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function removePerm(Request $request)
    {
        $timetableid = $request->input('timetableid');

        DB::table('timetable')->where('timetableid', $timetableid)->delete();

        return response()->json(array('success' => true));
    }

    public function getPermShows()
    {
        $temps = DB::table('timetable')->where('perm', 1)->orderBy('type', 'ASC')->get();

        $perm_shows = array();

        foreach ($temps as $temp) {
            $day = $temp->day;
            $time = $temp->time;

            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $time -= $timezone->value;
                } else {
                    $time += $timezone->value;
                }

                if ($time > 23) {
                    $time -= 24;
                    $day += 1;
                } elseif ($time < 0) {
                    $time += 24;
                    $day -= 1;
                }

                if ($day > 7) {
                    $day -= 7;
                } elseif ($day < 1) {
                    $day += 7;
                }
            }

            switch ($day) {
                case 1:
                    $day = "Monday";
                break;
                case 2:
                    $day = "Tuesday";
                break;
                case 3:
                    $day = "Wednesday";
                break;
                case 4:
                    $day = "Thursday";
                break;
                case 5:
                    $day = "Friday";
                break;
                case 6:
                    $day = "Saturday";
                break;
                case 7:
                    $day = "Sunday";
                break;
            }

            switch ($time) {
                case 0:
                    $time = "12 AM";
                break;
                case 12:
                    $time = "12 PM";
                break;
                case 13:
                    $time = "1 PM";
                break;
                case 14:
                    $time = "2 PM";
                break;
                case 15:
                    $time = "3 PM";
                break;
                case 16:
                    $time = "4 PM";
                break;
                case 17:
                    $time = "5 PM";
                break;
                case 18:
                    $time = "6 PM";
                break;
                case 19:
                    $time = "7 PM";
                break;
                case 20:
                    $time = "8 PM";
                break;
                case 21:
                    $time = "9 PM";
                break;
                case 22:
                    $time = "10 PM";
                break;
                case 23:
                    $time = "11 PM";
                break;
                default:
                    $time = $time . ' AM';
                break;
            }

            $type = $temp->type == 0 ? "Radio" : "Event";

            $array = array(
                'username' => UserHelper::getUsername($temp->userid),
                'day' => $day,
                'time' => $time,
                'type' => $type,
                'timetableid' => $temp->timetableid
            );

            $perm_shows[] = $array;
        }

        $returnHTML = view('staff.permShows')
        ->with('perm_shows', $perm_shows)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioDetails()
    {
        $time = date('G');
        $day = date('N');

        $after_time = $time+1;
        $after_day = $day;

        if ($after_time > 23) {
            $after_time -= 24;
            $after_day += 1;
        }
        
        if ($after_day > 7) {
            $after_day -= 7;
        }

        $check1 = DB::table('timetable')->where('userid', Auth::user()->userid)->where('day', $day)->where('time', $time)->count();
        $check2 = DB::table('timetable')->where('userid', Auth::user()->userid)->where('day', $after_day)->where('time', $after_time)->count();

        $ip = "";
        $port = "";
        $password = "";

        if ($check1 > 0 || $check2 > 0 || UserHelper::haveStaffPerm(Auth::user()->userid, 16)) {
            $radio_details = DB::table('radio_details')->orderBy('infoid', 'desc')->first();

            if (count($radio_details)) {
                $ip = $radio_details->ip;
                $port = $radio_details->port;
                $password = $radio_details->password;
            }

            $can_access = true;
            DB::table('radio_details_logs')->insert([
                'userID' => Auth::user()->userid,
                'action' => 1,
                'ip'=> $_SERVER['REMOTE_ADDR'],
                'dateline' => time()
            ]);
        } else {
            $can_access = false;
            DB::table('radio_details_logs')->insert([
                'userID' => Auth::user()->userid,
                'action' => 2,
                'ip'=> $_SERVER['REMOTE_ADDR'],
                'dateline' => time()
            ]);
        }

        $returnHTML = view('staff.radioDetails')
        ->with('ip', $ip)
        ->with('port', $port)
        ->with('password', $password)
        ->with('can_access', $can_access)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function removeRequest(Request $request)
    {
        $requestid = $request->input('requestid');
        $request = DB::table('requests')->where('requestid', $requestid)->first();
        $response = false;

        if (count($request)) {
            if ($request->userid != Auth::user()->userid) {
                if (!UserHelper::haveStaffPerm(Auth::user()->userid, 8)) {
                    return response()->json(array('success' => true, 'response' => $response, 'message' => "You can't delete others requests!"));
                }
            }

            DB::table('requests')->where('requestid', $requestid)->delete();
            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function removeAllRequests()
    {
        $request = DB::table('requests')->first();

        if (!count($request) || !UserHelper::haveStaffPerm(Auth::user()->userid, 8192)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can\'t delete all requests'));
        }

        DB::table('requests')->truncate();
        return response()->json(array('success' => true, 'response' => true));
    }

    public function removeMyRequests()
    {
        DB::table('requests')->where('userid', Auth::user()->userid)->delete();
        $response = true;

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getRadioRequests($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 30;
        $skip = ($pagenr-1) * $take;

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 8)) {
            $pagesx = DB::table('requests')->count();
        } else {
            $pagesx = DB::table('requests')->where('userid', Auth::user()->userid)->count();
        }

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/staff/radio/request/page/')->render();

        if (UserHelper::haveStaffPerm(Auth::user()->userid, 8)) {
            $temps = DB::table('requests')->orderBy('requestid', 'DESC')->take($take)->skip($skip)->get();
        } else {
            $temps = DB::table('requests')->where('userid', Auth::user()->userid)->orderBy('requestid', 'DESC')->take($take)->skip($skip)->get();
        }

        $requests = array();

        foreach ($temps as $temp) {
            $dj = DB::table('users')->where('userid', $temp->userid)->first();

            if (count($dj)) {
                $dj_username = $dj->username;
            } else {
                $dj_username = "Auto DJ";
            }

            if ($temp->real_account > 0) {
                $user = DB::table('users')->where('userid', $temp->real_account)->first();

                if (count($user)) {
                    $username = $user->username;
                } else {
                    if ($temp->username != "") {
                        $username = $temp->username;
                    } else {
                        $username = "Anonymous";
                    }
                }
            } else {
                if ($temp->username != "") {
                    $username = $temp->username;
                } else {
                    $username = "Anonymous";
                }
            }

            $message = str_replace(">", "&#62;", $temp->message);
            $message = str_replace("<", "&#60;", $message);

            $array = array(
                'username' => $username,
                'djname' => $dj_username,
                'message' => $message,
                'time' => ForumHelper::timeAgo($temp->dateline),
                'requestid' => $temp->requestid,
                'avatar' => UserHelper::getAvatar($temp->real_account)
            );

            $requests[] = $array;
        }

        $returnHTML = view('staff.requests')
        ->with('requests', $requests)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function eventBook(Request $request)
    {
        $day = $request->input('day');
        $time = $request->input('time');
        $event = $request->input('event');
        $message = "";
        $response = false;

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $time += $timezone->value;
            } else {
                $time -= $timezone->value;
            }

            if ($time > 23) {
                $day += 1;
                $time -= 24;
            } elseif ($time < 0) {
                $day -= 1;
                $time += 24;
            }
        }

        $check = DB::table('timetable')->where('type', 1)->where('day', $day)->where('time', $time)->count();

        if ($check == 0) {
            $current_day = date('N');
            $current_time = date('G');

            $userid = Auth::user()->userid;
            if (UserHelper::haveStaffPerm(Auth::user()->userid, 67108864)) {
                $usr = $request->input('user');
                if ($usr != '') {
                    $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->first();
                    if (count($u)) {
                        $userid = $u->userid;
                    } else {
                        return response()->json(array('success' => true, 'response' => false, 'message' => 'user with that name don\'t exist!'));
                    }
                }
            }

            if ($day == '8') {
                $day = '1';
            }

            DB::table('timetable')->insert([
                'userid' => $userid,
                'day' => $day,
                'time'=> $time,
                'type' => 1,
                'perm' => 0,
                'event' => $event,
                'dateline' => time()
            ]);

            DB::table('timetable_logs')->insert([
                'userid' => Auth::user()->userid,
                'action' => 1,
                'type' => 1,
                'eventType' => $event,
                'day' => $day,
                'time' => $time,
                'affected_userid' => $userid,
                'dateline' => time()
            ]);

            $response = true;
        } else {
            $message = "Slot already booked!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function eventUnbook(Request $request)
    {
        $timetableid = $request->input('timetableid');
        $response = false;
        $message = "";

        $timetable = DB::table('timetable')->where('timetableid', $timetableid)->first();

        if (count($timetable)) {
            if ($timetable->userid != Auth::user()->userid) {
                if (!UserHelper::haveStaffPerm(Auth::user()->userid, 16777216)) {
                    return response()->json(array('success' => true, 'response' => false, 'message' => "You can't unbook someone elses slot!"));
                }
            }

            DB::table('timetable_logs')->insert([
                'userid' => Auth::user()->userid,
                'action' => 2,
                'type' => 1,
                'day' => $timetable->day,
                'time' => $timetable->time,
                'affected_userid' => $timetable->userid,
                'dateline' => time()
            ]);

            DB::table('timetable')->where('timetableid', $timetableid)->delete();
            $response = true;
        } else {
            $message = "This slot doesn't not exist!";
        }

        return response()->json(array('success' => true, 'response' => true, 'message' => $message));
    }

    public function radioUnbook(Request $request)
    {
        $timetableid = $request->input('timetableid');
        $response = false;
        $message = "";

        $timetable = DB::table('timetable')->where('timetableid', $timetableid)->first();

        if (count($timetable)) {
            if ($timetable->userid != Auth::user()->userid) {
                if (!UserHelper::haveStaffPerm(Auth::user()->userid, 4)) {
                    return response()->json(array('success' => true, 'response' => false, 'message' => "You can't unbook someone elses slot!"));
                }
            }

            DB::table('timetable_logs')->insert([
                'userid' => Auth::user()->userid,
                'action' => 2,
                'type' => 0,
                'day' => $timetable->day,
                'time' => $timetable->time,
                'affected_userid' => $timetable->userid,
                'dateline' => time()
            ]);

            DB::table('timetable')->where('timetableid', $timetableid)->delete();
            $response = true;
        } else {
            $message = "This slot doesn't not exist!";
        }

        return response()->json(array('success' => true, 'response' => true, 'message' => $message));
    }

    public function radioBook(Request $request)
    {
        $day = $request->input('day');
        $time = $request->input('time');
        $message = "";
        $response = false;

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $time += $timezone->value;
            } else {
                $time -= $timezone->value;
            }

            if ($time > 23) {
                $day += 1;
                $time -= 24;
            } elseif ($time < 0) {
                $day -= 1;
                $time += 24;
            }
        }

        $check = DB::table('timetable')->where('type', 0)->where('day', $day)->where('time', $time)->count();

        if ($check == 0) {
            $userid = Auth::user()->userid;
            if (UserHelper::haveStaffPerm(Auth::user()->userid, 67108864)) {
                $usr = $request->input('user');
                if ($usr != '') {
                    $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($usr)])->first();
                    if (count($u)) {
                        $userid = $u->userid;
                    } else {
                        return response()->json(array('success' => true, 'response' => false, 'message' => 'user with that name don\'t exist!'));
                    }
                }
            }
            $current_day = date('N');
            $current_time = date('G');

            if ($day == '8') {
                $day = '1';
            }

            DB::table('timetable')->insert([
                'userid' => $userid,
                'day' => $day,
                'time'=> $time,
                'type' => 0,
                'perm' => 0,
                'dateline' => time()
            ]);

            DB::table('timetable_logs')->insert([
                'userid' => Auth::user()->userid,
                'action' => 1,
                'type' => 0,
                'day' => $day,
                'time' => $time,
                'affected_userid' => $userid,
                'dateline' => time()
            ]);

            $response = true;
        } else {
            $message = "Slot already booked!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function getRadioTimetable($day = null)
    {
        $time = time();

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();
        $minus = 0;
        $plus = 0;

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $mn = 3600*$timezone->value;
                $minus = $timezone->value;
                $time -= $mn;
            } else {
                $pl = 3600*$timezone->value;
                $plus = $timezone->value;
                $time += $pl;
            }
        }

        if ($day == null) {
            $day = date('N', $time);
        }

        $timetable = array();

        $temps = DB::table('timetable')->where('type', 0)->get();

        foreach ($temps as $temp) {
            $temp_day = $temp->day;
            $temp_time = $temp->time;

            if ($minus > 0) {
                $temp_time -= $minus;

                if ($temp_time < 0) {
                    $temp_day -= 1;
                    $temp_time += 24;
                }
            } elseif ($plus > 0) {
                $temp_time += $plus;

                if ($temp_time > 23) {
                    $temp_day += 1;
                    $temp_time -= 24;
                }
            }

            if ($temp_day > 7) {
                $temp_day -= 7;
            } elseif ($temp_day < 1) {
                $temp_day += 7;
            }

            if ($temp_day == $day) {
                $perm = "";
                if ($temp->perm == 1) {
                    $perm = '<b style="color: #000000;">(Perm Show)</b>';
                }

                $username = UserHelper::getUsername($temp->userid, true);
                $region = '(' . UserHelper::getRegion($temp->userid) . ')';

                if (UserHelper::getRegion($temp->userid) == 'OC') {
                    $opentag = '<span style="color: #8174da; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == 'NA') {
                    $opentag = '<span style="color: #74A5DA; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == 'EU') {
                    $opentag = '<span style="color: #647DD4; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == '') {
                    $opentag = '<span style="">';
                    $closetag = '</span>';
                }

                if (UserHelper::haveStaffPerm(Auth::user()->userid, 4) or $temp->userid == Auth::user()->userid) {
                    $username = '<span onclick="unbookslot(' . $temp->timetableid . ');" class="book_slot">' . $username . '</span>';
                }

                $timetable[$temp_time] = array(
                    'timetableid' => $temp->timetableid,
                    'username' => $opentag . ' ' . $username . ' ' . $closetag . ' ' . $region . ' ' . $perm,
                );
            }
        }

        $can_book_for_others = UserHelper::haveStaffPerm(Auth::user()->userid, 67108864);

        $returnHTML = view('staff.radioTimetable')
        ->with('day', $day)
        ->with('timetable', $timetable)
        ->with('can_book_for_others', $can_book_for_others)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEventTimetable($day = null)
    {
        $time = time();

        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();
        $minus = 0;
        $plus = 0;

        if (count($timezone)) {
            if ($timezone->negative == 1) {
                $mn = 3600*$timezone->value;
                $minus = $timezone->value;
                $time -= $mn;
            } else {
                $pl = 3600*$timezone->value;
                $plus = $timezone->value;
                $time += $pl;
            }
        }

        if ($day == null) {
            $day = date('N', $time);
        }

        $timetable = array();

        $temps = DB::table('timetable')->where('type', 1)->get();

        foreach ($temps as $temp) {
            $temp_day = $temp->day;
            $temp_time = $temp->time;

            if ($minus > 0) {
                $temp_time -= $minus;

                if ($temp_time < 0) {
                    $temp_day -= 1;
                    $temp_time += 24;
                }
            } elseif ($plus > 0) {
                $temp_time += $plus;

                if ($temp_time > 23) {
                    $temp_day += 1;
                    $temp_time -= 24;
                }
            }

            if ($temp_day > 7) {
                $temp_day -= 7;
            } elseif ($temp_day < 1) {
                $temp_day += 7;
            }

            if ($temp_day == $day) {
                $perm = "";
                if ($temp->perm == 1) {
                    $perm = '<b style="color: #000000;">(perm)</b>';
                }

                $event = '(' . DB::table('event_types')->where('typeid', $temp->event)->value('event') . ')';
                $region = '(' . UserHelper::getRegion($temp->userid) . ')';

                if (UserHelper::getRegion($temp->userid) == 'OC') {
                    $opentag = '<span style="color: #8174da; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == 'NA') {
                    $opentag = '<span style="color: #74A5DA; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == 'EU') {
                    $opentag = '<span style="color: #647DD4; font-weight: bold;">';
                    $closetag = '</span>';
                }

                if (UserHelper::getRegion($temp->userid) == '') {
                    $opentag = '<span style="">';
                    $closetag = '</span>';
                }

                $username = $opentag . ' ' . UserHelper::getUsername($temp->userid, true) . ' ' . $closetag . ' ' . $region . '<br />' . $event;

                if (UserHelper::haveStaffPerm(Auth::user()->userid, 16777216) or $temp->userid == Auth::user()->userid) {
                    $username = '<span onclick="unbookslot(' . $temp->timetableid . ');" class="book_slot">' . $username . '</span>';
                }

                $timetable[$temp_time] = array(
                    'timetableid' => $temp->timetableid,
                    'username' => $username . ' ' . $perm,
                );
            }
        }

        $events = DB::table('event_types')->orderBy('event', 'ASC')->get();
        $can_book_for_others = UserHelper::haveStaffPerm(Auth::user()->userid, 67108864);

        $returnHTML = view('staff.eventTimetable')
        ->with('day', $day)
        ->with('timetable', $timetable)
        ->with('events', $events)
        ->with('can_book_for_others', $can_book_for_others)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
}
