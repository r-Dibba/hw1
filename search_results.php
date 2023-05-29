<?php
    include_once("phpfiles/login_procedure.php");
    home_session("login.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Diapason - Cerca </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    <link rel = "stylesheet" href = "stylesheets/search_results.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
    <header>
      
      <div id = "header-left">
        <img id = "user-img" src = "images/svgicons/user.svg">
        <img id = "message-notifications" src = "images/pngicons/msg.png">
        <img id = "content-notifications" src = "images/pngicons/notifications.png">
        <form name = "search" method = "get">
          <input type = 'text' name = 'user-search' placeholder = 'cerca utenti' id = 'search-box'>
          <input type = 'submit' value = '' id = 'search-submit'>
        </form>
      </div>
      
      <div id = "header-center">
        <img id = "site-logo" src = "images/pngicons/diapason_logo_v2.png">
        <h1 id = "header-title">Diapason</h1>
      </div>
     
      <nav>
        <div class = "white-key">
          <a href = "home.php#" class = "nav-item">
            <img src = "images/pngicons/home.png">
            HOME
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "account.php#" class = "nav-item">
            <img src = "images/pngicons/user.png">
            ACCOUNT
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "chat.php#" class = "nav-item">
            <img src = "images/pngicons/chat.png">
            CHAT
          </a>
        </div>
        <div class = "white-key">
          <a href = "crea.php#" class = "nav-item">
            <img src = "images/pngicons/add.png">
            PUBBLICA
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "eventi.php#" class = "nav-item">
            <img src = "images/pngicons/events.png">
            EVENTI
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "questionari.php#" class = "nav-item">
            <img src = "images/pngicons/quest.png">
            QUESTIONARI
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "logout.php#" class = "nav-item">
            <img src = "images/pngicons/logout.png">
            LOGOUT
          </a>
        </div>
      </nav>
    
    </header>

    <h1 id = "found">
    </h1>

    <article class = "found-users">
  

      <section>

      
      </section>

    </article>

  </body>
</html>
