<?php

require "helpers/helpers.php";

$big_array = read_file_to_int_groups("01/input.txt");

$sums = array_map("array_sum", $big_array);

rsort($sums);

# Part 1
echo $sums[0] . "\n";

# Part 2
echo array_sum(array_slice($sums, 0, 3));

?>

