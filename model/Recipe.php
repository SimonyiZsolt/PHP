<?php
include_once("Ingredient.php");
include_once("Foodstuff.php");
include_once("IngredientAndQuantity.php");
class Recipe extends Foodstuff
{
    private string $description;
    private array $ingredientsAndQuantities;
    private string $imageURL;
    private int $calories;
    private int $protein;
    private int $carbohydrate;
    private int $fat;
    private string $uploadedBy;

    public function __construct($id, $name, $description, $ingredientsAndQuantities, string|null $imageURL = null, $calories = null, $protein = null, $carbohydrate = null, $fat = null, $uploadedBy = "admin")
    {

        $this->setDescription($description);
        $this->setIngredientsAndQuantities($ingredientsAndQuantities);
        $this->setUploadedBy($uploadedBy);
        if ($imageURL != null) {
            $this->setImageURL($imageURL);
        } else {
            $this->imageURL = "";
        }


        if ($calories == null && $protein == null && $carbohydrate == null && $fat == null) {
            $this->calculateNutritionData();
        } else {
            $this->calories = $calories;
            $this->protein = $protein;
            $this->carbohydrate = $carbohydrate;
            $this->fat = $fat;
        }

        parent::__construct($id, $name, "g", $this->calories, $this->protein, $this->carbohydrate, $this->fat);
    }

    public function calculateNutritionData(): void
    {
        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $totalQuantity = 0;

        for ($i = 0; $i < count($this->ingredientsAndQuantities); $i++) {
            $element = $this->ingredientsAndQuantities[$i];
            $nutritionData = $element->getCalculatedNutrients();

            $totalCalories += intval($nutritionData["calories"]);
            $totalProtein += intval($nutritionData["protein"]);
            $totalCarbs += intval($nutritionData["carbs"]);
            $totalFat += intval($nutritionData["fat"]);
            $totalQuantity += $element->getQuantity();
        }

        $this->calories = ceil($totalCalories / $totalQuantity * 100);
        $this->protein = ceil($totalProtein / $totalQuantity * 100);
        $this->carbohydrate = ceil($totalCarbs / $totalQuantity * 100);
        $this->fat = ceil($totalFat / $totalQuantity * 100);
    }

    
    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIngredientsAndQuantities(): array
    {
        return $this->ingredientsAndQuantities;
    }

    public function getImageURL(): string
    {
        return $this->imageURL;
    }

    public function setUploadedBy(string $uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;
    }

    public function getUploadedBy(): string
    {
        return $this->uploadedBy;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function setIngredientsAndQuantities(array $list): void
    {
        $this->ingredientsAndQuantities = $list;
    }

    public function setImageURL(string $URL): void
    {
        $this->imageURL = $URL;
    }

}
