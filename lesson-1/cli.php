<?php

require_once __DIR__ . "/vendor/autoload.php";

use Php\App\{Comments, Users, Staties};

$faker = Faker\Factory::create('ru_RU');

$message = "¬ведите один из аргументов:" . PHP_EOL . " user " . PHP_EOL . " state " . PHP_EOL  . " comment ";

if (empty($argv[1])) {
    die($message);
} else {
    switch ($argv[1]) {
        case 'user':
            echo new Users((int)$faker->uuid(), $faker->firstName(), $faker->lastName());
            break;
        case 'state':
            echo new Staties((int)$faker->uuid(), (int)$faker->uuid(), $faker->title(), $faker->text());
            break;
        case 'comment':
            echo new Comments((int)$faker->uuid(), (int)$faker->uuid(), (int)$faker->uuid(), $faker->text());
            break;
        default:
            echo $message;
    }
}
