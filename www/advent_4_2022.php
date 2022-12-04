<?php

require "get_input.php";

class AnalizedOverlapedSections {

    private array $content;

    public function __construct(array $content) {
        $this->content = $content;
    }

    private function isOneBlockFullyOverlaped(
        int $block1_initial,
        int $block1_end,
        int $block2_initial,
        int $block2_end,
    ): bool {
        if (
            (($block1_initial <= $block2_initial) && ($block1_end >= $block2_end)) ||
            (($block2_initial <= $block1_initial) && ($block2_end >= $block1_end))
        ){
            return true;
        }
        return false;
    }

    private function isOneBlockPartialOverlaped(
        int $block1_initial,
        int $block1_end,
        int $block2_initial,
        int $block2_end,
    ): bool {
        if (
            (($block1_initial <= $block2_initial) && ($block2_initial <= $block1_end)) ||
            (($block2_initial <= $block1_initial) && ($block1_initial <= $block2_end))
        ){
            return true;
        }
        return false;
    }

    private function analyzePairsIfOverlaped(string $pairs_content, bool $fullOverlap): bool {
        $sections = explode(',', $pairs_content);
        $block_pair_1 = explode('-', $sections[0]);
        $block_pair_2 = explode('-', $sections[1]);
        if ($fullOverlap) {
            return $this->isOneBlockFullyOverlaped($block_pair_1[0], $block_pair_1[1], $block_pair_2[0], $block_pair_2[1]);  
        }
        return $this->isOneBlockPartialOverlaped($block_pair_1[0], $block_pair_1[1], $block_pair_2[0], $block_pair_2[1]);  
    }

    public function getOverlapedPairs(bool $fullOverlap) {
        $count = 0;
        foreach ($this->content as &$pairs_content){
            if ($this->analyzePairsIfOverlaped($pairs_content, $fullOverlap)){
                $count++;
            }
        }
        return $count;
    }

}


$input = get_input("my_puzzles/puzzle_day_4.txt");

$analyzer = new AnalizedOverlapedSections($input);

print("In how many assignment pairs does one range fully contain the other? = " . $analyzer->getOverlapedPairs(true));
print("<br>");
print("In how many assignment pairs do the ranges overlap? = " . $analyzer->getOverlapedPairs(false));


?>