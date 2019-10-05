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
use Session;
use File;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Config;
use URL;

class UserController extends BaseController
{
    private $_api_context;

    public function __construct()
    {
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function gdprApprove()
    {
        if (Auth::check()) {
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'gdpr' => 1
            ]);
            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => false, 'response' => true));
        }
    }

    public function postTradeDiamonds(Request $request)
    {
        $rpoints = $request->input('points');
        $points = $rpoints * 2500;

        if ($rpoints <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can\'t trade a negative number of Diamonds!'));
        }

        if (Auth::user()->diamonds < $rpoints) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You don\'t have enough Diamonds!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits + $points,
            'diamonds' => Auth::user()->diamonds - $rpoints
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postTradeTHC(Request $request)
    {
        $rpoints = $request->input('points');
        $points = $rpoints * 2500;

        if ($points <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can\'t trade a negative number of Diamonds!'));
        }

        if (Auth::user()->credits < $points) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You don\'t have enough credits!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $points,
            'diamonds' => Auth::user()->diamonds + $rpoints
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postGiftPoints(Request $request)
    {
        $username = $request->input('username');
        $points = $request->input('points');

        if ($points <= 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can\'t send a negative number of points!'));
        }

        if (Auth::user()->credits < $points) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You don\'t have enough credits!'));
        }

        if (strtolower($username) == strtolower(Auth::user()->username)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'You can\'t gift youself!'));
        }

        $user = DB::table('users')->whereRaw('lower(username) LIKE ?', [strtolower($username)])->first();

        if (!count($user)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'User don\'t exist!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $points
        ]);

        DB::table('users')->where('userid', $user->userid)->update([
            'credits' => $user->credits + $points
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 3,
            'item' => $points,
            'itemid' => $user->userid,
            'dateline' => time()
        ]);

        DB::table('notifications')->insert([
            'postuserid' => Auth::user()->userid,
            'reciveuserid' => $user->userid,
            'content' => 13,
            'contentid' => $points,
            'dateline' => time(),
            'read_at' => 0
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postPrivateMessage(Request $request)
    {
        $content = $request->input('content');
        $userid = $request->input('userid');

        if (Auth::user()->habbo_verified == 0) {
            return response()->json(array('success' => true, 'response' => false, 'message' => "You must verify your habbo to PM!"));
        }

        if ($content == '') {
            return response()->json(array('success' => true, 'response' => false));
        }

        $pmid = DB::table('private_messages')->insertGetId([
            'recive_userid' => $userid,
            'post_userid' => Auth::user()->userid,
            'content' => $content,
            'dateline' => time(),
            'read_at' => 0
        ]);

        DB::table('notifications')->insert([
            'postuserid' => Auth::user()->userid,
            'reciveuserid' => $userid,
            'content' => 5,
            'contentid' => $pmid,
            'dateline' => time(),
            'read_at' => 0
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getPmIndex()
    {
        $temps = DB::table('private_messages')
        ->where('recive_userid', Auth::user()->userid)
        ->orWhere('post_userid', Auth::user()->userid)
        ->orderBy('dateline', 'DESC')
        ->get();

        $conversations = array();
        $posterAlreadyIn = array();
        foreach ($temps as $temp) {
            if (!in_array($temp->post_userid, $posterAlreadyIn)) {
                $content = ForumHelper::fixContent($temp->content);
                $content = ForumHelper::replaceEmojis($content);

                $conversations[] = array(
                    'userid' => $temp->post_userid,
                    'avatar' => UserHelper::getAvatar($temp->post_userid),
                    'username' => UserHelper::getUsername($temp->post_userid),
                    'content' => $content,
                    'dateline' => ForumHelper::timeAgo($temp->dateline),
                    'read' => $temp->read_at > 0 ? true : false
                );
                $posterAlreadyIn[] = $temp->post_userid;
            }
        }

        $returnHTML = view('usercp.pm.index')
        ->with('conversations', $conversations)
        ->with('verified', Auth::user()->habbo_verified)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postRedeemCode(Request $request)
    {
        $code = $request->input('code');

        $voucher = DB::table('voucher_codes')->where('code', 'LIKE', $code)->where('active', 1)->first();

        if (count($voucher)) {
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'credits' => Auth::user()->credits + $voucher->worth
            ]);
            DB::table('voucher_codes')->where('voucherid', $voucher->voucherid)->update(['active' => 0]);
            DB::table('shop_transactions')->insert([
                'userid' => Auth::user()->userid,
                'action' => 2,
                'item' => 2,
                'itemid' => $voucher->voucherid,
                'dateline' => time()
            ]);
            DB::table('voucher_logs')->insert([
                'userid' => Auth::user()->userid,
                'voucher_code' => $voucher->code,
                'action' => 1,
                'voucher_worth' => $voucher->worth,
                'dateline' => time()
            ]);

            return response()->json(array('success' => true, 'response' => true, 'worth' => $voucher->worth));
        } else {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'That code doesn\'t exist or has already been used!'));
        }
    }

    public function openBox($boxid)
    {
        $response = false;

        $purchase = DB::table('purchased_boxes')->where('boxid', $boxid)->first();
        if ($purchase->opened == 0) {
            if (count($purchase)) {
                if ($purchase->userid == Auth::user()->userid) {
                    $items = array();
                    $item1 = array(
                        'type' => $purchase->typeone,
                        'id' => $purchase->itemone
                    );
                    $items[] = $item1;
                    $item2 = array(
                        'type' => $purchase->typetwo,
                        'id' => $purchase->itemtwo
                    );
                    $items[] = $item2;
                    $item3 = array(
                        'type' => $purchase->typethree,
                        'id' => $purchase->itemthree
                    );
                    $items[] = $item3;

                    $showItems = array();
                    foreach ($items as $item) {
                        switch ($item['type']) {
                            case 1:
                                $dateline = time();
                                DB::table('theme_users')->insert([
                                    'userid' => Auth::user()->userid,
                                    'themeid' => $item['id'],
                                    'dateline' => $dateline
                                ]);
                                /*DB::table('shop_transactions')->insert([
                                    'userid' => Auth::user()->userid,
                                    'action' => 1,
                                    'item' => 6,
                                    'itemid' => $item['id'],
                                    'dateline' => time()
                                ]);*/

                                $showItem = array(
                                    'texttype' => 'Theme',
                                    'type' => 1,
                                    'name' => DB::table('themes')->where('themeid', $item['id'])->value('name'),
                                    'picture' => asset('_assets/img/themes/'.$item['id'].'.gif')
                                );
                                $showItems[] = $showItem;
                                break;
                            case 2:
                                $dateline = time();
                                DB::table('name_icon_users')->insert([
                                    'userid' => Auth::user()->userid,
                                    'iconid' => $item['id'],
                                    'dateline' => $dateline
                                ]);

                                /*DB::table('shop_transactions')->insert([
                                    'userid' => Auth::user()->userid,
                                    'action' => 1,
                                    'item' => 1,
                                    'itemid' => $item['id'],
                                    'dateline' => $dateline
                                ]);*/
                                $showItem = array(
                                    'texttype' => 'Name Icon',
                                    'type' => 2,
                                    'name' => DB::table('name_icons')->where('iconid', $item['id'])->value('name'),
                                    'picture' => asset('_assets/img/nameicons/'.$item['id'].'.gif')
                                );
                                $showItems[] = $showItem;
                                break;
                            case 3:
                                $dateline = time();
                                DB::table('name_effect_users')->insert([
                                    'userid' => Auth::user()->userid,
                                    'effectid' => $item['id'],
                                    'dateline' => $dateline
                                ]);
                                /*DB::table('shop_transactions')->insert([
                                    'userid' => Auth::user()->userid,
                                    'action' => 1,
                                    'item' => 3,
                                    'itemid' => $item['id'],
                                    'dateline' => $dateline
                                ]);*/
                                $showItem = array(
                                    'texttype' => 'Name Effects',
                                    'type' => 3,
                                    'name' => DB::table('name_effects')->where('effectid', $item['id'])->value('name'),
                                    'picture' => asset('_assets/img/nameeffects/'.$item['id'].'.gif')
                                );
                                $showItems[] = $showItem;
                                break;
                            case 4:
                                $alreadyHas = DB::table('subscription_subs')->where('userid', Auth::user()->userid)->where('packageid', $item['id'])->first();

                                if (count($alreadyHas)) {
                                    $currentEnd = $alreadyHas->end_date;
                                    if ($currentEnd > time()) {
                                        $newEnd = $currentEnd + 2419200;
                                    } else {
                                        $newEnd = time() + 2419200;
                                    }
                                    DB::table('subscription_subs')->where('userid', Auth::user()->userid)->where('packageid', $item['id'])->update(['end_date' => $newEnd]);
                                } else {
                                    DB::table('subscription_subs')->insert([
                                        'userid' => Auth::user()->userid,
                                        'packageid' => $item['id'],
                                        'start_date' => time(),
                                        'end_date' => time() + 2419200
                                    ]);
                                }
                                $packageGroupId = DB::table('subscription_packages')->where('packageid', $item['id'])->value('usergroupid');
                                $currentUserGroups = explode(',', Auth::user()->usergroups);

                                if (!in_array($packageGroupId, $currentUserGroups)) {
                                    $currentUserGroups[] = $packageGroupId;
                                }

                                DB::table('users')->where('userid', Auth::user()->userid)->update([
                                    'usergroups' => implode(',', $currentUserGroups),
                                    'displaygroup' => Auth::user()->displaygroup == 0 ? $packageGroupId : Auth::user()->displaygroup
                                ]);

                                /*DB::table('shop_transactions')->insert([
                                    'userid' =>  Auth::user()->userid,
                                    'action' => 1,
                                    'item' => 4,
                                    'itemid' => $item['id'],
                                    'dateline' => time()
                                ]);*/
                                $showItem = array(
                                    'type' => 4,
                                    'texttype' => 'Subscription',
                                    'name' => DB::table('subscription_packages')->where('packageid', $item['id'])->value('name'),
                                    'userbar' => UserHelper::getPackageUserbar($item['id']),
                                    'usertext' => UserHelper::getPackageUsertext($item['id'])
                                );
                                $showItems[] = $showItem;
                                break;
                            case 5:
                                $dateline = time();
                                DB::table('sticker_users')->insert([
                                    'userid' => Auth::user()->userid,
                                    'stickerid' => $item['id'],
                                    'dateline' => $dateline
                                ]);

                                /*DB::table('shop_transactions')->insert([
                                    'userid' => Auth::user()->userid,
                                    'action' => 1,
                                    'item' => 1,
                                    'itemid' => $item['id'],
                                    'dateline' => $dateline
                                ]);*/
                                $showItem = array(
                                    'texttype' => 'Sticker',
                                    'type' => 5,
                                    'name' => DB::table('stickers')->where('stickerid', $item['id'])->value('name'),
                                    'picture' => asset('_assets/img/stickers/'.$item['id'].'.gif')
                                );
                                $showItems[] = $showItem;
                            }
                    }


                    DB::table('purchased_boxes')->where('boxid', $boxid)->update([
                                                'opened' => 1
                                            ]);



                    $returnHTML = view('usercp.shop.openbox')
                        ->with('items', $showItems)
                        ->render();
                    return response()->json(array('success' => true, 'returnHTML' => $returnHTML, 'showItems' => $showItems, 'response' => true ));
                }
            }
        }
    }

    public function postBuyBox(Request $request)
    {
        $boxtype = $request->input('boxid');
        $box = DB::table('boxes')->where('boxid', $boxtype)->first();
        if (Auth::user()->credits >= $box->price) {
            $already_owned = DB::table('purchased_boxes')->where('boxtype', $boxtype)->where('userid', Auth::user()->userid)->first();
            if ($box->duplicate || !count($already_owned)) {
                $result = ShopHelper::packBox(Auth::user()->userid, $boxtype);
                DB::table('users')->where('userid', Auth::user()->userid)->update([
                  'credits' => Auth::user()->credits - $box->price
                ]);
            } else {
                $result['response'] = false;
                $result['purchaseId'] = 0;
                $result['message'] = "You already have this!";
            }
        } else {
            $result['response'] = false;
            $result['purchaseId'] = 0;
            $result['message'] = "Not enough credits!";
        }

        return response()->json(array('success' => true, 'response' => $result['response'], 'message' => $result['message'], 'purchaseId' => $result['purchaseId']));
    }

    public function postBuySub(Request $request)
    {
        $packageid = $request->input('packageid');

        $package = DB::table('subscription_packages')->where('packageid', $packageid)->first();
        $userid = Auth::user()->userid;
        $length = $package->days * (60*60*24);

        if (!count($package)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Subscription doesn\'t exist anymore, sorry!'));
        }

        if (Auth::user()->diamonds < $package->dprice) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this subscription!'));
        }

        $alreadyHas = DB::table('subscription_subs')->where('userid', $userid)->where('packageid', $packageid)->first();

        if (count($alreadyHas)) {
            $currentEnd = $alreadyHas->end_date;
            if ($currentEnd > time()) {
                $newEnd = $currentEnd + $length;
            } else {
                $newEnd = time() + $length;
            }
            DB::table('subscription_subs')->where('userid', $userid)->where('packageid', $packageid)->update(['end_date' => $newEnd]);
        } else {
            DB::table('subscription_subs')->insert([
                'userid' => $userid,
                'packageid' => $packageid,
                'start_date' => time(),
                'end_date' => time() + 2419200
            ]);
        }

        $packageGroupId = $package->usergroupid;
        $currentUserGroups = explode(',', Auth::user()->usergroups);

        if (!in_array($packageGroupId, $currentUserGroups)) {
            $currentUserGroups[] = $packageGroupId;
        }

        DB::table('users')->where('userid', $userid)->update([
            'usergroups' => implode(',', $currentUserGroups),
            'displaygroup' => Auth::user()->displaygroup == 0 ? $packageGroupId : Auth::user()->displaygroup,
            'diamonds' => Auth::user()->diamonds - $package->dprice
        ]);

        DB::table('shop_transactions')->insert([
            'userid' =>  $userid,
            'action' => 1,
            'item' => 4,
            'itemid' => $packageid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postBuyTheme(Request $request)
    {
        $themeid = $request->input('themeid');

        $theme = DB::table('themes')->where('themeid', $themeid)->first();

        if ($theme->thcb == 1 && !UserHelper::memberOfGroup(4)) {
            return  response()->json(array('success' => true, 'response' => false, 'message' => 'This is a THClub exclusive!'));
        }

        if (!count($theme)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Theme doesn\'t exist anymore!'));
        }

        $amount = DB::table('theme_users')->where('themeid', $themeid)->count();

        if (Auth::user()->credits < $theme->price) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this theme!'));
        }

        $alreadygottheme = DB::table('theme_users')->where('userid', Auth::user()->userid)->where('themeid', $themeid)->count();

        if ($alreadygottheme > 0) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You already own this theme!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $theme->price
        ]);

        DB::table('theme_users')->insert([
            'userid' => Auth::user()->userid,
            'themeid' => $themeid,
            'dateline' => time()
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'item' => 6,
            'itemid' => $themeid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postBuyEffect(Request $request)
    {
        $effectid = $request->input('effectid');

        $effect = DB::table('name_effects')->where('effectid', $effectid)->first();

        if ($effect->thcb == 1 && !UserHelper::memberOfGroup(4)) {
            return  response()->json(array('success' => true, 'response' => false, 'message' => 'This is a THClub exclusive!'));
        }

        if (!count($effect)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Effect doesn\'t exist anymore!'));
        }

        $amount = DB::table('name_effect_users')->where('effectid', $effectid)->count();

        if ($effect->limit >= 0 && $amount > $effect->limit) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'The amount for this effect is already sold!'));
        }

        if (Auth::user()->credits < $effect->price) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this effect!'));
        }

        $alreadygoteffect = DB::table('name_effect_users')->where('userid', Auth::user()->userid)->where('effectid', $effectid)->count();

        if ($alreadygoteffect > 0) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You already own this effect!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $effect->price
        ]);

        DB::table('name_effect_users')->insert([
            'userid' => Auth::user()->userid,
            'effectid' => $effectid,
            'dateline' => time()
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'item' => 3,
            'itemid' => $effectid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postBuyIcon(Request $request)
    {
        $iconid = $request->input('iconid');

        $icon = DB::table('name_icons')->where('iconid', $iconid)->first();

        if ($icon->thcb == 1 && !UserHelper::memberOfGroup(4)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'This is a THClub exclusive!'));
        }

        if (!count($icon)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Icon doesn\'t exist anymore!'));
        }

        $amount = DB::table('name_icon_users')->where('iconid', $iconid)->count();

        if ($icon->limit >= 0 and $amount >= $icon->limit) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'The amount for this icon is already sold!'));
        }

        if (Auth::user()->credits < $icon->price) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this icon!'));
        }

        $alreadygoticon = DB::table('name_icon_users')->where('userid', Auth::user()->userid)->where('iconid', $iconid)->count();

        if ($alreadygoticon > 0) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You already own this icon!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $icon->price
        ]);

        DB::table('name_icon_users')->insert([
            'userid' => Auth::user()->userid,
            'iconid' => $iconid,
            'dateline' => time()
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'item' => 1,
            'itemid' => $iconid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postBuyBackground(Request $request)
    {
        $backgroundid = $request->input('backgroundid');

        $background = DB::table('backgrounds')->where('backgroundid', $backgroundid)->first();


        if (!count($background)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Background doesn\'t exist anymore!'));
        }

        if ($background->thcb == 1 && !UserHelper::memberOfGroup(4)) {
            return  response()->json(array('success' => true, 'response' => false, 'message' => 'This is a THClub exclusive!'));
        }

        $amount = DB::table('background_users')->where('backgroundid', $backgroundid)->count();

        if (Auth::user()->credits < $background->price) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this background!'));
        }

        $alreadygotbackground = DB::table('background_users')->where('userid', Auth::user()->userid)->where('backgroundid', $backgroundid)->count();

        if ($alreadygotbackground > 0) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You already own this background!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $background->price
        ]);

        DB::table('background_users')->insert([
            'userid' => Auth::user()->userid,
            'backgroundid' => $backgroundid,
            'dateline' => time()
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'item' => 6,
            'itemid' => $backgroundid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postBuySticker(Request $request)
    {
        $stickerid = $request->input('stickerid');

        $sticker = DB::table('stickers')->where('stickerid', $stickerid)->first();

        if ($sticker->thcb == 1 && !UserHelper::memberOfGroup(4)) {
            return response()->json(array('success' => true, 'response' => false, 'message' => 'This is a THClub exclusive!'));
        }

        if (!count($sticker)) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'Sticker doesn\'t exist anymore!'));
        }

        $amount = DB::table('sticker_users')->where('stickerid', $stickerid)->count();

        if ($sticker->limit >= 0 && $amount > $sticker->limit) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'The amount for this sticker is already sold!'));
        }

        if (Auth::user()->credits < $sticker->price) {
            return response()->json(array('succes' => true, 'response' => false, 'message' => 'You can\'t afford this sticker!'));
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'credits' => Auth::user()->credits - $sticker->price
        ]);

        DB::table('sticker_users')->insert([
            'userid' => Auth::user()->userid,
            'stickerid' => $stickerid,
            'dateline' => time()
        ]);

        DB::table('shop_transactions')->insert([
            'userid' => Auth::user()->userid,
            'action' => 1,
            'item' => 5,
            'itemid' => $stickerid,
            'dateline' => time()
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }


    public function getShopThemes($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 8;
        $skip = 0;

        $pagesx = DB::table('themes')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/themes/page/')->render();

        $themes = ShopHelper::getThemeItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.themes')
        ->with('themes', $themes)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopBoxes($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 8;
        $skip = 0;

        $pagesx = DB::table('boxes')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/boxes/page/')->render();

        $boxes = ShopHelper::getBoxItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.boxes')
        ->with('boxes', $boxes)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopSubs($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 8;
        $skip = 0;

        $pagesx = DB::table('subscription_packages')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/subs/page/')->render();

        $subs = ShopHelper::getSubItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.subscriptions')
        ->with('subs', $subs)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopEffects($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 16;
        $skip = 0;

        $pagesx = DB::table('name_effects')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/effects/page/')->render();

        $effects = ShopHelper::getEffectItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.effects')
        ->with('effects', $effects)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopStickers($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 16;
        $skip = 0;

        $pagesx = DB::table('stickers')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/stickers/page/')->render();

        $stickers = ShopHelper::getStickerItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.stickers')
        ->with('stickers', $stickers)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopIcons($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 16;
        $skip = 0;

        $pagesx = DB::table('name_icons')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/icons/page/')->render();

        $icons = ShopHelper::getIconItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.icons')
        ->with('icons', $icons)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getShopBackgrounds($pagenr)
    {
        if ($pagenr <= 0) {
            $pagenr = 1;
        }

        $take = 16;
        $skip = 0;

        $pagesx = DB::table('backgrounds')->count();

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/shop/backgrounds/page/')->render();

        $backgrounds = ShopHelper::getBackgroundItems('page', $take, $skip);

        $returnHTML = view('usercp.shop.backgrounds')
        ->with('backgrounds', $backgrounds)
        ->with('pagi', $pagi)
        ->with('pagenr', $pagenr)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postSaveIcon(Request $request)
    {
        $iconid = $request->input('icon');
        $icon_side = $request->input('icon_side');

        if ($icon_side != 0 and $icon_side != 1) {
            $icon_side = 0;
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['name_icon_side' => $icon_side]);

        $haveIcon = DB::table('name_icon_users')->where('userid', Auth::user()->userid)->where('iconid', $iconid)->count();

        if ($iconid == 0 or $haveIcon > 0) {
            DB::table('users')->where('userid', Auth::user()->userid)->update(['name_icon' => $iconid]);
            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false));
        }
    }

    public function postSaveEffect(Request $request)
    {
        $effectid = $request->input('effect');
        $haveEffect = DB::table('name_effect_users')->where('userid', Auth::user()->userid)->where('effectid', $effectid)->count();

        if ($effectid == 0 or $haveEffect > 0) {
            DB::table('users')->where('userid', Auth::user()->userid)->update(['name_effect' => $effectid]);
            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false));
        }
    }

    public function postSaveBackground(Request $request)
    {
        $backgroundid = $request->input('background');
        $haveBackground = DB::table('background_users')->where('userid', Auth::user()->userid)->where('backgroundid', $backgroundid)->count();

        if ($backgroundid == 0 or $haveBackground > 0) {
            DB::table('users')->where('userid', Auth::user()->userid)->update(['background' => $backgroundid]);
            return response()->json(array('success' => true, 'response' => true));
        } else {
            return response()->json(array('success' => true, 'response' => false));
        }
    }

    public function getShop()
    {
        $latestItems = array();

        $latestItems = ShopHelper::getSubItems('latest');
        $latestItems = array_merge($latestItems, ShopHelper::getEffectItems('latest'));
        $latestItems = array_merge($latestItems, ShopHelper::getIconItems('latest'));

        shuffle($latestItems);

        $returnHTML = view('usercp.shop.index')->with('latestItems', $latestItems)->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function cmp($a, $b)
    {
        if ($a['dateline'] == $b['dateline']) {
            return 0;
        }
        return ($a['dateline'] < $b['dateline']) ? -1 : 1;
    }

    public function postSaveBadges(Request $request)
    {
        DB::table('users_badges')->where('userid', Auth::user()->userid)->update(['selected' => 0]);

        $badges = $request->input('badges');
        $saved = 0;

        foreach ($badges as $badge) {
            DB::table('users_badges')->where('userid', Auth::user()->userid)->where('badgeid', $badge)->update(['selected' => 1]);
            $saved++;

            if ($saved == 8) {
                break;
            }
        }

        return response()->json(array('success' => true));
    }

    public function postSaveExtras(Request $request)
    {
        $extras = $request->input('extras');

        DB::table('users')->where('userid', Auth::user()->userid)->update(['extras' => $extras]);
        return response()->json(array('success' => true, 'response' => 'true'));
    }

    public function saveTwitter(Request $request)
    {
        DB::table('users')->where('userid', Auth::user()->userid)->update(['twitter' => $request->input('twitter')]);

        return response()->json(array('success' => true));
    }

    public function saveTheme(Request $request)
    {
        $theme = $request->input('theme');
        if(UserHelper::haveAdminPerm(Auth::user()->userid, 4194304)){
            DB::table('users')->where('userid', Auth::user()->userid)->update(['theme' => $request->input('theme')]);
            return response()->json(array('success' => true));
        }else{
            if (DB::table('theme_users')->where('themeid', $theme)->where('userid', Auth::user()->userid)->first() || $theme == 0) {
                DB::table('users')->where('userid', Auth::user()->userid)->update(['theme' => $request->input('theme')]);
                return response()->json(array('success' => true));
            }
        }
        return respone()->json(array('success' => false));
    }

    public function saveHomepage(Request $request)
    {
        DB::table('users')->where('userid', Auth::user()->userid)->update(['homePage' => $request->input('homepage')]);
        return response()->json(array('success' => true));
    }

    public function saveSnow(Request $request)
    {
        $snow = $request->input('snow');
        if ($snow>300) {
            $snow = 300;
        }
        if ($snow < 0) {
            $snow = 0;
        }
        DB::table('users')->where('userid', Auth::user()->userid)->update(['snow' => $snow]);
        return response()->json(array('success' => true));
    }

    public function automaticSubscribe($type)
    {
        if ($type === "on") {
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'auto_subscribe' => 1
            ]);
        } elseif ($type === "off") {
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'auto_subscribe' => 0
            ]);
        }

        return response()->json(array('success' => true));
    }

    public function getPaymentStatus(Request $request)
    {
        // Get the payment ID before session clear
        $paymentInfo = DB::table('paymentlog')->where('userid', Auth::user()->userid)->orderBy('dateline', 'DESC')->first();

        $payment_id = $paymentInfo->paymentid;
        $amount = $paymentInfo->amount;

        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            return view('extras.fail');
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);
        //echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later
        if ($result->getState() == 'approved') { // payment made
            //Payment approved, let's add up the time for the user
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'diamonds' => DB::raw('diamonds+'.$amount)
            ]);

            return view('extras.success');
        }

        return view('extras.fail');
    }

    public function getCreditsPaypalUrl($amountCredits)
    {
        if ($amountCredits <= 0) {
            return response()->json(array('success' => true, 'response' => false));
        }

        $name = "Thishabbo Credits";
        $price = $amountCredits * 2;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName("Thishabbo Diamonds: $amountCredits Diamonds") // item name
            ->setCurrency('GBP')
            ->setQuantity(1)
            ->setPrice($price); // unit price

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('GBP')
            ->setTotal($price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Thishabbo Donator Transaction');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('getPaymentStatus'))
            ->setCancelUrl(URL::route('getPaymentStatus'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        if (isset($redirect_url)) {
            DB::table('paymentlog')->insert([
                'paymentid' => $payment->getId(),
                'userid' => Auth::user()->userid,
                'amount' => $amountCredits,
                'dateline' => time()
            ]);
            return response()->json(array('success' => true, 'response' => true, 'url' => $redirect_url));
        } else {
            return response()->json(array('success' => true, 'response' => false));
        }

        if (isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }
        return Redirect::route('original.route')
            ->with('error', 'Unknown error occurred');
    }

    public function getCredits($status = 0)
    {
        $returnHTML = view('usercp.subscriptionsList')
        ->with('status', $status)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function togglePreview(Request $request)
    {
        $content = $request->input('content');
        $content = ForumHelper::bbcodeParser($content);

        return response()->json(['success' => true, 'message' => $content]);
    }

    public function toggleCategory(Request $request)
    {
        $forumid = $request->input('forumid');
        $userid = Auth::user()->userid;
        if (count($forumid)) {
            $collapsed_forums = explode(',', Auth::user()->collapsed_forums);
            $toggled = array_search($forumid, $collapsed_forums);
            if ($toggled != false) {
                unset($collapsed_forums[$toggled]);
                $message = "Forum expanded!";
            } else {
                $collapsed_forums[] = $forumid;
                $collapsed_forums = array_merge($collapsed_forums);
                $message = "Forum collapsed!";
            }

            DB::table('users')->where('userid', $userid)->update([
                'collapsed_forums' => implode(',', $collapsed_forums)
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success'=> false, 'message' => 'Forum not found']);
        }
    }

    public function toggleIgnore(Request $request)
    {
        $forumid = $request->input('forumid');
        $userid = Auth::user()->userid;
        $forum = DB::table('forums')->where('forumid', $forumid)->first();
        $children_ids = array();
        $children = DB::table('forums')->where('parentid', $forumid)->get();
        foreach ($children as $child) {
            $children_ids[] = $child->forumid;
        }
        if (count($forum)) {
            $ignored_forums = explode(',', Auth::user()->ignored_forums);
            $ignored = array_search($forumid, $ignored_forums);
            if ($ignored != false) {
                unset($ignored_forums[$ignored]);
                $ignored_forums = array_diff($ignored_forums, $children_ids);
                $message = "Forum unignored!";
            } else {
                $ignored_forums[] = $forumid;
                $ignored_forums = array_merge($ignored_forums, $children_ids);
                $message = "Forum ignored!";
            }

            DB::table('users')->where('userid', $userid)->update([
                'ignored_forums' => implode(',', $ignored_forums)
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success'=> false, 'message' => 'Forum not found']);
        }
    }

    public function subscribeThread(Request $request)
    {
        $threadid = $request->input('threadid');

        $thread = DB::table('threads')->where('threadid', $threadid)->first();
        $check = DB::table('subscription_threads')->where('userid', Auth::user()->userid)->where('threadid', $threadid)->count();

        if ($check == 0 and count($thread)) {
            if (UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                DB::table('subscription_threads')->insert([
                    'userid' => Auth::user()->userid,
                    'threadid' => $threadid
                ]);
            }
        }

        return response()->json(array('success' => true));
    }

    public function unsubscribeAllThreads()
    {
        $subbedThreads = DB::table('subscription_threads')->where('userid', Auth::user()->userid)->get();

        foreach ($subbedThreads as $sub){
            DB::table('subscription_threads')->where('userid', Auth::user()->userid)->where('threadid', $sub->threadid)->delete();
        }

        return response()->json(array('success' => true));
    }

    public function unsubscribeThread(Request $request)
    {
        $threadid = $request->input('threadid');

        DB::table('subscription_threads')->where('userid', Auth::user()->userid)->where('threadid', $threadid)->delete();

        return response()->json(array('success' => true));
    }

    public function customRainbowBar(Request $request)
    {
        $groupid = $request->input('groupid');
        $new_colors = $request->input('colors');


        $userbardatas = explode('%', Auth::user()->userbardata);

        $new_colors = trim($new_colors);

        $new_userbardata = "";
        $first_top = true;
        $found = false;
        $empty = true;

        if (count($userbardatas) > 0) {
            foreach ($userbardatas as $userbardata) {
                if (substr($userbardata, 0, 1) == $groupid) {
                    $colors = "";

                    $new_colors = str_replace("%", "", $new_colors);
                    $new_colors = str_replace("]", "", $new_colors);
                    $new_colors = str_replace("[", "", $new_colors);
                    $new_colors = str_replace("/", "", $new_colors);
                    $new_colors = str_replace("<", "", $new_colors);
                    $new_colors = str_replace(">", "", $new_colors);
                    $first = true;

                    $new_colors = explode(',', $new_colors);

                    foreach ($new_colors as $new_color) {
                        if ($first) {
                            $colors = $new_color;
                            $first = false;
                        } else {
                            $colors = $colors . ',' . $new_color;
                        }
                    }

                    $ubd = $groupid . '&[option:3/color:' . $colors . ']';

                    $found = true;
                } else {
                    $ubd = $userbardata;
                }

                $ubd = str_replace("%", "", $ubd);

                if ($first_top) {
                    $new_userbardata = $ubd;
                    $first_top = false;
                } else {
                    $new_userbardata = $new_userbardata . '%' . $ubd;
                }
            }
            $empty = false;
        }

        if ($empty or !$found) {
            $colors = "";

            $new_colors = str_replace("%", "", $new_colors);
            $new_colors = str_replace("]", "", $new_colors);
            $new_colors = str_replace("[", "", $new_colors);
            $new_colors = str_replace("/", "", $new_colors);
            $first = true;

            $new_colors = explode(',', $new_colors);

            foreach ($new_colors as $new_color) {
                if ($first) {
                    $colors = $new_color;
                    $first = false;
                } else {
                    $colors = $colors . ',' . $new_color;
                }
            }

            if ($first_top) {
                $new_userbardata = $groupid . '&[option:3/color:' . $colors . ']';
            } else {
                $new_userbardata = $new_userbardata . '%' . $groupid . '&[option:3/color:' . $colors . ']';
            }
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['userbardata' => $new_userbardata]);

        return response()->json(array('success' => true));
    }

    public function rainbowUserbar(Request $request)
    {
        $groupid = $request->input('groupid');

        $new_userbardata = "";
        $first = true;
        $found = false;

        if (Auth::user()->userbardata !== "") {
            if (strpos(Auth::user()->userbardata, '%') !== false) {
                $userbardatas = explode('%', Auth::user()->userbardata);
            } else {
                $userbardatas = array(Auth::user()->userbardata);
            }
            foreach ($userbardatas as $userbardata) {
                if (substr($userbardata, 0, 1) == $groupid) {
                    $ubd = $groupid . '&[option:2/color:]';
                    $found = true;
                } else {
                    $ubd = $userbardata;
                }

                $ubd = str_replace("%", "", $ubd);

                if ($first) {
                    $new_userbardata = $ubd;
                    $first = false;
                } else {
                    $new_userbardata = $new_userbardata . '%' . $ubd;
                }
            }
        } else {
            $new_userbardata = $groupid . '&[option:2/color:]';
            $found = true;
        }

        if (!$found) {
            $ubd = $groupid . '&[option:2/color:]';
            if ($new_userbardata !== "") {
                $new_userbardata = $new_userbardata . '%' . $ubd;
            } else {
                $new_userbardata = $ubd;
            }
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['userbardata' => $new_userbardata]);

        return response()->json(array('success' => true, 'ubd' => $ubd));
    }

    public function oneUserbar(Request $request)
    {
        $color = $request->input('color');

        $color = str_replace("%", "", $color);
        $color = str_replace("]", "", $color);
        $color = str_replace("[", "", $color);
        $color = str_replace("/", "", $color);
        $color = str_replace("<", "", $color);
        $color = str_replace(">", "", $color);

        $groupid = $request->input('groupid');

        $new_userbardata = "";
        $first = true;
        $found = false;

        if (Auth::user()->userbardata !== "") {
            if (strpos(Auth::user()->userbardata, '%') !== false) {
                $userbardatas = explode('%', Auth::user()->userbardata);
            } else {
                $userbardatas = array(Auth::user()->userbardata);
            }
            foreach ($userbardatas as $userbardata) {
                if (substr($userbardata, 0, 1) == $groupid) {
                    $ubd = $groupid . '&[option:1/color:' . $color . ']';
                    $found = true;
                } else {
                    $ubd = $userbardata;
                }

                $ubd = str_replace("%", "", $ubd);

                if ($first) {
                    $new_userbardata = $ubd;
                    $first = false;
                } else {
                    $new_userbardata = $new_userbardata . '%' . $ubd;
                }
            }
        } else {
            $new_userbardata = $groupid . '&[option:1/color:' . $color . ']';
            $found = true;
        }

        if (!$found) {
            $ubd = $groupid . '&[option:1/color:' . $color . ']';
            if ($new_userbardata !== "") {
                $new_userbardata = $new_userbardata . '%' . $ubd;
            } else {
                $new_userbardata = $ubd;
            }
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['userbardata' => $new_userbardata]);

        return response()->json(array('success' => true));
    }

    public function setDefaultUserbar(Request $request)
    {
        $groupid = $request->input('groupid');

        $userbardatas = explode('%', Auth::user()->userbardata);

        $new_userbardata = "";
        $first = true;

        if (count($userbardatas) > 0) {
            foreach ($userbardatas as $userbardata) {
                if (substr($userbardata, 0, 1) == $groupid) {
                    $ubd = $groupid . '&[option:0/color:]';
                } else {
                    $ubd = $userbardata;
                }

                if ($first) {
                    $new_userbardata = $ubd;
                    $first = false;
                } else {
                    $new_userbardata = $new_userbardata . '%' . $ubd;
                }
            }
        } else {
            $new_userbardata = $groupid . '&[option:0/color:]';
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['userbardata' => $new_userbardata]);

        return response()->json(array('success' => true));
    }

    public function setDefaultUsername()
    {
        DB::table('users')->where('userid', Auth::user()->userid)->update(['username_option' => 0, 'username_color' => ""]);

        return response()->json(array('success' => true));
    }

    public function setoneColorUsername(Request $request)
    {
        $color = $request->input('color');
        $color = str_replace("%", "", $color);
        $color = str_replace("]", "", $color);
        $color = str_replace("[", "", $color);
        $color = str_replace("/", "", $color);
        $color = str_replace("<", "", $color);
        $color = str_replace(">", "", $color);

        if (strpos($color, '#') === false) {
            $color = '#' . $color;
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['username_option' => 1, 'username_color' => $color]);

        return response()->json(array('success' => true));
    }

    public function rainbowUsername()
    {
        DB::table('users')->where('userid', Auth::user()->userid)->update(['username_option' => 2, 'username_color' => ""]);

        return response()->json(array('success' => true));
    }

    public function customRainbowUsername(Request $request)
    {
        $colors = trim($request->input('colors'));
        $colors = str_replace("<", "", $colors);
        $colors = str_replace(">", "", $colors);

        if (strpos($colors, 'http') !== false) {
            DB::table('users')->where('userid', Auth::user()->userid)->update(['username_option' => 3, 'username_color' => $colors]);
            return response()->json(array('success' => true));
        }
        $colors = str_replace("%", "", $colors);
        $colors = str_replace("]", "", $colors);
        $colors = str_replace("[", "", $colors);
        $colors = str_replace("/", "", $colors);
        $colors = str_replace("(", "", $colors);
        $colors = str_replace(")", "", $colors);
        $colors = str_replace(".", "", $colors);

        $colors = explode(',', $colors);


        $new_colors = "";
        $first = true;

        foreach ($colors as $color) {
            if (strpos($color, '#') === false) {
                $color = '#' . $color;
            }

            if ($first) {
                $new_colors = $color;
                $first = false;
            } else {
                $new_colors = $new_colors . ',' . $color;
            }
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update(['username_option' => 3, 'username_color' => $new_colors]);

        return response()->json(array('success' => true));
    }

    public function getSpecificUserbar($groupid)
    {
        $userbar_one_color = false;
        $userbar_rainbow = false;
        $userbar_custom_rainbow = false;

        if (UserHelper::haveSubFeature(Auth::user()->userid, 16, $groupid)) {
            $userbar_one_color = true;
        }

        if (UserHelper::haveSubFeature(Auth::user()->userid, 32, $groupid)) {
            $userbar_rainbow = true;
        }

        if (UserHelper::haveSubFeature(Auth::user()->userid, 64, $groupid)) {
            $userbar_custom_rainbow = true;
        }

        $userbardatas = explode('%', Auth::user()->userbardata);
        $userbar_option = 0;
        $userbar_color = "";
        $found = false;

        foreach ($userbardatas as $userbardata) {
            if (substr($userbardata, 0, 1) == $groupid) {
                $data = substr($userbardata, 2);
                $data = explode('/', $data);
                $data[1] = str_replace("]", "", $data[1]);

                $data_option = explode(':', $data[0]);
                $userbar_option = $data_option[1];

                $data_color = explode(':', $data[1]);
                $userbar_color = $data_color[1];
            }
        }

        $x_grps = explode(',', Auth::user()->usergroups);

        if (in_array($groupid, $x_grps)) {
            $found = true;
        }

        $userbar = UserHelper::getUserbar(Auth::user()->userid, $groupid);

        $returnHTML = view('usercp.profileSettings.extras.editSpecificUserbar')
        ->with('userbar_one_color', $userbar_one_color)
        ->with('userbar_rainbow', $userbar_rainbow)
        ->with('userbar_custom_rainbow', $userbar_custom_rainbow)
        ->with('userbar_option', $userbar_option)
        ->with('userbar_color', $userbar_color)
        ->with('found', $found)
        ->with('userbar', $userbar)
        ->with('groupid', $groupid)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

     public function postEditHeader(Request $request)
    {
        $custom = $request->input('custom');
        $response = false;
        if ($custom == "CUSTOM") {
            //Uploaded Image
            if ($request->hasFile('header') and $request->file('header')->isValid()) {
                $img = Image::make($request->file('header'));

                $img->save('_assets/img/headers/' . Auth::user()->userid . '.gif', 60);
                DB::table('users')->where('userid', Auth::user()->userid)->update(['profile_header' => 0]);

                $response = true;
            }
        } else {
            //Static Image
            $img = $request->input('img');

            DB::table('users')->where('userid', Auth::user()->userid)->update(['profile_header' => $img]);
            $response = true;
        }

        return response()->json(array('success' => true, 'response' => $response));
    }

    public function getEditHeader()
    {
        if (Auth::user()->profile_header > 0) {
            //user have static header
            $header = asset('_assets/img/website/headers/' . Auth::user()->profile_header . '.png');
        } else {
            //user have custom header
            if (File::exists('_assets/img/headers/' . Auth::user()->userid . '.gif')) {
                $header = asset('_assets/img/headers/' . Auth::user()->userid . '.gif?' . time());
            } else {
                $header = asset('_assets/img/website/headers/6.png');
            }
        }

        $returnHTML = view('usercp.editHeader')
        ->with('header', $header)
        ->with('avatar', UserHelper::getAvatar(Auth::user()->userid))
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function verifyHabbo(Request $request)
    {
        $habbo = $request->input('habbo');
        $message = "";
        $response = false;
        $run = true;
        $check = DB::table('users')->where('userid', '!=', Auth::user()->userid)->where('habbo', 'LIKE', $habbo)->where('habbo_verified', 1)->count();

        $habbo = ForumHelper::fixContent($habbo);
        if ($check == 0) {
            if (Session::has('lastVerify')) {
                $ts = time();

                if (Session::get('lastVerify')+600 > $ts) {
                    $message = "You need to wait 1 hour between each verify!";
                    $run = false;
                }
            }

            if ($run) {

                $url = "http://www.habbo.com/api/public/users?name=" . $habbo;
		$ch = curl_init();
		$cookies = 'browser_token=thisisabrowsertoken;session.id=thisisasessionid;';

                curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Encoding: gzip, deflate, sdch', 'Cookie: ' . $cookies]);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $habbo_stats = json_decode(curl_exec($ch));
                curl_close($ch);

                if (strtolower($habbo_stats->motto) == strtolower("TH-user".Auth::user()->userid)) {
                    if (strtotime(substr($habbo_stats->memberSince, 0, 10)) <= time()-(30*24*60*60)) {
                        DB::table('users')->where('userid', Auth::user()->userid)->update([
                          'habbo' => $habbo,
                          'habbo_verified' => 1
                      ]);

                        $response = true;
                        Session::put('lastVerify', time());
                    } else {
                        $message = "Your habbo must be at least 1 month old! Please contact us if you want to verify.";
                    }
                } else {
                    $message = "Currently the motto is incorrect!";
                }
            }
        } else {
            $message = "That Habbo name has already been verified with another user!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function saveDisplayGroup(Request $request)
    {
        $displaygroup = $request->input('displaygroup');

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'displaygroup' => $displaygroup
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditBio(Request $request)
    {
        $content = $request->input('content');
        $content = ForumHelper::fixContent($content);

        if (strlen($content) > 250) {
            $content = substr($content, 0, 250);
        }

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'bio' => $content
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function clearStickers(Request $request)
    {
        $response = false;

        $stickers = DB::table('sticker_users')->where('visible', 1)->where('userid', Auth::user()->userid)->get();
        if (count($stickers)) {
            foreach ($stickers as $sticker) {
                DB::table('sticker_users')->where('transactionid', $sticker->transactionid)->update([
                    'top' => 0,
                    'left' => 0,
                    'visible' => 0
                ]);
            }
            $response = true;
            $message = "Success";
        } else {
            $message = "No visible stickers!";
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function hideSticker(Request $request)
    {
        $transactionid = $request->input('transactionid');
        $response = false;

        $sticker = DB::table('sticker_users')->where('transactionid', $transactionid)->where('visible', 1)->first();
        if (count($sticker)) {
            if ($sticker->userid == Auth::user()->userid) {
                DB::table('sticker_users')->where('transactionid', $transactionid)->update([
                    'top' => 0,
                    'left' => 0,
                    'visible' => 0
                ]);
                $message = "Success";
                $response = true;
            } else {
                $message = "Not your sticker!";
            }
        } else {
            $message = "No sticker!";
        }


        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function saveSticker(Request $request)
    {
        $transactionid = $request->input('transactionid');
        $top = $request->input('top');
        $left = $request->input('left');
        $response = false;

        $sticker = DB::table('sticker_users')->where('transactionid', $transactionid)->first();
        if (count($sticker)) {
            if ($sticker->userid == Auth::user()->userid) {
                DB::table('sticker_users')->where('transactionid', $transactionid)->update([
                    'top' => $top,
                    'left' => $left,
                    'visible' => 1
                ]);
                $message = "Success";
                $response = true;
            } else {
                $message = "You don't own this sticker!";
            }
        } else {
            $message = "Sticker doesn't exist!";
        }


        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }

    public function postEditPass(Request $request)
    {
        $pass = $request->input('pass');
        $repass = $request->input('repass');

        if ($pass != "" and $repass != "") {
            if (strlen($pass) >= 8) {
                if ($pass == $repass) {
                    DB::table('users')->where('userid', Auth::user()->userid)->update([
                        'password' => Hash::make($pass)
                    ]);
                } else {
                    return response()->json(array('success' => true, 'response' => false, 'message' => 'Password\'s don\'t match!', 'field' => 'edit-form-pass'));
                }
            } else {
                return response()->json(array('success' => true, 'response' => false, 'message' => 'Password needs to be 8 or longer!', 'field' => 'edit-form-pass'));
            }
        }

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditHabbo()
    {
    $returnHTML = view('usercp.editHabbo')->render();

    return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditSocial(Request $request)
    {
        /* ADD AND SANATISE DISCORD */
        $discord = $request->input('discord');
        $discord = str_replace("<", "", $discord);
        $discord = str_replace(">", "", $discord);
        $discord = str_replace("(", "", $discord);
        $discord = str_replace(")", "", $discord);
        $discord = str_replace("[", "", $discord);
        $discord = str_replace("]", "", $discord);

        /* ADD AND SANATISE TWITTER */
        $twitter = $request->input('twitter');
        $twitter = str_replace("<", "", $twitter);
        $twitter = str_replace(">", "", $twitter);
        $twitter = str_replace("(", "", $twitter);
        $twitter = str_replace(")", "", $twitter);
        $twitter = str_replace("[", "", $twitter);
        $twitter = str_replace("]", "", $twitter);

        /* ADD AND SANATISE INSTAGRAM */
        $instagram = $request->input('instagram');
        $instagram = str_replace("<", "", $instagram);
        $instagram = str_replace(">", "", $instagram);
        $instagram = str_replace("(", "", $instagram);
        $instagram = str_replace(")", "", $instagram);
        $instagram = str_replace("[", "", $instagram);
        $instagram = str_replace("]", "", $instagram);

        /* ADD AND SANATISE KIK */
        $kik = $request->input('kik');
        $kik = str_replace("<", "", $kik);
        $kik = str_replace(">", "", $kik);
        $kik = str_replace("(", "", $kik);
        $kik = str_replace(")", "", $kik);
        $kik = str_replace("[", "", $kik);
        $kik = str_replace("]", "", $kik);

        /* ADD AND SANATISE LASTFM */
        $lastfm = $request->input('lastfm');
        $lastfm = str_replace("<", "", $lastfm);
        $lastfm = str_replace(">", "", $lastfm);
        $lastfm = str_replace("(", "", $lastfm);
        $lastfm = str_replace(")", "", $lastfm);
        $lastfm = str_replace("[", "", $lastfm);
        $lastfm = str_replace("]", "", $lastfm);

        /* ADD AND SANATISE SNAPCHAT */
        $snapchat = $request->input('snapchat');
        $snapchat = str_replace("<", "", $snapchat);
        $snapchat = str_replace(">", "", $snapchat);
        $snapchat = str_replace("(", "", $snapchat);
        $snapchat = str_replace(")", "", $snapchat);
        $snapchat = str_replace("[", "", $snapchat);
        $snapchat = str_replace("]", "", $snapchat);

        /* ADD AND SANATISE SOUNDCLOUD */
        $soundcloud = $request->input('soundcloud');
        $soundcloud = str_replace("<", "", $soundcloud);
        $soundcloud = str_replace(">", "", $soundcloud);
        $soundcloud = str_replace("(", "", $soundcloud);
        $soundcloud = str_replace(")", "", $soundcloud);
        $soundcloud = str_replace("[", "", $soundcloud);
        $soundcloud = str_replace("]", "", $soundcloud);

        /* ADD AND SANATISE TUMBLR */
        $tumblr = $request->input('tumblr');
        $tumblr = str_replace("<", "", $tumblr);
        $tumblr = str_replace(">", "", $tumblr);
        $tumblr = str_replace("(", "", $tumblr);
        $tumblr = str_replace(")", "", $tumblr);
        $tumblr = str_replace("[", "", $tumblr);
        $tumblr = str_replace("]", "", $tumblr);

        $response = true;

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'discord' => $discord,
            'twitter' => $twitter,
            'instagram' => $instagram,
            'kik' => $kik,
            'lastfm' => $lastfm,
            'snapchat' => $snapchat,
            'soundcloud' => $soundcloud,
            'tumblr' => $tumblr
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function postEditCountryTime(Request $request)
    {
        $country = $request->input('country');
        $timezone = $request->input('timezone');
        $response = true;

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'country' => $country,
            'timezone' => $timezone
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getIndex()
    {
        $temps = DB::table('background_users')->where('userid', Auth::user()->userid)->count();
        $temps2 = DB::table('name_effect_users')->where('userid', Auth::user()->userid)->count();
        $temps3 = DB::table('name_icon_users')->where('userid', Auth::user()->userid)->count();
        $temps4 = DB::table('sticker_users')->where('userid', Auth::user()->userid)->count();
        $temps5 = DB::table('theme_users')->where('userid', Auth::user()->userid)->count();

        $shop_owned = $temps + $temps2 + $temps3 + $temps4 + $temps5;

        $subscriptions = array();
        $subs = DB::table('subscription_subs')->where('userid', Auth::user()->userid)->where('end_date', '>', time())->get();

        foreach ($subs as $sub) {
            $package = DB::table('subscription_packages')->where('packageid', $sub->packageid)->first();

            $array = array(
                'name' => $package->name,
                'date' => date('jS F Y G:iA e', $sub->end_date)
            );
            $subscriptions[] = $array;
        }

        $quests = array();
        $temps = DB::table('active_quests')->where('userid', Auth::user()->userid)->get();
        foreach ($temps as $temp) {
            $array = array(
                'text' => $temp->text,
                'box' => DB::table('boxes')->where('boxid', $temp->boxid)->value('name')
            );
            $quests[] = $array;
        }

        $xpcount = Auth::user()->xpcount;
        $levels = DB::Table('xp_levels')->orderBy('posts', 'DESC')->get();
        $nextlevel_id = 0;
        $nextlevel_posts = 0;
        $level_id = 0;
        $level_desc = 0;
        $level_name = 0;
        $level_pro = 0;
        $found = false;
        foreach ($levels as $level) {
            if ($found == false) {
                if ($xpcount >= $level->posts) {
                    $level_id = $level->levelid;
                    $level_name = $level->name;
                    $found = true;
                } else {
                    $nextlevel_posts = $level->posts;
                    $nextlevel_id = $level->levelid;
                }
            }
        }
        $level_until = $nextlevel_posts - $xpcount;
        if ($nextlevel_id == 0) {
            $level_pro = 100;
        } else {
            $pro = ($xpcount / $nextlevel_posts) * 100;
            $level_pro = round($pro);
        }

        $returnHTML = view('usercp.index')
        ->with('shop_owned', $shop_owned)
        ->with('subscriptions', $subscriptions)
        ->with('quests', $quests)
        ->with('level_name', $level_name)
        ->with('level_until', $level_until)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getAccountSettings()
    {
        /* COUNTRY AND TIMEZONE */
        $countrys = DB::table('countrys')->orderBy('name', 'ASC')->get();
        $timezones = DB::table('timezones')->orderBy('timezoneid', 'ASC')->get();

        /* DISPLAY GROUP */
        $user = DB::table('users')->where('userid', Auth::user()->userid)->first();
        $temps = DB::table('usergroups')->get();
        $groups = array();
        $users_grps = explode(",", $user->usergroups);
        foreach ($temps as $temp) {
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

        $returnHTML = view('usercp.accountSettings')
        ->with('countrys', $countrys)
        ->with('timezones', $timezones)
        ->with('groups', $groups)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getForumSettings()
    {
        $extras = [
            'NOTIFICATION_ON_GUIDE' => UserHelper::haveExtraOn(Auth::user()->userid, 1),
            'NOTIFICATION_ON_FOLLOW' => UserHelper::haveExtraOn(Auth::user()->userid, 2),
            'NOTIFICATION_ON_QUOTE' => UserHelper::haveExtraOn(Auth::user()->userid, 4),
            'BBCODE_MODE' => UserHelper::haveExtraOn(Auth::user()->userid, 8),
            'HIDE_FORUM_AVS' => UserHelper::haveExtraOn(Auth::user()->userid, 16),
            'HIDE_FORUM_SIGS' => UserHelper::haveExtraOn(Auth::user()->userid, 32)
        ];

        $subscribedThreads = array();
        $temps = DB::table('subscription_threads')->where('userid', Auth::user()->userid)->get();
        foreach ($temps as $temp) {
            $thread = DB::table('threads')->where('threadid', $temp->threadid)->first();
            if (count($thread)) {
                $array = array(
                    'title' => $thread->title,
                    'threadid' => $thread->threadid
                );
                $subscribedThreads[] = $array;
            }
        }

        $temps = DB::table('xp_levels')->orderBy('posts', 'ASC')->get();
        $levels = array();
        $postscount = Auth::user()->postcount;
        $likescount = Auth::user()->likecount;
        $threads = Auth::user()->threadcount;
        $threadcount = $threads * 2;
        $followed = DB::table('followers')->where('userid', Auth::user()->userid)->count();
        $followedcount = $followed * 2;
        $badges = DB::table('users_badges')->where('userid', Auth::user()->userid)->count();
        $badgescount = $badges * 100;
        $vmcount = DB::table('visitor_messages')->where('reciveuserid', Auth::user()->userid)->count();
        $motm = DB::table('motm')->where('motmuserid', Auth::user()->userid)->count();
        $motmcount = $motm * 1000;
        $photocomp = DB::table('photo_comp')->where('pcuserid', Auth::user()->userid)->count();
        $photocompcount = $photocomp * 1000;
        $postcount = $postscount + $likescount + $threadcount + $followedcount + $badgescount + $vmcount + $motmcount + $photocompcount;
        foreach ($temps as $temp) {
            $completed = "";
            if ($postcount >= $temp->posts) {
                $completed = "<i class=\"fa fa-check-square-o\" aria-hidden=\"true\"></i> Achieved";
            }
            $array = array(
                'levelid' => $temp->levelid,
                'name' => $temp->name,
                'posts' => $temp->posts,
                'user' => Auth::user()->postcount,
                'completed' => $completed,
            );
            $levels[] = $array;
        }

        $returnHTML = view('usercp.forumSettings')
        ->with('extras', $extras)
        ->with('subscribedThreads', $subscribedThreads)
        ->with('levels', $levels)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getProfileSettings()
    {
        /* EDIT POSTBIT */
        $temp = DB::table('users')->where('userid', Auth::user()->userid)->first();
        $stats = explode(',', $temp->postbit);
        $jn = false;
        $ps = false;
        $lk = false;
        $sa = false;
        $lb = false;
        $hh = false;
        foreach ($stats as $stat) {
            if ($stat == 1) {
                $jn = true;
            } elseif ($stat == 2) {
                $ps = true;
            } elseif ($stat == 3) {
                $lk = true;
            } elseif ($stat == 4) {
                $sa = true;
            } elseif ($stat == 5) {
                $lb = true;
            } elseif ($stat == 6) {
                $hh = true;
            }
        }
        $usergroups = array();
        $groups = explode(",", Auth::user()->usergroups);
        $hide = explode(",", Auth::user()->hidebars);
        foreach ($groups as $group) {
            $gp = DB::table('usergroups')->where('usergroupid', $group)->first();
            if (count($gp)) {
                $bar = UserHelper::getUserbar(Auth::user()->userid, $gp->usergroupid);
                if (count($bar)) {
                    $checked = 1;
                    if (in_array($gp->usergroupid, $hide)) {
                        $checked = 0;
                    }
                    $array = array(
                        'title' => $gp->title,
                        'groupid' => $gp->usergroupid,
                        'checked' => $checked
                    );
                    $usergroups[] = $array;
                }
            }
        }
        $badgeIds = DB::table('users_badges')->where('userid', Auth::user()->userid)->pluck('badgeid');
        $temps = DB::table('badges')->whereIn('badgeid', $badgeIds)->get();
        $postbitbadges = array();
        $slBadges = array();
        $selectedBadges = explode(',', Auth::user()->postbit_badges);
        foreach ($temps as $temp) {
            if (in_array($temp->badgeid, $selectedBadges)) {
                $slBadges[] = array(
                    'name' => $temp->name,
                    'description' => $temp->description,
                    'badgeid' => $temp->badgeid
                );
            } else {
                $postbitbadges[] = array(
                    'name' => $temp->name,
                    'description' => $temp->description,
                    'badgeid' => $temp->badgeid
                );
            }
        }

        /* EDIT NAME ICON */
        $icons = DB::table('name_icon_users')->where('userid', Auth::user()->userid)->pluck('iconid');

        /* EDIT NAME EFFECT */
        $effects = DB::table('name_effect_users')->where('userid', Auth::user()->userid)->pluck('effectid');

        /* EDIT BACKGROUNDS */
        $backgrounds = DB::table('background_users')->where('userid', Auth::user()->userid)->pluck('backgroundid');

        /* EDIT PROFILE BADGES */
        $temps = DB::table('users_badges')->where('userid', Auth::user()->userid)->orderBy('userbadgeid', 'DESC')->get();
        $profilebadges = array();
        foreach ($temps as $temp) {
            $bdg = DB::table('badges')->where('badgeid', $temp->badgeid)->first();
            if (count($bdg)) {
                $array = array(
                    'badgeid' => $temp->badgeid,
                    'name' => $bdg->name,
                    'description' => $bdg->description,
                    'time' => ForumHelper::timeAgo($temp->dateline),
                    'selected' => $temp->selected
                );
                $profilebadges[] = $array;
            }
        }

        /* USERNAME FEATURES */
        $username_one_color = false;
        $username_rainbow_color = false;
        $username_custom_rainbow_color = false;
        if (UserHelper::haveSubFeature(Auth::user()->userid, 2)) {
            $username_one_color = true;
        }
        if (UserHelper::haveSubFeature(Auth::user()->userid, 4)) {
            $username_rainbow_color = true;
        }
        if (UserHelper::haveSubFeature(Auth::user()->userid, 8)) {
            $username_custom_rainbow_color = true;
        }
        $username_option = Auth::user()->username_option;
        $username_color = Auth::user()->username_color;

        /* USERBAR FEATURES */
        $grps = explode(",", Auth::user()->usergroups);
        $userbarusergroups = array();

        foreach ($grps as $grp) {
            $usrgrp = DB::table('usergroups')->where('usergroupid', $grp)->first();

            if (count($usrgrp)) {
                if ($usrgrp->features & 16 or $usrgrp->features & 32 or $usrgrp->features & 64) {
                    $userbarusergroups[] = array(
                        'title' => $usrgrp->title,
                        'groupid' => $usrgrp->usergroupid
                    );
                }
            }
        }

        $returnHTML = view('usercp.profileSettings')
        ->with('usergroups', $usergroups)
        ->with('jn', $jn)
        ->with('ps', $ps)
        ->with('lk', $lk)
        ->with('sa', $sa)
        ->with('lb', $lb)
        ->with('hh', $hh)
        ->with('postbitbadges', $postbitbadges)
        ->with('slBadges', $slBadges)
        ->with('icons', $icons)
        ->with('effects', $effects)
        ->with('backgrounds', $backgrounds)
        ->with('profilebadges', $profilebadges)
        ->with('username_one_color', $username_one_color)
        ->with('username_rainbow_color', $username_rainbow_color)
        ->with('username_custom_rainbow_color', $username_custom_rainbow_color)
        ->with('username_option', $username_option)
        ->with('username', UserHelper::getUsername(Auth::user()->userid))
        ->with('username_color', $username_color)
        ->with('userbarusergroups', $userbarusergroups)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditSignature(Request $request)
    {
        $content = $request->input('content');

        DB::table('users')->where('userid', Auth::user()->userid)->update([
            'signature' => $content
        ]);

        return response()->json(array('success' => true));
    }

    public function getSignature()
    {
        $signature = ForumHelper::fixContent(Auth::user()->signature);
        $signature = ForumHelper::replaceEmojis($signature);
        $signature = ForumHelper::bbcodeParser($signature);
        $signature = nl2br($signature);

        $editsignature = str_replace("\"", "&quot;", Auth::user()->signature);

        $returnHTML = view('usercp.editSignature')->with('signature', $signature)->with('editsignature', $editsignature)->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function readNotification($notificationid)
    {
        DB::table('notifications')->where('notificationid', $notificationid)->where('reciveuserid', Auth::user()->userid)->update([
            'read_at' => time()
        ]);

        $amount = DB::table('notifications')->where('reciveuserid', Auth::user()->userid)->where('read_at', 0)->count();

        return response()->json(array('success' => true, 'response' => true, 'amount' => $amount));
    }

    public function getNotifications($pagenr = 1)
    {
        if ($pagenr < 1) {
            $pagenr = 1;
        }
        $newtake = 20;
        $newskip = ($pagenr*$newtake)-$newtake;
        if ($newskip<=0) {
            $newskip = 0;
        }

        $pagesx = DB::table('notifications')->where('reciveuserid', Auth::user()->userid)->count();
        $pages = ceil($pagesx/$newtake);

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

        $pagi = view('layout.paginator')->with('paginator', $paginator)->with('url', '/usercp/notifications/')->render();



        $new_notifications = array();
        $read_notifications = array();

        /* LOAD ALL NEW NOTIFICATIONS AND PUT THEM AT THE TOP */
        $temps = DB::table('notifications')->where('read_at', 0)->where('reciveuserid', Auth::user()->userid)->orderBy('notificationid', 'DESC')->take($newtake)->skip($newskip)->get();

        foreach ($temps as $noti) {
            $message = "";
            $run = false;
            $avatar = UserHelper::getAvatar($noti->postuserid);

            switch ($noti->content) {
                case 1:
                    //Mentioned
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $noti->contentid . '" class="web-page notif-link">';
                            $message = UserHelper::getUsername($noti->postuserid) . ' ' . $link . ' mentioned you in a post in the thread ' . $thread->title . '</a>';
                            $run = true;

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                $run = false;
                            }
                        }
                    }
                break;
                case 2:
                    //Quote
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $noti->contentid . '" class="web-page notif-link">';
                            $message = UserHelper::getUsername($noti->postuserid) . ' ' . $link . 'quoted your post in the thread ' . $thread->title . '</a>';
                            $run = true;

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                $run = false;
                            }
                        }
                    }
                break;
                case 3:
                    //Like
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid=' . $noti->contentid . '" class="web-page notif-link">';
                            $message = UserHelper::getUsername($noti->postuserid) . ' ' . $link . 'liked your post in the thread ' . $thread->title . '</a>';
                            $run = true;

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                $run = false;
                            }
                        }
                    }
                break;
                case 4:
                    //Someone posted in thread they subscribe in
                    $post = DB::table('posts')->where('postid', $noti->contentid)->first();
                    if (count($post)) {
                        $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                        if (count($thread)) {
                            $page = $noti->page;

                            if ($page < 1) {
                                $page = 1;
                            }

                            $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '" class="web-page notif-link">';
                            $message = UserHelper::getUsername($noti->postuserid) . ' ' . $link . ' posted in the thread ' . $thread->title . '</a>';
                            $run = true;

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                $run = false;
                            }

                            if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                $run = false;
                            }
                        }
                    }
                break;
                case 5:
                            // private message
                            $user = DB::table('users')->where('userid', $noti->postuserid)->first();
                            if (count($user)) {
                                $link = '<a class="web-page notif-link" href="/usercp/pm?userid=' . $user->userid . '">';
                                $message =  $link . UserHelper::getUsername($user->userid) . ' sent a private message!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($user->userid);
                            }
                        break;
                case 6:
                    $vm = DB::table('visitor_messages')->where('vmid', $noti->contentid)->first();
                    if (count($vm)) {
                        $amountPosted = DB::table('visitor_messages')->where('vmid', '>=', $noti->contentid)->where('postuserid', $noti->postuserid)->where('reciveuserid', $noti->reciveuserid)->where('visible', '1')->count();
                        $amountReceived = DB::table('visitor_messages')->where('vmid', '>=', $noti->contentid)->where('postuserid', $noti->reciveuserid)->where('reciveuserid', $noti->postuserid)->where('visible', '1')->count();
                        $amountPosts = $amountPosted + $amountReceived;
                        $page = ceil($amountPosts/10);

                        if ($page < 1) {
                            $page = 1;
                        }

                        $user1 = DB::table('users')->where('userid', $vm->postuserid)->first();
                        $user2 = DB::table('users')->where('userid', $vm->reciveuserid)->first();

                        if (count($user1) and count($user2)) {
                            $link = '<a class="notif-link web-page" href="/conversation/' . $user1->username . '/' . $user2->username . '/page/' . $page . '">';
                            $message = UserHelper::getUsername($noti->postuserid) . ' ' . $link . ' sent you a visitor message - click to read!</a>';
                            $run = true;
                        }
                    }
                break;
                case 7:
                    $badge = DB::table('badges')->where('badgeid', $noti->contentid)->first();
                    if (count($badge)) {
                        $link = '<a href="/usercp/badges" class="web-page notif-link">';
                        $message =  'You have received a new badge! ' . $link . '</a>';
                        $run = true;

                        $avatar = asset('_assets/img/website/badges/' . $badge->badgeid . '.gif');
                    }
                break;
                case 8:
                            // new quest guide published
                            $article = DB::table('articles')->where('articleid', $noti->contentid)->first();
                            if (count($article)) {
                                $link = '/article/' . $article->articleid . '-' . $article->title;
                                $message = 'A new quest guide has been published! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                                $run = true;

                                $badge = 'https://habboo-a.akamaihd.net/c_images/album1584/' . $article->badge_code . '.gif';

                                if (@getimagesize($badge)) {
                                    $avatar = $badge;
                                } else {
                                    $avatar = asset('_assets/img/website/badge_error.gif');
                                }
                            }
                        break;
                        case 9:
                            // someone followed you
                            $user = DB::table('users')->where('userid', $noti->contentid)->first();
                            if (count($user)) {
                                $link = '/profile/' . $user->username . '/page/1';
                                $message =  UserHelper::getUsername($user->userid) . ' followed you! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($user->userid);
                            }
                        break;
                        case 10:
                            // someone referred you
                            $user = DB::table('users')->where('userid', $noti->contentid)->first();
                            if (count($user)) {
                                $link = '/profile/' . $user->username;
                                $message =  UserHelper::getUsername($user->userid) . ' referred you! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($user->userid);
                            }
                        break;
                        case 11:
                            // Someone mentioned you in an article
                            $comment = DB::table('article_comments')->where('commentid', $noti->contentid)->first();
                            if (count($comment)) {
                                $pagex = DB::table('article_comments')->where('articleid', $comment->articleid)->where('commentid', '<', $comment->commentid)->count();
                                $page = ceil($pagex/10);

                                if ($page < 1) {
                                    $page = 1;
                                }
                                $link = '<a class="notif-link web-page" href="/article/' . $comment->articleid . '/page/' . $page . '"';
                                $message =  UserHelper::getUsername($comment->userid) . ' mentioned you in an article!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($comment->userid);
                            }
                        break;
                        case 12:
                            // Someone mentioned you on a creation
                            $comment = DB::table('creation_comments')->where('commentid', $noti->contentid)->first();
                            if (count($comment)) {
                                $pagex = DB::table('creation_comments')->where('creationid', $comment->creationid)->where('commentid', '<', $comment->commentid)->count();
                                $page = ceil($pagex/10);

                                if ($page < 1) {
                                    $page = 1;
                                }
                                $link = '/creation/' . $comment->creationid . '/page/' . $page;
                                $message =  UserHelper::getUsername($comment->userid) . ' mentioned you on an creation! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($comment->userid);
                            }
                        break;
                        case 13:
                            // Someone gifted you some THC
                            $user = DB::table('users')->where('userid', $noti->postuserid)->first();
                            if (count($user)) {
                                $link = '/usercp/shop';
                                $message =  UserHelper::getUsername($user->userid) . ' gave you ' . number_format($noti->contentid) . ' THC for the shop! <a class="web-page" href="'.$link.'">Click here to view!</a>';
                                $run = true;

                                $avatar = UserHelper::getAvatar($user->userid);
                            }
                        break;
                        case 14:
                            // Someone Replied to your comment
                            $user1 = DB::table('users')->where('userid', $noti->postuserid)->first();
                            $user2 = DB::table('users')->where('userid', $noti->reciveuserid)->first();
                            $quest = DB::table('articles')->where('articleid', $noti->contentid)->first();
                            if (count($quest)) {
                                $link = '/article/' . $quest->articleid;
                                $message =  $user1->username . ' replied to your comment! <a class="web-page" href="'.$link.'">Click here to view!</a>';
                                $run = true;
                            }
                        break;
                        case 15:
                            // You won a mystery box
                            $link = '/usercp/shop/box/open/'. $noti->contentid;
                            $message =  'You won a mystery box from a daily quest! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                            $run = true;
                        break;
                        case 16:
                            // Someone liked your comment
                        $comment = DB::table('article_comments')->where('commentid', $noti->contentid)->first();
                        if (count($comment)) {
                            $article = DB::table('articles')->where('articleid', $comment->articleid)->where('approved', 1)->first();
                            if (count($article)) {
                                $amountComments = DB::table('article_comments')->where('commentid', '<', $comment->commentid)->where('articleid', $article->articleid)->count();

                                $page = ceil($amountComments/10);

                                if ($page < 1) {
                                    $page = 1;
                                }

                                $link = '/article/' . $article->articleid;
                                $message = UserHelper::getUsername($noti->postuserid) . 'liked your comment in the article ' . $article->title . '! <a class="web-page notif-link" href="'.$link.'">Click here to view!</a>';
                                $run = true;
                            }
                        }
                        break;
                        case 18:
                            // Someone Liked you
                            $liker = UserHelper::getUsername($noti->postuserid);
                            $likerClean = UserHelper::getUsername($noti->postuserid, true);
                            $link = '/profile/' . $likerClean;
                            $message = $liker . ' liked you! Keep it up!';
                            $run = true;
                        break;
                        case 19:
                            // Someone Invited you to a clan

                            $inviter = UserHelper::getUsername($noti->postuserid);
                            $clan = DB::table('clans')->where('groupid', $noti->contentid)->first();

                            $link = '/clans/' . $clan->groupname;

                            $message = $inviter . ' invited you to join team ' . $clan->groupname . '! <a class="web-page notif-link" href="'.$link.'">Click here to respond!</a>';
                            $run = true;
                        break;
                        case 20:
                            // You earnt a key

                            $link = '/usercp';
                            $message =  'You have earnt a key!';
                            $run = true;

                        break;
            }

            if ($run) {
                $array = array(
                    'notificationid' => $noti->notificationid,
                    'avatar' => $avatar,
                    'time' => ForumHelper::timeAgo($noti->dateline),
                    'message' => $message
                );

                $new_notifications[] = $array;
            }
        }

        $newcount = DB::table('notifications')->where('reciveuserid', Auth::user()->userid)->where('read_at', 0)->count();
        if (count($new_notifications) < $newtake) {
            $oldtake = $newtake - count($new_notifications);
            $oldskip = $newskip - $newcount;
            if ($oldskip < 0) {
                $oldskip = 0;
            }

            /* LOAD ALL READ NOTIFICATIONS AND PUT THEM AT THE TOP */
            $temps = DB::table('notifications')->where('read_at', '>', 0)->where('reciveuserid', Auth::user()->userid)->orderBy('notificationid', 'DESC')->take($oldtake)->skip($oldskip)->get();

            foreach ($temps as $temp) {
                $message = "";
                $run = false;
                $avatar = UserHelper::getAvatar($temp->postuserid);
                switch ($temp->content) {
                    case 1:
                        //Mentioned
                        $post = DB::table('posts')->where('postid', $temp->contentid)->first();
                        if (count($post)) {
                            $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                            if (count($thread)) {
                                $page = $temp->page;

                                if ($page < 1) {
                                    $page = 1;
                                }

                                $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid='. $post->postid . '" class="web-page">';
                                $message = UserHelper::getUsername($temp->postuserid) . ' mentioned you in a post in the thread ' . $link . '<b>' . $thread->title . '</b></a>';
                                $run = true;

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                    $run = false;
                                }

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                    $run = false;
                                }
                            }
                        }
                    break;
                    case 2:
                        //Quote
                        $post = DB::table('posts')->where('postid', $temp->contentid)->first();
                        if (count($post)) {
                            $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                            if (count($thread)) {
                                $page = $temp->page;
                                if ($page < 1) {
                                    $page = 1;
                                }

                                $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid='. $post->postid . '" class="web-page">';
                                $message = UserHelper::getUsername($temp->postuserid) . ' quoted your post in the thread ' . $link . '<b>' . $thread->title . '</b></a>';
                                $run = true;

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                    $run = false;
                                }

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                    $run = false;
                                }
                            }
                        }
                    break;
                    case 3:
                        //Like
                        $post = DB::table('posts')->where('postid', $temp->contentid)->first();
                        if (count($post)) {
                            $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                            if (count($thread)) {
                                $page = $temp->page;

                                if ($page < 1) {
                                    $page = 1;
                                }

                                $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '?postid='. $post->postid . '" class="web-page">';
                                $message = UserHelper::getUsername($temp->postuserid) . ' liked your post in the thread ' . $link . '<b>' . $thread->title . '</b></a>';
                                $run = true;

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                    $run = false;
                                }

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                    $run = false;
                                }
                            }
                        }
                    break;
                    case 4:
                        //subscribe
                        $post = DB::table('posts')->where('postid', $temp->contentid)->first();
                        if (count($post)) {
                            $thread = DB::table('threads')->where('threadid', $post->threadid)->where('visible', 1)->first();
                            if (count($thread)) {
                                $amountPosts = DB::table('posts')->where('postid', '<', $post->postid)->where('threadid', $thread->threadid)->where('visible', 1)->count();

                                $page = ceil($amountPosts/10);

                                if ($page < 1) {
                                    $page = 1;
                                }

                                $link = '<a href="/forum/thread/' . $thread->threadid . '/page/' . $page . '" class="web-page">';
                                $message = UserHelper::getUsername($temp->postuserid) . ' posted in the thread ' . $link . '<b>' . $thread->title . '</b></a>';
                                $run = true;

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 1)) {
                                    $run = false;
                                }

                                if (!UserHelper::haveForumPerm(Auth::user()->userid, $thread->forumid, 32) and $thread->postuserid != Auth::user()->userid) {
                                    $run = false;
                                }
                            }
                        }
                    break;
                    case 6:
                        $vm = DB::table('visitor_messages')->where('vmid', $temp->contentid)->first();
                        if (count($vm)) {
                            $amountPosted = DB::table('visitor_messages')->where('vmid', '>=', $vm->vmid)->where('postuserid', $vm->postuserid)->where('reciveuserid', $vm->reciveuserid)->where('visible', '1')->count();
                            $amountReceived = DB::table('visitor_messages')->where('vmid', '>=', $vm->vmid)->where('postuserid', $vm->reciveuserid)->where('reciveuserid', $vm->postuserid)->where('visible', '1')->count();
                            $amountPosts = $amountPosted + $amountReceived;

                            $page = ceil($amountPosts/10);

                            if ($page < 1) {
                                $page = 1;
                            }

                            $user1 = DB::table('users')->where('userid', $vm->postuserid)->first();
                            $user2 = DB::table('users')->where('userid', $vm->reciveuserid)->first();

                            if (count($user1) and count($user2)) {
                                $link = '<a href="/conversation/' . $user1->username . '/' . $user2->username . '/page/' . $page . '" class="web-page">';
                                $message = UserHelper::getUsername($temp->postuserid) . ' ' . $link . ' sent you a visitor message - click to read!</a>';
                                $run = true;
                            }
                        }
                    break;
                    case 7:
                        $badge = DB::table('badges')->where('badgeid', $temp->contentid)->first();
                        if (count($badge)) {
                            $link = '<a href="/usercp/badges" class="web-page">';
                            $message =  'You have received a new badge! ' . $link . '</a>';
                            $run = true;

                            $avatar = asset('_assets/img/website/badges/' . $badge->badgeid . '.gif');
                        }
                    break;
                    case 14:
                        $message = "test";
                        $link = "test";
                        $run = true;
                }

                if ($run) {
                    $array = array(
                        'notificationid' => $temp->notificationid,
                        'avatar' => $avatar,
                        'time' => ForumHelper::timeAgo($temp->dateline),
                        'message' => $message
                    );

                    $read_notifications[] = $array;
                }
            }
        }

        $returnHTML = view('usercp.notifications')
        ->with('new_notifications', $new_notifications)
        ->with('read_notifications', $read_notifications)
        ->with('pagi', $pagi)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditPostbit(Request $request)
    {
        $jn = $request->input('jn');
        $ps = $request->input('ps');
        $lk = $request->input('lk');
        $sa = $request->input('sa');
        $lb = $request->input('lb');
        $hh = $request->input('hh');

        $post_avatar = $request->input('post_avatar');
        $hide_groups = explode(",", $request->input('hide_groups'));
        $selectedBadges = $request->input('selectedBadges');

        $current_groups = explode(",", Auth::user()->usergroups);

        if ($post_avatar != 0 && $post_avatar != 1 && $post_avatar != 2 && $post_avatar != 3 && $post_avatar != 4 && $post_avatar != 5) {
            $post_avatar = 1;
        }

        $userid = Auth::user()->userid;

        $stats = '';

        if ($jn == 1) {
            $stats = $stats . ',' . '1';
        }
        if ($ps == 1) {
            $stats = $stats . ',' . '2';
        }
        if ($lk == 1) {
            $stats = $stats . ',' . '3';
        }
        if ($sa == 1) {
            $stats = $stats . ',' . '4';
        }
        if ($lb == 1) {
            $stats = $stats . ',' . '5';
        }
        if ($hh == 1) {
            $stats = $stats . ',' . '6';
        }

        $hidebars = "";
        $first = 1;

        foreach ($hide_groups as $hide) {
            if (in_array($hide, $current_groups)) {
                if ($first == 1) {
                    $hidebars = $hide;
                    $first = 0;
                } else {
                    $hidebars = $hidebars . ',' . $hide;
                }
            }
        }

        $accepted_badges = array();
        $amount = 0;
        if ($selectedBadges) {
            foreach ($selectedBadges as $slBadge) {
                $checkIfOwnBadge = DB::table('users_badges')->where('userid', Auth::user()->userid)->where('badgeid', $slBadge)->count();

                if ($checkIfOwnBadge > 0) {
                    $accepted_badges[] = $slBadge;
                    $amount++;
                }
                if ($amount == 4) {
                    break;
                }
            }
        }

        DB::table('users')->where('userid', $userid)->update([
            'postbit' => $stats,
            'hidebars' => $hidebars,
            'post_avatar' => $post_avatar,
            'postbit_badges' => implode(',', $accepted_badges)
        ]);

        return response()->json(array('success' => true, 'response' => true));
    }

    public function getEditAvatar()
    {
        $groups = explode(",", Auth::user()->usergroups);
        $max_height = 200;
        $max_width = 200;

        foreach ($groups as $group) {
            $grp = DB::table('usergroups')->where('usergroupid', $group)->first();

            if (count($grp)) {
                if ($grp->avatar_height >= $max_height and $grp->avatar_width >= $max_width) {
                    $max_width = $grp->avatar_width;
                    $max_height = $grp->avatar_height;
                }
            }
        }

        $returnHTML = view('usercp.avatar')
        ->with('max_width', $max_width)
        ->with('max_height', $max_height)
        ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postEditAvatar(Request $request)
    {
        $response = false;

        if ($request->hasFile('avatar') and $request->file('avatar')->isValid()) {
            $img = Image::make($request->file('avatar'));
            $avid = time() . Auth::user()->userid;

            $groups = explode(",", Auth::user()->usergroups);
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
                File::delete(asset('_assets/img/avatars/'.Auth::user()->avatar).'.gif');
            }
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'lastavataredit' => time(),
                'avatar' => $avid
            ]);
            $response = true;
        }

        $avatar = UserHelper::getAvatar(Auth::user()->userid);

        return response()->json(array('success' => true, 'response' => true, 'new_avatar' => $avatar . '?' . rand()));
    }

    public function getOwned()
    {

        $temps = DB::table('shop_transactions')->orderBy('transactionid', 'DESC')->where('userid',Auth::user()->userid)->where('action', 1)->where('item', '!=', 4)->where('item', '!=', 2)->get();
        $transactions = array();

        foreach ($temps as $temp) {
            $category = "";
            $name = "";

            switch ($temp->item) {
                case 1:
                    // $text = $text . ' a name icon!';
                    $icon = DB::table('name_icons')->where('iconid', $temp->itemid)->first();
                    if(count($icon)) {
                        $category = 'Name Icon';
                        $name = $icon->name;
                    } else {
                        $category = 'Name Icon';
                        $name = 'Not Found';
                    }
                break;
                case 3:
                    //$text = $text . ' a name effect!';
                    $icon = DB::table('name_effects')->where('effectid', $temp->itemid)->first();
                    if(count($icon)) {
                        $category = 'Name Effect';
                        $name = $icon->name;
                    } else {
                        $category = 'Name Effect';
                        $name = 'Not Found';
                    }
                break;
                case 5:
                    //$text = $text . ' a sticker!';
                    $icon = DB::table('stickers')->where('stickerid', $temp->itemid)->first();
                    if(count($icon)) {
                        $category = 'Sticker';
                        $name = $icon->name;
                    } else {
                        $category = 'Sticker';
                        $name = 'Not Found';
                    }
                break;
                case 6:
                    // $text = $text . ' a background!';
                    $icon = DB::table('backgrounds')->where('backgroundid', $temp->itemid)->first();
                    if(count($icon)) {
                        $category = 'Background';
                        $name = $icon->name;
                    } else {
                        $category = 'Background';
                        $name = 'Not Found';
                    }
                break;
            }

            $transactions[] = array(
                'category' => $category,
                'item' => $name
            );
        }

        $returnHTML = view('usercp.shop.ownedItems')
        ->with('transactions', $transactions)
        ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function getOpenBoxes()
    {

        $prizesTemp = DB::table('xmasbox_winners')->where('user_id', Auth::user()->userid)->orderBy('dateline', 'DESC')->get();

        $prizes = array();

        foreach ($prizesTemp as $temp) {

            $prizeName = DB::table('xmasbox_prizes')->where('id', $temp->prize)->first();

            $array = array(
                'prize' => $prizeName->prize,
                'claimed' => $temp->claimed ? 'Yes' : 'No',
                'dateline' => ForumHelper::timeAgo($temp->dateline)
            );

            $prizes[] = $array;
        }

        $returnHTML = view('usercp.openBox')
            ->with('prizes', $prizes)
            ->render();

        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function postKeyOpeningBox(Request $request)
    {
        $response = false;
        $message = '';
        $claimed = 0;
        $userid = Auth::user()->userid;
        $randomPrize = DB::table('xmasbox_prizes')->where('quantity', '>', 0)->orderByRaw('RAND()')->first();

        $chance = $request->input('chance');
        $price = $request->input('price');

        if(Auth::user()->owned_keys > 0 && (Auth::user()->owned_keys - $price) >= 0){

            $response = true;

            DB::table('users')->where(['userid' => Auth::user()->userid])->update([
                'owned_keys' => Auth::user()->owned_keys - $price
            ]);

            if(rand(1,100) <= $chance){
                DB::table('xmasbox_prizes')->where(['id' => $randomPrize->id])->update([
                    'quantity' =>  DB::raw('quantity-1')
                ]);

                if($randomPrize->prize == 'THC'){
                    $THCTotal = rand(1, 100);
                    DB::table('users')->where(['userid' => Auth::user()->userid])->update([
                        'credits' => Auth::user()->credits + $THCTotal
                    ]);
                    $claimed = 1;
                    $message = 'You have opened the box and recieved ' . $THCTotal . ' credits.';
                }elseif($randomPrize->prize == 'Diamond'){
                    DB::table('users')->where(['userid' => Auth::user()->userid])->update([
                        'diamonds' =>  DB::raw('diamonds+1')
                    ]);

                    $claimed = 1;
                    $message = 'You have opened the box and found a diamond!';
                }elseif($randomPrize->prize == '1 Month Donator'){
                    $length = 31 * (60*60*24);
                    $alreadyHas = DB::table('subscription_subs')->where('userid', $userid)->where('packageid', 1)->first();

                    if (count($alreadyHas)) {
                        $currentEnd = $alreadyHas->end_date;
                        if ($currentEnd > time()) {
                            $newEnd = $currentEnd + $length;
                        } else {
                            $newEnd = time() + $length;
                        }
                        DB::table('subscription_subs')->where('userid', $userid)->where('packageid', 1)->update(['end_date' => $newEnd]);
                    } else {
                        DB::table('subscription_subs')->insert([
                            'userid' => $userid,
                            'packageid' => 1,
                            'start_date' => time(),
                            'end_date' => time() + 2419200
                        ]);
                    }

                    $claimed = 1;
                    $message = 'You have opened the box and won ' . $randomPrize->prize;
                }else{
                    $claimed = 0;
                    $message = 'You have opened the box and won ' . $randomPrize->prize;
                }

                DB::table('xmasbox_winners')->insert([
                    'user_id' => Auth::user()->userid,
                    'prize' => $randomPrize->id,
                    'claimed' => $claimed,
                    'dateline' => time()
                ]);
            }else{
                $message = 'Unlucky, you key snapped whilst opening the box.';
            }
        }else{
            $message = 'You do not have enough keys to open this box.';
        }

        return response()->json(array('success' => true, 'response' => $response, 'message' => $message));
    }
}
