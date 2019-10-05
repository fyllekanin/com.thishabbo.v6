<?php namespace App\Helpers;

use App\User;
use DB;
use Auth;
use App\Helpers\UserHelper;

class ShopHelper
{
    public static function packBox($userid, $boxtype)
    {
        $response = false;
        $purchaseId = 0;
        $possibles = DB::table('box_contents')->where('boxid', $boxtype)->get();

        foreach ($possibles as $key => $possible) {
            if ($possible->typeid == 1) {
                $checkTheme = DB::table('theme_users')->where('themeid', $possible->itemid)->where('userid', $userid)->get();
                if (count($checkTheme)) {
                    unset($possibles[$key]);
                    continue;
                }
            } elseif ($possible->typeid == 2) {
                $checkTheme = DB::table('name_icon_users')->where('iconid', $possible->itemid)->where('userid', $userid)->get();
                if (count($checkTheme)) {
                    unset($possibles[$key]);
                    continue;
                }
            } elseif ($possible->typeid == 3) {
                $checkTheme = DB::table('name_effect_users')->where('effectid', $possible->itemid)->where('userid', $userid)->get();
                if (count($checkTheme)) {
                    unset($possibles[$key]);
                    continue;
                }
            }
        }
        $message = "";

        if (count($possibles) >= 3) {
            $weighteditems = array();
            foreach ($possibles as $possible) {
                $weighteditems[$possible->contentid] = $possible->weight;
            }

            $rand = mt_rand(1, (int) array_sum($weighteditems));
            foreach ($weighteditems as $key => $value) {
                $rand -= $value;
                if ($rand <= 0) {
                    $item1 = $key;
                    break;
                }
            }
            unset($weighteditems[$item1]);


            $rand = mt_rand(1, (int) array_sum($weighteditems));
            foreach ($weighteditems as $key => $value) {
                $rand -= $value;
                if ($rand <= 0) {
                    $item2 = $key;
                    break;
                }
            }
            unset($weighteditems[$item2]);

            $rand = mt_rand(1, (int) array_sum($weighteditems));
            foreach ($weighteditems as $key => $value) {
                $rand -= $value;
                if ($rand <= 0) {
                    $item3 = $key;
                    break;
                }
            }
            unset($weighteditems[$item3]);

            $dbitem1 = DB::table('box_contents')->where('contentid', $item1)->first();
            $dbitem2 = DB::table('box_contents')->where('contentid', $item2)->first();
            $dbitem3 = DB::table('box_contents')->where('contentid', $item3)->first();

            $purchaseId = DB::table('purchased_boxes')->insertGetId([
                'boxtype' => $boxtype,
                'userid' => $userid,
                'typeone' => $dbitem1->typeid,
                'itemone' => $dbitem1->itemid,
                'typetwo' => $dbitem2->typeid,
                'itemtwo' => $dbitem2->itemid,
                'typethree' => $dbitem3->typeid,
                'itemthree' => $dbitem3->itemid,
            ]);
            $response = true;
            $message = "Success!";
        } else {
            $message = "Not enough possible items in box";
        }

        $result = array(
            'response' => $response,
            'message' => $message,
            'purchaseId' => $purchaseId
        );

        return $result;
    }

    public static function getStickers()
    {
        $stickers = array();
        $temps = DB::table('stickers')->get();

        foreach ($temps as $temp) {
            $sticker = array(
                'stickerid' => $temp->stickerid,
                'name' => $temp->name
            );

            $stickers[] = $sticker;
        }

        return $stickers;
    }

    public static function getIcons()
    {
        $icons = array();
        $temps = DB::table('name_icons')->get();

        foreach ($temps as $temp) {
            $icon = array(
                'iconid' => $temp->iconid,
                'name' => $temp->name
            );

            $icons[] = $icon;
        }

        return $icons;
    }

    public static function getEffects()
    {
        $effects = array();
        $temps = DB::table('name_effects')->get();

        foreach ($temps as $temp) {
            $effect = array(
                'effectid' => $temp->effectid,
                'name' => $temp->name
            );

            $effects[] = $effect;
        }

        return $effects;
    }

