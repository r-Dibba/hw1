const input_types = {
    'nome': 0,
    'cognome': 1,
    'email': 2,
    'user': 3,
    'pwd1': 4,
    'pwd2': 5
}

const error_codes = {
    'none': -1,
    'empty': 0,
    'too long': 1,
    'too short': 2,
    'not valid': 3,
    'unavailable': 4,
    'not equal': 5
}

const signup_form = document.forms['signup'];
const inputs = signup_form.querySelectorAll('input');

const cred_validation_endp = "http://localhost/homework1/phpfiles/ajax_signup_validation.php";

for (const input of inputs){
    switch(input_types[input.name]){
        case 0:
            input.addEventListener('input', checkName);
            input.addEventListener('blur', checkName);
            break;
        case 1:
            input.addEventListener('input', checkName);
            input.addEventListener('blur', checkName);
            break;
        case 2:
            input.addEventListener('input', checkCred);
            input.addEventListener('blur', checkCred);
            break;
        case 3:
            input.addEventListener('input', checkCred);
            input.addEventListener('blur', checkCred);
            break;
        case 4:
            input.addEventListener('input', checkPwd1);
            input.addEventListener('blur', checkPwd1);
            break;
        case 5:
            input.addEventListener('input', checkPwd2);
            input.addEventListener('input', checkPwd2);
            break;
    }
}

signup_form.addEventListener('submit', checkErrors);

function checkEmpty(to_check){
    let error_code = 'none';
    if (to_check.trim().length === 0)
        error_code = 'empty';
    return error_code;
}

function checkName(event){
    let error_code = checkEmpty(event.target.value);

    errorHandler(error_code, event.target);
}

function serverValidate(elem_ref){
    let type;
    const target = encodeURIComponent(elem_ref.value);
    if (elem_ref.name === 'user')
        type = 'user';
    else if (elem_ref.name = 'email')
        type = 'email';
    fetch(cred_validation_endp + "?type=" + type + "&to-check=" + target).then(onResp).then(startHandler);

}

function onResp(resp){
    return resp.json();
}

function startHandler(status){
    errorHandler(status.code, signup_form[status.type]);
        
}

function checkCred(event){

    let error_code = checkEmpty(event.target.value);

    if (event.target.name === 'email'){
        const email_regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if (!event.target.value.match(email_regex))
            error_code = 'not valid';
    }
    else if (event.target.name === 'user')
        if (event.target.value.trim().length > 12)
            error_code = 'too long';
    
    if (error_code === 'none')
        serverValidate(event.target);
    else
        errorHandler(error_code, event.target);
    
}

function checkPwd1(event){
    let error_code = checkEmpty(event.target.value);

    if (error_codes[error_code] === -1){
        const pwd_regex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[,.:-_!?<>+^@]).{10,}$/;
        if (event.target.value.trim().length < 10)
            error_code = 'too short';
        else if (!event.target.value.match(pwd_regex))
            error_code = 'not valid';
    }

    errorHandler(error_code, event.target);

}

function checkPwd2(event){
    let error_code = 'none';

    if (event.target.value !== signup_form.pwd1.value)
        error_code = 'not equal';
    
    errorHandler(error_code, event.target);
}

function errorHandler(code, elem_ref){
    let container = elem_ref.parentNode;
    let errmsg;

    switch(input_types[elem_ref.name]){
        case 0: 
            switch(error_codes[code]){
                case 0:
                    errmsg = 'Inserisci il tuo Nome!';
                    break;
            }
            break;
        case 1:
            switch(error_codes[code]){
                case 0:
                    errmsg = 'Inserisci il tuo Cognome!';
                    break;
            }
            break;
        case 2:
            switch(error_codes[code]){
                case 0:
                    errmsg = 'Inserisci la tua email!';
                    break;
                case 3:
                    errmsg = 'Email non valida';
                    break;
                case 4:
                    errmsg = 'Email già in uso';
                    break;
            }
            break;
        case 3:
            switch(error_codes[code]){
                case 0:
                    errmsg = 'Inserisci un nome utente!';
                    break;

                case 1:
                    errmsg = "L'username dev'essere al più di 12 caratteri";
                    break;
                case 4:
                    errmsg = 'Username già in uso';
                    break;
            }
            break;
        case 4:
            container = document.getElementById('password-error');
            switch(error_codes[code]){
                case 0:
                    errmsg = 'Inserisci una Password!';
                    break;
                case 2:
                    errmsg = "La password deve contenere almeno 10 caratteri";
                    break;

                case 3:
                    errmsg = "La password deve contenere almeno una maiuscola, una minuscola, un numero e uno fra i seguenti caratteri speciali: , . : - _ ! ? < > + ^ @"
                    break;
            }
        case 5:
            container = document.getElementById('password-error');
            switch(error_codes[code]){
                case 5:
                    errmsg = 'Le password non corrispondono!';
                    break;
            }

    }

    removeErrors(container);
    container.appendChild(createError(errmsg));
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

function checkErrors(event){
    const err_list = event.target.querySelectorAll('.error')
    for (const err of err_list)
        if (err.textContent !== ''){
            event.preventDefault();
            break;
        }
    
       
}