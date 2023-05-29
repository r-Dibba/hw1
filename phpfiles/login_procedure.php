<?php

    function log_user(){
        global $_POST;

        $TO_RETURN['login'] = false;

        

        if (isset($_POST["user"]) && isset($_POST["pwd"])){

            $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());

            $user = mysqli_real_escape_string($conn, trim($_POST["user"]));
            $pwd = mysqli_real_escape_string($conn, trim($_POST["pwd"]));

            $query = "SELECT * FROM USERS WHERE Username = '".$user."'";
            $res = mysqli_query($conn, $query) or die (mysqli_error($conn));

            if (mysqli_num_rows($res) === 0)
                $TO_RETURN['error'] = true;
            
            else{
                $record = mysqli_fetch_object($res);

                if(!password_verify($pwd, $record->Loginkey))
                    $TO_RETURN['error'] = 'wrong pwd';
                else{
                    $_SESSION["user"] = $record->Username;
                    $TO_RETURN['error'] = true;
                    $TO_RETURN['login'] = true;
                }
            }

            mysqli_free_result($res);
            mysqli_close($conn);
            
            
        }

        return $TO_RETURN;

    }

    function session_redirect($location){

        session_start();

        if (isset($_SESSION["user"])){ 
            header("Location: ".$location);
            exit;
        }
    }

    function home_session($location){
        session_start();

        if (!isset($_SESSION["user"])){
            header("Location: ".$location);
            exit;
        }

        return $_SESSION["user"];
    }

?>