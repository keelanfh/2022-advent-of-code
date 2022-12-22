<?php

// deprecated, retained for now for compatibility
function read_file_to_array($filepath)
{
    $contents = readFileToString($filepath);
    return stringSplit($contents, "\n");
}

function println($string)
{
    echo $string . PHP_EOL;
}

function arrayVerticalSlice(array $array, int $j): array
{
    return array_map(fn ($a) => $a[$j], $array);
}

function stringSplit(string $string, string $delim1, string $delim2 = null): array
{
    $result = explode($delim1, $string);
    if (isset($delim2)) {
        $result = array_map(fn ($a) => explode($delim2, $a), $result);
    }

    return $result;
}

function readFileToString(string $filepath): string
{
    $file = fopen($filepath, 'r');
    return fread($file, filesize($filepath));
}
