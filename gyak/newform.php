<?php
include_once("controller/UserController.php");
if (array_key_exists("user", $_POST)) {
    $userId = 0;
    $name = $_POST["name"];
    $city = $_POST["city"];
    $street = $_POST["street"];
    $house_number = $_POST["house_number"];
    $address = new Address($userId, $city, $street, $house_number);

    $user = new User($userId, $name, $address);

    UserController::addNewUser($user);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>

<body>
    <div class=" container">
        <form action="" method="post">
            <input type="hidden" name="user">

            <label class="mt-2" for="name">Név</label>
            <input class="mt-1 form-control" type="text" name="name" id="name">

            <label class="mt-2" for="city">Település</label>
            <input class="mt-1 form-control" type="text" name="city" id="city">

            <label class="mt-2" for="street">Utca</label>
            <input class="mt-1 form-control" type="text" name="street" id="street">

            <label class="mt-2" for="street">Házszám</label>
            <input class="mt-1 form-control" type="number" name="house_number" id="house_number">

            <input class="d-block mx-auto btn btn-primary mt-4" type="submit" value="Felhasználó hozzáadása">
        </form>
    </div>
</body>

</html>