<?php

/**
 * @var $faker \Faker\Generator
 */

$filesCount = \app\models\File::find()->count();
$tasksCount = \app\models\Task::find()->count();

return [
    'task_id' => $faker->unique()->numberBetween(1, $tasksCount),
    'file_id' => $faker->unique()->numberBetween(1, $filesCount),
];