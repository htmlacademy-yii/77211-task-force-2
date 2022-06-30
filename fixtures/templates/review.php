<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Task;

$tasksCount = Task::find()->count();

return [
    'task_id' => $faker->unique()->numberBetween(1, $tasksCount),
    'rate' => $faker->numberBetween(1, 5),
    'comment' => $faker->text(100),
];