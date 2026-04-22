<?php
include_once("Element.php");
include_once("Feladatok.php");

readData();

$element = null;
$pattern = "/[A-Z|a-z]{2}/";
$element_valid = false;
if (array_key_exists("element", $_POST)) {
    $element = $_POST["element"];

    if (strlen($element) == 2 && preg_match($pattern, $element)) {
        $element_valid = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (array_key_exists("nev", $_POST)) {
        echo ($_POST["nev"]);
        return;
    }

    if (array_key_exists("szam", $_POST)) {

        $num = $_POST["szam"];
        $element = getElementByNumber($num);
        echo (json_encode($element));
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="feladat.js"></script>
</head>

<body>
    <?php


    ?>
    <p>3. Feladat: Elemek száma: <?php echo feladat3() ?></p>
    <p>4. Feladat: Felfedezések száma az ókorban: <?php echo feladat4() ?></p>
    <p>5. Feladat: Kérek egy vegyjelet:
    <form style="display: inline;" action="" method="POST"><input type="text" name="element"> <input type="submit" value="Keresés"></form>
    </p>
    <?php if ($element_valid) {
        feladat6();
        feladat7();
        feladat8();
        writeToFile();
    } ?>

    <form id="form" action="">
        <label for="nev">Add meg a neved</label>
        <input type="text" name="nev" id="nev">

        <button id="gomb" type="button" onclick="sendXhr()">Nyomj meg</button>
    </form>

    <form id="newform" action="">
        <label for="szam">Add meg az elem számát</label>
        <input type="text" name="szam" id="szam">

        <button id="gomb" type="button" onclick="getElementData()">Keresés</button>
    </form>
</body>

</html>