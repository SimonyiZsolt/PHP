var ingredients = [];
var selectedIngredient = null;

var ingredientAndQuantityList = [];
var currentIndex = 0;

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener('hide.bs.modal', function (event) {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});

getIngredientList();
function getIngredientList() {
    var request = new XMLHttpRequest();

    request.onload = function () {
        if (request.status = 200) {
            
            let ingredientListJSON = JSON.parse(request.responseText);

            for (let i = 0; i < ingredientListJSON.length; i++) {
                let ingredient = Ingredient.build(ingredientListJSON[i]);

                ingredients.push(ingredient);

            }

        }

    };

    request.open("GET", "api/IngredientController.php", true);
    request.send();
}

function searchIngredients() {
    let input = document.querySelector("#ingredientSearch");
    let searchText = input.value;
    let resultUl = document.querySelector("#resultList");
    resultUl.innerHTML = "";


    if (searchText.length > 1) {
        let resultContainer = document.querySelector("#searchResults");
        resultContainer.classList.remove("d-none");
        resultContainer.classList.remove("hidden");
        resultContainer.classList.add("visible");

        let searchResults = searchListWithText(searchText);

        for (let i = 0; i < searchResults.length; i++) {

            resultUl.innerHTML += generateListItemForIngredient(searchResults[i]);
        }
    }



}

function generateListItemForIngredient(ingredient) {
    let str =
        `<li onclick="onIngredientClick(this)" foodid="${ingredient.id}">
            <div class="row w-100 mx-auto" >
                <div class="col-6 px-0">
                    <div>${ingredient.name}</div>
                    <div><small class="text-muted d-block"></small></div>
                </div>

                <div class="text-end col-6 px-0 py-2">${ingredient.calories} kCal/100 g</div>
        
        </div>

        </li>`;

    return str;
}

function searchListWithText(searchText) {
    let returnArr = [];

    for (let i = 0; i < ingredients.length; i++) {
        let ingredient = ingredients[i];
        if (ingredient.name.toLowerCase().trim().startsWith(searchText.toLowerCase())) {
            returnArr.push(ingredient);
        }
    }

    if (searchText.length >= 3 || returnArr.length < 10) {
        for (let i = 0; i < ingredients.length; i++) {
            let ingredient = ingredients[i];
            if (ingredient.name.toLowerCase().trim().includes(searchText.toLowerCase())) {
                if (!returnArr.includes(ingredient)) {
                    returnArr.push(ingredient);
                }

            }
        }
    }


    return returnArr;
}

function closeList() {
    let resultContainer = document.querySelector("#searchResults");
    resultContainer.classList.remove("visible");
    resultContainer.classList.add("hidden");
}

function onIngredientClick(element) {
    let id = element.getAttribute("foodid");
    let ingredient = getIngredientById(id);
    let addBtn = document.querySelector("#addToListBtn");
    let modifyBtn = document.querySelector("#modifyListBtn");

    let quantity = document.querySelector("#quantity");
    quantity.value = 100;

    addBtn.classList.remove("d-none");
    modifyBtn.classList.add("d-none");

    let title = document.querySelector("#ingredientModalLabel");
    title.textContent = ingredient.name;
    selectedIngredient = ingredient;

    setModalData(ingredient, 100);

    let modal = new bootstrap.Modal(document.querySelector("#ingredientModal"));
    let input = document.querySelector("#ingredientSearch");
    input.value = "";

    modal.show();
}

function setModalData(ingredient) {
    let quantityInput = document.querySelector("#quantity");
    let quantity = Number(quantityInput.value);
    let cal = document.querySelector("#modalCal");
    let prot = document.querySelector("#modalProt");
    let ch = document.querySelector("#modalCh");
    let fat = document.querySelector("#modalFat");

    cal.textContent = Math.ceil(Number(ingredient.calories) * (quantity / 100));
    prot.textContent = Math.ceil(Number(ingredient.protein) * (quantity / 100));
    ch.textContent = Math.ceil(Number(ingredient.carbohydrate) * (quantity / 100));
    fat.textContent = Math.ceil(Number(ingredient.fat) * (quantity / 100));

}

