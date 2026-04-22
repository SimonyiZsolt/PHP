<?php
include_once("../model/Recipe.php");
include_once("../model/SimpleRecipe.php");
include_once("../model/IngredientAndQuantity.php");
include_once("../controller/RecipeDAO.php");
include_once("../controller/FileHandler.php");
include_once("../controller/ImageScaler.php");
include_once("../model/Ingredient.php");
include_once("../model/IngredientAndQuantity.php");
include_once("../model/Recipe.php");
include_once("../model/SimpleRecipe.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    $ingredientsAndQuantities = IngredientAndQuantity::buildClassArrayFromJSON($_POST["ingredientAndQuantityList"]);

    echo ("ingredientCount: " . count($ingredientsAndQuantities) . "\n");
    print($ingredientsAndQuantities[0]->getIngredient()->getName());
    $recipe = new Recipe(0, $_POST["title"], $_POST["description"], $ingredientsAndQuantities);
    $recipe->setUploadedBy($_SESSION["user_name"]);
    $recipeId = RecipeDAO::insertRecipe($recipe);

    if (count($_FILES) > 0) {
        print_r($_FILES);

        $savedFile = saveUploadedFile($recipeId);
        RecipeDAO::updateImageAtId($recipeId, $savedFile);
        ImageScaler::scaleImageIfTooBig("../img/uploads/" . $savedFile);
    } else {
        
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (array_key_exists("method", $_GET)) {
        if ($_GET["method"] == "simple") {
            if (array_key_exists("offset", $_GET) && array_key_exists("limit", $_GET)) {
                $simpleRecipes = RecipeDAO::getSimpleRecipesLazy($_GET["limit"], $_GET["offset"]);
                $jsonString = json_encode($simpleRecipes);
                echo ($jsonString);
            } else {
                $simpleRecipes = RecipeDAO::getAllAsSimpleRecipe();
                $jsonString = json_encode($simpleRecipes);
                echo ($jsonString);
            }
        }
    }
}
