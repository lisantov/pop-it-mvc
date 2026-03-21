<?php
use Src\Route;
Route::add('', [Controllers\Site::class, 'index']);
Route::add('hello', [Controllers\Site::class, 'hello']);