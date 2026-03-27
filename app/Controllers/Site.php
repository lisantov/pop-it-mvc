<?php

namespace Controllers;

use Debug\DebugTools;
use Model\Employee;
use Model\User;
use Src\Validator\Validator;
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
        $financists = User::whereHas('role', function ($query) {
            $query->where('name', 'financist');
        })->get();

        $freeEmployees = Employee::whereNotIn('id', array_map(function ($item) {
            return ($item['employee_id']);
        }, User::all()->toArray()))->get();

        $message = '';
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'login' => ['required', 'unique:users,login'],
                'password' => ['required'],
                'employee_id' => ['required', 'unique:users,employee_id'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            elseif (User::create(array_merge($request->all(), ['role_id' => 2]))) {
                $message = 'Пользователь успешно создан';
            }
        }

        return (new View())->render('site.admin', [
            'financists' => $financists,
            'employees' => $freeEmployees,
            'message' => $message,
            'errors' => $errors
        ]);
    }

    public function deleteFinancist(Request $request): string
    {
        if($request->method === 'POST') {
            User::destroy($request->get('id'));
            app()->route->redirect('admin');
        }
        return (new View())->render('site.confirmDelete', [
            'name' => 'бухгалтера',
            'target' => Employee::findIdentity(User::find($request->get('id'))->employee_id)->getFullName(),
            'rollback' => app()->route->getUrl('admin')
        ]);
    }

    public function editFinancist(Request $request): string
    {
        $user_id = $request->get('id');
        if($request->method === 'POST') {
            $financist = User::find($user_id);
            $financist->login = $request->get('login');
            $financist->save();
            app()->route->redirect('admin');
        }
        return (new View())->render('site.updateFinancist', [
            'target' => Employee::find(User::find($user_id)->employee_id)->getFullName(),
            'id' => $user_id
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