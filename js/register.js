function sendData(username, email, password) {

    let request = new XMLHttpRequest();
    let formdata = new FormData();
    formdata.append("register", "1");
    formdata.append("username", username);
    formdata.append("email", email);
    formdata.append("password", password);

    request.open("POST", "api/UserController.php");
    request.onload = function () {
        if (request.status == 200) {        

            alert("Sikeres regisztráció");
        } else if (request.status == 409) {
            let response = JSON.parse(request.responseText);
            if (response.username == "TAKEN") {
                
                showUsernameTaken();
            }
            if (response.email == "TAKEN") {
                
                showEmailTaken();
            }

        } else {
            
        }

    };

    request.send(formdata);

}

function goBack() {
    location.href = "../food";
}

function onNameFieldChange() {
    let usernameInput = document.querySelector("#username");
    let nameCheckBtn = document.querySelector("#nameCheckBtn");
    let nameOk = document.querySelector("#nameOK");
    let nameNok = document.querySelector("#nameNOK");

    let feedback = document.querySelector("#usernameFeedback");

    usernameInput.classList.remove("invalid");
    feedback.classList.add("d-none");
    nameOk.classList.add("d-none");
    nameNok.classList.add("d-none");

    if (usernameInput.value.length > 0) {
        nameCheckBtn.disabled = false;
    } else {
        nameCheckBtn.disabled = true;
    }
}

function onEmailFieldChange() {

    let emailInput = document.querySelector("#email");
    let emailCheckBtn = document.querySelector("#emailCheckBtn");
    let Ok = document.querySelector("#emailOK");
    let Nok = document.querySelector("#emailNOK");
    let feedback = document.querySelector("#emailFeedback");

    emailInput.classList.remove("invalid");
    feedback.classList.add("d-none");
    Ok.classList.add("d-none");
    Nok.classList.add("d-none");

    if (emailInput.value.length > 0) {
        emailCheckBtn.disabled = false;
    } else {
        emailCheckBtn.disabled = true;
    }
}

function onNameCheckPressed() {

    let request = new XMLHttpRequest();
    let usernameField = document.querySelector("#username");
    let feedback = document.querySelector("#usernameFeedback");
    let formData = new FormData();
    let nameOk = document.querySelector("#nameOK");
    let nameNok = document.querySelector("#nameNOK");
    let spinner = document.querySelector("#nameSpinner");

    nameOk.classList.add("d-none");
    nameNok.classList.add("d-none");
    spinner.classList.remove("d-none");

    formData.append("check", "1");
    formData.append("username", usernameField.value);

    request.open("POST", "api/UserController.php");

    request.onload = function () {
        if (request.responseText == 0) {
            showUsernameTaken();
            nameNok.classList.remove("d-none");
            spinner.classList.add("d-none");
        } else {
            
            usernameField.classList.remove("invalid");
            usernameField.setAttribute("available", "1");
            feedback.classList.add("d-none");
            spinner.classList.add("d-none");
            nameOk.classList.remove("d-none");
            nameNok.classList.add("d-none");
        }
    }

    setTimeout(function () {
        request.send(formData);
    }, 1500);

}

function onEmailCheckPressed() {

    if (!validateEmail()) {
        return;
    }

    let request = new XMLHttpRequest();
    let emailField = document.querySelector("#email");
    let feedback = document.querySelector("#emailFeedback");
    let formData = new FormData();
    let Ok = document.querySelector("#emailOK");
    let Nok = document.querySelector("#emailNOK");
    let spinner = document.querySelector("#emailSpinner");

    Ok.classList.add("d-none");
    Nok.classList.add("d-none");
    spinner.classList.remove("d-none");

    formData.append("emailcheck", "1");
    formData.append("email", emailField.value);

    request.open("POST", "api/UserController.php");

    request.onload = function () {
        if (request.responseText == 0) {
            showEmailTaken();
            Nok.classList.remove("d-none");
            spinner.classList.add("d-none");
        } else {
            
            emailField.classList.remove("invalid");
            emailField.setAttribute("available", "1");
            feedback.classList.add("d-none");
            spinner.classList.add("d-none");
            Ok.classList.remove("d-none");
            Nok.classList.add("d-none");
        }
    }

    setTimeout(function () {
        request.send(formData);
    }, 1500);

}

