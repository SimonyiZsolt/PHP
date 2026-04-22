<?php
include_once("controller/RecipeDAO.php");
include_once("model/IngredientAndQuantity.php");
if (array_key_exists("id", $_GET)) {
    $recipe = RecipeDAO::getRecipeById($_GET["id"]);
} else {
    echo ("Hiba");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recept - <?php echo ($recipe->getName()) ?></title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/fontawesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.7.2-web/css/solid.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include_once("components/navbar.php"); ?>

    <div class="navbarOffset container" style="max-width: 720px;">
        <div class="border shadow p-2 mt-2 rounded-3">
            <h1 class="text-center"><?php echo ($recipe->getName()) ?></h1>
            <div class="imgContainer text-center">
                <img class="recipeImgBig" src="<?php
                                                if ($recipe->getImageURL() != "") {
                                                    echo ("img/uploads/" . $recipe->getImageURL());
                                                } else {
                                                    echo ("img/noimage.png");
                                                }
                                                ?>" alt="">
            </div>

            <div class=" border rounded bg-light-subtle mt-2">
                <h4 class="mt-2 text-center">Átlagos tápérték 100 g-ban</h4>
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <div class="m-1 border rounded shadow-sm">
                            <div class="text-center fs-5 fw-medium">Kalória</div>
                            <div class="text-center fw-medium"><?php echo ($recipe->getCalories()); ?> kCal</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="m-1 border rounded shadow-sm">
                            <div class="text-center fs-5 fw-medium">Fehérje</div>
                            <div class="text-center fw-medium"><?php echo ($recipe->getProtein()); ?> g</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="m-1 border rounded shadow-sm">
                            <div class="text-center fs-5 fw-medium">Szénhidrát</div>
                            <div class="text-center fw-medium"><?php echo ($recipe->getCarbohydrate()); ?> g</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <div class="m-1 border rounded shadow-sm">
                            <div class="text-center fs-5 fw-medium">Zsír</div>
                            <div class="text-center fw-medium"><?php echo ($recipe->getFat()); ?> g</div>
                        </div>
                    </div>


                </div>
            </div>

            <h3>Hozzávalók</h3>
            <ul style="list-style-type: none;">

                <?php
                $ingredientList = $recipe->getIngredientsAndQuantities();
                for ($i = 0; $i < count($ingredientList); $i++) {
                    $ingredientQuantity = $ingredientList[$i];
                    $ingredient = $ingredientQuantity->getIngredient();
                    $quantity = $ingredientQuantity->getQuantity();

                    echo ('<li>
                    <div class="col-12 col-lg-6 d-flex flex-row">
                        <div class="me-2"><i class="listIcon fa-solid fa-circle fa-2xs me-2"></i>' . $quantity . ' g</div>
                        <div>' . $ingredient->getName() . '</div>
                    </div>
                </li>');
                }
                ?>
            </ul>
            <h3>Elkészítés</h3>
            <p><?php echo ($recipe->getDescription()) ?></p>
        </div>

    </div>

    <?php include_once("components/footer.php") ?>

</body>

</html>