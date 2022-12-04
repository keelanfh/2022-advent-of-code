<?php

require("helpers/helpers.php");

$lines = read_file_to_array("04/input.txt");

$total = 0;
$p2_total = 0;

foreach ($lines as $line) {
    $areas = explode(",", $line);
    $first = explode("-", $areas[0]);
    $second = explode("-", $areas[1]);

    $first_from = intval($first[0]);
    $first_to = intval($first[1]);

    $second_from = intval($second[0]);
    $second_to = intval($second[1]);

    // this will be positive if first starts to the right
    // and negative if first starts to the left
    // 0 when first and second start in the same place
    $from_difference = $first_from - $second_from;

    // now we just treat these three cases differently

    // first starts to the left
    if ($from_difference < 0){
        if ($first_to >= $second_to){
            $total++;
        }
        if ($first_to >= $second_from) {
            $p2_total++;
        }
    }
    // second starts to the left
    elseif ($from_difference > 0){
        if ($second_to >= $first_to){
            $total++;
        }
        if ($second_to >= $first_from) {
            $p2_total++;
        }
    }
    // start in the same place
    // one always contains the other
    elseif ($from_difference == 0){
        $total++;
        $p2_total++;
    }

    }


echo $total . "\n";
echo $p2_total . "\n";


?>