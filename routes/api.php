<?php

use Src\Route;

Route::add('GET', '', [Controllers\Api::class, 'index']);
Route::add('POST', '/echo', [Controllers\Api::class, 'echo']);
Route::add('POST', '/login', [Controllers\Api::class, 'login']);