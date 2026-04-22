<?php

$adatok = [];
$writeArray = [];

function readData()
{
    global $adatok;
    $file = fopen("felfedezesekUTF.csv", "r");
    fgets($file);
    while (!feof($file)) {
        $line = fgets($file);
        if (strlen($line) > 0) {
            $lineArr = explode(";", $line);
            $element = new Element($lineArr);
            $adatok[] = $element;
        }
    }
}

function feladat3(): int
{
    global $adatok;
    return count($adatok);
}

function feladat4(): int
{
    global $adatok;
    $count = 0;
    for ($i = 0; $i < count($adatok); $i++) {
        if ($adatok[$i]->getYear() == "Ókor") {
            $count++;
        }
    }

    return $count;
}

function feladat6()
{
    echo ("<p>" . "6. Feladat: Keresés" . "</p>");
    global $adatok;
    global $element;

    for ($i = 0; $i < count($adatok); $i++) {

        if (strtolower(strval($element)) == strtolower($adatok[$i]->getSymbol())) {
            $adat = $adatok[$i];
            echo '<p style = "text-indent:50px;">Az elem vegyjele: ' . $adat->getSymbol() . '</p>' .
                '<p style = "text-indent:50px;">Az elem neve: ' . $adat->getElementName() . '</p>' .
                '<p style = "text-indent:50px;">Rendszáma: ' . $adat->getNumber() . '</p>' .
                '<p style = "text-indent:50px;">Felfedezés éve: ' . $adat->getYear() . '</p>' .
                '<p style = "text-indent:50px;">Felfedező: ' . $adat->getDiscoveredBy() . '</p>';
            return;
        }
    }

    echo 'Nincs ilyen!';
}

function feladat7()
{
    global $adatok;
    $maxDiff = 0;

    for ($i = 0; $i < count($adatok) - 1; $i++) {
        $element1 = $adatok[$i];
        $element2 = $adatok[$i + 1];

        if ($element1->getYear() == "Ókor" || $element2->getYear() == "Ókor") {
            continue;
        }

        $diff = $element2->getYear() - $element1->getYear();
        if ($diff > $maxDiff) {
            $maxDiff = $diff;
        }
    }

    echo ("<p>7. Feladat: " . $maxDiff . " év volt a leghosszabb időszak két elem felfedezése között.</p>");
}

function feladat8()
{
    echo ("<p>8. Feladat: Statisztika</p>");
    global $adatok;
    $stat = array();

    for ($i = 0; $i < count($adatok); $i++) {
        if ($adatok[$i]->getYear() == "Ókor") {
            continue;
        }

        $year = $adatok[$i]->getYear();
        if (array_key_exists($year, $stat)) {
            $stat[$year]++;
        } else {
            $stat[$year] = 1;
        }
    }

    global $writeArray;
    $writeArray = $stat;

    foreach ($stat as $key => $value) {
        if ($value > 3) {
            echo "<p style = 'text-indent:50px;'>$key : $value db</p>";
        }
    }
}

function writeToFile()
{
    global $writeArray;
    $file = fopen("statisztika.csv", "w");
    foreach ($writeArray as $key => $value) {
        fputs($file, $key . " : " . $value . "\n");
    }
    fclose($file);
}

function getElementByNumber($number)
{
    global $adatok;
    for ($i = 0; $i < count($adatok); $i++) {
        if ($adatok[$i]->getNumber() == $number) {
            return $adatok[$i];
        }
    }
}
