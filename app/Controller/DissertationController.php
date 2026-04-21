<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Postgraduate;
use Model\Dissertation;
use Model\DissertationStatus;
use Src\Validator\Validator;

class DissertationController
{
    public function dissertations(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        $dissertations = Dissertation::with(['postgraduate', 'status'])->get();
        
        return new View('site.dissertations', [
            'isAdmin' => $isAdmin,
            'user' => $user,
            'dissertations' => $dissertations
        ]);
    }

    public function addDissertation(Request $request): string
    {
        $message = '';
        $user = app()->auth::user();
        $isAdmin = $user->role_id == 1;

        if ($isAdmin) {
            $postgraduates = Postgraduate::with('supervisor')->get();
        } else {
            $postgraduates = Postgraduate::where('supervisor_id', $user->supervisor_id)
                ->with('supervisor')
                ->get();
        }

        $statuses = DissertationStatus::all();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'postgraduate_id' => ['required'],
                'topic' => ['required'],
                'status_id' => ['required']
            ], ['required' => 'Поле :field пусто']);

            if (!$validator->fails()) {
                Dissertation::create([
                    'postgraduate_id' => $request->postgraduate_id,
                    'topic' => $request->topic,
                    'approval_date' => $request->approval_date,
                    'status_id' => $request->status_id,
                    'vak_specialty' => $request->vak_specialty
                ]);
                $message = 'Диссертация успешно добавлена!';
            } else {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            }
        }

        return new View('site.add_dissertation', [
            'message' => $message,
            'postgraduates' => $postgraduates,
            'statuses' => $statuses
        ]);
    }

    public function editDissertation($id, Request $request): string
    {
        $dissertation = Dissertation::with('postgraduate')->find($id);
        $user = app()->auth::user();
        
        if (!$dissertation) {
            app()->route->redirect('/dissertations');
        }
        
        if ($user->role_id != 1 && $dissertation->postgraduate->supervisor_id != $user->supervisor_id) {
            app()->route->redirect('/dissertations');
        }
        
        $message = '';
        $statuses = DissertationStatus::all();
        
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'topic' => ['required'],
                'status_id' => ['required']
            ], ['required' => 'Поле :field пусто']);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $dissertation->topic = $request->topic;
                $dissertation->approval_date = $request->approval_date;
                $dissertation->status_id = $request->status_id;
                $dissertation->vak_specialty = $request->vak_specialty;
                
                if ($dissertation->save()) {
                    $message = 'Диссертация успешно обновлена!';
                } else {
                    $message = 'Ошибка при обновлении диссертации';
                }
            }
        }
        
        return new View('site.edit_dissertation', [
            'message' => $message,
            'dissertation' => $dissertation,
            'statuses' => $statuses
        ]);
    }

    public function deleteDissertation($id): void
    {
        $dissertation = Dissertation::with('postgraduate')->find($id);
        $user = app()->auth::user();
        
        if (!$dissertation) {
            app()->route->redirect('/dissertations');
        }
        
        if ($user->role_id != 1 && $dissertation->postgraduate->supervisor_id != $user->supervisor_id) {
            app()->route->redirect('/dissertations');
        }
        
        $dissertation->delete();
        app()->route->redirect('/dissertations');
    }
}