<?php

require_once("helpers/helpers.php");

$filepath = "06/input.txt";

// Open the file
$file = fopen($filepath, 'r');
$datastream = fread($file, filesize($filepath));

$datastream = str_split($datastream);

function findFirstUnique($array, $length): int
{
    for ($i = $length; $i < count($array) + 1; $i++) {
        if (count(array_unique(array_slice($array, $i - $length, $length))) == $length) {
            return $i;
        }
    }
}

println(findFirstUnique($datastream, 4));
println(findFirstUnique($datastream, 14));