function onQuantityChange() {
    setModalData(selectedIngredient);
}

function addIngredient() {
    let quantityInput = document.querySelector("#quantity");
    let quantity = Number(quantityInput.value);

    let ingredientAndQuantity = new IngredientAndQuantity(selectedIngredient, quantity);
    ingredientAndQuantityList.push(ingredientAndQuantity);

    refreshIngredientList();
}

function refreshIngredientList() {
    let ingredientList = document.querySelector("#ingredientList");
    ingredientList.innerHTML = "";

    let totalCal = 0;
    let totalProt = 0;
    let totalCh = 0;
    let totalFat = 0;

    let totalWeight = 0;
    for (let i = 0; i < ingredientAndQuantityList.length; i++) {
        let element = ingredientAndQuantityList[i];

        let str = `<li foodid="${element.ingredient.id}"class="ingredientli mb-2 py-2 px-1">
                            <div class="w-100 d-flex flex-row justify-content-between">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class=" flex-shrink-0">${element.quantity} g</div>
                                    <div class="flex-shrink-1">${element.ingredient.name}</div>
                                </div>
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <div class="me-2 flex-shrink-1">${Math.ceil(element.ingredient.calories * element.quantity / 100)} kCal</div>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="btn btn-outline-warning" onclick="modifyListItem(${i})"><i class="fa-regular fa-pen-to-square fa-lg"></i></button>
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteListItem(${i})"><i class="fa-regular fa-trash-can fa-lg"></i></button>
                                    </div>

                                </div>
                            </div>
                        </li>`

        ingredientList.innerHTML += str;

        totalWeight += element.quantity;

        totalCal += Math.ceil(element.ingredient.calories * element.quantity / 100);
        totalProt += Math.ceil(element.ingredient.protein * element.quantity / 100);
        totalCh += Math.ceil(element.ingredient.carbohydrate * element.quantity / 100);
        totalFat += Math.ceil(element.ingredient.fat * element.quantity / 100);
    }

    if (totalWeight != 0) {
        totalCal = Math.ceil((totalCal / totalWeight) * 100);
        totalProt = Math.ceil((totalProt / totalWeight) * 100);
        totalCh = Math.ceil((totalCh / totalWeight) * 100);
        totalFat = Math.ceil((totalFat / totalWeight) * 100);
    } else {
        totalCal = 0;
        totalProt = 0;
        totalCh = 0;
        totalFat = 0;
    }

    updateCalorieTable(totalCal, totalProt, totalCh, totalFat);
}

function updateCalorieTable(calories, protein, carbs, fat) {
    document.querySelector("#totalCal").textContent = calories + " kCal";
    document.querySelector("#totalProt").textContent = protein + " g";
    document.querySelector("#totalCh").textContent = carbs + " g";
    document.querySelector("#totalFat").textContent = fat + " g";
}


function getIngredientById(id) {
    for (let i = 0; i < ingredients.length; i++) {
        if (ingredients[i].id == id) {
            return ingredients[i];
        }
    }
}

function onImageChange() {
    let fileInput = document.querySelector("#image");
    let preview = document.querySelector("#preview");
    preview.src = URL.createObjectURL(fileInput.files[0]);
}

function validate(title, description) {
    let messageBox = document.querySelector("#messageBox");
    let valid = true;
    messageBox.innerHTML = "";
    if (title.value.length == 0) {
        messageBox.innerHTML = messageBox.innerHTML + `<div class="alert alert-danger">Nem adtál meg címet!</div>`;
        valid = false;
    }

    if (description.value.length == 0) {
        messageBox.innerHTML = messageBox.innerHTML + `<div class="alert alert-danger">Nem írtad le az elkészítés menetét!</div>`;
        valid = false;
    }

    if (ingredientAndQuantityList.length == 0) {
        messageBox.innerHTML = messageBox.innerHTML + `<div class="alert alert-danger">Nem adtál meg hozzávalókat!</div>`;
        valid = false;
    }
    return valid;
}