function showUsernameTaken() {
    let usernameInput = document.querySelector("#username");
    let nameCheckBtn = document.querySelector("#nameCheckBtn");
    let nameCheckBtnDiv = document.querySelector("#nameCheckBtnDiv");
    let nok = document.querySelector("#nameNOK");

    nok.classList.remove("d-none");
    usernameFeedback.innerHTML = "Ez a felhasználónév már foglalt!";
    usernameFeedback.classList.remove("d-none");
    usernameInput.classList.add("invalid");
    nameCheckBtnDiv.classList.remove("d-none");
}

function showEmailTaken() {
    let nok = document.querySelector("#emailNOK");
    nok.classList.remove("d-none");
    let emailInput = document.querySelector("#email");
    let emailCheckBtn = document.querySelector("#emailCheckBtn");
    let emailCheckBtnDiv = document.querySelector("#emailCheckBtnDiv");
    emailFeedback.innerHTML = "Ezzel az e-mail címmel már regisztrált valaki!";
    emailFeedback.classList.remove("d-none");
    emailInput.classList.add("invalid");
    emailCheckBtnDiv.classList.remove("d-none");
}

function submitOverride(event) {
    event.preventDefault();
    event.stopPropagation();
    
    let usernameInput = document.querySelector("#username");
    let emailInput = document.querySelector("#email");
    let passwordInput = document.querySelector("#password");
    let confirmInput = document.querySelector("#confirmPassword");

    if (validateInputs()) {
        
        sendData(usernameInput.value, emailInput.value, passwordInput.value);
    }

}

function validateInputs() {
    
    let usernameInput = document.querySelector("#username");
    let emailInput = document.querySelector("#email");
    let passwordInput = document.querySelector("#password");
    let confirmInput = document.querySelector("#confirmPassword");

    let usernameFeedback = document.querySelector("#usernameFeedback");
    let emailFeedback = document.querySelector("#emailFeedback");
    let passwordFeedback = document.querySelector("#passwordFeedback");
    let confirmFeedback = document.querySelector("#confirmFeedback");

    let result = true;

    if (usernameInput.value.length == 0) {
        
        usernameFeedback.innerHTML = "Nem adtál meg felhasználónevet!";
        usernameFeedback.classList.remove("d-none");
        usernameInput.classList.add("invalid");
        result = false;
    }

    if (emailInput.value.length == 0) {
        
        emailFeedback.innerHTML = "Nem adtál meg email címet!";
        emailFeedback.classList.remove("d-none");
        emailInput.classList.add("invalid");
        result = false;
    }

    if (passwordInput.value.length == 0) {
        
        confirmFeedback.innerHTML = "Nem adtál meg jelszót!";
        confirmFeedback.classList.remove("d-none");
        passwordInput.classList.add("invalid");
        confirmInput.classList.add("invalid");
        result = false;
    }

    if (confirmInput.value != passwordInput.value) {
        
        passwordInput.classList.add("invalid");
        confirmInput.classList.add("invalid");
        confirmFeedback.innerHTML = "A két jelszó nem egyezik!";
        confirmFeedback.classList.remove("d-none");
        result = false;
    }

    return result;
}

function onChange() {
    
    let password = document.querySelector("#password");
    let confirm = document.querySelector("#confirmPassword");
    let confirmFeedback = document.querySelector("#confirmFeedback");
    if (confirm.value === password.value || confirm.value == "" || password.value == "") {
        password.classList.remove("invalid");
        confirm.classList.remove("invalid");
        if (!confirmFeedback.classList.contains("d-none")) {
            confirmFeedback.classList.add("d-none");
        }

    } else {
        password.classList.add("invalid");
        confirm.classList.add("invalid");
        confirmFeedback.classList.remove("d-none");
    }
}

function validateEmail() {
    let emailInput = document.querySelector("#email");
    let emailFeedback = document.querySelector("#emailFeedback");
    let regex = /\S+@\S+\.\S+/;



    if (regex.test(emailInput.value)) {
        emailFeedback.classList.add("d-none");
        emailInput.classList.remove("invalid");
        return true;
    } else {
        emailFeedback.innerHTML = "Helytelen e-mail cím!"
        emailFeedback.classList.remove("d-none");
        emailInput.classList.add("invalid");
        return false;
    }
}



document.querySelector("form").addEventListener("submit", submitOverride);

