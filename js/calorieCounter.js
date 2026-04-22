function animateTo(percent) {
    let foreground = document.querySelector("#foreground");
    if (percent > 100) {
        percent = 100;
    }
    foreground.style.setProperty("--current_fill_percent", Number(percent) / 100);
    if (percent == 100) {
        foreground.style.setProperty("stroke", getComputedStyle(foreground).getPropertyValue("--danger_color"));
    } else {
        foreground.style.setProperty("stroke", getComputedStyle(foreground).getPropertyValue("--normal_color"));
    }
}

function mealAnimateTo(mealTypeNumber, percent) {
    let foreground = document.querySelectorAll(".circleFg")[mealTypeNumber];
    if (percent > 100) {
        percent = 100;
    }
    foreground.style.setProperty("--current_fill_percent", Number(percent) / 100);
    
}

var mealType = "";

function openModal(mealtype = "") {
    setMealtype(mealtype);
    updateList();
    document.querySelector("dialog").showModal();
}

function setMealtype(mealtype) {
    let heading = document.querySelector("#mealName");
    let label = document.querySelector("#mealSearchId");

    mealType = mealtype;

    if (mealtype == "breakfast") {
        heading.innerHTML = "Reggeli";
        label.innerHTML = "Mit reggeliztél?";
    }

    if (mealtype == "lunch") {
        heading.innerHTML = "Ebéd";
        label.innerHTML = "Mit ebédeltél?";
    }

    if (mealtype == "dinner") {
        heading.innerHTML = "Vacsora";
        label.innerHTML = "Mit vacsoráztál?";
    }

    if (mealtype == "snack") {
        heading.innerHTML = "Nassolnivalók";
        label.innerHTML = "Mit nassoltál?";
    }
}

function closeModal() {
    document.querySelector("dialog").close();
}

var foodstuff = [];
var currentFood;
var IngredientAndQuantityList = [];

var breakfast = [];
var lunch = [];
var dinner = [];
var snack = [];

var selectedActivity = null;
var activityList = [];
var activityToModify = null;
var addedActivitiesWithDuration = [];
var userData;
var caloriesConsumed = 0;
var caloriesBurnt = 0;
var canAnimate = false;

var date = document.querySelector("#dateInput").value;


function getFoodstuff() {
    
    let request = new XMLHttpRequest();
    request.onload = function () {
        if (request.status == 200) {
            let ingredientListJSON = JSON.parse(request.responseText);

            for (let i = 0; i < ingredientListJSON.length; i++) {
                let ingredient = Ingredient.build(ingredientListJSON[i]);

                foodstuff.push(ingredient);

            }
        }
    }


    request.open("GET", "api/IngredientController.php?foodstuff");
    request.send();
}

getFoodstuff();

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

    for (let i = 0; i < foodstuff.length; i++) {
        let ingredient = foodstuff[i];
        if (ingredient.name.toLowerCase().trim().startsWith(searchText.toLowerCase())) {
            returnArr.push(ingredient);
        }
    }

    if (searchText.length >= 3 || returnArr.length < 10) {
        for (let i = 0; i < foodstuff.length; i++) {
            let ingredient = foodstuff[i];
            if (ingredient.name.toLowerCase().trim().includes(searchText.toLowerCase())) {
                if (!returnArr.includes(ingredient)) {
                    returnArr.push(ingredient);
                }

            }
        }
    }


    return returnArr;
}

function searchIngredients() {
    let input = document.querySelector("#ingredientSearch");
    let searchText = input.value;
    let resultUl = document.querySelector("#resultList");
    resultUl.innerHTML = "";

    if (searchText.length > 0) {
        let resultContainer = document.querySelector("#searchResults");
        resultContainer.classList.remove("hidden");
        resultContainer.classList.add("visible");

        let searchResults = searchListWithText(searchText);

        for (let i = 0; i < searchResults.length; i++) {

            resultUl.innerHTML += generateListItemForIngredient(searchResults[i]);
        }
    }

}

