<?php namespace App\Helpers;

use App\User;
use DB;
use Auth;
use App\Helpers\UserHelper;

class StaffHelper {
    public static function creations()
    {
        return DB::table('creations')->where('approved', '0')->orderBy('creationid', 'ASC')->count();
    }

    public static function flaggedArticles()
    {
        return DB::table('flaged_articles')->where('handled', 0)->count();
    }

    public static function getRadioAlbum($song_temp)
    {
        $song_temp = strtolower($song_temp);

        if (strpos($song_temp, '&') !== false) {
            $song_temp = explode('&', $song_temp);
            if (is_array($song_temp)) {
                $song_temp = strtolower($song_temp[0]);
            }
        }

        $song_temp = trim($song_temp);

        $url = "https://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" . $song_temp . "&api_key=e85b98981b6ee7a1ce3e0f511aa67a91&format=json";
        $album_art = "https://i.imgur.com/xd2NHuO.png";
        $useragent    = "Mozilla (DNAS 2 Statuscheck)";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($result != null && !isset($result->error)) {
            $imageKey = '#text';
            if (is_array($result->artist->image)) {
                $al = $result->artist->image[3]->{'#text'};
            } else {
                $al = $result->artist->image->{'#text'};
            }

            if (isset($al)) {
                $album_art = $al;
            }
        }

        if ($album_art == "") {
            $album_art = "https://i.imgur.com/xd2NHuO.png";
        }

        return $album_art;
    }

    public static function getRadioStats()
    {
        $radio_details = DB::table('radio_details')->orderBy('dateline', 'DESC')->first();
        $dnas_data = null;
        if (count($radio_details)) {
            $url = $radio_details->ip . ':' . $radio_details->port . '/stats?sid=1&json=1';
            $dnas_data = null;
            $useragent    = "Mozilla (DNAS 2 Statuscheck)";
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 45);

            $result = curl_exec($ch);
            $json = json_decode($result);

            if ($json != null) {
                $dnas_data = array(
                    'CURRENTLISTENERS'    => $json->currentlisteners,
                    'PEAKLISTENERS'        => $json->peaklisteners,
                    'MAXLISTENERS'        => $json->maxlisteners,
                    'UNIQUELISTENERS'		=> $json->uniquelisteners,
                    'AVERAGETIME'        => $json->averagetime,
                    'SERVERGENRE'        => $json->servergenre,
                    'SERVERURL'            => $json->serverurl,
                    'SERVERTITLE'        => $json->servertitle,
                    'SONGTITLE'            => $json->songtitle,
                    'STREAMHITS'        => $json->streamhits,
                    'STREAMSTATUS'        => $json->streamstatus,
                    'BITRATE'            => $json->bitrate,
                    'CONTENT'            => $json->content,
                    'VERSION'            => $json->version,
                );
            }
            curl_close($ch);
        }
        return $dnas_data;

    }
}
