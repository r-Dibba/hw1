<?php
$TO_RETURN['append-to']['status'] = false;
$TO_RETURN['results'] = [];

session_start();

$TYPES = ["upvote" => 0, "downvote" => 1, "comment" => 2, "delete" => 3, "load-comments" => 5];

if (isset($_GET['type']) && isset($_GET['target-post']) && isset($_SESSION['user'])){
    $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());
    $type = trim($_GET['type']);
    $post_id = mysqli_real_escape_string($conn, trim($_GET['target-post']));
    $current_user = mysqli_real_escape_string($conn, $_SESSION['user']);

    switch($TYPES[$type]){
        case 0:
            $query = "CALL SET_INTERACTION ('".$current_user."', '".$post_id."', 'upv')";
            break;
        case 1:
            $query = "CALL SET_INTERACTION ('".$current_user."', '".$post_id."', 'dwn')";
            break;
        case 2:
            if (isset($_POST['comment'])){
                $comment = mysqli_real_escape_string($conn, trim($_POST['comment']));
                if (strlen($comment) > 0 && strlen($comment) <= 255)
                    $query = "CALL ADD_COMMENT('".$current_user."', '".$post_id."', '".$comment."')";
            }       
            break;
        case 3:
            $query = "CALL DEL_INTERACTION ('".$current_user."', '".$post_id."')";
            break;
        case 5:
            $query = "SELECT C.author, C.comment, C.comment_date, U.Propic FROM COMMENTS C JOIN USERS U ON C.author = U.Username WHERE post_id = '".$post_id."' ORDER BY C.comment_date DESC LIMIT 9 OFFSET 0";
            $TO_RETURN['append-to']['status'] = true;
            $TO_RETURN['append-to']['id'] = $post_id;
            break;
    }
    if (isset($query)){
        $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
        if ($TO_RETURN['append-to']['status'] === true){
            for($i = 0, $record = mysqli_fetch_assoc($res); $record !== null ;$i++, $record = mysqli_fetch_assoc($res))
                $TO_RETURN['results'][$i] = $record;
            mysqli_free_result($res);
        }

    }
    mysqli_close($conn);

}

echo (json_encode($TO_RETURN));
?>