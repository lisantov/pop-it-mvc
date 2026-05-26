<?php

namespace Src\Auth;

use Debug\DebugTools;
use Src\Session;

class Auth
{
    //Свойство для хранения любого класса, реализующего интерфейс IdentityInterface
    private static IdentityInterface $user;

    //Токен-аутентифицированный пользователь (для API)
    private static ?IdentityInterface $tokenUser = null;

    //Инициализация класса пользователя
    public static function init(IdentityInterface $user): void
    {
        self::$user = $user;
        if (self::user()) {
            self::login(self::user());
        }
    }

    //Вход пользователя по модели
    public static function login(IdentityInterface $user): void
    {
        self::$user = $user;
        Session::set('id', self::$user->getId());
    }

    //Аутентификация пользователя и вход по учетным данным
    public static function attempt(array $credentials): bool
    {
        if ($user = self::$user->attemptIdentity($credentials)) {
            self::login($user);
            return true;
        }
        return false;
    }

    //Возврат текущего аутентифицированного пользователя
    public static function user()
    {
        if (self::$tokenUser) {
            return self::$tokenUser;
        }
        $id = Session::get('id') ?? 0;
        return self::$user->findIdentity($id);
    }

    //Аутентификация по Bearer токену
    public static function attemptByToken(string $token): bool
    {
        $user = self::$user->findIdentityByToken($token);
        if ($user) {
            self::$tokenUser = $user;
            return true;
        }
        return false;
    }

    public static function generateCSRF(): string
    {
        $token = md5(time());
        Session::set('csrf_token', $token);
        return $token;
    }

    //Проверка является ли текущий пользователь аутентифицированным
    public static function check(): bool
    {
        if (self::user()) {
            return true;
        }
        return false;
    }

    //Выход текущего пользователя
    public static function logout(): bool
    {
        Session::clear('id');
        self::$tokenUser = null;
        return true;
    }

}