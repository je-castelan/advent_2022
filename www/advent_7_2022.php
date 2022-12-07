<?php

require "get_input.php";

abstract class Element {

    private string $name;
    private ?Element $memberOf;

    public function __construct(string $name, ?Element $memberOf){
        $this->name = $name;
        $this->memberOf = $memberOf;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getMemberOf(): Element {
        return $this->memberOf;
    }

    public function hasContent(): bool {
        return false;
    }
}

class FileElement extends Element {
    private int $size;

    public function __construct(string $name, ?Element $memberOf, int $size){
        parent::__construct($name, $memberOf);
        $this->size = $size;
    }

    public function getSize(): int {
        return $this->size;
    }

}

class DirectoryElement extends Element {

    private array $content;

    public function __construct(string $name, ?Element $memberOf){
        parent::__construct($name, $memberOf);
        $this->content = [];
    }

    public function hasContent(): bool {
        return true;
    }

    public function addElement(Element $element): void {
        array_push($this->content, $element);
    }

    public function getElement(string $name): ?Element {
        foreach ($this->content as &$element) {
            if ($element->getName() == $name) {
                return $element;
            }
        }
        return null;
    }

    public function getContent(): array {
        return $this->content;
    }
}

class Compiler {

    private array $directories;

    private array $commands;

    public Element $pointer;

    public function __construct(array $commands){
        $this->directories = [new DirectoryElement("/", null)];
        $this->commands = $commands;
    }

    public function executeCommands() {
        for ($line = 0; $line < count($this->commands); $line++){
            $command = trim($this->commands[$line]);
            if ($command == '$ cd /') {
                $this->pointer = $this->directories[0];
            }
            else if ($command == '$ cd ..') {
                $this->pointer = $this->pointer->getMemberOf();
            }
            else if ($command == '$ ls') {
               while (!str_starts_with($this->commands[$line + 1], '$')) {
                    $content = $this->commands[$line + 1];
                    if (str_starts_with($content, 'dir')) {
                        $element = new DirectoryElement(explode(' ',$content)[1], $this->pointer);
                        array_push($this->directories, $element);
                    } else {
                        $values = explode(' ',$content);
                        $element = new FileElement($values[1], $this->pointer, $values[0]);
                    }
                    $this->pointer->addElement($element);
                    $line++;

                    if($line + 1 == count($this->commands)) {
                        break;
                    }
               }
            }
            else { // cd x
                $values = explode(' ',$command);
                $name = end($values);
                $this->pointer = $this->pointer->getElement($name);
            }
        }

    }

    public function analizeSizes(): void {
        $directory_sizes = [];
        foreach ($this->directories as &$directory) {
            $size = 0;
            $content_to_analize = $directory->getContent();
            while (count($content_to_analize) != 0) {

                $element = $content_to_analize[0];
                if ($element->hasContent()) {
                    $content_to_analize = array_merge($content_to_analize, $element->getContent());
                } else {
                    $size += $element->getSize();
                }
                $content_to_analize = array_slice($content_to_analize, 1); 
            }
            array_push($directory_sizes, [
                "name" => $directory->getName(),
                "size" => $size
            ]);
        }
        $sum_to_remove = 0;
        $max_size = 0;
        foreach ($directory_sizes as &$directory) {
            if ($directory["size"] < 100000) {
                $sum_to_remove += $directory["size"];
            }
            if ($max_size < $directory["size"]) {
                $max_size = $directory["size"];
            }
        }

        $min_to_remove = 0;
        foreach ($directory_sizes as &$directory) {
            if ($min_to_remove == 0 || ((70000000 - $max_size + $directory["size"]) > 30000000 && $directory["size"] < $min_to_remove)) {
                $min_to_remove = $directory["size"];
            }
        }

        print("Q1) " .$sum_to_remove);
        print("<br>");
        print("Q2) " .$min_to_remove);

    }

}


$commands = get_input("my_puzzles/puzzle_day_7.txt");


$compiler = new Compiler($commands);
$compiler->executeCommands();
$compiler->analizeSizes();

?>