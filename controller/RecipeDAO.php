<?php


class RecipeDAO
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

    public static function insertRecipe(Recipe $recipe): int
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("INSERT INTO foodstuff (type,name,calories,protein,carbohydrate,fat, description, uploaded_by) VALUES (?,?,?,?,?,?,?,?);");

        $type = "recipe";
        $name = $recipe->getName();
        $description = $recipe->getDescription();
        $calories = $recipe->getCalories();
        $protein = $recipe->getProtein();
        $carbs = $recipe->getCarbohydrate();
        $fat = $recipe->getFat();
        $uploadedBy = $recipe->getUploadedBy();

        $stmt->bind_param("ssiiiiss", $type, $name, $calories, $protein, $carbs, $fat, $description, $uploadedBy);
        $stmt->execute();

        $recipeId = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO ingredients_quantities (recipe_id, ingredient_id, quantity) VALUES (?,?,?);");
        $ingredientsAndQuantities = $recipe->getIngredientsAndQuantities();

        for ($i = 0; $i < count($ingredientsAndQuantities); $i++) {
            $ingredientId = $ingredientsAndQuantities[$i]->getIngredient()->getId();
            $quantity = $ingredientsAndQuantities[$i]->getQuantity();
            $stmt->bind_param("iii", $recipeId, $ingredientId, $quantity);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        return $recipeId;
    }

    public static function updateImageAtId(int $recipeId, string $imgPath)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("UPDATE foodstuff SET img_path = ? WHERE id = ?");
        $stmt->bind_param("si", $imgPath, $recipeId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public static function getAllAsSimpleRecipe(): array
    {
        $returnArr = [];
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, calories, protein, carbohydrate, fat, img_path, uploaded_by FROM foodstuff WHERE type = 'recipe'");
        $stmt->execute();
        $stmt->bind_result($id, $title, $calories, $protein, $carbs, $fat, $imageURL, $uploadedBy);
        while ($stmt->fetch()) {
            $recipe = new SimpleRecipe($id, strval($title), intval($calories), intval($protein), intval($carbs), intval($fat), $imageURL, strval($uploadedBy));
            $returnArr[] = $recipe;
        }

        return $returnArr;
    }

    public static function getSimpleRecipesLazy($limit, $offset): array
    {
        $returnArr = [];
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, calories, protein, carbohydrate, fat, img_path, uploaded_by FROM foodstuff WHERE type = 'recipe' LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $stmt->bind_result($id, $title, $calories, $protein, $carbs, $fat, $imageURL, $uploadedBy);
        while ($stmt->fetch()) {
            
            $recipe = new SimpleRecipe($id, strval($title), intval($calories), intval($protein), intval($carbs), intval($fat), $imageURL, strval($uploadedBy));
            $returnArr[] = $recipe;
        }

        return $returnArr;
    }

    public static function getRecipeById(int $id): Recipe
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM foodstuff WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt->bind_result($recepieId, $type, $title, $unit, $calories, $protein, $carbs, $fat, $description, $imgPath, $uploadedBy);
        $stmt->fetch();

        $stmt->close();

        $ingredientsAndQuantities = self::getIngredientsAndQuantitiesForId($id, $conn);

        $recipe = new Recipe($recepieId, $title, $description, $ingredientsAndQuantities, $imgPath, $calories, $protein, $carbs, $fat, $uploadedBy);

        return $recipe;
    }

    public static function getIngredientsAndQuantitiesForId(int $id, mysqli $conn): array
    {
        $returnArr = [];
        $stmt = $conn->prepare("SELECT id, name, unit, calories, protein, carbohydrate, fat, quantity FROM foodstuff LEFT JOIN ingredients_quantities ON foodstuff.id = ingredients_quantities.ingredient_id WHERE recipe_id = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt->bind_result($ingredientId, $name, $unit, $calories, $protein, $carbs, $fat, $quantity);
        while ($stmt->fetch()) {
            $ingredientQuantity = new IngredientAndQuantity(new Ingredient($ingredientId, strval($name), strval($unit), intval($calories), intval($protein), intval($carbs), intval($fat)), intval($quantity));
            $returnArr[] = $ingredientQuantity;
        }
        $stmt->close();
        return $returnArr;
    }
}
