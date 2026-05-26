<?php

namespace Middlewares;

use Debug\DebugTools;
use Src\Auth\Auth;
use Src\Request;

class RoleMiddleware
{
    public function handle(Request $request, string $role)
    {
        if (!Auth::user() || !Auth::user()->hasRole($role)) {
            $header = $request->headers['Authorization'] ?? '';
            if (str_starts_with($header, 'Bearer ')) {
                (new \Src\View())->toJSON(['error' => 'forbidden'], 403);
            }
            app()->route->redirect('');
        }
    }
}