function closeList() {
    let resultContainer = document.querySelector("#searchResults");
    let input = document.querySelector("#ingredientSearch");
    resultContainer.classList.remove("visible");
    resultContainer.classList.add("hidden");
    input.value = "";
}




function onIngredientClick(element) {
    let foodId = Number(element.getAttribute("foodid"));

    let quantityInput = document.querySelector("#quantity");
    quantityInput.value = 100;

    let saveModifyButton = document.querySelector("#saveModifyButton");
    saveModifyButton.setAttribute("onclick", "addFoodToMeal()");
    saveModifyButton.innerHTML = "Hozzáad";

    for (let i = 0; i < foodstuff.length; i++) {
        if (foodstuff[i].id == foodId) {

            currentFood = foodstuff[i];
            setModalData(foodstuff[i]);
            break;
        }
    }

    closeList();


    swipeLeft();

}

function addFoodToMeal() {
    let ingredientAndQuantity = new IngredientAndQuantity(currentFood, Number(document.querySelector("#quantity").value));
    ingredientAndQuantity.setMealType(mealType);    
    IngredientAndQuantityList.push(ingredientAndQuantity);

    updateList();
    swipeRight();

}

function updateList() {
    let list = document.querySelector("#mealList");
    let fullHtml = "";

    
    for (let i = 0; i < IngredientAndQuantityList.length; i++) {
        let food = IngredientAndQuantityList[i].ingredient;

        if (IngredientAndQuantityList[i].mealType == mealType) {
            let quantity = IngredientAndQuantityList[i].quantity;
            let html = `<li class="w-100 d-flex flex-row justify-content-between align-items-center">
                                <div>
                                    <div class="nameDiv">
                                        ${food.name}
                                    </div>
                                    <div class="quantityDiv text-muted">
                                        <small>${quantity} g</small>
                                    </div>
                                </div>

                                <div class="calDiv flex-shrink-0">
                                    ${Math.ceil(food.calories * quantity / 100)} kcal
                                    <button type="button" class="btn btn-warning" onclick="modifyFood(${i})"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button type="button" class="btn btn-danger" onclick="deleteFood(${i})"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </li>`;
            fullHtml += html;
        }


    }

    list.innerHTML = fullHtml;
}

function deleteFood(index) {
    IngredientAndQuantityList.splice(index, 1);
    updateList();
}

function modifyFood(index) {

    let ingredientAndQuantity = IngredientAndQuantityList[index];
    let quantityInput = document.querySelector("#quantity");
    quantityInput.value = ingredientAndQuantity.quantity;
    let saveModifyButton = document.querySelector("#saveModifyButton");
    saveModifyButton.setAttribute("onclick", `executeFoodModify(${index})`);
    saveModifyButton.innerHTML = "Módosít";
    currentFood = ingredientAndQuantity.ingredient;
    setModalData(ingredientAndQuantity.ingredient);
    swipeLeft();
}

function executeFoodModify(index) {
    let ingredientAndQuantity = IngredientAndQuantityList[index];
    let quantityInput = document.querySelector("#quantity");
    let newQuantity = Number(quantityInput.value);

    if (newQuantity == ingredientAndQuantity.quantity) {
        return;
    }

    ingredientAndQuantity.quantity = newQuantity;
    updateList();
    swipeRight();
}

function swipeLeft() {
    let dialogQuantity = document.querySelector("#dialogcontainer");
    dialogQuantity.classList.remove("outofdialog");
    dialogQuantity.classList.add("insideofdialog");
}

function swipeRight() {
    let dialogQuantity = document.querySelector("#dialogcontainer");
    dialogQuantity.classList.remove("insideofdialog");
    dialogQuantity.classList.add("outofdialog");
}

function setModalData(ingredient) {
    let quantityInput = document.querySelector("#quantity");
    let quantity = Number(quantityInput.value);
    let name = document.querySelector("#foodName");
    let cal = document.querySelector("#modalCal");
    let prot = document.querySelector("#modalProt");
    let ch = document.querySelector("#modalCh");
    let fat = document.querySelector("#modalFat");

    name.innerHTML = ingredient.name;
    cal.textContent = Math.ceil(Number(ingredient.calories) * (quantity / 100));
    prot.textContent = Math.ceil(Number(ingredient.protein) * (quantity / 100));
    ch.textContent = Math.ceil(Number(ingredient.carbohydrate) * (quantity / 100));
    fat.textContent = Math.ceil(Number(ingredient.fat) * (quantity / 100));

}

