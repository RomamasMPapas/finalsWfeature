<?php
return [
    // Path to the service‑account JSON you downloaded from Firebase.
    'service_account' => env('FIREBASE_SERVICE_ACCOUNT', base_path('firebase_service_account.json')),

    // Optional project ID – can be read from the JSON if omitted.
    'database' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],
];
