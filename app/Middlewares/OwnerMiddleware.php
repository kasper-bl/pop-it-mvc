<?php

namespace Middlewares;

use Src\Request;
use Model\Publication;

class OwnerMiddleware
{
    public function handle(Request $request, string $type, string $idField = 'id'): Request
    {
        $user = app()->auth::user();
        
        // Админ может всё
        if ($user->role_id == 1) {
            return $request;
        }
        
        $id = $request->get($idField) ?? $request->all()[$idField] ?? null;
        
        if (!$id) {
            // Попробуем получить из URL
            $uri = $_SERVER['REQUEST_URI'];
            preg_match('/(?:edit|delete)-publication\/(\d+)/', $uri, $matches);
            if (isset($matches[1])) {
                $id = $matches[1];
            }
        }
        
        if ($type === 'publication' && $id) {
            $publication = Publication::find($id);
            if ($publication && $publication->staff_id != $user->supervisor_id) {
                app()->route->redirect('/publications');
            }
        }
        
        return $request;
    }
}