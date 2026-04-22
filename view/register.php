<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include_once("components/formnavbar.php"); ?>

    <div class="container navbarOffset" style="max-width: 720px;">
        <div class="p-3 bg-white mt-2 rounded-4 border shadow">
            <h2 class="mb-3 text-center"><i class="fa-solid fa-user fa-xs me-2"></i>Regisztráció</h2>
            <form novalidate>
                <input type="hidden" name="register">

                <div class="form-group">
                    <label class="form-label" for="username">Felhasználónév</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="username" id="username" autocomplete="off" available="1" oninput="onNameFieldChange()">
                        <div class="input-group-append" id="nameCheckBtnDiv">

                            <button onclick="onNameCheckPressed()" class="btn btn-primary appendbtn" id="nameCheckBtn" type="button" disabled>
                                <span class="d-none" id="nameOK">&#10004</span>
                                <span class="d-none" id="nameNOK">&#10006</span>
                                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true" id="nameSpinner"></span>
                                <span role="status">Ellenőrzés</span></button>
                            </button>
                        </div>
                    </div>
                    <div class="text-danger fw-semibold fs-6 d-none" id="usernameFeedback">
                        Ez a felhasználónév foglalt, kérlek válassz másikat!
                    </div>
                </div>


                <div class="form-group mt-2">
                    <label class="form-label" for="email">Email-cím</label>
                    <div class="input-group">
                        <input class="form-control" type="email" name="email" id="email" oninput="onEmailFieldChange()">
                        <div class="input-group-append" id="emailCheckBtnDiv">
                            <button onclick="onEmailCheckPressed()" class="btn btn-primary appendbtn" id="emailCheckBtn" type="button" disabled>
                                <span class="d-none" id="emailOK">&#10004</span>
                                <span class="d-none" id="emailNOK">&#10006</span>
                                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true" id="emailSpinner"></span>
                                <span role="status">Ellenőrzés</span></button>
                        </div>
                    </div>
                    <div class="text-danger fw-semibold fs-6 d-none" id="emailFeedback">
                        Helytelen e-mail cím!
                    </div>
                </div>


                <div class="form-group mt-2">
                    <label class="form-label" for="password">Jelszó</label>
                    <input class="form-control" type="password" name="password" id="password" onchange="onChange()">
                    <div class="text-danger fw-semibold fs-6 d-none" id="passwordFeedback">

                    </div>
                </div>

                <div class="form-group mt-2">
                    <label class="form-label" for="confirmPassword">Jelszó ismét</label>
                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" onchange="onChange()">
                    <div class="text-danger fw-semibold fs-6 d-none" id="confirmFeedback">
                        A jelszavak nem egyeznek!
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <input class="btn btn-success w-75" type="submit" value="Regisztrálok">
                </div>
                
            </form>
        </div>

    

    </div>
    <?php include_once("components/footer.php") ?>
    <script src="js/register.js"></script>
</body>

</html>