<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use App\Helpers\UserHelper;
use App\Helpers\StaffHelper;
use App\Helpers\ShopHelper;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* START RADIO LOGGING */
        $schedule->call(function () {
            $time = time();
            $last_record = DB::table('radio_logs')->orderBy('dateline', 'DESC')->first();

            $song = "";
            $listeners = 0;
            if (count($last_record)) {
                $song = $last_record->song;
                $listeners = $last_record->listeners;
            }


            $dnas_data = StaffHelper::getRadioStats();
            $day = date('N');
            $hour = date('G');

            $djid = 0;

            $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($dnas_data['SERVERGENRE'])])->first();

            if (count($user)) {
                $djid = $user->userid;
            }

            if ($dnas_data != null) {
                if ($dnas_data['SONGTITLE'] != $song) {
                    DB::table('radio_logs')->insert([
                            'djid' => $djid,
                            'dj' => $dnas_data['SERVERTITLE'],
                            'song' => $dnas_data['SONGTITLE'],
                            'listeners' => $dnas_data['UNIQUELISTENERS'],
                            'time' => $hour,
                            'day' => $day,
                            'dateline' => $time
                        ]);
                } else if($dnas_data['UNIQUELISTENERS'] > $listeners) {
                    DB::table('radio_logs')->where('dateline',$last_record->dateline)->update([
                        'song' => $dnas_data['SONGTITLE'],
                        'listeners' => $dnas_data['UNIQUELISTENERS'],
                        'dateline' => $time
                    ]);
                }
            }
        })->everyMinute();
        /* END RADIO LOGGING */

        // /* START OF RADIO POINTS - TEMP */
        $schedule->call(function () {
            $stats = DB::table('radio_stats')->first();
            $minute = date('i', time());

            if ($minute == '00') {
                return;
            }

            DB::table('radiopoints_temp')->insert([
                'listeners' => $stats->listeners,
                'djid' => $stats->djid,
                'minute' => $minute
            ]);
        })->everyTenMinutes();
        // /* END OF RADIO POINTS - TEMP */

        // /* START OF RADIO POINTS - AVERAGE */
        $schedule->call(function () {
            $counts = DB::table('radiopoints_temp')->get()->toArray();
            $data = DB::table('radiopoints_temp')->orderBy('minute', 'DESC')->first();
            $user = DB::table('users')->where('userid', $data->djid)->first();
            $total = array_reduce($counts, function($value, $count) {
                return $value + $count->listeners;
            }, 0);
            $points = $total / 5;

            $currentHour = intval(date('H'));
            $region = '';
            if (($currentHour >= 0 && $currentHour <= 5) || ($currentHour >= 22)) {
                $region = 'NA';
            } else if ($currentHour >= 6 && $currentHour <= 13) {
                $region = 'OC';
            } else if ($currentHour >= 14 && $currentHour <= 21) {
                $region = 'EU';
            }

            DB::table('radiopoints')->insert([
                'userid' => $user ? $user->userid : 0,
                'username' => $user ? $user->username : 0,
                'region' => $region,
                'points' => $points,
                'time' => date('i', time()),
                'day' => date('N'),
                'dateline' => time(),
            ]);
            DB::table('radiopoints_temp')->truncate();
        })->hourlyAt(59);
        // /* END OF RADIO POINTS - AVERAGE */

        /* RESET DJ SAYS HOURLY */
        $schedule->call(function () {
            $djsays = DB::table('djsays')->orderBy('dateline', 'DESC')->first();
            if ($djsays->djid != 1) {
                DB::table('djsays')->insert([
                    'djid' => '1',
                    'message' => 'The DJ says has not been set. Check back soon.',
                    'ipaddress' => '::1',
                    'dateline' => time()
                ]);
            }
        })->hourlyAt(01);
        /* END RESET DJ SAYS HOURLY */

        /* START CLEARING TIMETABLE FOR WEEK */
        $schedule->call(function () {
            $time = time();
            $day = date('N', $time);

            if ($day == 6) {
		    DB::table('timetable')->where('perm', '!=', 1)->where('day', '<=', 5)->where('day', '>', 1)->delete();
		    DB::table('timetable')->where('perm', '!=', 1)->where('day', 1)->where('time', '>', 5)->delete();
            }
        })->dailyAt('18:00');
        /* END CLEARING TIMETABLE FOR WEEK */

        /* START CLEARING TIMETABLE FOR WEEKEND */
        $schedule->call(function () {
            $time = time();
            $day = date('N', $time);

            if ($day == 1) {
		    DB::table('timetable')->where('perm', '!=', 1)->where('day', '>=', 6)->delete();
		    DB::table('timetable')->where('perm', '!=', 1)->where('day', 1)->where('time', '<=', 5)->delete();
            }
        })->dailyAt('18:00');
        /* END CLEARING TIMETABLE FOR WEEKEND */

        /* START STATS LOGGING */
        $schedule->call(function () {
            $time = strtotime('today midnight');
            DB::table('stats_log')->insert([
                  'posts' => DB::table('posts')->where('dateline', '>', $time)->count(),
                  'threads' => DB::table('threads')->where('dateline', '>', $time)->count(),
                  'creations' => DB::table('creations')->where('dateline', '>', $time)->count(),
                  'creation_comments' => DB::table('creation_comments')->where('dateline', '>', $time)->count(),
                  'articles' => DB::table('articles')->where('dateline', '>', $time)->count(),
                  'article_comments' => DB::table('article_comments')->where('dateline', '>', $time)->count(),
                  'visitor_messages' => DB::table('visitor_messages')->where('dateline', '>', $time)->count(),
                  'dateline' => time()
                ]);
        })->dailyAt('23:59');
        /* END STATS LOGGING */

        /* START AUTOMATED THREAD */
        $schedule->call(function () {
            $time = time();
            $day = date('N', $time);
            $hour = date('G', $time);
            $min = (substr(date('i', $time), 0, 1) === '0' ? substr(date('i', $time), 1, 1) : date('i', $time));

            $aus = DB::table('automated_threads')->where('day', $day)->where('hour', $hour)->where('minute', $min)->get();

            foreach ($aus as $au) {
                $user = DB::table('users')->where('userid', $au->postuserid)->first();

                if (count($user)) {
                    $title = $au->title;
                    $forumid = $au->forumid;

                    $postid = DB::table('posts')->insertGetId([
                            'threadid' => 0,
                            'username' => $user->username,
                            'userid' => $au->postuserid,
                            'dateline' => $time,
                            'lastedit' => 0,
                            'content' => $au->content,
                            'ipaddress' => $user->lastip,
                            'visible' => 1
                        ]);

                    $threadid = DB::table('threads')->insertGetId([
                            'title' => $title,
                            'forumid' => $au->forumid,
                            'postuserid' => $user->userid,
                            'dateline' => $time,
                            'firstpostid' => $postid,
                            'lastpost' => $time,
                            'lastpostid' => $postid,
                            'lastedited' => 0,
                        ]);

                    DB::table('forums')->where('forumid', $au->forumid)->update([
                            'posts' => DB::raw('posts+1'),
                            'threads' => DB::raw('threads+1'),
                            'lastpost' => $time,
                            'lastpostid' => $postid,
                            'lastposterid' => $au->postuserid,
                            'lastthread' => $time,
                            'lastthreadid' => $threadid
                        ]);

                    DB::table('posts')->where('postid', $postid)->update(['threadid' => $threadid]);

                    $time += 10;
                }
            }
        })->everyMinute();
        /* END AUTOMATED THREAD */

        // /* START BADGE SCANNING */
        // $schedule->call(function () {
        //     $time = time();
        //     $proxies = array(
        //         "dstiff:NcJH6t9Y@217.182.230.212:29842",
        //         "dstiff:NcJH6t9Y@217.182.230.254:29842",
        //         "dstiff:NcJH6t9Y@217.182.97.215:29842",
        //         "dstiff:NcJH6t9Y@217.182.97.35:29842",
        //         "dstiff:NcJH6t9Y@217.182.97.67:29842"
        //     );

        //     if (isset($proxies)) {  // If the $proxies array contains items, then
        //              $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        //     }

        //     $url = "https://www.habbo.com/gamedata/external_flash_texts/1";
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     if (isset($proxy)) {    // If the $proxy variable is set, then
        //             curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
        //     }
        //     curl_setopt($ch, CURLOPT_HEADER, false);
        //     curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //     $result = curl_exec($ch);
        //     curl_close($ch);
        //     $convert = explode("\n", $result);
        //     $count = count($convert);
        //     foreach ($convert as $conv) {
        //         if (preg_match('/_badge_name/', $conv)) {
        //             $temp = explode("_badge_name=", $conv);
        //             $count = DB::table('habbo_badges')->where('badge_name', $temp[0])->count();
        //             if ($count == 0) {
        //                 DB::table('habbo_badges')->insert([
        //                         'badge_name' => $temp[0],
        //                         'badge_desc' => $temp[1],
        //                         'dateline' => time(),
        //                         'subscribed_userids' => ""
        //                     ]);
        //             }
        //         } elseif (preg_match('/badge_name_/', $conv)) {
        //             $temp = explode("=", $conv);
        //             $name = explode("_", $temp[0]);
        //             $name = $name[2];
        //             $name = str_replace(" ", "", $name);
        //             $desc = $temp[1];
        //             $count = DB::table('habbo_badges')->where('badge_name', $name)->count();
        //             if ($count == 0) {
        //                 DB::table('habbo_badges')->insert([
        //                         'badge_name' => $name,
        //                         'badge_desc' => $desc,
        //                         'dateline' => time(),
        //                         'subscribed_userids' => ""
        //                     ]);
        //             }
        //         }
        //     }
        // })->everyThirtyMinutes();
        // /* END BADGE SCANNING */

        /* START SUB CLEARING */
        $schedule->call(function () {
            $time = time();
            $temps = DB::table('subscription_packages')->get();
            foreach ($temps as $temp) {
                $users = DB::table('users')
                    ->where('usergroups', 'LIKE', $temp->usergroupid)
                    ->orWhere('usergroups', 'LIKE', $temp->usergroupid . ',%')
                    ->orWhere('usergroups', 'LIKE', '%,' . $temp->usergroupid)
                    ->orWhere('usergroups', 'LIKE', '%,' . $temp->usergroupid . ',%')
                    ->get();

                foreach ($users as $user) {
                    $check = DB::table('subscription_subs')->where('userid', $user->userid)->where('packageid', $temp->packageid)->first();
                    $delete_sub = false;

                    if (count($check)) {
                        if ($check->end_date < $time) {
                            $delete_sub = true;
                        }
                    } else {
                        $delete_sub = true;
                    }

                    if ($delete_sub) {
                        $grps = explode(",", $user->usergroups);

                        $new_grps = "";
                        $first = true;
                        $new_display = 0;
                        foreach ($grps as $grp) {
                            if ($grp != $temp->usergroupid) {
                                if ($first) {
                                    $new_grps = $grp;
                                    $first = false;
                                    $new_display = $grp;
                                } else {
                                    $new_grps = $new_grps . ',' . $grp;
                                }
                            }
                        }

                        if ($temp->usergroupid == $user->displaygroup) {
                            DB::table('users')->where('userid', $user->userid)->update(['displaygroup' => $new_display]);
                        }

                        DB::table('users')->where('userid', $user->userid)->update(['usergroups' => $new_grps]);
                        DB::table('subscription_subs')->where('userid', $user->userid)->where('packageid', $temp->packageid)->delete();
                    }
                }
            }
        })->hourly();
        /* END SUB CLEARING */
    }
}
