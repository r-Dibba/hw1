<?php

require_once("./user_utils.php");
$TO_RETURN = [];
if (isset($_GET['type'])){
    $type = trim($_GET['type']);
    $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());
    session_start();

    if ($type === 'target'){
        
        $keys = array("target-user", "limit", "offset");
        $form_res = checkForm($_GET, array(), array(), $keys);

        $TO_RETURN['retrieved'] = true;
        foreach ($form_res as $k => $val)
            if ($val === 'empty')
                $TO_RETURN['retrieved'] = false;
        if ($TO_RETURN['retrieved'])
            $query = "SELECT U.Propic, P.ID, P.Author, P.Title, P.Textcontent, P.AmtLikes, P.AmtDislikes, P.PostedOn, R.Artist, R.Albumtitle, R.Score, R.Cover, L.Interaction
            FROM USERS U JOIN POST P
                ON U.Username = P.Author
            LEFT JOIN POST_REVIEW R
                ON R.PostID = P.ID
            LEFT JOIN (SELECT * FROM LIKES WHERE Username = '".mysqli_real_escape_string($conn, $_SESSION['user'])."') AS L
                ON L.TargetPost = P.ID
            WHERE P.Author = '".mysqli_real_escape_string($conn, $form_res['target-user'])."'
            ORDER BY P.PostedOn DESC LIMIT ".mysqli_real_escape_string($conn, $form_res['limit'])." OFFSET ".mysqli_real_escape_string($conn, $form_res['offset']);
        
    }
    else if ($type === 'friends'){
        $keys = array("limit", "offset");
        $form_res = checkForm($_GET, array(), array(), $keys);

        $TO_RETURN['retrieved'] = true;
        foreach ($form_res as $k => $val)
            if ($val === 'empty')
                $TO_RETURN['retrieved'] = false;
        if ($TO_RETURN['retrieved'] && isset($_SESSION['user'])){
            $query = "SELECT U.Propic, P.ID, P.Author, P.Title, P.Textcontent, P.AmtLikes, P.AmtDislikes, P.PostedOn, R.Artist, R.Albumtitle, R.Score, R.Cover, L.Interaction
            FROM USERS U JOIN POST P
                ON U.Username = P.Author
            LEFT JOIN POST_REVIEW R
                ON R.PostID = P.ID
            LEFT JOIN (SELECT * FROM LIKES WHERE Username = '".mysqli_real_escape_string($conn, $_SESSION['user'])."') AS L
            ON L.TargetPost = P.ID
            WHERE P.Author IN (
                SELECT Follows FROM FOLLOWERS WHERE Username = '".mysqli_real_escape_string($conn, $_SESSION['user'])."' 
                )
            ORDER BY P.PostedOn DESC LIMIT ".mysqli_real_escape_string($conn, $form_res['limit'])." OFFSET ".mysqli_real_escape_string($conn, $form_res['offset']);

        }
    }
    $res = mysqli_query($conn, $query) or die (mysqli_error($conn));

    for ($i = 0, $record = mysqli_fetch_assoc($res); $record !== null; $i++, $record = mysqli_fetch_assoc($res)){
        $TO_RETURN['posts'][$i] = $record;
        $TO_RETURN['posts'][$i]['query'] = $query;
    }
    mysqli_free_result($res);
    mysqli_close($conn);
}

echo(json_encode($TO_RETURN));

?>