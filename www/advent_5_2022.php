<?php

require "get_input.php";

class StackInstruction {
    private int $crateAmount;
    private int $fromStack;
    private int $toStack;

    public function __construct(string $command_text) {
        $words_command = explode(' ', $command_text);
        $this->crateAmount = intval($words_command[1]);
        $this->fromStack = intval($words_command[3]);
        $this->toStack = intval($words_command[5]);
    }

    public function getCrateAmount(): int {
        return $this->crateAmount;
    }

    public function getFromStack(): int {
        return $this->fromStack;
    }

    public function getToStack(): int {
        return $this->toStack;
    }
}

class Stack {
    private array $stack_content;

    public function __construct(string $init_stack) {
        $this->stack_content = str_split($init_stack);
    }

    public function pop(): string {
        return array_pop($this->stack_content);
    }

    public function push(string $value): void {
        array_push($this->stack_content, $value);
    }

    public function popBlock(int $amount) {
        $removed = array_slice($this->stack_content, count($this->stack_content) - $amount);
        $this->stack_content = array_slice($this->stack_content, 0, count($this->stack_content) - $amount);
        return $removed;
    }

    public function pushBlock(array $value) {
        $this->stack_content = array_merge($this->stack_content, $value);
    }

    public function getLastValue(): string {
        return end($this->stack_content);
    }

}


class CratesArragement {

    private array $stacks;
    private array $commands;

    public function __construct(array $stacks_string, array $commands_text) {
        $this->stacks = [];
        $counter_stack = 1;
        foreach ($stacks_string as &$stack_string){
            $stack = new Stack($stack_string);
            $this->stacks[$counter_stack] = $stack;
            $counter_stack++;
        }
        $this->commands = [];
        foreach ($commands_text as &$command_text){
            $command = new StackInstruction($command_text);
            array_push($this->commands, $command);
        }
    }

    private function moveCrates(int $origin, int $destination, int $amount){
        for ($move = 0; $move < $amount; $move++) {
            $popped = $this->stacks[$origin]->pop();
            $this->stacks[$destination]->push($popped);
        }
    }

    private function moveCratesByBlocks(int $origin, int $destination, int $amount){
        $popped = $this->stacks[$origin]->popBlock($amount);
        $this->stacks[$destination]->pushBlock($popped);
    }

    public function rearrageStacks(bool $stayOrderByBlocksMoved): void {
        foreach ($this->commands as &$command){
            if (!$stayOrderByBlocksMoved){
                $this->moveCrates($command->getFromStack(), $command->getToStack(), $command->getCrateAmount());
            } else {
                $this->moveCratesByBlocks($command->getFromStack(), $command->getToStack(), $command->getCrateAmount());
            }  
        }
    }

    public function getTopValues(): string {
        $string_result = '';
        foreach($this->stacks as &$stack) {
            $string_result .= $stack->getLastValue();
        }
        return $string_result;
    }
}



$stacks_text = get_input("my_puzzles/stacks_puzzle_day_5.txt");
$instructions_text = get_input("my_puzzles/instructions_puzzle_day_5");
$solve = new CratesArragement($stacks_text, $instructions_text);
$solve->rearrageStacks(false);
print("Q1) After the rearrangement procedure completes, what crate ends up on top of each stack? " . $solve->getTopValues());
print("<br>");
$solve = new CratesArragement($stacks_text, $instructions_text);
$solve->rearrageStacks(true);
print("Q2) After the rearrangement procedure completes, what crate ends up on top of each stack? " . $solve->getTopValues());
print("<br>");
?>