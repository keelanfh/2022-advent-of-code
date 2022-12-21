<?php

require_once("helpers/helpers.php");

enum Move: int
{
    case Rock = 1;
    case Paper = 2;
    case Scissors = 0;

    public function value123(): int
    {
        $value = $this->value;
        if ($value == 0) {
            $value = 3;
        }
        return $value;
    }

    public function howToDraw(): Move
    {
        return $this;
    }

    public function howToWin(): Move
    {
        // to win, you need one above in the ranking
        return Move::from(($this->value + 1) % 3);
    }

    public function howToLose(): Move
    {
        // to lose, you need one below in the ranking
        // % returns a value with the same sign as the input, so we add 3 to keep this in range
        return Move::from(($this->value - 1 + 3) % 3);
    }

    public function difference(Move $other): int
    {
        return ($this->value - $other->value + 3) % 3;
    }
}

function letterToMove($letter)
{
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

function score($mine, $theirs)
{
    $WIN_SCORE = 6;
    $DRAW_SCORE = 3;
    $LOSE_SCORE = 0;

    switch ($mine->difference($theirs)) {
        case 0:
            return $DRAW_SCORE;
        case 1:
            return $WIN_SCORE;
            // 2 === -1 (mod 3)
        case 2:
            return $LOSE_SCORE;
    }
}

$array = read_file_to_array("02/input.txt");

// Part 1
$total_score = 0;

foreach ($array as $line) {
    $moves = explode(" ", $line);

    $theirs = letterToMove($moves[0]);
    $mine = letterToMove($moves[1]);

    $total_score += $mine->value123();
    $total_score += score($mine, $theirs);
}

println($total_score);

// Part 2
$total_score = 0;

foreach ($array as $line) {
    $moves = explode(" ", $line);

    $theirs = letterToMove($moves[0]);

    // set my move to the right one
    $mine = match ($moves[1]) {
        "X" => $theirs->howToLose(),
        "Y" => $theirs->howToDraw(),
        "Z" => $theirs->howToWin(),
    };

    $total_score += $mine->value123();
    $total_score += score($mine, $theirs);
}

println($total_score);
