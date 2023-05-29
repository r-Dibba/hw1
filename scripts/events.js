const events_endp = "phpfiles/ajax_getevents.php";
let offset;
let isResearch = false;
let toSearch;
const limit = 9;

const toggle_type = document.getElementById('toggle-search');
toggle_type.addEventListener('click', toggleType);

const events_form = document.forms['artist-search'];
const search_box = events_form['filter'];
const section = document.querySelector('section');
const article = document.querySelector('article');

events_form.addEventListener('submit', searchEvents);

function toggleType(event){
    const data = events_form.dataset.type;
    section.innerHTML = "";

    const toRemove = document.getElementById('search-again');
    if (toRemove)
        toRemove.remove();

    if (data === 'country'){
        event.target.textContent = "Cerca Per Paese";
        events_form.dataset.type = 'artist';
        search_box.placeholder = "cerca artista";
    }
    else{
        event.target.textContent = "Cerca Per Artista";
        events_form.dataset.type = 'country';
        search_box.placeholder = "cerca Paese (es. US o Brazil)";
    }
}

function searchEvents(event){
    if(event || !isResearch){
        event.preventDefault();
        offset = 1;
        section.innerHTML = "";
        const res = encodeURIComponent(search_box.value);
        toSearch = res;
    }

    else
        offset++;

    if (toSearch)
        if (events_form.dataset.type === 'country')
            fetch(events_endp + "?type=1&filter=" + toSearch + "&page=" + offset).then(onResp).then(onEvent);

        else
            fetch(events_endp + "?type=0&filter=" + toSearch + "&page=" + offset).then(onResp).then(onEvent);

    
}

function onEvent(eventlist){

    console.log(eventlist);

    if (eventlist.length === 0 && !isResearch){
        const err = document.createElement('h2');
        err.textContent = "Nessun evento trovato";
        section.appendChild(err);

        const toRemove = document.getElementById('search-again');
        if (toRemove)
            toRemove.remove();

        return;
    }

    for (const elem of eventlist){
        const event_div = document.createElement('div');
        event_div.classList.add('event')

        const title = document.createElement('h3');
        const date = document.createElement('h6');
        const desc = document.createElement('h4');
        const tickets = document.createElement('a');

        title.textContent = elem.title;
        date.textContent = elem.date;
        desc.textContent = elem.venue + " - " + elem.city + ", " + elem.country;
        tickets.textContent = "BIGLIETTI";
        tickets.href = elem.tickets;

        section.appendChild(event_div);
        event_div.appendChild(title);
        event_div.appendChild(date);
        event_div.appendChild(desc);
        event_div.appendChild(tickets);
    }

    const toRemove = document.querySelector('#search-again');

    if (eventlist.length < limit){
        isResearch = false;
        if (toRemove)
            toRemove.remove();
    }
    else{
        if (!toRemove){
            const searchagain = document.createElement('div');
            searchagain.id = 'search-again';
            searchagain.textContent = '...';
            searchagain.addEventListener('click', searchAgain) 
            article.appendChild(searchagain);
        }
    }

}

function searchAgain(event){
    isResearch = true;
    searchEvents();
}