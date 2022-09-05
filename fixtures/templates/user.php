<?php

/**
 * @var $faker \Faker\Generator
 */

$citiesCount = 1087;

return [
    'email' => $faker->unique()->email(),
    'password' => '$2y$13$InYtC5CneFnBnwdvVvtCPe/utVDGd4LmGmPJ/2NxYfj5nl0WkPDvS', // secret
    'name' => $faker->name(),
    'birthdate' => $faker->date('Y-m-d', '2004-01-01'),
    'info' => $faker->text(),
    'avatar_file_id' => $faker->unique()->numberBetween(1, 10),
    'rating' => $faker->randomFloat(2, 0, 5),
    'city_id' => $faker->numberBetween(1, $citiesCount),
    'phone' => '12345678910',
    'telegram' => $faker->userName(),
    'role' => $faker->numberBetween(0, 1),
    'status' => $faker->numberBetween(0, 1),
    'created_at' => $faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d H:i:s'),
    'failed_tasks_count' => $faker->numberBetween(0, 5),
    'show_only_customer' => $faker->numberBetween(0, 1),
];