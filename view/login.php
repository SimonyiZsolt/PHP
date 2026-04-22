<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include_once("components/formnavbar.php");
    ?>

    <div class="container navbarOffset" style="max-width: 720px;">
        <div class="p-3 bg-white mt-2 rounded-4 border shadow">
            <h2 class="text-center mb-3"><i class="fa-solid fa-right-to-bracket fa-xs me-2"></i>Bejelentkezés</h2>
            <form action="api/UserController.php" method="post">
                <input type="hidden" name="login" value="1">
                <label class="form-label" for="username">Felhasználónév</label>
                <input class="form-control" type="text" name="username" id="username" required>

                <label class="form-label mt-2" for="password">Jelszó</label>
                <input class="form-control" type="password" name="password" id="password" required>

                <div>
                    <?php if (array_key_exists("message", $_GET)) {
                        echo ('<div class="alert alert-danger my-2">' . $_GET["message"] . '</div>');
                    } ?>
                </div>

                <div class="mt-3 text-center">
                    <input type="submit" class="btn btn-success w-75" value="Bejelentkezés">
                </div>

            </form>


        </div>

    </div>

    <?php include_once("components/footer.php") ?>
</body>

</html>