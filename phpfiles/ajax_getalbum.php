<?php

    $consumer_key = 'GUIScqCZQtYFacesCmMK';
    $consumer_secret = 'JJTExBnEGtDKmXvdszsqnxMPJTNpfIWq';
    $discogs_endp = 'https://api.discogs.com/database/search?';
    $TO_RETURN = [];

    if (!isset($_GET['album-title']) || !isset($_GET['album-artist'])){
        $TO_RETURN['error'] = 'no post data';
    }
    else{
        $title = trim($_GET['album-title']);
        $artist = trim($_GET['album-artist']);

        if (strlen($title) !== 0 && strlen($artist) !== 0){
            $params = http_build_query(array("release_title" => urlencode($title), "artist" => urlencode($artist), "per_page" => "1"));
            $headers = array("Authorization: Discogs key=$consumer_key, secret=$consumer_secret");
            $user_agent = "Diapason/1.0 ";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $discogs_endp.$params);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($curl, CURLOPT_COOKIE, "SameSite=Strict");
            
            $TO_RETURN = json_decode(curl_exec($curl))->results[0]->cover_image;
            curl_close($curl);  

        }
    }

    echo (json_encode($TO_RETURN));
?>