<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => Str::uuid()->toString(),
        'type' => 'App\\Notifications\\ExampleNotification',
        'notifiable_type' => 'App\\User',
        'notifiable_id' => factory(User::class)->create(),
        'data' => [
            'link' => url('/'),
            'message' => 'Mensaje de la notificación'
        ],
        'read_at' => null,

    ];
});
