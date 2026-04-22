<?php
include_once("Foodstuff.php");
class Ingredient extends Foodstuff implements JsonSerializable
{

    public function __construct(int $id, string $name, string $unit, int $calories, float $protein, float $carbohydrate, float $fat)
    {
        parent::__construct($id, $name, $unit, $calories, $protein, $carbohydrate,$fat);     
    }

}
