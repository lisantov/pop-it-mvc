<?php

namespace Middlewares;

use Debug\DebugTools;
use Src\Auth\Auth;
use Src\Request;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        $header = $request->headers['Authorization'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);
            if (Auth::attemptByToken($token)) {
                return;
            }
            (new \Src\View())->toJSON(['error' => 'unauthorized'], 401);
        }

        if (!Auth::check()) {
            if (str_starts_with($_SERVER['REQUEST_URI'], '/api')) {
                (new \Src\View())->toJSON(['error' => 'unauthorized'], 401);
            }
            app()->route->redirect('login');
            exit;
        }
    }
}