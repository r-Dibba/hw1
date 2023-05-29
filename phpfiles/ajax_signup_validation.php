<?php
$TO_RETURN = [];
if (isset($_GET['type']) && isset($_GET['to-check'])){
    $conn = mysqli_connect("localhost", "root", "", "homework1") or die (mysqli_connect_error());
    $type = $_GET['type'];
    $toSearch = mysqli_real_escape_string($conn, trim($_GET['to-check']));

    $TO_RETURN['type'] = $type;

    if (strlen($toSearch) === 0)
        $TO_RETURN['code'] = 'empty';
    else{
        if ($type === 'user')
            $query = "SELECT * FROM USERS WHERE Username = '".$toSearch."'";
        else if ($type === 'email')
            $query = "SELECT * FROM USERS WHERE Email = '".$toSearch."'";
        
        if (isset($query)){
            $res = mysqli_query($conn, $query) or die (mysqli_error($conn));
            $num_rows = mysqli_num_rows($res);
            if ($num_rows > 0)
                $TO_RETURN['code'] = 'unavailable';
            else
                $TO_RETURN['code'] = 'none';
            mysqli_free_result($res);
            mysqli_close($conn);
        }
    }
}

echo(json_encode($TO_RETURN));
?>