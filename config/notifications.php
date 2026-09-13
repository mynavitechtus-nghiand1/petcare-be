<?php

return [
    'channels' => [
        'mail' => [
            'driver' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'noreply@techtus.com'),
                'name' => env('MAIL_FROM_NAME', message('auth', 'brand_display_name')),
            ],
        ],
        'sns' => [
            'driver' => 'sns',
            'region' => env('AWS_DEFAULT_REGION', 'us-east-2'), // Updated to match topic ARN region
            'sender_id' => env('AWS_SNS_SENDER_ID', 'YB-OTP'),
            'role_arn' => env('AWS_SNS_ROLE_ARN'), // For ECS Fargate assume role
            'session_name' => env('AWS_SNS_SESSION_NAME', 'laravel-notifications'),
            'topic_arn' => env('SNS_SMS_TOPIC_ARN'), // SNS Topic ARN for SMS
            'default_country' => env('AWS_SNS_DEFAULT_COUNTRY', 'VN'), // Vietnam, JP for Japan
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'notifications',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Push Notification Configuration
    |--------------------------------------------------------------------------
    */
    'push' => [
        'enabled' => true, // Always enabled, check via FIREBASE_CREDENTIALS in code
        'timezone' => env('NOTIFICATION_TIMEZONE', 'Asia/Tokyo'), // JST
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Scheduled Scanner Configuration
    |--------------------------------------------------------------------------
    */
    'scanner' => [
        'batch_size' => env('SCANNER_BATCH_SIZE', 100),
    ],
    
    'rate_limiting' => [
        'otp' => [
            'attempts' => 5,
            'window' => 300, // 5 minutes
        ],
        'general' => [
            'attempts' => 10,
            'window' => 60, // 1 minute
        ],
    ],
    
    'templates' => [
        'otp' => [
            'subject' => message('notifications', 'otp.subject'),
            'greeting' => message('notifications', 'otp.greeting'),
            'expiration' => 10, // minutes
        ],
        'registration' => [
            'subject' => message('notifications', 'registration.subject'),
            'greeting' => message('notifications', 'registration.greeting'),
            'expiration' => 10,
        ],
        'password_reset' => [
            'subject' => message('notifications', 'password_reset.subject'),
            'greeting' => message('notifications', 'password_reset.greeting'),
            'expiration' => 10,
        ],
        'login' => [
            'subject' => message('notifications', 'login.subject'),
            'greeting' => message('notifications', 'login.greeting'),
            'expiration' => 10,
        ],
    ],
    
    'queue' => [
        'connection' => env('QUEUE_CONNECTION', 'sync'),
        'queue' => env('NOTIFICATION_QUEUE', 'notifications'),
        'retry_after' => 90,
        'tries' => 3,
    ],
    
    'security' => [
        'mask_otp_in_logs' => true,
        'log_failed_attempts' => true,
        'max_attempts_per_hour' => 10,
        'lockout_duration' => 3600, // 1 hour
    ],
];
