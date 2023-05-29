<?php

    $TO_RETURN = array();
    $TYPES = ["like" => 0, "followed-by" => 1, "follows" => 2, "like-chat" => 3, "followed-by-chat" => 4, "follows-chat" => 5, "unread" => 6, "is-followed" => 7];

    if (isset($_GET['type'])){
        session_start();
        $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());
        $type = mysqli_real_escape_string($conn, trim($_GET['type']));
        $toSearch = mysqli_real_escape_string($conn, trim($_GET["user-search"]));
        $current_user = mysqli_real_escape_string($conn, trim($_SESSION["user"]));
        $limit =  mysqli_real_escape_string($conn, trim($_GET["limit"]));
        $offset = mysqli_real_escape_string($conn, trim($_GET["offset"]));
        
        switch($TYPES[$type]){

            case 0:
                $query = "SELECT U.Nome, U.Cognome, U.Username, U.Propic, F.Follows 
                    FROM USERS U LEFT JOIN FOLLOWERS F
                        ON F.Follows = U.Username AND F.Username = '".$current_user."'
                    WHERE U.Username LIKE '%".$toSearch."%' AND U.Username != '".$current_user."' LIMIT ".$limit." OFFSET ".$offset;
                break;

            case 1:
                $query = "SELECT U.Nome, U.Cognome, U.Username, U.Propic, F.Follows
                FROM USERS U LEFT JOIN FOLLOWERS F
                    ON F.Follows = U.Username AND F.Username = '".$current_user."'
                WHERE U.Username IN (
                    SELECT Username FROM FOLLOWERS WHERE Follows = '".$toSearch."'	
                )
                AND U.Username != '".$current_user."'
                LIMIT ".$limit."
                OFFSET ".$offset; 
                break;
            case 2:
                $query = "SELECT U.Nome, U.Cognome, U.Username, U.Propic, F.Follows
                FROM USERS U LEFT JOIN FOLLOWERS F
                    ON F.Follows = U.Username AND F.Username = '".$current_user."'
                WHERE U.Username IN (
                    SELECT Follows FROM FOLLOWERS WHERE Username = '".$toSearch."'
                )
                AND U.Username != '".$current_user."'
                LIMIT ".$limit."
                OFFSET ".$offset;
                break;
            case 3:
                $query = "SELECT DISTINCT U.Nome, U.Cognome, U.Username, U.Propic, COUNT(T.Isread) AS amtUnread
                FROM USERS U LEFT JOIN(
                    SELECT Sender, Isread
                    FROM MESSAGES M
                    WHERE M.Sender = '".$current_user."') AS T
                ON U.Username = T.Sender 
                WHERE U.Username LIKE '%".$toSearch."%'
                AND U.Username != '".$current_user."'
                GROUP BY U.Username
                LIMIT ".$limit."
                OFFSET ".$offset;
                break;
            case 4:
                $query = "SELECT DISTINCT U.Nome, U.Cognome, U.Username, U.Propic, COUNT(T.Isread) AS amtUnread
                FROM USERS U LEFT JOIN(
                    SELECT Sender, Isread
                    FROM MESSAGES M
                    WHERE M.Receiver = '".$current_user."'
                    AND Isread = 0) AS T
                ON U.Username = T.Sender 
                WHERE U.Username IN (
                    SELECT Username FROM FOLLOWERS WHERE Follows = '".$toSearch."'	
                )
                AND U.Username != '".$current_user."'
                GROUP BY U.Username
                LIMIT ".$limit."
                OFFSET ".$offset;
                break;
            case 5:
                $query = "SELECT DISTINCT U.Nome, U.Cognome, U.Username, U.Propic, COUNT(T.Isread) AS amtUnread
                FROM USERS U LEFT JOIN(
                    SELECT Sender, Isread
                    FROM MESSAGES M
                    WHERE M.Receiver = '".$current_user."'
                    AND Isread = 0) AS T
                ON U.Username = T.Sender 
                WHERE U.Username IN (
                    SELECT Follows FROM FOLLOWERS WHERE Username = '".$toSearch."'
                )
                AND U.Username != '".$current_user."'
                GROUP BY U.Username
                LIMIT ".$limit."
                OFFSET ".$offset;

                break;
            case 6:
                $query = "SELECT DISTINCT U.Nome, U.Cognome, U.Username, U.Propic, COUNT(T.Isread) AS amtUnread
                FROM USERS U JOIN(
                    SELECT Sender, Isread
                    FROM MESSAGES M
                    WHERE M.Receiver = '".$current_user."'
                    AND M.Isread = 0) AS T
                ON U.Username = T.Sender 
                GROUP BY U.Username
                LIMIT ".$limit."
                OFFSET ".$offset;
                break;
            case 7:
                $query = "SELECT * FROM FOLLOWERS WHERE Username = '".$current_user."' AND Follows = '".$toSearch."'";
                break;
        }
        $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
        
        for($record = mysqli_fetch_assoc($res), $i = 0; $record != null; $record = mysqli_fetch_assoc($res), $i++)
            $TO_RETURN[$i] = $record;

        mysqli_free_result($res);
        mysqli_close($conn);

    }

    echo (json_encode($TO_RETURN));
?>