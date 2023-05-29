<?php
    session_start();

    $STATUS = [];
    $TYPES = ["upload-propic" => 0, "update-motto" => 1];

    if (isset($_GET['type'])){
        $conn = mysqli_connect('localhost', 'root', '', 'homework1') or die (mysqli_connect_error());
        $type = mysqli_real_escape_string($conn, trim($_GET['type']));
        switch ($TYPES[$type]){
            case 0:
                if ($_FILES['propic']['error'] !== 0)
                    $STATUS['error'] = "Errore nel caricamento del file";
                else{
                    if ($_FILES['propic']['size'] !== 0){
                        $new_pic = $_FILES['propic'];
                        $format = exif_imagetype($new_pic['tmp_name']);
                        $allowed_formats = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_GIF => 'gif');
                        if (isset($allowed_formats[$format])){  
                            if($new_pic['size'] < 5000000){
                                $file_dest = "../images/propics/".uniqid($_SESSION['user'], false).".".$allowed_formats[$format];
                                $STATUS['filemove'] = move_uploaded_file($new_pic['tmp_name'],$file_dest);
                                $query = "UPDATE USERS SET Propic = './homework1/".$file_dest."' WHERE Username = '".$_SESSION['user']."'";
                                mysqli_query($conn, $query);

                                $STATUS['success'] = "Immagine aggiornata con successo!";
                                $STATUS['propic'] = "./homework1/".$file_dest;
                            }
                            else $STATUS['error'] = "L'immagine non deve superare i 5MB!";
                            
                        }
                        else
                            $STATUS['error'] = "L'immagine dev'essere in formato .png, .jpg o .gif!";
                    }
                    else
                        $STATUS['error'] = 'Immagine non caricata!';    
                    }
                    break;
            case 1:
                if (isset($_POST['upd-motto'])){
                    $motto = mysqli_real_escape_string($conn, trim($_POST['upd-motto']));

                    if(strlen($motto) > 255)
                        $STATUS['error'] = "Il tuo Motto non può superare i 255 caratteri!";
                    else{
                        $query = "UPDATE USERS SET Motto = '".$motto."' WHERE Username = '".$_SESSION['user']."'";
                        mysqli_query($conn, $query);
                        $STATUS['motto'] = '"'.trim($_POST['upd-motto']).'"';
                        $STATUS['success'] = "Motto aggiornato con successo!";
                    }
                }
                else 
                    $STATUS['error'] = "Motto non impostato!";
                break;
        }
    }

    mysqli_close($conn);
    echo(json_encode($STATUS));
?>