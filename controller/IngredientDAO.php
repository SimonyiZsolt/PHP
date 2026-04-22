<?php
include_once("../model/Ingredient.php");
class IngredientDAO
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

    public static function insert(Ingredient $obj): int
    {
        $conn = self::getDBConnection();
        $sql = "INSERT INTO foodstuff (name, unit, calories, protein, carbohydrate, fat) VALUES (?,?,?,?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param("ssiddd", $name, $unit, $calories, $protein, $carbohydrate, $fat);

        $name = $obj->getName();
        $unit = $obj->getUnit();
        $calories = $obj->getCalories();
        $protein = $obj->getProtein();
        $carbohydrate = $obj->getCarbohydrate();
        $fat = $obj->getFat();

        $statement->execute();
        $id = mysqli_insert_id($conn);
        $conn->close();
        return $id;
    }

    public static function insertMultiple(array $ingredients)
    {
        $conn = self::getDBConnection();
        $sql = "";

        for ($i = 0; $i < count($ingredients); $i++) {
            $sql .= 'INSERT INTO foodstuff(name, unit, calories, protein, carbohydrate, fat) VALUES ('
                . '\'' . $ingredients[$i]->getName() . '\','
                . '\'' . $ingredients[$i]->getUnit() . '\','
                . '\'' . $ingredients[$i]->getCalories() . '\','
                . '\'' . $ingredients[$i]->getProtein() . '\','
                . '\'' . $ingredients[$i]->getCarbohydrate() . '\','
                . '\'' . $ingredients[$i]->getFat() . '\');';
        }

        if (mysqli_multi_query($conn, $sql)) {
            echo "New records created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        $conn->close();
    }

    public static function getByid(int $id): Ingredient
    {
        $conn = self::getDBConnection();
        $sql = "SELECT * FROM foodstuff WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $Ingid = $row["id"];
            $name = $row["name"];
            $unit = $row["unit"];
            $calories = $row["calories"];
            $protein = $row["protein"];
            $carbohydrate = $row["carbohydrate"];
            $fat = $row["fat"];

            return new Ingredient($Ingid, $name, $unit, $calories, $protein, $carbohydrate, $fat);

            $conn->close();
        } else {
            $conn->close();
            throw new InvalidArgumentException("No such id");
        }
    }

    public static function getAll(): array
    {
        $conn = self::getDBConnection();
        $sql = "SELECT * FROM foodstuff WHERE type='ingredient'";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $returnArray = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $Ingid = $row["id"];
                $name = $row["name"];
                $unit = $row["unit"];
                $calories = $row["calories"];
                $protein = $row["protein"];
                $carbohydrate = $row["carbohydrate"];
                $fat = $row["fat"];

                $returnArray[] = new Ingredient($Ingid, $name, $unit, $calories, $protein, $carbohydrate, $fat);
            }
        }
        $conn->close();

        return $returnArray;
    }

    public static function getAllFoodstuff(): array
    {
        $conn = self::getDBConnection();
        $sql = "SELECT * FROM foodstuff";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $returnArray = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $Ingid = $row["id"];
                $name = $row["name"];
                $unit = $row["unit"];
                $calories = $row["calories"];
                $protein = $row["protein"];
                $carbohydrate = $row["carbohydrate"];
                $fat = $row["fat"];

                $returnArray[] = new Ingredient($Ingid, $name, $unit, $calories, $protein, $carbohydrate, $fat);
            }
        }
        $conn->close();

        return $returnArray;
    }

    public function update($obj) {}

    public function delete($obj) {}
}
