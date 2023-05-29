const chat_search = document.forms['chat-search'];
const friend_list = document.getElementById('friend-list');
const friend_search = document.getElementById('friend-search')
const chat_endp = "http://localhost/homework1/phpfiles/ajax_chat.php";
const msgcheck_endp = "http://localhost/homework1/phpfiles/ajax_checkuserlist.php?type=messages";
let style_check = false;

const chatbox = document.getElementById('chatbox');

const conversation = document.getElementById('conversation');
const send_msg = document.forms['sendmsg'];
const msg_target = send_msg ? send_msg['target-user'] : null;
const send_msg_textbox = send_msg ? send_msg.message : null;

const fullinbox_section = document.getElementById('fullinbox-modal');

if(chat_search){
    chatbox.classList.add('hidden');
    chat_search.addEventListener('submit', chatResearch);
    fullinbox_section.querySelector('.ok-button').addEventListener('click', closeModalView);
    send_msg.addEventListener('submit', sendMessage);

}

function chatResearch(event){
    event.preventDefault();

    offset = 0;

    const toSearch = encodeURIComponent(chat_search['user-search'].value);
    chat_search['user-search'].classList.remove('wrong');
    searchedAgain = false;
    searched = toSearch;

    removeSearchAgainButton();

    if (offset === 0)
        friend_list.innerHTML = "";

    if (!toSearch){
        chat_search['user-search'].classList.add('wrong');
        printFlistError('Cerca un username!');
        return;
    }
    printFlistError('Sto cercando...');
    fetch(search_users_endp + "?user-search=" + toSearch + "&limit=" + limit + "&offset=" + offset + "&type=like-chat").then(onResp).then(onUsersChat);
}

function printFlistError(errmsg){
    const toChange = document.getElementById('search-info');
    if (toChange)
        toChange.textContent = errmsg;
    else{
        const user_not_found = document.createElement('p');
        user_not_found.id = 'search-info';
        user_not_found.textContent = errmsg;
        friend_list.appendChild(user_not_found);
    }
}

function onUsersChat(json){

    if (json.length === 0 && !searchedAgain){
        printFlistError("Non c'è nessuno :(");
        return;
    }
    printFlistError('Manda un Messaggio!');

    for (let userdata of json){
        const friend = document.createElement('div');
        const user_img = document.createElement('img');
        const friend_name = document.createElement('h3');
        const unread = document.createElement('div');

        friend.classList.add('friend');
        if (style_check){
            friend.classList.add('alt-style');
            style_check = false;
        }
        else
            style_check = true;
        friend_name.classList.add('friend-name');
        unread.classList.add('unread');
        if (userdata.amtUnread == 0)
            unread.classList.add('hidden');
        else
            unread.textContent = userdata.amtUnread;
        
        
        
        user_img.src = userdata.Propic;
        unread.dataset.user = userdata.Username;
        friend.dataset.user = userdata.Username;
        friend_name.textContent = userdata.Username;

        friend_list.appendChild(friend);
        friend.appendChild(user_img);
        friend.appendChild(friend_name);
        friend.appendChild(unread);

        friend.addEventListener('click', chatWith);
        unread.addEventListener('click', markAsRead);

        
    }

    if (json.length < limit )
            removeSearchAgainButton();

        else{
            const search_again = createSearchAgainButton();

            friend_search.appendChild(search_again);

            if (searched)
                search_again.addEventListener("click", ChatResearchAgain);
        }   
}


function ChatResearchAgain(event){
    event.preventDefault();
    searchedAgain = true;
    offset = offset + limit;
    removeSearchAgainButton();

    fetch(search_users_endp + "?user-search=" + searched + "&limit=" + limit + "&offset=" + offset + "&type=like-chat").then(onResp).then(onUsersChat);
}

function chatWith(event){
    chatbox.classList.remove('hidden');
    conversation.innerHTML = '';
    event.currentTarget.querySelector('.unread').classList.add('hidden');

    const toSearch = encodeURIComponent(event.currentTarget.dataset.user);
    document.querySelector('#chatbox h2').textContent = toSearch;

    msg_target.value = toSearch;
    fetch(chat_endp + "?type=load-conv&target-user=" + toSearch).then(onResp).then(loadConv);

    scrollTo(top);
    

}

function markAsRead(event){
    event.stopPropagation();
    const target_user = encodeURIComponent(event.target.dataset.user);
    fetch(chat_endp + "?type=mark-as-read&target-user=" + target_user);
    event.target.remove();
}

function loadConv(conv){    
    for (const msg of conv){
        const msgline = document.createElement('p');
        msgline.textContent = msg.Msgtext;
        msgline.classList.add('msg');
        if (msg.Sender !== current_user)
            msgline.classList.add('received');
        else
            msgline.classList.add('sent');
        conversation.appendChild(msgline);
    }

    startListen();

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

function checkLength(input_item, max_length, errmsg){
    
    check = true;

    if(input_item.value.length > max_length){
        input_item.classList.add('form-text-error');
        input_item.value = null;
        input_item.placeholder = errmsg;
        check = false;
    }
    return check;
}

function onMessageSent(info){
    send_msg_textbox.value = null;
    if (info.status){
        const msgline = document.createElement('p');
        msgline.textContent = info.message;
        msgline.classList.add('msg');
        msgline.classList.add('sent');
        conversation.appendChild(msgline);
    }

    else {        
        send_msg_textbox.blur();
        fullinbox_section.style.top = window.pageYOffset + 'px';
        document.querySelector('body').classList.add('no-scroll');
        fullinbox_section.querySelector('span').textContent = msg_target.value;
        fullinbox_section.classList.remove('hidden');
    }
}

function startListen(){
    const target_user = encodeURIComponent(msg_target.value);
    fetch(chat_endp + "?type=listen&target-user=" + target_user).then(onResp).then(loadConv);
}

function closeModalView(event){
    document.querySelector('body').classList.remove('no-scroll');
    event.target.parentNode.parentNode.classList.add('hidden');

}

function searchFollowers(event){
    event.preventDefault();

    offset = 0;

    friend_list.innerHTML = "";
    const found = document.getElementById('search-info');
    if (found)
        found.textContent = "Sto cercando...";

    removeSearchAgainButton();

    const type = event.currentTarget.dataset.flwInfo;
    searchagain_type = type;
    fetch(search_users_endp + "?type=" + type + "&limit=" + limit + "&offset=" + offset + "&user-search=" + user_shown).then(onResp).then(onUsersChat).then(handleFollowerResearch);
}

function followerSearchAgain(event){
    event.preventDefault();

    offset = offset + limit;

    const found = document.getElementById('search-info');
    found.textContent = "Sto cercando...";

    const type = event.currentTarget.dataset.flwInfo;

    fetch(search_users_endp + "?type=" + searchagain_type + "&limit=" + limit + "&offset=" + offset + "&user-search=" + user_shown).then(onResp).then(onUsersChat).then(handleFollowerResearch);
}