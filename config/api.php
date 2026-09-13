<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for PHP Laravel 12 Source Base API functionality
    |
    */

    'version' => env('API_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'authenticated' => env('API_RATE_LIMIT_AUTHENTICATED', 1000),
        'unauthenticated' => env('API_RATE_LIMIT_UNAUTHENTICATED', 100),
        'premium' => env('API_RATE_LIMIT_PREMIUM', 5000),
        'decay_minutes' => env('API_RATE_LIMIT_DECAY', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('API_LOGGING_ENABLED', true),
        'channel' => env('API_LOGGING_CHANNEL', 'api'),
        'slow_request_threshold' => env('API_SLOW_REQUEST_THRESHOLD', 1000), // milliseconds
        'log_request_body' => env('API_LOG_REQUEST_BODY', true),
        'log_response_body' => env('API_LOG_RESPONSE_BODY', false),
        'sanitize_sensitive_data' => env('API_SANITIZE_SENSITIVE_DATA', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Response Configuration  
    |--------------------------------------------------------------------------
    */
    'response' => [
        'include_timestamp' => env('API_INCLUDE_TIMESTAMP', true),
        'include_request_id' => env('API_INCLUDE_REQUEST_ID', true),
        'include_debug_info' => env('API_INCLUDE_DEBUG_INFO', false),
        'include_performance_metrics' => env('API_INCLUDE_PERFORMANCE_METRICS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    */
    'security' => [
        'enforce_https' => env('API_ENFORCE_HTTPS', false),
        'cors_enabled' => env('API_CORS_ENABLED', true),
        'require_api_key' => env('API_REQUIRE_API_KEY', false),
        'api_key_header' => env('API_KEY_HEADER', 'X-API-Key'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'enforce_json_content_type' => env('API_ENFORCE_JSON_CONTENT_TYPE', true),
        'require_accept_header' => env('API_REQUIRE_ACCEPT_HEADER', true),
        'supported_versions' => ['v1'], // Add more versions as needed
        'max_request_size' => env('API_MAX_REQUEST_SIZE', 10485760), // 10MB in bytes
    ],
];
