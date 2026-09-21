<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PetCare+ learning backend',
        'hint' => 'Swagger UI: GET /api/documentation — docs: GET /docs/api — health: GET /api/v1/health',
    ]);
});

Route::get('/api/documentation', function () {
    $auth = request()->header('Authorization', '');
    $user = null;
    $pass = null;

    if (str_starts_with($auth, 'Basic ')) {
        [$user, $pass] = explode(':', base64_decode(substr($auth, 6)), 2);
    }

    $expectedUser = env('DOCS_USER', 'admin');
    $expectedPass = env('DOCS_PASSWORD', 'secret');

    if ($user !== $expectedUser || $pass !== $expectedPass) {
        return response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic realm="PetCare Docs"',
        ]);
    }

    return view('swagger');
});
