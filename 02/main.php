<?php

require "helpers/helpers.php";

$WIN_SCORE = 6;
$DRAW_SCORE = 3;
$LOSE_SCORE = 0;

function move_to_value($move) {
    $value = $move->value;
    if ($value == 0) {
        $value = 3;
    }
    return $value;
}

enum Move: int {
    case Rock = 1;
    case Paper = 2;
    case Scissors = 0;

    public function how_to_draw(): Move
    {
        return $this;
    }
    
    public function how_to_win(): Move
    {
        // to win, you need one above in the ranking
        return Move::from(($this->value + 1) % 3);
    }

    public function how_to_lose(): Move
    {
        // to lose, you need one below in the ranking
        // % returns a value with the same sign as the input, so we add 3 to keep this in range
        return Move::from(($this->value -1 + 3) % 3);
    }

    public function difference(Move $other): int 
    {
        return ($this->value - $other->value + 3) % 3;
    }

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
    global $WIN_SCORE, $DRAW_SCORE, $LOSE_SCORE;

    
    switch ($mine->difference($theirs)) {
        case 0:
            return $DRAW_SCORE;
    }
    
    switch ($mine) {
        case Move::Rock:
            return match($theirs)
                {
                    Move::Scissors => $WIN_SCORE,
                    Move::Paper => $LOSE_SCORE,
                };

        case Move::Paper:
            return match($theirs)
                {
                    Move::Rock => $WIN_SCORE,
                    Move::Scissors => $LOSE_SCORE,
                };
                
        case Move::Scissors:
            return match($theirs)
                {
                    Move::Paper => $WIN_SCORE,
                    Move::Rock => $LOSE_SCORE,
                };
    }
}

$array = read_file_to_array("02/input.txt");


// Part 1
$total_score = 0;

foreach($array as $line) {
    $moves = explode(" ", $line);

    $theirs = letter_to_move($moves[0]);
    $mine = letter_to_move($moves[1]);

    $total_score += move_to_value($mine);
    $total_score += score($mine, $theirs);
}

echo $total_score . "\n";

$mine = 0;

// Part 2
$total_score = 0;

foreach($array as $line) {
    $moves = explode(" ", $line);

    $theirs = letter_to_move($moves[0]);

    $mine = match($moves[1])
        {
            "X" => $theirs->how_to_lose(),
            "Y" => $theirs->how_to_draw(),
            "Z" => $theirs->how_to_win(),
        };

    $total_score += move_to_value($mine);
    $total_score += score($mine, $theirs);

}

echo $total_score . "\n";

?>