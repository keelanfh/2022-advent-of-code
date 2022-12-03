<?php

require "helpers/helpers.php";

enum Move: int {
    case Rock = 1;
    case Paper = 2;
    case Scissors = 3;
}

function letter_to_move ($letter) {
    switch ($letter) {
        case "A":
        case "X":
            return Move::Rock;
        case "B":
        case "Y":
            return Move::Paper;
        case "C":
        case "Z":
            return Move::Scissors;
    }
}

function score($mine, $theirs) {
    $WIN_SCORE = 6;
    $DRAW_SCORE = 3;
    $LOSE_SCORE = 0;

    if ($mine == $theirs) {
        return $DRAW_SCORE;
    } if ($mine == Move::Rock) {
        if ($theirs == Move::Scissors) {
            return $WIN_SCORE;
        }
        if ($theirs == Move::Paper) {
            return $LOSE_SCORE;
        }
    } if ($mine == Move::Paper) {
        if ($theirs == Move::Rock) {
            return $WIN_SCORE;
        }
        if ($theirs == Move::Scissors) {
            return $LOSE_SCORE;
        }
    } if ($mine == Move::Scissors) {
        if ($theirs == Move::Paper) {
            return $WIN_SCORE;
        }
        if ($theirs == Move::Rock) {
            return $LOSE_SCORE;
        }
    }
}

$array = read_file_to_array("02/input.txt");

$total_score = 0;

foreach($array as $line) {
    $moves = explode(" ", $line);

    $theirs = letter_to_move($moves[0]);
    $mine = letter_to_move($moves[1]);

    $total_score += $mine->value;
    $total_score += score($mine, $theirs);
}

echo $total_score . "\n"

?>