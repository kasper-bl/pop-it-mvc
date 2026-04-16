<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

Route::add('GET', '/', [Controller\HomeController::class, 'index']);

Route::add('GET', '/dashboard', [Controller\DashboardController::class, 'index'])->middleware('auth');
Route::add('GET', '/dissertations', [Controller\DissertationController::class, 'index'])->middleware('auth');
Route::add('GET', '/publications', [Controller\PublicationController::class, 'index'])->middleware('auth');
Route::add(['GET', 'POST'], '/reports', [Controller\ReportController::class, 'index'])->middleware('auth');
Route::add(['GET', 'POST'], '/search', [Controller\SearchController::class, 'index'])->middleware('auth');