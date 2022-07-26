<?php

/**
 * @var $faker \Faker\Generator
 */

$filesCount = 100;
$tasksCount = 20;

return [
    'task_id' => $faker->numberBetween(1, $tasksCount),
    'file_id' => $faker->numberBetween(1, $filesCount),
];