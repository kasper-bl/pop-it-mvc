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
Route::add('GET', '/admin/users/delete/{id}', [Controller\Site::class, 'deleteUser'])->middleware('auth');

// Аспиранты
Route::add(['GET', 'POST'], '/postgraduates', [Controller\Site::class, 'postgraduates'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-postgraduate', [Controller\Site::class, 'addPostgraduate'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-postgraduate/{id}', [Controller\Site::class, 'editPostgraduate'])->middleware('auth');
Route::add('GET', '/delete-postgraduate/{id}', [Controller\Site::class, 'deletePostgraduate'])->middleware('auth');

// Диссертации
Route::add(['GET', 'POST'], '/dissertations', [Controller\Site::class, 'dissertations'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-dissertation', [Controller\Site::class, 'addDissertation'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-dissertation/{id}', [Controller\Site::class, 'editDissertation'])->middleware('auth');
Route::add('GET', '/delete-dissertation/{id}', [Controller\Site::class, 'deleteDissertation'])->middleware('auth');

// Публикации
Route::add('GET', '/publications', [Controller\Site::class, 'publications'])->middleware('auth');
Route::add(['GET', 'POST'], '/add-publication', [Controller\Site::class, 'addPublication'])->middleware('auth');
Route::add(['GET', 'POST'], '/edit-publication/{id}', [Controller\Site::class, 'editPublication'])->middleware('auth');
Route::add('GET', '/delete-publication/{id}', [Controller\Site::class, 'deletePublication'])->middleware('auth');

// Отчёты 
Route::add(['GET', 'POST'], '/reports', [Controller\Site::class, 'reports'])->middleware('auth');

// Поиск
Route::add(['GET', 'POST'], '/search', [Controller\Site::class, 'search'])->middleware('auth');