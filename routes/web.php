<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PetCare+ learning backend',
        'hint' => 'Swagger UI: GET /api/documentation — docs: GET /docs/api — health: GET /api/v1/health',
    ]);
});

Route::get('/api/documentation', function () {
    $user = request()->getUser();
    $pass = request()->getPassword();

    $expectedUser = config('app.docs_user', 'admin');
    $expectedPass = config('app.docs_password', 'secret');

    if ($user !== $expectedUser || $pass !== $expectedPass) {
        return response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic realm="PetCare Docs"',
        ]);
    }

    return view('swagger');
});
