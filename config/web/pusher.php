<?php

use Pusher\Pusher;

return [
    Pusher::class => [
        '__construct()' => [
            'auth_key' => $_ENV['PUSHER_APP_KEY'],
            'secret' => $_ENV['PUSHER_APP_SECRET'],
            'app_id' => $_ENV['PUSHER_APP_ID'],
            'options' => [
                'cluster' => $_ENV['PUSHER_APP_CLUSTER'],
                'useTLS' => true
            ]
        ]
    ]
];

