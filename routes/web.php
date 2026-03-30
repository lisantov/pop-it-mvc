<?php

use Src\Route;

Route::add('GET', '', [Controllers\Site::class, 'index'])
    ->middleware('auth');

Route::add(['GET', 'POST'], 'admin', [Controllers\Site::class, 'admin'])
    ->middleware('auth', 'role:admin');
Route::add(['GET', 'POST'], 'admin/delete', [Controllers\Site::class, 'deleteFinancist'])
    ->middleware('auth', 'role:admin');
Route::add(['GET', 'POST'], 'admin/edit', [Controllers\Site::class, 'editFinancist'])
    ->middleware('auth', 'role:admin');

Route::add('GET', 'financist', [Controllers\Site::class, 'financist'])
    ->middleware('auth', 'role:financist');
Route::add('GET', 'financist/stats', [Controllers\Site::class, 'financistStats'])
    ->middleware('auth', 'role:financist');
Route::add('GET', 'financist/departments', [Controllers\Site::class, 'financistDepartments'])
    ->middleware('auth', 'role:financist');

Route::add('GET', 'financist/accruals', [Controllers\Site::class, 'financistAccruals'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/accruals/add', [Controllers\Site::class, 'addAccrual'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/accruals/delete', [Controllers\Site::class, 'deleteAccrual'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/accruals/edit', [Controllers\Site::class, 'editAccrual'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/accruals/upload', [Controllers\Site::class, 'uploadAccrualFile'])
    ->middleware('auth', 'role:financist');

Route::add('GET', 'financist/deductions', [Controllers\Site::class, 'financistDeductions'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/deductions/add', [Controllers\Site::class, 'addDeduction'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/deductions/delete', [Controllers\Site::class, 'deleteDeduction'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/deductions/edit', [Controllers\Site::class, 'editDeduction'])
    ->middleware('auth', 'role:financist');
Route::add(['GET', 'POST'], 'financist/deductions/upload', [Controllers\Site::class, 'uploadDeductionFile'])
    ->middleware('auth', 'role:financist');

Route::add(['GET', 'POST'], 'login', [Controllers\Site::class, 'login']);
Route::add('GET', 'logout', [Controllers\Site::class, 'logout']);