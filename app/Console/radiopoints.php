// /* START OF RADIO POINTS - AVERAGE */
$schedule->call(function () {
    $counts = DB::table('radiopoints_temp')->get()->toArray();
    $data = DB::table('radiopoints_temp')->orderBy('minute', 'DESC')->first();
    $user = DB::table('users')->where('userid', $data->djid)->first();
    $total = array_reduce($counts, function($value, $count) {
        return $value + $count->listeners;
    }, 0);
    $points = $total / 5;

    $currentHour = date('H');
    if ($currentHour >= 14 && $currentHour <= 21) {
        $region = 'EU';
    } elseif ($currentHour >= 22 && $currentHour <= 5) {
        $region = 'NA';
    } elseif ($currentHour >= 6 && $currentHour <= 13) {
        $region = 'OC';
    }

    DB::table('radiopoints')->insert([
        'userid' => $user ? user->userid : '1',
        'username' => $user ? user->username : 'ThisHabbo',
        'region' => $region,
        'points' => $points,
        'time' => date('i', time()),
        'day' => date('N'),
        'dateline' => time(),
    ]);
    DB::table('radiopoints_temp')->truncate();
})->hourlyAt(59);
// /* END OF RADIO POINTS - AVERAGE */
