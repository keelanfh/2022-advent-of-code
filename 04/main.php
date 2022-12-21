<?php

require("helpers/helpers.php");

$lines = read_file_to_array("04/input.txt");

$total = 0;
$p2_total = 0;

foreach ($lines as $line) {
    $areas = explode(",", $line);
    
    // split each area by -
    $areas = array_map(fn($area) => explode("-", $area), $areas);

    // sort by the first element of each subarray
    usort($areas, fn($a, $b) => $a[0] <=> $b[0]);

    // special case where both start in the same place
    // these always overlap
    if ($areas[0][0] == $areas[1][0]) {
        $total++;
        $p2_total++;

    } else {
        // complete overlap
        if ($areas[0][1] >= $areas[1][1]) {
            $total++;
        }

        // partial overlap
        if ($areas[0][1] >= $areas[1][0]) {
            $p2_total++;
        }
    }
}


println($total);
println($p2_total);
