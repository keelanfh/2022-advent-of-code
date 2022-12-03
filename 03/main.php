<?php

require("helpers/helpers.php");

function letter_to_priority ($letter) {
    $value = ord($letter) - 96;

    if ($value < 0) {
        $value += 58;
    } 

    return $value;

}

$lines = read_file_to_array("03/input.txt");

$total = 0;

// Part 1

foreach($lines as $line) {
    $split_point=strlen($line)/2;

    $left=str_split(substr($line,0,$split_point));
    $right=str_split(substr($line,$split_point));

    $shared = array_values(array_intersect($left, $right))[0];

    $total += letter_to_priority($shared);

}

echo $total . "\n";

// Part 2

$total = 0;

$grouped_lines  = array_to_group_arrays($lines, 3, false);

foreach ($grouped_lines as $group_str) {
    $group_arrays = array_map("str_split", $group_str);

    $shared = array_values(array_intersect(...$group_arrays))[0];
    $total += letter_to_priority($shared);
 
}

echo $total . "\n";

?>