<?php

    $REG_ERROR_CODES= ["empty" => 0, "too long" => 1, "too short" => 2, "not valid" => 3, "unavailable" => 4, "not equal" => 5];

    function getUserData($toFind){

        $TO_RETURN = null;

        $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());

        $name = mysqli_real_escape_string($conn, $toFind);

        $query = "SELECT Nome, Cognome, Username, Propic, SignupDate, AmtFollows, AmtFollowedBy, Motto FROM USERS WHERE Username = '".$name."'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if (mysqli_num_rows($res) === 1){
            $record = mysqli_fetch_object($res);
            $TO_RETURN = $record;
        }

        mysqli_free_result($res);
        mysqli_close($conn);

        return $TO_RETURN;
    }

    function getRank($date){
        $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());
        $query = "SELECT DATEDIFF(CURDATE(), '".$date."')";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $rank = mysqli_fetch_array($res);
        $rank[0] = floor($rank[0]/365);
        mysqli_free_result($res);
        mysqli_close($conn);
        switch($rank[0]){
            case 0:
                return "Pozzoli I Corso";
            case 1:
                return "Musicista Occasionale";
            case 2:
                return "Amante del Setticlavio";
            case 3:
                return "Direttore d'Orchestra";
            case 4: 
                return "Compositore";
            default:
                return "Allievo di Liszt";
        }
    }

    function welcome_user($name){
        $time = localtime(null, true);
        
        if ($time['tm_hour'] >= 15)
            echo("Buonasera, ".$name."!");
        else
            echo("Buongiorno, ".$name."!");

    }

    function checkForm($getref, $postref, $postkeys, $getkeys){
        $STATUS = [];

        for ($i = 0; $i < count($getkeys); $i++)
            if (isset($getref[$getkeys[$i]]))
                $STATUS[$getkeys[$i]] = strlen(trim($getref[$getkeys[$i]])) === 0 ? 'empty' : trim($getref[$getkeys[$i]]);
            else
                $STATUS[$getkeys[$i]] = 'empty';
            
        
        

        for ($i = 0; $i < count($postkeys); $i++)
            if (isset($postref[$postkeys[$i]]))
                $STATUS[$postkeys[$i]] = strlen(trim($postref[$postkeys[$i]])) === 0 ? 'empty' : trim($postref[$postkeys[$i]]);
             else
                $STATUS[$postkeys[$i]] = 'empty';

        return $STATUS;
    }

?>