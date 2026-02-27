<?php 
    $file_handle = fopen("file.txt", "r");

    $fileSize = fileSize("file.txt");
    // echo $fileSize;
    // echo "<br/>";

    $data = fread($file_handle, $fileSize);
    echo $data;
    echo "<br/>";

    // line by line
    $file_handle = fopen("file.txt", "r");
    while (!feof($file_handle)) {
        echo fgets($file_handle) . "<br/>";
    }

    var_dump($file_handle) . "<br/>";
    fclose($file_handle);
    var_dump($file_handle) . "<br/>";

    /*=================== Write File ===================*/
    $file_handle = fopen("names.txt", "w");
    fwrite($file_handle, "Ahmed");
    fclose($file_handle);
    // Append
    $file_handle = fopen("names.txt", "a");
    fwrite($file_handle, "\nMahmoud");
    fclose($file_handle);