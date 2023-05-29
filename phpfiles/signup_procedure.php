<?php
    require_once("user_utils.php");
    function user_signup(){
        global $_POST;

        $postkeys = array("nome", "cognome", "user", "email", "pwd1", "pwd2");

        $STATUS = checkForm(array(), $_POST, $postkeys, array());
        $TO_RETURN['registration'] = false;

        foreach($postkeys as $key)
            if ($STATUS[$key] === 'empty')
                $TO_RETURN['errors'][$key] = 'empty';
            

       
            $conn = mysqli_connect("localhost", "root", "", "homework1") or die(mysqli_connect_error());
            $TO_REGISTER = [];
            foreach ($STATUS as $key => $value)
                $TO_REGISTER[$key] = mysqli_real_escape_string($conn, $value);

            if (strlen($TO_REGISTER['user']) > 12)
                $TO_RETURN['errors']['user'] = 'too long';

            if (!filter_var($TO_REGISTER['email'], FILTER_VALIDATE_EMAIL))
                $TO_RETURN['errors']['email'] = 'not valid';
            
            
            if ($TO_REGISTER['pwd1'] !== $TO_REGISTER['pwd2'])
                $TO_RETURN['errors']['pwd2'] = 'not equal';
            
            $pwd_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[,.:-_!?<>+^@]).{10,}$/";

            if (!preg_match($pwd_regex, $TO_REGISTER['pwd1']))
                $TO_RETURN['errors']['pwd1'] = "not valid";
            if (strlen($TO_REGISTER['pwd1']) < 10)
                $TO_RETURN['errors']['pwd1'] = "too short";

            if (!isset($TO_RETURN['errors']['user'])){
                $user_query = "SELECT * FROM USERS WHERE Username = '".$TO_REGISTER['user']."'";   
                $user_res = mysqli_query($conn, $user_query) or die(mysqli_error($conn));
                $user_rows = mysqli_num_rows($user_res);
                if($user_rows > 0){ 
                    $TO_RETURN['errors']['user'] = 'unavailable';
                }
                mysqli_free_result($user_res);

            }

            if (!isset($TO_RETURN['errors']['email'])){
                $email_query = "SELECT * FROM USERS WHERE Email = '".$TO_REGISTER['email']."'";
                $email_res = mysqli_query($conn, $email_query) or die(mysqli_error($conn));
                $email_rows = mysqli_num_rows($email_res);
                if ($email_rows > 0){
                    $TO_RETURN['errors']['email'] = 'unavailable';
                }
                mysqli_free_result($email_res);
            }
            if(!isset($TO_RETURN['errors'])){
                $query = "CALL REGISTRATION('".$TO_REGISTER['nome']."', '".$TO_REGISTER['cognome']."', '".$TO_REGISTER['user']."', '".$TO_REGISTER['email']."', '".password_hash($TO_REGISTER['pwd1'], PASSWORD_BCRYPT)."')";
                mysqli_query($conn, $query) or die(mysqli_error($conn));
                $TO_RETURN['registration'] = true;
            }

            mysqli_close($conn);

        return $TO_RETURN;
    }

?>