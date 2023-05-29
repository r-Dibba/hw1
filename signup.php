<?php
  require_once("./phpfiles/user_utils.php");
  include_once("phpfiles/signup_procedure.php");

  $status = user_signup();

  if ($status['registration']){
    header("Location: login.php");
    exit;
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Diapason - Registrati </title>
    <link rel = "stylesheet" href = "stylesheets/signup.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <script src = "./scripts/signup_validation.js" defer = "true"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
  </head>
  <body>
    <div id = "header-container">
      <header class = "prova test">
        <div id = "overlay"></div>
        <div id = "title-container">
          <img id = "logo" src = "images/diapason_logo.svg">
          <h1 id = "header-title">
            Diapason
          </h1>
        </div>
    
        <p id = "header-desc">
          Registrati per usufruire delle funzionalità di <em>Diapason</em><br> Interagisci con appassionati di musica da tutto il mondo!
        </p>
      </header>
    </div>
    <article>
      <p id = "other-page">
        Hai già un account?<br>
        <a href = "login.php">Clicca qui per accedere</a>
      </p>
      <form method = "post" name = 'signup'>
        <div class = "form-element">
          <label> Nome <br><input type = 'text' name = 'nome' placeholder="Nome" <?php if (isset($_POST['nome'])) echo("value='".$_POST['nome']."'"); ?>>
          <?php
            
            if(isset($status['errors']['nome']) && isset($_POST['nome'])){
              echo("<p class = 'error' >");
              if ($REG_ERROR_CODES[$status['errors']['nome']] === 0)
                echo("Inserisci il tuo Nome!");
              
              echo("</p>");
            }
          ?>
          </label>
          <label> Cognome <br><input type = 'text' name = 'cognome' placeholder="Cognome" <?php if (isset($_POST['cognome'])) echo("value='".$_POST['cognome']."'"); ?>>
          <?php
            
            if(isset($status['errors']['cognome'])&& isset($_POST['cognome']) ){
              echo("<p class = 'error' >");
              if ($REG_ERROR_CODES[$status['errors']['cognome']] === 0)
                echo("Inserisci il tuo Cogome!");
              
              echo("</p>");
            }
          ?>
        </div>
  
        <div class = "form-element">
          <label> E-mail <br><input type = 'email' name = 'email' placeholder="indirizzo@email.com" <?php if (isset($_POST['email'])) echo("value='".$_POST['email']."'"); ?>>
          <?php
            if(isset($status['errors']['email']) && isset($_POST['email']) ){
              echo("<p class = 'error'>");
              
              switch($REG_ERROR_CODES[$status['errors']['email']]){
                case 0:
                  echo ("Inserisci la tua email!");
                  break;
                case 3:
                  echo ("Email non valida");
                  break;
                case 4:
                  echo ("Email già in uso");
                  break;
              }
              
              echo("</p>");
            }
          ?>
          </label>

          <label> Username <br><input type = 'text' name = 'user' <?php if (isset($_POST['user'])) echo("value='".$_POST['user']."'"); ?>>
          <?php
            
            if(isset($status['errors']['user']) && isset($_POST['user']) ){
              echo("<p class = 'error' >");
              switch($REG_ERROR_CODES[$status['errors']['user']]){
                case 0:
                  echo ("Inserisci un nome utente!");
                  break;
                case 1:
                  echo ("L'username dev'essere al più di 12 caratteri");
                  break;
                case 4:
                  echo ("Username già in uso");
                  break;
              }
              
              echo("</p>");
            }
          ?>
          </label>

        </div>
  
        <div class = "form-element">
          <label> Password <br><input type = 'password' name = 'pwd1'></label>
          <label> Ripeti Password <br><input type = 'password' name = 'pwd2'></label>
        </div>
        <div id = "password-error">
        <?php
            
            if(isset($status['errors']['pwd1']) && isset($_POST['pwd1']) ){
              echo("<p class = 'error' >");
              switch($REG_ERROR_CODES[$status['errors']['pwd1']]){
                case 0:
                  echo ("Inserisci una Password!");
                  break;
                case 2:
                  echo ("La password deve contenere almeno 10 caratteri");
                  break;
                case 3:
                  echo ("La password deve contenere almeno una maiuscola, una minuscola, un numero e uno fra i seguenti caratteri speciali: 
                  , . : - _ ! ? < > + ^ @");
                  break;
              }
              
              echo("</p>");
            }
            else if(isset($status['errors']['pwd2']) && isset($_POST['pwd2']) ){
              echo("<p class = 'error' >");
              switch($REG_ERROR_CODES[$status['errors']['pwd2']]){
                case 5:
                  echo ("Le password non corrispondono");
                  break;
                
              }
              
              echo("</p>");
            }
          ?>
        </div>
  
        <div id = 'submit-container'><input type = 'submit' value = 'REGISTRATI' id = 'submit'></div>
      </form>
    </article>
  </body>
</html>
