<?php

use Src\Route;

Route::add('GET', 'hello', [Controllers\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], 'signup', [Controllers\Site::class, 'signup']);
Route::add(['GET', 'POST'], 'login', [Controllers\Site::class, 'login']);
Route::add('GET', 'logout', [Controllers\Site::class, 'logout']);