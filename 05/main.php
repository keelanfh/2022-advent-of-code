<?php

require("helpers/helpers.php");

$lines = read_file_to_array("05/input.txt");
$lines = array_to_group_arrays($lines, "", false);

$stacks = $lines[0];
$moves = $lines[1];

// remove the last, useless element
array_pop($stacks);

$stacks = array_map(fn($line) => str_split($line, 4), $stacks);

$stacks_nobrackets = [];
foreach ($stacks as $stack) {
    array_push($stacks_nobrackets, array_map(fn($line) => str_replace(["[", "]"], "", $line), $stack));
}

function clean_stack($stack) {
    $stack = array_map(fn($a) => str_replace(" ", "", $a), $stack);
    // this will just remove falsy values
    $stack = array_filter($stack);
    $stack = array_reverse($stack);
    return $stack;
}

// transposes the array
// it isn't clear to me why this is the syntax for this...
$stacks = array_map(null, ...$stacks_nobrackets);
$stacks = array_map("clean_stack", $stacks);

// now for the moves
function move_to_movedata($line) {
    $matches = [];
    preg_match("/^move (\d{1,2}) from (\d) to (\d)$/", $line, $matches);
    return array_slice($matches, 1);
}

$moves_data = array_map("move_to_movedata", $moves);

// make a copy for part 2
$stacks_2 = $stacks;

// moving - part 1
foreach ($moves_data as $move_data) {
    for ($submove=0; $submove < $move_data[0]; $submove++) {
        array_push($stacks[$move_data[2]-1], array_pop($stacks[$move_data[1] -1]));
    }
}

println(implode(array_map("array_pop", $stacks)));

// moving - part 2
$stacks = $stacks_2;

foreach ($moves_data as $move_data) {
    $temp_array = [];
    for ($submove=0; $submove < $move_data[0]; $submove++) {
        array_push($temp_array, array_pop($stacks[$move_data[1]-1]));
    }
    array_push($stacks[$move_data[2]-1], ...array_reverse($temp_array));
}

println(implode(array_map("array_pop", $stacks)));
