<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Src\Validator\Validator;

class UserController
{
    public function addUser(Request $request): string
    {

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

    public function deleteUser($id): void
    {
        $user = Staff::find($id);
        
        if (!$user) {
            app()->route->redirect('/dashboard');
        }
        
        if ($user->supervisor_id == app()->auth::user()->supervisor_id) {
            app()->route->redirect('/dashboard');
        }
        
        $user->delete();
        app()->route->redirect('/dashboard');
    }
}