<?php

/**
 * @var $faker \Faker\Generator
 */

$categoriesCount = 8;
$usersCount = 10;

return [
    'user_id' => $faker->numberBetween(1, $usersCount),
    'category_id' => $faker->numberBetween(1, $categoriesCount),
];