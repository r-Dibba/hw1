<?php
  include_once("phpfiles/login_procedure.php");
  include_once("phpfiles/user_utils.php");
  $current_user_data = getUserData(home_session("login.php"));

  $toFind = (isset($_GET["clicked-profile"])) ? $_GET["clicked-profile"] : $_SESSION["user"];
  $user_shown = getUserData($toFind);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Account - <?php echo($user_shown->Username) ?>  </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    <link rel = "stylesheet" href = "stylesheets/search_results.css">
    <link rel = "stylesheet" href = "stylesheets/search_posts.css">
    <link rel = "stylesheet" href = "stylesheets/user_profile.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    <script src = "scripts/search_users.js" defer = "true"></script>
    <script src = "scripts/chat.js" defer = "true"></script>
    <script src = "scripts/user_profile.js" defer = "true"></script>
    <script src = "scripts/search_posts.js" defer = "true"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
    
    <?php
      require_once("header.php");
    ?>

    <article>
        <div id = "user-banner" >
            <div class = "banner-row">

                <div id = "banner-left">
                    <img src = <?php echo($user_shown->Propic); ?> class = 'currentuser-propic'><br>
                    <p id = "rank">
                        Rank: <br>
                        <em> <?php echo(getRank($user_shown->SignupDate)); ?></em>
                    </p>
                </div>

                <div id = "banner-right">
                    <p id = "motto">
                        <?php echo('"'.$user_shown->Motto).'"'; ?>
                    </p>
                    <h6>
                      <?php echo('~ '.$user_shown->Username); ?>
                    </h6>
                </div>

            </div>

            <div class = "banner-row">
                <div id = "bottom-left">
                    <?php

                    if ($toFind !== $_SESSION['user']){
                      echo("<img src = 'images/svgicons/chat.svg' id = 'banner-quick-msg'>");

                      echo("<img src = 'images/svgicons/add_user.svg' data-user = '".$user_shown->Username."' id = 'banner-add-friend'>");

                    }
                    else
                      echo("<img src = 'images/svgicons/settings.svg' id = 'acc-settings'>");


                    ?>
                </div>

                <div id = "bottom-center">
                    <p class = "flw-info" data-flw-info = 'followed-by'>
                        Follower
                        <span><?php echo($user_shown->AmtFollowedBy); ?></span>
                  </p>

                    <p class = "flw-info" data-flw-info = 'follows'>
                        Seguiti
                        <span><?php echo($user_shown->AmtFollows); ?></span>
                  </p>
                </div>

                <div id = "bottom-right">
                    <p id = "show-post">
                        Mostra Post
                    </p>
                </div>
            </div>
        </div>

        <h2 id = "search-info">
        </h2>
      
      <section>
      
      </section>
      <?php
      if($user_shown->Username !== $_SESSION["user"]){
        echo(
          "<section id = 'send-msg-modal' class = 'modal hidden'>
        <div id = 'in-modal' class = 'quick-msg'>
        <h1>Invia un Messaggio!</h1>
        <form name = 'sendmsg'>
          <input type = 'text' name = 'message' placeholder = 'scrivi' class = 'user-searchbox' autocomplete = 'off'>
          <input type = 'hidden' name = 'target-user' value = ".$user_shown->Username.">
          <input type = 'submit' name = 'send' value = '' class = 'searchbox-send'>
        </form>
        <p>
        </p>
        <div class = 'ok-button hidden'>
          Chiudi
        </div>
        </div>
      </section>"
        );
      }
      else{
        echo(
          "<section id = 'acc-upd-modal' class = 'modal hidden'>
        <div id = 'in-modal' class = 'acc-settings'>
        <h1>Aggiorna Profilo</h1>
        <div id = 'forms-container'>
        <form name = 'form-propic' enctype = 'multipart/form-data' method = 'post'>
          <label> Aggiorna Immagine <img src = '".$current_user_data->Propic."' class = 'currentuser-propic'><input type = 'file' name = 'propic' class = 'hidden'></label>
          <input type = 'submit' name = 'send' value = 'aggiorna'>
        </form>
        <form name = 'form-motto' id = 'form-motto' method = 'post'>
          <label> Aggiorna Motto <textarea name = 'upd-motto' placeholder = '".$current_user_data->Motto."'></textarea> </label>
          <input type = 'submit' name = 'send' value = 'aggiorna'>
        </form>
        </div>
          
        <div id = 'upd-errors'>
        <p></p>
        <p></p>
        </div>
        
        <div class = 'ok-button'>
          Chiudi
        </div>
        </div>
      </section>"
        );
      }

      ?>
    </article>
  </body>
</html>
