<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Model\Edition;
use Model\IndexType;
use Model\Publication;
use Src\Validator\Validator;
use Model\Postgraduate;
use Model\Dissertation;
use Model\DissertationStatus;

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
     
    public function deleteUser($id): void
    {
        $this->checkAdmin();
        
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
                'date_to' => ['required'],
            ], [
                'required' => 'Поле :field пусто'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                // Получаем защищённые диссертации за период
                $dissertations = Dissertation::with(['postgraduate', 'status'])
                    ->whereHas('status', function($query) {
                        $query->where('name', 'защищена');
                    })
                    ->whereBetween('approval_date', [$dateFrom, $dateTo])
                    ->get();
                
                $totalDefenses = $dissertations->count();
                
                // Группировка по научным руководителям
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
        $user = app()->auth::user();
        $message = '';
        
        if ($user->role_id == 1) {
            $staff = Staff::all();
        } else {

            $staff = Staff::where('supervisor_id', $user->supervisor_id)->get();
        }
        
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
            'indexTypes' => $indexTypes,
            'user' => $user 
        ]);
    }

    public function publications(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        $publications = Publication::with(['staff', 'edition', 'indexType'])->get();
        
        return new View('site.publications', [
            'isAdmin' => $isAdmin,
            'user' => $user,
            'publications' => $publications
        ]);
    }

    public function editPublication($id, Request $request): string
    {
        $user = app()->auth::user();
        $publication = Publication::find($id);
        
        if (!$publication) {
            app()->route->redirect('/publications');
        }
        
        if ($user->role_id != 1 && $publication->staff_id != $user->supervisor_id) {
            app()->route->redirect('/publications');
        }
        
        $message = '';
        $staff = Staff::all();
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
                $publication->title = $request->title;
                $publication->publication_date = $request->publication_date;
                $publication->staff_id = $request->staff_id;
                $publication->edition_id = $request->edition_id;
                $publication->index_type_id = $request->index_type_id;
                
                if ($publication->save()) {
                    $message = 'Публикация успешно обновлена!';
                } else {
                    $message = 'Ошибка при обновлении публикации';
                }
            }
        }
        
        return new View('site.edit_publication', [
            'message' => $message,
            'publication' => $publication,
            'staff' => $staff,
            'editions' => $editions,
            'indexTypes' => $indexTypes
        ]);
    }

    public function deletePublication($id): void
    {
        $user = app()->auth::user();
        $publication = Publication::find($id);
        
        if (!$publication) {
            app()->route->redirect('/publications');
        }
        
        if ($user->role_id != 1 && $publication->staff_id != $user->supervisor_id) {
            app()->route->redirect('/publications');
        }
        
        $publication->delete();
        app()->route->redirect('/publications');
    }

    // ========== АСПИРАНТЫ ==========

    public function addPostgraduate(Request $request): string
    {
        $user = app()->auth::user();  // ← ЭТА СТРОКА УЖЕ ЕСТЬ
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
            'user' => $user  // ← ДОБАВЬТЕ ЭТУ СТРОКУ
        ]);
    }

    public function postgraduates(Request $request): string
    {
        $user = app()->auth::user();
        $isAdmin = $user->role_id == 1;
        
        // Получаем всех научных руководителей для фильтра
        $supervisors = Staff::all();
        
        // Параметр фильтрации
        $searchSupervisorId = $request->get('supervisor_id') ?? '';
        
        // Запрос на получение аспирантов
        $query = Postgraduate::with('supervisor');
        
        // Фильтр по научному руководителю
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

    // ========== ДИССЕРТАЦИИ ==========

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