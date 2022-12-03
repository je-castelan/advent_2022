<?php

require "get_input.php";

function getItemsPriorities() {
    return array_flip(str_split("-abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
}


function getPriority(string $items) {
    $items_priorities = getItemsPriorities();

    $itemsAsArray = str_split($items);

    $compartiment_1 = array_slice($itemsAsArray, 0, count($itemsAsArray) / 2);
    $compartiment_2 = array_slice($itemsAsArray, count($itemsAsArray) / 2);

    $priority = 0;
    foreach($compartiment_1 as &$item) {
        if (in_array($item, $compartiment_2)) {
            $priority = $items_priorities[$item];
            break;
        }
    }
    return $priority;
}

function getPriorityByTeams(array $items_groups) {
    $items_priorities = getItemsPriorities();
    $priority = 0;
    foreach(str_split($items_groups[0]) as &$item) {
        if (in_array($item, str_split($items_groups[1])) && in_array($item, str_split($items_groups[2]))) {
            $priority = $items_priorities[$item];
            break;
        }
    }
    return $priority;
}

function getSumPriorities(array $listItems) {
    $sum = 0;
    foreach($listItems as &$items) {
        $sum += getPriority($items);
    }
    return $sum;
}

function getSumPrioritiesByTeamsOfThree(array $listItems) {
    $sum = 0;
    $groups = array_chunk($listItems, 3);
    foreach($groups as &$items_groups) {
        $sum += getPriorityByTeams($items_groups);
    }
    return $sum;
}

$input = get_input("my_puzzles/puzzle_day_3.txt");
print(getSumPriorities($input));
print("<br>");
print(getSumPrioritiesByTeamsOfThree($input));

?>