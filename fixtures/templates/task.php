<?php

/**
 * @var $faker \Faker\Generator
 */

$categoriesCount = 8;
$usersCount = 10;

return [
    'customer_id' => $faker->numberBetween(1, $usersCount),
    'executor_id' => $faker->optional(0.6)->numberBetween(1, $usersCount),
    'status' => $faker->optional(0.6, 1)->numberBetween(2, 5),
    'title' => $faker->sentence(),
    'description' => $faker->text(),
    'category_id' => $faker->numberBetween(1, $categoriesCount),
    'budget' => $faker->optional(0.8)->numberBetween(500, 10000),
    'city_id' => null,
    'address' => null,
    'coordinates' => null,
    'created_at' => $faker->dateTimeBetween('-36 hours', 'now')->format('Y-m-d H:i:s'),
    'deadline_at' => $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
];