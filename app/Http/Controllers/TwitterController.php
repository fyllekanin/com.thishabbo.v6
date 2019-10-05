<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Validate;
use Auth;
use Image;
use App\User;
use DB;
use Hash;
use File;
use Twitter;

class TwitterController extends BaseController
{
    public function __construct()
    {
    }

    public function twitterUserTimeLine()
    {
        $data = Twitter::getUserTimeline(['count' => 10, 'format' => 'array']);

        $returnHTML = view('admincp.pages.twitter')
    ->with('data', $data)
    ->render();
        return response()->json(array('success' => true, 'returnHTML' => $returnHTML));
    }

    public function tweet(Request $request)
    {
        $newTwitte = ['status' => $request->tweet];

        if (!empty($request->images)) {
            foreach ($request->images as $key => $value) {
                $uploaded_media = Twitter::uploadMedia(['media' => File::get($value->getRealPath())]);
                if (!empty($uploaded_media)) {
                    $newTwitte['media_ids'][$uploaded_media->media_id_string] = $uploaded_media->media_id_string;
                }
            }
        }
        $twitter = Twitter::postTweet($newTwitte);
        return back();
    }
}
