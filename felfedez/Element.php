<?php

class Element implements JsonSerializable
{
    private int|string $year;
    private string $elementName;
    private string $symbol;
    private int $number;
    private string $discoveredBy;

    public function __construct(array $line)
    {
        $this->setYear($line[0]);
        $this->setElementName($line[1]);
        $this->setSymbol($line[2]);
        $this->setNumber(intval($line[3]));
        $this->setDiscoveredBy($line[4]);
    }

    private function setYear(int|string $year)
    {
        $this->year = $year;
    }

    private function setElementName(string $elementName)
    {
        $this->elementName = $elementName;
    }

    private function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }

    private function setNumber(int $number)
    {
        $this->number = $number;
    }

    private function setDiscoveredBy(string $discoveredBy)
    {
        $this->discoveredBy = $discoveredBy;
    }

    public function getYear(): int|string
    {
        return $this->year;
    }

    public function getElementName(): string
    {
        return $this->elementName;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getDiscoveredBy(): string
    {
        return $this->discoveredBy;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
