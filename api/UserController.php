<?php
include_once("../model/User.php");
include_once("../model/UserData.php");
include_once("../controller/UserDAO.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (array_key_exists("register", $_POST)) {
        try {
            $returnArr = array();
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            if (isUsernameAvailable($username)) {
                $returnArr["username"] = "OK";
            } else {
                $returnArr["username"] = "TAKEN";
            }

            if (isEmailAvailable($email)) {
                $returnArr["email"] = "OK";
            } else {
                $returnArr["email"] = "TAKEN";
            }

            if ($returnArr["username"] == "TAKEN" || $returnArr["email"] == "TAKEN") {
                http_response_code(409);
                echo (json_encode($returnArr));
            } else {
                $pwHash = password_hash($password, null);

                
                $user = new User($username, $pwHash, $email, "user");
                
                if (strlen($errors = UserDAO::createUser($user)) != 0) {
                    echo ($errors);
                    http_response_code(500);
                } else {
                    http_response_code(200);
                }
                
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
            http_response_code(500);
        }
    }

    if (array_key_exists("login", $_POST)) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $user = UserDAO::getUserByUsername($username);
        if ($user != null) {

            if (password_verify($password, $user->getPasswordHash())) {
                
                session_start();
                session_regenerate_id(true);

                $_SESSION['user_name'] = $user->getUsername();
                $_SESSION['logged_in'] = true;

                header('Location: ../recipes');
            } else {
                header('Location: ../login?message=Rossz jelszó');
            }
        } else {
            header('Location: ../login?message=Hibás felhasználónév');
        }
    }

    if (array_key_exists("dataForCalorie", $_POST)) {
        session_start();

        $goal = $_POST["goal"];
        $age = $_POST["age"];
        $height = $_POST["height"];
        $weight = $_POST["weight"];
        $gender = $_POST["gender"];
        $activity = $_POST["activity"];
        $userData = new UserData($goal, $age, $height, $weight, $gender, $activity);
      
        if (UserDAO::insertUserDataForUsername($_SESSION["user_name"], $userData)) {
            $returnArr = array();
            $returnArr["rmr"] = $userData->getRMR();
            $returnArr["bmr"] = $userData->getBMR();
            $returnArr["calorietarget"] = $userData->getCalorieTarget();
            $returnArr["goal"] = $userData->getGoal();
            echo (json_encode($returnArr));
            http_response_code(200);
        } else {
            http_response_code(500);
        }

    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (array_key_exists("check", $_POST)) {
        $username = $_POST["username"];

        if (UserDAO::usernameAvailable($username)) {
            echo (1);
        } else {
            echo (0);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (array_key_exists("emailcheck", $_POST)) {
        $email = $_POST["email"];

        if (UserDAO::emailAvailable($email)) {
            echo (1);
        } else {
            echo (0);
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    
}

function isUsernameAvailable(string $username): bool
{
    if (UserDAO::usernameAvailable($username)) {
        return true;
    } else {
        return false;
    }
}

function isEmailAvailable(string $email): bool
{
    if (UserDAO::emailAvailable($email)) {
        return true;
    } else {
        return false;
    }
}
