<?php

if (array_key_exists("user_name", $_SESSION)) {

    include_once("controller/UserDAO.php");
    include_once("controller/ActivityDAO.php");
    include_once("controller/MealDAO.php");

    $userName = $_SESSION["user_name"];
    $userData = UserDAO::getUserDataForUsername($userName);
    $activities = ActivityDAO::getActivityList();
    $startDate = MealDAO::getUserStartDate($userName);
} else {
    header("Location: login");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalóriaszámláló</title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include_once("components/navbar.php"); ?>
    <script>
        <?php echo ("var target = " . $userData->getCalorieTarget()) ?>
    </script>
    <div class="border shadow navbarOffsetMargin rounded container d-flex flex-column align-items-center justify-content-between" style="max-width: 720px;">
        <div class="mt-3 w-100 d-flex flex-row align-items-center justify-content-center">
            <button class="dateButton btn btn-outline-dark" id="dateDecrement" type="button" onclick="decrementDate()">&#128896</button>
            <input id="dateInput" class="p-2 mx-2 border-1 rounded-pill" type="date" oninput="onDateChange()" min="<?php echo ($startDate); ?>" max="<?php echo (date("Y-m-d")); ?>" value="<?php echo (date("Y-m-d")); ?>">
            <button disabled class="dateButton btn btn-outline-dark" id="dateIncrement" type="button" onclick="incrementDate()">&#128898</button>
        </div>
        <div class="w-100 text-start mt-3">
            <h3>Összefoglaló</h3>
            <div class="mx-auto w-100 indicator d-flex flex-row align-items-center justify-content-center position-relative">

                <div class="text-center" style="width: 25%;">
                    <div><span id="totalConsumed">0</span> kcal</div>
                    <div>Elfogyasztott</div>
                </div>
                <div class="w-50 position-relative" style="max-width: 500px;">
                    <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                        
                        <path class="grey" d="M25,85
             A40,40 0 1,1 75,85"
                            style="fill:none;">

                        </path>
                        <path class="foreground" id="foreground" d="M25,85
             A40,40 0 1,1 75,85"
                            style="fill:none;">

                        </path>
                    </svg>
                    <div class="position-absolute d-flex flex-column w-100 h-100 top-0 start-0 justify-content-center align-items-center">
                        <div><span id="totalRemaining"></span> kcal</div>
                        <div>Fennmaradt</div>
                    </div>
                </div>

                <div class="text-center" style="width: 25%;">
                    <div><span id="totalBurntCalories">0 kcal</span> kcal</div>
                    <div>Elégetett</div>
                </div>
            </div>
        </div>





        <div class="d-flex flex-row justify-content-center">
            <div class="row">
                <div class="col-4">
                    <div class="text-center mx-1">
                        <div>Fehérje</div>
                        <div class="text-nowrap"><span class="macroConsumed">0</span> / <span class="macroTarget">100</span> g</div>
                        <progress class="macroProgress w-100" min="0" max="100" value="0"></progress>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center mx-1">
                        <div>Szénhidrát</div>
                        <div class="text-nowrap"><span class="macroConsumed">0</span> / <span class="macroTarget">100</span> g</div>
                        <progress class="macroProgress w-100" min="0" max="100" value="0"></progress>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center mx-1">
                        <div>Zsír</div>
                        <div class="text-nowrap"><span class="macroConsumed">0</span> / <span class="macroTarget">100</span> g</div>
                        <progress class="macroProgress w-100" min="0" max="100" value="0"></progress>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100 mt-3 d-flex flex-column align-items-center justify-content-center">
            <div class="w-100 text-start">
                <h3>Étkezések</h3>
            </div>
            <div class="w-75 border border-1 border-black rounded-5">
                <div class="mealDiv d-flex flex-row justify-content-between align-items-center" id="breakfast">
                    <div class="position-relative d-flex flex-row">
                        <div class="mealIndicator position-relative">
                            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                                <circle class="circleBg" cx="50" cy="50" r="40" stroke="gray" stroke-width="6" fill="none"></circle>
                                <circle class="circleFg" cx="50" cy="50" r="40" transform="rotate(90, 50, 50)" stroke-width="6" fill="none"></circle>
                            </svg>

                            <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center">
                                <img class=" w-50 h-50 img-fluid" src="img/breakfast.png" alt="">
                            </div>
                        </div>
                        <div class="mealTexts d-flex flex-column align-items-start justify-content-center">
                            <div class="mealName fw-bold fs-5">Reggeli</div>
                            <div class="mealData"><span class="consumed">0</span> / <span class="targetMealCal"><?php echo (floor($userData->getCalorieTarget() * 0.3)); ?></span> kCal</div>
                        </div>


                    </div>
                    <div class="buttonDiv">
                        <button class="circle-btn" onclick="openModal('breakfast')">
                            <svg viewBox="0 0 56 56" aria-hidden="true">

                                <rect class="plus" x="14" y="27" width="28" height="1" fill="#fff" rx="2" />
                                <rect class="plus" x="27" y="14" width="1" height="28" fill="#fff" rx="2" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-1 w-75 border border-1 border-black rounded-5">
                <div class="mealDiv d-flex flex-row justify-content-between align-items-center" id="lunch">
                    <div class="position-relative d-flex flex-row">
                        <div class="mealIndicator position-relative">
                            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                                <circle class="circleBg" cx="50" cy="50" r="40" stroke="gray" stroke-width="6" fill="none"></circle>
                                <circle class="circleFg" cx="50" cy="50" r="40" transform="rotate(90, 50, 50)" stroke-width="6" fill="none"></circle>
                            </svg>

                            <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center">
                                <img class=" w-50 h-50 img-fluid" src="img/lunch.png" alt="">
                            </div>
                        </div>
                        <div class="mealTexts d-flex flex-column align-items-start justify-content-center">
                            <div class="mealName fw-bold fs-5">Ebéd</div>
                            <div class="mealData"><span class="consumed">0</span> / <span class="targetMealCal"><?php echo (floor($userData->getCalorieTarget() * 0.4)); ?></span> kCal</div>
                        </div>


                    </div>
                    <div class="buttonDiv">
                        <button class="circle-btn" onclick="openModal('lunch')">
                            <svg viewBox="0 0 56 56" aria-hidden="true">

                                <rect class="plus" x="14" y="27" width="28" height="1" fill="#fff" rx="2" />
                                <rect class="plus" x="27" y="14" width="1" height="28" fill="#fff" rx="2" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-1 w-75 border border-1 border-black rounded-5">
                <div class="mealDiv d-flex flex-row justify-content-between align-items-center" id="dinner">
                    <div class="position-relative d-flex flex-row">
                        <div class="mealIndicator position-relative">
                            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                                <circle class="circleBg" cx="50" cy="50" r="40" stroke="gray" stroke-width="6" fill="none"></circle>
                                <circle class="circleFg" cx="50" cy="50" r="40" transform="rotate(90, 50, 50)" stroke-width="6" fill="none"></circle>
                            </svg>

                            <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center">
                                <img class=" w-50 h-50 img-fluid" src="img/dinner.png" alt="">
                            </div>
                        </div>
                        <div class="mealTexts d-flex flex-column align-items-start justify-content-center">
                            <div class="mealName fw-bold fs-5">Vacsora</div>
                            <div class="mealData"><span class="consumed">0</span> / <span class="targetMealCal"><?php echo (floor($userData->getCalorieTarget() * 0.25)); ?></span> kCal</div>
                        </div>


                    </div>
                    <div class="buttonDiv">
                        <button class="circle-btn" onclick="openModal('dinner')">
                            <svg viewBox="0 0 56 56" aria-hidden="true">

                                <rect class="plus" x="14" y="27" width="28" height="1" fill="#fff" rx="2" />
                                <rect class="plus" x="27" y="14" width="1" height="28" fill="#fff" rx="2" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-1 w-75 border border-1 border-black rounded-5">
                <div class="mealDiv d-flex flex-row justify-content-between align-items-center" id="snack">
                    <div class="position-relative d-flex flex-row">
                        <div class="mealIndicator position-relative">
                            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                                <circle class="circleBg" cx="50" cy="50" r="40" stroke="gray" stroke-width="6" fill="none"></circle>
                                <circle class="circleFg" cx="50" cy="50" r="40" transform="rotate(90, 50, 50)" stroke-width="6" fill="none"></circle>
                            </svg>

                            <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center">
                                <img class=" w-50 h-50 img-fluid" src="img/snack.png" alt="">
                            </div>
                        </div>
                        <div class="mealTexts d-flex flex-column align-items-start justify-content-center">
                            <div class="mealName fw-bold fs-4">Nassolnivaló</div>
                            <div class="mealData"><span class="consumed">0</span> / <span class="targetMealCal"><?php echo (floor($userData->getCalorieTarget() * 0.05)); ?></span> kCal</div>
                        </div>


                    </div>
                    <div class="buttonDiv">
                        <button class="circle-btn" onclick="openModal('snack')">
                            <svg viewBox="0 0 56 56" aria-hidden="true">

                                <rect class="plus" x="14" y="27" width="28" height="1" fill="#fff" rx="2" />
                                <rect class="plus" x="27" y="14" width="1" height="28" fill="#fff" rx="2" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>



        <div class="mt-2 w-100 mb-2">
            <h3>Tevékenységek</h3>
            <div class="text-center" id="listContainer">

                <ul class="w-75 list-unstyled mx-auto" id="addedActivities">

                </ul>
                <button class="btn btn-outline-dark" type="button" onclick="openActivityModal()"><i class="fa-solid fa-circle-plus me-1"></i>Új tevékenység</button>
            </div>

        </div>

    </div>

    <dialog class="px-0">
        <div class="d-flex flex-row position-relative outofdialog" id="dialogcontainer">
            <div class="p-3" style="min-width:100% !important;">
                <h3 id="mealName">Reggeli</h3>
                <label id="mealSearchId" for="ingredientSearch" class="fs-5 fw-normal px-0">Mit reggeliztél?</label>
                <div class="position-relative px-0">
                    <input type="text" class="form-control" id="ingredientSearch" oninput="searchIngredients()"
                        onblur="closeList()" placeholder="Keresés...">
                    <div id="searchResults" class="shadow rounded-bottom position-absolute w-100">
                        <ul id="resultList" style="list-style-type: none;">
                            
                        </ul>
                    </div>
                    <ul class="p-0" id="mealList">

                    </ul>
                </div>
                <div class=" position-absolute bottom-0 mx-auto">
                    <button class="btn btn-primary" type="button" onclick="saveButton()">Mentés</button>
                    <button class="btn btn-secondary" type="button" onclick="closeModal()">Mégsem</button>
                </div>

            </div>

            <div class="dialogquantity p-3" style="min-width:100% !important;">
                <h3 class="text-center" id="foodName">Név</h3>
                <div class="row my-3">
                    <div>
                        <img class="img-fluid mx-auto d-block" src="img/ingredient.png" alt="Ingredient logo"
                            width="200">
                    </div>
                </div>

                <div class="row w-100 mx-0 text-center">
                    <div class="col-3">
                        <div class="fw-bold">Kalória</div>
                        <div id="modalCal">100</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold">Fehérje</div>
                        <div id="modalProt">100</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold">Szénhidrát</div>
                        <div id="modalCh">100</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold">Zsír</div>
                        <div id="modalFat">100</div>
                    </div>
                </div>

                <div class="row mt-4 mb-3">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <span class="fw-bold">Mennyiség:</span>
                        <input class="w-25 form-control" type="number" name="quantity" id="quantity"
                            oninput="onQuantityChange()">
                        <span>g</span>
                    </div>


                </div>

                <div class="w-100 mx-0 text-center">
                    <button id="saveModifyButton" class="btn btn-primary mx-2" type="button" onclick="addFoodToMeal()">Hozzáad</button>
                    <button class="btn btn-secondary mx-2" type="button" onclick="swipeRight()">Mégsem</button>

                </div>
            </div>
        </div>
    </dialog>

    <dialog id="activityDialog">
        <form>

            <label class="form-label" for="activity">Tevékenység</label>
            <select class="form-select" name="activity" id="activity" onchange="onActivitySelection(this)">
                <option value="-1" hidden>Válassz tevékenységet</option>
                <?php
                $category = "";
                for ($i = 0; $i < count($activities); $i++) {
                    if ($category != $activities[$i]->getCategory()) {
                        if ($category != "") {
                            echo ("</optgroup>");
                        }
                        echo ('<optgroup label="' . ucfirst($activities[$i]->getCategory()) . '">');
                        $category = $activities[$i]->getCategory();
                    }
                    echo ('<option value="' . $activities[$i]->getId() . '">' . $activities[$i]->getName() . '</option>');
                }
                echo ("</optgroup>");
                ?>
                <optgroup label=""></optgroup>
            </select>

            <label class="form-label mt-2" for="duration">Időtartam (perc)</label>
            <input class="form-control" type="number" name="duration" id="duration" min="0" value="0" oninput="onChangeDuration()">

            <div class="my-3">
                <p class="m-0 text-center">Elégetett kalória</p>
                <div class="text-center"><span id="burntCalories">0</span> kCal</div>
            </div>

            <div class="text-center">
                <button id="saveActivityButton" class="btn btn-primary" type="button" onclick="saveActivity()">Mentés</button>
                <button class="btn btn-secondary" type="button" onclick="closeActivityModal()">Mégsem</button>
            </div>

        </form>

    </dialog>

    <?php include_once("components/footer.php") ?>

    <script src="js/Ingredient.js"></script>
    <script src="js//IngredientAndQuantity.js"></script>
    <script src="js/CalorieCounter.js"></script>



</body>

</html>