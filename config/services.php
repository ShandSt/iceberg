<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'sendgrid' => [
        'key' => env('SENDGRID_KEY'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'sms' => [
        'driver' => 'beeline',
        'drivers' => [
            'beeline' => \App\Service\Sms\Driver\BeelineDriver::class,
        ],
    ],
    'push' => [
        'os_to_app' => [
            'android' => 'android',
            'ios' => 'ios',
        ],
        'driver' => 'laravel_push_notification',
        'drivers' => [
            'laravel_push_notification' => [
                'service' => \App\Service\Push\Drivers\LaravelPushNotificationDriver\Service::class,
                'message' => \App\Service\Push\Drivers\LaravelPushNotificationDriver\Message::class,
            ],
            'log' => [
                'service' => \App\Service\Push\Drivers\LogDriver\Service::class,
                'message' => \App\Service\Push\Drivers\LogDriver\LogMessage::class,
            ],
        ],
    ],
    'billing' => [
        'driver' => 'sberbank',
        'drivers' => [
            'sberbank' => \App\Service\Billing\Drivers\BillingSberbankDriver::class,
            'log'      => \App\Service\Billing\Drivers\BillingLogDriver::class,
        ],
    ],
];
