<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Eloquent\MailInbox::class, function (Faker $faker) {

    $data = array(
        'error' => [
            'type' => 'error',
            'body' => 'エラーメール
○○の理由により送信できませんでした。'
        ],

        'new' => [
            'type' => 'new',
            'body' => '予約が登録されました。
予約日時：2019-03-18
内容を確認してください。'
        ],

        'change' => [
            'type' => 'change',
            'body' => '予約が変更されました。
予約日時：2019-03-18　→　2019-04-01
内容を確認してください。'
        ],

        'cancel' => [
            'type' => 'cancel',
            'body' => '予約がキャンセルされました！
次回予約をお願いします。'
        ],

    );

    $target = $faker->randomElement(['error', 'new', 'change', 'cancel']);

    return [
        'type' => $data[$target]['type'],
        'body' => $data[$target]['body'],
        'name' => $faker->name,
        'email' => $faker->email,
        'clinic_id' => 1
    ];
});
