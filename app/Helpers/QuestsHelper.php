<?php namespace App\Helpers;

use App\User;
use DB;
use Auth;
use App\Helpers\UserHelper;

class QuestsHelper
{
    public static function getQuest($badge)
    {
        $row = DB::table('articles')->where('badge_code', $badge)->first();
        if ($row) {
            return $row->articleid;
        } else {
            return -1;
        }
    }

    public static function haveCompletedQuest($articleid)
    {
        if (Auth::check()) {
            $userid = Auth::user()->userid;
            $completed = DB::table('articles')->where('articleid', $articleid)->value('completed_userids');
            if ($completed=="") {
                return false;
            } else {
                $completed_array = explode(',', $completed);
                return in_array($userid, $completed_array);
            }
        }
        return false;
    }

    public static function getQuestLikers($articleid)
    {
        $likers = DB::table('notifications')->where('content', 15)->where('contentid', $articleid)->get();

        $likers_strike = "";
        $others = 0;
        $first = 1;

        if (count($likers)) {
            foreach ($likers as $liker) {
                if ($others < 4) {
                    if ($first == 1) {
                        $likers_strike = UserHelper::getUsername($liker->postuserid);
                        $first= 0;
                    } else {
                        $likers_strike = $likers_strike . ", " . UserHelper::getUsername($liker->postuserid);
                    }
                } else {
                    break;
                }
                $others++;
            }
        }

        if ($others > 4) {
            $others -= 4;
            $likers_strike = $likers_strike . ' and ' . $others . ' more likes this guide!';
        } else {
            $likers_strike = $likers_strike . ' likes this guide!';
        }

        return array('have_likers' => $first, 'likers_strike' => $likers_strike);
    }

    public static function getCommentLikers($commentid)
    {
        $likers = DB::table('notifications')->where('content', 16)->where('contentid', $commentid)->get();

        $likers_strike = "";
        $others = 0;
        $first = 1;

        if (count($likers)) {
            foreach ($likers as $liker) {
                if ($others < 4) {
                    if ($first == 1) {
                        $likers_strike = UserHelper::getUsername($liker->postuserid);
                        $first= 0;
                    } else {
                        $likers_strike = $likers_strike . ", " . UserHelper::getUsername($liker->postuserid);
                    }
                } else {
                    break;
                }
                $others++;
            }
        }

        if ($others > 4) {
            $others -= 4;
            $likers_strike = $likers_strike . ' and ' . $others . ' more likes this comment!';
        } else {
            $likers_strike = $likers_strike . ' likes this comment!';
        }

        return array('have_likers' => $first, 'likers_strike' => $likers_strike);
    }

    public static function isSubscribed($badge)
    {
        if (Auth::check()) {
            $userid = Auth::user()->userid;
        } else {
            return false;
        }

        $subscribed = DB::table('habbo_badges')->where('badge_name', $badge)->value('subscribed_userids');
        if ($subscribed == Auth::user()->userid) {
            return true;
        }
        $subscribed_array = explode(',', $subscribed);

        return in_array($userid, $subscribed_array);
    }
}
