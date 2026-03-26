<?php

namespace Middlewares;

use Debug\DebugTools;
use Src\Auth\Auth;
use Src\Request;

class RoleMiddleware
{
    public function handle(Request $request, string $role)
    {
        //Если пользователь не имеет нужной роли, то редирект на главную
        if (!Auth::user()->hasRole($role)) {
            app()->route->redirect('');
        }
    }
}