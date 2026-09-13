<?php

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     * 
     * This configuration is used for Firebase Cloud Messaging (FCM) push notifications only.
     * Credentials are stored in AWS Systems Manager Parameter Store and injected via
     * ECS Task Definition as environment variable FIREBASE_CREDENTIALS.
     * 
     * Parameter path: /hck/{env}/firebase/credentials (SecureString)
     */

    'projects' => [
        'app' => [
            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             * 
             * Firebase Service Account JSON credentials (as string or array).
             * Retrieved from AWS SSM Parameter Store via FIREBASE_CREDENTIALS env variable.
             * 
             * Format: JSON string containing service account credentials
             * Example: {"type":"service_account","project_id":"...","private_key":"..."}
             * 
             * The JSON string will be automatically converted to array for Firebase package.
             * If conversion fails, the original string will be used (Firebase can handle both).
             * 
             * Documentation: https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             */
            'credentials' => env('FIREBASE_CREDENTIALS', null),
        ],
    ],
];
