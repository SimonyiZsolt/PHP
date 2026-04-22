<?php

class SimpleRecipe implements JsonSerializable
{
    private int $id;
    private string $title;
    private int $calories;
    private int $protein;
    private int $carbohydrate;
    private int $fat;
    private string|null $imageURL;
    private string $uploadedBy;

    public function __construct(int $id, string $title, int $calories, int $protein, int $carbohydrate, int $fat, string|null $imageURL, string $uploadedBy)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setCalories($calories);
        $this->setProtein($protein);
        $this->setCarbs($carbohydrate);
        $this->setFat($fat);
        $this->setImageURL($imageURL);
        $this->setUploadedBy($uploadedBy);
    }

    public function getUploadedBy(): string
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(string $uploadedBy): void
    {
        $this->uploadedBy = $uploadedBy;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImageURL(): string
    {
        return $this->imageURL;
    }

    public function getCalories(): int
    {
        return $this->calories;
    }

    public function getProtein(): int
    {
        return $this->protein;
    }

    public function getCarbs(): int
    {
        return $this->carbohydrate;
    }

    public function getFat(): int
    {
        return $this->fat;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function setImageURL(string|null $URL): void
    {
        if ($URL !== null) {
            $this->imageURL = $URL;
        } else {
            $this->imageURL = null;
        }
    }

    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }

    public function setProtein(int $protein): void
    {
        $this->protein = $protein;
    }

    public function setCarbs(int $carbs): void
    {
        $this->carbohydrate = $carbs;
    }

    public function setFat(int $fat): void
    {
        $this->fat = $fat;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
