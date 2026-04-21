<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;
use Model\Edition;
use Model\IndexType;
use Model\Publication;
use Src\Validator\Validator;

class PublicationController
{
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
                'publication_date' => ['required', 'year'],
                'staff_id' => ['required'],
                'edition_id' => ['required'],
                'index_type_id' => ['required']
            ], [
                'required' => 'Поле :field пусто',
                'year' => 'Год публикации не может быть в будущем'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $fileError = null;
                $imagePath = null;
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $fileValidator = new Validator(['image' => $_FILES['image']], [
                        'image' => ['file']
                    ], [
                        'file' => 'Файл должен быть изображением (JPG, PNG, GIF, WEBP)'
                    ]);
                    
                    if ($fileValidator->fails()) {
                        $fileError = 'Ошибки валидации: ' . json_encode($fileValidator->errors(), JSON_UNESCAPED_UNICODE);
                    } else {
                        $uploadDir = __DIR__ . '/../../public/uploads/publications/';
                        
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = time() . '_' . uniqid() . '.' . $ext;
                        $uploadFile = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                            $imagePath = '/uploads/publications/' . $filename;
                        } else {
                            $fileError = 'Ошибка при загрузке изображения';
                        }
                    }
                }
                
                if ($fileError) {
                    $message = $fileError;
                } else {
                    $publicationData = [
                        'title' => $request->title,
                        'publication_date' => $request->publication_date,
                        'staff_id' => $request->staff_id,
                        'edition_id' => $request->edition_id,
                        'index_type_id' => $request->index_type_id,
                        'image_path' => $imagePath
                    ];
                    
                    if (Publication::create($publicationData)) {
                        $message = 'Публикация успешно добавлена!';
                    } else {
                        $message = 'Ошибка при добавлении публикации';
                    }
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
                'publication_date' => ['required', 'year'],
                'staff_id' => ['required'],
                'edition_id' => ['required'],
                'index_type_id' => ['required']
            ], [
                'required' => 'Поле :field пусто',
                'year' => 'Год публикации не может быть в будущем'
            ]);
            
            if ($validator->fails()) {
                $message = 'Ошибки валидации: ' . json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            } else {
                $publication->title = $request->title;
                $publication->publication_date = $request->publication_date;
                $publication->staff_id = $request->staff_id;
                $publication->edition_id = $request->edition_id;
                $publication->index_type_id = $request->index_type_id;
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $fileValidator = new Validator(['image' => $_FILES['image']], [
                        'image' => ['file']
                    ], [
                        'file' => 'Файл должен быть изображением (JPG, PNG, GIF, WEBP)'
                    ]);
                    
                    if ($fileValidator->fails()) {
                        $message = 'Ошибки валидации: ' . json_encode($fileValidator->errors(), JSON_UNESCAPED_UNICODE);
                    } else {
                        $uploadDir = __DIR__ . '/../../public/uploads/publications/';
                        
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        if ($publication->image_path && file_exists(__DIR__ . '/../..' . $publication->image_path)) {
                            unlink(__DIR__ . '/../..' . $publication->image_path);
                        }
                        
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = time() . '_' . uniqid() . '.' . $ext;
                        $uploadFile = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                            $publication->image_path = '/uploads/publications/' . $filename;
                        } else {
                            $message = 'Ошибка при загрузке изображения';
                        }
                    }
                }
                
                if (empty($message) && $publication->save()) {
                    $message = 'Публикация успешно обновлена!';
                } elseif (empty($message)) {
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
}