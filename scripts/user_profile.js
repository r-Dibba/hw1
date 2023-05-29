const followers_buttons = document.querySelectorAll('.flw-info');

let searchagain_type;

const user_shown_addfriend = document.querySelector('#banner-add-friend');
const user_shown_quickmsg = document.getElementById('banner-quick-msg');
const settings = document.querySelector('#bottom-left img');
let user_shown;
let user_list = [];

const quick_msg_hint = document.querySelector('#in-modal p');

const quickmsg_modal = document.getElementById('send-msg-modal');
const update_modal = document.getElementById('acc-upd-modal');

const form_propic = document.forms['form-propic'];
const form_motto = document.forms['form-motto'];
const update_profile_endp = "http://localhost/homework1/phpfiles/ajax_update_profile.php";
const retrieve_posts_endp = "http://localhost/homework1/phpfiles/ajax_retrieveposts.php?type=target";
const upd_hints = document.querySelectorAll('#upd-errors p');
const show_posts = document.getElementById('show-post');


if (show_posts)
    show_posts.addEventListener('click', showPosts);

if (user_shown_addfriend){
    user_shown = user_shown_addfriend.dataset.user;
    bannerAddHandlers();
    startFollowerListeners();
}
else {
    if(update_modal){
        settings.addEventListener('click', openModal);
        update_modal.querySelector('.ok-button').addEventListener('click', closeModal);
        form_propic.addEventListener('submit', updatePropic);
        form_motto.addEventListener('submit', updateMotto);
    }
        fetch(userdata_endp).then(onResp).then(getCurrentUser).then(setUserShown).then(startFollowerListeners);
    
}


/* FUNZIONI */

function setUserShown(){
    user_shown = current_user;
}

function startFollowerListeners(){
    for (const button of followers_buttons){
        button.addEventListener('click', searchFollowers);
    }
}

function bannerAddHandlers(){
    user_shown_quickmsg.addEventListener('click', openModal);
    quickmsg_modal.querySelector('.ok-button').addEventListener('click', closeModal);

    send_msg.addEventListener('submit', sendMessage);
    fetch(search_users_endp + "?type=is-followed&user-search=" + encodeURIComponent(user_shown)).then(onResp).then(onIsfriend);

}

function onIsfriend(stat){
    user_shown_addfriend.addEventListener('click', updFlwAmt);
    if (stat.length === 0){
        user_shown_addfriend.addEventListener('click', addFollow);
        user_shown_addfriend.src = "images/svgicons/add_user.svg";
        user_shown_addfriend.dataset.friend = 'not-flwd';
    }
    else{
        user_shown_addfriend.addEventListener('click', removeFollow);
        user_shown_addfriend.src = "images/svgicons/user_added.svg";
        user_shown_addfriend.dataset.friend = 'flwd';

    }

}

function updFlwAmt(event){
    if (event.target.dataset.friend === 'flwd'){
        followers_buttons[0].querySelector('span').textContent--;
        user_shown_addfriend.dataset.friend = 'not-flwd';
    }
    else{
        followers_buttons[0].querySelector('span').textContent++;
        user_shown_addfriend.dataset.friend = 'flwd';
    }
}

function searchFollowers(event){
    offset = 0;

    if (section.id !== "show-chat"){
        section.id = "found-users";
        section.innerHTML = "";
    }

    const found = document.getElementById('search-info');
    if (found)
        found.textContent = "Sto cercando...";

    


    removeSearchAgainButton();

    const type = event.currentTarget.dataset.flwInfo;
    searchagain_type = type;
    fetch(search_users_endp + "?type=" + type + "&limit=" + limit + "&offset=" + offset + "&user-search=" + user_shown).then(onResp).then(onUsers).then(handleFollowerResearch);

}

function handleFollowerResearch(){
    const search_again = document.querySelector("#search-again");
    if (search_again !== null)
        search_again.addEventListener('click', followerSearchAgain);
}

function followerSearchAgain(event){
    event.preventDefault();

    offset = offset + limit;

    const found = document.getElementById('search-info');
    found.textContent = "Sto cercando...";

    const type = event.currentTarget.dataset.flwInfo;

    fetch(search_users_endp + "?type=" + searchagain_type + "&limit=" + limit + "&offset=" + offset + "&user-search=" + user_shown).then(onResp).then(onUsers).then(handleFollowerResearch);
}

