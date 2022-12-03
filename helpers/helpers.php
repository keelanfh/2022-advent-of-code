<?php

function read_file_to_array($filepath)
{
    // Open the file
    $file = fopen($filepath, 'r'); 

    // Add each line to an array
    $array = explode("\n", fread($file, filesize($filepath)));

    return $array;
}

function array_to_group_arrays($big_array, $delim, $to_int)
{
    $output_array = [];
    $small_array = [];
    foreach ($big_array as $value) {
        if ($value == $delim) {
            array_push($output_array, $small_array);
            $small_array = [];
        } else {
            if ($to_int) {
                $new_value = intval($value);
            } else {
                $new_value = $value;
            }
            array_push($small_array, $new_value);
        }
    }
    array_push($output_array, $small_array);
    return $output_array;
}

function read_file_to_int_groups($filepath) {
    $big_array = read_file_to_array($filepath);
    return array_to_group_arrays($big_array, "", true);
}

?>