<?php

require "get_input.php";

class Cord{
    private int $x;
    private int $y;
    private ?Cord $dependentCord; 
    public string $name;

    public function __construct(int $x, int $y, string $name = "xd", ?Cord $dependentCord = null){
        $this->x = $x;
        $this->y = $y;
        $this->dependentCord = $dependentCord;
        $this->name = $name;
    }

    public function moveUp(){
        $this->x -= 1;
    }

    public function moveDown(){
        $this->x += 1;
    }

    public function moveLeft(){
        $this->y -= 1;
    }

    public function moveRight(){
        $this->y += 1;
    }

    public function getX(){
        return $this->x;
    }

    public function getY(){
        return $this->y;
    }

    public function setX(int $x){
        $this->x = $x;
    }

    public function setY(int $y){
        $this->y = $y;
    }

    public function &getDependentCord(): ?Cord{
        return $this->dependentCord;
    }
    
    public function putOnSameXAsParentCord(Cord $cord){
        $this->x = $cord->getX();
    }

    public function putOnSameYAsParentCord(Cord $cord){
        $this->y = $cord->getY();
    }

    public function putOnSameXToDependentCord(){
        $this->dependentCord->putOnSameXAsParentCord($this);
    }

    public function putOnSameYToDependentCord(){
        $this->dependentCord->putOnSameYAsParentCord($this);
    }


}

 class MoveRope{

    private array $matrix;

    private int $matrix_size;

    private Cord $head;

    private Cord $h;

    public function __construct(Cord $cord, int $matrix_size){
        $this->matrix_size = $matrix_size;
        $this->matrix = array_fill(0, $matrix_size, array_fill(0, $matrix_size, false));
        $this->head = $cord;
        $this->matrix[$this->head->getX()][$this->head->getY()] = true;
    }

    public function moveHead(string $direction, int $positions){

        for ($_ = 0; $_ < $positions; $_++){
            switch($direction) {
                case 'U':
                    $this->head->moveUp();
                    break;
                case 'D':
                    $this->head->moveDown();
                    break;
                case 'L':
                    $this->head->moveLeft();
                    break;
                case 'R':
                    $this->head->moveRight();
                    break;
            }
            $this->adjustDependents();
        }
    }

    public function getTailPositions(): int{
        $positions = 0;
        for ($x=0; $x < $this->matrix_size; $x++) {
            for ($y=0; $y < $this->matrix_size; $y++) {
                if($this->matrix[$x][$y]){
                    $positions++;
                }
            }
        }
        return $positions;
    }

    private function adjustDependents(){
        $parentCord = &$this->head;
        $x = 0;
        $y = 0;
        
        while ($parentCord->getDependentCord() != null) {

            if (abs($parentCord->getY() - $parentCord->getDependentCord()->getY()) == 2 && abs($parentCord->getX() - $parentCord->getDependentCord()->getX()) == 2){
                $parentCord->getDependentCord()->setX(($parentCord->getX() + $parentCord->getDependentCord()->getX())/2);
                $parentCord->getDependentCord()->setY(($parentCord->getY() + $parentCord->getDependentCord()->getY())/2);
            }
            
            
            if($parentCord->getY() == $parentCord->getDependentCord()->getY() + 2){
                $parentCord->getDependentCord()->moveRight();
                $this->adjustTailOnX($parentCord);
            }
            else if($parentCord->getY() == $parentCord->getDependentCord()->getY() - 2){
                $parentCord->getDependentCord()->moveLeft();
                $this->adjustTailOnX($parentCord);
            }
            else if($parentCord->getX() == $parentCord->getDependentCord()->getX() + 2){
                $parentCord->getDependentCord()->moveDown();
                $this->adjustTailOnY($parentCord);
                
            }
            else if($parentCord->getX() == $parentCord->getDependentCord()->getX() - 2){
                $parentCord->getDependentCord()->moveUp();
                $this->adjustTailOnY($parentCord);
            }
           

            $x = $parentCord->getDependentCord()->getX();
            $y = $parentCord->getDependentCord()->getY();

            $parentCord = &$parentCord->getDependentCord();
            
        }

        


        
        $this->matrix[$x][$y] = true;
    }

    private function adjustTailOnX(Cord $parentCord){
        if($parentCord->getX() != $parentCord->getDependentCord()->getX()){
            $parentCord->putOnSameXToDependentCord();
        }
    }

    private function adjustTailOnY(Cord $parentCord){
        if($parentCord->getY() != $parentCord->getDependentCord()->getY()){
            $parentCord->putOnSameYToDependentCord();
        }
    }

 }


 $moves = get_input("my_puzzles/puzzle_day_9.txt");

 $matrix_size = 5000;

$cord2 = new Cord($matrix_size/2, $matrix_size/2, "x");
$cord1 = new Cord($matrix_size/2, $matrix_size/2, "y", $cord2);


$cord09 = new Cord($matrix_size/2, $matrix_size/2,'009');
$cord08 = new Cord($matrix_size/2, $matrix_size/2,'008', $cord09);
$cord07 = new Cord($matrix_size/2, $matrix_size/2,'007', $cord08);
$cord06 = new Cord($matrix_size/2, $matrix_size/2,'006', $cord07);
$cord05 = new Cord($matrix_size/2, $matrix_size/2,'005', $cord06);
$cord04 = new Cord($matrix_size/2, $matrix_size/2,'004', $cord05);
$cord03 = new Cord($matrix_size/2, $matrix_size/2,'003', $cord04);
$cord02 = new Cord($matrix_size/2, $matrix_size/2,'002', $cord03);
$cord01 = new Cord($matrix_size/2, $matrix_size/2,'001', $cord02);
$cord0H = new Cord($matrix_size/2, $matrix_size/2,'001', $cord01);



 $ropeA = new MoveRope($cord1,  $matrix_size);
 $ropeB = new MoveRope($cord0H,  $matrix_size);



 foreach($moves as &$move){
    $parts = explode(' ', $move);
    $ropeA->moveHead($parts[0], intval($parts[1]));
    $ropeB->moveHead($parts[0], intval($parts[1]));

 }
 print("Q1) " . $ropeA->getTailPositions());
 print("<br>");
 print("Q2) " . $ropeB->getTailPositions());


?>