<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Task;
use app\models\User;

$tasksCount = Task::find()->count();
$usersCount = User::find()->count();

return [
    'task_id' => $faker->unique()->numberBetween(1, $tasksCount),
    'executor_id' => $faker->numberBetween(1, $usersCount),
    'comment' => $faker->text(),
    'budget' => $faker->numberBetween(500, 10000),
    'is_refused' => $faker->numberBetween(0, 1),
];