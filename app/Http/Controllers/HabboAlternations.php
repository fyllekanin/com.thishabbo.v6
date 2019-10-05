<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
use Image;
use Illuminate\Http\Request;
use App\Helpers\UserHelper;

class HabboAlternations extends BaseController
{
    public function __construct()
    {
    }

    public function getKissing()
    {
        $returnHTML = view('goodies.kissing_alternations')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getImager()
    {
        $returnHTML = view('goodies.habboImager')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getKiss($habbo1, $habbo2, $action)
    {
        $cookies = 'browser_token=thisisabrowsertoken;session.id=thisisasessionid;';
        $options = array(
          'http'=>array(
            'timeout' => 5,
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                      "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                      "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
          )
        );
        $context = stream_context_create($options);
        if (strlen($habbo1) == 0) {
            $habbo1 = "irDez";
        }
        if (strlen($habbo2) == 0) {
            $habbo2 = "Snooze";
        }
        switch ($action) {
            case 1:
                $imageContent1 = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo1 . "&direction=1&head_direction=1&action=&gesture=nrm&size=m&img_format=gif", false, $context);
                $imageContent2 = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo2 . "&direction=5&head_direction=5&action=&gesture=nrm&size=m&img_format=gif", false, $context);
                $imageContent3 = file_get_contents("_assets/img/website/goodies/hearts.png", false, $context);
                $imageContent4 = file_get_contents("_assets/img/website/goodies/cupido.png", false, $context);


                $img = Image::canvas(170, 170);
                $img->insert($imageContent3, 'bottom-right', 35, 70);
                $img->insert($imageContent1, 'bottom-right', 60, 10);
                $img->insert($imageContent2, 'bottom-right', 30, 10);
                $img->insert($imageContent4, 'bottom-right', -20, 100);
            break;
            case 2:
                $imageContent1 = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo1 . "&direction=2&head_direction=2&action=,crr=0&gesture=nrm&size=m&img_format=gif", false, $context);
                $imageContent2 = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo2 . "&direction=4&head_direction=2&gesture=nrm&size=m&img_format=gif", false, $context);
                $imageContent3 = file_get_contents("_assets/img/website/goodies/moon_gate_1.png", false, $context);
                $imageContent4 = file_get_contents("_assets/img/website/goodies/hearts_ground.png", false, $context);
                $imageContent5 = file_get_contents("_assets/img/website/goodies/moon_gate_2.png", false, $context);
                $imageContent6 = file_get_contents("_assets/img/website/goodies/hearts_dropping.png", false, $context);
                $img = Image::canvas(170, 170);
                $img->insert($imageContent3, 'bottom-right', -1, -5);
                $img->insert($imageContent4, 'bottom-right', -20, -70);
                $img->insert($imageContent1, 'bottom-right', 50, 20);
                $img->insert($imageContent2, 'bottom-right', 20, 17);
                $img->insert($imageContent5, 'bottom-right', -50, -30);
                $img->insert($imageContent6, 'bottom-right', -1, -5);
            break;
        }
        return response($img->encode('png'))->header('Content-Type', 'image/png');
    }

    public function getTop25Collectors()
    {
        $temps = DB::table('users')->where('amount_badges', '>', 0)->orderBy('amount_badges', 'DESC')->take(25)->where('habbo_verified', 1)->get();

        $top25 = array();
        foreach ($temps as $temp) {
            $array = array(
                'username' => UserHelper::getUsername($temp->userid),
                'clean_username' => $temp->username,
                'habbo' => $temp->habbo,
                'amount' => $temp->amount_badges
            );


            $top25[] = $array;
        }

        $returnHTML = view('goodies.top25Badge')->with('top25', $top25)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postScanHabbo(Request $request)
    {
        $cookies = 'browser_token=thisisabrowsertoken;session.id=thisisasessionid;';
        $options = array(
          'http'=>array(
            'timeout' => 5,
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                      "Cookie: $cookies foo=bar\r\n" .  // check function.stream-context-create on php.net
                      "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
          )
        );
        $context = stream_context_create($options);
        $response = false;
        $badges = array();
        $habbo = $request->input('habbo');
        $allBadges = array();
        $amountBadges = 0;
        $private = false;
        $habboContent = file_get_contents("http://www.habbo.com/api/public/users?name=" . $habbo, false, $context);
        if (!preg_match('/{"error":"not-found"}/', $habboContent)) {
            $habboContent = json_decode($habboContent);
            foreach ($habboContent->selectedBadges as $displayBadge) {
                $array = array(
                    'order' => $displayBadge->badgeIndex,
                    'code' => $displayBadge->code,
                    'name' => $displayBadge->name
                );
                $badges[] = $array;
            }
            ksort($badges);
            try {
                $habboBadges = file_get_contents("http://www.habbo.com/api/public/users/" . $habboContent->uniqueId . "/badges", false, $context);
                $habboBadges = json_decode($habboBadges);
                foreach ($habboBadges as $habboBadge) {
                    $array = array(
                        'code' => $habboBadge->code,
                        'name' => $habboBadge->name
                    );
                    $allBadges[] = $array;
                }
                $amountBadges = count($allBadges);
                $user = DB::table('users')->where('habbo', 'LIKE', $habbo)->where('habbo_verified', 1)->first();
                if (count($user)) {
                    DB::table('users')->where('userid', $user->userid)->update(['amount_badges' => $amountBadges]);
                }
            } catch (Exception $e) {
                $private = true;
            }
            $response = true;
        }
        return response()->json(array('success' => true, 'badges' => $badges, 'response' => $response, 'allBadges' => $allBadges, 'amountBadges' => $amountBadges, 'private' => $private));
    }

    public function getBadgeScanner()
    {
        $returnHTML = view('goodies.badgeScanner')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAlternations()
    {
        $returnHTML = view('goodies.alternations')->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAlt($habbo, $action)
    {
        $cookies = 'browser_token=thisisabrowsertoken;session.id=thisisasessionid;';
        $options = array(
          'http'=>array(
            'timeout' => 5,
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                      "Cookie: $cookies foo=bar\r\n" .  // check function.stream-context-create on php.net
                      "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
          )
        );

        $context = stream_context_create($options);

        if ($habbo == "") {
            $habbo = "optra";
        }

        $img = Image::canvas(150, 150);

        try {
            switch ($action) {
                case 1:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=2&action=sit&gesture=nrm&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/plane.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 15, 20);
                break;
                case 2:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=3&head_direction=3&&gesture=nrm&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/smoke.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/car.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 35, 60);
                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent3, 'bottom-right', 48, 20);
                break;
                case 3:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=4&action=sit&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/cats.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 30, 15);
                break;
                case 4:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=3&head_direction=3&&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/egg.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 45, 8);
                break;
                case 5:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=4&action=sit&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/hay.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/bird.png", false, $context);
                    $imageContent4 = file_get_contents("_assets/img/website/goodies/hay_hat.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 32, 10);
                    $img->insert($imageContent, 'bottom-right', 25, 20);
                    $img->insert($imageContent3, 'bottom-right', 55, 45);
                    $img->insert($imageContent4, 'bottom-right', 38, 88);
                break;
                case 6:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=4&action=crr=0&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/fire_ring.png", false, $context);

                    $img->insert($imageContent, 'bottom-right', 16, 30);
                    $img->insert($imageContent2, 'bottom-right', 20, 30);
                break;
                case 7:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=4&action=sit&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/fish_stone.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/fish_pole.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 30, 0);
                    $img->insert($imageContent, 'bottom-right', 35, 30);
                    $img->insert($imageContent3, 'bottom-right', 53, 41);
                break;
                case 8:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=2&head_direction=2&action=wlk&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/winner.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 21, 24);
                break;
                case 9:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=2&action=sit&gesture=srp&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/paper.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 25, 30);
                break;
                case 10:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo ."&direction=4&head_direction=2&&gesture=srp&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/rat.png", false, $context);

                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent2, 'bottom-right', 60, 33);
                break;
                case 11:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo ."&direction=4&head_direction=2&&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/scuba_back.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/scuba_front.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 55, 50);
                    $img->insert($imageContent, 'center');
                    $img->insert($imageContent3, 'bottom-right', 60, 70);
                break;
                case 12:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo ."&direction=4&head_direction=2&&gesture=srp&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/shark1.png", false, $context);

                    $img->insert($imageContent, 'bottom-right', 57, 22);
                    $img->insert($imageContent2, 'bottom-right', 0, 0);
                break;
                case 13:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo ."&direction=4&head_direction=2&action=sit&gesture=srp&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/shark2.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/lifesaver.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 35, 0);
                    $img->insert($imageContent3, 'bottom-right', 5, 35);
                    $img->insert($imageContent, 'bottom-right', 20, 40);
                break;
                case 14:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=2&action=crr=0&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/cat.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/book.png", false, $context);

                    $img->insert($imageContent, 'bottom-right', 10, 20);
                    $img->insert($imageContent2, 'bottom-right', 90, 20);
                    $img->insert($imageContent3, 'bottom-right', 45, 40);
                break;
                case 15:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=2&&gesture=srp&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/arrow1.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/arrow2.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 40, 59);
                    $img->insert($imageContent, 'bottom-right', 51, 15);
                    $img->insert($imageContent3, 'bottom-right', 90, 48);
                break;
                case 16:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=2&&gesture=sml&size=m", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/staff.png", false, $context);
                    $imageContent3 = file_get_contents("_assets/img/website/goodies/beard.png", false, $context);

                    $img->insert($imageContent, 'center', 0, 0);
                    $img->insert($imageContent2, 'bottom-right', 90, 40);
                    $img->insert($imageContent3, 'bottom-right', 48, 18);
                break;
                case 17:
                    $imageContent = file_get_contents("http://www.habbo.com/habbo-imaging/avatarimage?user=" . $habbo . "&direction=4&head_direction=4&gesture=srp&img_format=gif&headonly=1", false, $context);
                    $imageContent2 = file_get_contents("_assets/img/website/goodies/dress.png", false, $context);

                    $img->insert($imageContent2, 'bottom-right', 42, 7);
                    $img->insert($imageContent, 'center');
                break;
            }
        } catch (Exception $e) {
            // Nothing sadly
        }
        return response($img->encode('png'))->header('Content-Type', 'image/png');
    }
}
