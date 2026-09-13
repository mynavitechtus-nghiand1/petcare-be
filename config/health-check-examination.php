<?php
// config/health-check-examination.php

return [
    // File configuration is now in global config/file.php

    // OCR Configuration
    'ocr' => [
        // OCR API Endpoint
        'api_url' => env('OCR_API_URL', 'https://st.cloud.ocr.irrc.co.jp'),
        
        // OCR API Authentication
        'auth' => [
            'service_name' => env('OCR_AUTH_SERVICE_NAME'),
            'service_key' => env('OCR_AUTH_SERVICE_KEY'),
            'encryption' => [
                'cipher' => 'aes-128-cbc',
                'key' => env('OCR_ENCRYPTION_KEY'),
                'iv' => env('OCR_ENCRYPTION_IV'),
            ],
        ],
        
        // OCR API Request Settings
        'response_mode' => env('OCR_RESPONSE_MODE', '2'), // 1 = immediate, 2 = polling
        'timeout' => 60, // 1 minute per individual file upload
        'polling_interval' => 5, // 5 seconds between polling requests
        'polling_timeout' => 300, // 5 minutes total polling timeout
        'retry_attempts' => 3,
        'retry_delay' => 5, // seconds
        
        // Queue Configuration
        'queue_name' => env('OCR_QUEUE_NAME', 'ocr-processing'),
        'max_processing_time' => 600, // 10 minutes per job
        
        // File Processing Settings
        'individual_file_upload' => true, // Upload 1 file per request
        'max_files_per_job' => 15, // Maximum files per OCR job
        
        // Data Extraction Settings
        'confidence_threshold' => 0.8, // Minimum confidence for extraction
        'extract_indicators' => 62, // Number of health indicators to extract
    ],
];

