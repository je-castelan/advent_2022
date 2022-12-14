<?php

require "get_input.php";

class CPU {
    private int $cycle;

    private int $x;

    private int $sum_strengths;

    private array $queue_execution;

    private array $requested_signal_strength;

    private array $crt_cycles;

    public function __construct(){
        $this->cycle = 1;
        $this->x = 1;
        $this->queue_execution = [];
        $this->sum_strengths = 0;
        $this->crt_cycles = array_fill(1, 240, ".");
        $this->crt_cycles[1] = "#";
    }

    private function execute(string $instruction){
        
        if ($instruction != "noop"){
            $xincrement = intval(explode(" ", $instruction)[1]);

            $this->advanceCycle();

            $this->x += $xincrement;

            $this->advanceCycle();
            
            
        } else {
            $this->advanceCycle();
        }
    }

    private function advanceCycle() {
        $this->cycle ++;
        if (in_array($this->cycle, $this->requested_signal_strength)) {
            $this->sum_strengths += $this->cycle * $this->x;
        }
        if (in_array(($this-> cycle % 40) , [$this->x, $this->x + 1, $this->x + 2])){
            $this->crt_cycles[$this->cycle] = "#";
        }
    }

    public function executeBulk(array $instructions, $requested_signal_strength){
        $this->requested_signal_strength = $requested_signal_strength;
        foreach($instructions as &$instruction){
            $this->execute($instruction);
        }
    }

    public function getSumStrengths(){
        return $this->sum_strengths;
    }

    public function getCRTDraw(){
        $position = 1;
        for ($x = 1 ; $x <= 6; $x++){
            for($y = 1; $y <= 40; $y++) {
                print($this->crt_cycles[$position]);
                $position++;
            }
            print("<br>");
        }
    }

}



$instructions = get_input("my_puzzles/puzzle_day_10.txt");
$cpu = new CPU();
$requested_signal_strength = [20, 60, 100, 140, 180, 220];
$cpu->executeBulk($instructions, $requested_signal_strength);
print("Q1) ". $cpu->getSumStrengths());

print("Q2) ");
print("<br><br>");
$cpu->getCRTDraw();


?>