<?php

    $TYPES = ["follow" => 0, "unfollow" => 1];

    if(isset($_GET['type']) && isset($_GET['targetuser'])){
        session_start();
        $current_user = $_SESSION['user'];
        
        $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());
        $procedure = mysqli_real_escape_string($conn, trim($_GET['type']));
        $target = mysqli_real_escape_string($conn, trim($_GET['targetuser']));

        switch($TYPES[$procedure]){
            case 0:
                $query = "CALL FOLLOW ('".$current_user."', '".$target."')";
                break;
            case 1:
                $query = "CALL UNFOLLOW ('".$current_user."', '".$target."')";
                break;
        }

        if (isset($query))
            mysqli_query($conn, $query) or die (mysqli_error($conn));

        mysqli_close($conn);
    }
?>