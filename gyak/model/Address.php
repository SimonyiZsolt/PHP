<?php

class Address
{
    private int $user_id;
    private string $city;
    private string $street;
    private int $house_number;

    public function __construct(int $user_id, string $city, string $street, int $house_number)
    {
        $this->setUser_id($user_id);
        $this->setCity($city);
        $this->setStreet($street);
        $this->setHouse_Number($house_number);
    }

    private function setUser_id(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    public function setHouse_Number(int $house_number)
    {
        $this->house_number = $house_number;
    }

    public function getUser_id(): int
    {
        return $this->user_id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouse_Number(): int
    {
        return $this->house_number;
    }
}
