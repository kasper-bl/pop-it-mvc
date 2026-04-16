<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;

class AdminController
{
    // Проверка, является ли пользователь администратором
    private function checkAdmin(): void
    {
        $user = app()->auth::user();
        if ($user->id_role != 1) {
            app()->route->redirect('/dashboard');
        }
    }

    // Страница добавления сотрудника
    public function addUser(Request $request): string
    {
        $this->checkAdmin();
        
        $message = '';
        
        if ($request->method === 'POST') {
            $userData = [
                'login' => $request->login,
                'password' => $request->password,
                'name' => $request->name,
                'surname' => $request->surname,
                'patronymic' => $request->patronymic ?? '',
                'department' => $request->department ?? '',
                'id_role' => $request->id_role ?? 2
            ];
            
            if (Staff::create($userData)) {
                $message = 'Сотрудник успешно добавлен!';
            } else {
                $message = 'Ошибка при добавлении сотрудника';
            }
            }
        return new View('site.admin_add_user', [
            'message' => $message,
            'roles' => $this->getRoles()
        ]);
    }
    
    private function getRoles(): array
    {
        return [
            1 => 'Администратор',
            2 => 'Сотрудник научного отдела'
        ];
    }
}