function onQuantityChange() {
    setModalData(currentFood);
}

function majaz() {
    let dialogQuantity = document.querySelector("#dialogcontainer");

    if (dialogQuantity.classList.contains("outofdialog")) {
        dialogQuantity.classList.remove("outofdialog");
        dialogQuantity.classList.add("insideofdialog");
    } else {
        dialogQuantity.classList.remove("insideofdialog");
        dialogQuantity.classList.add("outofdialog");
    }

}

function saveButton() {
    
    let request = new XMLHttpRequest();
    let formData = new FormData();
    request.onload = function () {
        if (request.status = 200) {
            closeModal();
            getData();
        }

    };

    request.open("POST", "api/CalorieCounterController.php");
    let data = JSON.stringify(getListForCurrentMealType());
    formData.append("mealType", mealType);
    formData.append("foodList", data);
    formData.append("date", date);

    request.send(formData);

    updateIndicators();
}

function getListForCurrentMealType() {
    let list = [];
    for (let i = 0; i < IngredientAndQuantityList.length; i++) {
        if (IngredientAndQuantityList[i].mealType == mealType) {
            list.push(IngredientAndQuantityList[i]);
        }
    }

    return list;
}

function updateIndicators() {


    caloriesBurnt = 0;
    for (let i = 0; i < addedActivitiesWithDuration.length; i++) {
        let userActivity = addedActivitiesWithDuration[i];
        let calBurned = Math.floor(userActivity.activity.met * userData.weight * (userActivity.durationMinutes / 60));
        caloriesBurnt += calBurned;
    }

    let targeCaloriesArr = getMealTargetCalories();
    let consumedCaloriesArr = getMealConsumedCalories();
    caloriesConsumed = 0;

    let consumedMacronutrients = getMacroNutrients();
    let targetMacros = getMacroTargets();

    let calBurntSpan = document.querySelector("#totalBurntCalories");
    
    let totalTargetCalories = userData.calorieTarget + caloriesBurnt;

    let consumedSpan = document.querySelector("#totalConsumed");
    let remainingSpan = document.querySelector("#totalRemaining");

    for (let i = 0; i < consumedCaloriesArr.length; i++) {
        caloriesConsumed += consumedCaloriesArr[i];
    }

    
    animateValue(consumedSpan, caloriesConsumed, 1000);
    animateValue(remainingSpan, totalTargetCalories - caloriesConsumed, 1000);
    animateValue(calBurntSpan, caloriesBurnt, 1000);

    animateTo(caloriesConsumed / totalTargetCalories * 100);

    updateMealDials(targeCaloriesArr, consumedCaloriesArr);
    updateMealTargets(targeCaloriesArr);
    updateMealNumbers(consumedCaloriesArr);
    updateMacroTargets(targetMacros);
    updateMacrosConsumed(consumedMacronutrients);
    updateMacroProgress(consumedMacronutrients, targetMacros);

}

function updateMacroProgress(consumed, target) {
    let progressBars = document.querySelectorAll(".macroProgress");
    for (let i = 0; i < progressBars.length; i++) {
        let percent = Math.floor(consumed[i] / target[i] * 100);
        if (percent > 100) {
            percent = 100;
        }
        
        animateProgressValue(progressBars[i], percent, 1000);
        
    }
}

function updateMacroTargets(targets) {
    let spans = document.querySelectorAll(".macroTarget");
    for (let i = 0; i < spans.length; i++) {
    
        animateValue(spans[i], targets[i], 1000);
    }
}

function updateMacrosConsumed(consumed) {
    let spans = document.querySelectorAll(".macroConsumed");
    for (let i = 0; i < spans.length; i++) {
        
        animateValue(spans[i], consumed[i], 1000);
    }
}

