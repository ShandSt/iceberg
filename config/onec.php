<?php

return [
    'endpoint' => env('ONE_C_ENDPOINT', false),
    'ping_endpoint' => sprintf("%s/ping",
            env('ONE_C_ENDPOINT', false)
        ),
];