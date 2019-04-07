<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => 'ちよだ歯科医院',
        'director' => 'ちよだ',
        'tel' => '01-1234-7869',
        'zip' => '112-3456',
        'address' => '東京都杉並区杉並5-5',
        'url' => 'https://www.chiyoda.com',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 4,
        'user_id' => 6
    ];
}, 'chiyoda');

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => 'ValueLink矯正歯科',
        'director' => 'Value Link',
        'tel' => '03-6868-8448',
        'zip' => '101-0044',
        'address' => '東京都千代田区鍛冶町1-10-6 BIZ SMART 4F',
        'url' => 'https://www.valuelink.jp',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 3,
        'user_id' => 1
    ];
}, 'valuelink');

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => '高橋矯正歯科',
        'director' => '高橋信也',
        'tel' => $faker->phoneNumber,
        'zip' => $faker->postcode,
        'address' => substr($faker->address(), 9),
        'url' => 'https://takahashi-ortho.com',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 3,
        'user_id' => 2
    ];
}, 'takahashi');

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => '野々村デンタルクリニック',
        'director' => '野々村太郎',
        'tel' => $faker->phoneNumber,
        'zip' => $faker->postcode,
        'address' => substr($faker->address(), 9),
        'url' => 'https://www.nonomura-dental.com',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 4,
        'user_id' => 3
    ];
}, 'nonomura');

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => '松岡矯正ステーション',
        'director' => '松岡YUKI',
        'tel' => $faker->phoneNumber,
        'zip' => $faker->postcode,
        'address' => substr($faker->address(), 9),
        'url' => 'https://www.matsuoka-orthodontic-station.com',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 3,
        'user_id' => 4
    ];
}, 'matsuoka');

$factory->define(App\Models\Eloquent\Clinic::class, function (Faker $faker) {
    return [
        'name' => '金森歯科',
        'director' => '金森孝徳',
        'tel' => $faker->phoneNumber,
        'zip' => $faker->postcode,
        'address' => substr($faker->address(), 9),
        'url' => 'https://www.kanamori-dental.com',
        'app_key' => md5(uniqid(rand())),
        'unit_number' => 3,
        'user_id' => 5
    ];
}, 'kanamori');
