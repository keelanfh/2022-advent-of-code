<?php

require_once("helpers/helpers.php");

$contents = readFileToString("08/input.txt");
$numbers = stringSplit($contents, "\n", "");

function isTreeVisible($i, $j): bool
{
    global $numbers;

    $row = $numbers[$i];
    $currentTree = $numbers[$i][$j];

    $left = array_slice($row, 0, $j);
    $right = array_slice($row, $j + 1);

    $column = arrayVerticalSlice($numbers, $j);

    $above = array_slice($column, 0, $i);
    $below = array_slice($column, $i + 1);

    foreach ([$left, $right, $above, $below] as $direction) {
        if (empty($direction)) {
            return true;
        }
        if (max($direction) < $currentTree) {
            return true;
        }
    }

    return false;
}

$width = count($numbers);

$total = 0;

for ($i = 0; $i < $width; $i++) {
    for ($j = 0; $j < $width; $j++) {
        if (isTreeVisible($i, $j)) {
            $total++;
        }
    }
}

println($total);
