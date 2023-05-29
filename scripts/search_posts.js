const get_posts_endp = "http://localhost/homework1/phpfiles/ajax_retrieveposts.php";
const post_interactions_endp = "http://localhost/homework1/phpfiles/ajax_postinteractions.php";
const post_limit = 9;
let post_offset = 0;
let loadMore = false;

const interaction_codes = {
    "upvote": 0,
    "downvote": 1,
    "comment": 2,
    "remove-post": 3
}

const post_refs = {};

if(document.getElementById('show-posts'))
    searchPosts();

function searchPosts(){

    if (!loadMore){
        post_offset = 0;
        document.getElementById('show-posts').innerHTML = '';
    }

    fetch(get_posts_endp + "?type=friends&limit=" + post_limit + "&offset=" + post_offset).then(onResp).then(onPosts)

}


function onPosts(posts){
    
    const found = document.getElementById('search-info');
    removeSearchAgainButton();

    if (posts.length === 0 && !loadMore){
        found.textContent = "Nessun post trovato :(";
        return;
    }
    
    found.textContent = "Ecco cos'ho trovato:";   
    if (posts.posts){
        for (const post of posts.posts){
            const div_post = document.createElement('div');
            const post_author = document.createElement('a');
            const author_propic = document.createElement('img');
            const author_name = document.createElement('span');
            const post_container = document.createElement('div');
            const post_title = document.createElement('h3');
            const desc = document.createElement('p');
            const post_actions = document.createElement('div');
            const upv = document.createElement('img');
            const downv = document.createElement('img');
            const comment = document.createElement('img');
            const amt_likes = document.createElement('span');
            const amt_dislikes = document.createElement('span');
    
            div_post.classList.add('post');
            post_author.classList.add('author');   
            post_author.href = "./user_profile.php?clicked-profile=" + post.Author;
            author_propic.classList.add('post-propic');
            author_propic.src = post.Propic;
            author_name.textContent = post.Author;
            post_container.classList.add('post-container');
            post_title.textContent = post.Title;
            desc.textContent = post.Textcontent;
            post_actions.classList.add('post-actions');
    
            upv.dataset.interaction = 'upvote';
            downv.dataset.interaction = 'downvote';
            upv.dataset.action = 'upvote';
            downv.dataset.action = 'downvote';
    
            if (post.Interaction === 'upv'){
                upv.src = './images/svgicons/liked.svg';
                downv.src = './images/svgicons/dislike.svg';
                upv.dataset.action = 'delete';
            }
            else if (post.Interaction === 'dwn'){
                upv.src = './images/svgicons/like.svg';
                downv.src = './images/svgicons/disliked.svg';
                downv.dataset.action = 'delete';
            }
            else{
                upv.src = './images/svgicons/like.svg';
                downv.src = './images/svgicons/dislike.svg';
            }
            
    
            comment.src = './images/svgicons/comment.svg';
            comment.dataset.interaction = 'comment';
            comment.dataset.action = 'load-comments'
            comment.classList.add('comment');
            amt_likes.textContent = post.AmtLikes;
            amt_dislikes.textContent = post.AmtDislikes
    
            div_post.appendChild(post_author);
            post_author.appendChild(author_propic);
            post_author.appendChild(author_name);
            div_post.appendChild(post_container);
            post_container.appendChild(post_title);
    
    
    
            if (post.Albumtitle !== null){
                const review = document.createElement('div');
                const album = document.createElement('album');
                const cover = document.createElement('img');
                const alb_title = document.createElement('h4');
                const artist = document.createElement('em');
                const score = document.createElement('h4');
    
                review.classList.add('review');
                album.classList.add('album');
                cover.classList.add('cover');
                cover.src = post.Cover;
                alb_title.textContent = post.Albumtitle;
                artist.textContent = post.Artist;
                score.textContent = post.Score;
    
                post_container.appendChild(review);
                review.appendChild(album);
                album.appendChild(cover);
                album.appendChild(alb_title);
                album.appendChild(artist);
                album.appendChild(score);
                review.appendChild(desc);
    
    
    
            }
            else
                post_container.appendChild(desc);
            
            post_container.appendChild(post_actions);
            post_actions.appendChild(amt_likes);
            post_actions.appendChild(upv);
            post_actions.appendChild(downv);
            post_actions.appendChild(amt_dislikes);
            post_actions.appendChild(comment);
            post_actions.dataset.postId = post.ID;
    
            upv.addEventListener('click', postInteraction);
            upv.dataset.interaction = 'upvote';
            downv.addEventListener('click', postInteraction);
            downv.dataset.interaction = 'downvote';
            comment.addEventListener('click', postInteraction);
            comment.dataset.interaction = 'comment';
            
            const comment_box = document.createElement('section');
            comment_box.classList.add('comment-box');
            comment_box.classList.add('hidden');
            const comment_list = document.createElement('div');
            comment_list.classList.add('comment-list');
            const send_comment = document.createElement('form');
            send_comment.classList.add('send-comment');
            const comment_text = document.createElement('input');
            comment_text.classList.add('comment-text');
            comment_text.type = 'text';
            comment_text.name = 'comment';
            comment_text.placeholder = 'scrivi un commento!';
            comment_text.autocomplete = 'off';
            const confirm_comment = document.createElement('input');
            confirm_comment.classList.add('confirm-comment');
            confirm_comment.type = 'submit';
            confirm_comment.value = '';

            post_container.appendChild(comment_box);
            comment_box.appendChild(comment_list);
            comment_box.appendChild(send_comment);
            send_comment.appendChild(comment_text);
            send_comment.appendChild(confirm_comment);

            comment_text.addEventListener('input', checkComment);
            comment_text.addEventListener('blur', checkComment);
            send_comment.addEventListener('submit', sendComment);

            document.getElementById('show-posts').appendChild(div_post);
            post_refs[post.ID] = div_post;
            
        }

        if (posts.posts.length === post_limit){
            const load_more_button = createSearchAgainButton();
            load_more_button.addEventListener('click', loadMorePosts);
            document.getElementById('show-posts').appendChild(load_more_button);
    
        }
    }

    loadMore = false;
    
}

