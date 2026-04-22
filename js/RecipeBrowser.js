
getRecipes();

function getRecipes() {
    let request = new XMLHttpRequest();
    request.open("GET", "api/RecipeController.php?method=simple");

    request.onload = function () {

        recipeArray = JSON.parse(request.responseText);
        
        createRecipeCards(recipeArray);
    }

    request.send();
}

function createRecipeCards(recipeArray) {

    let container = document.querySelector("#recipeContainer");
    let htmlString = "";

    for (let i = 0; i < recipeArray.length; i++) {
        let element = recipeArray[i];

        let card = `<div recipeId="${element.id}" class="recipeCardContainer col-12 col-sm-6 col-lg-4 p-2">
                <div class="recipeCard">
                    <div class="recipeCardHeader">
                        <div class="recipeTitleText fw-bold">${element.title}</div>
                    </div>
                    <div class="recipeCardBody d-flex flex-row">
                        <div class="recipeImageDiv w-50">
                            <img class="recipeImg" src="${element.imageURL == "" ? "img/noimage.png" : "img/uploads/" + element.imageURL}">
                        </div>
                        <div class="recipeDataDiv w-50 d-flex flex-column justify-content-between">
                            <div class="w-100 flex-grow-1 d-flex flex-column justify-content-center">
                                <div class="recipeDataText text-center"><i class="fireIcon fa-solid fa-fire-flame-curved  me-2"></i>${element.calories} kcal</div>
                            </div>

                            <a href="${"recipes?id=" + element.id}" class="btn btn-dark m-2">Részletek</a>
                        </div>
                    </div>
                </div>
            </div>`;

        htmlString += card;

    }

    container.innerHTML = htmlString;
}

function checkModalInputs() {
    let goalInput = document.querySelector("#goal");
    let ageInput = document.querySelector("#age");
    let heightInput = document.querySelector("#height");
    let weightInput = document.querySelector("#weight");
    let genderInput = document.querySelector("#gender");
    let submitbutton = document.querySelector("#modalSubmit");

    let isActivitySelected = checkActitvitySelected();

    if (goalInput.value != "NA" && ageInput.value != "" && heightInput.value != "" && weightInput.value != "" && genderInput.value != "NA" && isActivitySelected) {
        submitbutton.disabled = false;
    } else {
        submitbutton.disabled = true;
    }
}

function checkActitvitySelected() {
    let radios = document.querySelectorAll(".form-check-input");

    for (let i = 0; i < radios.length; i++) {
        let radio = radios[i];
        if (radio.checked) {

            return true;
        }

    }

    return false;
}

function checkRadio(index) {
    document.querySelectorAll(".form-check-input")[index].checked = true;
    checkModalInputs();
}

function sendModalData() {
    let form = document.querySelector("form");
    let formdata = new FormData(form);
    let spinner = document.querySelector("#modalSpinner");
    spinner.classList.remove("d-none");

    let request = new XMLHttpRequest();
    request.onload = function () {
        if (request.status == 200) {
            spinner.classList.add("d-none");
            
            showResult(JSON.parse(request.responseText));
        } else {
            
        }
    }
    request.open("POST", "api/UserController.php");
    setTimeout(function () {
        request.send(formdata);
    }, 500);

}

function closeModal() {
    document.querySelector("dialog").close();
}

function showResult(data) {
    let rmrDiv = document.querySelector("#rmr");
    let bmrDiv = document.querySelector("#bmr");
    let targetDiv = document.querySelector("#calorieTarget");
    let resultTextDiv = document.querySelector("#resultText");

    rmrDiv.innerHTML = data.rmr + " kCal";
    bmrDiv.innerHTML = data.bmr + " kCal";
    targetDiv.innerHTML = data.calorietarget + " kCal";
    let resultText = "";

    if (data.goal == 0) {
        resultText = "A fogyáshoz a kalóriacélnak megfelelő, vagy kevesebb kalóriát kell bevinned naponta."
    } else if (data.goal == 1) {
        resultText = "Testsúlyod megtartásához a kalóriacélnak megfelelő, vagy kevesebb kalóriát kell bevinned naponta."
    } else {
        resultText = "Testsúlyod növeléséhez a kalóriacélnak megfelelő, vagy több kalóriát kell bevinned naponta."
    }

    resultTextDiv.innerHTML = resultText;
    switchPage();
}

function switchPage() {
    let pageContainer = document.querySelector(".pages");
    if (pageContainer.classList.contains("outofdialog")) {
        pageContainer.classList.add("insideofdialog");
        pageContainer.classList.remove("outofdialog");
    } else {
        pageContainer.classList.remove("insideofdialog");
        pageContainer.classList.add("outofdialog");

    }
}
