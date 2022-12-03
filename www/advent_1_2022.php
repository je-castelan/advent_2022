<?php

require "get_input.php";

/*
Code used for the amount of calories per elves
https://adventofcode.com/2022/day/1
*/

class ElvesAnalyzer {

    public function getElfWithMoreCalories(array $elvesCalories, int $top_elves): int {
        $listOfElves = [];
        $tmpCaloryCounter = 0;
        foreach ($elvesCalories as &$calories) {
            if (!is_numeric($calories)) {
                array_push($listOfElves, $tmpCaloryCounter);
                $tmpCaloryCounter = 0;
            } else {
                $tmpCaloryCounter += intval($calories);
            }
        }
        array_push($listOfElves, $tmpCaloryCounter);
        rsort($listOfElves);
        return array_sum(array_slice($listOfElves, 0, $top_elves ));
    }
}

$input = get_input("my_puzzles/puzzle_day_1.txt");

$analyzer = new ElvesAnalyzer();

print("Max calories for top 1 is ". $analyzer->getElfWithMoreCalories($input, 1));
print("<br>");
print("Max calories for top 3 is ". $analyzer->getElfWithMoreCalories($input, 3));


?>