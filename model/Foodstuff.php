<?php
    class Foodstuff implements JsonSerializable
{

    private int $id;
    private string $type;
    private string $name;
    private string $unit;
    private int $calories;
    private int $protein;
    private int $carbohydrate;
    private int $fat;

    public function __construct(int $id, string $name, string $unit, int $calories, float $protein, float $carbohydrate, float $fat)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setUnit($unit);
        $this->setCalories($calories);
        $this->setProtein($protein);
        $this->setCarbohydrate($carbohydrate);
        $this->setFat($fat);
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setUnit(string $unit)
    {
        $this->unit = $unit;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setCalories(int $calories)
    {
        $this->calories = $calories;
    }

    public function getCalories(): int
    {
        return $this->calories;
    }

    public function setProtein(float $protein)
    {
        $this->protein = $protein;
    }

    public function getProtein(): float
    {
        return $this->protein;
    }

    public function setCarbohydrate(float $carbohydrate)
    {
        $this->carbohydrate = $carbohydrate;
    }

    public function getCarbohydrate(): float
    {
        return $this->carbohydrate;
    }

    public function setFat(float $fat)
    {
        $this->fat = $fat;
    }

    public function getFat(): float
    {
        return $this->fat;
    }

    public function exposeVariables()
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
