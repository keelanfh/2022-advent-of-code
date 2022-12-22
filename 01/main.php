<?php

require_once "helpers/helpers.php";

$contents = readFileToString("01/input.txt");
$big_array = stringSplit($contents, "\n\n", "\n");

$sums = array_map("array_sum", $big_array);

rsort($sums);

# Part 1
println($sums[0]);

# Part 2
println(array_sum(array_slice($sums, 0, 3)));