    public static function getSubs()
    {
        $subs = array();
        $temps = DB::table('subscription_packages')->get();

        foreach ($temps as $temp) {
            $sub = array(
                'packageid' => $temp->packageid,
                'name' => $temp->name
            );

            $subs[] = $sub;
        }
        return $subs;
    }

    public static function getThemes()
    {
        $themes = array();
        $temps = DB::table('themes')->where('visible', '1')->get();

        foreach ($temps as $temp) {
            $theme = array(
                'themeid' => $temp->themeid,
                'name' => $temp->name
            );

            $themes[] = $theme;
        }

        return $themes;
    }

    public static function getThemeItems($type, $take = 0, $skip = 0)
    {
        $themes = array();
        if ($type == 'page') {
            $temps = DB::table('themes')->orderBy('name', 'ASC')->where('visible', 1)->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('themes')->orderBy('dateline', 'DESC')->where('visible', 1)->take(12)->get();
        }


        $already_owned = DB::table('theme_users')->where('userid', Auth::user()->userid)->pluck('themeid')->all();


        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $canbuy = UserHelper::memberOfGroup(4) || ($temp->thcb == 0) ? 1 : 0;

            $theme = array(
                    'html' => array(
                        'themeid' => $temp->themeid,
                        'canbuy' => $canbuy,
                        'thcb' => $temp->thcb,
                        'name' => $title,
                        'description' => $temp->description,
                        'price' => $temp->price,
                        'theme' => asset('_assets/img/themes/'.$temp->themeid.'.gif'),
                        'owns' => in_array($temp->themeid, $already_owned)
                    ),
                    'dateline' => $temp->dateline
                );

            $themes[] = view('usercp.shop.items.theme')->with('theme', $theme)->render();
        }

