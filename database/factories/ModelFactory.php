<?php

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

// POSTGIS
use Phaza\LaravelPostgis\Geometries\Point;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
//        'remember_token' => str_random(10),
    ];
});

/**
 * Definições para o modelo Models\User
 */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'facebook_id' => '' . $faker->numberBetween(999999, 999999999),
        'phone' => $faker->numberBetween(20, 50) . '' . $faker->numberBetween(2000000, 99999999),
        'address' => $faker->address
    ];
});

/**
 * Definições para o modelo Models\Local
 */
$factory->define(App\Models\Local::class, function (Faker\Generator $faker) {

    $paraiba = [-7.1464333, -34.9518485];
    $acre = [-7.6258137,-74.039277];
    $sta_vit_palmar = [-33.6541545,-53.257789];
    $roraima = [4.7159533,-60.815794];

    $maxLat = 4.7159533;
    $minLat = -33.6541545;

    $minLng = -74.039277;
    $maxLng = -34.9518485;

    $lat = $faker->randomFloat(7, $minLat, $maxLat);
    $lng = $faker->randomFloat(7, $minLng, $maxLng);

    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'phone' => $faker->numberBetween(20, 50) . '' . $faker->numberBetween(2000000, 99999999),
        'amount' => random_int(0, 1) == 0 ? null : $faker->numberBetween(10, 2000),
        'appointment_duration_in_minutes' => $faker->numberBetween(0, 120),
        'location' => new Point($lat, $lng)
    ];
});
