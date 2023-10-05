<?php

return [
    'api' => [
        'host' => env('HC_EMPLOYEE_URL', 'http://localhost:8081'),
        'client_id' => env('HC_EMPLOYEE_ID', 'superuser@reksa.co.id'),
        'client_secret' => env('HC_EMPLOYEE_SECRET', 'password')
    ]
];