        return $themes;
    }

    public static function getBoxItems($type, $take = 0, $skip = 0)
    {
        $boxes = array();
        if ($type == 'page') {
            $temps = DB::table('boxes')->orderBy('price', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('boxes')->orderBy('dateline', 'DESC')->take(12)->get();
        }



        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $owned_check = DB::table('purchased_boxes')->where('boxtype', $temp->boxid)->where('userid', Auth::user()->userid)->first();
            $owned = count($owned_check) ? 1 : 0;
            $box = array(
                'html' => array(
                    'boxid' => $temp->boxid,
                    'name' => $title,
                    'price' => $temp->price,
                    'owned' => $owned,
                    'duplicate' => $temp->duplicate,
                    'box' => asset('_assets/img/boxes/'.$temp->boxid.'.gif')
                )
            );

            $boxes[] = view('usercp.shop.items.box')->with('box', $box)->render();
        }

        return $boxes;
    }

    public static function getBackgroundItems($type, $take = 0, $skip = 0)
    {
        $backgrounds = array();
        if ($type == 'page') {
            $temps = DB::table('backgrounds')->orderBy('name', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('backgrounds')->orderBy('dateline', 'DESC')->take(12)->get();
        }

        $already_owned = DB::table('background_users')->where('userid', Auth::user()->userid)->pluck('backgroundid')->all();

        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $amount = DB::table('background_users')->where('backgroundid', $temp->backgroundid)->count();
            $canbuy = UserHelper::memberOfGroup(4) || ($temp->thcb == 0) ? 1 : 0;
            $background = array(
                    'html' => array(
                        'backgroundid' => $temp->backgroundid,
                        'name' => $title,
                        'description' => $temp->description,
                        'thcb' => $temp->thcb,
                        'canbuy' => $canbuy,
                        'price' => $temp->price,
                        'background' => asset('_assets/img/backgrounds/'.$temp->backgroundid.'.gif'),
                        'owns' => in_array($temp->backgroundid, $already_owned)
                    ),
                    'dateline' => $temp->dateline
                );

            $backgrounds[] = view('usercp.shop.items.background')->with('background', $background)->render();
        }

        return $backgrounds;
    }


    public static function getIconItems($type, $take = 0, $skip = 0)
    {
        $icons = array();
        if ($type == 'page') {
            $temps = DB::table('name_icons')->orderBy('name', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('name_icons')->orderBy('dateline', 'DESC')->take(12)->get();
        }

        $already_owned = DB::table('name_icon_users')->where('userid', Auth::user()->userid)->pluck('iconid')->all();

        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $canbuy = (UserHelper::memberOfGroup(4) || ($temp->thcb == 0)) ? 1 : 0;
            $amount = DB::table('name_icon_users')->where('iconid', $temp->iconid)->count();
            if ($temp->limit >= 0) {
                $limit_left = $temp->limit - $amount;
                $limit_left = $limit_left < 0 ? 0 : $limit_left;
            } else {
                $limit_left = -1;
            }
            $icon = array(
                    'html' => array(
                        'iconid' => $temp->iconid,
                        'name' => $title,
                        'thcb' => $temp->thcb,
                        'canbuy' => $canbuy,
                        'description' => $temp->description,
                        'price' => $temp->price,
                        'icon' => asset('_assets/img/nameicons/'.$temp->iconid.'.gif'),
                        'owns' => in_array($temp->iconid, $already_owned),
                        'limit_left' => $limit_left
                    ),
                    'dateline' => $temp->dateline
                );

            $icons[] = view('usercp.shop.items.icon')->with('icon', $icon)->render();
        }

        return $icons;
    }

    public static function getStickerItems($type, $take = 0, $skip = 0)
    {
        $stickers = array();
        if ($type == 'page') {
            $temps = DB::table('stickers')->orderBy('name', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('stickers')->orderBy('dateline', 'DESC')->take(12)->get();
        }

        $already_owned = DB::table('sticker_users')->where('userid', Auth::user()->userid)->pluck('stickerid')->all();

        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $canbuy = UserHelper::memberOfGroup(4) || ($temp->thcb == 0) ? 1 : 0;
            $amount = DB::table('sticker_users')->where('stickerid', $temp->stickerid)->count();
            if ($temp->limit >= 0) {
                $limit_left = $temp->limit - $amount;
                $limit_left = $limit_left < 0 ? 0 : $limit_left;
            } else {
                $limit_left = -1;
            }
            $sticker = array(
                    'html' => array(
                        'stickerid' => $temp->stickerid,
                        'name' => $title,
                        'thcb' => $temp->thcb,
                        'canbuy' => $canbuy,
                        'description' => $temp->description,
                        'price' => $temp->price,
                        'sticker' => asset('_assets/img/stickers/'.$temp->stickerid.'.gif'),
                        'owns' => in_array($temp->stickerid, $already_owned),
                        'limit_left' => $limit_left
                    ),
                    'dateline' => $temp->dateline
                );

            $stickers[] = view('usercp.shop.items.sticker')->with('sticker', $sticker)->render();
        }

        return $stickers;
    }

    public static function getEffectItems($type, $take = 0, $skip = 0)
    {
        $effects = array();
        if ($type == 'page') {
            $temps = DB::table('name_effects')->orderBy('name', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('name_effects')->orderBy('dateline', 'DESC')->take(12)->get();
        } elseif ($type == 'all') {
            $temps = DB::table('name_effects')->orderBy('dateline', 'DESC')->get();
        }

        $already_owned = DB::table('name_effect_users')->where('userid', Auth::user()->userid)->pluck('effectid')->all();

        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;
            $canbuy = UserHelper::memberOfGroup(4) || ($temp->thcb == 0) ? 1 : 0;
            $amount = DB::table('name_effect_users')->where('effectid', $temp->effectid)->count();
            if ($temp->limit >= 0) {
                $limit_left = $temp->limit - $amount;
                $limit_left = $limit_left < 0 ? 0 : $limit_left;
            } else {
                $limit_left = -1;
            }
            $effect = array(
                    'html' => array(
                        'effectid' => $temp->effectid,
                        'thcb' => $temp->thcb,
                        'canbuy' => $canbuy,
                        'name' => $title,
                        'description' => $temp->description,
                        'price' => $temp->price,
                        'effect' => asset('_assets/img/nameeffects/'.$temp->effectid.'.gif'),
                        'owns' => in_array($temp->effectid, $already_owned),
                        'limit_left' => $limit_left
                    ),
                    'dateline' => $temp->dateline
                );

            $effects[] = view('usercp.shop.items.effect')->with('effect', $effect)->render();
        }

        return $effects;
    }

    public static function getSubItems($type, $take = 0, $skip = 0)
    {
        $subs = array();
        if ($type == 'page') {
            $temps = DB::table('subscription_packages')->orderBy('name', 'ASC')->take($take)->skip($skip)->get();
        } elseif ($type == 'latest') {
            $temps = DB::table('subscription_packages')->orderBy('dateline', 'DESC')->take(12)->get();
        }

        foreach ($temps as $temp) {
            $title = strlen($temp->name) >= 21 ? substr($temp->name, 0, 21)."..." : $temp->name;

            $sub= array(
                'html' => array(
                    'packageid' => $temp->packageid,
                    'name' => $title,
                    'description' => $temp->description,
                    'dprice' => $temp->dprice,
                    'userbar' => UserHelper::getPackageUserbar($temp->packageid),
                    'usertext' => UserHelper::getPackageUsertext($temp->packageid)
                ),
                'dateline' => $temp->dateline
            );

            $subs[] = view('usercp.shop.items.subscription')->with('sub', $sub)->render();
        }

        return $subs;
    }

    public static function getLatestTransactions()
    {
        $temps = DB::table('shop_transactions')->take(25)->orderBy('transactionid', 'DESC')->where('action', '<', 3)->get();
        $transactions = array();

        foreach ($temps as $temp) {
            $text = "";

            switch ($temp->action) {
                case 1:
                    $text = "Bought";
                break;
                case 2:
                    $text = "Redeemed";
                break;
            }

            switch ($temp->item) {
                case 1:
                    $text = $text . ' a name icon!';
                break;
                case 2:
                    $text = $text . ' a voucher!';
                break;
                case 3:
                    $text = $text . ' a name effect!';
                break;
                case 4:
                    $text = $text . ' a subscription!';
                break;
        case 5:
          $text = $text . ' a sticker!';
        break;
        case 6:
          $text = $text . ' a background!';
        break;
            }

            $transactions[] = array(
                'clean_username' => UserHelper::getUsername($temp->userid, true),
                'text' => $text
            );
        }

        return $transactions;
    }

    public static function getLatestTransfers()
    {
        $temps = DB::table('shop_transactions')->take(25)->orderBy('transactionid', 'DESC')->where('action', 3)->get();
        $transfers = array();

        foreach ($temps as $temp) {
            $transfers[] = array(
                'clean_username1' => UserHelper::getUsername($temp->userid, true),
                'clean_username2' => UserHelper::getUsername($temp->itemid, true),
                'points' => number_format($temp->item)
            );
        }

        return $transfers;
    }

    public static function getPossibleBoxContents($id)
    {
        $box = array();
        $temps = DB::table('box_contents')->where('boxid', $id)->get();
        foreach ($temps as $temp) {
            $row = array();
            $row['contentid'] = $temp->contentid;
            $row['type'] = $temp->typeid;
            switch ($temp->typeid) {
                case 1:
                  $row['title'] = DB::table('themes')->where('themeid', $temp->itemid)->value('name');
                  break;
                case 2:
                  $row['title'] = DB::table('name_icons')->where('iconid', $temp->itemid)->value('name');
                  break;
                case 3:
                  $row['title'] = DB::table('name_effects')->where('effectid', $temp->itemid)->value('name');
                  break;
                case 4:
                  $row['title'] = DB::table('subscription_packages')->where('packageid', $temp->itemid)->value('name');
                  break;
            }
            $row['weight'] = $temp->weight;
            $box[] = $row;
        }

        return $box;
    }
}
