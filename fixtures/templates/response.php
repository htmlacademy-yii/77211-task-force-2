<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Task;
use app\models\User;

$tasksCount = 20;
$usersCount = 10;

return [
    'task_id' => $faker->numberBetween(1, $tasksCount),
    'executor_id' => $faker->numberBetween(1, $usersCount),
    'comment' => $faker->text(),
    'budget' => $faker->numberBetween(500, 10000),
    'is_refused' => $faker->numberBetween(0, 1),
    'created_at' => $faker->dateTimeBetween('-12 hours', 'now')->format('Y-m-d H:i:s'),
];