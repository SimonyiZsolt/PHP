<?php

function saveUploadedFile(string $fileName): string
{
    $targetDir = "../img/uploads/";

    if ($_FILES["recipeImg"]["error"] == 0) {

        $targetFile = $targetDir . basename($_FILES["recipeImg"]["name"]);
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($file_info, $_FILES["recipeImg"]["tmp_name"]);
        $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        if ($mime == "image/jpeg" || $mime == "image/png") {
            echo ("Mime ok!\n");
            echo ("Ext: " . $extension);
            move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $targetDir . $fileName . "." . $extension);

            return $fileName . "." . $extension;
        }

        throw new Exception("The file is not an image!");
    } else {
        throw new Exception("Error while uploading file!");
    }
}
