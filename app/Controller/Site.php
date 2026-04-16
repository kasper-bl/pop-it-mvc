<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Model\Edition;
use Model\IndexType;
use Model\Publication;
use Src\Validator\Validator;

class Site
{
    
    public function hello(): string
    {
        return new View('site.hello', ['message' => 'Научный отдел']);
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (app()->auth::attempt($request->all())) {
            app()->route->redirect('/dashboard');
        }
        return new View('site.login', ['message' => 'Ошибка входа']);
    }

    public function logout(): void
    {
        app()->auth::logout();
        app()->route->redirect('/login');
    }
    
    public function home(): void
    {
        if (app()->auth::check()) {
            app()->route->redirect('/dashboard');
        }
        app()->route->redirect('/login');
    }
        
    public function dashboard(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        return new View('site.dashboard', [
            'isAdmin' => $isAdmin,
            'user' => $user
        ]);
    }

    private function checkAdmin(): void
    {
        $user = app()->auth::user();
        if ($user->role_id != 1) {
            app()->route->redirect('/dashboard');
        }
    }

    public function addUser(Request $request): string
    {
        $this->checkAdmin();
        
        $message = '';
        
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'login' => ['required', 'unique:staff,login'],
                'password' => ['required'],
                'name' => ['required'],
                'surname' => ['required']
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Поле :field должно быть уникально'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $userData = [
                    'login' => $request->login,
                    'password' => $request->password,
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'patronymic' => $request->patronymic ?? '',
                    'department' => $request->department ?? '',
                    'role_id' => $request->role_id ?? 2
                ];
                
                if (Staff::create($userData)) {
                    $message = 'Сотрудник успешно добавлен!';
                } else {
                    $message = 'Ошибка при добавлении сотрудника';
                }
            }
        }
        
        return new View('site.admin_add_user', [
            'message' => $message,
            'roles' => [1 => 'Администратор', 2 => 'Сотрудник научного отдела']
        ]);
    }
     
    public function dissertations(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        return new View('site.dissertations', [
            'isAdmin' => $isAdmin
        ]);
    }
    
    public function reports(): string
    {
        $user = app()->auth::user();
        
        if ($user->role_id == 1) {
            app()->route->redirect('/dashboard');
        }
        
        return new View('site.reports');
    }
    
    
    public function search(): string
    {
        $user = app()->auth::user();
        
        // Только для сотрудников
        if ($user->role_id == 1) {
            app()->route->redirect('/dashboard');
        }
        
        return new View('site.search');
    }

    // ========== ПУБЛИКАЦИИ ==========

    public function addPublication(Request $request): string
    {
        $message = '';
        
        // Получаем списки для выпадающих меню
        $staff = Staff::all();  // сотрудники = научные руководители
        $editions = Edition::all();
        $indexTypes = IndexType::all();
        
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'title' => ['required'],
                'publication_date' => ['required'],
                'staff_id' => ['required'],
                'edition_id' => ['required'],
                'index_type_id' => ['required']
            ], [
                'required' => 'Поле :field пусто'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $publicationData = [
                    'title' => $request->title,
                    'publication_date' => $request->publication_date,
                    'staff_id' => $request->staff_id,
                    'edition_id' => $request->edition_id,
                    'index_type_id' => $request->index_type_id
                ];
                
                if (Publication::create($publicationData)) {
                    $message = 'Публикация успешно добавлена!';
                } else {
                    $message = 'Ошибка при добавлении публикации';
                }
            }
        }
        
        return new View('site.add_publication', [
            'message' => $message,
            'staff' => $staff,
            'editions' => $editions,
            'indexTypes' => $indexTypes
        ]);
    }

    public function publications(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        $publications = Publication::with(['staff', 'edition', 'indexType'])->get();
        
        return new View('site.publications', [
            'isAdmin' => $isAdmin,
            'publications' => $publications
        ]);
    }
}