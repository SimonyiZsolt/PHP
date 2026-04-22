<?php

session_start([
    'cookie_lifetime' => 0,            // session lasts until browser closes
    'cookie_httponly' => true,         // JS cannot access session cookie
    'cookie_secure' => isset($_SERVER['HTTPS']), // send only over HTTPS
    'use_strict_mode' => true,         // block uninitialized sessions
    'use_only_cookies' => true,
    'cookie_samesite' => 'Lax',        // prevents CSRF
]);


if (array_key_exists("route", $_GET)) {
    $route = $_GET["route"];
    if ($route == "recipes") {
        if (array_key_exists("id", $_GET)) {

            include_once("model/Ingredient.php");
            include_once("model/IngredientAndQuantity.php");
            include_once("model/Recipe.php");
            include_once("model/SimpleRecipe.php");

            include_once("view/viewRecipe.php");
        } else {
            $route = "";
            require_once("view/recipes.php");
        }
    } else if ($route == "create") {

        include_once("view/createRecipe.php");
    } else if ($route == "register") {
        include_once("view/register.php");
    } else if ($route == "login") {
        include_once("view/login.php");
    } else if ($route == "logout") {

        session_unset();
        session_destroy();
        include_once("view/recipes.php");
    } else if ($route == "calorieCounter") {
        include_once("view/calorieCounter.php");
    }else{
        $route = "";
        require_once("view/recipes.php");
    }
} else {
    $route = "";

    //$encrypted = openssl_encrypt("simonyizsolt88@gmail.com", "AES-128-ECB", "anyad");
    //echo ("encrypted: " . $encrypted . "\n");
    //$decrypted = openssl_decrypt($encrypted, "AES-128-ECB", "anyad");
    //echo ("decrypted: " . $decrypted);

    require_once("view/recipes.php");
}
