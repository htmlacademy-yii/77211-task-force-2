<?php

/**
 * @var $faker \Faker\Generator
 */

use app\models\City;

$citiesCount = City::find()->count();

return [
    'email' => $faker->unique()->email(),
    'password' => '$2y$13$InYtC5CneFnBnwdvVvtCPe/utVDGd4LmGmPJ/2NxYfj5nl0WkPDvS', // secret
    'name' => $faker->name(),
    'birthdate' => $faker->date('Y-m-d', '2004-01-01'),
    'info' => $faker->text(),
    'avatar_file_id' => $faker->unique()->numberBetween(1, 100),
    'rating' => $faker->randomFloat(2, 0, 5),
    'city_id' => $faker->numberBetween(1, $citiesCount),
    'phone' => $faker->e164PhoneNumber(),
    'telegram' => "@{$faker->userName()}",
    'role' => $faker->numberBetween(0, 1),
    'status' => $faker->numberBetween(0, 1),
    'last_activity_at' => $faker->dateTimeBetween('-2 week', 'now')->format('Y-m-d H:i:s'),
    'failed_tasks_count' => $faker->numberBetween(0, 5),
    'show_only_customer' => $faker->numberBetween(0, 1),
];