<?php

/*
Code used for the amount of calories per elves
https://adventofcode.com/2022/day/1
*/

class ElvesAnalyzer {

    public function getElfWithMoreCalories(array $elvesCalories, int $top_elves): int {
        $listOfElves = [];
        $tmpCaloryCounter = 0;
        foreach ($elvesCalories as &$calories) {
            if ($calories == "") {
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

?>