function loadMorePosts(event){
    loadMore = true;
    post_offset += post_limit;
    searchPosts();
}

function postInteraction(event){
    const type = event.target.dataset.interaction;
    const action = event.target.dataset.action;
    const target = event.target.parentNode.dataset.postId;
    const like = event.target.parentNode.querySelectorAll('img')[0];
    const dislike = event.target.parentNode.querySelectorAll('img')[1];
    const amt_liked = event.target.parentNode.querySelectorAll('span')[0];
    const amt_disliked = event.target.parentNode.querySelectorAll('span')[1];

    let comment_hide = false;

    switch(interaction_codes[type]){
        case 0:
            if (action === 'delete'){
                event.target.src = './images/svgicons/like.svg';
                event.target.dataset.action = 'upvote';
                amt_liked.textContent--;
            }
            else{
                event.target.src = "./images/svgicons/liked.svg";
                event.target.dataset.action = 'delete';
                amt_liked.textContent++;
    
                if (dislike.dataset.action === 'delete'){
                    dislike.src = "./images/svgicons/dislike.svg";
                    dislike.dataset.action = 'downvote';
                    amt_disliked.textContent--;
                }
    
            }
            break;
    
        case 1:
            if (action === 'delete'){
                event.target.src = './images/svgicons/dislike.svg';
                event.target.dataset.action = 'upvote';
                amt_disliked.textContent--;
            }
            else{
                event.target.src = "./images/svgicons/disliked.svg";
                amt_disliked.textContent++;
                event.target.dataset.action = 'delete';
    
                if (like.dataset.action === 'delete'){
                    like.src = "./images/svgicons/like.svg";
                    like.dataset.action = 'upvote';
                    amt_liked.textContent--;
                }
    
            }
            break;
        case 2:

            if (event.target.dataset.action === 'load-comments'){
                event.target.parentNode.parentNode.querySelector('.comment-box').classList.remove('hidden');
                event.target.dataset.action = 'hide-comments';
            }
            else if(event.target.dataset.action === 'hide-comments'){
                event.target.parentNode.parentNode.querySelector('.comment-box').classList.add('hidden');
                event.target.parentNode.parentNode.querySelector('.comment-box .comment-list').innerHTML = '';
                event.target.dataset.action = 'load-comments';
                comment_hide = true;
            }


            break;
    }
    if (!comment_hide)
        fetch(post_interactions_endp + "?type=" + action + "&target-post=" + target).then(onResp).then(onInteraction);

}

function removeSearchAgainButton(){
    const search_again = document.querySelector('#search-again');
        
    if (search_again !== null)
        search_again.remove();
}

function createSearchAgainButton(){

    const search_again = document.createElement('div');
    search_again.id = "search-again";
    search_again.textContent = "...";
    search_again.href = "";
    return search_again;
}

function sendComment(event){
    event.preventDefault();
    if (event.target['comment'].value.length > 0 && event.target['comment'].value.length < 255){
        const target_post = event.target.parentNode.parentNode.querySelector('.post-actions').dataset.postId;
        const to_post = {
            method: 'post',
            body: new FormData(event.target)
        } 
        fetch(post_interactions_endp + '?type=comment&target-post=' + target_post, to_post);
    
        const target_post_list = post_refs[target_post].querySelector('.comment-list');
        const user_post = createPost(current_user, document.getElementById('user-img').src, event.target['comment'].value);
        target_post_list.appendChild(user_post);
    
        event.target['comment'].value = null;
    }



}

function createPost(author_name, author_pic, comment_content){
    const comment_container = document.createElement('div');
            comment_container.classList.add('comment-container');
            const author_page = document.createElement('a');
            author_page.href = "http://localhost/homework1/user_profile.php?clicked-profile=" + author_name;
            author_page.style.backgroundImage = 'url(' + author_pic + ')';
            const author_username = document.createElement('h6');
            author_username.textContent = author_name;
            const comment_body = document.createElement('div');
            comment_body.classList.add('comment-body');
            const comment_text = document.createElement('p');
            comment_text.textContent = comment_content;


            comment_container.appendChild(author_page);
            comment_container.appendChild(comment_body);
            comment_body.appendChild(author_username);
            comment_body.appendChild(comment_text);
            return comment_container;
}

function onInteraction(status){
    if (status['append-to']['status']){
        const target_post = post_refs[status['append-to']['id']].querySelector('.comment-list');
        for (const res of status.results){
            const comment_container = createPost(res.author, res.Propic, res.comment);
            target_post.appendChild(comment_container);
           
        }
    }
}

function checkComment(event){
    if(event.target.value.trim().length > 255){
        event.target.value = null;
        event.target.placeholder = 'Commento troppo lungo!';
        event.target.style.backgroundColor = 'var(--color-coralhover)';
    }
    else if (event.target.value.trim().length === 0){
        event.target.value = null;
        event.target.placeholder = 'Nessun commento inserito!';
        event.target.style.backgroundColor = 'var(--color-coralhover)';
    }
    else
        event.target.style.backgroundColor = 'white';

}