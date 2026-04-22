<?php
include_once("Foodstuff.php");
class MealQuantity implements JsonSerializable
{
    private Foodstuff $food;
    private int $quantity;


    public function __construct(Foodstuff $food, int $quantity)
    {
        $this->setFood($food);
        $this->setQuantity($quantity);
    }

    private function setFood(Foodstuff $food): void
    {
        $this->food = $food;
    }

    private function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getFood(): Foodstuff
    {
        return $this->food;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
