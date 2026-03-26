<?php

namespace Controllers;

use Debug\DebugTools;
use Model\Employee;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function index(Request $request): string
    {
        $fio = Employee::findIdentity(Auth::user()->employee_id)->getFullName();
        return (new View())->render('site.hello', ['login' => $fio]);
    }

    public function admin(Request $request): string
    {
        $message = '';
        if ($request->method === 'POST' && User::create(array_merge($request->all(), ['role_id' => 2]))) {
            $message = 'Пользователь успешно создан';
        }

        $financists = User::whereHas('role', function ($query) {
            $query->where('name', 'financist');
        })->get();
        $freeEmployees = Employee::all();
        return (new View())->render('site.admin', [
            'financists' => $financists,
            'employees' => $freeEmployees,
            'message' => $message
        ]);
    }

    public function financist(Request $request): string
    {
        return (new View())->render('site.financist');
    }

    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('login');
    }
}