function onMessageSent(info){
    send_msg_textbox.value = null;
    if (info.status){
        quick_msg_hint.textContent = "Messaggio Inviato!";
        quick_msg_hint.classList.add('msg-success');
    }

    else {
        quick_msg_hint.textContent = "Inbox di " + msg_target.value + " piena!";
        quick_msg_hint.classList.add('msg-failure');
    }
}

function openModal(event){
    if(quickmsg_modal){
        quickmsg_modal.classList.remove('hidden');
        quickmsg_modal.style.top = window.pageXOffset + 'px';
    }
    else
        update_modal.classList.remove('hidden');
    document.querySelector('body').classList.add('no-scroll');

}

function closeModal(){
    quick_msg_hint.textContent = '';
    quick_msg_hint.classList.remove('msg-success');
    quick_msg_hint.classList.remove('msg-failure');

    if (quickmsg_modal)
        quickmsg_modal.classList.add('hidden');
    else
        update_modal.classList.add('hidden');

    if (upd_hints.length != 0)
        for (const hint of upd_hints){
            hint.classList.remove('msg-failure');
            hint.classList.remove('msg-success');
            hint.textContent = '';
        }

    document.querySelector('body').classList.remove('no-scroll');

}

function sendMessage(event){
    event.preventDefault();
    if (!checkLength(send_msg_textbox, 500, "Scrivi un messaggio più corto! (max. 500 caratteri)"))
        return;
    else{
        to_post = {
            method: 'post',
            body: new FormData(send_msg)
        }

        fetch(chat_endp + "?type=send-msg", to_post).then(onResp).then(onMessageSent);
    }

}

function updatePropic(event){
    event.preventDefault();
    const form_data = new FormData(form_propic);
    const to_post = {
        method: 'post',
        body: form_data
    };
    fetch(update_profile_endp + "?type=upload-propic", to_post).then(onResp).then(onUpdate);

}

function updateMotto(event){
    event.preventDefault();

    if (mottoCheck(form_motto.querySelector('textarea').value)){
        const form_data = new FormData(form_motto);
        const to_post = {
            method: 'post',
            body: form_data
        };
        fetch(update_profile_endp + "?type=update-motto", to_post).then(onResp).then(onMotto);
    }
}

function onUpdate(status){
    if(status['error']){
        upd_hints[0].classList.remove('msg-success');
        upd_hints[0].classList.add('msg-failure');
        upd_hints[0].textContent = status.error;
    }
    else{
        upd_hints[0].classList.remove('msg-failure');
        upd_hints[0].classList.add('msg-success');
        upd_hints[0].textContent = status.success;

        toChange = document.querySelectorAll('.currentuser-propic');
        for (const img of toChange)
            img.src = status['propic'];
        }
}

function mottoCheck(text){
    if(text.length > 255){
        upd_hints[1].classList.remove('msg-success');
        upd_hints[1].classList.add('msg-failure');
        upd_hints[1].textContent = "Il tuo Motto non può superare i 255 caratteri!";
        return false;
    }
    return true;
}


function onMotto(status){
    if(status['error']){
        upd_hints[1].classList.remove('msg-success');
        upd_hints[1].classList.add('msg-failure');
        upd_hints[1].textContent = status.error;
    }
    else{
        upd_hints[1].classList.remove('msg-failure');
        upd_hints[1].classList.add('msg-success');
        upd_hints[1].textContent = status.success;
        document.getElementById('motto').textContent = status['motto'];
        form_motto['upd-motto'].placeholder = status['motto'];    }
}

function showPosts(event){
    fetch(retrieve_posts_endp + "&target-user=" + encodeURIComponent(user_shown) + "&limit=9&offset=0").then(onResp).then(appendPosts);
}

function appendPosts(posts_list){
    const post_section = document.querySelector('section');
    post_section.id = 'show-posts';
    post_section.innerHTML = '';

    if(posts_list.posts)
        onPosts(posts_list);
}