<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PetCare+ learning backend',
        'hint' => 'Swagger UI: GET /api/documentation — docs: GET /docs/api — health: GET /api/v1/health',
    ]);
});

Route::get('/api/documentation', function () {
    return view('swagger');
});
