<?php
include_once("MealQuantity.php");
include_once("MealType.php");
class Meal implements JsonSerializable
{
    
    private string $username;
    private string $date;
    private MealType $mealType; //0-breakfast 1-lunch 2-dinner 3-snack
    private array $mealQuantities = [];

    public function __construct(string $username, string $date, MealType $mealType)
    {
        $this->setUsername($username);
        $this->setDate($date);
        $this->setMealType($mealType);
    }

    private function setUsername(string $username): void
    {
        $this->username = $username;
    }

    private function setDate(string $date): void
    {
        $this->date = $date;
    }

    private function setMealType(MealType $mealType): void
    {
        $this->mealType = $mealType;
    }

    public function setMealQuantities(array $mealQuantities): void
    {
        $this->mealQuantities = $mealQuantities;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getMealType(): MealType
    {
        return $this->mealType;
    }

    public function getMealQuantities(): array
    {
        return $this->mealQuantities;
    }

    public function addMealQuantity(MealQuantity $mealQuantity)
    {
        $this->mealQuantities[] = $mealQuantity;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
