<?php

return [
    'user_agent' => env('ASK_USER_AGENT', config('app.name')),
    'audit_log_force_delete_unattached' => env('ASK_AUDIT_LOG_FORCE_DELETE_UNATTACHED', false),
    'get_health_check_exception' => [
        'message' => 'Health check is not available.',
        'code' => 490,
    ],
    'get_new_api_keys_exception' => [
        'message' => 'Generate new API keys is not available.',
        'code' => 491,
    ],
    'http_methods' => [
        'head' => 1,
        'get' => 2,
        'post' => 3,
        'put' => 4,
        'delete' => 5,
    ],
];
