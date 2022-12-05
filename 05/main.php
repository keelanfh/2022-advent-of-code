<?php

require("helpers/helpers.php");

$lines = read_file_to_array("05/input.txt");

$lines = array_to_group_arrays($lines, "", false);

$stacks = $lines[0];
$moves = $lines[1];

array_pop($stacks);
$stacks = array_map(fn($line) => str_split($line, 4), $stacks);
$stacks_cleaned = [];

foreach ($stacks as $stack) {
    array_push($stacks_cleaned, array_map(fn($line) => str_replace(["[", "]"], "", $line), $stack));

}

print_r($stacks_cleaned);

function clean_stack($stack) {
    $stack = array_map(fn($a) => str_replace(" ", "", $a), $stack);
    // this will just remove falsy values
    $stack = array_filter($stack);
    $stack = array_reverse($stack);
    return $stack;
}

// transposes the array
// it isn't clear to me why this is the way to do it, but it is...
$stacks_cleaned = array_map(null, ...$stacks_cleaned);

$stacks = array_map("clean_stack", $stacks_cleaned);

print_r($stacks);

// now for the moves

function move_to_movedata($line) {
    $matches = [];
    preg_match("/^move (\d{1,2}) from (\d) to (\d)$/", $line, $matches);
    return array_slice($matches, 1);
}

$moves_data = array_map("move_to_movedata", $moves);

// do the actual moving around
foreach ($moves_data as $move_data) {
    for ($submove=0; $submove < $move_data[0]; $submove++) {
        array_push($stacks[$move_data[2]-1], array_pop($stacks[$move_data[1] -1]));
    }
}

print_r($stacks);

$result = implode(array_map("array_pop", $stacks));

print_r($result . "\n");