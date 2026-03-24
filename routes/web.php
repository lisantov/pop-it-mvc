<?php

use Src\Route;

Route::add('', [Controllers\Site::class, 'index']);
Route::add('hello', [Controllers\Site::class, 'hello']);
Route::add('signup', [Controllers\Site::class, 'signup']);
Route::add('login', [Controllers\Site::class, 'login']);
Route::add('logout', [Controllers\Site::class, 'logout']);