function animateValue(obj, end, duration) {

    let startTimestamp = null;
    let start = Number(obj.textContent);
    if (!canAnimate) {
        start = end;
    }
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);


}

function animateProgressValue(obj, end, duration) {

    let startTimestamp = null;
    let start = Number(obj.value);
    if (!canAnimate) {
        start = end;
    }
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.value = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);


}

function getMealTargetCalories() {
    let targetCalArr = [];
    let multiplierArr = [0.3, 0.4, 0.25, 0.05];
    
    for (let i = 0; i < multiplierArr.length; i++) {
        cal = Math.floor((userData.calorieTarget + caloriesBurnt) * multiplierArr[i]);
        targetCalArr.push(cal);
    }
    return targetCalArr;
}

function getMealConsumedCalories() {
    let consumedCalories = [];
    consumedCalories.push(getCaloriesForMealType("breakfast"));
    consumedCalories.push(getCaloriesForMealType("lunch"));
    consumedCalories.push(getCaloriesForMealType("dinner"));
    consumedCalories.push(getCaloriesForMealType("snack"));

    return consumedCalories;
}

function updateMealDials(targetCalories, consumedCalories) {
    for (let i = 0; i < targetCalories.length; i++) {
        let percent = (consumedCalories[i] / targetCalories[i]) * 100;
        mealAnimateTo(i, percent.toFixed(0));
    }
}

function updateMealNumbers(consumedCalories) {
    let spans = document.querySelectorAll(".consumed");
    for (let i = 0; i < spans.length; i++) {
        animateValue(spans[i], consumedCalories[i], 1000);
    }
}

function updateMealTargets(targetCalories) {
    let spans = document.querySelectorAll(".targetMealCal");
    for (let i = 0; i < spans.length; i++) {
        animateValue(spans[i], targetCalories[i], 1000);
    }
}

function getCaloriesForMealType(mType) {
    let mealCalories = 0;
    for (let i = 0; i < IngredientAndQuantityList.length; i++) {
        if (IngredientAndQuantityList[i].mealType == mType) {
            let Ingredient = IngredientAndQuantityList[i].ingredient;
            let quantity = IngredientAndQuantityList[i].quantity;
            mealCalories += Math.ceil((Ingredient.calories / 100) * quantity);
        }

    }
    return Number(mealCalories);
}

function getData() {
    IngredientAndQuantityList = [];
    let request = new XMLHttpRequest();
    request.onload = function () {
        if (request.status == 200) {

            let response = request.responseText.split("\n");

            let mealData = JSON.parse(response[0]);
            
            breakfast = mealData.breakfast.mealQuantities;
            lunch = mealData.lunch.mealQuantities;
            dinner = mealData.dinner.mealQuantities;
            snack = mealData.snack.mealQuantities;

            addDataToIngredientList(breakfast, "breakfast");
            addDataToIngredientList(lunch, "lunch");
            addDataToIngredientList(dinner, "dinner");
            addDataToIngredientList(snack, "snack");

            userData = JSON.parse(response[1]);
            activityList = JSON.parse(response[2]);
            addedActivitiesWithDuration = JSON.parse(response[3]);

            updateIndicators();
            displayAddedActivities();

            canAnimate = true;
        }

    };

    request.open("GET", "api/CalorieCounterController.php?date=" + date);
    request.send();
}

function getActivityById(id) {
    for (let i = 0; i < activityList.length; i++) {
        if (activityList[i].id == id) {
            return activityList[i];
        }
    }
}


function addDataToIngredientList(mealArray, type) {
    if (mealArray.length > 0) {
        for (let i = 0; i < mealArray.length; i++) {
            let food = mealArray[i].food;
            let quantity = mealArray[i].quantity;
            let iq = new IngredientAndQuantity(new Ingredient(food.id, food.name, food.unit, food.calories, food.protein, food.carbohydrate, food.fat), quantity, type);
            IngredientAndQuantityList.push(iq);
        }
    } else {
        
    }

}

