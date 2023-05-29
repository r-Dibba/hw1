<?php
    include_once("phpfiles/login_procedure.php");
    include_once("phpfiles/user_utils.php");
    $current_user_data = getUserData(home_session("login.php"));
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Cerca Eventi </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    <link rel = "stylesheet" href = "stylesheets/events.css">

    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    <script src = "scripts/events.js" defer = "true"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
     <?php
      require_once("header.php");
    ?>

    <h2 id = "search-info">
        Cerca eventi:
    </h2>

    <div id = "search-container">
            <p id = 'toggle-search'>Cerca Per Paese </p>
            <form name = "artist-search" method = "get" data-type = "artist">
            <input type = 'text' name = 'filter' placeholder = 'cerca artisti' id = 'search-box'>
            <input type = 'submit' value = '' id = 'search-submit'>
            </form>
        </div>

    <article>
      <section>
      </section>

    </article>
  </body>
</html>
