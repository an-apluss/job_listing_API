<?php

use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->firstName($gender = 'male'|'female'), 
        'last_name' => $faker->lastName,
        'user_name' => $faker->userName,
        'password' => Hash::make('secret'),
        'is_admin' => function() {
            $possibleValue = array(true, false);
            return $possibleValue[rand(0, (count($possibleValue) - 1))];
        }
    ];
});

$factory->define(App\Job::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->jobTitle,
        'company' => $faker->company,
        'description' => $faker->paragraph($nbSentences = 7, $variableNbSentences = true),
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'location' => function() {
            $possibleState = array("Lagos", "Enugu", "Oyo", "Anambra", "Ogun", "Abuja");
            return $possibleState[rand(0, (count($possibleState) - 1))];
        },
        'job_type' => function() {
            $possibleType = array("Full-Time", "Part-Time");
            return $possibleType[rand(0, (count($possibleType) - 1))];
        },
        'salary' => function() {
            $possibleSalary = array(250000, 150000, 200000, 300000, 500000, 100000);
            return $possibleSalary[rand(0, (count($possibleSalary) - 1))];
        },
        'industry' => function() {
            $possibleIndustry = array("IT & Software Development", "Accounting", "Communication");
            return $possibleIndustry[rand(0, (count($possibleIndustry) -1))];
        }
    ];
});