function saveRecipe() {
    let title = document.querySelector("#title");
    let description = document.querySelector("#description");

    if (!validate(title, description)) {
        return;
    }

    let fileInput = document.querySelector("#image");

    let recipe = new Recipe(title.value, ingredientAndQuantityList, description.value);

    let data = JSON.stringify(recipe);
    

    let request = new XMLHttpRequest();
    let formData = new FormData();
    formData.append("title", title.value);
    formData.append("ingredientAndQuantityList", JSON.stringify(ingredientAndQuantityList));
    formData.append("description", description.value);
    formData.append("recipeImg", fileInput.files[0]);
    request.onload = function () {
        
        if (request.status == 200) {
            document.querySelector("#successModal").showModal();
        }


    };
    request.open("POST", "api/RecipeController.php");
    request.send(formData);
}

function openNewIngredientModal() {
    let modal = document.querySelector("#newIngredientModal");
    modal.showModal();
}

function closeNewIngredientModal() {
    let modal = document.querySelector("#newIngredientModal");
    modal.close();
}

function showAlertModal(success = true) {
    let modal = document.querySelector("#messageModal");
    let alertDiv = document.querySelector("#alert");

    if (success) {
        alertDiv.classList.add("alert-success");
        alertDiv.innerHTML = "Hozzávaló sikeresen mentve!";
    } else {
        alertDiv.classList.add("alert-danger");
        alertDiv.innerHTML = "Valami hiba történt!";
    }

    modal.showModal();
}

function dismissAlert() {
    let modal = document.querySelector("#messageModal");
    let alertDiv = document.querySelector("#alert");

    modal.close();
    alertDiv.classList.remove("alert-success", "alert-danger");
}

function saveNewIngredient() {
    let form = document.querySelector("#addIngredientForm");
    let formData = new FormData(form);
    let request = new XMLHttpRequest();

    request.onload = function () {
        if (request.status == 200) {
        
            let ingredientParsed = JSON.parse(request.responseText);
            let ingredient = Ingredient.build(ingredientParsed);
            ingredients.push(ingredient);
            form.reset();
            closeNewIngredientModal();
            showAlertModal();

        } else {
            
            showAlertModal(false);
        }

    }

    request.open("POST", "api/IngredientController.php");
    request.send(formData);
}

function modifyListItem(index) {
    
    onIngredientModify(index);
}

function onIngredientModify(index) {

    let ingredient = ingredientAndQuantityList[index].ingredient;
    let addBtn = document.querySelector("#addToListBtn");
    let modifyBtn = document.querySelector("#modifyListBtn");

    addBtn.classList.add("d-none");
    modifyBtn.classList.remove("d-none");

    let quantity = document.querySelector("#quantity");
    quantity.value = ingredientAndQuantityList[index].quantity;

    let title = document.querySelector("#ingredientModalLabel");
    title.textContent = ingredient.name;
    selectedIngredient = ingredient;

    setModalData(ingredient);

    let modal = new bootstrap.Modal(document.querySelector("#ingredientModal"));

    let input = document.querySelector("#ingredientSearch");
    input.value = "";

    currentIndex = index;
    modal.show();
}

function saveModification() {
    let quantityInput = document.querySelector("#quantity");
    let quantity = Number(quantityInput.value);

    let ingredientAndQuantity = ingredientAndQuantityList[currentIndex];
    ingredientAndQuantity.quantity = quantity;
    refreshIngredientList();
}

function deleteListItem(index) {
    
    ingredientAndQuantityList.splice(index, 1);
    refreshIngredientList();
}
