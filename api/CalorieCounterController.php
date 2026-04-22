<?php
include_once("../controller/MealDAO.php");
include_once("../controller/UserDAO.php");
include_once("../model/Meal.php");
include_once("../model/MealType.php");
include_once("../controller/ActivityDAO.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();
    if (array_key_exists("activityinsert", $_POST)) {
        $userName = $_SESSION["user_name"];
        $date = $_POST["date"];
        $activity = json_decode($_POST["activity"]);
        ActivityDAO::addActivityForUserAndDate($activity->id, $userName, $_POST["duration"], $date);
        echo (json_encode(ActivityDAO::getActivitiesForUserAndDate($userName, $date)));
        http_response_code(200);

    } else if (array_key_exists("activityUpdate", $_POST)) {
        //print_r($_POST);
        ActivityDAO::updateUserActivity(intval($_POST["userActivityId"]), intval($_POST["newDuration"]));
        http_response_code(200);
    } else if (array_key_exists("deleteUserActivity", $_POST)) {

        ActivityDAO::deleteUserActivityByID(intval($_POST["userActivityId"]));
    } else {
        $assocArr = [];

        $userName = $_SESSION["user_name"];
        $date = $_POST["date"];
        $mealType = MealType::getMealTypeFromString($_POST["mealType"]);
        $foodArray = json_decode($_POST["foodList"]);
        $obj = $foodArray[0];
        $assocArr["userName"] = $userName;
        $assocArr["mealType"] = $mealType->value;
        $assocArr["date"] = $date;

        $arr = [];
        for ($i = 0; $i < count($foodArray); $i++) {
            $foodQuantity = $foodArray[$i];
            $arr[] = array("foodId" => $foodQuantity->ingredient->id, "quantity" => $foodQuantity->quantity);
        }
        $assocArr["mealQuantity"] = $arr;
        print_r(MealDAO::insert($assocArr));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    session_start();
    $userName = $_SESSION["user_name"];
    $date = $_GET["date"];

    echo (json_encode(MealDAO::getAllMealsForUserAndDate($userName, $date)));
    $arr = array();
    echo ("\n");
    echo (json_encode(UserDAO::getUserDataForUsername($userName)));
    echo ("\n");
    echo (json_encode(ActivityDAO::getActivityList()));
    echo ("\n");
    echo (json_encode(ActivityDAO::getActivitiesForUserAndDate($userName, $date)));
    http_response_code(200);
}
