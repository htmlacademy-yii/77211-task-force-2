<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Category;
use app\models\User;

$categoriesCount = Category::find()->count();
$usersCount = User::find()->count();

return [
    'user_id' => $faker->numberBetween(1, $usersCount),
    'category_id' => $faker->numberBetween(1, $categoriesCount),
];