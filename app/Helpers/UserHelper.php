<?php namespace App\Helpers;

use Cache;
use App\User;
use DB;
use Auth;
use App\Helpers\ForumHelper;

class UserHelper
{
    private static $superAdmins = array(2,96,43325,122);

    public static function getUser($userid) {
        $user = DB::table('users')->where('userid', $userid)->first();
        return $user;
    }

    // FOR USERS TO BE BLOCKED FROM VIEWING THREADS
    public static function isThreadBanned($threaid)
    {
        $threadid = $threaid;
        if (Auth::check()) {
            $userid = Auth::user()->userid;
            return DB::table('threads')->where('threadid', $threadid)->whereRaw('find_in_set("' . $userid . '", banned_userids)')->count() > 0;
        }
        
        return false;
    }

    // FOR MODS TO BE ABLE TO UNBAN FROM THREAD
    public static function isuserThreadBanned($threadid, $userid)
    {
        return DB::table('threads')->where('threadid', $threadid)->whereRaw('find_in_set("' . $userid . '", banned_userids)')->count() > 0;
    }

    public static function memberOfGroup($groupid)
    {
        $usergroups = explode(',', Auth::user()->usergroups);
        return in_array($groupid, $usergroups);
    }

    public function updateActivity()
    {
        if (Auth::check()) {
            $time = strtotime("now");
            $userid = Auth::user()->userid;
            DB::table('users')->where('userid', $userid)->update(['lastactivity' => $time, 'lastip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public static function getPackageUsertext($packageid)
    {
        $response = array(
            'haveUsertext' => 0,
            'text' => ''
        );

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();
        if (count($package)) {
            $usergroup = DB::table('usergroups')->where('usergroupid', $package->usergroupid)->first();
            if (count($usergroup)) {
                if (!($usergroup->features & 2) && !($usergroup->features & 4) && !($usergroup->features & 8)) {
                    //usergroup bar
                    $response['haveUsertext'] = 1;
                    $response['text'] = $usergroup->opentag . 'Username' . $usergroup->closetag;
                } else {
                    if ($usergroup->features & 2) {
                        //userbar one color
                        $response['haveUsertext'] = 1;
                        $response['text'] = '<span style="color: #000000; font-weight: bold;">Username</span>';
                    } elseif ($usergroup->features & 32) {
                        //rainbow color
                        $response['haveUsertext'] = 1;
                        $response['text'] = '<span class="rainbow-text">Username</span>';
                    } elseif ($usergroup->features & 64) {
                        //custom rainbow
                        $response['haveUsertext'] = 1;
                        $response['text'] = '<span style="-webkit-text-fill-color: transparent; background: -webkit-gradient(linear, left top, right top, color-stop(0, #333333), color-stop(0.3, #333333), color-stop(0.6, #333333)); -webkit-background-clip: text; background-size: 0; -webkit-background-size: auto; -o-background-size: 0; font-weight: bold;">Username</span>';
                    }
                }
            }
        }
        return $response;
    }

    public static function getPackageUserbar($packageid)
    {
        $response = array(
            'haveUserbar' => 0,
            'html' => '',
            'css' => ''
        );

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();
        if (count($package)) {
            $usergroup = DB::table('usergroups')->where('usergroupid', $package->usergroupid)->first();
            if (count($usergroup)) {
                if (!($usergroup->features & 16) && !($usergroup->features & 32) && !($usergroup->features & 64)) {
                    //usergroup bar
                    $groupbar = DB::table('groupbars')->where('usergroupid', $usergroup->usergroupid)->first();
                    if (count($groupbar)) {
                        $response['haveUserbar'] = 1;
                        $response['html'] = $groupbar->html;
                        $response['css'] = $groupbar->css;
                    }
                } else {
                    if ($usergroup->features & 16) {
                        //userbar one color
                        $response['haveUserbar'] = 1;
                        $response['html'] = '<div class="thc-cutom-bar-template" style="border-radius: 3px;">' . $package->userbar_text . '</div>';
                    } elseif ($usergroup->features & 32) {
                        //rainbow color
                        $response['haveUserbar'] = 1;
                        $response['html'] = '<div class="thc-cutom-bar-template rainbow-static-bar-colors" style="border-radius: 3px;">' . $package->userbar_text . '</div>';
                    } elseif ($usergroup->features & 64) {
                        //custom rainbow
                        $response['haveUserbar'] = 1;
                        $response['html'] = '<div class="thc-cutom-bar-template" style="background: -webkit-gradient(linear, 0 top, 138 45, color-stop(0,#333333), color-stop(0.3,#333333), color-stop(0.6,#333333)); border-radius: 3px;">' . $package->userbar_text . '</div>';
                    }
                }
            }
        }
        return $response;
    }

    public static function getAvatarSize($usergroups)
    {
        $groups = explode(",", $usergroups);
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
        return 'style="max-width:' . $max_width . 'px; max-height: ' . $max_height . 'px;"';
    }

    public static function getImmunity($userid)
    {
        $immunity = 0;

        if (self::isSuperAdmin($userid)) {
            return 101;
        }

        $user = self::getUser($userid);
        if (count($user)) {
            $usergroups = explode(',', $user->usergroups);
            foreach ($usergroups as $usergroup) {
                $grp = DB::table('usergroups')->where('usergroupid', $usergroup)->first();
                if (count($grp) && $grp->immunity > $immunity) {
                    $immunity = $grp->immunity;
                }
            }
        }

        return $immunity;
    }

    public static function getExcludedGroups($userid)
    {
        $immunity = self::getImmunity($userid);
        return DB::table('usergroups')->where('immunity', '>', $immunity)->pluck('usergroupid');
    }

    public static function getConverstation($userid)
    {
        $user = self::getUser($userid);

        if (!count($user)) {
            $response = array(
                'username' => '__blank__',
                'clean_username' => 'ThisHabbo',
                'userid' => '1'
            );
        }

        $response = array(
            'username' => self::getUsername($user->userid),
            'userid' => $user->userid,
            'clean_username' => $user->username
        );

        $messages = array();
        $temps = DB::table('private_messages')
        ->where([
            ['recive_userid', '=', Auth::user()->userid],
            ['post_userid', '=', $user->userid]
        ])
        ->orWhere([
            ['recive_userid', '=', $user->userid],
            ['post_userid', '=', Auth::user()->userid]
        ])
        ->get();

        foreach ($temps as $temp) {
            $content = ForumHelper::fixContent($temp->content);
            $content = ForumHelper::replaceEmojis($content);
            $content = ForumHelper::bbcodeParser($content);
            $content = nl2br($content);
            $messages[] = array(
                'userid' => $temp->post_userid,
                'content' => $content,
                'dateline' => ForumHelper::timeAgo($temp->dateline),
                'username' => self::getUsername($temp->post_userid, true),
                'clear_dateline' => $temp->dateline,
                'me' => $temp->post_userid == Auth::user()->userid ? true : false,
                'read' => $temp->read_at > 0 ? true : false
            );
        }

        DB::table('private_messages')
        ->where('recive_userid', Auth::user()->userid)
        ->where('post_userid', $user->userid)
        ->where('read_at', 0)
        ->update(['read_at' => time()]);

        usort($messages, function ($b, $a) {
            if ($a['clear_dateline'] < $b['clear_dateline']) {
                return -1;
            } elseif ($a['clear_dateline'] == $b['clear_dateline']) {
                return 0;
            } else {
                return 1;
            }
        });

        $response['messages'] = $messages;

        return $response;
    }

    public static function saveAnimatedImage($file, $name, $path)
    {
        try {
            $filename        = $name . '.gif';
            $uploadSuccess   = $file->move($path, $filename);
            return $path . $filename;
        } catch (Exception $e) {
        }
    }

    public static function getSuperAdmins()
    {
        return array_diff(self::$superAdmins, [Auth::user()->userid]);
    }

    public static function isSuperAdmin($userid = 0)
    {
        $userid = $userid == 0 ? Auth::user()->userid : $userid;
        return in_array($userid, self::$superAdmins);
    }

    public static function url_exists($url)
    {
        $headers=get_headers($url);
        return stripos($headers[0], "200 OK")?true:false;
    }

    public static function getAvatar($userid)
    {
        $user = self::getUser($userid);
        $avatar = asset('_assets/img/website/default_avatar.png');

	if (count($user)) {
            if ($user->avatar != 0 && file_exists('_assets/img/avatars/' . $user->avatar . '.gif')) {
                if ($user->lastavataredit > (time() - 300)) {
                    $avatar = asset('_assets/img/avatars/' . $user->avatar . '.gif?' . rand());
                } else {
                    $avatar = asset('_assets/img/avatars/' . $user->avatar . '.gif');
                }
            }
        }

        return $avatar;
    }

    public static function getUserbar($userid, $groupid)
    {
        if (Cache::has('user-' . $userid . '-group-' . $groupid . '-bar')) {
            return Cache::get('user-' . $userid . '-group-' . $groupid . '-bar');
        }
        $user = self::getUser($userid);
        $response = [ 'html' => '', 'css' => ''];
        $run_default = true;
        $userbar_html = "";
        $userbar_css = "";
        $userbar_option = 0;
        $userbar_color = "";
        if (!$groupid) {
            Cache::add('user-' . $userid . '-group-' . $groupid . '-bar', $response, 1);
            return $response;
        }
        if (count($user)) {
            $userbardatas = explode("%", $user->userbardata);
            foreach ($userbardatas as $userbardata) {
                if (substr($userbardata, 0, 1) === $groupid) {
                    $data = substr($userbardata, 2);
                    $data = explode('/', $data);
                    $data[1] = str_replace("]", "", $data[1]);
                    $data_option = explode(':', $data[0]);
                    $userbar_option = $data_option[1];
                    $data_color = explode(':', $data[1]);
                    $userbar_color = $data_color[1];
                }
            }
            $sub = DB::table('subscription_packages')->where('usergroupid', $groupid)->first();
            $bar_text = "";
            if (count($sub)) {
                $bar_text = $sub->userbar_text;
            }
            if ($userbar_option == 1 and self::haveSubFeature($user->userid, 16, $groupid)) {
                //One Color
                $css = 'background: ' . $userbar_color;
                $html = '<div class="thc-cutom-bar-template" style="' . $css . '">' . $bar_text . '</div>';
                $response['html'] = $html;
                $response['css'] = "";
                $run_default = false;
            } elseif ($userbar_option == 2 and self::haveSubFeature($user->userid, 32, $groupid)) {
                $html = '<div class="thc-cutom-bar-template rainbow-static-bar-colors">' . $bar_text . '</div>';
                $response['html'] = $html;
                $response['css'] = "";
                $run_default = false;
            } elseif ($userbar_option == 3 and self::haveSubFeature($user->userid, 64, $groupid)) {
                //Custom Rainbow Color
                $colors = explode(",", $userbar_color);
                $math = 0;
                $count = count($colors);
                $add = bcdiv(1, $count, 2);
                foreach ($colors as $color) {
                    if ($math == 0) {
                        $color_stop = 'color-stop(' . $math . ',' . $color . ')';
                    } else {
                        $color_stop = $color_stop . ', color-stop(' . $math . ',' . $color . ')';
                    }
                    $math += $add;
                }
                $css = "background-image: url('/_assets/img/website/bargradient.png'), -webkit-gradient(linear, 0 top, 138 45, " . $color_stop . ");";
                $html = '<div class="thc-cutom-bar-template" style="' . $css . '">' . $bar_text . '</div>';
                $response['html'] = $html;
                $response['css'] = "";
                $run_default = false;
            }
            if ($run_default) {
                $groupbar = DB::table('groupbars')->where('usergroupid', $groupid)->first();
                if (count($groupbar)) {
                    $response['html'] = $groupbar->html;
                    $response['css'] = $groupbar->css;
                }
            }
        }
        Cache::add('user-' . $userid . '-group-' . $groupid . '-bar', $response, 1);
        return $response;
    }

    public static function getUsername($userid, $clean = false) {
        $user = self::getUser($userid);
        $response = "__blank__";
        $run_default = true;

        if (Cache::has('user-username-' . $userid) && !$clean) {
            return Cache::get('user-username-' . $userid);
        }

        if (count($user)) {
            if ($clean) {
                return $user->username;
            } else {
                if ($user->username_option > 0) {
                    if ($user->username_option == 1 and self::haveSubFeature($user->userid, 2)) {
                        $response = '<span style="color: ' . $user->username_color . '; font-weight: bold;">' . $user->username . '</span>';
                        $run_default = false;
                    } elseif ($user->username_option == 2 and self::haveSubFeature($user->userid, 4)) {
                        $response = '<span class="rainbow-text">' . $user->username . '</span>';
                        $run_default = false;
                    } elseif ($user->username_option == 3 and self::haveSubFeature($user->userid, 8)) {
                        $part = $user->username_color;
                        if (strpos($part, 'http') !== false && strpos($part, 'BITCH-') !== false) {
                            $url = explode('-', $part)[1];
                            return '<span style="font-weight: bold; color: white; background: url(' . $url . ') no-repeat; background-position: center; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">' . $user->username . '</span>';
                        } else if (strpos($part, 'http') !== false) {
                            return $user->username;
                        }
                        $pieces = explode(",", $part);
                        $color_stops = "";
                        $math = 0;

                        $first = true;

                        $count = count($pieces);

                        $add = bcdiv(1, $count, 2);

                        for ($i = 0; $i < $count; $i++) {
                            if ($first == true) {
                                $first = false;
                                $color_stops = $color_stops . " color-stop(" . $math . ", " . $pieces[$i] . ")";
                            } else {
                                $color_stops = $color_stops . ", color-stop(" . $math . ", " . $pieces[$i] . ")";
                            }
                            $math += $add;
                        }

                        $response = '<span style="-webkit-text-fill-color: transparent; background: -webkit-gradient(linear, left top, right top, ' . $color_stops . '); -webkit-background-clip: text; background-size: 0; -webkit-background-size: auto; -o-background-size: 0; font-weight: bold;">' . $user->username . '</span>';
                        $run_default = false;
                    }
                }

                if ($run_default) {
                    $group = DB::table('usergroups')->where('usergroupid', $user->displaygroup)->first();
                    if (count($group)) {
                        $response = $group->opentag . $user->username . $group->closetag;
                    } else {
                        $response = '<span style="color: #696969;">' . $user->username . '</span>';
                    }
                }

                $name_icon = "";

                if ($user->name_icon > 0) {
                    $padding = "margin: -1px 1px 0px 1px;";
                    if ($user->name_icon_side == 0) {
                        $padding = "margin: -1px 1px 0px 1px;";
                    }

                    $name_icon = '<img src="' . asset('_assets/img/nameicons/'.$user->name_icon.'.gif') . '" style="' . $padding . ' width: 16px; height: 16px;" />';
                }
                if ($user->name_effect > 0) {
                    $response = '<span class="nameEffectClass" style="background-image: url(' .  asset('_assets/img/nameeffects/'.$user->name_effect.'.gif') . '">' . $response . '</span>';
                }
                if ($user->name_icon_side == 1) {
                    $response = $response . $name_icon;
                } else {
                    $response = $name_icon . $response;
                }
            }
        }
        Cache::add('user-username-' . $userid, $response, 1);
        return $response;
    }

    public static function getRegion($userid) {
        $user = self::getUser($userid);
        return $user ? $user->region : '__blank__';
    }

    public static function getHabbo($userid, $clean = false) {
        $habbo = self::getUser($userid);
        $response = "__blank__";
        $run_default = true;

        if (count($habbo)) {
            if ($clean) {
                return $habbo->habbo;
            } else {
                if ($habbo->username_option > 0) {
                    if ($habbo->username_option == 1 and self::haveSubFeature($habbo->userid, 2)) {
                        $response = '<span style="color: ' . $habbo->username_color . '; font-weight: bold;">' . $habbo->habbo . '</span>';
                        $run_default = false;
                    } elseif ($habbo->username_option == 2 and self::haveSubFeature($habbo->userid, 4)) {
                        $response = '<span class="rainbow-text">' . $habbo->habbo . '</span>';
                        $run_default = false;
                    } elseif ($habbo->username_option == 3 and self::haveSubFeature($habbo->userid, 8)) {
                        $part = $habbo->username_color;
                        if (strpos($part, 'http') !== false) {
                            return '<span style="font-weight: bold; color: white; background: url(' . $part . ') no-repeat; background-position: center; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">' . $user->username . '</span>';
                        }
                        $pieces = explode(",", $part);
                        $color_stops = "";
                        $math = 0;

                        $first = true;

                        $count = count($pieces);

                        $add = bcdiv(1, $count, 2);

                        for ($i = 0; $i < $count; $i++) {
                            if ($first == true) {
                                $first = false;
                                $color_stops = $color_stops . " color-stop(" . $math . ", " . $pieces[$i] . ")";
                            } else {
                                $color_stops = $color_stops . ", color-stop(" . $math . ", " . $pieces[$i] . ")";
                            }
                            $math += $add;
                        }

                        $response = '<span style="-webkit-text-fill-color: transparent; background: -webkit-gradient(linear, left top, right top, ' . $color_stops . '); -webkit-background-clip: text; background-size: 0; -webkit-background-size: auto; -o-background-size: 0; font-weight: bold;">' . $habbo->habbo . '</span>';
                        $run_default = false;
                    }
                }

                if ($run_default) {
                    $group = DB::table('usergroups')->where('usergroupid', $habbo->displaygroup)->first();
                    if (count($group)) {
                        $response = $group->opentag . $habbo->habbo . $group->closetag;
                    } else {
                        $response = $habbo->habbo;
                    }
                }
            }
        }

        return $response;
    }


    public static function haveForumPerm($userid, $forumid, $perm)
    {
        $response = false;

        $default = DB::table('forumpermissions')->where('forumid', $forumid)->where('usergroupid', 0)->first();

        if (count($default)) {
            if ($default->forumpermissions & $perm) {
                return true;
            }
        }

        if (!$response) {
            $user = self::getUser($userid);
            if (in_array($userid, self::$superAdmins)) {
                return true;
            } else {
                if (count($user)) {
                    $groups = explode(",", $user->usergroups);

                    foreach ($groups as $group) {
                        $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                        if (count($grp)) {
                            $permissions = DB::table('forumpermissions')->where('usergroupid', $group)->where('forumid', $forumid)->first();
                            if (count($permissions)) {
                                if ($permissions->forumpermissions & $perm) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }

    public static function haveExtraOn($userid, $perm)
    {
        $user = self::getUser($userid);

        if (count($user)) {
            return $user->extras & $perm;
        }

        return false;
    }

    public static function haveModPerm($userid, $forumid, $perm)
    {
        $response = false;

        $user = self::getUser($userid);

        if (in_array($userid, self::$superAdmins)) {
            return true;
        } else {
            if (count($user)) {
                $groups = explode(",", $user->usergroups);

                foreach ($groups as $group) {
                    $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                    if (count($grp)) {
                        $permissions = DB::table('moderationpermissions')->where('usergroupid', $group)->where('forumid', $forumid)->first();
                        if (count($permissions)) {
                            if ($permissions->moderationpermissions & $perm) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }

    public static function haveAdminPerm($userid, $perm)
    {
        $response = false;

        if (in_array($userid, self::$superAdmins)) {
            return true;
        } else {
            if (!is_int($perm)) {
                $perm = intval($perm);
            }

            $user = self::getUser($userid);

            if (count($user)) {
                $groups = explode(",", $user->usergroups);

                foreach ($groups as $group) {
                    $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                    if (count($grp)) {
                        if ($grp->adminpermissions & $perm) {
                            return true;
                        }
                    }
                }
            }
        }

        return $response;
    }

    public static function haveSubFeature($userid, $feature, $groupid = false)
    {
        $response = false;

        if (!is_int($feature)) {
            $feature = intval($feature);
        }

        $user = self::getUser($userid);

        if (count($user)) {
            if (!$groupid) {
                $groups = explode(",", $user->usergroups);

                foreach ($groups as $group) {
                    $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                    if (count($grp)) {
                        if ($grp->features & $feature) {
                            return true;
                        }
                    }
                }
            } else {
                $grp = DB::table('usergroups')->where('usergroupid', $groupid)->first();

                if (count($grp)) {
                    if ($grp->features & $feature) {
                        return true;
                    }
                }
            }
        }

        return $response;
    }

    public static function haveStaffPerm($userid, $perm)
    {
        $response = false;

        if (!is_int($perm)) {
            $perm = intval($perm);
        }

        $user = self::getUser($userid);

        if (count($user)) {
            $groups = explode(",", $user->usergroups);

            foreach ($groups as $group) {
                $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                if (count($grp)) {
                    if ($grp->staffpermissions & $perm) {
                        return true;
                    }
                }
            }
        }

        if (in_array($userid, self::$superAdmins)) {
            return true;
        }

        return $response;
    }

    public static function haveGeneralModPerm($userid, $perm)
    {
        $response = false;

        if (!is_int($perm)) {
            $perm = intval($perm);
        }

        $user = self::getUser($userid);

        if (count($user)) {
            $groups = explode(",", $user->usergroups);

            foreach ($groups as $group) {
                $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

                if (count($grp)) {
                    if ($grp->modpermissions & $perm) {
                        return true;
                    }
                }
            }
        }

        if (in_array($userid, self::$superAdmins)) {
            return true;
        }

        return $response;
    }

    public static function checkBadge($action)
    {
        /*
            1 = posts
        */

        $temps = DB::table('users_badges')->where('userid', Auth::user()->userid)->get();
        $badges = array();
        foreach ($temps as $temp) {
            $badges[] = $temp->badgeid;
        }

        switch ($action) {
            case 1:
                $posts = Auth::user()->postcount + 1;

                if ($posts >= 10 and !in_array(11, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 11,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 11,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 100 and !in_array(12, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 12,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 12,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 200 and !in_array(13, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 13,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 13,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 300 and !in_array(14, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 14,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 14,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 500 and !in_array(15, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 15,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 15,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 700 and !in_array(16, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 16,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 16,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 1000 and !in_array(17, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 17,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 17,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 2000 and !in_array(18, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 18,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 18,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 3000 and !in_array(19, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 19,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 19,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 4000 and !in_array(20, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 20,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 20,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 5000 and !in_array(22, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 22,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 22,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 6000 and !in_array(23, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 23,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 23,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 7000 and !in_array(24, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 24,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 24,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 8000 and !in_array(26, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 26,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 26,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 9000 and !in_array(27, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 27,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 27,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 10000 and !in_array(28, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 28,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 28,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 12000 and !in_array(29, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 29,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 29,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }

                if ($posts >= 15000 and !in_array(30, $badges)) {
                    DB::table('users_badges')->insert([
                        'userid' => Auth::user()->userid,
                        'badgeid' => 30,
                        'dateline' => time()
                    ]);

                    DB::table('notifications')->insert([
                        'postuserid' => 0,
                        'reciveuserid' => Auth::user()->userid,
                        'content' => 7,
                        'contentid' => 30,
                        'dateline' => time(),
                        'read_at' => 0
                    ]);
                }
            break;
        }
    }
}
