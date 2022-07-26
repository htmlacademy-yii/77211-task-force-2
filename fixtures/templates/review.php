<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Task;

$tasksCount = 20;
$usersCount = 10;

return [
    'task_id' => $faker->unique()->numberBetween(1, $tasksCount),
    'author_id' => $faker->numberBetween(1, $usersCount),
    'user_id' => $faker->numberBetween(1, $usersCount),
    'rate' => $faker->numberBetween(1, 5),
    'comment' => $faker->text(100),
    'created_at' => $faker->dateTimeBetween('-12 hours', 'now')->format('Y-m-d H:i:s'),
];