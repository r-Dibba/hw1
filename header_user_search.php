<header>
      
      <div id = "header-left">
        <img id = "user-img" src = <?php echo($current_user_data->Propic); ?> >
        <img id = "message-notifications" src = "images/svgicons/msg.svg" data-type = 'msg'>
        <img id = "content-notifications" src = "images/svgicons/notifications.svg" data-type = 'oth'>
        <form name = "header-search" method = "get" >
          <input type = 'text' name = 'user-search' placeholder = 'cerca utenti' id = 'search-box' autocomplete = "off">
          <input type = 'submit' value = '' id = 'search-submit'>
        </form>
      </div>
      
      <div id = "header-center">
        <img id = "site-logo" src = "images/pngicons/diapason_logo_v2.png">
        <h1 id = "header-title">Diapason</h1>
      </div>
      <img src = "./images/svgicons/menu_icon.svg" id = 'mobile-nav'>

      <nav>
        <div class = "white-key">
          <a href = "home.php" class = "nav-item">
            <img src = "images/pngicons/home.png">
            HOME
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "user_profile.php" class = "nav-item">
            <img src = "images/pngicons/user.png">
            ACCOUNT
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "chat.php" class = "nav-item">
            <img src = "images/pngicons/chat.png">
            CHAT
          </a>
        </div>
        <div class = "white-key">
          <a href = "post.php" class = "nav-item">
            <img src = "images/pngicons/add.png">
            PUBBLICA
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "events.php" class = "nav-item">
            <img src = "images/pngicons/events.png">
            EVENTI
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "search_users.php" class = "nav-item">
            <img src = "images/pngicons/user_search.png">
            UTENTI
          </a>
          <div class = "black-key"></div>
        </div>
        <div class = "white-key">
          <a href = "logout.php" class = "nav-item">
            <img src = "images/pngicons/logout.png">
            LOGOUT
          </a>
        </div>
      </nav>
    
    </header>

<div id = 'mobile-modal' class = 'modal hidden'>
<nav id = 'modal-nav'>
  <div class = "white-key">
    <a href = "home.php" class = "nav-item">
      <img src = "images/pngicons/home.png">
      
    </a>
    <div class = "black-key"></div>
  </div>
  <div class = "white-key">
    <a href = "user_profile.php" class = "nav-item">
      <img src = "images/pngicons/user.png">
      
    </a>
    <div class = "black-key"></div>
  </div>
  <div class = "white-key">
    <a href = "chat.php" class = "nav-item">
      <img src = "images/pngicons/chat.png">
      
    </a>
  </div>
  <div class = "white-key">
    <a href = "post.php" class = "nav-item">
      <img src = "images/pngicons/add.png">
      
    </a>
    <div class = "black-key"></div>
  </div>
  <div class = "white-key">
    <a href = "events.php" class = "nav-item">
      <img src = "images/pngicons/events.png">
      
    </a>
    <div class = "black-key"></div>
  </div>
  <div class = "white-key">
    <a href = "search_users.php" class = "nav-item">
      <img src = "images/pngicons/user_search.png">
      
    </a>
    <div class = "black-key"></div>
  </div>
  <div class = "white-key">
    <a href = "logout.php" class = "nav-item">
      <img src = "images/pngicons/logout.png">
      
    </a>
  </div>
</nav>

<img id = 'close-mobile-navbar' src = 'images/svgicons/iconx.svg'>

</div>

<div id = "notif-modal" class = "modal hidden">
  <div id = "notif-box">
  <h1> </h1>
  <div class = 'ok-button'>Ok</div>
  </div>
</div>