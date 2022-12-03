<?php

function read_file_to_array($filepath)
{
    // Open the file
    $file = fopen($filepath, 'r'); 

    // Add each line to an array
    $array = explode("\n", fread($file, filesize($filepath)));

    return $array;
}

function array_to_group_arrays($big_array, $delim_or_height, $to_int)
{
    $output_array = [];
    $small_array = [];
    $row = 0;
    foreach ($big_array as $value) {
        // Arrange lines in groups of set length
        if (is_int($delim_or_height)) {
            if (($row != 0) and (($row % $delim_or_height) == 0)) {
                array_push($output_array, $small_array);
                $small_array = [];
            }

        // Arrange lines by delimiter
        } elseif ($value == $delim_or_height) {
            array_push($output_array, $small_array);
            $small_array = [];
            // Continue here because we don't want blank lines.
            continue;
        }

        if ($to_int) {
            $value = intval($value);
        }

        array_push($small_array, $value);
        $row++;
    }

    // Remember to add the final small array to the big one
    array_push($output_array, $small_array);
    return $output_array;
}

function read_file_to_int_groups($filepath) {
    $big_array = read_file_to_array($filepath);
    return array_to_group_arrays($big_array, "", true);
}

?>