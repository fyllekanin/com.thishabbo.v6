<?php namespace App\Http\Console;

use DB;

$time = time();

$proxies = array(); // Declaring an array to store the proxy list

    $proxies[] = "amcgui01:uhXrgNpB@192.80.185.31:29842";
    $proxies[] = "amcgui01:uhXrgNpB@50.118.141.168:29842";
    $proxies[] = "amcgui01:uhXrgNpB@192.80.185.82:29842";
    $proxies[] = "amcgui01:uhXrgNpB@50.118.141.251:29842";
    $proxies[] = "amcgui01:uhXrgNpB@192.80.185.190:29842";

    if (isset($proxies)) {  // If the $proxies array contains items, then
        $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
    }

    $url = "https://www.habbo.com/gamedata/external_flash_texts/1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if (isset($proxy)) {    // If the $proxy variable is set, then
        curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
    }
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec($ch);
    curl_close($ch);
    $convert = explode("\n", $result);
    $count = count($convert);
    foreach ($convert as $conv) {
        if (preg_match('/_badge_desc/', $conv)) {
            $temp = explode("_badge_desc=", $conv);
            $count = DB::table('habbo_badges')->where('badge_name', $temp[0])->count();
            if ($count == 0) {
                DB::table('habbo_badges')->insert([
                    'badge_name' => $temp[0],
                    'badge_desc' => $temp[1],
                    'dateline' => time()
                ]);
            }
        } elseif (preg_match('/badge_desc_/', $conv)) {
            $temp = explode("=", $conv);
            $name = explode("_", $temp[0]);
            $name = $name[2];
            $name = str_replace(" ", "", $name);
            $desc = $temp[1];
            $count = DB::table('habbo_badges')->where('badge_name', $name)->count();
            if ($count == 0) {
                DB::table('habbo_badges')->insert([
                    'badge_name' => $name,
                    'badge_desc' => $desc,
                    'dateline' => time()
                ]);
            }
        }
    }
