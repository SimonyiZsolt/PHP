<?php

include_once("../model/Ingredient.php");
include_once("../controller/IngredientDAO.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists("foodstuff", $_GET)) {
        $list = IngredientDAO::getAllFoodstuff();
        echo (json_encode($list));
        http_response_code(200);
    } else {

        $list = IngredientDAO::getAll();
        echo (json_encode($list));
        http_response_code(200);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ingredient = new Ingredient(0, $_POST["ingredientName"], "g", $_POST["ingredientCalories"], $_POST["ingredientProtein"], $_POST["ingredientCarbs"], $_POST["ingredientFat"]);
    $insertId = IngredientDAO::insert($ingredient);
    if ($insertId != 0) {
        echo (json_encode(IngredientDAO::getByid($insertId)));
        http_response_code(200);
    } else {
        http_response_code(500);
    }
}
