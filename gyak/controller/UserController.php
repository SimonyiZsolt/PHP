<?php
include_once("model/User.php");
class UserController
{

    private static string $host = "localhost";
    private static string $username = "root";
    private static string $password = "";
    private static string $database = "gyak";

    private static function getDBConnection(): mysqli
    {
        $connection = new mysqli(self::$host, self::$username, self::$password, self::$database);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        return $connection;
    }

    public static function getAllUsers(): array
    {
        $users = [];

        $connection = self::getDBConnection();
        $stmt = $connection->prepare("SELECT users.id, users.name, address.city, address.street, address.house_number FROM users JOIN address ON user_id = users.id;");
        $stmt->execute();
        $stmt->bind_result($id, $name, $city, $street, $house_number);

        while ($stmt->fetch()) {
            $address = new Address(intval($id), strval($city), strval($street), intval($house_number));
            $user = new User(intval($id), strval($name), $address);
            $users[] = $user;
        }

        $stmt->close();
        $connection->close();

        return $users;
    }

    public static function getUser($userId): User
    {
        $user = null;

        $connection = self::getDBConnection();
        $stmt = $connection->prepare("SELECT users.id, users.name, address.city, address.street, address.house_number FROM users JOIN address ON user_id = users.id WHERE users.id = ?");
        $stmt->bind_param("i", $userId);

        $stmt->execute();
        $stmt->bind_result($id, $name, $city, $street, $house_number);

        $stmt->fetch();
        $address = new Address(intval($id), strval($city), strval($street), intval($house_number));
        $user = new User(intval($id), strval($name), $address);

        $stmt->close();
        $connection->close();

        return $user;
    }

    public static function updateAddress(Address $address)
    {
        $userId = $address->getUser_id();
        $newCity = $address->getCity();
        $newStreet = $address->getStreet();
        $newHouse_number = $address->getHouse_Number();

        $connection = self::getDBConnection();
        $stmt = $connection->prepare("UPDATE address SET city = ?, street = ?, house_number = ? WHERE user_id = ?");
        $stmt->bind_param("ssii", $newCity, $newStreet, $newHouse_number, $userId);
        $stmt->execute();

        $stmt->close();
        $connection->close();

        header("location:index.php");
    }

    public static function addNewUser(User $user)
    {
        $name = $user->getName();
        $city = $user->getAddress()->getCity();
        $street = $user->getAddress()->getStreet();
        $house_number = $user->getAddress()->getHouse_Number();

        $connection = self::getDBConnection();
        $stmt = $connection->prepare("INSERT INTO users(name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $insertId = $stmt->insert_id;

        $stmt->close();
        $stmt = $connection->prepare("INSERT INTO address(user_id, city, street, house_number) VALUES (?,?,?,?)");
        $stmt->bind_param("issi", $insertId, $city, $street, $house_number);
        $stmt->execute();

        $stmt->close();
        $connection->close();

        header("location:index.php");
    }
}
