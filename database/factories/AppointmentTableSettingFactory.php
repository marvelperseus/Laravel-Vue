<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Eloquent\AppointmentTableSetting::class, function (Faker $faker) {
    return [
        'display_unit_number' => 4,
        'unit_names' => 'チェア1,チェア2,チェア3',
        'time_delimiter' => 60,
        'display_items' => 'name,age',
        'clinic_id' => 2
    ];
}, 'takahashi');

$factory->define(App\Models\Eloquent\AppointmentTableSetting::class, function (Faker $faker) {
    return [
        'display_unit_number' => 4,
        'unit_names' => 'チェア1,チェア2,チェア3',
        'time_delimiter' => 60,
        'display_items' => 'name,age',
        'clinic_id' => 3
    ];
}, 'nonomura');

$factory->define(App\Models\Eloquent\AppointmentTableSetting::class, function (Faker $faker) {
    return [
        'display_unit_number' => 4,
        'unit_names' => 'チェア1,チェア2,チェア3',
        'time_delimiter' => 60,
        'display_items' => 'name,age',
        'clinic_id' => 4
    ];
}, 'matsuoka');

$factory->define(App\Models\Eloquent\AppointmentTableSetting::class, function (Faker $faker) {
    return [
        'display_unit_number' => 4,
        'unit_names' => 'チェア1,チェア2,チェア3',
        'time_delimiter' => 60,
        'display_items' => 'name,age',
        'clinic_id' => 5
    ];
}, 'kanamori');