function openActivityModal() {
    let activityDialog = document.querySelector("#activityDialog");
    let saveButton = document.querySelector("#saveActivityButton");

    selectOption(-1);

    saveButton.setAttribute("onclick", "saveActivity()");
    activityDialog.showModal();
}

function openActivityModalForModification() {
    let activityDialog = document.querySelector("#activityDialog");
    let saveButton = document.querySelector("#saveActivityButton");
    let durationInput = activityDialog.querySelector("#duration");

    selectOption(activityToModify.activity.id);
    selectedActivity = activityToModify.activity;
    durationInput.value = activityToModify.durationMinutes;
    onChangeDuration();
    saveButton.setAttribute("onclick", "saveActivityModification()");
    activityDialog.showModal();
}

function selectOption(actId) {
    let select = document.querySelector("#activity");
    let options = select.querySelectorAll("option");

    for (let i = 0; i < options.length; i++) {
        if (options[i].value == actId) {
            options[i].selected = true;
        }
    }

    if (actId == -1) {
        select.disabled = false;
    } else {
        select.disabled = true;
    }
}

function closeActivityModal() {
    
    let activityDialog = document.querySelector("#activityDialog");
    let duratoinInput = activityDialog.querySelector("#duration");
    duratoinInput.value = 0;
    selectedActivity = null;
    activityDialog.close();
}

function onChangeDuration() {
    calculateBurntCaloriesForCurrentActivity();
}

function onActivitySelection(element) {
    let activityId = Number(element.value);

    if (activityId != -1) {
        selectedActivity = getActivityById(activityId);
    }

    calculateBurntCaloriesForCurrentActivity();

}

function calculateBurntCaloriesForCurrentActivity() {
    let calSpan = document.querySelector("#burntCalories");
    if (selectedActivity != null) {
        let duration = Number(document.querySelector("#duration").value);
        let weight = Number(userData.weight);
        let calBurnt = Math.floor(Number(selectedActivity.met) * weight * (duration / 60));
        calSpan.innerHTML = calBurnt;

    } else {
        calSpan.innerHTML = "0";
    }
}

function saveActivity() {
    let request = new XMLHttpRequest();
    let formData = new FormData();
    let duration = Number(document.querySelector("#duration").value);

    request.onload = function () {
        if (request.status == 200) {
            addedActivitiesWithDuration = JSON.parse(request.responseText);
            displayAddedActivities();
            getData();

            closeActivityModal();
        }
    }

    formData.append("activityinsert", 1);
    formData.append("activity", JSON.stringify(selectedActivity));
    formData.append("duration", duration);
    formData.append("date", date);

    request.open("POST", "api/CalorieCounterController.php");
    request.send(formData);
}

function displayAddedActivities() {

    let inner = "";

    if(addedActivitiesWithDuration.length == 0){
        inner+="<li>Még nem adtál meg tevékenységeket</li>"
    }

    for (let i = 0; i < addedActivitiesWithDuration.length; i++) {
        let userActivity = addedActivitiesWithDuration[i];
        let calBurned = Math.floor(userActivity.activity.met * userData.weight * (userActivity.durationMinutes / 60));

        let listItem = `<li class="activityListItem">
                        <div class="row align-items-center">
                            <div class="col-8 text-start">
                                <div class="fw-bold">${userActivity.activity.name}</div>
                                <div class="d-flex flex-row">
                                    <div class=" text-muted fst-italic">${userActivity.durationMinutes} perc</div>
                                    <div class="ms-3 text-muted fst-italic">${calBurned} kcal</div>
                                </div>
                            </div>

                            <div class="col-4 text-end"><button type="button" class="btn btn-warning" onclick="modifyActivity(${userActivity.userActivityId})"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="btn btn-danger" onclick="deleteActivity(this, ${userActivity.userActivityId})"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>

                    </li>`;

        inner += listItem;
    }

    document.querySelector("#addedActivities").innerHTML = inner;


}

