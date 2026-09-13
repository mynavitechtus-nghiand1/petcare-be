<?php

$originsEnv = env('CORS_ALLOWED_ORIGINS');

$allowedOrigins = ($originsEnv !== null && $originsEnv !== '')
    ? array_values(array_filter(array_map('trim', explode(',', $originsEnv))))
    : ['*'];

$corsEnabled = filter_var(env('API_CORS_ENABLED', false), FILTER_VALIDATE_BOOLEAN);

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | Chỉ áp dụng cho trình duyệt (Origin header). App native / server-to-server
    | không bị giới hạn bởi CORS.
    |
    | CORS_ALLOWED_ORIGINS: danh sách URL đầy đủ, cách nhau bởi dấu phẩy, ví dụ:
    |   https://stg.yellow-brick.jp,https://admin.stg.yellow-brick.jp
    | Để trống hoặc không set → mặc định * (chỉ nên dùng khi dev).
    |
    | Nếu bật CORS_SUPPORTS_CREDENTIALS=true thì không được dùng *; phải liệt kê
    | từng origin cụ thể (và thường kèm SANCTUM_STATEFUL_DOMAINS).
    |
    */

    'paths' => $corsEnabled ? ['api/*', 'sanctum/csrf-cookie'] : [],

    'allowed_methods' => ['*'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => (int) env('CORS_MAX_AGE', 0),

    'supports_credentials' => filter_var(env('CORS_SUPPORTS_CREDENTIALS', false), FILTER_VALIDATE_BOOLEAN),

];
