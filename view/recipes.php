<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptek</title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include_once("components/navbar.php"); ?>
    <div class="brandImage">
        <div class="col-8 ps-1" style="padding-top: 10%;">
            <h1 class="navbarOffset text-white">Tudatos étkezés egyszerűen</h1>
            <h6 class="text-white">Receptek, tápérték-számítás és étkezési napló egy helyen.</h6>
        </div>
        
    </div>
    <div class="container mt-3">
        <div class="row px-1" id="recipeContainer">

        </div>
        
        <div id="loader"></div>
    </div>

    <dialog style="padding: 0;">
        <div class="container p-0 position-relative">
            <div class="pages outofdialog">
                <div class="page">
                    <form>

                        <input type="hidden" name="dataForCalorie">
                        <label for="goal">Mi a célod?</label>
                        <select class="form-select form-control" name="goal" id="goal" required oninput="checkModalInputs()">
                            <option value="0">Fogyás</option>
                            <option value="1">Súlymegtartás</option>
                            <option value="2">Testtömeg növelés</option>
                        </select>

                        <label class="form-label mt-2" for="age">Életkorod</label>
                        <div class="input-group">
                            <input class="form-control" type="number" name="age" id="age" required oninput="checkModalInputs()">
                            <span class="input-group-text">év</span>
                        </div>

                        <label class="form-label mt-2" for="height">Magasságod</label>
                        <div class="input-group">
                            <input class="form-control" type="number" name="height" id="height" required oninput="checkModalInputs()">
                            <span class="input-group-text">cm</span>
                        </div>

                        <label class="form-label mt-2" for="weight">Testsúlyod</label>
                        <div class="input-group">
                            <input class="form-control" type="number" name="weight" id="weight" required oninput="checkModalInputs()">
                            <span class="input-group-text">kg</span>
                        </div>

                        <label class="form-label mt-2" for="height">Nemed</label>
                        <select class="form-select form-control" name="gender" id="gender" required oninput="checkModalInputs()">
                            <option value="0">Nő</option>
                            <option value="1">Férfi</option>
                        </select>

                        <div class="mt-2">Mennyire vagy aktív a hétköznapokban?</div>

                        <div class="form-check d-flex flex-row align-items-center border p-0" onclick="checkRadio(0)">
                            <input class="form-check-input mx-2" type="radio" name="activity" id="activity" value="1.25" oninput="checkModalInputs()">
                            <label class="form-check-label" for="activity">
                                Ülő életmód - Minimális testmozgás, irodai munka
                            </label>
                        </div>
                        <div class="form-check d-flex flex-row align-items-center border p-0" onclick="checkRadio(1)">
                            <input class="form-check-input mx-2" type="radio" name="activity" id="activity" value="1.375" oninput="checkModalInputs()">
                            <label class="form-check-label" for="activity">
                                Kissé aktív - Könnyű testmozgás/sport heti 1-3 alkalommal
                            </label>
                        </div>
                        <div class="form-check d-flex flex-row align-items-center border p-0" onclick="checkRadio(2)">
                            <input class="form-check-input mx-2" type="radio" name="activity" id="activity" value="1.55" oninput="checkModalInputs()" checked>
                            <label class="form-check-label" for="activity">
                                Közepesen aktív - Mérsékelten nehéz testmozgás/sport heti 1-3 alkalommal, könnyű fizikai munka
                            </label>
                        </div>
                        <div class="form-check d-flex flex-row align-items-center border p-0" onclick="checkRadio(3)">
                            <input class="form-check-input mx-2" type="radio" name="activity" id="activity" value="1.725" oninput="checkModalInputs()">
                            <label class="form-check-label" for="activity">
                                Aktív - Megerőltető testmozgás/sport heti 6-7 alkalommal, fizikai munka
                            </label>
                        </div>
                        <div class="form-check d-flex flex-row align-items-center border p-0" onclick="checkRadio(4)">
                            <input class="form-check-input mx-2" type="radio" name="activity" id="activity" value="1.9" oninput="checkModalInputs()">
                            <label class="form-check-label" for="activity">
                                Nagyon aktív - Rendszeres megerőltető testmozgás/sport, nehéz fizikai munka
                            </label>
                        </div>

                        <button onclick="sendModalData()" id="modalSubmit" class="mt-3 btn btn-primary form-control" type="button" disabled>
                            <span class="spinner-border spinner-border-sm d-none" aria-hidden="true" id="modalSpinner"></span>
                            <span>Küldés</span>
                        </button>
                        
                    </form>
                </div>


                <div class="page d-flex flex-column align-items-center">
                    <h1>Köszönjük</h1>
                    <div class="text-center">A megadott adataid alapján az alábbi értékeket kaptuk:</div>
                    <div class="text-center my-3">
                        <div class="fs-5 fw-medium">Nyugalmi anyagcsere</div>
                        <div class="fs-5 fw-bold" id="rmr">1234 kcal</div>
                    </div>

                    <div class="text-center my-3">
                        <div class="fs-5 fw-medium">Alapanyagcsere</div>
                        <div class="fs-5 fw-bold" id="bmr">1234 kcal</div>
                    </div>

                    <div class="text-center my-3">
                        <div class="fs-5 fw-medium">Kalóriacél</div>
                        <div class="fs-5 fw-bold" id="calorieTarget">1234 kcal</div>
                    </div>

                    <div id="resultText" class="text-center my-3">A célod elérése érdekében ennyi kalóriát kell bevinned. A megadott adataidat később módosíthatod.</div>
                    <button type="button" class="btn btn-primary mt-5 w-75" onclick="closeModal()">Rendben</button>

                </div>

            </div>

        </div>

    </dialog>

    <?php

    include_once("controller/UserDAO.php");
    if (array_key_exists("user_name", $_SESSION)) {
        $userData = UserDAO::getUserDataForUsername($_SESSION["user_name"]);
        if ($userData == null) {

            echo ('<script>setTimeout(function() {document.querySelector("dialog").showModal();}, 200);</script>');
        }
    } else {
        

    }
    ?>
    
    <?php include_once("components/footer.php") ?>

    <script src="js/SimpleRecipe.js"></script>
    <script src="js/RecipeBrowser.js"></script>
</body>

</html>