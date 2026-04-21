<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Model\Postgraduate;
use Src\Validator\Validator;

class PostgraduateController
{
    public function addPostgraduate(Request $request): string
    {
        $user = app()->auth::user();
        $message = '';

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'surname' => ['required'],
            ], ['required' => 'Поле :field пусто']);

            if (!$validator->fails()) {
                Postgraduate::create([
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'patronymic' => $request->patronymic,
                    'supervisor_id' => $user->role_id == 1
                        ? ($request->supervisor_id ?? $user->supervisor_id)
                        : $user->supervisor_id
                ]);
                $message = 'Аспирант успешно добавлен!';
            } else {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            }
        }

        $supervisors = Staff::all();
        
        return new View('site.add_postgraduate', [
            'message' => $message,
            'supervisors' => $supervisors,
            'user' => $user
        ]);
    }

    public function postgraduates(Request $request): string
    {
        $user = app()->auth::user();
        $isAdmin = $user->role_id == 1;
        
        $supervisors = Staff::all();
        $searchSupervisorId = $request->get('supervisor_id') ?? '';
        
        $query = Postgraduate::with('supervisor');
        
        if (!empty($searchSupervisorId)) {
            $query->where('supervisor_id', $searchSupervisorId);
        }
        
        $postgraduates = $query->get();
        
        return new View('site.postgraduates', [
            'postgraduates' => $postgraduates,
            'isAdmin' => $isAdmin,
            'user' => $user,
            'supervisors' => $supervisors,
            'searchSupervisorId' => $searchSupervisorId
        ]);
    }

    public function editPostgraduate($id, Request $request): string
    {
        $user = app()->auth::user();
        $postgraduate = Postgraduate::find($id);
        
        if (!$postgraduate) {
            app()->route->redirect('/postgraduates');
        }
        
        if ($user->role_id != 1 && $postgraduate->supervisor_id != $user->supervisor_id) {
            app()->route->redirect('/postgraduates');
        }
        
        $message = '';
        $supervisors = Staff::all();
        
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'surname' => ['required'],
            ], ['required' => 'Поле :field пусто']);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $postgraduate->name = $request->name;
                $postgraduate->surname = $request->surname;
                $postgraduate->patronymic = $request->patronymic;
                
                if ($user->role_id == 1 && $request->supervisor_id) {
                    $postgraduate->supervisor_id = $request->supervisor_id;
                }
                
                if ($postgraduate->save()) {
                    $message = 'Аспирант успешно обновлён!';
                } else {
                    $message = 'Ошибка при обновлении аспиранта';
                }
            }
        }
        
        return new View('site.edit_postgraduate', [
            'message' => $message,
            'postgraduate' => $postgraduate,
            'supervisors' => $supervisors,
            'user' => $user
        ]);
    }

    public function deletePostgraduate($id): void
    {
        $user = app()->auth::user();
        $postgraduate = Postgraduate::find($id);
        
        if (!$postgraduate) {
            app()->route->redirect('/postgraduates');
        }
        
        if ($user->role_id != 1 && $postgraduate->supervisor_id != $user->supervisor_id) {
            app()->route->redirect('/postgraduates');
        }
        
        $postgraduate->delete();
        app()->route->redirect('/postgraduates');
    }
}