<?php
include_once("Ingredient.php");

class IngredientAndQuantity
{
    private Ingredient $ingredient;
    private int $quantity;

    public function __construct(Ingredient $ingredient, int $quantity)
    {
        $this->setIngredient($ingredient);
        $this->setQuantity($quantity);
    }

    private function setIngredient(Ingredient $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    private function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCalculatedNutrients(): array
    {
        $calculatedCalories = ceil(($this->ingredient->getCalories() / 100) * $this->quantity);
        $calculatedProtein = ceil(($this->ingredient->getProtein() / 100) * $this->quantity);
        $calculatedCarbs = ceil(($this->ingredient->getCarbohydrate() / 100) * $this->quantity);
        $calculatedFat = ceil(($this->ingredient->getFat() / 100) * $this->quantity);

        return array(
            "calories" => $calculatedCalories,
            "protein" => $calculatedProtein,
            "carbs" => $calculatedCarbs,
            "fat" => $calculatedFat
        );
    }

    public static function buildClassArrayFromJSON(string $jsonStringEncoded): array
    {
        $returnArray = [];
        $array = json_decode($jsonStringEncoded);

        for ($i = 0; $i < count($array); $i++) {
            $id = intval($array[$i]->ingredient->id);
            $name = $array[$i]->ingredient->name;
            $unit = $array[$i]->ingredient->unit;
            $calories = intval($array[$i]->ingredient->calories);
            $protein = intval($array[$i]->ingredient->protein);
            $carbohydrate = intval($array[$i]->ingredient->carbohydrate);
            $fat = intval($array[$i]->ingredient->fat);

            $ingredient = new Ingredient($id, $name, $unit, $calories, $protein, $carbohydrate, $fat);
            $quantity = intval($array[$i]->quantity);

            $ingredientQuantity = new IngredientAndQuantity($ingredient, $quantity);
            $returnArray[] = $ingredientQuantity;
        }

        return $returnArray;
    }
}