function getMacroTargets() {
    let arr = [];

    let totalCal = userData.calorieTarget + caloriesBurnt;
    arr.push(Math.floor(totalCal * 0.2 / 4)); //protein
    arr.push(Math.floor(totalCal * 0.5 / 4)); //carbs
    arr.push(Math.floor(totalCal * 0.3 / 9)); //fat

    return arr;

}

function getMacroNutrients() {
    let arr = [];
    let consumedProtein = 0;
    let consumedCarbohydrate = 0;
    let consumedFat = 0;
    
    for (let i = 0; i < IngredientAndQuantityList.length; i++) {
        let ingredient = IngredientAndQuantityList[i].ingredient;
        let quantity = IngredientAndQuantityList[i].quantity;

        consumedProtein += ingredient.protein * (quantity / 100);
        consumedCarbohydrate += ingredient.carbohydrate * (quantity / 100);
        consumedFat += ingredient.fat * (quantity / 100);
    }

    arr.push(Math.floor(consumedProtein));
    arr.push(Math.floor(consumedCarbohydrate));
    arr.push(Math.floor(consumedFat));

    return arr;
}

function saveActivityModification() {
    let request = new XMLHttpRequest();
    let formData = new FormData();

    let durationInput = document.querySelector("#duration");
    let modifiedDuration = durationInput.value;

    if (modifiedDuration == activityToModify.durationMinutes) {
        closeActivityModal();
        return;
    } else {
        activityToModify.durationMinutes = modifiedDuration;
    }

    request.onload = function () {
        if (request.status == 200) {
            getData();
            updateIndicators();
            displayAddedActivities();
            closeActivityModal();
        }
    }

    formData.append("activityUpdate", "1");
    formData.append("userActivityId", activityToModify.userActivityId);
    formData.append("newDuration", activityToModify.durationMinutes);

    request.open("POST", "api/CalorieCounterController.php");
    request.send(formData);

}

function modifyActivity(userActivityId) {
    activityToModify = getUserActivityById(userActivityId);
    openActivityModalForModification();
}

function getUserActivityById(userActivityId) {
    
    for (let i = 0; i < addedActivitiesWithDuration.length; i++) {
        if (addedActivitiesWithDuration[i].userActivityId == userActivityId) {
            return addedActivitiesWithDuration[i];
        }
    }
}

function deleteActivity(element, id) {
    let li = element.parentElement.parentElement.parentElement;
    li.classList.add("hide");

    setTimeout(function () {
        doActivityDelete(id);
    }, 400);

}

function doActivityDelete(id) {
    let request = new XMLHttpRequest();
    let formData = new FormData;
    request.onload = function () {

        getData();
        updateIndicators();
        displayAddedActivities();

    };

    formData.append("deleteUserActivity", "1");
    formData.append("userActivityId", id);

    request.open("POST", "api/CalorieCounterController.php");
    request.send(formData);
}

function onDateChange() {
    date = document.querySelector("#dateInput").value;
    getData();
}

function decrementDate() {
    let dateObj = new Date(date);

    dateObj.setDate(dateObj.getDate() - 1);
    date = dateObj.toISOString().split("T")[0];

    document.querySelector("#dateInput").value = date;
    getData();
    checkButtons();
}

function incrementDate() {
    let dateObj = new Date(date);

    dateObj.setDate(dateObj.getDate() + 1);
    date = dateObj.toISOString().split("T")[0];

    document.querySelector("#dateInput").value = date;
    getData();
    checkButtons();
}

function checkButtons() {
    let dateObj = new Date(date);
    let minDate = new Date(document.querySelector("#dateInput").getAttribute("min"));
    let maxDate = new Date(document.querySelector("#dateInput").getAttribute("max"));

    let incrementBtn = document.querySelector("#dateIncrement");
    let decrementBtn = document.querySelector("#dateDecrement");

    if (dateObj.getTime() == minDate.getTime()) {
        decrementBtn.disabled = true;
    } else {
        decrementBtn.disabled = false;
    }

    if (dateObj.getTime() == maxDate.getTime()) {
        incrementBtn.disabled = true;
    } else {
        incrementBtn.disabled = false;
    }
}

getData();
checkButtons();
