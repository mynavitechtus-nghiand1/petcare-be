<?php

return [
    'name' => 'Core',
    
    /*
    |--------------------------------------------------------------------------
    | Core Module Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for the Core module which
    | provides foundation classes and API infrastructure for the application.
    |
    */
    
    'api' => [
        /*
        |--------------------------------------------------------------------------
        | API Response Configuration
        |--------------------------------------------------------------------------
        */
        'response' => [
            'include_timestamp' => true,
            'include_debug_in_environments' => ['local', 'testing'],
            'paginate_limit' => 15,
            'max_paginate_limit' => 100,
            'use_problem_details' => env('API_USE_PROBLEM_DETAILS', false), // NEW: Feature flag
            'problem_details' => [                                           // NEW SECTION
                'base_uri' => env('PROBLEM_DETAILS_BASE_URI', null),
                'include_trace' => env('PROBLEM_DETAILS_INCLUDE_TRACE', false),
                'include_suggestions' => env('PROBLEM_DETAILS_INCLUDE_SUGGESTIONS', true),
            ],
        ],
        
        /*
        |--------------------------------------------------------------------------
        | Exception Handling Configuration
        |--------------------------------------------------------------------------
        */
        'exceptions' => [
            'log_level_mapping' => [
                'critical' => 'error',
                'high' => 'error', 
                'medium' => 'warning',
                'low' => 'info',
            ],
            'include_context_in_environments' => ['local', 'testing'],
            'redact_sensitive_fields' => ['password', 'password_confirmation', 'token', 'api_key', 'secret'],
        ],
        
        /*
        |--------------------------------------------------------------------------
        | STEP 3: API Middleware Configuration
        |--------------------------------------------------------------------------
        | Added for Global Exception Handler & Middleware Integration
        */
        
        'rate_limit' => [
            'authenticated' => env('API_RATE_LIMIT_AUTHENTICATED', 1000),
            'unauthenticated' => env('API_RATE_LIMIT_UNAUTHENTICATED', 100),
        ],
        
        'logging' => [
            'enabled' => env('API_LOGGING_ENABLED', true),
            'slow_request_threshold' => env('API_SLOW_REQUEST_THRESHOLD', 1000),
            'log_request_body' => env('API_LOG_REQUEST_BODY', true),
            'log_response_body' => env('API_LOG_RESPONSE_BODY', false),
        ],
        
        'validation' => [
            'enforce_json' => env('API_ENFORCE_JSON', true),
            'require_accept_header' => env('API_REQUIRE_ACCEPT_HEADER', true),
            'supported_versions' => ['v1'],
        ],
        
        'security' => [
            'require_auth_header' => env('API_REQUIRE_AUTH_HEADER', true),
            'min_token_length' => env('API_MIN_TOKEN_LENGTH', 40),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Validation Configuration
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'stop_on_first_failure' => false,
        'custom_messages' => [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'min' => [
                'string' => 'The :attribute must be at least :min characters.',
                'numeric' => 'The :attribute must be at least :min.',
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Base Model Configuration
    |--------------------------------------------------------------------------
    */
    'models' => [
        'use_uuid' => false,
        'date_format' => 'Y-m-d H:i:s',
        'recent_threshold_hours' => 24,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Flexible Logging Configuration
    |--------------------------------------------------------------------------
    | Support for multiple logging channels: laravel|sentry|slack|syslog|multi|none
    */
    'logging' => [
        // Primary logging driver
        'error_driver' => env('ERROR_LOGGING_DRIVER', 'laravel'), // laravel|sentry|slack|syslog|multi|none
        
        // Laravel channel configuration
        'error_channel' => env('ERROR_LOGGING_CHANNEL', 'single'),
        'validation_channel' => env('VALIDATION_LOGGING_CHANNEL', 'single'),
        
        // Multi-channel configuration
        'multi_channels' => explode(',', env('ERROR_LOGGING_MULTI_CHANNELS', 'laravel,sentry')),
        
        // Sentry configuration
        'sentry' => [
            'enabled' => env('SENTRY_LARAVEL_DSN') !== null,
            'environment' => env('SENTRY_ENVIRONMENT', 'production'),
            'sample_rate' => env('SENTRY_SAMPLE_RATE', 1.0),
        ],
        
        // Slack configuration
        'slack' => [
            'webhook_url' => env('SLACK_ERROR_WEBHOOK_URL'),
            'channel' => env('SLACK_ERROR_CHANNEL', '#errors'),
            'username' => env('SLACK_ERROR_USERNAME', 'Laravel Bot'),
            'emoji' => env('SLACK_ERROR_EMOJI', ':warning:'),
        ],
        
        // Syslog configuration
        'syslog' => [
            'facility' => env('SYSLOG_FACILITY', LOG_USER),
            'flags' => env('SYSLOG_FLAGS', LOG_PID),
        ],
    ],
];
