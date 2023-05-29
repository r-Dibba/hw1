<?php
    include_once("phpfiles/login_procedure.php");
    include_once("phpfiles/user_utils.php");
    $current_user_data = getUserData(home_session("login.php"));
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Diapason - Chat </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    <link rel = "stylesheet" href = "stylesheets/search_results.css">
    <link rel = "stylesheet" href = "stylesheets/user_profile.css">
    <link rel = "stylesheet" href = "stylesheets/chat.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    <script src = "scripts/search_users.js" defer = "true"></script>
    <script src = "scripts/user_profile.js" defer = "true"></script>
    <script src = "scripts/chat.js" defer = "true"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
    <?php
      require_once("header.php");
    ?>

    <h2 id = 'page-info'>

      <?php 

        echo("Chatta con gli utenti di <em>Diapason</em>");      
      ?>

    </h2>

    <article>
      <section id = "show-chat">

      <div id = "friend-search">
        <form name = "chat-search" method = "get" >
          <input type = 'text' name = 'user-search' placeholder = 'cerca utenti' id = 'search-box' autocomplete = "off">
          <input type = 'submit' value = '' id = 'search-submit'>
        </form>
        <div id = 'filters'>
          <p class = 'flw-info' data-flw-info = 'followed-by-chat'>
            Follower
          </p>
          <p class = 'flw-info' data-flw-info = 'follows-chat'>
            Seguiti
          </p>
          <p class = 'flw-info' data-flw-info = 'unread'>
            Non Letti
          </p>
        </div>

      <div id = "friend-list">
      </div>
        
      </div>

      <div id = chatbox>
        <h2></h2>
        <div id = "conversation">
        </div>

        <form name = 'sendmsg'>
          <input type = "text" name = "message" placeholder = "scrivi..." class = "user-searchbox" autocomplete = 'off'>
          <input type = 'hidden' name = 'target-user'>
          <input type = 'submit' name = 'send' value = '' class = "searchbox-send">
        </form>
      </div>
        

      </section>
      <section id = 'fullinbox-modal' class = 'hidden modal'>
        <div id = 'in-modal'>
          <h1>Rallenta!</h1>
          <p>Sembra che <span></span> sia impegnato al momento.</p>
          <p>Aspetta che visualizzi i messaggi prima di inviarne altri!</p>

          <div class = 'ok-button'>Ok</div> 
        </div>
      </section>

    </article>
  </body>
</html>
