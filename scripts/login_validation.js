const login_form = document.forms['login-form'];

login_form.addEventListener('submit', checkErrors);

function checkErrors(event){
    removeErrors(event.target);
    if (event.target.user.value.length === 0 || event.target.pwd.value.length === 0){
        event.target.pwd.parentNode.appendChild(createError('Inserisci Username e Password!'));
        event.preventDefault();
    }
}

function removeErrors(elem_ref){
    toRemove = elem_ref.querySelectorAll('.error');
    if (toRemove.length !== 0){
        for (let error of toRemove){
            error.remove();
        }
    }
}

function createError(message){
    const error = document.createElement('p');
    error.classList.add('error');
    error.textContent = message;
    return error;
}
