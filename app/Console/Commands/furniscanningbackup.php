/* START FURNI SCANNING */
$schedule->call(function() {
    $url = 'https://www.habbo.com/gamedata/furnidata_xml/1';
    $dnas_data = null;
    $useragent    = "Mozilla (DNAS 2 Statuscheck)";
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

    $result = curl_exec($ch);
    $xml = @simplexml_load_string($result);

    if($xml != null) {
        foreach($xml->roomitemtypes->furnitype as $item) {
            $id = (string)$item->attributes()['id'];
            $doesExist = DB::table('furnis')->where('id', $id)->count();

            if($doesExist == 0) {
                $classname = (string)$item->attributes()['classname'];
                $revision = (string)$item->revision;
                $name = (string)$item->name;
                $description = (string)$item->description;
                $bc = (string)$item->bc;
                $canstandon = (string)$item->canstandon;
                $cansiton = (string)$item->cansiton;
                $canlayon = (string)$item->canlayon;
                $buyout = (string)$item->buyout;
                $furniline = (string)$item->furniline;

                DB::table('furnis')->insert([
                    'id' => $id,
                    'classname' => $classname,
                    'revision' => $revision,
                    'name' => $name,
                    'description' => $description,
                    'bc' => $bc,
                    'canstandon' => $canstandon,
                    'cansiton' => $cansiton,
                    'canlayon' => $canlayon,
                    'buyout' => $buyout,
                    'furniline' => $furniline,
                    'dateline' => time()
                ]);
            }
        }
        foreach($xml->wallitemtypes->furnitype as $item) {
            $id = (string)$item->attributes()['id'];
            $doesExist = DB::table('furnis')->where('id', $id)->count();

            if($doesExist == 0) {
                $classname = (string)$item->attributes()['classname'];
                $revision = (string)$item->revision;
                $name = (string)$item->name;
                $description = (string)$item->description;
                $bc = (string)$item->bc;
                $canstandon = (string)$item->canstandon;
                $cansiton = (string)$item->cansiton;
                $canlayon = (string)$item->canlayon;
                $buyout = (string)$item->buyout;
                $furniline = (string)$item->furniline;

                DB::table('furnis')->insert([
                    'id' => $id,
                    'classname' => $classname,
                    'revision' => $revision,
                    'name' => $name,
                    'description' => $description,
                    'bc' => $bc,
                    'canstandon' => $canstandon,
                    'cansiton' => $cansiton,
                    'canlayon' => $canlayon,
                    'buyout' => $buyout,
                    'furniline' => $furniline,
                    'dateline' => time()
                ]);
            }
        }
    }
    curl_close($ch);
})->everyThirtyMinutes();
/* END FURNI SCANNING */
