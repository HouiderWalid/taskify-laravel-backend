<?php

require __DIR__ . '/api/authentication.php';
require __DIR__ . '/api/project.php';
require __DIR__ . '/api/task.php';
require __DIR__ . '/api/overview.php';

Route::any('/{any}', function (Request $request) {
    $uri = request()->fullUrl();
    return response()->json(['code' => 404, 'data' => [], 'messages' => "$uri path is not found."]);
})->where('any', '.*');