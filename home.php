<?php
    include_once("phpfiles/login_procedure.php");
    include_once("phpfiles/user_utils.php");
    $current_user_data = getUserData(home_session("login.php"));
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Diapason - Home </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    
    <link rel = "stylesheet" href = "stylesheets/search_posts.css">

    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    
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

    <h2 id = "search-info">

      <?php 

        welcome_user($current_user_data->Nome);
      
      ?>

    </h2>


    <article>
    <h2 id = 'post-info'> Ultimi post dei tuoi seguiti: </h2>
    <section id = "show-posts">

    </section>
    </article>
  </body>
</html>
