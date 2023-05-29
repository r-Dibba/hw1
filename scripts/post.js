const album_cover_endp = "./phpfiles/ajax_getalbum.php";
const add_post_endp = "./phpfiles/ajax_addpost.php";
const form_post = document.forms['form-post'];
const alb_title = form_post['album-title'];
const alb_artist = form_post['album-artist'];
const cover = document.getElementById('default-album');
const overlay = document.getElementById('overlay');
const modal = document.getElementById('post-modal');

form_post.addEventListener('submit', onSubmit);
alb_title.addEventListener('input', fetchCover);
alb_artist.addEventListener('input', fetchCover);

alb_title.addEventListener('blur', checkEmptyBlur);
alb_artist.addEventListener('blur', checkEmptyBlur);
form_post['post-title'].addEventListener('blur', checkEmptyBlur);
form_post['score'].addEventListener('blur', checkEmptyBlur);
form_post['desc'].addEventListener('blur', checkEmptyBlur);
modal.querySelector('.ok-button').addEventListener('click', closeModal)


form_post['is-review'].addEventListener('change', setOverlay);

function fetchCover(event){
    event.preventDefault();

    if (event.target.value.length !== 0)
        fetch(album_cover_endp + "?album-title=" + encodeURIComponent(alb_title.value) + "&album-artist=" + encodeURIComponent(alb_artist.value)).then(onResp).then(onAlbum);
}


function onAlbum(alb){
    if (alb === null || alb.length === 0)
        cover.src = "./images/svgicons/notes.svg";
    else
        cover.src = alb;

}

function setOverlay(event){
    if (event.target.checked)
        overlay.classList.add('hidden');
    else{
        overlay.classList.remove('hidden');
        alb_title.classList.remove('error');
        alb_artist.classList.remove('error');
        form_post['score'].classList.remove('error');
        reviewReset();
    }
}

function reviewReset(){
    alb_title.value = null;
    alb_artist.value = null;
    form_post['cover-url'].value = null;
    form_post['score'].value = null;
    cover.src = "./images/svgicons/notes.svg";

}

function checkEmptyBlur(event){
    checkEmpty(event.target);
    
}

function onSubmit(event){
    event.preventDefault();
    const input_list = document.querySelectorAll('input, textarea');

    let check = 0;

    for (const elem of input_list)
        if (!checkEmpty(elem))
            check ++;
    
    if (check === 0){
        form_post['cover-url'].value = cover.src;
        const to_post = {
            method: 'post',
            body: new FormData(form_post)
        };
        const type = form_post['is-review'].checked ? 0 : 1;
        fetch(add_post_endp + "?type=" + type, to_post).then(onResp).then(onPosted);
    }
}

function checkEmpty(elem){
    let check = true;
    if ((elem.dataset.review === 'true' && form_post['is-review'].checked) || elem.dataset.review !== 'true'){
        if (elem.value.length === 0 && elem.name !== 'is-review' && elem.name !== 'cover-url'){
            elem.classList.add('error');
            if (elem.dataset.type !== 'score')
                elem.placeholder = "Compila questo campo!";
            
            check = false;
        }
    
        else
            elem.classList.remove('error');
    }
    
    
    return check;
}

function onPosted(stat){
    if (!stat.posted)
        openModal('Oh No!', stat.status);

    else{
        const input_list = document.querySelectorAll('input, textarea');
        for (const input of input_list)
            if(input.type !== 'submit')
                input.value = null;
        openModal('Ok', 'Contenuto pubblicato con successo!');
    }

}

function openModal(h1_title, p_content){
    modal.classList.remove('hidden');
    modal.style.top = window.pageYOffset + 'px';
    document.querySelector('body').classList.add('no-scroll');
    modal.querySelector('h1').textContent = h1_title;
    modal.querySelector('p').textContent = p_content;
}

function closeModal(){
    modal.classList.add('hidden');
    document.querySelector('body').classList.remove('no-scroll');

}
