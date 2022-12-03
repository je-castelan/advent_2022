<?php

require "get_input.php";

/*
Code for rock-paper-scissors challenge
https://adventofcode.com/2022/day/2
*/


class RockPaperScissors {

    const ROCK = 'rock';
    const PAPER = 'paper';
    const SCISSORS = 'scissors';

    private $rival_options = [
        'A' => self::ROCK,
        'B' => self::PAPER,
        'C' => self::SCISSORS,
    ];

    private $my_soptions = [
        'X' => self::ROCK,
        'Y' => self::PAPER,
        'Z' => self::SCISSORS 
    ];

    private $options_winners = [
        self::ROCK => self::SCISSORS,
        self::PAPER => self::ROCK,
        self::SCISSORS => self::PAPER, 
    ];

    private $pointsPerOption = [
        self::ROCK => 1,
        self::PAPER => 2,
        self::SCISSORS => 3,
    ];

    // PART 1
    public function getTotalRoundsPoints(array $selections) {
        $total_points = 0;
        foreach ($selections as &$selection) {
            $selections_splitted = explode(" ", $selection);
            $total_points += $this->obtainedRoundPoints($selections_splitted[0], $selections_splitted[1]);
        }
        return $total_points;
    }

    //PART 2
    public function getTotalExpectationPoints(array $selections) {
        $total_points = 0;
        foreach ($selections as &$selection) {
            $selections_splitted = explode(" ", $selection);
            $total_points += $this->obtainedExpectationPoints($selections_splitted[0], $selections_splitted[1]);
        }
        return $total_points;
    }

    //USED FOR PART 1
    private function obtainedRoundPoints(string $rival_selection, string $my_selection) {
        $rival_selection_converted = $this->rival_options[$rival_selection];
        $my_selection_converted = $this->my_soptions[$my_selection];
        $points = $this->pointsPerOption[$my_selection_converted];
        if ($rival_selection_converted == $my_selection_converted ){
            $points += 3;
        } else if ($this->options_winners[$my_selection_converted] == $rival_selection_converted) {
            $points += 6;
        }
        return $points;
    }

    //USED FOR PART 2
    private function obtainedExpectationPoints(string $rival_selection, string $expected_result) {
        $options_losers = array_flip($this->options_winners);
        $rival_selection_converted = $this->rival_options[$rival_selection];
        $points = 0;
        switch($expected_result) {
            case 'X':
                $points += $this->pointsPerOption[$this->options_winners[$rival_selection_converted]];
                break;
            case 'Y':
                $points += 3 + $this->pointsPerOption[$rival_selection_converted];
                break;
            case 'Z':
                $points += 6 + $this->pointsPerOption[$options_losers[$rival_selection_converted]];
                break;               
        }
        return $points;
    }
}

$input = get_input("my_puzzles/puzzle_day_2.txt");

$analyzer = new RockPaperScissors();

print("Points for rounds: ". $analyzer->getTotalRoundsPoints($input));
print("<br>");
print("Points for expected result: ". $analyzer->getTotalExpectationPoints($input));


?>