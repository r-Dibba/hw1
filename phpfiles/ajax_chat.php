<?php
    session_start();
   
    $TO_RETURN = [];
    $TYPES = ["load-conv" => 0, "send-msg" => 1, "listen" => 2, "mark-as-read" => 3];

    if (isset($_GET['type'])){

        $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());
        $current_user = $_SESSION["user"];
        $type = $_GET['type'];

        switch($TYPES[$type]){
            case 0:
                if (isset($_GET['target-user'])){

                    $target_user = mysqli_real_escape_string($conn, trim($_GET['target-user']));

                    $query = "SELECT *
                    FROM MESSAGES 
                    WHERE Receiver = '".$current_user."'
                    AND Sender = '".$target_user."'
                    AND Isread = 0
                    ORDER BY Datesent DESC
                    LIMIT 10";
                    $res = mysqli_query($conn, $query) or die (mysqli_error($conn));

                    $readquery = "UPDATE MESSAGES M
                    SET M.Isread = 1
                    WHERE Receiver = '".$current_user."'
                    AND Sender = '".$target_user."'"; 
                    $readres = mysqli_query($conn, $readquery) or die (mysqli_error($conn));
                    
                    for ($i = 0, $record = mysqli_fetch_assoc($res); $record != null; $record = mysqli_fetch_assoc($res), $i++)
                        $TO_RETURN[$i] = $record;

                    $query = "SELECT *
                    FROM MESSAGES
                    WHERE Sender = '".$current_user."'
                    AND Receiver = '".$target_user."' 
                    ORDER BY Datesent DESC
                    LIMIT 10"; 
                    $res = mysqli_query($conn, $query);

                    for ($j = 0, $record = mysqli_fetch_assoc($res); $record != null && $j < $i; $record = mysqli_fetch_assoc($res), $j++)
                        $TO_RETURN[($i + $j)] = $record;

                    sort($TO_RETURN);
                    mysqli_free_result($res);
                
                }
                break;
            
            case 1:
                if (isset($_POST['message']) && isset($_POST['target-user'])){

                    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
                    $target_user = mysqli_real_escape_string($conn, trim($_POST['target-user']));

                    $query = "SELECT COUNT(*) FROM MESSAGES WHERE Isread = 0 AND Sender = '".$current_user."' AND Receiver = '".$target_user."'";
                    $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
                    $amt = mysqli_fetch_column($res);

                    $TO_RETURN['status'] = false;
                    $TO_RETURN['message'] = null;
                    $TO_RETURN['unread'] = $amt;
            
                    if ($amt < 10){

                        $query = "CALL SEND_MSG('".$current_user."', '".$target_user."', '".$message."')";
                        $res = mysqli_query($conn, $query) or die (mysqli_error($conn));

                        $TO_RETURN['status'] = true;
                        $TO_RETURN['message'] = $message;
                        $TO_RETURN['unread'] = $amt + 1;
                    }
                
                }
                break;

            case 2:
                if (isset($_GET['target-user'])){
                    $target_user = mysqli_real_escape_string($conn, trim($_GET['target-user']));  

                            $query = "SELECT *
                            FROM MESSAGES 
                            WHERE Receiver = '".$current_user."'
                            AND Sender = '".$target_user."'
                            AND Isread = 0
                            ORDER BY Datesent DESC
                            LIMIT 10";
                        
                            $res = mysqli_query($conn, $query);
                            $amt = mysqli_num_rows($res);

                            if ($amt > 0){  
                                $readquery = "UPDATE MESSAGES M
                                SET M.Isread = 1
                                WHERE Receiver = '".$current_user."'
                                AND Sender = '".$target_user."'"; 
                                $readres = mysqli_query($conn, $readquery) or die (mysqli_error($conn));
                                
                                for ($i = 0, $record = mysqli_fetch_assoc($res); $record != null; $record = mysqli_fetch_assoc($res), $i++)
                                    $TO_RETURN[$i] = $record;
                            }
                            mysqli_free_result($res);
                            
                }
                break;

            case 3:

                if (isset($_GET['target-user'])){

                    $target_user = mysqli_real_escape_string($conn, trim($_GET['target-user']));


                    $readquery = "UPDATE MESSAGES M
                    SET M.Isread = 1
                    WHERE Receiver = '".$current_user."'
                    AND Sender = '".$target_user."'"; 
                    $readres = mysqli_query($conn, $readquery) or die (mysqli_error($conn));
                }
                break;

        }
        

        mysqli_close($conn);

    }

    echo(json_encode($TO_RETURN));
?>