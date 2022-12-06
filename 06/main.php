<?php

$filepath = "06/input.txt";

// Open the file
$file = fopen($filepath, 'r');
$datastream = fread($file, filesize($filepath));

$datastream = str_split($datastream);

function find_first_unique($array, $length): int {
    for ($i=$length; $i < count($array) + 1; $i++) { 
        if (count(array_unique(array_slice($array, $i-$length, $length))) == $length) {
            return $i;
        }
    }
}

echo find_first_unique($datastream, 4) . "\n";
echo find_first_unique($datastream, 14) . "\n";

