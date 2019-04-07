<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Eloquent\Patient::class, function (Faker $faker) {
    return [
        'karte_number' => 'C-' . $faker->numberBetween(100, 500),
        'name' => $faker->name,
        'kana_name' => $faker->kanaName,
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->dateTimeBetween('-60 years', '-10 years')->format('Y-m-d'),
        'contact_way' => $faker->randomElement([["tel", "email"], ["tel"], ["email"]]),
        'tel' => $faker->phoneNumber,
        'email' => $faker->email,
        'status' => $faker->randomElement(['first', 'before', 'treatment', 'suspend', 'complete']),
        'zip' => $faker->postcode,
        'address' => substr($faker->address(), 9),
        'clinic_id' => $faker->numberBetween(2, 5)
    ];
});
