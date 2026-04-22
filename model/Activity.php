<?php
class Activity implements JsonSerializable
{
    private int $id;
    private string $name;
    private float $met;
    private string $category;

    public function __construct(int $id, string $name, float $met, string $category = "")
    {
        $this->setId($id);
        $this->setName($name);
        $this->setMet($met);
        $this->setCategory($category);
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setMet(float $met)
    {
        $this->met = $met;
    }

    private function setCategory(string $category)
    {
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMet(): float
    {
        return $this->met;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
