<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(PatientsEvaluations::class, function (Faker $faker) {
    return [
        'evaluations_id' => $faker->numberBetween(1, 3),
        'guid' => Str::uuid()->toString(),
        'reference' => $faker->name,
        'gender' => $faker->randomElement(['MALE', 'FEMALE']),
        'answered' => $faker->boolean(),
    ];
});
