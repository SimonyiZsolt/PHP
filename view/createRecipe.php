<?php

if (!array_key_exists("user_name", $_SESSION)) {
    header("Location: login");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új recept</title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
</head>

<body>

    <?php include_once("components/navbar.php"); ?>

    <div class="navbarOffset container" style="max-width: 720px;">
        <div class="border shadow p-4 mt-2 rounded-4">
            <div class="row">
                <h1>Új recept</h1>
                <div class="col-12">
                    <label for="title" class="fs-5 px-0 my-1">Recept neve<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="title" id="title" required>

                    <label for="ingredientSearch" class="fs-5 fw-normal px-0 mt-2">Hozzávalók kiválasztása<span
                            class="text-danger">*</span></label>
                    <div class="position-relative px-0">
                        <input type="text" class="form-control" id="ingredientSearch" oninput="searchIngredients()"
                            onblur="closeList()" placeholder="A kereséshez kezdj gépelni">
                        <div id="searchResults" class="shadow rounded-bottom position-absolute w-100 d-none">
                            <ul id="resultList" style="list-style-type: none;">

                            </ul>
                        </div>
                    </div>

                    <div class="px-0">
                        <h5 class="fs-5 fw-normal px-0 mt-2">Eddigi hozzávalók</h5>
                        <ul id="ingredientList" class="px-1 py-2 border">
                            <li>
                                <div class="w-100 text-center text-muted">Még nem adtál hozzá hozzávalókat.</div>
                            </li>                           

                        </ul>
                        <hr style="z-index: -5;">
                        <div class="text-center text-muted">Nem találsz egy hozzávalót a listánkban? Ha tudsz,
                            segíthetsz nekünk és
                            felviheted az alábbi
                            gombbal!
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-dark my-3"
                                onclick="openNewIngredientModal()"><i class="fa-solid fa-circle-plus me-1"></i>Új
                                hozzávaló felvitele</button>
                        </div>

                    </div>

                    <label class="fs-5 fw-normal px-0" for="description">Elkészítés<span
                            class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                        required></textarea>

                </div>

                <div class="col-12">
                    <div class="d-flex flex-column justify-content-start h-100">
                        <div class="my-1">
                            <label for="image" class="fs-5 fw-normal">Kép feltöltése (opcionális)</label>


                            <input type="file" name="image" id="image" onchange="onImageChange()"
                                class="form-control my-1">
                        </div>

                        <div>
                            <div class="text-center mx-auto">

                                <img id="preview" class="img-fluid" alt="">
                            </div>

                        </div>
                    </div>


                </div>

                <div class="mt-3 d-flex justify-content-center">
                    <div>
                        <table class="table caption-top table-light text-center table-borderless shadow-sm">
                            <caption class="text-center fw-bold">Tápérték adatok 100 g-ra</caption>
                            <thead>
                                <tr>
                                    <th>Kalória</th>
                                    <th>Fehérje</th>
                                    <th>Szénhidrát</th>
                                    <th>Zsír</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td id="totalCal"></td>
                                    <td id="totalProt"></td>
                                    <td id="totalCh"></td>
                                    <td id="totalFat"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="messageBox" class="w-100">
                    
                </div>

                <div class="row">
                    <button type="button" class="btn btn-lg btn-success w-75 mx-auto my-4" onclick="saveRecipe()">Recept
                        mentése</button>
                </div>

            </div>
        </div>

    </div>



    <!-- Modal -->
    <div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ingredientModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row my-3">
                            <div>
                                <img class="img-fluid mx-auto d-block" src="img/ingredient.png" alt="Ingredient logo"
                                    width="200">
                            </div>

                        </div>

                        <div class="row text-center">
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
                    </div>
                </div>
                <div class="modal-footer justify-content-center align-items-center">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                    <button type="button" id="addToListBtn" class="btn btn-primary" onclick="addIngredient()"
                        data-bs-dismiss="modal">Hozzáadás</button>
                    <button type="button" class="d-none btn btn-primary" id="modifyListBtn" onclick="saveModification()"
                        data-bs-dismiss="modal">Módosítás</button>

                </div>
            </div>
        </div>
    </div>

    <dialog id="newIngredientModal" class=" border-1 rounded-3">
        <h3 class="text-center">Új hozzávaló</h3>
        <form id="addIngredientForm" action="#" method="post">
            <div class="row">
                <div class="col-12">
                    <label class="form-label" for="ingredientName">Hozzávaló neve</label>
                    <input class="form-control border-dark-subtle" type="text" name="ingredientName"
                        id="ingredientName">
                </div>
            </div>

            <div class="row my-3 fw-bold">
                <div class="col-12">
                    <div class="text-center fs-5">Tápérték adatok 100 grammban:</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <label class="form-label" for="ingredientCalories">Kalória</label>
                    <input class="form-control border-dark-subtle" type="number" name="ingredientCalories"
                        id="ingredientCalories" min="0">
                </div>

                <div class="col-12 col-lg-6">
                    <label class="form-label" for="ingredientProtein">Fehérje</label>
                    <input class="form-control border-dark-subtle" type="number" name="ingredientProtein"
                        id="ingredientProtein" min="0">
                </div>

                <div class="col-12 col-lg-6">
                    <label class="form-label" for="ingredientCarbs">Szénhidrát</label>
                    <input class="form-control border-dark-subtle" type="number" name="ingredientCarbs"
                        id="ingredientCarbs" min="0">
                </div>

                <div class="col-12 col-lg-6">
                    <label class="form-label" for="ingredientFat">Zsír</label>
                    <input class="form-control border-dark-subtle" type="number" name="ingredientFat" id="ingredientFat"
                        min="0">
                </div>

            </div>

            <div class="row">

                <div class="col-12">
                    <button type="button" class="btn btn-success form-control mt-3"
                        onclick="saveNewIngredient()">Mentés</button>
                </div>

                <div class="col-12">
                    <button type="button" class="btn btn-warning form-control mt-3"
                        onclick="closeNewIngredientModal()">Mégsem</button>
                </div>
            </div>



        </form>
    </dialog>

    <dialog id="messageModal" class=" border-1 rounded-3 p-4">
        <div id="alert" class="alert alert-success">Hozzávaló sikeresen mentve!</div>
        <button type="button" class="btn btn-primary w-100" onclick="dismissAlert()">OK</button>
    </dialog>

    <dialog id="successModal" class=" border-1 rounded-3 p-4">
        <div id="alert" class="alert alert-success">Receptedet sikeresen mentettük!</div>
        <a class="btn btn-primary w-100" href="recipes">OK</a>
    </dialog>

    <?php include_once("components/footer.php") ?>

    <script src="js/Ingredient.js"></script>
    <script src="js/IngredientAndQuantity.js"></script>
    <script src="js/Recipe.js"></script>
    <script src="js/createRecipe.js"></script>

</body>

</html>