<?php

function get_input(string $fileName) {

$myfile = fopen($fileName, "r") or die("Unable to open file!");
    $input = [];
    while(!feof($myfile)) {
        array_push($input, trim(fgets($myfile)));
      }
    fclose($myfile);
    return $input;
}

?>