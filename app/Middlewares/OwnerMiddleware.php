<?php

namespace Middlewares;

use Src\Request;
use Model\Publication;
use Model\Postgraduate;
use Model\Dissertation;

class OwnerMiddleware
{
    public function handle(Request $request, string $type, string $idField = 'id'): Request
    {
        $user = app()->auth::user();
        
        if ($user->role_id == 1) {
            return $request;
        }
        
        $id = $request->get($idField) ?? $request->all()[$idField] ?? null;
        
        if (!$id) {
            $uri = $_SERVER['REQUEST_URI'];
            
            if ($type === 'publication') {
                preg_match('/(?:edit|delete)-publication\/(\d+)/', $uri, $matches);
            } elseif ($type === 'postgraduate') {
                preg_match('/(?:edit|delete)-postgraduate\/(\d+)/', $uri, $matches);
            } elseif ($type === 'dissertation') {
                preg_match('/(?:edit|delete)-dissertation\/(\d+)/', $uri, $matches);
            }
            
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
        
        if ($type === 'postgraduate' && $id) {
            $postgraduate = Postgraduate::find($id);
            if ($postgraduate && $postgraduate->supervisor_id != $user->supervisor_id) {
                app()->route->redirect('/postgraduates');
            }
        }
        
        if ($type === 'dissertation' && $id) {
            $dissertation = Dissertation::with('postgraduate')->find($id);
            if ($dissertation && $dissertation->postgraduate->supervisor_id != $user->supervisor_id) {
                app()->route->redirect('/dissertations');
            }
        }
        
        return $request;
    }
}