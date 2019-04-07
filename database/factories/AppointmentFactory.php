<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Eloquent\Appointment::class, function (Faker $faker) {

    $hour = $faker->numberBetween(10, 19);
    $start = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d ' . $hour . ':00:00');

    $end1 = strtotime($start . '+30 minute');
    $end2 = strtotime($start . '+60 minute');

    return [
        'start' => $start,
        'end' => $faker->randomElement([date('Y-m-d H:i:s', $end1), date('Y-m-d H:i:s', $end2)]),
        'patient_id' => $faker->randomElement([102, 117, 123, 131]),
        'treatment_id' => 3,
        'unit' => $faker->numberBetween(0, 2),
        'way' => $faker->randomElement(['web', 'app', 'tel']),
        'clinic_id' => 2
    ];
}, 'takahashi');


$factory->define(App\Models\Eloquent\Appointment::class, function (Faker $faker) {

    $hour = $faker->numberBetween(10, 19);
    $start = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d ' . $hour . ':00:00');

    $end1 = strtotime($start . '+30 minute');
    $end2 = strtotime($start . '+60 minute');

    return [
        'start' => $start,
        'end' => $faker->randomElement([date('Y-m-d H:i:s', $end1), date('Y-m-d H:i:s', $end2)]),
        'patient_id' => $faker->randomElement([108, 120, 130, 156]),
        'treatment_id' => 4,
        'unit' => $faker->numberBetween(0, 2),
        'way' => $faker->randomElement(['web', 'app', 'tel']),
        'clinic_id' => 3
    ];
}, 'nonomura');


$factory->define(App\Models\Eloquent\Appointment::class, function (Faker $faker) {

    $hour = $faker->numberBetween(10, 19);
    $start = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d ' . $hour . ':00:00');

    $end1 = strtotime($start . '+30 minute');
    $end2 = strtotime($start . '+60 minute');

    return [
        'start' => $start,
        'end' => $faker->randomElement([date('Y-m-d H:i:s', $end1), date('Y-m-d H:i:s', $end2)]),
        'patient_id' => $faker->randomElement([111, 119, 124, 128]),
        'treatment_id' => 5,
        'unit' => $faker->numberBetween(0, 2),
        'way' => $faker->randomElement(['web', 'app', 'tel']),
        'clinic_id' => 4
    ];
}, 'matsuoka');


$factory->define(App\Models\Eloquent\Appointment::class, function (Faker $faker) {

    $hour = $faker->numberBetween(10, 19);
    $start = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d ' . $hour . ':00:00');

    $end1 = strtotime($start . '+30 minute');
    $end2 = strtotime($start . '+60 minute');

    return [
        'start' => $start,
        'end' => $faker->randomElement([date('Y-m-d H:i:s', $end1), date('Y-m-d H:i:s', $end2)]),
        'patient_id' => $faker->randomElement([104, 106, 107, 113]),
        'treatment_id' => 6,
        'unit' => $faker->numberBetween(0, 2),
        'way' => $faker->randomElement(['web', 'app', 'tel']),
        'clinic_id' => 5
    ];
}, 'kanamori');
