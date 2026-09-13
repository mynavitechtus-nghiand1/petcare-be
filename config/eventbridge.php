<?php

return [
    /*
     * ------------------------------------------------------------------------
     * EventBridge Rule Prefix
     * ------------------------------------------------------------------------
     *
     * Prefix for EventBridge rule names. Format: [project_code]-[environment]
     * Example: hck-dev, hck-staging, hck-prod
     * Note: A dash will be automatically appended if not present.
     *
     * This value is required and must be set in your .env file.
     *
     */

    'rule_prefix' => env('EVENTBRIDGE_RULE_PREFIX', "hck-dev"),

    /*
     * ------------------------------------------------------------------------
     * AWS Configuration
     * ------------------------------------------------------------------------
     *
     * AWS credentials and endpoint configuration for EventBridge.
     * For LocalStack (local development), set AWS_ENDPOINT_URL.
     * For production, set AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY.
     *
     */

    'aws' => [
        /*
         * AWS Region
         * Default: us-east-1
         */
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),

        /*
         * AWS Endpoint URL (for LocalStack or custom endpoints)
         * Set this to use LocalStack for local development
         * Example: http://localhost:4566
         */
        'endpoint_url' => env('AWS_ENDPOINT_URL', null),

        /*
         * AWS Credentials
         * For production: Set AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY
         * For LocalStack: Can use dummy credentials (test/test) if endpoint_url is set
         */
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID', null),
            'secret' => env('AWS_SECRET_ACCESS_KEY', null),
            'token' => env('AWS_SESSION_TOKEN', null),
        ],
    ],

    /*
     * ------------------------------------------------------------------------
     * SQS Queue Configuration
     * ------------------------------------------------------------------------
     *
     * SQS queue configuration for EventBridge targets.
     *
     */

    'sqs' => [
        /*
         * AWS Account ID
         * For LocalStack: 000000000000
         * For production: Your AWS account ID
         */
        'account_id' => env('AWS_ACCOUNT_ID', null),

        /*
         * Queue name
         * Default: Uses queue.connections.sqs.queue config
         */
        'queue_name' => env('SQS_QUEUE', 'default'),
    ],

    /*
     * ------------------------------------------------------------------------
     * EventBridge IAM Role Configuration
     * ------------------------------------------------------------------------
     *
     * IAM Role ARN for EventBridge to send messages to targets (e.g., SQS).
     * This role must have permissions to send messages to the target service.
     * Format: arn:aws:iam::{account-id}:role/{role-name}
     *
     */
    'role_arn' => env('EVENTBRIDGE_ROLE_ARN', null),
];
