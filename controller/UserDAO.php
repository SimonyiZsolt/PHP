<?php
if(file_exists("model")){
    include_once("model/User.php");
    include_once("model/UserData.php");
}else{
    include_once("../model/User.php");
    include_once("../model/UserData.php");
}

class UserDAO
{
    private static function getDBConnection($servername = "localhost", $username = "root", $password = "", $dbname = "food_db"): mysqli
    {
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public static function createUser(User $user): string
    {
        try {
            $conn = self::getDBConnection();
            $stmt = $conn->prepare("INSERT INTO users VALUES(?,?,?,?,?);");

            $username = $user->getUsername();
            $pwHash = $user->getPasswordHash();
            $email = $user->getEmail();
            $emailConfirmed = $user->isEmailConfirmed();
            $role = $user->getRole();

            $stmt->bind_param("sssis", $username, $pwHash, $email, $emailConfirmed, $role);
            $stmt->execute();

            if ($stmt->error != "") {
                return $stmt->error;
            } else {
                return "";
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public static function usernameAvailable(string $username): bool
    {
        try {
            $conn = self::getDBConnection();
            $uname = $username;
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->bind_param("s", $uname);
            $stmt->execute();

            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                return false;
            }
            return true;
        } catch (Exception $ex) {
            throw new Exception("Hiba");
        }
    }

    public static function emailAvailable(string $email): bool
    {
        try {
            $conn = self::getDBConnection();
            $emailAddr = $email;
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->bind_param("s", $emailAddr);
            $stmt->execute();

            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                return false;
            }
            return true;
        } catch (Exception $ex) {
            throw new Exception("Hiba");
        }
    }

    public static function getUserByUsername(string $username): User|null
    {
        try {

            $conn = self::getDBConnection();
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                $stmt->close();
                $conn->close();
                return null;
            }
            $stmt->bind_result($uname, $passwordHash, $email, $emailConfirmed, $role);
            $stmt->fetch();

            $user = new User($uname, $passwordHash, $email, $emailConfirmed, $role);
            $stmt->close();
            $conn->close();

            return $user;
        } catch (Exception $e) {
            $stmt->close();
            $conn->close();

            return null;
        }
    }

    public static function getUserDataForUsername(string $username): UserData|null
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
        try {

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows() == 0) {
                return null;
            }
            $stmt->bind_result($username, $goal, $age, $height, $weight, $gender, $activity, $rmr, $bmr, $calorie_target);
            $stmt->fetch();

            $userData = new UserData(intval($goal), intval($age), intval($height), intval($weight), intval($gender), floatval($activity), intval($rmr), intval($bmr), intval($calorie_target));
            $stmt->close();
            $conn->close();
            return $userData;
        } catch (Exception $e) {
            $stmt->close();
            $conn->close();
            return null;
        }
    }

    public static function insertUserDataForUsername(string $userName, UserData $userdata): bool
    {
        try {
            $conn = self::getDBConnection();
            $stmt = $conn->prepare("INSERT INTO user_data VALUES (?,?,?,?,?,?,?,?,?,?);");

            $uname = $userName;
            $goal = $userdata->getGoal();
            $age = $userdata->getAge();
            $height = $userdata->getHeight();
            $weight = $userdata->getWeight();
            $gender = $userdata->getGender();
            $activity = $userdata->getActivity();
            $rmr = $userdata->getRMR();
            $bmr = $userdata->getBMR();
            $calorieTarget = $userdata->getCalorieTarget();

            $stmt->bind_param("siiiiidiii", $uname, $goal, $age, $height, $weight, $gender, $activity, $rmr, $bmr, $calorieTarget);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
