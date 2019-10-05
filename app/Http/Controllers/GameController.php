<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;

class GameController extends BaseController
{
    public function __construct()
    {
    }

    public function postSaveScore(Request $request)
    {
        $game = $request->input('gm');
        $score = $request->input('score');

        $message = "Score saved!";

        $leaders = DB::table('game_leaderboard')->where('game', $game)->take(5)->orderBy('score', 'DESC')->get();

        if (count($leaders) > 0) {
            foreach ($leaders as $leader) {
                if ($leader->score < $score) {
                    $message = "You just passed " . UserHelper::getUsername($leader->userid, true) . ' on the leaderboard!';
                }
            }
        }

        DB::table('game_leaderboard')->insert([
            'userid' => Auth::user()->userid,
            'score' => $score,
            'game' => $game,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true, 'message' => $message));
    }

    public function getBlockrain()
    {
        $leaders = array();

        $temps = DB::table('game_leaderboard')->where('game', 1)->take(5)->orderBy('score', 'DESC')->get();

        foreach ($temps as $temp) {
            $array = array(
                'avatar' => UserHelper::getAvatar($temp->userid),
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'score' => number_format($temp->score)
            );
            $leaders[] = $array;
        }

        $returnHTML = view('games.blockrain')
            ->with('leaders', $leaders)
            ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }
}
