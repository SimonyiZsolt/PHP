<?php

if (str_contains(getcwd(), "api")) {
    include_once("../model/Foodstuff.php");
    include_once("../model/Meal.php");
} else {
    include_once("model/Foodstuff.php");
    include_once("model/Meal.php");
}



class MealDAO
{
    private static string $servername = "localhost";
    private static string $username = "root";
    private static string $password = "";
    private static string $dbname = "food_db";

    private static function getDBConnection(): mysqli
    {
        $conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public static function insert($mealArray)
    {
        $username = $mealArray["userName"];
        $date = $mealArray["date"];
        $mealType = $mealArray["mealType"];
        $foodsAndQuantities = $mealArray["mealQuantity"];
        $mealId = -1;
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM meals WHERE username = ? AND date = ? AND mealtype = ?;");
        $stmt->bind_param("ssi", $username, $date, $mealType);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        if ($id == 0) {
            $stmt = $conn->prepare("INSERT INTO meals (username, date, mealtype) VALUES(?,?,?)");
            $stmt->bind_param("ssi", $username, $date, $mealType);
            $stmt->execute();
            $mealId = $stmt->insert_id;
            $stmt->close();
        } else {
            $mealId = $id;
            $stmt = $conn->prepare("DELETE FROM meal_quantity WHERE meal_id = ?");
            $stmt->bind_param("i", $mealId);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("INSERT INTO meal_quantity(meal_id, food_id, quantity) VALUES(?,?,?);");
        $stmt->bind_param("iii", $mealId, $foodId, $quantity);

        for ($i = 0; $i < count($foodsAndQuantities); $i++) {
            $foodAndQuantity = $foodsAndQuantities[$i];
            $foodId = $foodAndQuantity["foodId"];
            $quantity = $foodAndQuantity["quantity"];

            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
    }

    public static function getAllMealsForUserAndDate(string $username, string $date)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT foodstuff.id, type, name, unit, calories, protein, carbohydrate, fat, quantity, mealtype FROM foodstuff INNER JOIN meal_quantity ON foodstuff.id = food_id INNER JOIN meals ON meal_id = meals.id WHERE username = ? AND date = ?;");

        $stmt->bind_param("ss", $username, $date);
        $stmt->execute();

        $stmt->bind_result($id, $type, $name, $unit, $calories, $protein, $carbohydrate, $fat, $quantity, $mealType);

        $breakfast = new Meal($username, $date, MealType::Breakfast);
        $lunch = new Meal($username, $date, MealType::Lunch);
        $dinner = new Meal($username, $date, MealType::Dinner);
        $snack = new Meal($username, $date, MealType::Snack);

        while ($stmt->fetch()) {

            $food = new Foodstuff(intval($id), strval($name), strval($unit), intval($calories), intval($protein), intval($carbohydrate), intval($fat));


            $mealQuantity = new MealQuantity($food, intval($quantity));

            if ($mealType == MealType::Breakfast->value) {
                $breakfast->addMealQuantity($mealQuantity);
            }

            if ($mealType == MealType::Lunch->value) {
                $lunch->addMealQuantity($mealQuantity);
            }

            if ($mealType == MealType::Dinner->value) {
                $dinner->addMealQuantity($mealQuantity);
            }

            if ($mealType == MealType::Snack->value) {
                $snack->addMealQuantity($mealQuantity);
            }
        }

        $stmt->close();
        $conn->close();

        $returnArr = array();
        $returnArr["username"] = $username;
        $returnArr["date"] = $date;
        $returnArr["breakfast"] = $breakfast;
        $returnArr["lunch"] = $lunch;
        $returnArr["dinner"] = $dinner;
        $returnArr["snack"] = $snack;

        return $returnArr;
    }

    public static function getUserStartDate($userName)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT MIN(date) FROM meals WHERE username = ?;");
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $stmt->bind_result($date);

        $stmt->fetch();

        $stmt->close();
        $conn->close();

        return $date;
    }
}
