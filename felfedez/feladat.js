function sendXhr() {
    let form = document.querySelector("#form");
    let request = new XMLHttpRequest();

    request.onload = function () {
        if (request.status = 200) {
            modifyButtonText(request.responseText);
        }
    }

    request.open("POST", "index.php");
    let formData = new FormData(form);
    request.send(formData);
}

function getElementData() {
    let form = document.querySelector("#newform");
    let request = new XMLHttpRequest();

    request.onload = function () {
        if (request.status = 200) {
            let response = request.responseText.replace("\\r\\n", "");

            console.log(JSON.parse(response));
        }
    }

    request.open("POST", "index.php");
    let formData = new FormData(form);
    request.send(formData);
}

function modifyButtonText(text) {
    let button = document.querySelector("#gomb");
    button.innerHTML = text + " vagy!";
}