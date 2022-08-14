<?php

/**
 * @var $faker \Faker\Generator
 */

$files = [
    '/img/avatars/1.png',
    '/img/avatars/2.png',
    '/img/avatars/3.png',
    '/img/avatars/4.png',
    '/img/avatars/5.png',
];

return [
    'path' => $faker->randomElement($files),
];