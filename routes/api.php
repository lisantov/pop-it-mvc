<?php

use Src\Route;

Route::add('GET', '', [Controllers\Api::class, 'index']);
Route::add('POST', '/echo', [Controllers\Api::class, 'echo']);
Route::add('POST', '/login', [Controllers\Api::class, 'login']);
Route::add('GET', '/financists', [Controllers\Api::class, 'getFinancists'])
->middleware('auth', 'role:admin');
Route::add('DELETE', '/financists', [Controllers\Api::class, 'deleteFinancist'])
->middleware('auth', 'role:admin');
Route::add('PATCH', '/financists', [Controllers\Api::class, 'editFinancist'])
    ->middleware('auth', 'role:admin');
Route::add('POST', '/financists', [Controllers\Api::class, 'addFinancist'])
    ->middleware('auth', 'role:admin');