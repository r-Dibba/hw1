<?php
include_once("phpfiles/login_procedure.php");
include_once("phpfiles/user_utils.php");
$current_user_data = getUserData(home_session("login.php"));
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Diapason - Pubblica </title>
    <link rel = "stylesheet" href = "stylesheets/home_header.css">
    <link rel = "stylesheet" href = "stylesheets/post.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "scripts/home.js" defer = "true"></script>
    <script src = "scripts/post.js" defer = "true"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
     <?php
      require_once("header.php");
    ?>

    <h2>

      Pubblica qualcosa!

    </h2>

    <article>
      <section id = 'post'>
        <form name = 'form-post' method = 'post'>
          <input type = 'text' name = 'post-title' placeholder = "Titolo del post" id = 'post-title' autocomplete = "off">
          <div id = 'review-container'>
            <label class = 'row-lab'><input type = "checkbox" name = 'is-review' >Recensione </label>
            <div id = review>
              <label class = 'col-lab'>
                <img id = 'default-album' src = 'images/placeholder_icon.svg'>
                <input type = 'text' name = 'album-title' placeholder = "titolo dell'album" data-review = "true" autocomplete = "off">
                <input type = 'text' name = 'album-artist' placeholder = "artista" data-review = "true" autocomplete = "off">
                <input type = 'hidden' name = 'cover-url' data-review = "true">
              </label>
              <label class = 'row-lab'> Voto: <input type = 'number' name = 'score' min = '0' max = '100' data-type = 'score' data-review = "true">/100</label>
              <div id = "overlay"></div>
            </div>
          </div>
          <textarea name = 'desc' placeholder = 'A cosa stai pensando?' value = ''></textarea>
          <input type = 'submit' value = 'PUBBLICA' class = 'ok-button'>
        </form>
      </section>
      <section id = 'post-modal' class = 'hidden modal'>
        <div id = 'in-modal'>
          <h1></h1>
          <p></p>

          <div class = 'ok-button'>Ok</div> 
        </div>
      </section>
    </article>
  </body>
</html>
