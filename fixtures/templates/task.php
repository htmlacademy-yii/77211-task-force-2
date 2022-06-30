<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\Category;
use app\models\City;
use app\models\User;

$categoriesCount = Category::find()->count();
$usersCount = User::find()->count();
$citiesCount = City::find()->count();

return [
    'customer_id' => $faker->numberBetween(1, $usersCount),
    'executor_id' => $faker->numberBetween(1, $usersCount),
    'status' => $faker->numberBetween(1, 5),
    'title' => $faker->sentence(),
    'description' => $faker->text(),
    'category_id' => $faker->numberBetween(1, $categoriesCount),
    'budget' => $faker->numberBetween(500, 10000),
    'city_id' => $faker->optional($weight = 0.7)->numberBetween(1, $citiesCount),
    'coordinates' => null,
    'created_at' => $faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d H:i:s'),
    'deadline_at' => $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s'),
];