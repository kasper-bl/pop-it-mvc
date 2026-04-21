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
                // Бизнес-логика вынесена в модель
                Postgraduate::createFromRequest($request, $user);
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
        $postgraduate = Postgraduate::find($id);
        $user = app()->auth::user();
        
        if (!$postgraduate) {
            app()->route->redirect('/postgraduates');
        }
        
        if (!$postgraduate->canEdit($user)) {
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
                if ($postgraduate->updateFromRequest($request, $user)) {
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
        $postgraduate = Postgraduate::find($id);
        $user = app()->auth::user();
        
        if (!$postgraduate) {
            app()->route->redirect('/postgraduates');
        }
        
        if (!$postgraduate->canEdit($user)) {
            app()->route->redirect('/postgraduates');
        }
        
        $postgraduate->delete();
        app()->route->redirect('/postgraduates');
    }
}