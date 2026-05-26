<?php

namespace Controllers;

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
}