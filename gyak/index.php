<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználók</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>

<body>
    <div class=" container">
        <?php
        include_once("controller/UserController.php");
        $userArray = UserController::getAllUsers();
        ?>
        <h1>Felhasználók</h1>
        <table class="table table-bordered table-light text-center table-striped">
            <thead class="">
                <tr>
                    <th>Név</th>
                    <th>Város</th>
                    <th>Utca</th>
                    <th>Házszám</th>
                    <th>Módosítás</th>
                </tr>
            </thead>
            <tbody>

                <?php

                for ($i = 0; $i < count($userArray); $i++) {
                    $row = $userArray[$i]->getAsTableRow();
                    echo ("<tr>" . $row . createModButton($userArray[$i]->getId()) . "</tr>");
                }

                function createModButton(int $id): string
                {
                    return "<td>" . '<form action="modform.php" method="get">
                <input type="hidden" name="user" value="' . $id . '">
                <button class=" btn btn-primary" type="submit">Módosítás</button></form>' . "</td>";
                }
                ?>
            </tbody>
        </table>

        <div class="w-100 text-center">
            <a class="btn btn-primary" href="newform.php">Új felhasználó hozzáadása</a>
        </div>

    </div>
</body>

</html>