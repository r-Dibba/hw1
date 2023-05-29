const userdata_endp = "http://localhost/homework1/phpfiles/ajax_currentuser.php";
const notif_endp = "http://localhost/homework1/phpfiles/ajax_notifications.php";
let current_user;
let notif_amt = [0, 0];
const mobile_nav = document.getElementById('mobile-nav');
const mobile_modal = document.getElementById('mobile-modal');
const close_mobile_modal = document.getElementById('close-mobile-navbar');
const mess_notif = document.getElementById('message-notifications');
const cont_notif = document.getElementById('content-notifications');
const notif_modal = document.getElementById('notif-modal');
const notif_close = notif_modal.querySelector('.ok-button');
const notif_title = notif_modal.querySelector('h1');

mess_notif.addEventListener('click', showNotifs);
cont_notif.addEventListener('click', showNotifs);
mobile_nav.addEventListener('click', showNavbar);
close_mobile_modal.addEventListener('click', closeNavbar);


loggedUserName();
getNotifications();

function loggedUserName(){
    fetch(userdata_endp).then(onResp).then(getCurrentUser);
}

function onResp(response){
    return response.json();
}

function getCurrentUser(user){
    current_user = user.Username;
}

function showNavbar(event){
    mobile_modal.classList.remove('hidden');
    document.querySelector('body').classList.add('no-scroll');
    mobile_modal.style.top = window.pageYOffset + 'px';
}

function closeNavbar(event){
    event.target.parentNode.classList.add('hidden');
    document.querySelector('body').classList.remove('no-scroll');

}

function getNotifications(){
    fetch(notif_endp + '?type=get-msg').then(onResp).then(onNotif);
    fetch(notif_endp + '?type=get-other').then(onResp).then(onNotif);
}

function onNotif(stat){
    if (stat.type === 'get-msg'){
        notif_amt[0] = stat.res.amt;
        if (notif_amt[0] > 0)
            mess_notif.src = "./images/svgicons/msg_notified.svg";
    }

    else if (stat.type === 'get-other'){
        notif_amt[1] = stat.res.amt;
        if (notif_amt[1] > 0)
            cont_notif.src = "./images/svgicons/notified.svg";
    }
}

function showNotifs(event){

    notif_modal.classList.remove('hidden');
    document.querySelector('body').classList.add('no-scroll');
    notif_modal.style.top = window.pageYOffset + 'px';


    if (event.target.dataset.type === 'msg'){
        notif_title.textContent = (notif_amt[0] > 0) ? 'Hai ' + notif_amt[0] + " nuovi messaggi!" : 'Non hai nuovi messaggi';
        notif_close.addEventListener('click', closeNotif);
    }

    else if (event.target.dataset.type === 'oth'){
        notif_title.textContent = (notif_amt[1] > 0) ? notif_amt[1] + " Persone hanno commentato i tuoi Post!" : 'Non hai nuove notifiche';
        notif_close.addEventListener('click', closeNotif);
        fetch(notif_endp + "?type=check-other");
        cont_notif.src = "./images/svgicons/notifications.svg";

    }
}

function closeNotif(){
    notif_modal.classList.add('hidden');
    document.querySelector('body').classList.remove('no-scroll');

}

