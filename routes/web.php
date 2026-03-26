<?php

use Src\Route;

Route::add('GET', '', [Controllers\Site::class, 'index'])
    ->middleware('auth');
Route::add('GET', 'admin', [Controllers\Site::class, 'admin'])
    ->middleware('auth', 'role:admin');
Route::add('GET', 'financist', [Controllers\Site::class, 'financist'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'login', [Controllers\Site::class, 'login']);
Route::add('GET', 'logout', [Controllers\Site::class, 'logout']);