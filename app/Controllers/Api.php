<?php

namespace Controllers;

use BasicValidators\Validator\Validator;
use Model\Employee;
use Model\Post;
use Model\User;
use Src\Request;
use Src\View;
use Debug\DebugTools;

class Api
{
    public function index(): void
    {
        $posts = Post::all()->toArray();

        (new View())->toJSON($posts);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function login(Request $request): void
    {
        $login = $request->get('login');
        $password = $request->get('password');

        if (!$login || !$password) {
            (new View())->toJSON(['error' => 'login and password required'], 400);
            return;
        }

        $user = User::where('login', $login)
            ->where('password', md5($password))
            ->first();

        if (!$user) {
            (new View())->toJSON(['error' => 'invalid credentials'], 401);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $user->token = $token;
        $user->save();

        (new View())->toJSON(['token' => $token]);
    }

    public function getFinancists(Request $request): void
    {
        (new View())->toJSON([
            'data' => User::whereHas(
                'role',
                function ($query) {
                    $query->where('name', 'financist');
                })->get()
        ]);
    }
    public function deleteFinancist(Request $request): void
    {
        $id = $request->get('id');
        $user = User::find($id);
        if (!$user) {
            (new View())->toJSON(['error' => 'User not found'], 404);
        }
        User::destroy($id);
        (new View())->toJSON([
            'message' => 'Delete was successful'
        ]);
    }

    public function editFinancist(Request $request): void
    {
        $id = $request->get('id');
        $user = User::find($id);
        if (!$user) {
            (new View())->toJSON(['error' => 'User not found'], 404);
        }
        $errors = [];
        $validator = new Validator($request->all(), [
            'login' => ['required', 'unique:users,login'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            (new View())->toJSON(['errors' => $errors], 401);
        }
        else {
            $financist = User::find($id);
            $financist->login = $request->get('login');
            $financist->save();
            (new View())->toJSON(['message' =>'Edit was successful']);
        }
    }

    public function addFinancist(Request $request): void
    {
        $errors = [];
        $validator = new Validator($request->all(), [
            'login' => ['required', 'unique:users,login'],
            'password' => ['required'],
            'employee_id' => ['required', 'unique:users,employee_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            (new View())->toJSON(['errors' => $errors], 401);
        }
        elseif (User::create(array_merge($request->all(), ['role_id' => 2]))) {
            (new View())->toJSON(['message' =>'Created successfully'], 201);
        }
    }
}