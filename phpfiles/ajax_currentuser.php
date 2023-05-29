<?php

    include_once("user_utils.php");
    session_start();
    $user = json_encode(getUserData($_SESSION["user"]));

    echo($user);

?>