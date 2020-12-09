<?php

return [
    'ios' => [
        'environment' => 'production',
        'certificate' => storage_path('certificates/push_prod.pem'),
        'passPhrase' => '',
        'service' => 'apns'
    ],
    'android' => [
        'environment' => 'production',
        'apiKey' => 'AAAAVtmb1mo:APA91bHNrIIvNnjDCvTXVfiCQfxmLNddElhNbFT_d_6bnnR-J4qfj1svdD3f9UZ-qameMyffkuB7lxm-EivDg96Rh347u43mRO62_8tvWUhAjRT7tvXLXOL9HX7jXYuFN1hKH1ya01G382U-j1yC0gkjxbO0IJKeXw',
        'service' => 'gcm'
    ]
];
