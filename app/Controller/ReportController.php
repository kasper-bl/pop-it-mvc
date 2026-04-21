<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Model\Postgraduate;
use Model\Dissertation;
use Src\Validator\Validator;

class ReportController
{
    public function reports(Request $request): string
    {
        $user = app()->auth::user();
        
        $message = '';
        $reportData = [];
        $totalDefenses = 0;
        $dateFrom = '';
        $dateTo = '';
        
        if ($request->method === 'POST') {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            
            $validator = new Validator($request->all(), [
                'date_from' => ['required'],
                'date_to' => ['required', 'date_range:' . $dateFrom]
            ], [
                'required' => 'Поле :field пусто',
                'date_range' => 'Дата "до" не может быть раньше даты "от"'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $dissertations = Dissertation::with(['postgraduate', 'status'])
                    ->whereHas('status', function($query) {
                        $query->where('name', 'защищена');
                    })
                    ->whereBetween('approval_date', [$dateFrom, $dateTo])
                    ->get();
                
                $totalDefenses = $dissertations->count();
                
                $defensesBySupervisor = [];
                foreach ($dissertations as $dissertation) {
                    $supervisor = $dissertation->postgraduate->supervisor;
                    if ($supervisor) {
                        $key = $supervisor->supervisor_id;
                        if (!isset($defensesBySupervisor[$key])) {
                            $defensesBySupervisor[$key] = [
                                'name' => $supervisor->surname . ' ' . $supervisor->name . ' ' . $supervisor->patronymic,
                                'count' => 0,
                                'dissertations' => []
                            ];
                        }
                        $defensesBySupervisor[$key]['count']++;
                        $defensesBySupervisor[$key]['dissertations'][] = [
                            'topic' => $dissertation->topic,
                            'postgraduate' => $dissertation->postgraduate->surname . ' ' . $dissertation->postgraduate->name,
                            'approval_date' => $dissertation->approval_date,
                            'vak_specialty' => $dissertation->vak_specialty
                        ];
                    }
                }
                
                $reportData = [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'total_defenses' => $totalDefenses,
                    'by_supervisor' => $defensesBySupervisor,
                    'dissertations' => $dissertations
                ];
                
                if ($totalDefenses == 0) {
                    $message = 'За указанный период защит не найдено.';
                }
            }
        }
        
        return new View('site.reports', [
            'message' => $message,
            'reportData' => $reportData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totalDefenses' => $totalDefenses,
            'user' => $user
        ]);
    }

    public function search(Request $request): string
    {
        $user = app()->auth::user();
        
        if ($user->role_id == 1) {
            app()->route->redirect('/dashboard');
        }
        
        $message = '';
        $postgraduates = [];
        $searchSupervisorId = '';
        
        $supervisors = Staff::all();
        
        if ($request->method === 'POST') {
            $searchSupervisorId = $request->supervisor_id;
            
            if (empty($searchSupervisorId)) {
                $message = 'Пожалуйста, выберите научного руководителя';
            } else {
                $supervisor = Staff::find($searchSupervisorId);
                if (!$supervisor) {
                    $message = 'Выбранный научный руководитель не найден';
                } else {
                    $postgraduates = Postgraduate::with(['supervisor', 'dissertation.status'])
                        ->where('supervisor_id', $searchSupervisorId)
                        ->get();
                    
                    if ($postgraduates->isEmpty()) {
                        $message = 'У выбранного научного руководителя нет аспирантов';
                    }
                }
            }
        }
        
        return new View('site.search', [
            'message' => $message,
            'supervisors' => $supervisors,
            'postgraduates' => $postgraduates,
            'searchSupervisorId' => $searchSupervisorId,
            'user' => $user
        ]);
    }
}