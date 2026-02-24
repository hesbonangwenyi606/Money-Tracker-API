<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app'     => 'Money Tracker API',
        'version' => '1.0.0',
        'docs'    => 'See README.md for API endpoint documentation.',
    ]);
});
