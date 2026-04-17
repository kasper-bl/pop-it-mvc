<?php

use Src\Route;

// Аутентификация
Route::add('GET', '/hello', [Controller\Site::class, 'hello'])->middleware('auth');
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

// Главная
Route::add('GET', '/', [Controller\Site::class, 'home']);

// Дашборд
Route::add('GET', '/dashboard', [Controller\Site::class, 'dashboard'])->middleware('auth');

Route::add(['GET', 'POST'], '/admin/users/add', [Controller\Site::class, 'addUser'])->middleware('auth');

// Диссертации
Route::add('GET', '/dissertations', [Controller\Site::class, 'dissertations'])->middleware('auth');

// Публикации
Route::add('GET', '/publications', [Controller\Site::class, 'publications'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-publication', [Controller\Site::class, 'addPublication'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-publication/{id}', [Controller\Site::class, 'editPublication'])->middleware('auth');
Route::add('GET', '/delete-publication/{id}', [Controller\Site::class, 'deletePublication'])->middleware('auth');

// Отчёты (только сотрудник)
Route::add(['GET', 'POST'], '/reports', [Controller\Site::class, 'reports'])->middleware('auth');

// Поиск (только сотрудник)
Route::add(['GET', 'POST'], '/search', [Controller\Site::class, 'search'])->middleware('auth');