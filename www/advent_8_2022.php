<?php

require "get_input.php";

class AnalyzeTreesVisibility {
    private array $matrix;

    private int $x_axis;

    private int $y_axis;

    private array $matrixVisible;
    private array $scenicVisible;

    public function __construct(array $matrix){
        $this->matrix = $matrix;
        $this->y_axis = count($this->matrix);
        $this->x_axis = count($this->matrix[0]);
        $this->matrixVisible = array_fill(0, $this->y_axis, array_fill(0, $this->x_axis, false));
        $this->scenicVisible = array_fill(0, $this->y_axis, array_fill(0, $this->x_axis, 1));

    }

    public function countVisibleTrees() {
        for ($x=0; $x < $this->x_axis; $x++) {
            $max_long = 0;
            for ($y=0; $y < $this->y_axis; $y++) {
                $max_long = $this->getMaxLong($x, $y, $max_long);
                if ($max_long == 9) break;
            }
            $max_long = 0;
            for ($y=$this->y_axis - 1; $y > 0; $y--) {
                $max_long = $this->getMaxLong($x, $y, $max_long);
                if ($max_long == 9) break;
            }
        }

        for ($y=0; $y < $this->y_axis; $y++) {
            $max_long = 0;
            for ($x=0; $x < $this->x_axis; $x++) {
                $max_long = $this->getMaxLong($x, $y, $max_long);
                if ($max_long == 9) break;
            }
            $max_long = 0;
            for ($x=$this->x_axis - 1; $x > 0; $x--) {
                $max_long = $this->getMaxLong($x, $y, $max_long);
                if ($max_long == 9) break;
            }
        }

        $countVisible = 0;
        for ($x=0; $x < $this->x_axis; $x++) {
            for ($y=0; $y < $this->y_axis; $y++) {
                if ($this->matrixVisible[$x][$y]){
                    $countVisible++;
                }
            }
        }
        return $countVisible;
    }

    public function getMaxScenicScore(){
        $max_scenic_score = 0;
        for ($x=1; $x < $this->x_axis - 1; $x++) {
            for ($y=1; $y < $this->y_axis - 1; $y++) {
                for ($left_pointer = $y; $left_pointer > 0; $left_pointer--){
                    if($this->matrix[$x][$left_pointer - 1] >= $this->matrix[$x][$y]){
                        $left_pointer++;
                        break;
                    }
                }
                $left_score = abs($y - $left_pointer);

                for ($right_pointer = $y; $right_pointer < $this->y_axis - 1; $right_pointer++){
                    if($this->matrix[$x][$right_pointer + 1] >= $this->matrix[$x][$y]){
                        $right_pointer++;
                        break;
                    }
                }
                $right_score = abs($y - $right_pointer);

                for ($top_pointer = $x; $top_pointer > 0; $top_pointer--){
                    if($this->matrix[$top_pointer - 1][$y] >= $this->matrix[$x][$y]){
                        $top_pointer++;
                        break;
                    }
                }
                $top_score = abs($x - $top_pointer);


                for ($bottom_pointer = $x; $bottom_pointer < $this->x_axis -1 ; $bottom_pointer++){
                    if($this->matrix[$bottom_pointer + 1][$y] >= $this->matrix[$x][$y]){
                        $bottom_pointer++;
                        break;
                    }
                }
                $bottom_score = abs($x - $bottom_pointer);

                $score = $left_score * $right_score * $top_score * $bottom_score;
                if($score > $max_scenic_score) {
                    print("left_score: " . $left_score . " - right_score: " . $right_score . " - top_score: " . $top_score . " - bottom_score: " . $bottom_score . "<br>");
                    print("x: " . $x . " - y: " . $y . "<br>");
                    $max_scenic_score = $score;
                }
            }
        }
        return $max_scenic_score;

    }

    private function getMaxLong(int $x, int $y, int $current_max_long){
        if($x == 0 || $x == $this->x_axis - 1 || $y == 0 || $y == $this->y_axis - 1 || $this->matrix[$x][$y] > $current_max_long) {
            $this->matrixVisible[$x][$y] = true;
            return $this->matrix[$x][$y];
        }
        return $current_max_long;
    }
    
}

$input = get_input_as_matrix("my_puzzles/puzzle_day_8.txt");
/*
for ($x=0; $x < 99; $x++) {
    for ($y=0; $y < 99; $y++) {
        print($input[$x][$y] . ',');
    }
    print("<br>");
}}*/

print("Q1) " . (new AnalyzeTreesVisibility($input))->countVisibleTrees());
print("<br>");
print("Q2) " . (new AnalyzeTreesVisibility($input))->getMaxScenicScore());





?>