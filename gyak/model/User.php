<?php
include_once("Address.php");
class User
{
    private int $id;
    private string $name;
    private Address $address;

    public function __construct(int $id, string $name, Address $address)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setAddress($address);
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setAddress(Address $address)
    {
        $this->address = $address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getAsTableRow(){
        return "<td>".$this->name."</td>"."<td>".$this->address->getCity()."</td>"."<td>".$this->address->getStreet()."</td>"."<td>".$this->address->getHouse_Number()."</td>";
    }
}
