<?php
    $TYPES = ["get-msg" => 0, "get-other" => 1, "check-other" => 2];

    $TO_RETURN = [];
    session_start();

    if (isset($_GET['type']) && isset($_SESSION['user'])){
        
        $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());
        
        $type = trim($_GET['type']);
        $current_user = $_SESSION['user'];

        $check = false;


        switch($TYPES[$type]){
            case 0:
                $query = "SELECT COUNT(*) AS amt FROM MESSAGES WHERE Receiver = '".$current_user."' AND Isread = 0";
                break;
            case 1:
                $query = "SELECT COUNT(*) AS amt FROM NOTIFICATIONS WHERE notified_user = '".$current_user."'";
                break;
            case 2:
                $query = "DELETE FROM NOTIFICATIONS WHERE notified_user = '".$current_user."'";
                $check = true;
                break;
        }

        if (!$check){
            $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
        
            $record = mysqli_fetch_assoc($res);
            $TO_RETURN['type'] = $type;
            $TO_RETURN['res'] = $record;
        }
        else{
            mysqli_query($conn, $query) or die (mysqli_error($conn));
        }

        if (isset($res))
            mysqli_free_result($res);
        
        mysqli_close($conn);

    }

    echo(json_encode($TO_RETURN));
?>