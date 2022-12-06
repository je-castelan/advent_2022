<?php

require "get_input.php";

$input = get_input("my_puzzles/puzzle_day_6.txt")[0];

function getFirstStartOfPacketEnd(string $message, int $lenght) {
    $message_array = str_split($message);
    for ($pointer = $lenght - 1; $pointer < count($message_array); $pointer++) {
        $tmp_array = array_slice($message_array, $pointer - ($lenght - 1), $lenght);
        if (count(array_unique($tmp_array)) == $lenght) {
            return $pointer + 1;
        }
    }
    return 0;
}

print("Solution 1: " . getFirstStartOfPacketEnd($input, 4));
print("<br>");
print("Solution 2: " . getFirstStartOfPacketEnd($input, 14));

?>