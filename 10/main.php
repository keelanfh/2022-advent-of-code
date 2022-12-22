<?php

require_once("helpers/helpers.php");

$contents = readFileToString("10/input.txt");
$contents = stringSplit($contents, "\n", " ");

// print_r($contents);

$results = [];
$register = 1;

foreach ($contents as $line) {
    if ($line[0] == "noop") {
        array_push($results, $register);
    }
    if ($line[0] == "addx") {
        // print_r($line);
        array_push($results, $register);
        $register += $line[1];
        array_push($results, $register);
    }
}

print_r($results);

$total = 0;

for ($i = 20; $i < count($results); $i += 40) {
    println($results[$i - 1]);
    $total += $i * $results[$i - 1];
}

print_r($total);
