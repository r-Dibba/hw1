<?php
    require_once("./user_utils.php");
    session_start();
    $TO_RETURN = [];

    if (!isset($_SESSION['user'])){
        $TO_RETURN['posted'] = false;
        $TO_RETURN['status'] = "Effettuare il Login";
    }
    else{
        $type = trim($_GET['type']);

            $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());
            $post_id = uniqid("", null);
            $TO_RETURN['type'] = $type;

            if($type === '0'){
                $post_keys = array("post-title", "album-title", "album-artist", "cover-url", "score", "desc");
                $form_res = checkForm(array(), $_POST, $post_keys, array());

                $check = true;
                
                foreach ($form_res as $k => $val)
                    if($val === 'empty'){
                        $check = false;
                        break;
                    }

                if (!$check){
                    $TO_RETURN['posted'] = false;
                    $TO_RETURN['status'] = 'Compila tutti i Campi!';
                }

                if (!preg_match('/^[0-9]*$/',$form_res['score'])){
                    $TO_RETURN['status'] = 'Inserisci un voto numerico';
                    $check = false;
                }
                
                else{

                    $query = "CALL ADD_REVIEW ('".$post_id."', '".$_SESSION['user']."', '".mysqli_real_escape_string($conn, $form_res['post-title'])."', '".mysqli_real_escape_string($conn, $form_res['desc'])."', '".mysqli_real_escape_string($conn, $form_res['album-artist'])."', '".mysqli_real_escape_string($conn, $form_res['album-title'])."', ".mysqli_real_escape_string($conn, $form_res['score']).", '".mysqli_real_escape_string($conn, $form_res['cover-url'])."')";
                    $TO_RETURN['posted'] = mysqli_query($conn, $query);
                }
            }
            else if($type === '1'){
                $post_keys = array("post-title", "desc");
                $form_res = checkForm(array(), $_POST, $post_keys, array());

                $check = true;
                
                foreach ($form_res as $k => $val)
                if($val === 'empty'){
                    $check = false;
                    break;
                }

                if (!$check){
                    $TO_RETURN['posted'] = false;
                    $TO_RETURN['status'] = 'Compila tutti i Campi!';
                }
                else{

                    $query = "CALL ADD_POST ('".$post_id."', '".$_SESSION['user']."', '".mysqli_real_escape_string($conn, $form_res['post-title'])."', '".mysqli_real_escape_string($conn, $form_res['desc'])."')";
                    $TO_RETURN['posted'] = mysqli_query($conn, $query);

                }
        }

        mysqli_close($conn);
    }

    echo(json_encode($TO_RETURN));

?>