<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\Helpers\ForumHelper;
use App\Helpers\ShopHelper;
use Auth;
use Image;
use App\User;
use DB;
use Hash;
use File;

class AdminController extends BaseController
{
    public function editAccolade(Request $request) {
        $accoladeid = $request->input('accoladeid');
        $accolade = $request->input('accolade');
        $display = $request->input('display');
        $userid = $request->input('userid');

        DB::table('accolades')->where('id', $accoladeid)->update([
            'description' => $accolade,
            'display_order' => $display
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited an Accolade',
            'content' => 3,
            'contentid' => $userid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditAccolade($accoladeid) {
        $temps = DB::table('accolades')->where('id', $accoladeid)->first();

        if (!count($temps)) {
            return redirect()->route('getSearchUsers');
        }

        $id = $temps->id;
        $accolade = $temps->description;
        $display_order = $temps->display_order;
        $username = UserHelper::getUsername($temps->userid, true);
        $userid = $temps->userid;

        $returnHTML = view('admincp.user.editAccolade')
            ->with('id', $id)
            ->with('accolade', $accolade)
            ->with('display_order', $display_order)
            ->with('username', $username)
            ->with('userid', $userid)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function deleteAccolade(Request $request)
    {
        $accoladeid = $request->input('accoladeid');
        $userid = $request->input('userid');
        $response = false;

        DB::table('accolades')->where('id', $accoladeid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed an Accolade',
            'content' => 3,
            'contentid' => $userid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);
        $response = true;

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function addAccolade(Request $request)
    {
        $userid = $request->input('userid');
        $accolade = $request->input('accolade');
        $type = $request->input('type');
        $start = $request->input('start');
        $end = $request->input('end');
        $display = $request->input('display');
        $response = false;

        $accolade = ForumHelper::fixContent($accolade);

        $user = DB::table('users')->where('userid', $userid)->first();
        $ending = '';
        if ($end != '') {
            $ending = ' - ' . $end;
        }

        if ($type == 'award') {
            $accolade = '<span style="color: #FFB400"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'admin') {
            $accolade = '<span style="color: #C66464"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'management') {
            $accolade = '<span style="color: #79B14E"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'moderator') {
            $accolade = '<span style="color: #4E4E4E"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'veteran') {
            $accolade = '<span style="color: #B4B6B9"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'developer') {
            $accolade = '<span style="color: #437682"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }
        if ($type == 'audioproducer') {
            $accolade = '<span style="color: #FFC0CB"><i class="fa fa-trophy"></i> ' . $accolade . ' &raquo;</span> ' . $start . '' . $ending;
        }

        if (count($user)) {
            DB::table('accolades')->insert([
                'userid' => $userid,
                'description' => $accolade,
                'display_order' => $display,
                'dateline' => time(),
                'awarded_id' => Auth::user()->userid
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Gave a user an accolade',
                'content' => 3,
                'contentid' => $userid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getAccolade($userid)
    {
        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user)) {
            $temps = DB::table('accolades')->where('userid', $userid)->orderBy('display_order', 'DESC')->get();
            $current_accolades = array();

            foreach($temps as $temp){
                $array = array(
                    'id' => $temp->id,
                    'accolade' => $temp->description,
                    'awarded_by' => UserHelper::getUsername($temp->awarded_id),
                    'date' => ForumHelper::timeAgo($temp->dateline),
                    'display_order' => $temp->display_order
                );

                $current_accolades[] = $array;
            }

            $returnHTML = view('admincp.user.addAccolade')
                ->with('user', $user)
                ->with('current_accolades', $current_accolades)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getSearchUsers');
    }

    public function deleteErrorLog()
    {
        File::delete('storage/logs/laravel.log');

        return response()->json(array('success' => true));
    }

    public function getErrorLog()
    {
        return response()->file(storage_path('logs/laravel.log'));
    }

    public function getPostingFestLog()
    {
        $postfest = DB::table('postfest')->orderBy('posts', 'DESC')->get();

        $returnHTML = view('admincp.logs.postingfest')
            ->with('postfest', $postfest)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postAddNotices(Request $request)
    {
        $title = $request->input('title');
        $type = $request->input('type');
        $body = $request->input('body');
        $expiry = $request->input('expiry');
        $visibility = $request->input('visibility');

        $id = DB::table('site_notices')->insertGetId([
            'title' => $title,
            'type' => $type,
            'body' => $body,
            'expiry' => strtotime($expiry),
            'enabled' => $visibility,
            'userid' => Auth::user()->userid,
            'dateline' => time(),
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added Site Notice',
            'content' => 19,
            'contentid' => $id,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditNotices(Request $request)
    {
        $noticeid = $request->input('noticeid');
        $title = $request->input('title');
        $type = $request->input('type');
        $body = $request->input('body');
        $expiry = $request->input('expiry');
        $visibility = $request->input('visibility');

        DB::table('site_notices')->where('noticeid', $noticeid)->update([
            'title' => $title,
            'type' => $type,
            'body' => $body,
            'expiry' => strtotime($expiry),
            'enabled' => $visibility,
            'lasteditedby' => Auth::user()->userid,
            'edited' => time(),
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited Site Notice',
            'content' => 19,
            'contentid' => $noticeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditNotices($noticeid)
    {
        $temps = DB::table('site_notices')->where('noticeid', $noticeid)->first();

        $returnHTML = view('admincp.notices.editNotices')
            ->with('noticeid', $noticeid)
            ->with('title', $temps->title)
            ->with('type', $temps->type)
            ->with('body', $temps->body)
            ->with('expiry', $temps->expiry)
            ->with('enabled', $temps->enabled)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAddNotices()
    {
        $returnHTML = view('admincp.notices.addNotices')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveNotices(Request $request)
    {
        $noticeid = $request->input('noticeid');
        DB::table('site_notices')->where('noticeid', $noticeid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted Site Notice',
            'content' => 19,
            'contentid' => $noticeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getListNotices()
    {
        $temps = DB::table('site_notices')->orderBy('noticeid', 'DESC')->get();

        $notices = array();

        foreach ($temps as $temp) {
            $enabled ='';
            if ($temp->enabled == '1') {
                $enabled = 'Active';
            }
            if ($temp->enabled == '0') {
                $enabled = 'Hidden';
            }

            $array = array(
                'noticeid' => $temp->noticeid,
                'title' => $temp->title,
                'enabled' => $enabled,
                'creator' => UserHelper::getUsername($temp->userid),
                'created' => ForumHelper::timeAgo($temp->dateline),
                'editor' => UserHelper::getUsername($temp->lasteditedby),
                'edited' => ForumHelper::timeAgo($temp->edited)
            );
            $notices[] = $array;
        }

        $returnHTML = view('admincp.notices.listNotices')
            ->with('notices', $notices)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRadioDetailsLog($pagenr)
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
        $skip = 0;

        if ($userid) {
            $pagesx = DB::table('radio_details_logs')->where('userid', $userid)->whereRaw($action)->count();
        } else {
            $pagesx = DB::table('radio_details_logs')->whereRaw($action)->count();
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

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($userid) {
            $temps = DB::table('radio_details_logs')->take($take)->whereRaw($action)->where('userid', $userid)->skip($skip)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('radio_details_logs')->take($take)->whereRaw($action)->skip($skip)->orderBy('dateline', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $action = "No Idea";

            switch ($temp->action) {
                case 1:
                    $action = "<font color=\"green\">Successfully viewed Radio Info</font>";
                    break;
                case 2:
                    $action = "<font color=\"red\">Failed to view Radio Info</font>";
                    break;
            }

            $array = array(
                'username' => UserHelper::getUsername($temp->userID),
                'action' => $action,
                'ip' => $temp->ip,
                'dateline' => ForumHelper::getTimeInDate($temp->dateline),
            );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/radiodetailslog/page/')->render();

        $returnHTML = view('admincp.logs.radiodetailslog')
            ->with('logs', $logs)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getVoucherLog($pagenr)
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
        $skip = 0;

        if ($userid) {
            $pagesx = DB::table('voucher_logs')->where('userid', $userid)->whereRaw($action)->count();
        } else {
            $pagesx = DB::table('voucher_logs')->whereRaw($action)->count();
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

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($userid) {
            $temps = DB::table('voucher_logs')->take($take)->whereRaw($action)->where('userid', $userid)->skip($skip)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('voucher_logs')->take($take)->whereRaw($action)->skip($skip)->orderBy('dateline', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $action = "No Idea";
            $contentid = "Not applicable";

            switch ($temp->action) {
                case 1:
                    $action = "<font color=\"purple\">Redeemed Voucher</font>";
                    break;
                case 2:
                    $action = "<font color=\"green\">Created Voucher</font>";
                    break;
                case 3:
                    $action = "<font color=\"red\">Deleted Voucher</font>";
                    break;
            }

            $array = array(
                'userid' => UserHelper::getUsername($temp->userid),
                'voucher_code' => $temp->voucher_code,
                'action' => $action,
                'voucher_worth' => $temp->voucher_worth,
                'dateline' => ForumHelper::getTimeInDate($temp->dateline),
            );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/voucherlog/page/')->render();

        $returnHTML = view('admincp.logs.voucherlog')
            ->with('logs', $logs)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getPointsLog($pagenr)
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
        $skip = 0;

        if ($userid) {
            $pagesx = DB::table('points_logs')->where('userid', $userid)->whereRaw($action)->count();
        } else {
            $pagesx = DB::table('points_logs')->whereRaw($action)->count();
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

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($userid) {
            $temps = DB::table('points_logs')->take($take)->whereRaw($action)->where('userid', $userid)->skip($skip)->orderBy('dateline', 'DESC')->get();
        } else {
            $temps = DB::table('points_logs')->take($take)->whereRaw($action)->skip($skip)->orderBy('dateline', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $action = "No Idea";
            $contentid = "Not Applicable";

            switch ($temp->action) {
                case 1:
                    $action = "<font color=\"green\">Added $temp->credit_number Credits</font>";
                    break;
                case 2:
                    $action = "<font color=\"red\">Removed $temp->credit_number Credits</font>";
                    break;
            }

            $array = array(
                'action_username' => UserHelper::getUsername($temp->userid),
                'action' => $action,
                'reason' => $temp->reason,
                'affected_user' => UserHelper::getUsername($temp->affected_userid),
                'dateline' => ForumHelper::getTimeInDate($temp->dateline),
            );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/pointslogs/page/')->render();

        $returnHTML = view('admincp.logs.pointsIssuing')
            ->with('logs', $logs)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postPoints(Request $request)
    {
        $username = $request->input('username');
        $amount = $request->input('amount');
        $action = $request->input('action');
        $reason = $request->input('reason');

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($request->input('username'))])->first();

        $action2 = "No Idea";

        if ($action == 'Add') {
            $action2 = '1';
            DB::table('users')->where('userid', $user->userid)->update([
                'credits' => $user->credits + $amount
            ]);
        }

        if ($action == 'Remove') {
            $action2 = '2';
            DB::table('users')->where('userid', $user->userid)->update([
                'credits' => $user->credits - $amount
            ]);
        }

        DB::table('points_logs')->insert([
            'userid' => Auth::user()->userid,
            'action' => $action2,
            'reason' => $reason,
            'credit_number' => $amount,
            'affected_userid' => $user->userid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getManagePoints()
    {
        $returnHTML = view('admincp.points')->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postAcceptTHC(Request $request)
    {
        $thcid = $request->input('thcid');
        $thcrequest = DB::table('thc_requests')->where('id', $thcid)->first();
        $user = DB::table('users')->where('userid', $thcrequest->userid)->first();

        DB::table('users')->where('userid', $user->userid)->update([
            'credits' => $user->credits + $thcrequest->thc
        ]);

        DB::table('thc_requests')->where('id', $thcid)->delete();

        DB::table('points_logs')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'reason' => 'Approved Request - ' . $thcrequest->reason,
            'credit_number' => $thcrequest->thc,
            'affected_userid' => $thcrequest->userid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postAcceptTHCs(Request $request)
    {
        $thcrequests = DB::table('thc_requests')->get();

        foreach ($thcrequests as $thcrequest) {
            $user = DB::table('users')->where('userid', $thcrequest->userid)->first();

            DB::table('users')->where('userid', $user->userid)->update([
                'credits' => $user->credits + $thcrequest->thc
            ]);

            DB::table('thc_requests')->where('id', $thcrequest->id)->delete();

            DB::table('points_logs')->insert([
                'userid' => Auth::user()->userid,
                'action' => 1,
                'reason' => 'Approved Request - ' . $thcrequest->reason,
                'credit_number' => $thcrequest->thc,
                'affected_userid' => $thcrequest->userid,
                'dateline' => time()
            ]);
        }

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postDenyTHC(Request $request)
    {
        $thcid = $request->input('thcid');
        $thcreq = DB::table('thc_requests')->where('id', $thcid)->count();

        if(count($thcreq)) {
            DB::table('thc_requests')->where('id', $thcid)->delete();

            return response()->json(array('success' => true, 'response' => true));
        }
    }

    public function getManagePointRequests()
    {

        DB::table('thc_requests')
            ->join('users', 'users.userid', '=', 'thc_requests.userid')
            ->where('users.lastactivity', '<', strtotime('-1 month'))
            ->delete();

        $temps = DB::table('thc_requests')->orderby('id', 'ASC')->get();
        $thcrequest = array();

        foreach ($temps as $temp) {
            $user = DB::table('users')->where('userid', $temp->userid)->first();

            $array = array(
                'id' => $temp->id,
                'username' => UserHelper::getUsername($temp->userid),
                'timeago' => ForumHelper::timeAgo($user->lastactivity),
                'thc' => $temp->thc,
                'reason' => $temp->reason,
                'requestor' => UserHelper::getUsername($temp->requestor),
                'dateline' => ForumHelper::timeAgo($temp->dateline)
            );
            $thcrequest[] = $array;
        }

        $returnHTML = view('admincp.pointRequests')
            ->with('thcrequest', $thcrequest)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function deleteDailyQuest(Request $request)
    {
        $questid = $request->input('questid');
        $response = false;
        $message = "";

        $check = DB::table('daily_quests')->where('questid', $questid)->first();
        if (count($check)) {
            DB::table('daily_quests')->where('questid', $questid)->delete();
            DB::table('active_quests')->where('questid', $questid)->delete();
            $response = true;
        } else {
            $message = "Doesn't exist!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postAddDailyQuest(Request $request)
    {
        $type = $request->input('type');
        $target = $request->input('target');
        $text = $request->input('text');
        $box = $request->input('boxid');

        $response = false;
        if (is_numeric($type)) {
            if (is_numeric($target)) {
                if (strlen('text')>0) {
                    if (is_numeric($box)) {
                        DB::table('daily_quests')->insert([
                            'type' => $type,
                            'text' => $text,
                            'amount' => $target,
                            'boxid' => $box
                        ]);
                        $response = true;
                        $message = "Success!";
                    } else {
                        $message = "Box is not valid!";
                    }
                } else {
                    $message = "You must enter text!";
                }
            } else {
                $message = "The target is invalid!";
            }
        } else {
            $message = "The type is invalid";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }


    public function getAddDailyQuest()
    {
        $questtemps = DB::table('daily_quests')->get();
        $quests = array();
        foreach ($questtemps as $quest) {
            $array = array(
                'id' => $quest->questid,
                'text' => $quest->text,
                'prize' => DB::table('boxes')->where('boxid', $quest->boxid)->value('name')
            );
            $quests[] = $array;
        }

        $boxtemps = DB::table('boxes')->get();
        $boxes = array();
        foreach ($boxtemps as $box) {
            $array = array(
                'id' => $box->boxid,
                'name' => $box->name
            );
            $boxes[] = $array;
        }

        $returnHTML = view('admincp.dailyquests')
            ->with('quests', $quests)
            ->with('boxes', $boxes)
            ->render();

        return response()->json(array('success'=>true, 'response'=>true, 'returnHTML'=>$returnHTML));
    }

    public function addToBoxPage()
    {
        $themes = ShopHelper::getThemes();
        $icons = ShopHelper::getIcons();
        $effects = ShopHelper::getEffects();
        $subs = ShopHelper::getSubs();
        $stickers = ShopHelper::getStickers();

        $boxes = array();
        $temps = DB::table('boxes')->get();
        foreach ($temps as $temp) {
            $items = array();
            $itemtemps = DB::table('box_contents')->where('boxid', $temp->boxid)->get();
            foreach ($itemtemps as $itemtemp) {
                switch ($itemtemp->typeid) {
                    case 1:
                        $itemtype = 'Theme';
                        $itemname = DB::table('themes')->where('themeid', $itemtemp->itemid)->value('name');
                        break;
                    case 2:
                        $itemtype = 'Name Icon';
                        $itemname = DB::table('name_icons')->where('iconid', $itemtemp->itemid)->value('name');
                        break;
                    case 3:
                        $itemtype = 'Name Effect';
                        $itemname = DB::table('name_effects')->where('effectid', $itemtemp->itemid)->value('name');
                        break;
                    case 4:
                        $itemtype = 'Subscription Package';
                        $itemname = DB::table('subscription_packages')->where('packageid', $itemtemp->itemid)->value('name');
                        break;
                    case 5:
                        $itemtype = 'Sticker';
                        $itemname = DB::table('stickers')->where('stickerid', $itemtemp->itemid)->value('name');

                }


                $item = array(
                    'id' => $itemtemp->contentid,
                    'type' => $itemtype,
                    'item' => $itemname,
                    'weight' => $itemtemp->weight
                );
                $items[] = $item;
            }
            $box = array(
                'id' => $temp->boxid,
                'image' => asset('_assets/img/boxes/' . $temp->boxid . '.gif'),
                'price' => $temp->price,
                'name' => $temp->name,
                'items' => $items
            );
            $boxes[] = $box;
        }

        $returnHTML = view('admincp.boxcontents')
            ->with('themes', $themes)
            ->with('icons', $icons)
            ->with('effects', $effects)
            ->with('subs', $subs)
            ->with('boxes', $boxes)
            ->with('stickers', $stickers)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function deleteFromBox(Request $request)
    {
        $contentid = $request->input('contentid');
        $response = false;

        $check = DB::table('box_contents')->where('contentid', $contentid)->first();
        if (count($check)) {
            $check = DB::table('box_contents')->where('contentid', $contentid)->delete();
            $message = "Deleted!";
            $response = true;
        } else {
            $message = "This doesn't exist!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }
    public function addToBox(Request $request)
    {
        $box = $request->input('box');
        $type = $request->input('type');
        $item = $request->input('item');
        $weight = $request->input('weight');
        $id=0;

        $response = false;
        $check = DB::table('box_contents')->where('typeid', $type)->where('itemid', $item)->where('boxid', $box)->first();
        if (!$check) {
            $id = DB::table('box_contents')->insertGetId([
                'typeid' => $type,
                'itemid' => $item,
                'boxid' => $box,
                'weight' => $weight
            ]);
            $response = true;
            $message = "Success!";
        } else {
            $message = "This is already in the box!";
        }


        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'id'=>$id));
    }

    public function postEditDefaultTheme(Request $request)
    {
        $themeid = $request->input('themeid');
        $mode = $request->input('mode');

        if ($mode == 1) {
            DB::table('themes')->update(['default_theme' => 0]);
            DB::table('themes')->where('themeid', $themeid)->update(['default_theme' => 1]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Made theme default',
                'content' => 17,
                'contentid' => $themeid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
        } else {
            DB::table('themes')->update(['default_theme' => 0]);
            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Made theme none-default',
                'content' => 17,
                'contentid' => $themeid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function getStats()
    {
        $time = strtotime('today midnight');
        $todayStats = [
            'posts' => DB::table('posts')->where('dateline', '>', $time)->count(),
            'threads' => DB::table('threads')->where('dateline', '>', $time)->count(),
            'creations' => DB::table('creations')->where('dateline', '>', $time)->count(),
            'creation_comments' => DB::table('creation_comments')->where('dateline', '>', $time)->count(),
            'articles' => DB::table('articles')->where('dateline', '>', $time)->count(),
            'article_comments' => DB::table('article_comments')->where('dateline', '>', $time)->count(),
            'visitor_messages' => DB::table('visitor_messages')->where('dateline', '>', $time)->count()
        ];

        $statsLog = DB::table('stats_log')->where('dateline', '>', strtotime('Last Monday', time()))->orderBy('statid', 'DESC')->get();

        $returnHTML = view('admincp.stats')
            ->with('todayStats', $todayStats)
            ->with('statsLog', $statsLog)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getThemes()
    {
        $themes = DB::table('themes')->orderBy('name', 'ASC')->get();

        $returnHTML = view('admincp.shop.listThemes')
            ->with('themes', $themes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewTheme()
    {
        $returnHTML = view('admincp.shop.newTheme')
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditTheme($themeid)
    {
        $theme = DB::table('themes')->where('themeid', $themeid)->first();

        $returnHTML = view('admincp.shop.editTheme')
            ->with('theme', $theme)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postNewTheme(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $css = $request->input('css');
        $thcb = $request->input('thcb');
        $visible = $request->input('visible');
        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }
        if (!isset($desc)) {
            $message = "Okey yea i know description sucks but add it!!!";
        }
        if (!isset($price)) {
            $message = "It need to cost something? NOTHING IS FREE FFS!";
        }
        if (!($visible == '0' || $visible == '1')) {
            $message = "Visibility invalid!";
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('theme') and $request->file('theme')->isValid()) {
            $themeid = DB::table('themes')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'style' => $css,
                'thcb' => $thcb,
                'visible' => $visible,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('theme'), $themeid, '_assets/img/themes/');

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Added theme',
                'content' => 17,
                'contentid' => $themeid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a theme placeholder to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function postEditTheme(Request $request)
    {
        $themeid = $request->input('themeid');

        $theme = DB::table('themes')->where('themeid', $themeid)->first();

        if (count($theme)) {
            $name = $request->input('name');
            $desc = $request->input('description');
            $price = $request->input('price');
            $css = $request->input('css');
            $thcb = $request->input('thcb');
            $visible = $request->input('visible');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($desc)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('theme') and $request->file('theme')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('theme'), $themeid, '_assets/img/themes/');
            }

            DB::table('themes')->where('themeid', $themeid)->update([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'style' => $css,
                'thcb' => $thcb,
                'visible' => $visible
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated theme',
                'content' => 17,
                'contentid' => $themeid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Theme you try to update dont exist!'));
        }
    }

    public function postRemoveTheme(Request $request)
    {
        $themeid = $request->input('themeid');

        DB::table('themes')->where('themeid', $themeid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed theme',
            'content' => 17,
            'contentid' => $themeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getManageBadge($badgeid)
    {
        $temps = DB::table('users_badges')->where('badgeid', $badgeid)->get();

        $users = array();

        foreach ($temps as $temp) {
            $array = array(
                'userid' => $temp->userid,
                'username' => UserHelper::getUsername($temp->userid, true)
            );

            $users[] = $array;
        }

        $badge_image = asset('_assets/img/website/badges/' . $badgeid . '.gif');

        $returnHTML = view('admincp.extras.manageBadge')
            ->with('users', $users)
            ->with('badge_image', $badge_image)
            ->with('badgeid', $badgeid)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditBadge($badgeid)
    {
        $badge = DB::table('badges')->where('badgeid', $badgeid)->first();

        $returnHTML = view('admincp.extras.editBadge')
            ->with('badgeid', $badge->badgeid)
            ->with('name', $badge->name)
            ->with('description', $badge->description)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getManageBadges($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 50;
        $skip = 0;

        $pagesx = DB::table('badges')->where('system_defined', '==', '0')->count();
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

        $temps = DB::table('badges')->where('system_defined', '==', '0')->take($take)->skip($skip)->orderBy('name', 'ASC')->get();

        $badges = array();

        foreach ($temps as $temp) {
            $array = array(
                'badgeid' => $temp->badgeid,
                'name' => $temp->name,
                'description' => $temp->description,
                'badge' => asset('_assets/img/website/badges/' . $temp->badgeid . '.gif')
            );

            $badges[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/badges/manage/page/')->render();

        $returnHTML = view('admincp.manageBadges')
            ->with('badges', $badges)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveUserBadge(Request $request)
    {
        $userid = $request->input('userid');
        $badgeid = $request->input('badgeid');

        DB::table('notifications')->where('reciveuserid', $userid)->where('content', 7)->where('contentid', $badgeid)->delete();
        DB::table('users_badges')->where('userid', $userid)->where('badgeid', $badgeid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed badge from user',
            'content' => 11,
            'contentid' => $badgeid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postAddUserBadge(Request $request)
    {
        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($request->input('username'))])->first();
        $badgeid = $request->input('badgeid');

        if (!count($user)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'User not found!'));
        }

        $check = DB::table('users_badges')->where('badgeid', $badgeid)->where('userid', $user->userid)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'User already got badge!'));
        }

        DB::table('users_badges')->insert([
            'userid' => $user->userid,
            'badgeid' => $badgeid,
            'selected' => 0,
            'dateline' => time()
        ]);

        DB::table('users')->where('userid', $user->userid)->update([
            'xpcount' => DB::raw('xpcount+200')
        ]);

        DB::table('notifications')->insert([
            'postuserid' => 0,
            'reciveuserid' => $user->userid,
            'content' => 7,
            'contentid' => $badgeid,
            'dateline' => time(),
            'read_at' => 0
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added badge to user',
            'content' => 11,
            'contentid' => $badgeid,
            'affected_userid' => $user->userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditBadge(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');

        $badgeid = $request->input('badgeid');
        if ($request->hasFile('badge_file') and $request->file('badge_file')->isValid()) {
            UserHelper::saveAnimatedImage($request->file('badge_file'), $badgeid, '_assets/img/website/badges/');
        }

        if (strlen('desc') <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Description can\'t be empty!'));
        }

        DB::table('badges')->where('badgeid', $badgeid)->update([
            'name' => $name,
            'description' => $desc
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited badge',
            'content' => 12,
            'contentid' => $badgeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postAddBadge(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');

        if (!$request->hasFile('badge_file') or !$request->file('badge_file')->isValid()) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Invalid image!'));
        }

        if (strlen('name') <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Name can\'t be empty!'));
        }

        if (strlen('desc') <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Description can\'t be empty!'));
        }

        $badgeid = DB::table('badges')->insertGetId([
            'system_defined' => 0,
            'name' => $name,
            'description' => $desc
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added badge',
            'content' => 12,
            'contentid' => $badgeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        UserHelper::saveAnimatedImage($request->file('badge_file'), $badgeid, '_assets/img/website/badges/');

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveBadge(Request $request)
    {
        DB::table('badges')->where('badgeid', $request->input('badgeid'))->delete();

        DB::table('user_badges')->where('badgeid', $request->input('badgeid'))->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed badge',
            'content' => 12,
            'contentid' => $request->input('badgeid'),
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function updateLinkPartners(Request $request)
    {
        $content = $request->input('content');
        File::put('linkpartners.txt', $content);
        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated Link Partners Page',
            'content' => 9,
            'contentid' => 0,
            'extra_info' => "",
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);
        return response()->json(array('success' => true));
    }

    public function getLinkPartners()
    {
        $linkpartners = "";
        if (File::exists('linkpartners.txt')) {
            $linkpartners = File::get('linkpartners.txt');
        }
        $returnHTML = view('admincp.pages.managePartners')
            ->with('linkpartners', $linkpartners)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function updateSiteRules(Request $request)
    {
        $content = $request->input('content');
        File::put('rules.txt', $content);
        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated Site Rules Page',
            'content' => 9,
            'contentid' => 0,
            'extra_info' => "",
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);
        return response()->json(array('success' => true));
    }

    public function getSiteRules()
    {
        $rules = "";
        if (File::exists('rules.txt')) {
            $rules = File::get('rules.txt');
        }
        $returnHTML = view('admincp.pages.manageSiteRules')
            ->with('rules', $rules)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
    // HOLY

    public function updatePCMW(Request $request)
    {
        $username = $request->input('username');
        $comment = $request->input('comment');
        $comment = ForumHelper::fixContent($comment);

        if ($username != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($u)) {
                $username = $u->userid;
            }
        }

        DB::table('photo_comp')->insert([
            'pcuserid' => $username,
            'comment' => $comment,
            'userid' => Auth::user()->userid,
            'dateline' => time()
        ]);

        DB::table('users')->where('userid', $username)->update([
            'xpcount' => DB::raw('xpcount+1000')
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated Photo Comp Monthly Winner',
            'content' => 9,
            'contentid' => 0,
            'extra_info' => "",
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);
        return response()->json(array('success' => true, 'response' => true));
    }

    public function getPCMW()
    {
        $temps = DB::table('photo_comp')->orderBy('pcid', 'DESC')->first();

        /* PLAIN TEXT NAMES */
        $pcuserid = UserHelper::getUsername($temps->pcuserid);
        $comment = $temps->comment;
        $userid = UserHelper::getUsername($temps->userid);
        $dateline = ForumHelper::timeago($temps->dateline);

        $returnHTML = view('admincp.pages.managePCMW')
            ->with('pcuserid', $pcuserid)
            ->with('comment', $comment)
            ->with('userid', $userid)
            ->with('dateline', $dateline)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    // YEPPERS
    public function updateMOTM(Request $request)
    {
        $username = $request->input('username');
        $comment = $request->input('comment');
        $comment = ForumHelper::fixContent($comment);

        if ($username != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($u)) {
                $username = $u->userid;
            }
        }

        DB::table('motm')->insert([
            'motmuserid' => $username,
            'comment' => $comment,
            'userid' => Auth::user()->userid,
            'dateline' => time()
        ]);

        DB::table('users')->where('userid', $username)->update([
            'xpcount' => DB::raw('xpcount+1000')
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated Member of the Month',
            'content' => 9,
            'contentid' => 0,
            'extra_info' => "",
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);
        return response()->json(array('success' => true, 'response' => true));
    }

    public function getMOTM()
    {
        $temps = DB::table('motm')->orderBy('motmid', 'DESC')->first();

        /* PLAIN TEXT NAMES */
        $motmuserid = UserHelper::getUsername($temps->motmuserid);
        $comment = $temps->comment;
        $userid = UserHelper::getUsername($temps->userid);
        $dateline = ForumHelper::timeago($temps->dateline);

        $returnHTML = view('admincp.pages.manageMOTM')
            ->with('motmuserid', $motmuserid)
            ->with('comment', $comment)
            ->with('userid', $userid)
            ->with('dateline', $dateline)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function updateSOTW(Request $request)
    {
        $eu_management = $request->input('eu_management');
        $eu_radio = $request->input('eu_radio');
        $eu_events = $request->input('eu_events');
        $na_management = $request->input('na_management');
        $na_radio = $request->input('na_radio');
        $na_events = $request->input('na_events');
        $oc_management = $request->input('oc_management');
        $oc_radio = $request->input('oc_radio');
        $oc_events = $request->input('oc_events');
        $media = $request->input('media');
        $moderation = $request->input('moderation');
        $quests = $request->input('quests');
        $graphics = $request->input('graphics');
        $audioprod = $request->input('audioprod');
        $recruitment = $request->input('recruitment');
        $global_management = $request->input('global_management');
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $month = $request->input('month');

        // START OF POSTING THREAD
        $check = DB::table('threads')->where('postuserid', Auth::user()->userid)->where('dateline', '>', time()-15)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'msg' => 'posting to fast'));
        }

        $threadid = DB::table('threads')->insertGetId([
            'title' => 'Staff of the Week [' . $startdate . ' - ' . $enddate . ' ' . $month . ']',
            'forumid' => 19,
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

        // MANAGEMENT NAMES
        if ($global_management == "") {
            $global_management = "N/A";
        } else {
            $global_management = '[mention]' . $global_management . '[/mention]';
        }
        if ($eu_management == "") {
            $eu_management = "N/A";
        } else {
            $eu_management = '[mention]' . $eu_management . '[/mention]';
        }
        if ($na_management == "") {
            $na_management = "N/A";
        } else {
            $na_management = '[mention]' . $na_management . '[/mention]';
        }
        if ($oc_management == "") {
            $oc_management = "N/A";
        } else {
            $oc_management = '[mention]' . $oc_management . '[/mention]';
        }

        // RADIO NAMES
        if ($eu_radio == "") {
            $eu_radio = "N/A";
        } else {
            $eu_radio = '[mention]' . $eu_radio . '[/mention]';
        }
        if ($na_radio == "") {
            $na_radio = "N/A";
        } else {
            $na_radio = '[mention]' . $na_radio . '[/mention]';
        }
        if ($oc_radio == "") {
            $oc_radio = "N/A";
        } else {
            $oc_radio = '[mention]' . $oc_radio . '[/mention]';
        }
        // EVENTS NAMES
        if ($eu_events == "") {
            $eu_events = "N/A";
        } else {
            $eu_events = '[mention]' . $eu_events . '[/mention]';
        }
        if ($na_events == "") {
            $na_events = "N/A";
        } else {
            $na_events = '[mention]' . $na_events . '[/mention]';
        }
        if ($oc_events == "") {
            $oc_events = "N/A";
        } else {
            $oc_events = '[mention]' . $oc_events . '[/mention]';
        }
        // MISC NAMES
        if ($moderation == "") {
            $moderation = "N/A";
        } else {
            $moderation = '[mention]' . $moderation . '[/mention]';
        }
        if ($media == "") {
            $media = "N/A";
        } else {
            $media = '[mention]' . $media . '[/mention]';
        }
        if ($quests == "") {
            $quests = "N/A";
        } else {
            $quests = '[mention]' . $quests . '[/mention]';
        }
        if ($graphics == "") {
            $graphics = "N/A";
        } else {
            $graphics = '[mention]' . $graphics . '[/mention]';
        }
        if ($audioprod == "") {
            $audioprod = "N/A";
        } else {
            $audioprod = '[mention]' . $audioprod . '[/mention]';
        }
        if ($recruitment == "") {
            $recruitment = "N/A";
        } else {
            $recruitment = '[mention]' . $recruitment . '[/mention]';
        }

        $string = '[imgc]https://cdn.discordapp.com/attachments/442537322836131840/480926682664927245/sotw.gif[/imgc]
[imgc]https://i.imgur.com/N3bVien.png[/imgc]
A big congratulations to the following people who have made an outstanding effort within their departments this week, by working hard and standing out. You have been noticed by your Department Managers/Leaders and have been selected to win this week\'s \'[b]Staff of the Week[/b]\' for your efforts. Your name will also be listed on the main page of the site.

[b]Global Management -[/b] ' . $global_management . '
[b]EU Management -[/b] ' . $eu_management . '
[b]NA Management -[/b] ' . $na_management . '
[b]OC Management -[/b] ' . $oc_management . '

[b]EU Radio -[/b] ' . $eu_radio . '
[b]NA Radio -[/b] ' . $na_radio . '
[b]OC Radio -[/b] ' . $oc_radio . '

[b]EU Events -[/b] ' . $eu_events . '
[b]NA Events -[/b] ' . $na_events . '
[b]OC Events -[/b] ' . $oc_events . '

[b]Moderation -[/b] ' . $moderation . '
[b]Media -[/b] ' . $media . '
[b]Quests -[/b] ' . $quests . '
[b]Graphics -[/b] ' . $graphics . '
[b]Audio Producers -[/b] ' . $audioprod . '
[b]Recruitment -[/b] ' . $recruitment . '

You will now be rewarded for your efforts! Each person mentioned will win themselves [b]1 week of ThisHabboClub[/b] and [b]50 THC Points[/b]. All will be distributed right after the thread is posted so if you do not want either of the prizes please let us know ASAP.

Well done to all of the winners this week! Good luck to everyone fighting it out for a chance to win Staff of the Week next week!

--
[b]ThisHabbo Administration[/b]';

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

        DB::table('forums')->where('forumid', 19)->update([
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
        // END OF POSTING THREAD
        // START OF DATABASE ENTRY FOR SOTW HOMEPAGE
        $global_management = $request->input('global_management');
        $eu_management = $request->input('eu_management');
        $eu_radio = $request->input('eu_radio');
        $eu_events = $request->input('eu_events');
        $na_management = $request->input('na_management');
        $na_radio = $request->input('na_radio');
        $na_events = $request->input('na_events');
        $oc_management = $request->input('oc_management');
        $oc_radio = $request->input('oc_radio');
        $oc_events = $request->input('oc_events');
        $media = $request->input('media');
        $moderation = $request->input('moderation');
        $quests = $request->input('quests');
        $graphics = $request->input('graphics');
        $audioprod = $request->input('audioprod');
        $recruitment = $request->input('recruitment');
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $month = $request->input('month');

        if ($global_management != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($global_management)])->first();
            if (count($u)) {
                $global_management = $u->userid;
            }
        }

        if ($eu_management != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($eu_management)])->first();
            if (count($u)) {
                $eu_management = $u->userid;
            }
        }

        if ($eu_radio != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($eu_radio)])->first();
            if (count($u)) {
                $eu_radio = $u->userid;
            }
        }

        if ($eu_events != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($eu_events)])->first();
            if (count($u)) {
                $eu_events = $u->userid;
            }
        }

        if ($na_management != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($na_management)])->first();
            if (count($u)) {
                $na_management = $u->userid;
            }
        }

        if ($na_events != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($na_events)])->first();
            if (count($u)) {
                $na_events = $u->userid;
            }
        }

        if ($na_radio != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($na_radio)])->first();
            if (count($u)) {
                $na_radio = $u->userid;
            }
        }

        if ($oc_management != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($oc_management)])->first();
            if (count($u)) {
                $oc_management = $u->userid;
            }
        }

        if ($oc_events != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($oc_events)])->first();
            if (count($u)) {
                $oc_events = $u->userid;
            }
        }

        if ($oc_radio != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($oc_radio)])->first();
            if (count($u)) {
                $oc_radio = $u->userid;
            }
        }

        if ($media != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($media)])->first();
            if (count($u)) {
                $media = $u->userid;
            }
        }

        if ($moderation != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($moderation)])->first();
            if (count($u)) {
                $moderation = $u->userid;
            }
        }

        if ($quests != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($quests)])->first();
            if (count($u)) {
                $quests = $u->userid;
            }
        }

        if ($graphics != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($graphics)])->first();
            if (count($u)) {
                $graphics = $u->userid;
            }
        }

        if ($audioprod != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($audioprod)])->first();
            if (count($u)) {
                $audioprod = $u->userid;
            }
        }

        if ($recruitment != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($recruitment)])->first();
            if (count($u)) {
                $recruitment = $u->userid;
            }
        }

        DB::table('sotw')->insert([
            'global_management' => $global_management,
            'eu_management' => $eu_management,
            'eu_radio' => $eu_radio,
            'eu_events' => $eu_events,
            'na_management' => $na_management,
            'na_radio' => $na_radio,
            'na_events' => $na_events,
            'oc_management' => $oc_management,
            'oc_radio' => $oc_radio,
            'oc_events' => $oc_events,
            'media' => $media,
            'moderation' => $moderation,
            'quests' => $quests,
            'graphics' => $graphics,
            'audioprod' => $audioprod,
            'recruitment' => $recruitment,
            'userid' => Auth::user()->userid,
            'dateline' => time()
        ]);

        $notification = DB::table('sotw')->orderBy('sotwID', 'DESC')->first();
        // Notification for Management SOTW
        if ($notification->global_management != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->global_management, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->eu_management != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->eu_management, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->na_management != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->na_management, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->oc_management != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->oc_management, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        // Notification for Radio SOTW
        if ($notification->eu_radio != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->eu_radio, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->na_radio != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->na_radio, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->oc_radio != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->oc_radio, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        // Notification for Events SOTW
        if ($notification->eu_events != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->eu_events, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->na_events != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->na_events, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->oc_events != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->oc_events, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        // Notification for Misc SOTW
        if ($notification->media != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->media, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->moderation != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->moderation, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->quests != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->quests, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->graphics != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->graphics, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->audioprod != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->audioprod, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }
        if ($notification->recruitment != "0") {
            DB::table('notifications')->insert(['postuserid' => Auth::user()->userid, 'reciveuserid' => $notification->recruitment, 'content' => 1, 'contentid' => $postid, 'dateline' => time(), 'read_at' => 0 ]);
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated Staff of the Week',
            'content' => 9,
            'contentid' => 0,
            'extra_info' => "",
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);
        return response()->json(array('success' => true, 'response' => true));
    }

    public function getSOTW()
    {
        $temps = DB::table('sotw')->orderBy('sotwID', 'DESC')->first();

        /* PLAIN TEXT NAMES */
        $global_management = UserHelper::getUsername($temps->global_management, true);
        $eu_management = UserHelper::getUsername($temps->eu_management, true);
        $na_management = UserHelper::getUsername($temps->na_management, true);
        $oc_management = UserHelper::getUsername($temps->oc_management, true);
        $moderation = UserHelper::getUsername($temps->moderation, true);
        $eu_radio = UserHelper::getUsername($temps->eu_radio, true);
        $eu_events = UserHelper::getUsername($temps->eu_events, true);
        $na_radio = UserHelper::getUsername($temps->na_radio, true);
        $na_events = UserHelper::getUsername($temps->na_events, true);
        $oc_radio = UserHelper::getUsername($temps->oc_radio, true);
        $oc_events = UserHelper::getUsername($temps->oc_events, true);
        $media = UserHelper::getUsername($temps->media, true);
        $quests = UserHelper::getUsername($temps->quests, true);
        $graphics = UserHelper::getUsername($temps->graphics, true);
        $audioprod = UserHelper::getUsername($temps->audioprod, true);
        $recruitment = UserHelper::getUsername($temps->recruitment, true);

        $returnHTML = view('admincp.pages.manageSOTW')
            ->with('global_management', $global_management)
            ->with('eu_management', $eu_management)
            ->with('na_management', $na_management)
            ->with('oc_management', $oc_management)
            ->with('moderation', $moderation)
            ->with('eu_radio', $eu_radio)
            ->with('eu_events', $eu_events)
            ->with('na_radio', $na_radio)
            ->with('na_events', $na_events)
            ->with('oc_radio', $oc_radio)
            ->with('oc_events', $oc_events)
            ->with('media', $media)
            ->with('quests', $quests)
            ->with('graphics', $graphics)
            ->with('audioprod', $audioprod)
            ->with('recruitment', $recruitment)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function deleteVoucherCode(Request $request)
    {
        $voucherid = $request->input('voucherid');

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted voucher code',
            'content' => 15,
            'contentid' => 0,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        $vouchers = DB::table('voucher_codes')->where('voucherid', $request->input('voucherid'))->first();
        DB::table('voucher_logs')->insert([
            'userid' => Auth::user()->userid,
            'voucher_code' => $vouchers->code,
            'action' => 3,
            'voucher_worth' => $vouchers->worth,
            'dateline' => time()
        ]);

        DB::table('voucher_codes')->where('voucherid', $voucherid)->update([
            'active' => 0
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function createNewCode(Request $request)
    {
        $worth = $request->input('worth');

        $code = self::generateVoucherCode();
        $existing_codes = DB::table('voucher_codes')->pluck('code')->all();
        $run = true;
        while ($run) {
            if (!in_array($code, $existing_codes)) {
                $run = false;
            } else {
                $code = self::generateVoucherCode();
            }
        }

        DB::table('voucher_codes')->insert([
            'userid' => Auth::user()->userid,
            'worth' => $worth,
            'code' => $code,
            'dateline' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Created voucher code',
            'content' => 15,
            'contentid' => 0,
            'affected_userid' => $worth,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        DB::table('voucher_logs')->insert([
            'userid' => Auth::user()->userid,
            'voucher_code' => $code,
            'action' => 2,
            'voucher_worth' => $worth,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    private function generateVoucherCode($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function getManageVoucher()
    {
        $unused_vouchers = array();

        $temps = DB::table('voucher_codes')->where('active', 1)->orderBy('voucherid', 'DESC')->get();

        foreach ($temps as $temp) {
            $unused_vouchers[] = array(
                'code' => $temp->code,
                'worth' => $temp->worth,
                'username' => UserHelper::getUsername($temp->userid, true),
                'dateline' => ForumHelper::getTimeInDate($temp->dateline),
                'voucherid' => $temp->voucherid
            );
        }

        $returnHTML = view('admincp.shop.manageVoucher')
            ->with('unused_vouchers', $unused_vouchers)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditNameEffect(Request $request)
    {
        $effectid = $request->input('effectid');

        $effect = DB::table('name_effects')->where('effectid', $effectid)->first();

        if (count($effect)) {
            $name = $request->input('name');
            $desc = $request->input('description');
            $price = $request->input('price');
            $limit = $request->input('limit');
            $thcb = $request->input('thcb');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($desc)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('effect') and $request->file('effect')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('effect'), $effectid, '_assets/img/nameeffects/');
            }

            DB::table('name_effects')->where('effectid', $effectid)->update([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated Name Effect',
                'content' => 16,
                'contentid' => $effectid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Effect you try to update dont exist!'));
        }
    }

    public function postEditSticker(Request $request)
    {
        $stickerid = $request->input('stickerid');

        $sticker = DB::table('stickers')->where('stickerid', $stickerid)->first();

        if (count($sticker)) {
            $name = $request->input('name');
            $desc = $request->input('description');
            $price = $request->input('price');
            $limit = $request->input('limit');
            $thcb = $request->input('thcb');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($desc)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('icon') and $request->file('icon')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('icon'), $iconid, '_assets/img/stickers/');
            }

            DB::table('stickers')->where('stickerid', $stickerid)->update([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated Sticker',
                'content' => 14,
                'contentid' => $stickerid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Icon  you try to update dont exist!'));
        }
    }

    public function postEditNameIcon(Request $request)
    {
        $iconid = $request->input('iconid');

        $icon = DB::table('name_icons')->where('iconid', $iconid)->first();

        if (count($icon)) {
            $name = $request->input('name');
            $desc = $request->input('description');
            $price = $request->input('price');
            $limit = $request->input('limit');
            $thcb = $request->input('thcb');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($desc)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('icon') and $request->file('icon')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('icon'), $iconid, '_assets/img/nameicons/');
            }

            DB::table('name_icons')->where('iconid', $iconid)->update([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated Name icon',
                'content' => 14,
                'contentid' => $iconid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Icon  you try to update dont exist!'));
        }
    }

    public function postEditBackground(Request $request)
    {
        $backgroundid = $request->input('backgroundid');

        $background = DB::table('backgrounds')->where('backgroundid', $backgroundid)->first();

        if (count($background)) {
            $name = $request->input('name');
            $desc = $request->input('description');
            $price = $request->input('price');
            $limit = $request->input('limit');
            $thcb = $request->input('thcb');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($desc)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('background') and $request->file('background')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('background'), $backgroundid, '_assets/img/backgrounds/');
            }

            DB::table('backgrounds')->where('backgroundid', $backgroundid)->update([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb
            ]);

            /* ADMIN LOG
            DB::table('admin_log')->insert([
              'userid' => Auth::user()->userid,
              'description' => 'Updated Name icon',
              'content' => 14,
              'contentid' => $iconid,
              'affected_userid' => 0,
              'ip' => Auth::user()->lastip,
              'dateline' => time()
    ]); */

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Background you try to update dont exist!'));
        }
    }

    public function postEditBox(Request $request)
    {
        $boxid = $request->input('boxid');

        $box = DB::table('boxes')->where('boxid', $boxid)->first();

        if (count($box)) {
            $name = $request->input('name');
            $price = $request->input('price');
            $description = $request->input('description');
            $duplicate = $request->input('duplicate');

            $message = "";
            if (!isset($name)) {
                $message = "Hello, adding something without a name are we?";
            }
            if (!isset($price)) {
                $message = "It need to cost something? NOTHING IS FREE FFS!";
            }

            if (!isset($description)) {
                $message = "Okey yea i know description sucks but add it!!!";
            }
            if ($message != "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => $message));
            }

            if ($request->hasFile('box') and $request->file('box')->isValid()) {
                UserHelper::saveAnimatedImage($request->file('box'), $boxid, '_assets/img/boxes/');
            }

            DB::table('boxes')->where('boxid', $boxid)->update([
                'name' => $name,
                'description' => $description,
                'duplicate' => $duplicate,
                'price' => $price
            ]);

            /* ADMIN LOG
            DB::table('admin_log')->insert([
              'userid' => Auth::user()->userid,
              'description' => 'Updated Name icon',
              'content' => 14,
              'contentid' => $iconid,
              'affected_userid' => 0,
              'ip' => Auth::user()->lastip,
              'dateline' => time()
    ]); */

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Box you try to update dont exist!'));
        }
    }

    public function getEditBackground($backgroundid)
    {
        $background = DB::table('backgrounds')->where('backgroundid', $backgroundid)->first();

        $returnHTML = view('admincp.shop.editBackground')
            ->with('background', $background)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditBox($boxid)
    {
        $box = DB::table('boxes')->where('boxid', $boxid)->first();

        $returnHTML = view('admincp.shop.editBox')
            ->with('box', $box)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditNameIcon($iconid)
    {
        $icon = DB::table('name_icons')->where('iconid', $iconid)->first();

        $returnHTML = view('admincp.shop.editNameIcon')
            ->with('icon', $icon)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditSticker($stickerid)
    {
        $sticker = DB::table('stickers')->where('stickerid', $stickerid)->first();

        $returnHTML = view('admincp.shop.editSticker')
            ->with('sticker', $sticker)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditNameEffect($effectid)
    {
        $effect = DB::table('name_effects')->where('effectid', $effectid)->first();

        $returnHTML = view('admincp.shop.editNameEffect')
            ->with('effect', $effect)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }


    public function postRemoveSticker(Request $request)
    {
        $stickerid = $request->input('stickerid');

        DB::table('stickers')->where('stickerid', $stickerid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed Sticker',
            'content' => 14,
            'contentid' => $iconid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveCarousel(Request $request)
    {
        $carouselid = $request->input('carouselid');

        DB::table('carousel')->where('carouselid', $carouselid)->delete();

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveNameIcon(Request $request)
    {
        $iconid = $request->input('iconid');

        DB::table('name_icons')->where('iconid', $iconid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed Name icon',
            'content' => 14,
            'contentid' => $iconid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveBackground(Request $request)
    {
        $backgroundid = $request->input('backgroundid');

        DB::table('backgrounds')->where('backgroundid', $backgroundid)->delete();

        /* ADMIN L OG
        DB::table('admin_log')->insert([
          'userid' => Auth::user()->userid,
          'description' => 'Removed Name icon',
          'content' => 14,
          'contentid' => $iconid,
          'affected_userid' => 0,
          'ip' => Auth::user()->lastip,
          'dateline' => time()
  ]); */

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveBox(Request $request)
    {
        $boxid = $request->input('boxid');

        DB::table('boxes')->where('boxid', $boxid)->delete();

        /* ADMIN L OG
        DB::table('admin_log')->insert([
          'userid' => Auth::user()->userid,
          'description' => 'Removed Name icon',
          'content' => 14,
          'contentid' => $iconid,
          'affected_userid' => 0,
          'ip' => Auth::user()->lastip,
          'dateline' => time()
  ]); */

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveNameEffect(Request $request)
    {
        $effectid = $request->input('effectid');

        DB::table('name_effects')->where('effectid', $effectid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed Name Effect',
            'content' => 16,
            'contentid' => $effectid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postNewCarousel(Request $request)
    {
        $text = $request->input('text');
        $link = $request->input('link');
        $message = "";
        if (!isset($name)) {
            $message = "Must have text";
        }
        if (!isset($link)) {
            $message = "No link!";
        }

        if ($request->hasFile('carousel') and $request->file('carousel')->isValid()) {
            $carouselid = DB::table('carousel')->insertGetId([
                'link' => $link,
                'text' => $text
            ]);

            UserHelper::saveAnimatedImage($request->file('carousel'), $carouselid, '_assets/img/carousel/');

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a carousel image to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function saveAds(Request $request)
    {
        $ad1 = $request->input('ad1');
        $ad2 = $request->input('ad2');


        if ($ad1 != $ad2) {
            $ad1check = DB::table('carousel')->where('carouselid', $ad1)->get();
            $ad2check = DB::table('carousel')->where('carouselid', $ad2)->get();

            if (count($ad1check) && count($ad2check)) {
                DB::table('carousel')->where('adverts', 1)->update([
                    'adverts' => 0
                ]);
                DB::table('carousel')->where('adverts', 2)->update([
                    'adverts' => 0
                ]);
                DB::table('carousel')->where('carouselid', $ad1)->update([
                    'adverts' => 1
                ]);

                DB::table('carousel')->where('carouselid', $ad2)->update([
                    'adverts' => 2
                ]);

                return response()->json(array('success' => true, 'response' => true));
            }
        }

        return response()->json(array('success' => true, 'response' => false, 'message'=>$ad1.' and '.$ad2));
    }

    public function postNewEffect(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $limit = $request->input('limit');
        $thcb = $request->input('thcb');
        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }
        if (!isset($desc)) {
            $message = "Okey yea i know description sucks but add it!!!";
        }
        if (!isset($price)) {
            $message = "It need to cost something? NOTHING IS FREE FFS!";
        }
        if (!isset($usergroups)) {
            $usergroups = '-1';
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('effect') and $request->file('effect')->isValid()) {
            $effectid = DB::table('name_effects')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('effect'), $effectid, '_assets/img/nameeffects/');
            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Added Name Effect',
                'content' => 16,
                'contentid' => $effectid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
            $avatar = UserHelper::getAvatar(Auth::user()->userid);
            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a effect to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function postNewIcon(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $limit = $request->input('limit');
        $thcb = $request->input('thcb');
        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }
        if (!isset($desc)) {
            $message = "Okey yea i know description sucks but add it!!!";
        }
        if (!isset($price)) {
            $message = "It need to cost something? NOTHING IS FREE FFS!";
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('icon') and $request->file('icon')->isValid()) {
            $iconid = DB::table('name_icons')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('icon'), $iconid, '_assets/img/nameicons/');

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Added Name icon',
                'content' => 14,
                'contentid' => $iconid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a icon to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function postNewBox(Request $request)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');
        $duplicate = $request->input('duplicate');

        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }

        if (!isset($description)) {
            $message = "Okey yea i know description sucks but add it!!!";
        }

        if (!isset($price)) {
            $message = "It need to cost something? NOTHING IS FREE FFS!";
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('box') and $request->file('box')->isValid()) {
            $boxid = DB::table('boxes')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'duplicate' => $duplicate,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('box'), $boxid, '_assets/img/boxes/');

            /* ADMIN LOG
            DB::table('admin_log')->insert([
              'userid' => Auth::user()->userid,
              'description' => 'Added Name icon',
              'content' => 14,
              'contentid' => $iconid,
              'affected_userid' => 0,
              'ip' => Auth::user()->lastip,
              'dateline' => time()
    ]);*/

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a box to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function postNewBackground(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $limit = $request->input('limit');
        $thcb = $request->input('thcb');
        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }
        if (!isset($desc)) {
            $message = "Okey yea i know description sucks but add it!!!";
        }
        if (!isset($price)) {
            $message = "It need to cost something? NOTHING IS FREE FFS!";
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('background') and $request->file('background')->isValid()) {
            $backgroundid = DB::table('backgrounds')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'thcb' => $thcb,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('background'), $backgroundid, '_assets/img/backgrounds/');

            /* ADMIN LOG
            DB::table('admin_log')->insert([
              'userid' => Auth::user()->userid,
              'description' => 'Added Name icon',
              'content' => 14,
              'contentid' => $iconid,
              'affected_userid' => 0,
              'ip' => Auth::user()->lastip,
              'dateline' => time()
    ]);*/

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a icon to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function postNewSticker(Request $request)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $limit = $request->input('limit');
        $thcb = $request->input('thcb');
        $message = "";
        if (!isset($name)) {
            $message = "Hello, adding something without a name are we?";
        }
        if (!isset($desc)) {
            $message = "Add a description!";
        }
        if (!isset($price)) {
            $message = "Add a price!";
        }
        if ($message != "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        if ($request->hasFile('sticker') and $request->file('sticker')->isValid()) {
            $stickerid = DB::table('stickers')->insertGetId([
                'name' => $name,
                'price' => $price,
                'description' => $desc,
                'limit' => $limit,
                'thcb' => $thcb,
                'dateline' => time()
            ]);

            UserHelper::saveAnimatedImage($request->file('sticker'), $stickerid, '_assets/img/stickers/');

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Added sticker',
                'content' => 14,
                'contentid' => $stickerid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            $message = "You need to pick a sticker to upload!";
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }
    }

    public function getCarousel()
    {
        $carousel = array();

        $temps = DB::table('carousel')->get();
        foreach ($temps as $temp) {
            $carousel[] = array(
                'id' => $temp->carouselid,
                'text' => $temp->text,
                'link' => $temp->link,
                'adverts' => $temp->adverts,
                'image' => asset('_assets/img/carousel/'.$temp->carouselid.'.gif'),
            );
        }

        $returnHTML = view('admincp.carousel')
            ->with('carousel', $carousel)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBackgrounds()
    {
        $backgrounds = array();

        $temps = DB::table('backgrounds')->orderBy('name', 'ASC')->get();
        foreach ($temps as $temp) {
            $backgrounds[] = array(
                'name' => $temp->name,
                'desc' => $temp->description,
                'price' => $temp->price,
                'background' => $avatar = asset('_assets/img/backgrounds/'.$temp->backgroundid.'.gif?v='.time()),
                'backgroundid' => $temp->backgroundid,
                'limit' => $temp->limit > -1 ? $temp->limit : 'Unlimited'
            );
        }

        $returnHTML = view('admincp.shop.listBackgrounds')
            ->with('backgrounds', $backgrounds)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBoxes()
    {
        $boxes = array();

        $temps = DB::table('boxes')->orderBy('name', 'ASC')->get();
        foreach ($temps as $temp) {
            $boxes[] = array(
                'name' => $temp->name,
                'price' => $temp->price,
                'box' => $avatar = asset('_assets/img/boxes/'.$temp->boxid.'.gif?v='.time()),
                'boxid' => $temp->boxid
            );
        }

        $returnHTML = view('admincp.shop.listBoxes')
            ->with('boxes', $boxes)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNameIcons()
    {
        $nameicons = array();

        $temps = DB::table('name_icons')->orderBy('name', 'ASC')->get();
        foreach ($temps as $temp) {
            $nameicons[] = array(
                'name' => $temp->name,
                'desc' => $temp->description,
                'price' => $temp->price,
                'icon' => $avatar = asset('_assets/img/nameicons/'.$temp->iconid.'.gif?v='.time()),
                'iconid' => $temp->iconid,
                'limit' => $temp->limit > -1 ? $temp->limit : 'Unlimited'
            );
        }

        $returnHTML = view('admincp.shop.listNameIcons')
            ->with('nameicons', $nameicons)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getStickers()
    {
        $stickers = array();

        $temps = DB::table('stickers')->orderBy('name', 'ASC')->get();
        foreach ($temps as $temp) {
            $stickers[] = array(
                'name' => $temp->name,
                'desc' => $temp->description,
                'price' => $temp->price,
                'sticker' => $avatar = asset('_assets/img/stickers/'.$temp->stickerid.'.gif?v='.time()),
                'stickerid' => $temp->stickerid,
                'limit' => $temp->limit > -1 ? $temp->limit : 'Unlimited'
            );
        }

        $returnHTML = view('admincp.shop.listStickers')
            ->with('stickers', $stickers)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNameEffects()
    {
        $nameeffects = array();

        $temps = DB::table('name_effects')->orderBy('name', 'ASC')->get();
        foreach ($temps as $temp) {
            $nameeffects[] = array(
                'name' => $temp->name,
                'desc' => $temp->description,
                'price' => $temp->price,
                'effect' => $avatar = asset('_assets/img/nameeffects/'.$temp->effectid.'.gif?v='.time()),
                'effectid' => $temp->effectid,
                'limit' => $temp->limit > -1 ? $temp->limit : 'Unlimited'
            );
        }

        $returnHTML = view('admincp.shop.listNameEffects')
            ->with('nameeffects', $nameeffects)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewNameEffect()
    {
        $returnHTML = view('admincp.shop.newNameEffect')
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewNameIcon()
    {
        $returnHTML = view('admincp.shop.newNameIcon')
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewBackground()
    {
        $returnHTML = view('admincp.shop.newBackground')
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewBox()
    {
        $returnHTML = view('admincp.shop.newBox')
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getNewSticker()
    {
        $returnHTML = view('admincp.shop.newSticker')
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postAddLevels(Request $request)
    {
        $name = $request->input('name');
        $posts = $request->input('posts');

        $levelid = DB::table('xp_levels')->insertGetId([
            'name' => $name,
            'posts' => $posts,
            'dateline' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added XP Level',
            'content' => 13,
            'contentid' => $levelid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemoveLevels(Request $request)
    {
        $levelid = $request->input('levelid');

        DB::table('xp_levels')->where('levelid', $levelid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted XP Level',
            'content' => 13,
            'contentid' => $levelid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditLevels(Request $request)
    {
        $levelid = $request->input('xplevelid');
        $name = $request->input('name');
        $posts = $request->input('posts');

        DB::table('xp_levels')->where('levelid', $levelid)->update([
            'name' => $name,
            'posts' => $posts,
            'dateline' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited XP Level',
            'content' => 18,
            'contentid' => $levelid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditXPLevel($xplevelid)
    {
        $xplevel = DB::table('xp_levels')->where('levelid', $xplevelid)->first();

        $name = $xplevel->name;
        $posts = $xplevel->posts;
        $levelid = $xplevel->levelid;

        $returnHTML = view('admincp.extras.editXPLevels')
            ->with('name', $name)
            ->with('posts', $posts)
            ->with('levelid', $levelid)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getXPLevels()
    {
        $temps = DB::table('xp_levels')->orderBy('posts', 'ASC')->get();
        $levels = array();

        foreach ($temps as $temp) {
            $array = array(
                'levelid' => $temp->levelid,
                'name' => $temp->name,
                'posts' => $temp->posts,
                'dateline' => ForumHelper::timeago($temp->dateline)
            );

            $levels[] = $array;
        }

        $returnHTML = view('admincp.xpLevels')
            ->with('levels', $levels)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postAddPrefix(Request $request)
    {
        $text = $request->input('text');
        $style = $request->input('style');
        $forumid = $request->input('forumid');

        $prefixid = DB::table('prefixes')->insertGetId([
            'text' => $text,
            'style' => $style,
            'forumid' => $forumid
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added prefix',
            'content' => 13,
            'contentid' => $prefixid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postRemovePrefix(Request $request)
    {
        $prefixid = $request->input('prefixid');

        DB::table('prefixes')->where('prefixid', $prefixid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted prefix',
            'content' => 13,
            'contentid' => $prefixid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditPrefix(Request $request)
    {
        $prefixid = $request->input('prefixid');
        $text = $request->input('text');
        $style = $request->input('style');
        $forumid = $request->input('forumid');

        DB::table('prefixes')->where('prefixid', $prefixid)->update([
            'text' => $text,
            'style' => $style,
            'forumid' => $forumid
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited Thread Prefix',
            'content' => 17,
            'contentid' => $prefixid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditPrefix($prefixid)
    {
        $prefix = DB::table('prefixes')->where('prefixid', $prefixid)->first();

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

        $returnHTML = view('admincp.extras.editPrefix')
            ->with('text', $prefix->text)
            ->with('style', $prefix->style)
            ->with('forums', $forums)
            ->with('prefix', $prefix)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getPrefixes()
    {
        $temps = DB::table('prefixes')->orderBy('prefixid', 'DESC')->get();

        $prefixes = array();

        foreach ($temps as $temp) {
            if ($temp->forumid == 0) {
                $forum = "All";
            } else {
                $frm = DB::table('forums')->where('forumid', $temp->forumid)->first();

                if (count($frm)) {
                    $forum = $frm->title;
                } else {
                    $forum = "Forum don't exist";
                }
            }

            $array = array(
                'text' => $temp->text,
                'style' => $temp->style,
                'prefixid' => $temp->prefixid,
                'forum' => $forum
            );

            $prefixes[] = $array;
        }

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

        $returnHTML = view('admincp.prefixes')
            ->with('prefixes', $prefixes)
            ->with('forums', $forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getModLog($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = false;
        $username = '';
        $content = '';
        if (isset($_GET['username']) && $_GET['username'] != '') {
            $username = $_GET['username'];
            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($user)) {
                $userid = $user->userid;
                $username = '?username=' . $username;
            }
        }
        if (isset($_GET['content']) && $_GET['content'] != '') {
            $content = 'content = ' . $_GET['content'];
        } else {
            $content = 'content > 0';
        }

        $take = 30;
        $skip = 0;

        if ($userid) {
            $pagesx = DB::table('mod_log')->where('userid', $userid)->whereRaw($content)->count();
        } else {
            $pagesx = DB::table('mod_log')->whereRaw($content)->count();
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

        if ($userid) {
            $temps = DB::table('mod_log')->take($take)->whereRaw($content)->where('userid', $userid)->skip($skip)->orderBy('logid', 'DESC')->get();
        } else {
            $temps = DB::table('mod_log')->take($take)->whereRaw($content)->skip($skip)->orderBy('logid', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $content = "no idea";
            $contentid = "Not applicable";

            switch ($temp->content) {
                case 1:
                    $content = "Thread";
                    $thread = DB::table('threads')->where('threadid', $temp->contentid)->first();
                    if (count($thread)) {
                        $contentid = $thread->title;
                    }
                    break;
                case 2:
                    $content = "Post";
                    break;
                case 3:
                    $content = "Article Comment";
                    break;
                case 4:
                    $content = "Visitor Message";
                    break;
                case 5:
                    $content = "User";
                    break;
                case 6:
                    $content = "Creation";
                    break;
                case 7:
                    $content = "Flagged article";
                    break;
            }

            $array = array(
                'mod_username' => UserHelper::getUsername($temp->userid, true),
                'description' => $temp->description,
                'content' => $content,
                'contentid' => $contentid,
                'affected_user' => UserHelper::getUsername($temp->affected_userid, true),
                'time' => ForumHelper::getTimeInDate($temp->dateline),
            );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/modlog/page/')->render();

        $returnHTML = view('admincp.logs.modlog')
            ->with('logs', $logs)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAdminLog($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $userid = false;
        $username = '';
        $content = '';
        if (isset($_GET['username']) && $_GET['username'] != '') {
            $username = $_GET['username'];
            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();
            if (count($user)) {
                $userid = $user->userid;
                $username = '?username=' . $username;
            }
        }
        if (isset($_GET['content']) && $_GET['content'] != '') {
            $content = 'content = ' . $_GET['content'];
        } else {
            $content = 'content > 0';
        }

        $take = 30;
        $skip = 0;

        if ($userid) {
            $pagesx = DB::table('admin_log')->where('userid', $userid)->whereRaw($content)->count();
        } else {
            $pagesx = DB::table('admin_log')->whereRaw($content)->count();
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

        if ($pagenr >= 2) {
            $skip = $take*$pagenr - $take;
        }

        if ($userid) {
            $temps = DB::table('admin_log')->take($take)->whereRaw($content)->where('userid', $userid)->skip($skip)->orderBy('logid', 'DESC')->get();
        } else {
            $temps = DB::table('admin_log')->take($take)->whereRaw($content)->skip($skip)->orderBy('logid', 'DESC')->get();
        }

        $logs = array();

        foreach ($temps as $temp) {
            $content = "no idea";
            $contentid = "Not applicable";

            switch ($temp->content) {
                case 1:
                    $content = "Usergroup";
                    $grp = DB::table('usergroups')->where('usergroupid', $temp->contentid)->first();
                    if (count($grp)) {
                        $contentid = $grp->title;
                    }
                    break;
                case 2:
                    $content = "Subscription";
                    $pkg = DB::table('subscription_packages')->where('packageid', $temp->contentid)->first();
                    if (count($pkg)) {
                        $contentid = $pkg->name;
                    }
                    break;
                case 3:
                    $content = "User";
                    $contentid = UserHelper::getUsername($temp->contentid);
                    break;
                case 4:
                    $content = "BBcode";
                    $bbc = DB::table('bbcodes')->where('bbcodeid', $temp->contentid)->first();
                    if (count($bbc)) {
                        $contentid = $bbc->name;
                    }
                    break;
                case 5:
                    $content = "Moderation Forum";
                    break;
                case 6:
                    $content = "Maintenance";
                    break;
                case 7:
                    $content = "Automated Thread";
                    break;
                case 8:
                    $content = "Forum";
                    $frm = DB::table('forums')->where('forumid', $temp->contentid)->first();
                    if (count($frm)) {
                        $contentid = $frm->title;
                    }
                    break;
                case 9:
                    $content = "Others";
                    break;
                case 10:
                    $content = "Subscriptions";
                    break;
                case 11:
                    $content = "Badge to User";
                    break;
                case 12:
                    $content = "Manage Badges";
                    break;
                case 13:
                    $content = "Prefix";
                    break;
                case 14:
                    $content = "Name Icon";
                    break;
                case 15:
                    $contentid = 'Worth: ' . $temp->affected_userid;
                    $content = "Voucher Code";
                    break;
                case 16:
                    $content = "Name Effect";
                    break;
                case 17:
                    $content = "Manage Prefixes";
                    $pref = DB::table('prefixes')->where('prefixid', $temp->contentid)->first();
                    if (count($pref)) {
                        $contentid = $pref->text;
                    }
                    break;
                case 18:
                    $content = "Manage XP Level";
                    $postl = DB::table('xp_levels')->where('levelid', $temp->contentid)->first();
                    if (count($postl)) {
                        $contentid = $postl->name;
                    }
                    break;
                case 19:
                    $content = "Manage Site Notices";
                    $snotice = DB::table('site_notices')->where('noticeid', $temp->contentid)->first();
                    if (count($snotice)) {
                        $contentid = $snotice->title;
                    }
                    break;
                case 20:
                    $bet = DB::table('betting_bets')->where('betid', $temp->contentid)->first();
                    $content = "Betting Hub";
                    if ($bet) {
                        $contentid = $bet->bet;
                    } else {
                        $contentid = "_blank_";
                    }
                    break;
            }

            $array = array(
                'admin_username' => UserHelper::getUsername($temp->userid, true),
                'description' => $temp->description,
                'content' => $content,
                'contentid' => $contentid,
                'affected_user' => UserHelper::getUsername($temp->affected_userid, true),
                'time' => ForumHelper::getTimeInDate($temp->dateline),
            );

            $logs[] = $array;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/adminlog/page/')->render();

        $returnHTML = view('admincp.logs.adminlog')
            ->with('logs', $logs)
            ->with('pagi', $pagi)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveUserSubscription(Request $request)
    {
        $userid = $request->input('userid');
        $subid = $request->input('subid');

        $subscription = DB::table('subscription_subs')->where('subid', $subid)->first();

        $package = DB::table('subscription_packages')->where('packageid', $subscription->packageid)->first();
        $user = DB::table('users')->where('userid', $userid)->first();

        $new_usergroups = "";
        $first = true;
        $display_safe = 0;

        $usergroups = explode(",", $user->usergroups);

        if (in_array($package->usergroupid, $usergroups)) {
            foreach ($usergroups as $usergroup) {
                if ($usergroup != $package->usergroupid) {
                    if ($first) {
                        $new_usergroups = $usergroup;
                        $first = false;
                    } else {
                        $new_usergroups = $new_usergroups . ',' . $usergroup;
                    }

                    $display_safe = $usergroup;
                }
            }

            DB::table('users')->where('userid', $userid)->update(['usergroups' => $new_usergroups]);
        }

        if ($user->displaygroup == $package->usergroupid) {
            DB::table('users')->where('userid', $userid)->update(['displaygroup' => $display_safe]);
        }

        DB::table('subscription_subs')->where('userid', $userid)->where('subid', $subid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed users subscription',
            'content' => 10,
            'contentid' => 0,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postAddUserSubscription(Request $request)
    {
        $packageid = $request->input('packageid');
        $userid = $request->input('userid');
        $end_date = strtotime($request->input('end_date'));

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();
        $user = DB::table('users')->where('userid', $userid)->first();

        if ($end_date < time()) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Can\'t add end date that have already passed!'));
        }

        $check = DB::table('subscription_subs')->where('userid', $userid)->where('packageid', $packageid)->count();

        if ($check > 0) {
            DB::table('subscription_subs')->where('userid', $userid)->where('packageid', $packageid)->update([
                'end_date' => $end_date
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated users end date on subscription',
                'content' => 10,
                'contentid' => $packageid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
        } else {
            DB::table('subscription_subs')->insert([
                'userid' => $userid,
                'packageid' => $packageid,
                'start_date' => time(),
                'end_date' => $end_date
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Added new subscription to user',
                'content' => 10,
                'contentid' => $packageid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);
        }

        $usergroups = explode(",", $user->usergroups);

        $new_usergroups = "";
        $first = true;

        if (!in_array($package->usergroupid, $usergroups)) {
            foreach ($usergroups as $usergroup) {
                if ($first) {
                    $new_usergroups = $usergroup;
                    $first = false;
                } else {
                    $new_usergroups = $new_usergroups . ',' . $usergroup;
                }
            }

            if ($first) {
                $new_usergroups = $package->usergroupid;
            } else {
                $new_usergroups = $new_usergroups . ',' . $package->usergroupid;
            }

            DB::table('users')->where('userid', $userid)->update(['usergroups' => $new_usergroups]);
        }

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getUsersSubscriptions($userid)
    {
        $temps = DB::table('subscription_subs')->where('userid', $userid)->get();

        $users_subs = array();

        $user = DB::table('users')->where('userid', $userid)->first();

        foreach ($temps as $temp) {
            $package = DB::table('subscription_packages')->where('packageid', $temp->packageid)->first();

            if (count($package)) {
                $array = array(
                    'subid' => $temp->subid,
                    'name' => $package->name,
                    'started' => ForumHelper::getTimeInDate($temp->start_date),
                    'ends' => ForumHelper::getTimeInDate($temp->end_date, true)
                );

                $users_subs[] = $array;
            }
        }

        $available_subs = array();
        $packages = DB::table('subscription_packages')->orderBy('name', 'ASC')->get();

        foreach ($packages as $package) {
            $array = array(
                'packageid' => $package->packageid,
                'name' => $package->name,
            );

            $available_subs[] = $array;
        }

        $returnHTML = view('admincp.userSubs')
            ->with('users_subs', $users_subs)
            ->with('available_subs', $available_subs)
            ->with('user', $user)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditDefaultPerms(Request $request)
    {
        $forumid = $request->input('forumid');
        $permissions = $request->input('permissions');

        $check = DB::table('forumpermissions')->where('usergroupid', 0)->where('forumid', $forumid)->count();

        if ($check > 0) {
            DB::table('forumpermissions')->where('usergroupid', 0)->where('forumid', $forumid)->update([
                'forumpermissions' => $permissions
            ]);
        } else {
            DB::table('forumpermissions')->insert([
                'usergroupid' => 0,
                'forumid' => $forumid,
                'forumpermissions' => $permissions
            ]);
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited default forum permissions',
            'content' => 1,
            'contentid' => 0,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getDefaultPermsGroup($forumid)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            $can_see_forum = UserHelper::haveForumPerm(0, $forumid, 1) ? true : false;
            $can_create_thread = UserHelper::haveForumPerm(0, $forumid, 2) ? true : false;
            $can_reply_to_others_threads = UserHelper::haveForumPerm(0, $forumid, 4) ? true : false;
            $can_edit_own_post = UserHelper::haveForumPerm(0, $forumid, 8) ? true : false;
            $can_skip_approve_thread = UserHelper::haveForumPerm(0, $forumid, 16) ? true : false;
            $can_see_others_threads = UserHelper::haveForumPerm(0, $forumid, 32) ? true : false;
            $can_skip_double_post = UserHelper::haveForumPerm(0, $forumid, 64) ? true : false;
            $can_reply_to_own_threads = UserHelper::haveForumPerm(0, $forumid, 128) ? true : false;

            $returnHTML = view('admincp.extras.editDefault')
                ->with('can_see_forum', $can_see_forum)
                ->with('can_create_thread', $can_create_thread)
                ->with('can_reply_to_others_threads', $can_reply_to_others_threads)
                ->with('can_edit_own_post', $can_edit_own_post)
                ->with('can_skip_approve_thread', $can_skip_approve_thread)
                ->with('can_see_others_threads', $can_see_others_threads)
                ->with('can_skip_double_post', $can_skip_double_post)
                ->with('can_reply_to_own_threads', $can_reply_to_own_threads)
                ->with('forum', $forum)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function getDefaultPerms()
    {
        $userid = Auth::user()->userid;
        $parents = DB::table('forums')->where('parentid', -1)->get();

        $forums = array();

        foreach ($parents as $parent) {
            if (UserHelper::haveForumPerm($userid, $parent->forumid, 1)) {
                $array = array(
                    'forumid' => $parent->forumid,
                    'title' => $parent->title,
                    'displayorder' => $parent->displayorder,
                    'can_see' => UserHelper::haveForumPerm(0, $parent->forumid, 1) ? true : false,
                    'childs' => array()
                );

                $temps = DB::table('forums')->where('parentid', $parent->forumid)->get();

                foreach ($temps as $temp) {
                    if (UserHelper::haveForumPerm($userid, $temp->forumid, 1)) {
                        $ar = array(
                            'forumid' => $temp->forumid,
                            'displayorder' => $temp->displayorder,
                            'title' => '-' . $temp->title,
                            'can_see' => UserHelper::haveForumPerm(0, $temp->forumid, 1) ? true : false,
                            'childs' => 0
                        );

                        $chi = self::getForumChildsDefault($userid, $temp->forumid, '--');

                        $ar['childs'] = $chi;

                        $array['childs'][] = $ar;
                    }
                }

                $forums[] = $array;
            }
        }

        $returnHTML = view('admincp.listDefaultForums')
            ->with('forums', $forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveStaff(Request $request)
    {
        $groupid = $request->input('groupid');

        DB::table('staff_list')->where('usergroupid', $groupid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed usergroup from staff list',
            'content' => 1,
            'contentid' => $groupid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postAddStaff(Request $request)
    {
        $groupid = $request->input('groupid');
        $displayorder = $request->input('displayorder');
        $color = $request->input('color');
        $customrole = $request->input('customrole');

        $response = true;
        $message = "";

        $check = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (!count($check)) {
            $response = false;
            $message = "Usergroup don't exist!";
        }

        $check = DB::table('staff_list')->where('usergroupid', $groupid)->first();

        if (count($check)) {
            $response = false;
            $message = "Usergroup already in the list!";
        }

        if (!is_numeric($displayorder)) {
            $response = false;
            $message = "Display order needs to be a number!";
        }

        if (!$response) {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message));
        }

        DB::table('staff_list')->insert([
            'usergroupid' => $groupid,
            'displayorder' => $displayorder,
            'color' => $color,
            'custom' => $customrole
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added usergroup to staff list',
            'content' => 1,
            'contentid' => $groupid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getManageStaffList()
    {
        $temps = DB::table('staff_list')->orderBy('displayorder', 'ASC')->get();

        $groups = array();
        $grpids = array();
        foreach ($temps as $temp) {
            $group = DB::table('usergroups')->where('usergroupid', $temp->usergroupid)->first();

            if (count($group)) {
                $array = array(
                    'groupid' => $temp->usergroupid,
                    'title' => $group->title,
                    'color' => $temp->color,
                    'displayorder' => $temp->displayorder,
                    'custom' => $temp->custom
                );

                $groups[] = $array;
                $grpids[] = $temp->usergroupid;
            }
        }

        $usergroups = DB::table('usergroups')->whereNotIn('usergroupid', $grpids)->get();

        $returnHTML = view('admincp.manageStaffList')
            ->with('groups', $groups)
            ->with('usergroups', $usergroups)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditSubscription(Request $request)
    {
        $name = $request->input('name');
        $userbartext = $request->input('userbartext');
        $usergroup = $request->input('usergroup');
        $dprice = $request->input('dprice');
        $usernamefeature = $request->input('usernamefeature');
        $userbarfeature = $request->input('userbarfeature');
        $description = $request->input('description');
        $packageid = $request->input('packageid');
        $days = $request->input('days');

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();

        if (!count($package)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Package don\'t exist!', 'field' => ''));
        }

        $response = true;
        $message = "";
        $field = "";

        if (strlen($name) <= 0) {
            $response = false;
            $message = "Name can't be empty!";
            $field = "sub-form-name";
        }

        if (strlen($userbartext) <= 0) {
            $response = false;
            $message = "Userbar text can't be empty!";
            $field = "sub-form-userbartext";
        }

        $check = DB::table('usergroups')->where('usergroupid', $usergroup)->first();

        if (!count($check)) {
            $response = false;
            $message = "Usergroup don't exist!";
            $field = "sub-form-usergroup";
        }

        $check1 = DB::table('subscription_packages')->where('usergroupid', $usergroup)->where('packageid', '!=', $packageid)->count();

        if ($check1 > 0) {
            $response = false;
            $message = "Usergroup already have subscription linked to it!";
            $field = "";
        }

        if (strlen($dprice) <= 0 or !is_numeric($dprice)) {
            $response = false;
            $message = "Price is invalid or not set!";
            $field = "sub-form-dprice";
        }

        if ($usernamefeature != 0 and $usernamefeature != 2 and $usernamefeature != 4 and $usernamefeature != 8) {
            $response = false;
            $message = "Username feature is invalid!";
            $field = "sub-form-usernamefeature";
        }

        if ($userbarfeature != 0 and $userbarfeature != 16 and $userbarfeature != 32 and $userbarfeature != 64) {
            $response = false;
            $message = "Userbar feature is invalid!";
            $field = "sub-form-userbarfeature";
        }

        if (strlen($description) <= 0) {
            $response = false;
            $message = "Description can't be empty!";
            $field = "sub-form-description";
        }

        if (!$response) {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message, 'field' => $field));
        }

        DB::table('subscription_packages')->where('packageid', $packageid)->update([
            'name' => $name,
            'description' => $description,
            'usergroupid' => $usergroup,
            'dprice' => $dprice,
            'userbar_text' => $userbartext,
            'days' => $days,
            'lastedit' => time()
        ]);

        $features = $userbarfeature + $usernamefeature + 1;

        DB::table('usergroups')->where('usergroupid', $usergroup)->update([
            'features' => $features,
            'lastedited' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited subscription',
            'content' => 2,
            'contentid' => $packageid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditSubscription($packageid)
    {
        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();

        if (count($package)) {
            $grp = DB::table('usergroups')->where('usergroupid', $package->usergroupid)->first();
            $usergroupid = 0;
            $usernamefeature = 0;
            $userbarfeature = 0;

            if (count($grp)) {
                $usergroupid = $grp->usergroupid;

                if ($grp->features & 2) {
                    $usernamefeature = 2;
                } elseif ($grp->features & 4) {
                    $usernamefeature = 4;
                } elseif ($grp->features & 8) {
                    $usernamefeature = 8;
                }

                if ($grp->features & 16) {
                    $userbarfeature = 16;
                } elseif ($grp->features & 32) {
                    $userbarfeature = 32;
                } elseif ($grp->features & 64) {
                    $userbarfeature = 64;
                }
            }

            $usergroups = DB::table('usergroups')->orderBy('title', 'ASC')->get();

            $returnHTML = view('admincp.shop.editSubscription')
                ->with('name', $package->name)
                ->with('userbartext', $package->userbar_text)
                ->with('usergroupid', $usergroupid)
                ->with('dprice', $package->dprice)
                ->with('usernamefeature', $usernamefeature)
                ->with('userbarfeature', $userbarfeature)
                ->with('description', $package->description)
                ->with('usergroups', $usergroups)
                ->with('packageid', $package->packageid)
                ->with('days', $package->days)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function postRemoveSubscription(Request $request)
    {
        $packageid = $request->input('packageid');

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();

        if (count($package)) {
            DB::table('usergroups')->where('usergroupid', $package->usergroupid)->update(['features' => 0]);
        }

        DB::table('subscription_packages')->where('packageid', $packageid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted subscription',
            'content' => 2,
            'contentid' => $packageid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postAddSubscription(Request $request)
    {
        $name = $request->input('name');
        $userbartext = $request->input('userbartext');
        $usergroup = $request->input('usergroup');
        $dprice = $request->input('dprice');
        $usernamefeature = $request->input('usernamefeature');
        $userbarfeature = $request->input('userbarfeature');
        $description = $request->input('description');
        $days = $request->input('days');

        $response = true;
        $message = "";
        $field = "";

        if (strlen($name) <= 0) {
            $response = false;
            $message = "Name can't be empty!";
            $field = "sub-form-name";
        }

        if (strlen($userbartext) <= 0) {
            $response = false;
            $message = "Userbar text can't be empty!";
            $field = "sub-form-userbartext";
        }

        $check = DB::table('usergroups')->where('usergroupid', $usergroup)->first();

        if (!count($check)) {
            $response = false;
            $message = "Usergroup don't exist!";
            $field = "sub-form-usergroup";
        }

        if (strlen($dprice) <= 0 or !is_numeric($dprice)) {
            $response = false;
            $message = "Price is invalid or not set!";
            $field = "sub-form-dprice";
        }

        if ($usernamefeature != 0 and $usernamefeature != 2 and $usernamefeature != 4 and $usernamefeature != 8) {
            $response = false;
            $message = "Username feature is invalid!";
            $field = "sub-form-usernamefeature";
        }

        if ($userbarfeature != 0 and $userbarfeature != 16 and $userbarfeature != 32 and $userbarfeature != 64) {
            $response = false;
            $message = "Userbar feature is invalid!";
            $field = "sub-form-userbarfeature";
        }

        if (strlen($description) <= 0) {
            $response = false;
            $message = "Description can't be empty!";
            $field = "sub-form-description";
        }

        if ($days <= 0) {
            $response = false;
            $message = "Must have more than 0 days!";
            $field = "sub-form-days";
        }

        if (!$response) {
            return response()->json(array('success' => true, 'response' => false, 'message' => $message, 'field' => $field));
        }

        $packageid = DB::table('subscription_packages')->insertGetId([
            'name' => $name,
            'description' => $description,
            'usergroupid' => $usergroup,
            'dprice' => $dprice,
            'userbar_text' => $userbartext,
            'days' => $days,
            'dateline' => time(),
            'lastedit' => time()
        ]);

        $features = $userbarfeature + $usernamefeature + 1;

        DB::table('usergroups')->where('usergroupid', $usergroup)->update([
            'features' => $features,
            'lastedited' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added subscription',
            'content' => 2,
            'contentid' => $packageid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getNewSubscription()
    {
        $usergroups = DB::table('usergroups')->orderBy('title', 'ASC')->get();

        $returnHTML = view('admincp.shop.newSubscription')
            ->with('usergroups', $usergroups)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getSubscriptions()
    {
        $subscriptions = array();

        $temps = DB::table('subscription_packages')->get();

        foreach ($temps as $temp) {
            $usergroup = "None";

            $grp = DB::table('usergroups')->where('usergroupid', $temp->usergroupid)->first();

            if (count($grp)) {
                $usergroup = $grp->title;
            }

            $array = array(
                'packageid' => $temp->packageid,
                'name' => $temp->name,
                'desc' => $temp->description,
                'usergroup' => $usergroup,
                'dprice' => $temp->dprice,
                'userbar_text' => $temp->userbar_text
            );

            $subscriptions[] = $array;
        }

        $returnHTML = view('admincp.shop.listSubscriptions')
            ->with('subscriptions', $subscriptions)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getIndex()
    {
        /* Users Count and Last User */
        $last = DB::table('users')->orderBy('userid', 'DESC')->first();
        $user_amount = $last->userid;
        $userlatest = $last->username;

        /* Threads */
        $thread_amount = 0;
        $thread_amount = DB::table('threads')->count();

        /* Posts */
        $post_amount = 0;
        $post_amount = DB::table('posts')->count();

        /* Most Replied to Thread */
        $replies_amount = 0;
        $replies_title = "";
        $replies_id = 0;
        $most = DB::table('threads')->orderBy('replys', 'DESC')->first();
        if (count($most)) {
            $replies_amount = $most->replys;
            $replies_title = $most->title;
            $replies_id = $most->threadid;
        }

        /* Most Active Forum */
        $frms = DB::table('forums')->where('parentid', '>', 0)->orderBy('posts', 'ASC')->get();
        $most_active_frm = 0;
        $most_active_frm_posts = 0;
        $most_active_frmid = 0;
        foreach ($frms as $frm) {
            if ($frm->posts > $most_active_frm) {
                $most_active_frm = $frm->title;
                $most_active_frm_posts = number_format($frm->posts);
                $most_active_frmid = $frm->forumid;
            }
        }

        $time = strtotime('today midnight');
        $posts_today = 0;
        $posts_today = DB::table('posts')->where('dateline', '>', $time)->count();

        $posts_this_week = 0;
        $posts_this_week = DB::table('stats_log')->orderBy('dateline', 'desc')->take(7)->get()->sum('posts');

        $posts_this_month = 0;
        $posts_this_month = DB::table('stats_log')->orderBy('dateline', 'desc')->take(30)->get()->sum('posts');

        $thcb_subscriptions = 0;
        $whatsthetime = time();
        $thcb_subscriptions = DB::table('subscription_subs')->where('end_date', '>=', $whatsthetime)->count();

        $thc_count = 0;
        $thc_count = DB::table('users')->where('credits', '!=', '0')->sum('credits');

        $shop_sold = 0;
        $shop_sold = DB::table('shop_transactions')->where('action', '!=', '3')->count();

        $thd_count = 0;
        $thd_count = DB::table('users')->where('diamonds', '!=', '0')->sum('diamonds');

        $returnHTML = view('admincp.index')
            ->with('posts_today', $posts_today)
            ->with('posts_this_week', $posts_this_week)
            ->with('posts_this_month', $posts_this_month)
            ->with('thcb_subscriptions', $thcb_subscriptions)
            ->with('thc_count', $thc_count)
            ->with('shop_sold', $shop_sold)
            ->with('thd_count', $thd_count)
            ->with('users', $user_amount)
            ->with('userlatest', $userlatest)
            ->with('threads', $thread_amount)
            ->with('posts', $post_amount)
            ->with('replies_id', $replies_id)
            ->with('replies_title', $replies_title)
            ->with('replies', $replies_amount)
            ->with('most_active_frm', '<a class="web-page" href="/forum/category/' . $most_active_frmid . '/page/1">' . $most_active_frm . '</a> with ' . $most_active_frm_posts . ' posts')
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function updateBio(Request $request)
    {
        $userid = $request->input('userid');
        $bio = $request->input('bio');

        if (strlen($bio) > 250) {
            $bio = substr($bio, 0, 250);
        }

        DB::table('users')->where('userid', $userid)->update(['bio' => $bio]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Updated users bio',
            'content' => 3,
            'contentid' => $userid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditBBcode(Request $request)
    {
        $name = $request->input('name');
        $example = $request->input('example');
        $pattern = $request->input('pattern');
        $replace = $request->input('replace');
        $content = $request->input('content');
        $hidden  = $request->input('hidden');
        $bbcodeid = $request->input('bbcodeid');

        $bbcode = DB::table('bbcodes')->where('bbcodeid', $bbcodeid)->first();

        if (count($bbcode)) {
            $message = "";

            if ($name == "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'Name can\'t be empty!', 'field' => 'bbcode-form-name'));
            }

            if ($example == "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'Example can\'t be empty!', 'field' => 'bbcode-form-example'));
            }

            if ($pattern == "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'Pattern can\'t be empty!', 'field' => 'bbcode-form-pattern'));
            }

            if ($replace == "") {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'Replace can\'t be empty!', 'field' => 'bbcode-form-replace'));
            }

            DB::table('bbcodes')->where('bbcodeid', $bbcodeid)->update([
                'name' => $name,
                'example' => $example,
                'pattern' => $pattern,
                'replace' => $replace,
                'content' => $content,
                'staff_specific' => $hidden
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Edited bbcode',
                'content' => 4,
                'contentid' => $bbcodeid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'This bbcode does not exist!'));
        }
    }

    public function getEditBBcode($bbcodeid)
    {
        $bbcode = DB::table('bbcodes')->where('bbcodeid', $bbcodeid)->first();

        if (count($bbcode)) {
            $returnHTML = view('admincp.extras.editBBcode')
                ->with('bbcode', $bbcode)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function postRemoveBBcode(Request $request)
    {
        $bbcodeid = $request->input('bbcodeid');

        DB::table('bbcodes')->where('bbcodeid', $bbcodeid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted bbcode',
            'content' => 4,
            'contentid' => $bbcodeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postAddBBcode(Request $request)
    {
        $name = $request->input('name');
        $example = $request->input('example');
        $pattern = $request->input('pattern');
        $replace = $request->input('replace');
        $content = $request->input('content');
        $hidden = $request->input('hidden');

        $message = "";

        if ($name == "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Name can\'t be empty!', 'field' => 'bbcode-form-name'));
        }

        if ($example == "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Example can\'t be empty!', 'field' => 'bbcode-form-example'));
        }

        if ($pattern == "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Pattern can\'t be empty!', 'field' => 'bbcode-form-pattern'));
        }

        if ($replace == "") {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Replace can\'t be empty!', 'field' => 'bbcode-form-replace'));
        }

        $check = DB::table('bbcodes')->where('name', 'like', $name)->count();

        if ($check > 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Name needs to be unique!', 'field' => 'bbcode-form-name'));
        }

        $bbcodeid = DB::table('bbcodes')->insertGetId([
            'name' => $name,
            'example' => $example,
            'pattern' => $pattern,
            'replace' => $replace,
            'content' => $content,
            'staff_specific' => $hidden,
            'dateline' => time()
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added bbcode',
            'content' => 4,
            'contentid' => $bbcodeid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getNewBBcode()
    {
        $returnHTML = view('admincp.extras.newBBcode')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBBcodes()
    {
        $bbcodes = array();
        $temps = DB::table('bbcodes')->get();

        foreach ($temps as $temp) {
            $array = array(
                'name' => $temp->name,
                'example' => $temp->example,
                'result' => ForumHelper::bbcodeParser($temp->example),
                'bbcodeid' => $temp->bbcodeid
            );

            $bbcodes[] = $array;
        }

        $returnHTML = view('admincp.manageBBcode')
            ->with('bbcodes', $bbcodes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveModforum(Request $request)
    {
        $mfid = $request->input('mfid');

        DB::table('moderation_forums')->where('mfid', $mfid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Removed moderation forum',
            'content' => 5,
            'contentid' => $mfid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postAddModforum(Request $request)
    {
        $forumid = $request->input('forum');
        $prefixid = $request->input('prefixid');
        $title = $request->input('title');
        $message = "";
        $response = false;

        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
                $check = DB::table('moderation_forums')->where('forumid', $forumid)->count();

                if ($check == 0) {
                    if ($title != "") {
                        $mfid = DB::table('moderation_forums')->insertGetId([
                            'title' => $title,
                            'forumid' => $forumid,
                            'prefixid' => $prefixid
                        ]);

                        DB::table('admin_log')->insert([
                            'userid' => Auth::user()->userid,
                            'description' => 'Added moderation forum',
                            'content' => 5,
                            'contentid' => $mfid,
                            'affected_userid' => 0,
                            'ip' => Auth::user()->lastip, 'dateline' => time()
                        ]);

                        $response = true;
                    } else {
                        $message = "Can't leave title empty!";
                    }
                } else {
                    $message = "This forum is already added!";
                }
            } else {
                $message = "You don't have access to this forum";
            }
        } else {
            $message = "That forum doesn't exist!";
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getModForum()
    {
        $temps = DB::table('forums')->where('parentid', '>', 0)->orderBy('title', 'ASC')->get();
        $forums = array();
        $prefixes = DB::table('prefixes')->get();

        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $temp->forumid, 1)) {
                $forums[] = array('forumid' => $temp->forumid, 'title' => $temp->title);
            }
        }

        $temps = DB::table('moderation_forums')->orderBy('mfid', 'DESC')->get();
        $mfs = array();

        foreach ($temps as $temp) {
            $forum = DB::table('forums')->where('forumid', $temp->forumid)->first();
            $prefix = DB::table('prefixes')->where('prefixid', $temp->prefixid)->first();

            if (count($forum)) {
                $array = array(
                    'title' => $temp->title,
                    'forum' => $forum->title,
                    'mfid' => $temp->mfid,
                    'prefix' => count($prefix) ? '<span style="' . $prefix->style . '">' . $prefix->text . '</span>' : ""
                );

                $mfs[] = $array;
            }
        }

        $returnHTML = view('admincp.moderationForums')
            ->with('mfs', $mfs)
            ->with('forums', $forums)
            ->with('prefixes', $prefixes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postStopMaintenance()
    {
        DB::table('maintenances')->where('active', 1)->update(['active' => 0]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Turned off maintenance',
            'content' => 6,
            'contentid' => 0,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true));
    }

    public function postStartMaintenance(Request $request)
    {
        $reason = $request->input('reason');

        $check = DB::table('maintenances')->where('active', 1)->count();

        if ($check == 0) {
            DB::table('maintenances')->insert([
                'reason' => $reason,
                'userid' => Auth::user()->userid,
                'active' => 1,
                'dateline' => time()
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Turned on maintenance',
                'content' => 6,
                'contentid' => 0,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function getMaintenances()
    {
        $temps = DB::table('maintenances')->where('active', 0)->take(10)->orderBy('maintenanceid', 'DESC')->get();
        $maintenances = array();

        foreach ($temps as $temp) {
            $array = array(
                'reason' => $temp->reason,
                'username' => UserHelper::getUsername($temp->userid),
                'date' => ForumHelper::getTimeInDate($temp->dateline)
            );

            $maintenances[] = $array;
        }

        $active = 0;
        $reason = "";
        $username = "";

        $check = DB::table('maintenances')->where('active', 1)->first();

        if (count($check)) {
            $active = 1;
            $reason = $check->reason;
            $username = UserHelper::getUsername($check->userid);
        }


        $returnHTML = view('admincp.manageMaintenance')
            ->with('active', $active)
            ->with('reason', $reason)
            ->with('username', $username)
            ->with('maintenances', $maintenances)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postSaveAutomated(Request $request)
    {
        $forumid = $request->input('forum');
        $title = $request->input('title');
        $content = $request->input('content');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $min = $request->input('min');
        $atid = $request->input('atid');

        $response = false;
        $message = "";


        $at = DB::table('automated_threads')->where('atid', $atid)->first();

        if (count($at)) {
            $forum = DB::table('forums')->where('forumid', $forumid)->first();

            if (count($forum)) {
                if ($forum->parentid > 0 and UserHelper::haveForumPerm(Auth::user()->userid, $forum->forumid, 1)) {
                    if ($title != "") {
                        if ($content != "") {
                            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

                            if (count($timezone)) {
                                if ($timezone->negative == 1) {
                                    $hour += $timezone->value;
                                } else {
                                    $hour -= $timezone->value;
                                }

                                if ($hour > 23) {
                                    $hour -= 24;
                                    $day += 1;
                                } elseif ($hour < 0) {
                                    $hour += 24;
                                    $day -= 1;
                                }

                                if ($day < 1) {
                                    $day += 7;
                                } elseif ($day > 7) {
                                    $day -= 7;
                                }

                                DB::table('automated_threads')->where('atid', $atid)->update([
                                    'forumid' => $forumid,
                                    'postuserid' => Auth::user()->userid,
                                    'title' => $title,
                                    'content' => $content,
                                    'day' => $day,
                                    'hour' => $hour,
                                    'minute' => $min
                                ]);

                                DB::table('admin_log')->insert([
                                    'userid' => Auth::user()->userid,
                                    'description' => 'Updated automated thread',
                                    'content' => 7,
                                    'contentid' => $atid,
                                    'affected_userid' => 0,
                                    'ip' => Auth::user()->lastip, 'dateline' => time()
                                ]);

                                $response = true;
                            }
                        } else {
                            $message = "Can't leave the content empty!";
                        }
                    } else {
                        $message = "Can't have an empty title!";
                    }
                } else {
                    $message = "You can't add automated thread to this forum!";
                }
            } else {
                $message = "Can't find forum!";
            }
        } else {
            $message = "This automated thread does not exists!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function getEditAutomated($atid)
    {
        $at = DB::table('automated_threads')->where('atid', $atid)->first();

        if (count($at)) {
            $temps = DB::table('forums')->where('parentid', '>', 0)->orderBy('title', 'ASC')->get();
            $forums = array();

            foreach ($temps as $temp) {
                if (UserHelper::haveForumPerm(Auth::user()->userid, $temp->forumid, 1)) {
                    $forums[] = array('forumid' => $temp->forumid, 'title' => $temp->title);
                }
            }

            $day = $at->day;
            $hour = $at->hour;
            $min = $at->minute;

            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $hour -= $timezone->value;
                } else {
                    $hour += $timezone->value;
                }

                if ($hour > 23) {
                    $hour += 24;
                    $day += 1;
                } elseif ($hour < 0) {
                    $hour -= 24;
                    $day -= 1;
                }

                if ($day > 7) {
                    $day -= 7;
                } elseif ($day < 1) {
                    $day += 7;
                }
            }

            $returnHTML = view('admincp.extras.editAt')
                ->with('at', $at)
                ->with('hour', $hour)
                ->with('day', $day)
                ->with('min', $min)
                ->with('forums', $forums)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getErrorPerm');
    }

    public function postDeleteAutomated(Request $request)
    {
        $atid = $request->input('atid');
        DB::table('automated_threads')->where('atid', $atid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted automated thread',
            'content' => 7,
            'contentid' => $atid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postAutomated(Request $request)
    {
        $forumid = $request->input('forum');
        $title = $request->input('title');
        $content = $request->input('content');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $min = $request->input('min');

        $response = false;
        $message = "";


        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            if ($forum->parentid > 0 and UserHelper::haveForumPerm(Auth::user()->userid, $forum->forumid, 1)) {
                if ($title != "") {
                    if ($content != "") {
                        $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

                        if (count($timezone)) {
                            if ($timezone->negative == 1) {
                                $hour += $timezone->value;
                            } else {
                                $hour -= $timezone->value;
                            }

                            if ($hour > 23) {
                                $hour -= 24;
                                $day += 1;
                            } elseif ($hour < 0) {
                                $hour += 24;
                                $day -= 1;
                            }

                            if ($day < 1) {
                                $day += 7;
                            } elseif ($day > 7) {
                                $day -= 7;
                            }

                            $atid = DB::table('automated_threads')->insertGetId([
                                'forumid' => $forumid,
                                'postuserid' => Auth::user()->userid,
                                'title' => $title,
                                'content' => $content,
                                'dateline' => time(),
                                'day' => $day,
                                'hour' => $hour,
                                'minute' => $min
                            ]);

                            DB::table('admin_log')->insert([
                                'userid' => Auth::user()->userid,
                                'description' => 'Added automated thread',
                                'content' => 7,
                                'contentid' => $atid,
                                'affected_userid' => 0,
                                'ip' => Auth::user()->lastip, 'dateline' => time()
                            ]);

                            $response = true;
                        }
                    } else {
                        $message = "Can't leave the content empty!";
                    }
                } else {
                    $message = "Can't have an empty title!";
                }
            } else {
                $message = "You can't add automated thread to this forum!";
            }
        } else {
            $message = "Can't find forum!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function getNewAutomated()
    {
        $temps = DB::table('forums')->where('parentid', '>', 0)->orderBy('title', 'ASC')->get();
        $forums = array();

        foreach ($temps as $temp) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $temp->forumid, 1)) {
                $forums[] = array('forumid' => $temp->forumid, 'title' => $temp->title);
            }
        }

        $returnHTML = view('admincp.extras.newAt')
            ->with('forums', $forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAutomatedThreads()
    {
        $temps = DB::table('automated_threads')->orderBy('atid', 'DESC')->get();

        $automated_threads = array();

        foreach ($temps as $temp) {
            $username = "System";

            $user = DB::table('users')->where('userid', $temp->postuserid)->first();

            if (count($user)) {
                $username = $user->username;
            }

            $day = $temp->day;
            $hour = $temp->hour;
            $min = $temp->minute;

            $timezone = DB::table('timezones')->where('timezoneid', Auth::user()->timezone)->first();

            if (count($timezone)) {
                if ($timezone->negative == 1) {
                    $hour -= $timezone->value;
                } else {
                    $hour += $timezone->value;
                }

                if ($hour < 0) {
                    $hour += 24;
                    $day -= 1;
                } elseif ($hour > 23) {
                    $hour -= 24;
                    $day += 1;
                }

                if ($day < 1) {
                    $day += 7;
                } elseif ($day > 7) {
                    $day -= 7;
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

            $array = array(
                'atid' => $temp->atid,
                'title' => $temp->title,
                'postuser' => $username,
                'day' => $day,
                'hour' => $hour,
                'min' => $min
            );

            $automated_threads[] = $array;
        }

        $returnHTML = view('admincp.listAutomated')
            ->with('automated_threads', $automated_threads)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBannedUsers()
    {
        $temps = DB::table('users_banned')->orderBy('banned_at', 'DESC')->get();

        $users = array();

        foreach ($temps as $temp) {
            $usr = DB::table('users')->where('userid', $temp->userid)->first();
            $adm = DB::table('users')->where('userid', $temp->adminid)->first();

            if (count($usr) and count($adm)) {
                $array = array(
                    'userid' => $usr->userid,
                    'username' => $usr->username,
                    'banned_at' => date('Y-m-d H:i', ForumHelper::returnTimeAfterTimezone($temp->banned_at)),
                    'banned_until' => date('Y-m-d H:i', ForumHelper::returnTimeAfterTimezone($temp->banned_until)),
                    'banned_untilraw' => $temp->banned_until,
                    'reason' => $temp->reason,
                    'admin_name' => $adm->username
                );

                $users[] = $array;
            }
        }

        $returnHTML = view('admincp.viewBanned')
            ->with('users', $users)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function unBanUser(Request $request)
    {
        $userid = $request->input('temp_userid');

        DB::table('users_banned')->where('userid', $userid)->delete();

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Unbanned user',
            'content' => 3,
            'contentid' => $userid,
            'affected_userid' => $userid,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function banUser(Request $request)
    {
        $userid = $request->input('temp_userid');
        $reason = $request->input('reason');
        $time = $request->input('time');
        $response = false;

        if ($time > 0) {
            $time = time() + $time;
        }

        if (!in_array($userid, UserHelper::getSuperAdmins())) {
            DB::table('users_banned')->where('userid', $userid)->delete();

            DB::table('users_banned')->insert([
                'userid' => $userid,
                'adminid' => Auth::user()->userid,
                'banned_at' => time(),
                'banned_until' => $time,
                'reason' => $reason
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Banned user',
                'content' => 3,
                'contentid' => $userid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            DB::table('sessions')->where('user_id', $userid)->delete();

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function updateAvatar(Request $request)
    {
        $response = false;
        $userid = $request->input('userid');
        $user = DB::table('users')->where('userid', $userid)->first();
        $avatar = asset('_assets/img/website/default_avatar');

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            if ($request->hasFile('avatar') and $request->file('avatar')->isValid()) {
                $img = Image::make($request->file('avatar'));
                $avid = time() . $user->userid;

                $groups = explode(",", $user->usergroups);
                $max_height = 200;
                $max_width = 200;
                $resize = false;
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
                    $resize = true;
                    $img->resize(null, $max_height);
                }
                if ($img->width() > $max_width) {
                    $resize = true;
                    $img->resize($max_width, null);
                }

                if ($resize) {
                    $img->save('_assets/img/avatars/' . $avid . '.gif', 100);
                } else {
                    UserHelper::saveAnimatedImage($request->file('avatar'), $avid, '_assets/img/avatars/');
                }

                if (Auth::user()->avatar != 0) {
                    File::delete(asset('_assets/img/avatars/'.$user->avatar).'.gif');
                }

                DB::table('users')->where('userid', $user->userid)->update([
                    'lastavataredit' => time(),
                    'avatar' => $avid
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Updated users avatar',
                    'content' => 3,
                    'contentid' => $user->userid,
                    'affected_userid' => $user->userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }

            $avatar = UserHelper::getAvatar($user->userid);
        }

        return response()->json(array('success' => true, 'response' => $response, 'new_avatar' => $avatar . '?' . rand()));
    }

    public function updateHeader(Request $request)
    {
        $response = false;
        $userid = $request->input('userid');

        $user = DB::table('users')->where('userid', $userid)->first();

        $header = asset('_assets/img/website/headers/6.png');

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            if ($request->hasFile('header') and $request->file('header')->isValid()) {
                $img = Image::make($request->file('header'));

                $img->save('_assets/img/headers/' . $user->userid . '.gif', 60);
                DB::table('users')->where('userid', $user->userid)->update([
                    'profile_header' => 0
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Updated users header',
                    'content' => 3,
                    'contentid' => $user->userid,
                    'affected_userid' => $user->userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }

            $header = asset('_assets/img/headers/' . $user->userid . '.gif');
        }

        return response()->json(array('success' => true, 'response' => $response, 'new_header' => $header . '?' . rand()));
    }

    public function updateGeneral(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $userid = $request->input('userid');
        $response = false;
        $message = "User not found";
        $field = "";
        $aValid = array('-', '_');

        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            if(!ctype_alnum($username)) {
                return response()->json(array('success' => true, 'response' => false, 'field' => 'edit-user-username', 'message' => 'Username must not contain special characters!'));
            } else {
                $check = DB::table('users')->where('userid', '!=', $userid)->where('username', 'LIKE', $username)->count();

                if ($check > 0) {
                    return response()->json(array('success' => true, 'response' => false, 'field' => 'edit-user-username', 'message' => 'Username already in use!'));
                }
            }

            if ($password != "") {
                if (strlen($password) <= 7) {
                    return response()->json(array('success' => true, 'response' => false, 'field' => 'edit-user-password', 'message' => 'To short password!'));
                } else {
                    DB::table('users')->where('userid', $userid)->update(['password' => Hash::make($password)]);
                }
            }

            DB::table('users')->where('userid', $userid)->update([
                'username' => $username,
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated users username',
                'content' => 3,
                'contentid' => $userid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'field' => $field));
    }

    public function updateHabbo(Request $request)
    {
        $habbo_name = $request->input('habbo_name');
        $habbo_veri = $request->input('habbo_veri');
        $userid = $request->input('userid');
        $response = false;
        $message = "Something went wrong!";

        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            $check_habbo = DB::table('users')->where('habbo', 'LIKE', $habbo_name)->where('habbo_verified', 1)->first();

            if (count($check_habbo)) {
                $message = $check_habbo->username . " already have that habbo verified!";
            } else {
                DB::table('users')->where('userid', $userid)->update([
                    'habbo' => $habbo_name,
                    'habbo_verified' => $habbo_veri
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Updated users habbo',
                    'content' => 3,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function updateTimeCountry(Request $request)
    {
        $userid = $request->input('userid');
        $region = $request->input('region');
        $country = $request->input('country');
        $timezone = $request->input('timezone');
        $response = false;

        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            $check_country = DB::table('countrys')->where('countryid', $country)->count();
            $check_timezone = DB::table('timezones')->where('timezoneid', $timezone)->count();

            if ($check_country > 0 and $check_timezone > 0) {
                DB::table('users')->where('userid', $userid)->update([
                    'region' => $region,
                    'country' => $country,
                    'timezone' => $timezone
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Updated users country and/or timezone',
                    'content' => 3,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function updateSignature(Request $request)
    {
        $userid = $request->input('userid');
        $signature = $request->input('signature');
        $response = false;

        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            DB::table('users')->where('userid', $userid)->update([
                'signature' => $signature
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Updated users signature',
                'content' => 3,
                'contentid' => $userid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getBotPage()
    {
        $text = DB::table('welcomebot')->value('text');

        $returnHTML = view('admincp.bot')
            ->with('text', $text)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function updateBot(Request $request)
    {
        $response = false;
        $text = $request->input('text');

        if (strlen($text) > 0) {
            DB::table('welcomebot')->update([
                'text' => $text
            ]);
            $response = true;
        }


        return response()->json(array('success' => true, 'response' => $response));
    }

    public function removeUserGroup(Request $request)
    {
        $userid = $request->input('userid');
        $groupid = $request->input('groupid');

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $user = DB::table('users')->where('userid', $userid)->first();

            $old_groups = explode(',', $user->usergroups);
            $new_groups = array();

            foreach ($old_groups as $old_group) {
                if ($old_group != $groupid) {
                    $new_groups[] = $old_group;
                }
            }

            if ($user->displaygroup == $groupid) {
                $displaygroup = count($new_groups) > 0 ? $new_groups[0] : '';
            } else {
                $displaygroup = $user->displaygroup;
            }

            $new_groups = implode(',', $new_groups);
            DB::table('users')->where('userid', $userid)->update([
                'usergroups' => $new_groups,
                'displaygroup' => $displaygroup
            ]);

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Removed usergroup: ' . $group->title . ' from user',
                'content' => 3,
                'contentid' => $userid,
                'affected_userid' => $userid,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function updateUser(Request $request)
    {
        $userid = $request->input('userid');
        $usergroups = explode(",", $request->input('usergroups'));
        $display = $request->input('displaygroup');
        $response = false;
        $backup_display = 0;
        $role = $request->input('customrole');
        $priority = $request->input('rolepriority');

        $user = DB::table('users')->where('userid', $userid)->first();

        if (count($user) and !in_array($userid, UserHelper::getSuperAdmins())) {
            $new_groups = "";
            $first = 1;
            $old_groups = explode(',', $user->usergroups);
            $new_groups_names = "";
            $new_first = true;

            $editable_groups_list = "";
            $eglfirst = true;
            foreach ($old_groups as $gr) {
                $grp = DB::table('usergroups')->where('usergroupid', $gr)->first();

                if (count($grp)) {
                    if ($grp->editable == 0 and !UserHelper::isSuperAdmin()) {
                        if ($eglfirst) {
                            $editable_groups_list = $grp->usergroupid;
                            $eglfirst = false;
                        } else {
                            $editable_groups_list = $editable_groups_list . ',' . $grp->usergroupid;
                        }
                    }
                }
            }

            foreach ($usergroups as $usergroup) {
                $grp = DB::table('usergroups')->where('usergroupid', $usergroup)->first();
                if (count($grp)) {
                    $run = true;
                    if ($grp->editable == 0 and !UserHelper::isSuperAdmin()) {
                        $run = false;
                    }

                    if ($run) {
                        if (!in_array($usergroup, $old_groups)) {
                            if ($new_first) {
                                $new_groups_names = $grp->title;
                                $new_first = false;
                            } else {
                                $new_groups_names = $new_groups_names . ', ' . $grp->title;
                            }
                        } else {
                            $indexOf = array_search($usergroup, $old_groups);
                            unset($old_groups[$indexOf]);
                            sort($old_groups);
                        }

                        if ($first == 1) {
                            $new_groups = $grp->usergroupid;
                            $first = 0;
                        } else {
                            $new_groups = $new_groups . ',' . $grp->usergroupid;
                        }
                        $backup_display = $grp->usergroupid;
                    }
                }
            }

            if (!in_array($display, $usergroups)) {
                if (!in_array($user->displaygroup, $usergroups)) {
                    $display = $backup_display;
                }
            }

            DB::table('users')->where('userid', $userid)->update([
                'usergroups' => strlen($new_groups) > 0 ? $new_groups . ',' . $editable_groups_list : $editable_groups_list,
                'displaygroup' => $display,
                'role' => $role,
                'priority' => $priority
            ]);

            $old_groups_names = "";
            $old_first = true;

            foreach ($old_groups as $old_group) {
                $grp = DB::table('usergroups')->where('usergroupid', $old_group)->first();

                if (count($grp)) {
                    if ($old_first) {
                        $old_groups_names = $grp->title;
                        $old_first = false;
                    } else {
                        $old_groups_names = $old_groups_names . ', ' . $grp->title;
                    }
                }
            }

            if (strlen($old_groups_names) > 0) {
                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Removed usergroups: ' . $old_groups_names,
                    'content' => 3,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);
            }

            if (strlen($new_groups_names) > 0) {
                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Added usergroups: ' . $new_groups_names,
                    'content' => 3,
                    'contentid' => $userid,
                    'affected_userid' => $userid,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);
            }

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditUser($userid)
    {
        $user = DB::table('users')->where('userid', $userid)->first();
        $referrals = DB::table('users')->where('referdby', $userid)->count();

        if (count($user)) {
            if (!in_array($user->userid, UserHelper::getSuperAdmins())) {

                /* get list of all usergroups */
                $temps = DB::table('usergroups')->whereNotIn('usergroupid', UserHelper::getExcludedGroups(Auth::user()->userid))->orderBy('title', 'ASC')->get();
                $groups = array();

                $users_grps = explode(",", $user->usergroups);

                foreach ($temps as $temp) {
                    $run = true;
                    if ($temp->editable == 0 and !UserHelper::isSuperAdmin()) {
                        $run = false;
                    }

                    if ($run) {
                        $in_it = false;

                        if (in_array($temp->usergroupid, $users_grps)) {
                            $in_it = true;
                        }

                        $array = array(
                            'groupid' => $temp->usergroupid,
                            'title' => $temp->title,
                            'in_it' => $in_it
                        );

                        $groups[] = $array;
                    }
                }

                $countrys = DB::table('countrys')->orderBy('name', 'ASC')->get();
                $timezones = DB::table('timezones')->orderBy('timezoneid', 'ASC')->get();

                $can_edit_usergroups = UserHelper::haveAdminPerm(Auth::user()->userid, 524288);
                $can_edit_details = UserHelper::haveAdminPerm(Auth::user()->userid, 1024);

                $returnHTML = view('admincp.user.editUser')
                    ->with('groups', $groups)
                    ->with('avatar', UserHelper::getAvatar($user->userid))
                    ->with('header', $user->profile_header > 0 ? asset('_assets/img/website/headers/' . $user->profile_header . '.png') : asset('_assets/img/headers/' . $user->userid . '.gif'))
                    ->with('user', $user)
                    ->with('region', $user->region)
                    ->with('referrals', $referrals)
                    ->with('countrys', $countrys)
                    ->with('timezones', $timezones)
                    ->with('can_edit_usergroups', $can_edit_usergroups)
                    ->with('can_edit_details', $can_edit_details)
                    ->render();

                return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
            }
        }

        return redirect()->route('getSearchUsers');
    }

    public function getUserList($username, $pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 50;
        $skip = 0;

        if ($username != "all") {
            $pagesx = DB::table('users')->where('username', 'LIKE', '%' . $username . '%')->orWhere('habbo', 'LIKE', '%' . $username . '%')->count();
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
            $temps = DB::table('users')->where('username', 'LIKE', '%' . $username . '%')->orWhere('habbo', 'LIKE', '%' . $username . '%')->take($take)->skip($skip)->orderBy('username', 'ASC')->get();
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
                    'habbo' => $temp->habbo,
                    'userid' => $temp->userid,
                    'lastactivity' => ForumHelper::timeAgo($temp->lastactivity),
                    'banned' => $banned,
                );

                $users[] = $array;
            }
        }

        $can_give_subscription = false;
        $can_ban_user = false;
        $can_unban_user = false;

        if (UserHelper::haveAdminPerm(Auth::user()->userid, 4096)) {
            $can_give_subscription = true;
        }

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 2)) {
            $can_ban_user = true;
        }

        if (UserHelper::haveGeneralModPerm(Auth::user()->userid, 4)) {
            $can_unban_user = true;
        }

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/users/all/page/')->render();

        $returnHTML = view('admincp.user.listUsers')
            ->with('users', $users)
            ->with('pagi', $pagi)
            ->with('searched', $username)
            ->with('can_ban_user', $can_ban_user)
            ->with('can_unban_user', $can_unban_user)
            ->with('can_give_subscription', $can_give_subscription)
            ->with('current_page', $pagenr)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function checkExactMatch(Request $request)
    {
        $username = $request->input('username');
        $response = false;
        $userid = 0;
        $check = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if (count($check) && $myImmunity > UserHelper::getImmunity($check->userid)) {
            $response = true;
            $userid = $check->userid;
        }

        return response()->json(array('success' => true, 'response' => $response, 'userid' => $userid));
    }

    public function getSearchUsers()
    {
        $returnHTML = view('admincp.searchUsers')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getSearchThreads()
    {
        $returnHTML = view('admincp.searchThreads')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRemoveForum(Request $request)
    {
        $forumid = $request->input('forumid');
        $userid = Auth::user()->userid;
        $response = false;

        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($forum)) {
            /* BEFORE DELETING WE WILL NEED TO REMOVE THE POSTS/THREDS FROM MAIN COUNT */
            $parentid = $forum->parentid;
            $run = true;
            while ($run) {
                $pr = DB::table('forums')->where('forumid', $parentid)->first();
                if (count($pr)) {
                    DB::table('forums')->where('forumid', $parentid)->update([
                        'posts' => DB::raw('posts-' . $forum->posts),
                        'threads' => DB::raw('threads-' . $forum->threads),
                    ]);

                    DB::table('admin_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Deleted forum',
                        'content' => 8,
                        'contentid' => $forumid,
                        'affected_userid' => 0,
                        'ip' => Auth::user()->lastip, 'dateline' => time()
                    ]);

                    if ($pr->parentid < 0) {
                        $run = false;
                    } else {
                        $parentid = $pr->parentid;
                    }
                } else {
                    $run = false;
                }
            }

            /* NOW THEY ARE GONE! */
            DB::table('forums')->where('forumid', $forumid)->delete();

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function postEditStaffpermissions(Request $request)
    {
        $groupid = $request->input('groupid');
        $permissions = $request->input('permissions');
        $response = false;

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                DB::table('usergroups')->where('usergroupid', $groupid)->update([
                    'staffpermissions' => $permissions,
                    'lastedited' => time()
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Edited staff permissions',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function postEditAdminpermissions(Request $request)
    {
        $groupid = $request->input('groupid');
        $permissions = $request->input('permissions');
        $response = false;

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                DB::table('usergroups')->where('usergroupid', $groupid)->update([
                    'adminpermissions' => $permissions,
                    'lastedited' => time()
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Edited admin permissions',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function postEditGeneralModPerms(Request $request)
    {
        $groupid = $request->input('groupid');
        $permissions = $request->input('permissions');
        $response = false;

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                DB::table('usergroups')->where('usergroupid', $groupid)->update([
                    'modpermissions' => $permissions,
                    'lastedited' => time()
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Edited general mod permissions',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditStaffPermissions($groupid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $perm = $group->staffpermissions;

                $can_use_staff_panel = $perm & 1 ? 1 : 0;
                $can_use_radio_stuff = $perm & 2 ? 1 : 0;
                $can_unbook_radio_slot = $perm & 4 ? 1 : 0;
                $can_see_all_requests = $perm & 8 ? 1 : 0;
                $can_always_see_connection = $perm & 16 ? 1 : 0;

                $can_use_manager_stuff = $perm & 32 ? 1 : 0;
                $can_add_perm_show = $perm & 64 ? 1 : 0;
                $can_use_media = $perm & 128 ? 1 : 0;
                $can_manage_other_articles = $perm & 256 ? 1 : 0;
                $can_see_graphics = $perm & 512 ? 1 : 0;

                $can_upload_graphic = $perm & 1024 ? 1 : 0;
                $can_delete_others_graphics = $perm & 2048 ? 1 : 0;

                $can_see_admin = $perm & 32768 ? 1 : 0;
                $can_see_jobs = $perm & 65536 ? 1 : 0;
                $can_delete_add_jobs = $perm & 131072 ? 1 : 0;

                $can_manage_job_apps = $perm & 262144 ? 1 : 0;
                $can_manage_event_types = $perm & 524288 ? 1 : 0;
                $can_use_event_stuff = $perm & 1048576 ? 1 : 0;
                $can_unbook_event_slot = $perm & 16777216 ? 1 : 0;

                $can_use_shoutbox = $perm & 4096 ? 1 : 0;
                $can_manage_radio_info = $perm & 8192 ? 1 : 0;

                $can_manage_flagged_articles = $perm & 16384 ? 1 : 0;

                $can_book_for_others = $perm & 67108864 ? 1 : 0;

                $permissions = array(
                    'can_manage_flagged_articles' => $can_manage_flagged_articles,
                    'can_use_staff_panel' => $can_use_staff_panel,
                    'can_use_radio_stuff' => $can_use_radio_stuff,
                    'can_unbook_radio_slot' => $can_unbook_radio_slot,
                    'can_see_all_requests' => $can_see_all_requests,
                    'can_always_see_connection' => $can_always_see_connection,
                    'can_use_manager_stuff' => $can_use_manager_stuff,
                    'can_add_perm_show' => $can_add_perm_show,
                    'can_use_media' => $can_use_media,
                    'can_manage_other_articles' => $can_manage_other_articles,
                    'can_see_graphics' => $can_see_graphics,
                    'can_upload_graphic' => $can_upload_graphic,
                    'can_delete_others_graphics' => $can_delete_others_graphics,
                    'can_see_admin' => $can_see_admin,
                    'can_see_jobs' => $can_see_jobs,
                    'can_delete_add_jobs' => $can_delete_add_jobs,
                    'can_manage_job_apps' => $can_manage_job_apps,
                    'can_manage_event_types' => $can_manage_event_types,
                    'can_use_event_stuff' => $can_use_event_stuff,
                    'can_unbook_event_slot' => $can_unbook_event_slot,
                    'can_use_shoutbox' => $can_use_shoutbox,
                    'can_manage_radio_info' => $can_manage_radio_info,
                    'can_book_for_others' => $can_book_for_others
                );

                $returnHTML = view('admincp.perms.editStaffPerms')
                    ->with('group', $group)
                    ->with('permissions', $permissions)
                    ->render();

                return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
            }
        }
    }

    public function getEditGeneralModPerms($groupid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $perm = $group->modpermissions;

                $can_see_moderation = $perm & 1 ? 1 : 0;
                $can_ban_user = $perm & 2 ? 1 : 0;
                $can_unban_user = $perm & 4 ? 1 : 0;
                $can_soft_delete_vm = $perm & 8 ? 1 : 0;
                $can_soft_delete_article_comments = $perm & 32 ? 1 : 0;
                $can_search_users_using_same_ip = $perm & 64 ? 1 : 0;
                $can_manage_creations = $perm & 128 ? 1 : 0;
                $can_infract_article_comments = $perm & 256 ? 1 : 0;
                $can_infract_vm = $perm & 512 ? 1 : 0;
                $can_soft_delete_creation_comments = $perm & 1024 ? 1 : 0;
                $can_hard_delete_vm = $perm & 2048 ? 1 : 0;
                $can_hard_delete_article_comments = $perm & 4096 ? 1 : 0;
                $can_hard_delete_creation_comments = $perm & 8192 ? 1 : 0;

                $permissions = array(
                    'can_see_moderation' => $can_see_moderation,
                    'can_ban_user' => $can_ban_user,
                    'can_unban_user' => $can_unban_user,
                    'can_soft_delete_vm' => $can_soft_delete_vm,
                    'can_soft_delete_article_comments' => $can_soft_delete_article_comments,
                    'can_search_users_using_same_ip' => $can_search_users_using_same_ip,
                    'can_manage_creations' => $can_manage_creations,
                    'can_infract_article_comments' => $can_infract_article_comments,
                    'can_infract_vm' => $can_infract_vm,
                    'can_soft_delete_creation_comments' => $can_soft_delete_creation_comments,
                    'can_hard_delete_vm' => $can_hard_delete_vm,
                    'can_hard_delete_article_comments' => $can_hard_delete_article_comments,
                    'can_hard_delete_creation_comments' => $can_hard_delete_creation_comments
                );

                $returnHTML = view('admincp.perms.editGeneralModPerms')
                    ->with('group', $group)
                    ->with('permissions', $permissions)
                    ->with('dont_have_staff', !($group->staffpermissions & 1))
                    ->render();
            }

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function getEditAdminPermissions($groupid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $perm = $group->adminpermissions;

                $can_use_admincp = $perm & 1 ? 1 : 0;
                $can_admin_forums = $perm & 2 ? 1 : 0;
                $can_admin_users = $perm & 4 ? 1 : 0;
                $can_admin_usergroups = $perm & 8 ? 1 : 0;
                $can_admin_bbcodes = $perm & 16 ? 1 : 0;
                $can_edit_adminpermissions = $perm & 32 ? 1 : 0;
                $can_edit_moderationpermissions = $perm & 64 ? 1 : 0;
                $can_edit_staffpermissions = $perm & 128 ? 1 : 0;
                $can_edit_subscriptions = $perm & 256 ? 1 : 0;
                $can_edit_default_forumpermissions = $perm & 512 ? 1 : 0;
                $can_edit_users_details = $perm & 1024 ? 1 : 0;
                $can_manage_prefixes = $perm & 2048 ? 1 : 0;
                $can_give_subscription = $perm & 4096 ? 1 : 0;
                $can_see_shop_settings = $perm & 8192 ? 1 : 0;
                $can_manage_name_effects = $perm & 16384 ? 1 : 0;
                $can_manage_voucher_codes = $perm & 32768 ? 1 : 0;
                $can_edit_users_usergroups = $perm & 524288 ? 1 : 0;
                $can_manage_name_icons = $perm & 65536 ? 1 : 0;
                $can_view_site_rules = $perm & 131072 ? 1 : 0;
                $can_manage_badges = $perm & 262144 ? 1 : 0;
                $can_edit_site_rules = $perm & 1048576 ? 1 : 0;
                $can_edit_generalmodperm = $perm & 2097152 ? 1 : 0;
                $can_manage_themes = $perm & 4194304 ? 1 : 0;
                $can_manage_automated_threads = $perm & 8388608 ? 1 : 0;
                $can_manage_staff_list = $perm & 16777216 ? 1 : 0;
                $can_edit_maintenance = $perm & 33554432 ? 1 : 0;
                $can_manage_daily_quests = $perm & 67108864 ? 1 : 0;
                $can_manage_link_partners = $perm & 134217728 ? 1 : 0;
                $can_manage_sotw = $perm & 268435456 ? 1 : 0;
                $can_tweet_via_admincp = $perm & 536870912 ? 1 : 0;
                $can_view_logs = $perm & 1073741824 ? 1 : 0;
                $can_view_statistics = $perm & 2147483648 ? 1 : 0;
                $can_issue_points = $perm & 4294967296 ? 1 : 0;
                $can_manage_carousel = $perm & 8589934592 ? 1 : 0;
                $can_manage_welcome_bot = $perm & 17179869184 ? 1 : 0;
                $can_manage_post_levels = $perm & 34359738368 ? 1 : 0;
                $can_admin_bets = $perm & 68719476736 ? 1 : 0;

                $permissions = array(
                    'can_use_admincp' => $can_use_admincp,
                    'can_admin_forums' => $can_admin_forums,
                    'can_admin_users' => $can_admin_users,
                    'can_admin_usergroups' => $can_admin_usergroups,
                    'can_admin_bbcodes' => $can_admin_bbcodes,
                    'can_edit_adminpermissions' => $can_edit_adminpermissions,
                    'can_edit_moderationpermissions' => $can_edit_moderationpermissions,
                    'can_edit_staffpermissions' => $can_edit_staffpermissions,
                    'can_edit_subscriptions' => $can_edit_subscriptions,
                    'can_edit_default_forumpermissions' => $can_edit_default_forumpermissions,
                    'can_edit_users_details' => $can_edit_users_details,
                    'can_manage_prefixes' => $can_manage_prefixes,
                    'can_give_subscription' => $can_give_subscription,
                    'can_see_shop_settings' => $can_see_shop_settings,
                    'can_manage_name_effects' => $can_manage_name_effects,
                    'can_manage_voucher_codes' => $can_manage_voucher_codes,
                    'can_edit_users_usergroups' => $can_edit_users_usergroups,
                    'can_manage_name_icons' => $can_manage_name_icons,
                    'can_view_site_rules' => $can_view_site_rules,
                    'can_manage_badges' => $can_manage_badges,
                    'can_edit_site_rules' => $can_edit_site_rules,
                    'can_edit_generalmodperm' => $can_edit_generalmodperm,
                    'can_manage_themes' => $can_manage_themes,
                    'can_manage_automated_threads' => $can_manage_automated_threads,
                    'can_manage_staff_list' => $can_manage_staff_list,
                    'can_edit_maintenance' => $can_edit_maintenance,
                    'can_manage_daily_quests' => $can_manage_daily_quests,
                    'can_manage_link_partners' => $can_manage_link_partners,
                    'can_manage_sotw' => $can_manage_sotw,
                    'can_tweet_via_admincp' => $can_tweet_via_admincp,
                    'can_view_logs' => $can_view_logs,
                    'can_view_statistics' => $can_view_statistics,
                    'can_issue_points' => $can_issue_points,
                    'can_manage_carousel' => $can_manage_carousel,
                    'can_manage_welcome_bot' => $can_manage_welcome_bot,
                    'can_manage_post_levels' => $can_manage_post_levels,
                    'can_admin_bets' => $can_admin_bets,
                );

                $returnHTML = view('admincp.perms.editAdminPerms')
                    ->with('group', $group)
                    ->with('permissions', $permissions)
                    ->render();
            }

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function postEditForumpermissions(Request $request)
    {
        $forumid = $request->input('forumid');
        $groupid = $request->input('groupid');
        $permissions = $request->input('permissions');
        $response = false;

        if (UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
            $forum = DB::table('forums')->where('forumid', $forumid)->first();
            $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

            if (count($forum) and count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
                $run = true;
                if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                    $run = false;
                }

                if ($run) {
                    $check = DB::table('forumpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->count();

                    if ($check == 0) {
                        DB::table('forumpermissions')->insert([
                            'forumid' => $forumid,
                            'usergroupid' => $groupid,
                            'forumpermissions' => $permissions,
                            'lastedited' => time()
                        ]);
                    } else {
                        DB::table('forumpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->update([
                            'forumpermissions' => $permissions,
                            'lastedited' => time()
                        ]);
                    }

                    DB::table('admin_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Edited forum permissions',
                        'content' => 1,
                        'contentid' => $groupid,
                        'affected_userid' => 0,
                        'ip' => Auth::user()->lastip, 'dateline' => time()
                    ]);

                    $response = true;
                }
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function postEditModerationpermission(Request $request)
    {
        $forumid = $request->input('forumid');
        $groupid = $request->input('groupid');
        $permissions = $request->input('permissions');
        $response = false;

        if (UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
            $forum = DB::table('forums')->where('forumid', $forumid)->first();
            $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

            if (count($forum) and count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
                $run = true;
                if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                    $run = false;
                }

                if ($run) {
                    $check = DB::table('moderationpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->count();

                    if ($check == 0) {
                        DB::table('moderationpermissions')->insert([
                            'forumid' => $forumid,
                            'usergroupid' => $groupid,
                            'moderationpermissions' => $permissions,
                            'lastedited' => time()
                        ]);
                    } else {
                        DB::table('moderationpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->update([
                            'moderationpermissions' => $permissions,
                            'lastedited' => time()
                        ]);
                    }

                    DB::table('admin_log')->insert([
                        'userid' => Auth::user()->userid,
                        'description' => 'Edited moderation permissions',
                        'content' => 1,
                        'contentid' => $groupid,
                        'affected_userid' => 0,
                        'ip' => Auth::user()->lastip, 'dateline' => time()
                    ]);

                    $response = true;
                }
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditForumpermissions($groupid, $forumid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($group) and count($forum) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                if (UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
                    $forumpermissions = DB::table('forumpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->first();

                    if (count($forumpermissions)) {
                        $perm = $forumpermissions->forumpermissions;
                    } else {
                        $perm = 0;
                    }

                    $can_see_forum = $perm & 1 ? 1 : 0;
                    $can_create_thread = $perm & 2 ? 1 : 0;
                    $can_reply_to_others_threads = $perm & 4 ? 1 : 0;
                    $can_edit_own_post = $perm & 8 ? 1 : 0;
                    $can_skip_approve_thread = $perm & 16 ? 1 : 0;
                    $can_see_others_threads = $perm & 32 ? 1 : 0;
                    $can_skip_double_post = $perm & 64 ? 1 : 0;
                    $can_reply_to_own_threads = $perm & 128 ? 1 : 0;

                    $permissions = array(
                        'can_see_forum' => $can_see_forum,
                        'can_create_thread' => $can_create_thread,
                        'can_reply_to_others_threads' => $can_reply_to_others_threads,
                        'can_edit_own_post' => $can_edit_own_post,
                        'can_skip_approve_thread' => $can_skip_approve_thread,
                        'can_see_others_threads' => $can_see_others_threads,
                        'can_skip_double_post' => $can_skip_double_post,
                        'can_reply_to_own_threads' => $can_reply_to_own_threads,
                    );

                    $returnHTML = view('admincp.perms.editForumPerms')
                        ->with('permissions', $permissions)
                        ->with('group', $group)
                        ->with('forum', $forum)
                        ->render();

                    return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
                }
            }
        }
    }

    public function getEditModerationpermissions($groupid, $forumid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (count($group) and count($forum) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                if (UserHelper::haveForumPerm(Auth::user()->userid, $forumid, 1)) {
                    $moderationpermissions = DB::table('moderationpermissions')->where('usergroupid', $groupid)->where('forumid', $forumid)->first();

                    if (count($moderationpermissions)) {
                        $perm = $moderationpermissions->moderationpermissions;
                    } else {
                        $perm = 0;
                    }

                    $can_edit_posts = $perm & 1 ? 1 : 0;
                    $can_soft_delete_posts = $perm & 2 ? 1 : 0;
                    $can_hard_delete_posts = $perm & 4 ? 1 : 0;
                    $can_open_close_threads = $perm & 8 ? 1 : 0;
                    $can_move_posts_threads = $perm & 16 ? 1 : 0;
                    $can_merge_threads = $perm & 32 ? 1 : 0;
                    $can_change_owner = $perm & 64 ? 1 : 0;
                    $can_move_posts = $perm & 128 ? 1 : 0;
                    $can_warninf_users = $perm & 256 ? 1 : 0;
                    $can_approve_unapprove_threads = $perm & 512 ? 1 : 0;
                    $can_view_unapproved_threads = $perm & 1024 ? 1 : 0;

                    $permissions = array(
                        'can_edit_posts' => $can_edit_posts,
                        'can_soft_delete_posts' => $can_soft_delete_posts,
                        'can_hard_delete_posts' => $can_hard_delete_posts,
                        'can_open_close_threads' => $can_open_close_threads,
                        'can_move_posts_threads' => $can_move_posts_threads,
                        'can_merge_threads' => $can_merge_threads,
                        'can_change_owner' => $can_change_owner,
                        'can_move_posts' => $can_move_posts,
                        'can_warninf_users' => $can_warninf_users,
                        'can_approve_unapprove_threads' => $can_approve_unapprove_threads,
                        'can_view_unapproved_threads' => $can_view_unapproved_threads
                    );

                    $returnHTML = view('admincp.perms.editModPerms')
                        ->with('permissions', $permissions)
                        ->with('group', $group)
                        ->with('forum', $forum)
                        ->render();

                    return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
                }
            }
        }
    }

    public function getSelectForum($groupid, $type)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group)) {
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

            $forumpermissions = false;

            if ($type == "forum") {
                $forumpermissions = true;
            }

            $returnHTML = view('admincp.extras.selectForum')
                ->with('forums', $forums)
                ->with('group', $group)
                ->with('forumpermissions', $forumpermissions)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function getThreadInfo($threadid)
    {
        $thread = DB::table('threads')->where('threadid', $threadid)->first();

        if (!count($thread)) {
            return redirect()->route('getErrorPerm');
        }

        $forum = DB::table('forums')->where('forumid', $thread->forumid)->first();

        $forumName = $forum->title;
        $threadTitle = $thread->title;
        $threadAuthor = UserHelper::getUsername($thread->postuserid, true);

        $temps = DB::table('posts')->where('threadid', $threadid)->get();

        foreach ($temps as $temp){
            $bbcodes = DB::table('bbcodes')->get();
            $content = ForumHelper::fixContent($temp->content);
            $content = ForumHelper::replaceEmojis($content);
            $content = ForumHelper::bbcodeParser($content, $bbcodes);
            $content = nl2br($content);

            $array = array(
                'postid' => $temp->postid,
                'content' => $content,
                'author' => UserHelper::getUsername($temp->userid, true)
            );

            $posts[] = $array;

        }

        $returnHTML = view('admincp.forum.viewThread')
            ->with('forumName', $forumName)
            ->with('threadTitle', $threadTitle)
            ->with('threadAuthor', $threadAuthor)
            ->with('posts', $posts)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditForum($forumid)
    {
        $forum = DB::table('forums')->where('forumid', $forumid)->first();

        if (!count($forum)) {
            return redirect()->route('getErrorPerm');
        }

        $userid = Auth::user()->userid;
        $parents = DB::table('forums')->where('parentid', -1)->get();


        $forums = array();

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

        $returnHTML = view('admincp.forum.editForum')
            ->with('forums', $forums)
            ->with('forum', $forum)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditForum(Request $request)
    {
        $title = $request->input('title');
        $parent = $request->input('parent');
        $display = $request->input('display');
        $desc = $request->input('desc');
        $posts = $request->input('posts');
        $approve = $request->input('approve');
        $forumid = $request->input('forumid');
        $userid = Auth::user()->userid;
        $thumbnail = $request->input('thumbnail');
        $error = 0;
        $field = "";

        if (UserHelper::haveForumPerm($userid, $forumid, 1)) {
            $forum = DB::table('forums')->where('forumid', $forumid)->first();

            if (!count($forum)) {
                return response()->json(array('success' => true, 'error' => 1, 'field' => "all", 'message' => 'Forum does not exists!'));
            }

            if ($title == "") {
                $field = "forum-edit-title";
                $error = 1;
            }

            if ($parent == "") {
                $field = "forum-edit-parent";
                $error = 1;
            }

            if ($display == "") {
                $field = "forum-edit-display";
                $error = 1;
            }

            if (!($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid())) {
                $field = "forum-edit-thumbnail";
                $error = 1;
            }

            $display = intval($display);

            if (!is_int($display)) {
                return response()->json(array('success' => true, 'error' => 1, 'field' => "forum-edit-display", 'message' => 'Display order needs to be a number!'));
            }

            if ($error == 1) {
                return response()->json(array('success' => true, 'error' => 1, 'field' => $field, 'message' => 'Can\'t leave empty!'));
            }

            if ($parent > 0) {
                $check_parent = DB::table('forums')->where('forumid', $parent)->first();

                if (count($check_parent)) {
                    if (!UserHelper::haveForumPerm($userid, $check_parent->forumid, 1)) {
                        return response()->json(array('success' => true, 'error' => 1, 'field' => 'forum-edit-parent', 'message' => 'You don\'t have access to the parent!'));
                    }
                } else {
                    return response()->json(array('success' => true, 'error' => 1, 'field' => 'forum-edit-parent', 'message' => 'The parent forum does not exist!'));
                }
            }

            $options = 0;

            if ($posts==0) {
                $options += 2;
            }

            if ($approve == 1) {
                $options += 1;
            }

            if ($desc == "") {
                $desc = " ";
            }

            $time = time();

            /* MOVE OVER THE AMOUNT OF POSTS TO NEW PARENT */
            if ($parent != $forum->parentid) {
                /* REMOVE FROM OLD MAIN(S) */
                $parentid = $forum->parentid;
                $run = true;
                while ($run) {
                    $pr = DB::table('forums')->where('forumid', $parentid)->first();
                    if (count($pr)) {
                        DB::table('forums')->where('forumid', $parentid)->update([
                            'posts' => DB::raw('posts-' . $forum->posts),
                            'threads' => DB::raw('threads-' . $forum->threads),
                        ]);

                        if ($pr->parentid < 0) {
                            $run = false;
                        } else {
                            $parentid = $pr->parentid;
                        }
                    } else {
                        $run = false;
                    }
                }

                /* GIVE TO NEW MAIN(S) */
                $parentid = $parent;
                $run = true;
                while ($run) {
                    $pr = DB::table('forums')->where('forumid', $parentid)->first();
                    if (count($pr)) {
                        DB::table('forums')->where('parentid', $parentid)->update([
                            'posts' => DB::raw('posts+' . $forum->posts),
                            'threads' => DB::raw('threads+' . $forum->threads),
                        ]);

                        if ($pr->parentid < 0) {
                            $run = false;
                        } else {
                            $parentid = $pr->parentid;
                        }
                    } else {
                        $run = false;
                    }
                }
            }

            DB::table('forums')->where('forumid', $forumid)->update([
                'title' => $title,
                'description' => $desc,
                'parentid' => $parent,
                'options' => $options,
                'displayorder' => $display,
                'lastedited' => $time
            ]);

            UserHelper::saveAnimatedImage($request->file('thumbnail'), $forumid, '_assets/img/forumthumbnails/');


            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Edited forum',
                'content' => 8,
                'contentid' => $forumid,
                'affected_userid' => 0,
                'ip' => Auth::user()->lastip, 'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'error' => 0));
        }
    }

    public function postAddForum(Request $request)
    {
        $title = $request->input('title');
        $parent = $request->input('parent');
        $display = $request->input('display');
        $desc = $request->input('desc');
        $posts = $request->input('posts');
        $approve = $request->input('approve');
        $userid = Auth::user()->userid;
        $thumbnail = $request->input('thumbnail');
        $error = 0;
        $field = "";

        if ($title == "") {
            $field = "forum-add-title";
            $error = 1;
        }

        if ($parent == "") {
            $field = "forum-add-parent";
            $error = 1;
        }

        if ($display == "") {
            $field = "forum-add-display";
            $error = 1;
        }

        $display = intval($display);

        if (!($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid())) {
            $field = "forum-add-thumbnail";
            $error = 1;
        }

        if (!is_int($display)) {
            return response()->json(array('success' => true, 'error' => 1, 'field' => "forum-add-display", 'message' => 'Display order needs to be a number!'));
        }

        if ($error == 1) {
            return response()->json(array('success' => true, 'error' => 1, 'field' => $field, 'message' => 'Can\'t leave empty!'));
        }

        if ($parent > 0) {
            $check_parent = DB::table('forums')->where('forumid', $parent)->first();

            if (count($check_parent)) {
                if (!UserHelper::haveForumPerm($userid, $check_parent->forumid, 1)) {
                    return response()->json(array('success' => true, 'error' => 1, 'field' => 'forum-add-parent', 'message' => 'You don\'t have access to the parent!'));
                }
            } else {
                return response()->json(array('success' => true, 'error' => 1, 'field' => 'forum-add-parent', 'message' => 'The parent forum does not exist!'));
            }
        }

        $options = 0;

        if ($posts==0) {
            $options += 2;
        }

        if ($approve == 1) {
            $options += 1;
        }

        if ($desc == "") {
            $desc = " ";
        }

        $time = time();



        $forumid = DB::table('forums')->insertGetId([
            'title' => $title,
            'description' => $desc,
            'parentid' => $parent,
            'options' => $options,
            'displayorder' => $display,
            'lastedited' => $time
        ]);


        UserHelper::saveAnimatedImage($request->file('thumbnail'), $forumid, '_assets/img/forumthumbnails/');

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added forum',
            'content' => 8,
            'contentid' => $forumid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        /* COPY PARENT FORUMPERMISSIONS */
        if ($parent > 0) {
            $permissions = DB::table('forumpermissions')->where('forumid', $parent)->get();

            foreach ($permissions as $perm) {
                DB::table('forumpermissions')->insert([
                    'forumid' => $forumid,
                    'usergroupid' => $perm->usergroupid,
                    'forumpermissions' => $perm->forumpermissions,
                    'lastedited' => $time
                ]);
            }

            $mod_permissions = DB::table('moderationpermissions')->where('forumid', $parent)->get();

            foreach ($mod_permissions as $mod) {
                DB::table('moderationpermissions')->insert([
                    'forumid' => $parent,
                    'usergroupid' => $mod->usergroupid,
                    'moderationpermissions' => $mod->moderationpermissions,
                    'lastedited' => $time
                ]);
            }
        }

        return response()->json(array('success' => true, 'error' => 0));
    }

    public function getAddForum()
    {
        $userid = Auth::user()->userid;
        $parents = DB::table('forums')->where('parentid', -1)->get();

        $forums = array();

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

        $returnHTML = view('admincp.forum.addForum')
            ->with('forums', $forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getForums()
    {
        $userid = Auth::user()->userid;
        $parents = DB::table('forums')->where('parentid', -1)->orderBy('displayorder', 'ASC')->get();

        $forums = array();

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
                            'title' => '- ' . $temp->title,
                            'displayorder' => $temp->displayorder,
                            'childs' => 0
                        );

                        $chi = self::getForumChilds($userid, $temp->forumid, '- - - ');

                        $ar['childs'] = $chi;

                        $array['childs'][] = $ar;
                    }
                }

                $forums[] = $array;
            }
        }

        $returnHTML = view('admincp.forum.listForums')
            ->with('forums', $forums)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    /* RETURN ARRAY OF CHILDS */
    private function getForumChilds($userid, $forumid, $add)
    {
        $forums = array();

        $temps = DB::table('forums')->where('parentid', $forumid)->orderBy('displayorder', 'ASC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'forumid' => $temp->forumid,
                'title' => $add . $temp->title,
                'displayorder' => $temp->displayorder,
                'childs' => 0
            );

            $chi = self::getForumChilds($userid, $temp->forumid, $add.'- - ');

            $array['childs'] = $chi;

            $forums[] = $array;
        }

        return $forums;
    }

    /* RETURN ARRAY OF CHILDS */
    private function getForumChildsDefault($userid, $forumid, $add)
    {
        $forums = array();

        $temps = DB::table('forums')->where('parentid', $forumid)->get();

        foreach ($temps as $temp) {
            $array = array(
                'forumid' => $temp->forumid,
                'title' => $add . $temp->title,
                'displayorder' => $temp->displayorder,
                'can_see' => UserHelper::haveForumPerm(0, $temp->forumid, 1) ? true : false,
                'childs' => 0
            );

            $chi = self::getForumChildsDefault($userid, $temp->forumid, $add.'-');

            $array['childs'] = $chi;

            $forums[] = $array;
        }

        return $forums;
    }

    public function postEditGroupBar(Request $request)
    {
        $groupid = $request->input('groupid');
        $html = $request->input('html');
        $css = $request->input('css');
        $response = false;

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $check = DB::table('groupbars')->where('usergroupid', $groupid)->count();

                if ($check == 0) {
                    DB::table('groupbars')->insert([
                        'usergroupid' => $groupid,
                        'html' => $html,
                        'css' => $css,
                        'lastedited' => time()
                    ]);
                } else {
                    DB::table('groupbars')->where('usergroupid', $groupid)->update([
                        'html' => $html,
                        'css' => $css,
                        'lastedited' => time()
                    ]);
                }

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Edited usergroup userbar',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditGroupBar($groupid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group) && UserHelper::getImmunity(Auth::user()->userid) >= $group->immunity) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $bar = DB::table('groupbars')->where('usergroupid', $groupid)->first();
                $html = "";
                $css = "";

                if (count($bar)) {
                    $html = $bar->html;
                    $css  = $bar->css;
                }

                $returnHTML = view('admincp.usergroup.editGroupBar')
                    ->with('html', $html)
                    ->with('css', $css)
                    ->with('group', $group)
                    ->render();
            }

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }
    }

    public function postEditGroup(Request $request)
    {
        $title = $request->input('title');
        $opentag = $request->input('opentag');
        $height = intval($request->input('height'));
        $width = intval($request->input('width'));
        $groupid = $request->input('groupid');
        $immunity = intval($request->input('immunity'));

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (UserHelper::isSuperAdmin()) {
            $editable = $request->input('editable');
        } else {
            $editable = 1;
        }

        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if ($immunity > $myImmunity) {
            $immunity = $myImmunity;
        } elseif ($immunity < 0) {
            $immunity = 0;
        }

        if ($immunity > 100) {
            $immunity = 100;
        }

        if (count($group)) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $error = 0;
                $message = "";
                $field = "";

                if ($title == "") {
                    $error = 1;
                    $field = "group-add-title";
                    $message = "Can't leave title empty!";
                }

                if (!is_int($height)) {
                    $error = 1;
                    $field = "group-add-height";
                    $message = "Height needs to be a number!";
                }

                if (!is_int($width)) {
                    $error = 1;
                    $field = "group-add-width";
                    $message = "Width needs to be a number!";
                }

                if ($error == 1) {
                    return response()->json(array('success' => true, 'error' => $error, 'message' => $message, 'field' => $field));
                }

                DB::table('usergroups')->where('usergroupid', $groupid)->update([
                    'title' => $title,
                    'opentag' => '<span style="' . $opentag . '">',
                    'closetag' => '</span>',
                    'avatar_height' => $height,
                    'avatar_width' => $width,
                    'editable' => $editable,
                    'immunity' => $immunity,
                    'lastedited' => time()
                ]);

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Edited usergroup',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);
            }
        }

        return response()->json(array('success' => true, 'error' => 0));
    }

    public function getEditGroup($groupid)
    {
        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();

        if (count($group)) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }

            if ($run) {
                $opentag = str_replace('<span style="', "", $group->opentag);
                $opentag = str_replace('">', "", $opentag);

                $usergroup = array(
                    'title' => $group->title,
                    'opentag' => $opentag,
                    'height' => $group->avatar_height,
                    'width' => $group->avatar_width,
                    'groupid' => $group->usergroupid,
                    'editable' => $group->editable,
                    'immunity' => $group->immunity
                );

                $returnHTML = view('admincp.usergroup.editGroup')
                    ->with('group', $usergroup)
                    ->with('is_super', UserHelper::isSuperAdmin())
                    ->with('immunity', UserHelper::getImmunity(Auth::user()->userid))
                    ->render();
                return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
            }
        }

        return redirect()->route('getErrorPerm');
    }

    public function postRemoveGroup(Request $request)
    {
        $groupid = $request->input('groupid');

        $group = DB::table('usergroups')->where('usergroupid', $groupid)->first();
        $response = false;

        if (count($group)) {
            $run = true;
            if ($group->editable == 0 and !UserHelper::isSuperAdmin()) {
                $run = false;
            }
            if ($run) {
                $users = DB::table('users')->where('usergroups', 'LIKE', '%,' . $groupid)->orWhere('usergroups', 'LIKE', $groupid . ',%')->orWhere('usergroups', 'LIKE', $groupid)->orWhere('usergroups', 'LIKE', '%,' . $groupid . ',%')->get();

                foreach ($users as $user) {
                    $old_groups = explode(",", $user->usergroups);
                    $new_groups = "";
                    $first = 1;
                    $display = "";

                    foreach ($old_groups as $old_group) {
                        if ($old_group != $groupid) {
                            if ($first == 1) {
                                $new_groups = $old_group;
                                $first = 0;
                                $display = $old_group;
                            } else {
                                $new_groups = $new_groups . ',' . $old_group;
                            }
                        }
                    }

                    if ($user->displaygroup != $groupid) {
                        $display = $user->displaygroup;
                    }

                    DB::table('users')->where('userid', $user->userid)->update([
                        'usergroups' => $new_groups,
                        'displaygroup' => $display
                    ]);
                }

                DB::table('forumpermissions')->where('usergroupid', $groupid)->delete();
                /* ADD DELETE FOR MODERATION HERE ALSO */
                DB::table('usergroups')->where('usergroupid', $groupid)->delete();

                DB::table('admin_log')->insert([
                    'userid' => Auth::user()->userid,
                    'description' => 'Deleted usergroup',
                    'content' => 1,
                    'contentid' => $groupid,
                    'affected_userid' => 0,
                    'ip' => Auth::user()->lastip, 'dateline' => time()
                ]);

                $response = true;
            }
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function postAddGroup(Request $request)
    {
        $title = $request->input('title');
        $opentag = $request->input('opentag');
        $height = intval($request->input('height'));
        $width = intval($request->input('width'));
        $immunity = intval($request->input('immunity'));
        $copy = $request->input('copy');

        if (UserHelper::isSuperAdmin()) {
            $editable = $request->input('editable');
        } else {
            $editable = 1;
        }

        $myImmunity = UserHelper::getImmunity(Auth::user()->userid);
        if ($immunity > $myImmunity) {
            $immunity = $myImmunity;
        } elseif ($immunity < 0) {
            $immunity = 0;
        }

        if ($immunity > 100) {
            $immunity = 100;
        }

        $error = 0;
        $message = "";
        $field = "";

        $copy_usergroup = $copy == 0 ? false : true;

        $usrgrp = DB::table('usergroups')->where('usergroupid', $copy)->first();

        if (!count($usrgrp) && $copy_usergroup) {
            $copy_usergroup = false;
            $error = 1;
            $field = "group-add-copy";
            $message = "Usergroup you trying to copy don't exist";
            return response()->json(array('success' => true, 'error' => $error, 'message' => $message, 'field' => $field));
        }

        if ($copy_usergroup && (($usrgrp->editable == 0 && !UserHelper::isSuperAdmin()) || $usrgrp->immunity > UserHelper::getImmunity(Auth::user()->userid))) {
            $copy_usergroup = false;
            $error = 1;
            $field = "group-add-copy";
            $message = "You are trying to copy usergroup you can't access";
        }

        if ($title == "") {
            $error = 1;
            $field = "group-add-title";
            $message = "Can't leave title empty!";
        }

        if (!is_int($height)) {
            $error = 1;
            $field = "group-add-height";
            $message = "Height needs to be a number!";
        }

        if (!is_int($width)) {
            $error = 1;
            $field = "group-add-width";
            $message = "Width needs to be a number!";
        }

        if ($error == 1) {
            return response()->json(array('success' => true, 'error' => $error, 'message' => $message, 'field' => $field));
        }

        $adminpermissions = $copy_usergroup == true ? $usrgrp->adminpermissions : 0;
        $staffpermissions = $copy_usergroup == true ? $usrgrp->staffpermissions : 0;
        $modpermissions = $copy_usergroup == true ? $usrgrp->modpermissions : 0;

        $groupid = DB::table('usergroups')->insertGetId([
            'title' => $title,
            'opentag' => '<span style="' . $opentag . '">',
            'closetag' => '</span>',
            'adminpermissions' => $adminpermissions,
            'staffpermissions' => $staffpermissions,
            'modpermissions' => $modpermissions,
            'avatar_height' => $height,
            'avatar_width' => $width,
            'editable' => $editable,
            'immunity' => $immunity,
            'lastedited' => time()
        ]);

        if ($copy_usergroup == true) {
            $copy_usrgrp_bar = DB::table('groupbars')->where('usergroupid', $usrgrp->usergroupid)->first();
            if (count($copy_usrgrp_bar)) {
                DB::table('groupbars')->insert([
                    'usergroupid' => $groupid,
                    'html' => $copy_usrgrp_bar->html,
                    'css' => $copy_usrgrp_bar->css,
                    'lastedited' => time()
                ]);
            }

            $forumpermissions = DB::table('forumpermissions')->where('usergroupid', $usrgrp->usergroupid)->get();

            foreach ($forumpermissions as $forumpermission) {
                DB::table('forumpermissions')->insert([
                    'forumid' => $forumpermission->forumid,
                    'usergroupid' => $groupid,
                    'forumpermissions' => $forumpermission->forumpermissions,
                    'lastedited' => time()
                ]);
            }

            $moderationpermissions = DB::table('moderationpermissions')->where('usergroupid', $usrgrp->usergroupid)->get();

            foreach ($moderationpermissions as $moderationpermission) {
                DB::table('moderationpermissions')->insert([
                    'forumid' => $moderationpermission->forumid,
                    'usergroupid' => $groupid,
                    'moderationpermissions' => $moderationpermission->moderationpermissions,
                    'lastedited' => time()
                ]);
            }
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Added usergroup',
            'content' => 1,
            'contentid' => $groupid,
            'affected_userid' => 0,
            'ip' => Auth::user()->lastip, 'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'error' => 0));
    }

    public function getAddGroup()
    {
        $is_super = UserHelper::isSuperAdmin();
        $usergroups = array();

        $temps = DB::table('usergroups')->whereNotIn('usergroupid', UserHelper::getExcludedGroups(Auth::user()->userid))->orderBy('title', 'ASC')->get();

        foreach ($temps as $temp) {
            $run = true;
            if (($temp->editable == 0 and !UserHelper::isSuperAdmin())) {
                $run = false;
            }

            if ($run) {
                $array = array(
                    'title' => $temp->title,
                    'groupid' => $temp->usergroupid
                );

                $usergroups[] = $array;
            }
        }

        $returnHTML = view('admincp.usergroup.addGroup')
            ->with('is_super', $is_super)
            ->with('immunity', UserHelper::getImmunity(Auth::user()->userid))
            ->with('usergroups', $usergroups)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getUsergroups()
    {
        $temps = DB::table('usergroups')->whereNotIn('usergroupid', UserHelper::getExcludedGroups(Auth::user()->userid))->orderBy('title', 'ASC')->get();
        $groups = array();

        foreach ($temps as $temp) {
            $run = true;
            if (($temp->editable == 0 and !UserHelper::isSuperAdmin())) {
                $run = false;
            }

            if ($run) {
                $users = DB::table('users')
                    ->whereNotIn('userid', UserHelper::getSuperAdmins())
                    ->where('displaygroup', $temp->usergroupid)
                    ->orWhere('usergroups', 'LIKE', $temp->usergroupid . ',%')
                    ->orWhere('usergroups', 'LIKE', '%,' . $temp->usergroupid)
                    ->orWhere('usergroups', 'LIKE', '%,' . $temp->usergroupid . ',%')
                    ->orderBy('username', 'ASC')
                    ->get(['userid', 'username']);

                $array = array(
                    'title' => $temp->title,
                    'groupid' => $temp->usergroupid,
                    'avatar_height' => $temp->avatar_height,
                    'avatar_width' => $temp->avatar_width,
                    'users' => $users,
                    'lastedited' => ForumHelper::timeAgo($temp->lastedited)
                );

                $groups[] = $array;
            }
        }

        $edit_admin_perms = false;
        $edit_mod_perms = false;
        $edit_staff_perms = false;
        $can_admin_users = false;
        $can_admin_general_mod_perms = false;

        if (UserHelper::haveAdminPerm(Auth::user()->userid, 4)) {
            $can_admin_users = true;
        }
        if (UserHelper::haveAdminPerm(Auth::user()->userid, 32)) {
            $edit_admin_perms = true;
        }
        if (UserHelper::haveAdminPerm(Auth::user()->userid, 64)) {
            $edit_mod_perms = true;
        }
        if (UserHelper::haveAdminPerm(Auth::user()->userid, 128)) {
            $edit_staff_perms = true;
        }
        if (UserHelper::haveAdminPerm(Auth::user()->userid, 2097152)) {
            $can_admin_general_mod_perms = true;
        }

        $returnHTML = view('admincp.usergroup.listUsergroups')
            ->with('groups', $groups)
            ->with('edit_admin_perms', $edit_admin_perms)
            ->with('edit_mod_perms', $edit_mod_perms)
            ->with('edit_staff_perms', $edit_staff_perms)
            ->with('can_admin_users', $can_admin_users)
            ->with('can_admin_general_mod_perms', $can_admin_general_mod_perms)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getEditBet($betid)
    {
        $bet = DB::table('betting_bets')->where('betid', $betid)->first();

        $returnHTML = view('admincp.betting.editBet')
            ->with('bet', $bet->bet)
            ->with('odds', $bet->odds)
            ->with('displayorder', $bet->displayorder)
            ->with('betid', $bet->betid)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getCreateBet()
    {
        $returnHTML = view('admincp.betting.createBet')
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getBetLog($pagenr) {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 20;
        $skip = 0;

        $pagesx = DB::table('betting_user')->count();

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

        $temps = DB::table('betting_user')->take($take)->skip($skip)->orderBy('dateline', 'DESC')->get();
        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/bets/logs/page/')->render();

        $bets = array();

        foreach($temps as $temp) {
            $bet = DB::table('betting_bets')->where('betid', $temp->betid)->first();
            $array = array(
                'betuser' => UserHelper::getUsername($temp->userid),
                'bet' => ''. $bet->bet . ' ' . $temp->odds,
                'amount' => $temp->amount,
                'dateline' => ForumHelper::getTimeInDate($temp->dateline),
            );
            $bets[] = $array;
        }

        $returnHTML = view('admincp.betting.logs')
            ->with('bets', $bets)
            ->with('pagi', $pagi)
            ->with('current_page', $pagenr)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getExistingBets($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 20;
        $skip = 0;

        $pagesx = DB::table('betting_bets')->where('finished',0)->count();

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

        $temps = DB::table('betting_bets')->where('finished',0)->take($take)->skip($skip)->orderBy('displayorder', 'ASC')->get();
        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/bets/page/')->render();

        $bets = array();
        foreach($temps as $temp){

            $array = array(
                'betid' => $temp->betid,
                'bet' => $temp->bet,
                'odds' => $temp->odds,
                'displayorder' => $temp->displayorder,
                'finished' => $temp->finished,
                'suspended' => $temp->suspended
            );

            $bets[] = $array;
        }

        $returnHTML = view('admincp.betting.listBets')
            ->with('bets', $bets)
            ->with('pagi', $pagi)
            ->with('current_page', $pagenr)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function endBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $result = $request->input('verdict');
        $keep = $request->input('keep');
        $response = false;

        $check = DB::table('betting_bets')->where('betid', $betid)->count();

        if ($check > 0) {

            if ($keep === FALSE || !$keep || $keep == 'false') {
                DB::table('betting_bets')->where('betid', $betid)->update([
                    'finished' => 1
                ]);
            }

            $bets = DB::table('betting_bets')->join('betting_user', 'betting_user.betid', '=', 'betting_bets.betid')->where('betting_user.betid', '=', $betid)->join('users', 'users.userid', '=', 'betting_user.userid')->get();

            foreach($bets as $bet){
                $winnings = $bet->amount + $bet->amount * explode("/", $bet->odds)[0] / explode("/", $bet->odds)[1];

                DB::table('betting_user')->where('betid', $betid)->update([
                    'result' => $result
                ]);

                if($result == 1){
                    DB::table('users')->where('userid', $bet->userid)->update([
                        'credits' => $bet->credits + $winnings
                    ]);
                }
            }

            DB::table('admin_log')->insert([
                'userid' => Auth::user()->userid,
                'description' => 'Ended a Bet',
                'content' => 20,
                'contentid' => $betid,
                'affected_userid' => 1,
                'ip' => Auth::user()->lastip,
                'dateline' => time()
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function suspendBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $response = false;

        $check = DB::table('betting_bets')->where('betid', $betid)->count();

        if ($check > 0) {
            DB::table('betting_bets')->where('betid', $betid)->update([
                'suspended' => 1
            ]);

            $response = true;
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Suspended a Bet',
            'content' => 20,
            'contentid' => $betid,
            'affected_userid' => 1,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function editBet(Request $request)
    {
        $bet = $request->input('bet');
        $odds = $request->input('odds');
        $displayorder = $request->input('displayorder');
        $betid = $request->input('betid');

        $response = false;

        $check = DB::table('betting_bets')->where('betid', $betid)->count();

        if (strlen($bet) <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Bet name can\'t be empty!'));
        }

        if (strpos($odds, '/') == false) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Odds must be in the format of 10/1!'));
        }

        if ($check > 0) {
            DB::table('betting_bets')->where('betid', $betid)->update([
                'bet' => $bet,
                'odds' => $odds,
                'displayorder' => $displayorder
            ]);

            $response = true;
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Edited a Bet',
            'content' => 20,
            'contentid' => $betid,
            'affected_userid' => 1,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function unsuspendBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $response = false;

        $check = DB::table('betting_bets')->where('betid', $betid)->count();

        if ($check > 0) {
            DB::table('betting_bets')->where('betid', $betid)->update([
                'suspended' => 0
            ]);

            $response = true;
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Unsuspended a Bet',
            'content' => 20,
            'contentid' => $betid,
            'affected_userid' => 1,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function deleteBet(Request $request)
    {
        $betid = $request->input('temp_betid');
        $response = false;
        $check = DB::table('betting_bets')->where('betid', $betid)->count();

        if ($check > 0) {
            DB::table('betting_bets')->where('betid', $betid)->delete();
            $response = true;
        }

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Deleted a Bet',
            'content' => 20,
            'contentid' => $betid,
            'affected_userid' => 1,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function createBet(Request $request)
    {
        $bet = $request->input('bet');
        $odds = $request->input('odds');
        $displayorder = $request->input('displayorder');

        if (strlen($bet) <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Bet name can\'t be empty!'));
        }

        if (strpos($odds, '/') == false) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Odds must be in the format of 10/1!'));
        }

        if (!is_numeric(explode("/", $odds)[0]) || !is_numeric(explode("/", $odds)[1])){
            return response()->json(array('success' => true, 'response' => false, 'message' => 'The odds must be numbers!'));
        }

        $theBet = DB::table('betting_bets')->insertGetId([
            'bet' => $bet,
            'odds' => $odds,
            'displayorder' => $displayorder
        ]);

        DB::table('admin_log')->insert([
            'userid' => Auth::user()->userid,
            'description' => 'Created a Bet',
            'content' => 20,
            'contentid' => $theBet,
            'affected_userid' => 1,
            'ip' => Auth::user()->lastip,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getAllClans($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 20;
        $skip = 0;

        $pagesx = DB::table('clans')->count();

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

        $clans = DB::table('clans')->take($take)->skip($skip)->orderBy('groupid', 'ASC')->get();
        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/admincp/clans/page/')->render();

        $returnHTML = view('admincp.clans.list')
            ->with('clans', $clans)
            ->with('pagi', $pagi)
            ->with('current_page', $pagenr)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getClanAccolade($clanid)
    {
        $clan = DB::table('clans')->where('groupid', $clanid)->first();

        if (count($clan)) {
            $temps = DB::table('clan_accolades')->where('groupid', $clanid)->orderBy('display_order', 'DESC')->get();
            $current_accolades = array();

            foreach($temps as $temp){
                $array = array(
                    'id' => $temp->id,
                    'accolade' => $temp->description,
                    'awarded_by' => UserHelper::getUsername($temp->awarded_id),
                    'date' => ForumHelper::timeAgo($temp->dateline),
                    'display_order' => $temp->display_order
                );

                $current_accolades[] = $array;
            }

            $returnHTML = view('admincp.clans.addAccolade')
                ->with('clan', $clan)
                ->with('current_accolades', $current_accolades)
                ->render();

            return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
        }

        return redirect()->route('getSearchUsers');
    }

    public function deleteClanAccolade(Request $request)
    {
        $accoladeid = $request->input('accoladeid');
        $groupid = $request->input('groupid');
        $response = false;

        DB::table('clan_accolades')->where('id', $accoladeid)->delete();

        $response = true;

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function addClanAccolade(Request $request)
    {
        $groupid = $request->input('groupid');
        $accolade = $request->input('accolade');
        $display = $request->input('display');
        $response = false;

        $accolade = ForumHelper::fixContent($accolade);

        $clan = DB::table('clans')->where('groupid', $groupid)->first();

        $accolade = '<span style="color: #FFB400"><i class="fa fa-trophy"></i> ' . $accolade . '</span>';

        if (count($clan)) {
            DB::table('clan_accolades')->insert([
                'groupid' => $groupid,
                'description' => $accolade,
                'display_order' => $display,
                'dateline' => time(),
                'awarded_id' => Auth::user()->userid
            ]);

            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditClanAccolade($accoladeid) {
        $temps = DB::table('clan_accolades')->where('id', $accoladeid)->first();

        $clan = DB::table('clans')->where('groupid', $temps->groupid)->first();

        $id = $temps->id;
        $accolade = $temps->description;
        $display_order = $temps->display_order;
        $groupname = $clan->groupname;
        $groupid = $temps->groupid;

        $returnHTML = view('admincp.clans.editAccolade')
            ->with('id', $id)
            ->with('accolade', $accolade)
            ->with('display_order', $display_order)
            ->with('groupname', $groupname)
            ->with('groupid', $groupid)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
}
