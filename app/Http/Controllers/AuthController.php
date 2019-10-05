<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use DB;
use App\User;
use Hash;
use Illuminate\Http\Request;
use App\Helpers\ForumHelper;
use Auth;
use Mail;
use URL;
use Session;

class AuthController extends BaseController
{
    public function changePassword(Request $request)
    {
        $code = $request->input('code');
        $password = $request->input('pw');
        $repassword = $request->input('repw');

        if (!strlen($password) >= 8) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Password to short! atleast 8 length'));
        }

        $check = DB::table('forgot_passwords')->where('code', 'LIKE', $code)->where('dateline', '>=', time()-1800)->first();

        if (!count($check)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Code is invalid, don\'t exist or to old!'));
        }

        if ($password !== $repassword) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'Passwords does not match!'));
        }

        DB::table('users')->where('userid', $check->userid)->update(['password' => Hash::make($password)]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getChangePassword()
    {
        $returnHTML = view('auth.changepassword')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function forgotPassword(Request $request)
    {
        $email = $request->input('email');

        $user = DB::table('users')->whereRaw('lower(email) LIKE ?', [strtolower($email)])->first();

        if (count($user)) {
            $randstring = "";
            $length = 32;
            $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $randstring .= $characters[$rand];
            }

            $check = DB::table('forgot_passwords')->where('userid', $user->userid)->count();

            if ($check > 0) {
                DB::table('forgot_passwords')->where('userid', $user->userid)->update(['code' => $randstring, 'dateline' => time()]);
            } else {
                DB::table('forgot_passwords')->insert([
                    'userid' => $user->userid,
                    'code' => $randstring,
                    'dateline' => time()
                ]);
            }

            $to = $user->email;
            $subject = "ThisHabbo.com - Reset Password";

            $message = "
			<html>
			<head></head>
				<body style='background: black; color: white'>
					<b>Hi " . $user->email . "</b> <br />
					You recently requested to reset your password for your ThisHabbo account. Use the link below to change your password. <br />
					<br />
					<a href=\"" . URL::to('/') . "/auth/change/password?code=" . $randstring . "\">" . URL::to('/') . "/auth/change/password?code=" . $randstring . "</a>
					<br />
					<br />
					If you did not request a password reset, please ignore this email. This password reset is only valid for the next 30 minutes.
					<br />
					<br />
					Thanks,
					<br />
					ThisHabbo
				</body>
			</html>
			";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <noreply@thishabbo.com>' . "\r\n";

            mail($to, $subject, $message, $headers);
        }

        return response()->json(array('success' => true));
    }

    public function getForgot()
    {
        $returnHTML = view('auth.forgotten')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getLogin()
    {
        $returnHTML = view('auth.login')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getRegister()
    {
        setcookie('register-cookie', time(), time()+1800);
        $countrys = DB::table('countrys')->orderBy('name', 'ASC')->get();
        $timezones = DB::table('timezones')->get();
        $returnHTML = view('auth.register')
            ->with('countrys', $countrys)
            ->with('timezones', $timezones)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postLogin(Request $request)
    {
        $response = false;
        $rememberme = false;
        $message = "";
        $userid = 0;
        $username = '';

        if (!Auth::check()) {
            if ($request->input('rememberme') == 1) {
                $rememberme = true;
            }

            if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $rememberme) OR Auth::attempt(['habbo' => $request->username, 'password' => $request->password], $rememberme)) {
                if (Auth::user()->lastlogin == '') {
                    $homePage = '/rules';
                } else {
                    $homePage = Auth::user()->homePage != '' ? Auth::user()->homePage : '/home';
                }
                $username = Auth::user()->username;
                $userid = Auth::user()->userid;
                $response = true;
                $user = User::find(Auth::user()->userid);
                $user->loginip = $request->ip();
                $user->lastlogin = time();
                $user->save();

                $check2 = DB::table('users')->where('lastip', 'LIKE', $request->ip())->orderBy('username', 'ASC')->get();

                if(count($check2) > 1){

                    $threadContent = 'Multiple account detection has detected the following.

The user, ' . Auth::user()->username . ', has the same IP Address as:' . PHP_EOL;

                    foreach($check2 as $user){

                        $threadContent .= '- ' . $user->username . PHP_EOL;

                    }

                    $threadid = DB::table('threads')->insertGetId([
                        'title' => 'Multiple Account Detected (IP: ' . $request->ip() . ')',
                        'forumid' => 1129,
                        'open' => 1,
                        'visible' => 1,
                        'replys' => 0,
                        'postuserid' => 1,
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

                    $postid = DB::table('posts')->insertGetId([
                        'threadid' => $threadid,
                        'username' => 'ThisHabbo',
                        'userid' => 1,
                        'dateline' => time(),
                        'lastedit' => 0,
                        'content' => $threadContent,
                        'ipaddress' => $_SERVER['REMOTE_ADDR'],
                        'visible' => 1
                    ]);

                    DB::table('forums')->where('forumid', 1129)->update([
                        'lastpost' => time(),
                        'lastposterid' => 1,
                        'lastpostid' => $postid,
                        'lastthread' => time(),
                        'lastthreadid' => $threadid
                    ]);

                    DB::table('threads')->where('threadid', $threadid)->update([
                        'firstpostid' => $postid,
                        'lastpostid' => $postid
                    ]);

                    DB::table('users')->where('userid', 1)->update([
                        'postcount' => DB::raw('postcount+1'),
                        'threadcount' => DB::raw('threadcount+1')
                    ]);
                }

                $check = DB::table('users_banned')->where('userid', Auth::user()->userid)->where('banned_until', '>', time())->first();

                if (count($check)) {
                    $message = "You are banned until: " . date('Y-m-d g:i A', $check->banned_until) . ' GMT';
                    $response = false;
                    Auth::logout();
                } else {
                    $check = DB::table('users_banned')->where('userid', Auth::user()->userid)->where('banned_until', 0)->first();

                    if (count($check)) {
                        $message = "Your account has been permanently banned!";
                        $response = false;
                        Auth::logout();
                    }
                }
            } else {
                $homePage = '/home';
                $username = $request->username;
                $message = "Your Username/Habbo or Password is incorrect!";
            }
        } else {
            $response = true;
        }

        DB::table('login_log')->insert([
            'userid' => $userid,
            'username' => $username,
            'dateline' => time(),
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);

        $time = strtotime("now");
        DB::table('users')->where('userid', $userid)->update(['lastactivity' => $time, 'lastip' => $_SERVER['REMOTE_ADDR']]);

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message, 'homePage' => $homePage))->withCookie('thishabbo_login', 1, 604800);
    }

    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return response()->json(array('success' => true, 'reponse' => true))->withCookie('thishabbo_login', 0, 604800);
    }

    public function getProfileBox()
    {
        $returnHTML = view('menu-stuff.profile-box')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getProfileTop($mobile = false)
    {
        $returnHTML = view('menu-stuff.profile-top')
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getLoadContent($mobile)
    {
        $topContent = view('menu-stuff.profile-top')->render();
        if ($mobile) {
            $menuContent = view('menu-stuff.menu-mobile')->render();
        } else {
            $menuContent = view('menu-stuff.menu')
                ->with('homePage', Auth::check() ? Auth::user()->homePage : '')
                ->with('mobile', $mobile)
                ->render();
        }
        $mobileExtra = view('menu-stuff.extra-menu-mobile')->render();

        return response()->json(array('success' => true, 'topContent' => $topContent, 'menuContent' => $menuContent, 'mobileExtra' => $mobileExtra));
    }

    public function postRegister(Request $request)
    {
        if (DB::table('users')->where('lastip', 'LIKE', $request->ip())->where('lastactivity', '>', time()-10)->count() > 0) {
            return response()->json(array('success' => true, 'error' => 1, 'field' => '', 'message' => 'Failed'));
        }
        $username = $request->input('username');
        $pass = $request->input('pass');
        $repass = $request->input('repass');
        $country = $request->input('country');
        $timezone = $request->input('timezone');
        $referd = $request->input('referd');

        $username = ForumHelper::fixContent($username);
        if ($referd != '') {
            $u = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($referd)])->first();
            if (count($u)) {
                $referd = $u->userid;
            }
        }

        $field = "";
        $message = "";
        $error = 0;

        if($username != "" AND $pass != "" AND $repass != "" AND $country != "" AND $timezone != "") {
            $checkUser = DB::table('users')->where('username', 'LIKE', $username)->count();
            if($checkUser == 0) {
                if(ctype_alnum($username)) {
                    if($pass == $repass) {
                        if(strlen($pass) >= 8) {
                            //Everything is ok! Let's create the user!
                            $user = new User;

                            $user->username = str_replace(" ", "", $username);
                            $user->password = Hash::make($pass);
                            $user->usergroups = "";
                            $user->displaygroup = "";
                            $user->lastactivity = time();
                            $user->lastip = $request->ip();
                            $user->lastlogin = "";
                            $user->loginip = "";
                            $user->joindate = time();
                            $user->country = $country;
                            $user->timezone = $timezone;
                            $user->referdby = $referd;

                            if(!$user->save()) {
                                $field = "total";
                                $message = "Something went wrong! try again later";
                                $error = 1;
                            } else {
                                if($referd > 0) {
                                    DB::table('notifications')->insert([
                                        'postuserid' => $user->userid,
                                        'reciveuserid' => $referd,
                                        'content' => 10,
                                        'contentid' => $user->userid,
                                        'dateline' => time(),
                                        'read_at' => 0
                                    ]);
                                }
                            }
                        } else {
                            $field = "reg-form-password";
                            $message = "Password to short!";
                            $error = 1;
                        }
                    } else {
                        $field = "reg-form-password";
                        $message = "Password don't match!";
                        $error = 1;
                    }
                } else {
                    $field = "reg-form-username";
                    $message = "Username must not contain special characters!";
                    $error = 1;
                }
            } else {
                $field = "reg-form-username";
                $message = "Username already taken!";
                $error = 1;
            }
        } else {
            $field = "all";
            $message = "You didn't fill in all inputs!";
            $error = 1;
        }

        return response()->json(array('success' => true, 'error' => $error, 'field' => $field, 'message' => $message));
    }
}
