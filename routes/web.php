<?php

use Src\Route;

// Аутентификация
Route::add('GET', '/hello', [Controller\AuthController::class, 'hello'])->middleware('auth');
Route::add(['GET', 'POST'], '/login', [Controller\AuthController::class, 'login']);
Route::add('GET', '/logout', [Controller\AuthController::class, 'logout']);

// Главная и дашборд
Route::add('GET', '/', [Controller\DashboardController::class, 'home']);
Route::add('GET', '/dashboard', [Controller\DashboardController::class, 'dashboard'])->middleware('auth');

// Пользователи (сотрудники)
Route::add(['GET', 'POST'], '/admin/users/add', [Controller\UserController::class, 'addUser'])->middleware('auth');
Route::add('GET', '/admin/users/delete/{id}', [Controller\UserController::class, 'deleteUser'])->middleware('auth');

// Аспиранты
Route::add(['GET', 'POST'], '/postgraduates', [Controller\PostgraduateController::class, 'postgraduates'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-postgraduate', [Controller\PostgraduateController::class, 'addPostgraduate'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-postgraduate/{id}', [Controller\PostgraduateController::class, 'editPostgraduate'])->middleware('auth');
Route::add('GET', '/delete-postgraduate/{id}', [Controller\PostgraduateController::class, 'deletePostgraduate'])->middleware('auth');

// Диссертации
Route::add(['GET', 'POST'], '/dissertations', [Controller\DissertationController::class, 'dissertations'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-dissertation', [Controller\DissertationController::class, 'addDissertation'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-dissertation/{id}', [Controller\DissertationController::class, 'editDissertation'])->middleware('auth');
Route::add('GET', '/delete-dissertation/{id}', [Controller\DissertationController::class, 'deleteDissertation'])->middleware('auth');

// Публикации
Route::add('GET', '/publications', [Controller\PublicationController::class, 'publications'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-publication', [Controller\PublicationController::class, 'addPublication'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-publication/{id}', [Controller\PublicationController::class, 'editPublication'])->middleware('auth');
Route::add('GET', '/delete-publication/{id}', [Controller\PublicationController::class, 'deletePublication'])->middleware('auth');

// Отчёты и поиск
Route::add(['GET', 'POST'], '/reports', [Controller\ReportController::class, 'reports'])->middleware('auth');
Route::add(['GET', 'POST'], '/search', [Controller\ReportController::class, 'search'])->middleware('auth');